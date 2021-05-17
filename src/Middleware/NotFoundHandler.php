<?php declare(strict_types=1);

namespace Circli\WebCore\Middleware;

use Circli\WebCore\Common\Actions\NotFoundActionInterface;
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
        $newRoute = new class($this->notFoundAction, $route) implements Route {
            public function __construct(
                private Action $action,
                private Route $route
            ) {}

            public function getStatus(): int
            {
                return RouterDispatcher::FOUND;
            }

            /**
             * @return string[]
             */
            public function getAllows(): array
            {
                return $this->route->getAllows();
            }

            public function getHandler(): Action
            {
                return $this->action;
            }

            public function getMethod(): string
            {
                return $this->route->getMethod();
            }

            /**
             * @return array<string, mixed>
             */
            public function getAttributes(): array
            {
                return $this->route->getAttributes();
            }
        };

        $response = $handler->handle($request->withAttribute(RouterMiddleware::ATTRIBUTE_KEY, $newRoute));
        return $response->withStatus(404);
    }
}
