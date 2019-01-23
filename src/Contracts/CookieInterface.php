<?php

namespace Circli\WebCore\Contracts;

interface CookieInterface
{
    public function setName(string $value): CookieInterface;
    public function setValue($value): CookieInterface;
    /**
     * @param \DateTimeInterface|\DateInterval|int $expire
     * @return self
     */
    public function setExpire($expire): CookieInterface;
    public function save(): bool;
    public function delete(): bool;
    public function exits(): bool;

    public function getValue(): string;
}