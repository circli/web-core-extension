<?php declare(strict_types=1);

namespace Circli\WebCore\Middleware;

use Circli\WebCore\Common\Actions\NotFoundActionInterface;
use Circli\WebCore\Routes\WrapperRoute;
use Polus\Adr\Interfaces\Action;
use Polus\Router\Route;
use Polus\Router\RouterDispatcher;
use Polus\Router\RouterMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NotFoundHandler implements MiddlewareInterface
{
    public function __construct(
        private NotFoundActionInterface $notFoundAction,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $reDispatch = clone $handler;
        $route = $request->getAttribute(RouterMiddleware::ATTRIBUTE_KEY);
        if ($route instanceof Route && $route->getStatus() === RouterDispatcher::NOT_FOUND) {
            return $this->dispatchNotFound($request, $handler, $route);
        }

        $response = $handler->handle($request);
        if ($response->getStatusCode() === 404) {
            return $this->dispatchNotFound($request, $reDispatch, $route);
        }
        return $response;
    }

    private function dispatchNotFound(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        Route $route
    ): ResponseInterface {
        return $handler->handle($request->withAttribute(RouterMiddleware::ATTRIBUTE_KEY, new WrapperRoute(
            $route,
            handler: $this->notFoundAction,
            status: RouterDispatcher::FOUND,
        )))->withStatus(404);
    }
}
