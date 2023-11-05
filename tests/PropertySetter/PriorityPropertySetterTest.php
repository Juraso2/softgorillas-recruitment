<?php

namespace App\Tests\PropertySetter;

use App\Enum\TaskPriority;
use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\PriorityPropertySetter;
use PHPUnit\Framework\TestCase;

class PriorityPropertySetterTest extends TestCase
{
    public function testSupports(): void
    {
        $setter = new PriorityPropertySetter();

        $this->assertTrue($setter->supports(TaskType::FAILURE));
        $this->assertFalse($setter->supports(TaskType::REVIEW));
    }

    public function testSetProperty(): void
    {
        $setter = new PriorityPropertySetter();

        $message = new Message();
        $message->setDescription('');

        $searchable = new class() implements SearchableInterface {
            private TaskPriority $priority;
            private Message $message;

            public function getPriority(): TaskPriority
            {
                return $this->priority;
            }

            public function setPriority(TaskPriority $priority): void
            {
                $this->priority = $priority;
            }

            public function getMessage(): Message
            {
                return $this->message;
            }

            public function setMessage(Message $message): void
            {
                $this->message = $message;
            }

            public function getType(): TaskType
            {
                return TaskType::FAILURE;
            }

            public function search(string $text): bool
            {
                return true;
            }
        };

        $setter->setMessage($message);

        $setter->setProperty($searchable);

        $this->assertEquals(TaskPriority::NORMAL, $searchable->getPriority());
    }

    public function testSetPropertyWithCriticalQuery(): void
    {
        $setter = new PriorityPropertySetter();

        $message = new Message();
        $message->setDescription('Sprawa jest bardzo pilna. Proszę o szybką odpowiedź.');

        $searchable = new class() implements SearchableInterface {
            private TaskPriority $priority;
            private Message $message;

            public function getPriority(): TaskPriority
            {
                return $this->priority;
            }

            public function setPriority(TaskPriority $priority): void
            {
                $this->priority = $priority;
            }

            public function getMessage(): Message
            {
                return $this->message;
            }

            public function setMessage(Message $message): void
            {
                $this->message = $message;
            }

            public function getType(): TaskType
            {
                return TaskType::FAILURE;
            }

            public function search(string $text): bool
            {
                return true;
            }
        };

        $setter->setMessage($message);

        $setter->setProperty($searchable);

        $this->assertEquals(TaskPriority::CRITICAL, $searchable->getPriority());
    }

    public function testSetPropertyWithHighQuery(): void
    {
        $setter = new PriorityPropertySetter();

        $message = new Message();
        $message->setDescription('Sprawa jest dość pilna. Proszę o szybką odpowiedź.');

        $searchable = new class() implements SearchableInterface {
            private TaskPriority $priority;
            private Message $message;

            public function getPriority(): TaskPriority
            {
                return $this->priority;
            }

            public function setPriority(TaskPriority $priority): void
            {
                $this->priority = $priority;
            }

            public function getMessage(): Message
            {
                return $this->message;
            }

            public function setMessage(Message $message): void
            {
                $this->message = $message;
            }

            public function getType(): TaskType
            {
                return TaskType::FAILURE;
            }

            public function search(string $text): bool
            {
                return true;
            }
        };

        $setter->setMessage($message);

        $setter->setProperty($searchable);

        $this->assertEquals(TaskPriority::HIGH, $searchable->getPriority());
    }
}
