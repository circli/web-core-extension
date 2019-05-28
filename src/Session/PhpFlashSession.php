<?php declare(strict_types=1);

namespace Circli\WebCore\Session;

use Circli\WebCore\Session\Flash\Message;

class PhpFlashSession implements FlashSession
{
    private const SESSION_KEY = '_flash_messages_';

    public function addMessage(Message $message, string $namespace = null)
    {
        $data = (array)($_SESSION[self::SESSION_KEY] ?? []);

        $data[] = $message->jsonSerialize();

        $_SESSION[self::SESSION_KEY] = $data;
    }

    /**
     * @param string $namespace
     * @return Message[]
     */
    public function getMessages(string $namespace = null): array
    {
        $messages = [];
        if (isset($_SESSION[self::SESSION_KEY])) {
            foreach ($_SESSION[self::SESSION_KEY] as $message) {
                $messages[] = Message::fromJson($message);
            }
            unset($_SESSION[self::SESSION_KEY]);
        }

        return $messages;
    }
}