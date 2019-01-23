<?php declare(strict_types=1);

namespace Circli\WebCore\Common\Payload;

use Aura\Payload\Payload;

abstract class AbstractPayload extends Payload
{
    public function __construct(string $status, string $message = null)
    {
        if (!\defined(static::class . '::ALLOWED_STATUS')) {
            throw new \InvalidArgumentException('Invalid payload status. No statuses defined');
        }
        if (!\in_array($status, static::ALLOWED_STATUS, true)) {
            throw new \InvalidArgumentException('Invalid payload status');
        }
        if ($message === null && \defined(static::class . '::MESSAGES') && isset(static::MESSAGES[$status])) {
            $message = static::MESSAGES[$status];
        }
        elseif ($message === null) {
            $message = $status;
        }

        $this->status = $status;
        $this->messages = $message;
        $this->output = new \stdClass;
    }
}
