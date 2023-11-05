<?php

namespace App\PropertySetter;

use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class StatusPropertySetter implements PropertySetterInterface
{
    private const PROPERTY = 'status';

    private const STATUSES = [
        TaskType::FAILURE->value => [
            TaskStatus::DEADLINE,
        ],
        TaskType::REVIEW->value => [
            TaskStatus::PLANNED,
        ],
    ];

    private const SUPPORTED = [
        TaskType::FAILURE,
        TaskType::REVIEW,
    ];

    private PropertyAccessorInterface $propertyAccessor;
    private Message $message;

    public function supports(TaskType $taskType): bool
    {
        return in_array($taskType, self::SUPPORTED, true);
    }

    public function setProperty(SearchableInterface $searchable): void
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor();

        $value = $this->getStatus($searchable);

        $this->propertyAccessor->setValue($searchable, self::PROPERTY, $value);
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }

    private function getStatus(SearchableInterface $searchable): TaskStatus
    {
        $type = $searchable->getType();

        foreach (self::STATUSES[$type->value] as $status) {
            if ($this->isStatus()) {
                return $status;
            }
        }

        return TaskStatus::NEW;
    }

    private function isStatus(): bool
    {
        return $this->isStatusByDueDate();
    }

    private function isStatusByDueDate(): bool
    {
        $message = $this->getMessage();
        $dueDate = $this->propertyAccessor->getValue($message, 'dueDate');

        return !empty($dueDate);
    }
}
