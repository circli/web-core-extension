<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use Circli\WebCore\DomainStatus;
use PayloadInterop\DomainPayload;

final class InvalidArgumentPayload implements DomainPayload
{
    private \InvalidArgumentException $exception;

    public function __construct(\InvalidArgumentException $exception)
    {
        $this->exception = $exception;
    }

    public function getStatus(): string
    {
        return DomainStatus::FAILURE;
    }

    public function getResult(): array
    {
        return [
            'messages' => $this->exception->getMessage(),
        ];
    }
}
