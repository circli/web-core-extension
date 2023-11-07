<?php declare(strict_types=1);

namespace Circli\WebCore;

use Circli\Contracts\InitHttpApplication;
use Circli\Contracts\ModuleInterface;
use Circli\Core\Environment;
use Circli\WebCore\Events\Listeners\CollectAdrModules;
use Circli\WebCore\Events\MiddlewareBuildEvent;
use Circli\WebCore\Middleware\Container as MiddlewareContainer;
use Circli\WebCore\Middleware\RouterMiddleware;
use Polus\Adr\Interfaces\ActionDispatcher;
use Polus\Adr\Interfaces\Resolver;
use Polus\Adr\Interfaces\ResponseHandler;
use Polus\MiddlewareDispatcher\DispatcherInterface as MiddlewareDispatcherInterface;
use Polus\MiddlewareDispatcher\Factory as MiddlewareDispatcherFactory;
use Polus\Router\RouterCollection;
use Polus\Router\RouterDispatcher;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class App implements RequestHandlerInterface
{
    protected Adr $adr;
    protected ContainerInterface $container;
    protected CollectAdrModules $adrModules;
    protected ContainerBuilder $containerBuilder;
    protected EventDispatcherInterface $eventDispatcher;
    /** @var ModuleInterface[] */
    protected array $modules = [];

    public function __construct(Environment $mode, string $containerClass = ContainerBuilder::class, string $basePath = null)
    {
        $this->containerBuilder = new $containerClass($mode, $basePath ?? \dirname(__DIR__, 3));
        $this->eventDispatcher = $this->containerBuilder->getEventDispatcher();
        $this->adrModules = new CollectAdrModules();
        $this->containerBuilder->getEventListenerProvider()->addProvider($this->adrModules);
        $this->container = $this->containerBuilder->build();
        $this->initAdr();
    }

    protected function initAdr(): void
    {
        $rawMiddlewares = $this->container->get('middlewares');
        if ($rawMiddlewares instanceof MiddlewareContainer) {
            $middlewares = $rawMiddlewares;
            $rawMiddlewares = null;
        }
        else {
            $middlewares = new MiddlewareContainer((array)$rawMiddlewares);
        }
        $eventDispatcher = $this->container->get(EventDispatcherInterface::class);

        $middlewares->insert(new RouterMiddleware(
            $this->container->get(RouterDispatcher::class),
            $eventDispatcher
        ), 1000);

        $eventDispatcher->dispatch(new MiddlewareBuildEvent($middlewares));

        $this->adr = new Adr(
            $this->container->get(ResponseFactoryInterface::class),
            $this->container->get(Resolver::class),
            $this->container->get(RouterCollection::class),
            $this->container->get(ResponseHandler::class),
            new MiddlewareDispatcherFactory($this->container->get(MiddlewareDispatcherInterface::class), $middlewares),
            $eventDispatcher,
            $this->container->get(ActionDispatcher::class)
        );

        foreach ($this->adrModules as $module) {
            if ($module instanceof InitHttpApplication) {
                $module->initHttp($this->adr, $this->container);
            }
        }
    }

    public function run(): ResponseHandler
    {
        $marshal = new ServerRequestMarshal();
        return $this->adr->run($marshal->marshal($_SERVER));
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->adr->handle($request);
    }
}
