<?php

namespace Circli\WebCore\Session\Flash;

class Message implements \JsonSerializable
{
    private $type;
    private $message;

    private function __construct(string $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public static function success(string $msg): Message
    {
        return new self('success', $msg);
    }

    public static function error(string $msg): Message
    {
        return new self('error', $msg);
    }

    public static function info(string $msg): Message
    {
        return new self('info', $msg);
    }

    public static function fromJson(array $json): Message
    {
        return new self($json['type'], $json['message']);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'message' => $this->type,
        ];
    }
}