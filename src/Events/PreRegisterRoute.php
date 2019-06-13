<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Polus\Adr\Interfaces\ActionInterface;

final class PreRegisterRoute
{
    /** @var string */
    private $method;
    /** @var string */
    private $route;
    /** @var ActionInterface */
    private $action;

    public function __construct(string $method, string $route, ActionInterface $action)
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

    public function getAction(): ActionInterface
    {
        return $this->action;
    }
}
