<?php

namespace App\Tests\Parser;

use App\Enum\FileType;
use App\Parser\CsvParser;
use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{
    public function testGetType(): void
    {
        $this->assertEquals(FileType::CSV, (new CsvParser())->getType());
    }

    public function testParseSuccessfully(): void
    {
        $parser = new CsvParser();
        $file = new \SplFileObject(__DIR__ . '/../data/messages.csv');
        $messages = $parser->parse($file);

        $this->assertCount(2, $messages);
        $this->assertEquals('Hello World!', $messages[0]->getDescription());
        $this->assertEquals('Hello World 2!', $messages[1]->getDescription());
    }
}
