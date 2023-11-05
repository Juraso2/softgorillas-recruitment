<?php

namespace App\Tests\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\DescriptionPropertySetter;
use PHPUnit\Framework\TestCase;

class DescriptionPropertySetterTest extends TestCase
{
    public function testSupports(): void
    {
        $setter = new DescriptionPropertySetter();

        $this->assertTrue($setter->supports(TaskType::FAILURE));
        $this->assertTrue($setter->supports(TaskType::REVIEW));
    }

    public function testSetProperty(): void
    {
        $setter = new DescriptionPropertySetter();

        $message = new Message();
        $message->setDescription('Hello World!');

        $searchable = new class() implements SearchableInterface {
            private string $description;
            private Message $message;

            public function getDescription(): string
            {
                return $this->description;
            }

            public function setDescription(?string $description): void
            {
                $this->description = $description;
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

        $this->assertEquals('Hello World!', $searchable->getDescription());
    }
}
