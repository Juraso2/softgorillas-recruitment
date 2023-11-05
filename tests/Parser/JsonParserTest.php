<?php

namespace App\Tests\Parser;

use App\Enum\FileType;
use App\Parser\JsonParser;
use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    public function testGetType(): void
    {
        $this->assertEquals(FileType::JSON, (new JsonParser())->getType());
    }

    public function testParseSuccessfully(): void
    {
        $parser = new JsonParser();
        $file = new \SplFileObject(__DIR__ . '/../data/messages.json');
        $messages = $parser->parse($file);

        $this->assertCount(2, $messages);
        $this->assertEquals('Hello World!', $messages[0]->getDescription());
        $this->assertEquals('Hello World 2!', $messages[1]->getDescription());
    }

    public function testParseWithInvalidJson(): void
    {
        $this->expectException(\JsonException::class);

        $parser = new JsonParser();
        $file = new \SplFileObject(__DIR__ . '/../data/messages_invalid.json');

        $parser->parse($file);
    }
}
