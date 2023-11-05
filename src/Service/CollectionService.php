<?php

namespace App\Service;

use App\Exception\FileException;
use App\Exception\ProcessingException;
use App\Exception\SearchException;
use App\Factory\PropertySetterFactory;
use App\Model\CollectionResult;
use App\Model\CollectionResultInterface;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

readonly class CollectionService
{
    private CollectionResultInterface $collectionResult;

    public function __construct(
        private string                $projectDir,
        private LoggerInterface       $logger,
        private SearchService         $searchService,
        private CounterService        $counterService,
        private FileService           $fileService,
        private PropertySetterFactory $propertySetterFactory
    )
    {
        $this->collectionResult = new CollectionResult();
    }

    /**
     * @throws ProcessingException|FileException|SearchException|InvalidArgumentException
     */
    public function processCollection(string $filePath): CollectionResultInterface
    {
        $messages = $this->fileService->getFromFile($filePath);

        [$messages, $duplicates] = $this->removeDuplicates($messages);

        $processed = $this->processMessages($messages);

        $this->saveMessages(...$processed);

        if(!empty($duplicates)) {
            $this->saveDuplicates(...$duplicates);
        }

        $this->collectionResult->setCounters(
            $this->counterService->count($processed, $duplicates)
        );

        return $this->collectionResult;
    }

    private function removeDuplicates(array $messages): array
    {
        $unique = [];
        $duplicates = [];

        array_walk($messages, static function ($message) use (&$unique, &$duplicates) {
            /** @var Message $message */
            if (!array_key_exists($message->getDescription(), $unique)) {
                $unique[$message->getDescription()] = $message;
            } else {
                $duplicates[$message->getDescription()] = $message;
            }
        });

        return [
            array_values($unique),
            array_values($duplicates)
        ];
    }

    /**
     * @return SearchableInterface[]
     * @throws SearchException|ProcessingException
     */
    private function processMessages(array $messages): array
    {
        $processed = [];

        foreach ($messages as $message) {
            $this->logger->info('Processing message: ' . $message->getNumber());

            $model = $this->searchService->search($message);

            $this->setMessageProperties($model, $message);

            $this->logger->info('Message processed: ' . $message->getNumber());

            $processed[] = $model;
        }

        return $processed;
    }

    /**
     * @throws ProcessingException
     */
    private function setMessageProperties(SearchableInterface $searchable, Message $message): void
    {
        try {
            $propertySetters = $this->propertySetterFactory->getPropertySetters(
                $searchable->getType()
            );

            foreach ($propertySetters as $propertySetter) {
                $propertySetter->setMessage($message);
                $propertySetter->setProperty($searchable);
            }
        } catch (\Throwable $th) {
            throw ProcessingException::fromMessage($message->getNumber());
        }
    }

    private function saveMessages(SearchableInterface ...$searchable): void
    {
        $divided = $this->divideMessages(...$searchable);

        foreach ($divided as $type => $messages) {
            $path = $this->getFilePath($type);

            $this->collectionResult->addPath($type, $path);

            $this->fileService->saveToFile($path, $messages);
        }
    }

    private function saveDuplicates(Message ...$messages): void
    {
        $path = $this->getFilePath('duplicates');

        $this->collectionResult->addPath('duplicates', $path);

        $this->fileService->saveToFile($path, $messages);
    }

    private function divideMessages(SearchableInterface ...$messages): array
    {
        $divided = [];

        foreach ($messages as $message) {
            $divided[$message->getType()->value][] = $message;
        }

        return $divided;
    }

    private function getFilePath(string $type): string
    {
        return sprintf(
            '%s/%s.%s.json',
            $this->projectDir . '/' . $_ENV['TARGET_DIR'],
            $type,
            date('Y-m-d_H-i-s')
        );
    }
}
