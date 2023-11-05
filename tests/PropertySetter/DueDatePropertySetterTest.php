<?php

namespace App\Tests\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\DueDatePropertySetter;
use PHPUnit\Framework\TestCase;

class DueDatePropertySetterTest extends TestCase
{
    public function testSupports(): void
    {
        $setter = new DueDatePropertySetter();

        $this->assertTrue($setter->supports(TaskType::FAILURE));
        $this->assertTrue($setter->supports(TaskType::REVIEW));
    }

    public function testSetProperty(): void
    {
        $setter = new DueDatePropertySetter();

        $message = new Message();
        $message->setDueDate('2021-01-01');

        $searchable = new class() implements SearchableInterface {
            private ?\DateTimeInterface $dueDate;
            private Message $message;

            public function getDueDate(): ?\DateTimeInterface
            {
                return $this->dueDate;
            }

            public function setDueDate(?\DateTimeInterface $dueDate): void
            {
                $this->dueDate = $dueDate;
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
        $searchable->setMessage($message);

        $setter->setProperty($searchable);

        $this->assertEquals('2021-01-01', $searchable->getDueDate()->format('Y-m-d'));
    }

    public function testSetPropertyWithWrongDate(): void
    {
        $setter = new DueDatePropertySetter();

        $message = new Message();
        $message->setDueDate('abcd');

        $searchable = new class() implements SearchableInterface {
            private ?\DateTimeInterface $dueDate;
            private Message $message;

            public function getDueDate(): ?\DateTimeInterface
            {
                return $this->dueDate;
            }

            public function setDueDate(?\DateTimeInterface $dueDate): void
            {
                $this->dueDate = $dueDate;
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
        $searchable->setMessage($message);

        $setter->setProperty($searchable);

        $this->assertEquals(null, $searchable->getDueDate());
    }

    public function testSetPropertyWithoutDate(): void
    {
        $setter = new DueDatePropertySetter();

        $message = new Message();
        $message->setDueDate(null);

        $searchable = new class() implements SearchableInterface {
            private ?\DateTimeInterface $dueDate;
            private Message $message;

            public function getDueDate(): ?\DateTimeInterface
            {
                return $this->dueDate;
            }

            public function setDueDate(?\DateTimeInterface $dueDate): void
            {
                $this->dueDate = $dueDate;
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
        $searchable->setMessage($message);

        $setter->setProperty($searchable);

        $this->assertEquals(null, $searchable->getDueDate());
    }
}
