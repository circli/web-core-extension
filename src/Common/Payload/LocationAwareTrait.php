<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

trait LocationAwareTrait
{
    protected string $location = '';

    public function getLocation(): string
    {
        return (string)$this->location;
    }
}
