<?php

namespace App\Parser;

use App\Enum\FileType;
use App\Model\Message;
use App\Parser\Iterator\KeyedArrayIterator;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Yaml;

final class CsvParser implements ParserInterface
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new GetSetMethodNormalizer(),
                new DateTimeNormalizer(),
                new ArrayDenormalizer(),
            ],
            [
                new JsonEncoder()
            ]
        );
    }

    public function getType(): FileType
    {
        return FileType::CSV;
    }

    /**
     * @return Message[]
     * @throws \JsonException
     */
    public function parse(\SplFileObject $file): array
    {
        $content = $this->getContent($file);

        return $this->serializer->deserialize(
            $content,
            'App\Model\Message[]',
            JsonEncoder::FORMAT
        );
    }

    private function getContent(\SplFileObject $file): string
    {
        $content = [];

        $file->setFlags($file::READ_CSV | $file::READ_AHEAD | $file::SKIP_EMPTY | $file::DROP_NEW_LINE);

        $iterator = new KeyedArrayIterator($file);

        foreach ($iterator as $row) {
            $content[] = $row;
        }

        return json_encode($content, JSON_THROW_ON_ERROR);
    }
}
