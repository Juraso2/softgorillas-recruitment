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
use PHPUnit\Framework\TestCase;

class StatusPropertySetterTest extends TestCase
{
    public function testSupports(): void
    {
        $setter = new StatusPropertySetter();

        $this->assertTrue($setter->supports(TaskType::FAILURE));
        $this->assertTrue($setter->supports(TaskType::REVIEW));
    }

    public function testSetPropertyForFailureModel(): void
    {
        $setter = new StatusPropertySetter();
        $message = new Message();
        $failure = new Failure();

        $message->setDueDate('2021-01-01');
        $setter->setMessage($message);
        $setter->setProperty($failure);

        $this->assertEquals(TaskStatus::DEADLINE, $failure->getStatus());
    }

    public function testSetPropertyForFailureModelWithoutMessageDate(): void
    {
        $setter = new StatusPropertySetter();
        $message = new Message();
        $failure = new Failure();

        $message->setDueDate(null);
        $setter->setMessage($message);
        $setter->setProperty($failure);

        $this->assertEquals(TaskStatus::NEW, $failure->getStatus());
    }

    public function testSetPropertyForReviewModel(): void
    {
        $setter = new StatusPropertySetter();
        $message = new Message();
        $review = new Review();

        $message->setDueDate('2021-01-01');
        $setter->setMessage($message);
        $setter->setProperty($review);

        $this->assertEquals(TaskStatus::PLANNED, $review->getStatus());
    }

    public function testSetPropertyForReviewModelWithoutMessageDate(): void
    {
        $setter = new StatusPropertySetter();
        $message = new Message();
        $review = new Review();

        $message->setDueDate(null);
        $setter->setMessage($message);
        $setter->setProperty($review);

        $this->assertEquals(TaskStatus::NEW, $review->getStatus());
    }
}
