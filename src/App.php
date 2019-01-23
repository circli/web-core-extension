<?php declare(strict_types=1);

namespace Circli\WebCore;

use Circli\Contracts\InitAdrApplication;
use Circli\Core\Environment;
use Circli\Core\Events\InitModule;
use Circli\WebCore\Events\MiddlewareBuildEvent;
use Circli\WebCore\Middleware\Container as MiddlewareContainer;
use Circli\EventDispatcher\EventDispatcher;
use Polus\Adr\Adr;
use Polus\Adr\Interfaces\ResponseHandlerInterface;
use Polus\Adr\ResponseHandler\HttpResponseHandler;
use Polus\MiddlewareDispatcher\DispatcherInterface as MiddlewareDispatcherInterface;
use Polus\MiddlewareDispatcher\Factory as MiddlewareDispatcherFactory;
use Polus\Router\RouterCollectionInterface;
use Polus\Router\RouterDispatcherInterface;
use Polus\Router\RouterMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

abstract class App
{
    /** @var Adr */
    protected $adr;
    /** @var ContainerInterface */
    protected $container;
    /** @var Container */
    protected $containerBuilder;
    /** @var \Circli\EventDispatcher\EventDispatcherInterface */
    protected $eventDispatcher;
    /** @var InitAdrApplication[] */
    protected $modules = [];

    public function __construct(Environment $mode, string $containerClass = Container::class)
    {
        $this->containerBuilder = new $containerClass($mode, \dirname(__DIR__, 2));
        $this->eventDispatcher = $this->containerBuilder->getEventDispatcher();
        $this->eventDispatcher->listen(InitModule::class, function (InitModule $event) {
            $this->modules[] = $event->getModule();
        });
        $this->container = $this->containerBuilder->build();
        $this->initAdr();
    }

    protected function initAdr(): void
    {
        $middlewares = new MiddlewareContainer((array)$this->container->get('middlewares'));
        $middlewares->insert(new RouterMiddleware($this->container->get(RouterDispatcherInterface::class)), 1000);

        $eventManager = $this->container->get(EventDispatcher::class);
        $eventManager->trigger(new MiddlewareBuildEvent($middlewares));

        $this->adr = new Adr(
            $this->container->get(ResponseFactoryInterface::class),
            new ActionResolver($this->container),
            $this->container->get(RouterCollectionInterface::class),
            new HttpResponseHandler(),
            new MiddlewareDispatcherFactory($this->container->get(MiddlewareDispatcherInterface::class), $middlewares)
        );

        if (count($this->modules)) {
            foreach ($this->modules as $module) {
                $module->initAdr($this->adr);
            }
        }
    }

    public function run(): ResponseHandlerInterface
    {
        return $this->adr->run(\Zend\Diactoros\ServerRequestFactory::fromGlobals());
    }
}
