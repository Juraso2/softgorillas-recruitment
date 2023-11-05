<?php

namespace App\Tests\Service;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\Service\CounterService;
use PHPUnit\Framework\TestCase;

class CounterServiceTest extends TestCase
{
    public function testCount(): void
    {
        $messages = [
            $this->createMessage(),
            $this->createMessage(),
            $this->createMessage(),
        ];

        $duplicateMessages = [
            $this->createMessage(),
            $this->createMessage(),
        ];

        $counterService = new CounterService();
        $counters = $counterService->count($messages, $duplicateMessages);

        $this->assertEquals(3, $counters[TaskType::FAILURE->getValue()]);
        $this->assertEquals(2, $counters['duplicates']);
    }

    private function createMessage(): SearchableInterface
    {
        return new class() implements SearchableInterface {
            public function getType(): TaskType
            {
               return TaskType::FAILURE;
            }

            public function getMessage(): Message
            {
                // TODO: Implement getMessage() method.
            }

            public function search(string $text): bool
            {
                // TODO: Implement search() method.
            }
        };
    }
}
