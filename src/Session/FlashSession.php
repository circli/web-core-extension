<?php

namespace Circli\WebCore\Session;

use Circli\WebCore\Session\Flash\Message;

interface FlashSession
{
    public const FLASH_TEMPLATE_KEY = 'flashMessages__INTERNAL';

    public function addMessage(Message $message, string $namespace = null);

    /**
     * @param string $namespace
     * @return Message[]
     */
    public function getMessages(string $namespace = null): array;
}