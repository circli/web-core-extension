<?php declare(strict_types=1);

namespace Circli\WebCore\Middleware;

use Circli\WebCore\Events\PostRouteDispatch;
use Circli\WebCore\Events\PreRouteDispatch;
use Polus\Router\RouterDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RouterDispatcher $routerDispatcher,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = rtrim($request->getUri()->getPath(), '/');
        $request = $request->withUri($request->getUri()->withPath($path ?: '/'));

        $request = $this->eventDispatcher->dispatch(new PreRouteDispatch($request))->getRequest();
        $route = $this->routerDispatcher->dispatch($request);
        if (count($route->getAttributes())) {
            foreach ($route->getAttributes() as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }
        }
        $event = $this->eventDispatcher->dispatch(new PostRouteDispatch($route, $request));
        $request = $event->getRequest();
        $request = $request->withAttribute(\Polus\Router\RouterMiddleware::ATTRIBUTE_KEY, $event->getRoute());

        return $handler->handle($request);
    }
}
