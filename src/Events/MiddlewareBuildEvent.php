<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Circli\WebCore\Middleware\Container as MiddlewareContainer;

final class MiddlewareBuildEvent
{
    private MiddlewareContainer $middlewareContainer;

    public function __construct(MiddlewareContainer $middlewareContainer)
    {
        $this->middlewareContainer = $middlewareContainer;
    }

    public function getMiddlewareContainer(): MiddlewareContainer
    {
        return $this->middlewareContainer;
    }
}
