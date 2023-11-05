<?php

namespace App\Tests\Model\Report;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\Failure;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
class FailureTest extends TestCase
{
    public function setUp(): void
    {
        ClockMock::register(Failure::class);
        ClockMock::withClockMock(strtotime('2021-01-01 00:00:00'));
    }

    public function testSetMessageSuccessfully(): void
    {
        $failure = new Failure();
        $message = new Message();

        $failure->setMessage($message);

        $this->assertEquals($message, $failure->getMessage());
    }

    public function testSetDescriptionSuccessfully(): void
    {
        $failure = new Failure();

        $failure->setDescription('foo');

        $this->assertEquals('foo', $failure->getDescription());
    }

    public function testSetTypeSuccessfully(): void
    {
        $failure = new Failure();

        $failure->setType(TaskType::FAILURE);

        $this->assertEquals(TaskType::FAILURE, $failure->getType());
    }

    public function testSetPrioritySuccessfully(): void
    {
        $failure = new Failure();

        $failure->setPriority(TaskPriority::NORMAL);

        $this->assertEquals(TaskPriority::NORMAL, $failure->getPriority());
    }

    public function testSetDueDateSuccessfully(): void
    {
        $failure = new Failure();
        $date = new \DateTimeImmutable();

        $failure->setDueDate($date);

        $this->assertEquals($date, $failure->getDueDate());
    }

    public function testSetStatusSuccessfully(): void
    {
        $failure = new Failure();

        $failure->setStatus(TaskStatus::NEW);

        $this->assertEquals(TaskStatus::NEW, $failure->getStatus());
    }

    public function testSetServiceNoteSuccessfully(): void
    {
        $failure = new Failure();

        $failure->setServiceNote('foo');

        $this->assertEquals('foo', $failure->getServiceNote());
    }

    public function testSetPhoneNumberSuccessfully(): void
    {
        $failure = new Failure();

        $failure->setPhoneNumber('123456789');

        $this->assertEquals('123456789', $failure->getPhoneNumber());
    }

    public function testGetCreatedAtSuccessfully(): void
    {
        $failure = new Failure();
        $date = DateTime::createFromFormat('U', time());

        $this->assertEquals($date, $failure->getCreatedAt());
    }

    public function testSearch(): void
    {
        $failure = new Failure();

        $this->assertTrue($failure->search('foo'));
    }

    public function testJsonSerializeWillReturnArray(): void
    {
        $failure = new Failure();
        $failure->setDescription('foo');
        $failure->setDueDate(new DateTimeImmutable('2021-01-01 00:00:00'));
        $failure->setMessage(new Message());
        $failure->setPhoneNumber('123456789');
        $failure->setPriority(TaskPriority::NORMAL);
        $failure->setServiceNote('foo');
        $failure->setStatus(TaskStatus::NEW);
        $failure->setType(TaskType::FAILURE);

        $this->assertEquals([
            'description' => 'foo',
            'type' => TaskType::FAILURE->value,
            'priority' => TaskPriority::NORMAL->value,
            'dueDate' => '2021-01-01',
            'status' => TaskStatus::NEW->value,
            'serviceNote' => 'foo',
            'phoneNumber' => '123456789',
            'createdAt' => '2021-01-01 00:00:00',
        ], $failure->jsonSerialize());
    }
}
