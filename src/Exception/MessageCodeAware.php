<?php declare(strict_types=1);

namespace Circli\WebCore\Exception;

interface MessageCodeAware
{
    public function getMessageCode(): string;
}
