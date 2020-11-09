<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use Circli\WebCore\DomainStatus;
use Circli\WebCore\Exception\NotFoundInterface;
use PayloadInterop\DomainPayload;

final class NotFoundPayload implements DomainPayload
{
    private NotFoundInterface $notFound;

    public function __construct(NotFoundInterface $notFound)
    {
        $this->notFound = $notFound;
    }

    public function getStatus(): string
    {
        return DomainStatus::NOT_FOUND;
    }

    public function getResult(): array
    {
        return [
            'messages' => $this->notFound->getMessage(),
        ];
    }
}
