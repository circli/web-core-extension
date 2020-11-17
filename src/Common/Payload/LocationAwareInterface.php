<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use Psr\Http\Message\ServerRequestInterface;

interface LocationAwareInterface
{
    public function getLocation(ServerRequestInterface $request): string;
}
