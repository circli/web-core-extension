<?php

namespace Circli\WebCore\Events;

use Circli\WebCore\Middleware\Container as MiddlewareContainer;

class MiddlewareBuildEvent
{
    /** @var MiddlewareContainer */
    protected $middlewareContainer;

    public function __construct(MiddlewareContainer $middlewareContainer)
    {
        $this->middlewareContainer = $middlewareContainer;
    }

    public function getMiddlewareContainer(): MiddlewareContainer
    {
        return $this->middlewareContainer;
    }
}