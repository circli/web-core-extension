<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Polus\Adr\Interfaces\Action;

final class PreRegisterRoute
{
    private string $method;
    private string $route;
    private Action $action;

    public function __construct(string $method, string $route, Action $action)
    {
        $this->method = $method;
        $this->route = $route;
        $this->action = $action;
    }

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
