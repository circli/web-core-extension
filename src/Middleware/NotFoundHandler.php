<?php declare(strict_types=1);

namespace Circli\WebCore\Middleware;

use Circli\WebCore\Common\Actions\NotFoundActionInterface;
use Polus\Adr\Interfaces\Action;
use Polus\Router\Route;
use Polus\Router\RouterDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NotFoundHandler implements MiddlewareInterface
{
    private NotFoundActionInterface $notFoundAction;

    public function __construct(NotFoundActionInterface $notFoundAction)
    {
        $this->notFoundAction = $notFoundAction;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $reDispatch = clone $handler;
        $route = $request->getAttribute('route');
        if ($route instanceof Route && $route->getStatus() === RouterDispatcher::NOT_FOUND) {
            return $this->dispatchNotFound($request, $handler, $route);
        }

        $response = $handler->handle($request);
        if ($response->getStatusCode() === 404) {
            return $this->dispatchNotFound($request, $reDispatch, $route);
        }
        return $response;
    }


    private function dispatchNotFound(ServerRequestInterface $request, RequestHandlerInterface $handler, Route $route)
    {
        $newRoute = new class($this->notFoundAction, $route) implements Route {
            private Action $action;
            private Route $route;

            public function __construct(Action $action, Route $route)
            {
                $this->action = $action;
                $this->route = $route;
            }

            public function getStatus(): int
            {
                return RouterDispatcher::FOUND;
            }

            public function getAllows(): array
            {
                return $this->route->getAllows();
            }

            public function getHandler()
            {
                return $this->action;
            }

            public function getMethod()
            {
                return $this->route->getMethod();
            }

            public function getAttributes(): array
            {
                return $this->route->getAttributes();
            }
        };

        $response = $handler->handle($request->withAttribute('route', $newRoute));
        return $response->withStatus(404);
    }
}
