<?php declare(strict_types=1);

namespace Circli\WebCore\Contracts;

interface CookieFactory
{
    public function getCookie(string $name): CookieInterface;
}