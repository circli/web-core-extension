<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Polus\Adr\Interfaces\Action;

final class PreRegisterRoute
{
    /**
     * @param Action|class-string<Action> $action
     */
    public function __construct(
        private string $method,
        private string $route,
        private Action|string $action,
    ) {}

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return Action|class-string<Action>
     */
    public function getAction(): Action|string
    {
        return $this->action;
    }
}
