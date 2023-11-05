<?php

namespace App\Tests\Parser;

use App\Enum\FileType;
use App\Parser\YamlParser;
use PHPUnit\Framework\TestCase;

class YamlParserTest extends TestCase
{
    public function testGetType(): void
    {
        $this->assertEquals(FileType::YAML, (new YamlParser())->getType());
    }

    public function testParseSuccessfully(): void
    {
        $parser = new YamlParser();
        $file = new \SplFileObject(__DIR__ . '/../data/messages.yaml');
        $messages = $parser->parse($file);

        $this->assertCount(2, $messages);
        $this->assertEquals('Hello World!', $messages[0]->getDescription());
        $this->assertEquals('Hello World 2!', $messages[1]->getDescription());
    }
}
