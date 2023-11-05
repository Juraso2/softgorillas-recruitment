<?php

namespace App\Tests\Enum;

use App\Enum\FileType;
use PHPUnit\Framework\TestCase;

class FileTypeTest extends TestCase
{
    public function testFromFileExtension(): void
    {
        $this->assertEquals(FileType::CSV, FileType::fromFileExtension('csv'));
        $this->assertEquals(FileType::XML, FileType::fromFileExtension('xml'));
        $this->assertEquals(FileType::JSON, FileType::fromFileExtension('json'));
        $this->assertEquals(FileType::YAML, FileType::fromFileExtension('yaml'));
    }

    public function testFromFileExtensionThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File extension "txt" not supported');

        FileType::fromFileExtension('txt');
    }
}
