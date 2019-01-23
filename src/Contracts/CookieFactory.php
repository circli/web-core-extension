<?php

namespace Circli\WebCore\Contracts;

interface CookieFactory
{
    public function getCookie(string $name): CookieInterface;
}