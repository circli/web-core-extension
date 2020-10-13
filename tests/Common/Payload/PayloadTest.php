<?php

namespace Tests\Common\Payload;

use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function testCallStatic(): void
    {
        $payload = TestPayload::ERROR();
        $this->assertSame(TestPayload::ERROR, $payload->getStatus());
        $this->assertSame('Error default message', $payload->getMessage());
    }

    public function testCallStaticWithArguments(): void
    {
        $arg1 = random_bytes(10);
        $arg2 = random_bytes(10);
        $payload = TestPayload::TEST($arg1, $arg2);
        $this->assertSame(TestPayload::TEST, $payload->getStatus());
        $output = $payload->getResult();
        $this->assertSame($arg1, $output['a1']);
        $this->assertSame($arg2, $output['a2']);
    }

    public function testCallStaticWithArgumentsAndDefaultConstructor(): void
    {
        $testData = ['test' => 1];
        $payload = PayloadWithDefaultConstructor::TEST($testData);
        $this->assertSame(PayloadWithDefaultConstructor::TEST, $payload->getStatus());
        $output = $payload->getResult();
        $this->assertSame($testData, $output);
    }

    public function testCallStaticWithMessageAndDefaultConstructor(): void
    {
        $payload = PayloadWithDefaultConstructor::ERROR('test');
        $this->assertSame(PayloadWithDefaultConstructor::ERROR, $payload->getStatus());
        $output = $payload->getResult();
        $this->assertSame([], $output);
        $this->assertSame('test', $payload->getMessage());
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
