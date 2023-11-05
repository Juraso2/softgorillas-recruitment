<?php

namespace App\Parser;

use App\Enum\FileType;
use App\Model\Message;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Yaml;

final class YamlParser implements ParserInterface
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
        return FileType::YAML;
    }

    /**
     * @return Message[]
     * @throws \JsonException
     */
    public function parse(\SplFileObject $file): array
    {
        $content = Yaml::parseFile($file->getRealPath());

        return $this->serializer->deserialize(
            json_encode($content, JSON_THROW_ON_ERROR),
            'App\Model\Message[]',
            JsonEncoder::FORMAT
        );
    }
}
