<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use PayloadInterop\DomainPayload;

abstract class AbstractPayload implements DomainPayload
{
    protected string $status;
    /** @var array<mixed, mixed> */
    protected array $result = [];
    protected string $message;

    /**
     * @param string|array<mixed,mixed>|null $message
     */
    public function __construct(string $status, $message = null)
    {
        if (!\defined(static::class . '::ALLOWED_STATUS')) {
            throw new \InvalidArgumentException('Invalid payload status. No statuses defined');
        }
        /** @phpstan-ignore-next-line */
        if (!\in_array($status, static::ALLOWED_STATUS, true)) {
            throw new \InvalidArgumentException('Invalid payload status');
        }

        if (is_array($message)) {
            $this->result = $message;
            $message = null;
        }
        /** @phpstan-ignore-next-line */
        if ($message === null && defined(static::class . '::MESSAGES') && isset(static::MESSAGES[$status])) {
            $message = static::MESSAGES[$status]; // @phpstan-ignore-line
        }
        if ($message !== null && !$this->result) {
            $this->result = [
                'messages' => $message,
            ];
            /** @phpstan-ignore-next-line */
            if (defined(static::class . '::MESSAGE_CODES') && isset(static::MESSAGE_CODES[$status])) {
                $this->result['code'] = (string)static::MESSAGE_CODES[$status]; // @phpstan-ignore-line
            }
        }
        if ($message === null) {
            $message = $status;
        }

        $this->status = $status;
        $this->message = (string)$message;
    }

    /**
     * @param array<mixed> $arguments
     * @return static
     */
    public static function __callStatic(string $name, $arguments)
    {
        try {
            $status = constant(static::class . '::' . strtoupper($name));
        }
        catch (\Throwable $e) {
            throw new \InvalidArgumentException('Invalid payload status');
        }
        /** @phpstan-ignore-next-line */
        return new static($status, ...$arguments);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array<mixed>
     */
    public function getResult(): array
    {
        return $this->result;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
