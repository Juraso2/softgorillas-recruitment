<?php

namespace App\Tests\Parser;

use App\Enum\FileType;
use App\Parser\XMLParser;
use PHPUnit\Framework\TestCase;

class XMLParserTest extends TestCase
{
    public function testGetType(): void
    {
        $this->assertEquals(FileType::XML, (new XMLParser())->getType());
    }

    public function testParseSuccessfully(): void
    {
        $parser = new XMLParser();
        $file = new \SplFileObject(__DIR__ . '/../data/messages.xml');
        $messages = $parser->parse($file);

        $this->assertCount(2, $messages);
        $this->assertEquals('Hello World!', $messages[0]->getDescription());
        $this->assertEquals(null, $messages[0]->getPhone());

        $this->assertEquals('Hello World 2!', $messages[1]->getDescription());
        $this->assertEquals('', $messages[1]->getPhone());
    }
}
