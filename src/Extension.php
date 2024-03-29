<?php declare(strict_types=1);

namespace Circli\WebCore;

use Circli\Contracts\ExtensionInterface;
use Circli\Contracts\PathContainer;
use Circli\WebCore\Exception\ExceptionHandler as DefaultExceptionHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Polus\Adr\ActionDispatcher\HandlerActionDispatcher;
use Polus\Adr\ActionDispatcher\MiddlewareActionDispatcher;
use Polus\Adr\ActionHandler\EventActionHandler;
use Polus\Adr\Interfaces\ActionDispatcher;
use Polus\Adr\Interfaces\ExceptionHandler;
use Polus\Adr\Interfaces\Resolver;
use Polus\Adr\Interfaces\ResponseHandler;
use Polus\MiddlewareDispatcher\Factory as MiddlewareDispatcherFactory;
use Polus\Router\RouterCollection;
use Polus\Router\RouterDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use function DI\autowire;
use function DI\create;
use function DI\get;
use FastRoute\DataGenerator\GroupCountBased as DataGeneratorGroupCountBased;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Polus\Adr\ResponseHandler\HttpResponseHandler;
use Polus\MiddlewareDispatcher\DispatcherInterface as MiddlewareDispatcherInterface;
use Polus\MiddlewareDispatcher\Relay\Dispatcher as RelayDispatcher;
use Polus\Router\FastRoute\Dispatcher as FastRouteDispatcher;
use Polus\Router\FastRoute\RouterCollection as FastRouterCollection;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;

class Extension implements ExtensionInterface
{
    /**
     * @return array<string, mixed>
     */
    public function configure(PathContainer $paths = null): array
    {
        return [
            'adr.relay_resolver' => function (ContainerInterface $container) {
                return function ($middleware) use ($container) {
                    if ($middleware instanceof MiddlewareInterface) {
                        return $middleware;
                    }

                    return $container->get($middleware);
                };
            },
            ResponseHandler::class => autowire(HttpResponseHandler::class),
            ResponseFactoryInterface::class => autowire(Psr17Factory::class),
            RequestFactoryInterface::class => create(Psr17Factory::class),
            MiddlewareDispatcherInterface::class => create(RelayDispatcher::class)->constructor(
                get(ResponseFactoryInterface::class),
                get('adr.relay_resolver')
            ),
            RouteCollector::class => create(RouteCollector::class)->constructor(
                get(Std::class),
                get(DataGeneratorGroupCountBased::class)
            ),
            RouterCollection::class => autowire(FastRouterCollection::class),
            RouterDispatcher::class => function (ContainerInterface $container) {
                return new FastRouteDispatcher(
                    GroupCountBased::class,
                    $container->get(RouteCollector::class)
                );
            },
            Resolver::class => function (ContainerInterface $container) {
                return new ActionResolver($container);
            },
            ActionDispatcher::class => static function (ContainerInterface $container) {
                $resolver = $container->get(Resolver::class);
                $defaultDispatcher = HandlerActionDispatcher::default(
                    $resolver,
                    $container->get(ResponseFactoryInterface::class),
                );
                if ($container->has(ExceptionHandler::class)) {
                    $defaultDispatcher->setExceptionHandler($container->get(ExceptionHandler::class));
                }
                else {
                    $defaultDispatcher->setExceptionHandler(new DefaultExceptionHandler());
                }

                $defaultDispatcher->addHandler(new EventActionHandler(
                    $resolver,
                    $container->get(EventDispatcherInterface::class)
                ));
                return new MiddlewareActionDispatcher(
                    $defaultDispatcher,
                    new MiddlewareDispatcherFactory($container->get(MiddlewareDispatcherInterface::class), $container),
                );
            },
        ];
    }
}
