<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Polus\Router\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PostRouteDispatch
{
    /** @var RouteInterface */
    private $route;
    /** @var ServerRequestInterface */
    private $request;

    public function __construct(RouteInterface $route, ServerRequestInterface $request)
    {
        $this->route = $route;
        $this->request = $request;
    }

    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): void
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
