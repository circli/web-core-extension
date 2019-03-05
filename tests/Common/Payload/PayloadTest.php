<?php

namespace Tests\Common\Payload;

use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function testCallStatic(): void
    {
        $payload = TestPayload::ERROR();
        $this->assertSame(TestPayload::ERROR, $payload->getStatus());
        $this->assertSame('Error default message', $payload->getMessages());
    }

    public function testCallStaticWithArguments(): void
    {
        $arg1 = random_bytes(10);
        $arg2 = random_bytes(10);
        $payload = TestPayload::TEST($arg1, $arg2);
        $this->assertSame(TestPayload::TEST, $payload->getStatus());
        $output = $payload->getOutput();
        $this->assertSame($arg1, $output->a1);
        $this->assertSame($arg2, $output->a2);
    }

    public function testInvalidStatus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        TestPayload::INVALID();
    }

    public function testInvalidStatusConstructor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TestPayload('invalid');
    }
}
