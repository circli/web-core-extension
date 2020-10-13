<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Input;

use Psr\Http\Message\ServerRequestInterface;

class RawInput
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(ServerRequestInterface $request): array
    {
        return (array)$request->getAttributes() + (array)$request->getParsedBody() + (array)$request->getQueryParams();
    }
}
