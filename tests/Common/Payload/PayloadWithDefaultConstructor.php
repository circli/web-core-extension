<?php declare(strict_types=1);

namespace Tests\Common\Payload;

use Circli\WebCore\Common\Payload\AbstractPayload;

/**
 * @method static self TEST(array $data)
 * @method static self ERROR(string $message = null)
 */
final class PayloadWithDefaultConstructor extends AbstractPayload
{
    public const TEST = 'test';
    public const ERROR = 'error';

    protected const ALLOWED_STATUS = [self::TEST, self::ERROR];

    protected const MESSAGES = [
        self::TEST => 'Test default message',
        self::ERROR => 'Error default message',
    ];
}
