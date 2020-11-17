<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use Circli\WebCore\DomainStatus;
use PayloadInterop\DomainPayload;

final class AccessDeniedPayload implements DomainPayload
{
    public function getStatus(): string
    {
        return DomainStatus::UNAUTHORIZED;
    }

    public function getResult(): array
    {
        return [
            'messages' => 'Access denied',
            'code' => 'ACCESS_DENIED',
        ];
    }
}
