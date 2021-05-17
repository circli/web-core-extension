<?php declare(strict_types=1);

namespace Circli\WebCore\Events;

use Psr\Http\Message\ServerRequestInterface;

final class PreRouteDispatch
{
    public function __construct(
        private ServerRequestInterface $request,
    ) {}

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
