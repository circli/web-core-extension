<?php declare(strict_types=1);

namespace Circli\WebCore\Session;

use Circli\WebCore\Session\Flash\Message;
use PSR7Sessions\Storageless\Session\SessionInterface;

class StoragelessFlashSession implements FlashSession
{
    private const SESSION_KEY = '_flash_messages_';

    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function addMessage(Message $message, string $namespace = null)
    {
        $data = [];
        if ($this->session->has(self::SESSION_KEY)) {
            /** @var array $data */
            $data = $this->session->get(self::SESSION_KEY);
        }

        $data[] = $message;

        $this->session->set(self::SESSION_KEY, $data);
    }

    /**
     * @param string $namespace
     * @return Message[]
     */
    public function getMessages(string $namespace = null): array
    {
        $messages = [];
        if ($this->session->has(self::SESSION_KEY)) {
            $rawMessages = $this->session->get(self::SESSION_KEY);
            $this->session->remove(self::SESSION_KEY);
            foreach ($rawMessages as $message) {
                $messages[] = Message::fromJson($message);
            }
        }

        return $messages;
    }
}