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

final class XMLParser implements ParserInterface
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
        return FileType::XML;
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

    /**
     * @throws \JsonException
     */
    private function getContent(\SplFileObject $file): string
    {
        $content = [];

        $fileContent = $file->fread($file->getSize());

        $xml = simplexml_load_string($fileContent);

        foreach ($xml->children() as $child) {
            $content[] = $this->processEmptyNodes($child);
        }

        return json_encode($content, JSON_THROW_ON_ERROR);
    }

    private function processEmptyNodes(\SimpleXMLElement|array $data): array|null|string
    {
        if ($data instanceof \SimpleXMLElement && $data->count() === 0) {
            if($data->attributes()->nullable == 'true') {
                return null;
            }

            return '';
        }
        $data = (array)$data;
        foreach ($data as &$value) {
            if (is_array($value) || $value instanceof \SimpleXMLElement) {
                $value = $this->processEmptyNodes($value);
            }
        }
        return $data;
    }
}
