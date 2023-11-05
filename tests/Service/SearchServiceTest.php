<?php

namespace App\Tests\Service;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\Service\SearchService;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{
    public function testSearch(): void
    {
        $searchables = [
            new class implements SearchableInterface {
                public function search(string $text): bool
                {
                    return true;
                }

                public function getMessage(): Message
                {
                    // TODO: Implement getMessage() method.
                }

                public function getType(): TaskType
                {
                    return TaskType::FAILURE;
                }
            },
        ];

        $message = new Message();
        $message->setDescription('test');

        $searchService = new SearchService($searchables);

        $searchable = $searchService->search($message);

        $this->assertEquals(TaskType::FAILURE, $searchable->getType());
    }

    public function testSearchWillThrowException(): void
    {
        $searchables = [
            new class implements SearchableInterface {
                public function search(string $text): bool
                {
                    return false;
                }

                public function getMessage(): Message
                {
                    // TODO: Implement getMessage() method.
                }

                public function getType(): TaskType
                {
                    return TaskType::FAILURE;
                }
            },
        ];

        $message = new Message();
        $message->setDescription('test');

        $searchService = new SearchService($searchables);

        $this->expectExceptionMessage('Error while searching by message: test');

        $searchService->search($message);
    }
}
