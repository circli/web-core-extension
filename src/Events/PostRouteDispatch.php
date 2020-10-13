<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Polus\Router\Route;
use Psr\Http\Message\ServerRequestInterface;

final class PostRouteDispatch
{
    private Route $route;
    private ServerRequestInterface $request;

    public function __construct(Route $route, ServerRequestInterface $request)
    {
        $this->route = $route;
        $this->request = $request;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }
}
