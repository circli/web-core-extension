<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use PayloadInterop\DomainPayload;

abstract class AbstractPayload implements DomainPayload
{
    protected string $status;
    protected array $result = [];
    protected string $message;

    public function __construct(string $status, $message = null)
    {
        if (!\defined(static::class . '::ALLOWED_STATUS')) {
            throw new \InvalidArgumentException('Invalid payload status. No statuses defined');
        }
        if (!\in_array($status, static::ALLOWED_STATUS, true)) {
            throw new \InvalidArgumentException('Invalid payload status');
        }

        if (is_array($message)) {
            $this->result = $message;
            $message = null;
        }
        if ($message === null && defined(static::class . '::MESSAGES') && isset(static::MESSAGES[$status])) {
            $message = static::MESSAGES[$status];
        }
        if ($message === null) {
            $message = $status;
        }

        $this->status = $status;
        $this->message = (string)$message;
    }

    public static function __callStatic($name, $arguments)
    {
        try {
            $status = constant(static::class . '::' . strtoupper($name));
        }
        catch (\Throwable $e) {
            throw new \InvalidArgumentException('Invalid payload status');
        }

        return new static($status, ...$arguments);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

}
