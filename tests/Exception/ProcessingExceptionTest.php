<?php

namespace App\Tests\Exception;

use App\Exception\ProcessingException;
use PHPUnit\Framework\TestCase;

class ProcessingExceptionTest extends TestCase
{
    public function testFromMessage(): void
    {
        $exception = ProcessingException::fromMessage('test message');

        $this->assertEquals('Error processing message: test message', $exception->getMessage());
    }
}
