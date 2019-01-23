<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Input;

use Psr\Http\Message\ServerRequestInterface;

class RawInput
{
	public function __invoke(ServerRequestInterface $request)
	{
		return $request->getAttributes() + $request->getParsedBody() + $request->getQueryParams();
	}
}
