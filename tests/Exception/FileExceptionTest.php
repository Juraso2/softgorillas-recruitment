<?php

namespace App\Tests\Exception;

use App\Exception\FileException;
use PHPUnit\Framework\TestCase;

class FileExceptionTest extends TestCase
{
    public function testFromParsing(): void
    {
        $exception = FileException::fromReading('/app/tests/Command/fixture/invalid.json');

        $this->assertEquals('Error reading file: /app/tests/Command/fixture/invalid.json', $exception->getMessage());
    }

    public function testFromSaving(): void
    {
        $exception = FileException::fromSaving('/app/tests/Command/fixture/invalid.json');

        $this->assertEquals('Error saving file: /app/tests/Command/fixture/invalid.json', $exception->getMessage());
    }
}
