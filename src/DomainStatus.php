<?php declare(strict_types=1);

namespace Circli\WebCore;

interface DomainStatus extends \PayloadInterop\DomainStatus
{
    public const FAILURE = 'FAILURE';

    public const REDIRECT = 'REDIRECT';
}
