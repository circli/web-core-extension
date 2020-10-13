<?php declare(strict_types=1);

namespace Circli\WebCore;

use PayloadInterop\DomainPayload;
use Psr\Http\Message\ServerRequestInterface;

final class DomainStatusToHttpStatus
{
    private const DOMAIN_TO_HTTP = [
        DomainStatus::FOUND => 200,
        DomainStatus::SUCCESS => 200,
        DomainStatus::CREATED => 201,
        DomainStatus::ACCEPTED => 202,
        DomainStatus::PROCESSING => 203,
        DomainStatus::DELETED => 204,
        DomainStatus::REDIRECT => 302,
        DomainStatus::UPDATED => 303,
        DomainStatus::FAILURE => 400,
        DomainStatus::UNAUTHORIZED => 403,
        DomainStatus::NOT_FOUND => 404,
        DomainStatus::INVALID => 422,
        DomainStatus::ERROR => 500,
    ];

    public static function httpCode(DomainPayload $payload): int
    {
        return self::DOMAIN_TO_HTTP[$payload->getStatus()] ?? 500;
    }
}
