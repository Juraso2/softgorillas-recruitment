<?php

namespace App\Tests\Factory;

use App\Enum\FileType;
use App\Factory\ParserFactory;
use App\Parser\ParserInterface;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    public function testGetParser(): void
    {
        $parser = new class() implements ParserInterface {
            public function getType(): FileType
            {
                return FileType::JSON;
            }

            public function parse(\SplFileObject $file): array
            {
                return [];
            }
        };

        $factory = new ParserFactory([$parser]);

        $this->assertSame($parser, $factory->getParser(FileType::JSON));
    }

    public function testGetParserThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parser for type "csv" not found');

        $factory = new ParserFactory([]);

        $factory->getParser(FileType::CSV);
    }
}
