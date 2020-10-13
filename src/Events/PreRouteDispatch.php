<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Psr\Http\Message\ServerRequestInterface;

final class PreRouteDispatch
{
    private ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
