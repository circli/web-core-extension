<?php

namespace Tests\Common\Payload;

use Circli\WebCore\Common\Payload\AbstractPayload;

/**
 * @method static self TEST($arg1, $arg2)
 * @method static self ERROR()
 */
class TestPayload extends AbstractPayload
{
    public const TEST = 'test';
    public const ERROR = 'error';

    protected const ALLOWED_STATUS = [self::TEST, self::ERROR];

    protected const MESSAGES = [
        self::TEST => 'Test default message',
        self::ERROR => 'Error default message',
    ];

    public function __construct(string $status, $arg1 = null, $arg2 = null)
    {
        parent::__construct($status);

        if ($arg1) {
            $this->result['a1'] = $arg1;
        }
        if ($arg2) {
            $this->result['a2'] = $arg2;
        }
    }
}
