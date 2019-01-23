<?php

namespace Circli\WebCore\Session;

use Circli\WebCore\Session\Flash\Message;

interface FlashSession
{
    public function addMessage(Message $message, string $namespace = null);

    /**
     * @param string $namespace
     * @return Message[]
     */
    public function getMessages(string $namespace = null): array;
}