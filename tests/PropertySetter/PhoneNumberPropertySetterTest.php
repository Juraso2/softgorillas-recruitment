<?php

namespace App\Tests\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\PhoneNumberPropertySetter;
use PHPUnit\Framework\TestCase;

class PhoneNumberPropertySetterTest extends TestCase
{
    public function testSupports(): void
    {
        $setter = new PhoneNumberPropertySetter();

        $this->assertTrue($setter->supports(TaskType::FAILURE));
        $this->assertTrue($setter->supports(TaskType::REVIEW));
    }

    public function testSetProperty(): void
    {
        $setter = new PhoneNumberPropertySetter();

        $message = new Message();
        $message->setPhone('123456789');

        $searchable = new class() implements SearchableInterface {
            private ?string $phoneNumber;
            private Message $message;

            public function getPhoneNumber(): ?string
            {
                return $this->phoneNumber;
            }

            public function setPhoneNumber(?string $phoneNumber): void
            {
                $this->phoneNumber = $phoneNumber;
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

        $this->assertEquals('+48 12 345 67 89', $searchable->getPhoneNumber());
    }

    public function testSetPropertyWithWrongNumber(): void
    {
        $setter = new PhoneNumberPropertySetter();

        $message = new Message();
        $message->setPhone('00000000000000000000000');

        $searchable = new class() implements SearchableInterface {
            private ?string $phoneNumber;
            private Message $message;

            public function getPhoneNumber(): ?string
            {
                return $this->phoneNumber;
            }

            public function setPhoneNumber(?string $phoneNumber): void
            {
                $this->phoneNumber = $phoneNumber;
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

        $this->assertEquals(null, $searchable->getPhoneNumber());
    }
}
