<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use Aura\Payload\Payload;

abstract class AbstractPayload extends Payload
{
    public function __construct(string $status, $message = null)
    {
        if (!\defined(static::class . '::ALLOWED_STATUS')) {
            throw new \InvalidArgumentException('Invalid payload status. No statuses defined');
        }
        if (!\in_array($status, static::ALLOWED_STATUS, true)) {
            throw new \InvalidArgumentException('Invalid payload status');
        }
        $this->output = new \stdClass;

        if ($message === null && defined(static::class . '::MESSAGES') && isset(static::MESSAGES[$status])) {
            $message = static::MESSAGES[$status];
        }
        elseif (!is_string($message) && $message !== null) {
            $this->output = $message;
        }
        if ($message === null) {
            $message = $status;
        }

        $this->status = $status;
        $this->messages = $message;
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
}
