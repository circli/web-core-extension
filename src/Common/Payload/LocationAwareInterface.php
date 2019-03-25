<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

interface LocationAwareInterface
{
	public function getLocation(): string;
}
