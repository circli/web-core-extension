<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Polus\Adr\Interfaces\Action;

final class PreRegisterRoute
{
    public function __construct(
        private string $method,
        private string $route,
        private Action $action,
    ) {}

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getAction(): Action
    {
        return $this->action;
    }
}
