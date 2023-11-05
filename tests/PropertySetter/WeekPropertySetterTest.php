<?php

namespace App\Tests\PropertySetter;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\Failure;
use App\Model\Report\Review;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\StatusPropertySetter;
use App\PropertySetter\WeekPropertySetter;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class WeekPropertySetterTest extends TestCase
{
    public function testSupports(): void
    {
        $setter = new WeekPropertySetter();

        $this->assertFalse($setter->supports(TaskType::FAILURE));
        $this->assertTrue($setter->supports(TaskType::REVIEW));
    }

    public function testSetProperty(): void
    {
        $setter = new WeekPropertySetter();
        $message = new Message();
        $review = new Review();

        $message->setDueDate('2021-01-07');
        $setter->setMessage($message);
        $setter->setProperty($review);

        $this->assertEquals('01', $review->getWeek());
    }

    public function testSetPropertyWithEmptyDate(): void
    {
        $setter = new WeekPropertySetter();
        $message = new Message();
        $review = new Review();

        $message->setDueDate(null);
        $setter->setMessage($message);
        $setter->setProperty($review);

        $this->assertEquals(null, $review->getWeek());
    }

    public function testSetPropertyWithInvalidDate(): void
    {
        $setter = new WeekPropertySetter();
        $message = new Message();
        $review = new Review();

        $message->setDueDate('invalid');
        $setter->setMessage($message);
        $setter->setProperty($review);

        $this->assertEquals(null, $review->getWeek());
    }
}
