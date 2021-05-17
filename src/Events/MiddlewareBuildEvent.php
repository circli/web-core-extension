<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Circli\WebCore\Middleware\Container as MiddlewareContainer;

final class MiddlewareBuildEvent
{
    public function __construct(
        private MiddlewareContainer $middlewareContainer,
    ) {}

    public function getMiddlewareContainer(): MiddlewareContainer
    {
        return $this->middlewareContainer;
    }
}
