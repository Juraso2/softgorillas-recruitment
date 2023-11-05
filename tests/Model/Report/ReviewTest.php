<?php

namespace App\Tests\Model\Report;

use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\Review;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
class ReviewTest extends TestCase
{
    public function setUp(): void
    {
        ClockMock::register(Review::class);
        ClockMock::withClockMock(strtotime('2021-01-01 00:00:00'));
    }

    public function testSetMessageSuccessfully(): void
    {
        $review = new Review();
        $message = new Message();

        $review->setMessage($message);

        $this->assertEquals($message, $review->getMessage());
    }

    public function testSetDescriptionSuccessfully(): void
    {
        $review = new Review();

        $review->setDescription('foo');

        $this->assertEquals('foo', $review->getDescription());
    }

    public function testSetTypeSuccessfully(): void
    {
        $review = new Review();

        $review->setType(TaskType::REVIEW);

        $this->assertEquals(TaskType::REVIEW, $review->getType());
    }

    public function testSetDueDateSuccessfully(): void
    {
        $review = new Review();

        $review->setDueDate(new \DateTimeImmutable('2021-01-01 00:00:00'));

        $this->assertEquals(new \DateTimeImmutable('2021-01-01 00:00:00'), $review->getDueDate());
    }

    public function testSetWeekSuccessfully(): void
    {
        $review = new Review();

        $review->setWeek('06');

        $this->assertEquals('06', $review->getWeek());
    }

    public function testSetStatusSuccessfully(): void
    {
        $review = new Review();

        $review->setStatus(TaskStatus::NEW);

        $this->assertEquals(TaskStatus::NEW, $review->getStatus());
    }

    public function testSetRecommendationSuccessfully(): void
    {
        $review = new Review();

        $review->setRecommendation('foo');

        $this->assertEquals('foo', $review->getRecommendation());
    }

    public function testSetPhoneNumberSuccessfullu(): void
    {
        $review = new Review();

        $review->setPhoneNumber('foo');

        $this->assertEquals('foo', $review->getPhoneNumber());
    }

    public function testGetCreatedAtSuccessfully(): void
    {
        $review = new Review();
        $date = DateTime::createFromFormat('U', time());

        $this->assertEquals($date, $review->getCreatedAt());
    }

    /**
     * @dataProvider searchDataProvider
     */
    public function testSearch(string $text, bool $result): void
    {
        $review = new Review();

        $this->assertEquals($result, $review->search($text));
    }

    public function testJsonSerializeWillReturnArray(): void
    {
        $review = new Review();
        $review->setDescription('foo');
        $review->setDueDate(new DateTimeImmutable('2021-01-01 00:00:00'));
        $review->setMessage(new Message());
        $review->setPhoneNumber('123456789');
        $review->setRecommendation('foo');
        $review->setStatus(TaskStatus::NEW);
        $review->setType(TaskType::REVIEW);
        $review->setWeek('06');

        $this->assertEquals([
            'description' => 'foo',
            'type' => TaskType::REVIEW->value,
            'dueDate' => '2021-01-01',
            'week' => '06',
            'status' => TaskStatus::NEW->value,
            'recommendation' => 'foo',
            'phoneNumber' => '123456789',
            'createdAt' => '2021-01-01 00:00:00',
        ], $review->jsonSerialize());
    }

    private function searchDataProvider(): array
    {
        return [
            ['foo', false],
            ['przegląd', true],
            ['bar', false],
        ];
    }
}
