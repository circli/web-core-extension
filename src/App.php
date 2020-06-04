<?php declare(strict_types=1);

namespace Circli\WebCore;

use Circli\Contracts\InitAdrApplication;
use Circli\Core\Environment;
use Circli\Core\Events\InitModule;
use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use Circli\WebCore\Events\Listeners\CollectAdrModules;
use Circli\WebCore\Events\MiddlewareBuildEvent;
use Circli\WebCore\Middleware\Container as MiddlewareContainer;
use Circli\WebCore\Middleware\RouterMiddleware;
use Polus\Adr\ActionDispatcherFactory;
use Polus\Adr\Interfaces\ResolverInterface;
use Polus\Adr\Interfaces\ResponseHandlerInterface;
use Polus\MiddlewareDispatcher\DispatcherInterface as MiddlewareDispatcherInterface;
use Polus\MiddlewareDispatcher\Factory as MiddlewareDispatcherFactory;
use Polus\Router\RouterCollectionInterface;
use Polus\Router\RouterDispatcherInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Laminas\Diactoros\ServerRequestFactory;

abstract class App
{
    /** @var Adr */
    protected $adr;
    /** @var ContainerInterface */
    protected $container;
    /** @var Container */
    protected $containerBuilder;
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
	/** @var CollectAdrModules */
	private $adrModuleCollector;

	public function __construct(Environment $mode, string $containerClass = Container::class, string $basePath = null)
    {
        $this->containerBuilder = new $containerClass($mode, $basePath ?? \dirname(__DIR__, 3));
        $this->eventDispatcher = $this->containerBuilder->getEventDispatcher();
        $this->adrModuleCollector = new CollectAdrModules();
        $this->containerBuilder->getEventListenerProvider()->addProvider($this->adrModuleCollector);
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

        $middlewares->insert(new RouterMiddleware(
            $this->container->get(RouterDispatcherInterface::class),
            $this->eventDispatcher
        ), 1000);

		$this->eventDispatcher->dispatch(new MiddlewareBuildEvent($middlewares));

        $this->adr = new Adr(
            $this->container->get(ResponseFactoryInterface::class),
            $this->container->get(ResolverInterface::class),
            $this->container->get(RouterCollectionInterface::class),
            $this->container->get(ResponseHandlerInterface::class),
            new MiddlewareDispatcherFactory($this->container->get(MiddlewareDispatcherInterface::class), $middlewares),
			$this->eventDispatcher,
            $this->container->get(ActionDispatcherFactory::class)
        );

        if (\count($this->adrModuleCollector)) {
            foreach ($this->adrModuleCollector as $module) {
                $module->initAdr($this->adr, $this->container);
            }
        }
    }

    public function run(): ResponseHandlerInterface
    {
        return $this->adr->run(ServerRequestFactory::fromGlobals());
    }
}
