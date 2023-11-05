<?php

namespace App\Factory;

use App\Enum\FileType;
use App\Parser\ParserInterface;
use InvalidArgumentException;

final readonly class ParserFactory
{
    public function __construct(private iterable $parsers)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getParser(FileType $type): ParserInterface
    {
        /** @var ParserInterface $parser */
        foreach ($this->parsers as $parser) {
            if ($type === $parser->getType()) {
                return $parser;
            }
        }

        throw new InvalidArgumentException(sprintf('Parser for type "%s" not found', $type->value));
    }
}
