<?php

namespace App\Service;

use App\Enum\FileType;
use App\Exception\FileException;
use App\Factory\ParserFactory;
use App\Model\Message;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class FileService
{
    public function __construct(
        private LoggerInterface     $logger,
        private ParserFactory       $parserFactory,
        private SerializerInterface $serializer
    )
    {
    }

    /**
     * @return Message[]
     * @throws FileException
     */
    public function getFromFile(string $filePath): array
    {
        $this->logger->info('Processing file: ' . $filePath);

        try {
            $file = new \SplFileObject($filePath);

            $parser = $this->parserFactory->getParser(
                FileType::fromFileExtension(
                    $file->getExtension()
                )
            );

            return $parser->parse($file);
        } catch (\Throwable $th) {
            $this->logger->error('Error reading file: ' . $filePath);

            throw FileException::fromReading($filePath);
        }
    }

    /**
     * @throws FileException
     */
    public function saveToFile(string $filePath, array $data): void
    {
        $this->logger->info('Saving file: ' . $filePath);

        $this->makeDir(dirname($filePath));

        $file = new \SplFileObject($filePath, 'w+');

        if(!$file->isWritable()) {
            $this->logger->error(sprintf('The passed file %s is not writable', $filePath));

            throw FileException::fromSaving($filePath);
        }

        $file->fwrite(
            $this->serializer->serialize(
                $data,
                JsonEncoder::FORMAT,
                [
                    JsonEncode::OPTIONS => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => false
                ]
            )
        );
    }

    private function makeDir(string $dirPath): void
    {
        if (!file_exists($dirPath) && !mkdir($dirPath, 0777, true) && !is_dir($dirPath)) {
            throw new FileException('Error creating directory: ' . $dirPath);
        }
    }
}
