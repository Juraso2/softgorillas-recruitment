<?php

namespace App\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DueDatePropertySetter implements PropertySetterInterface
{
    private const PROPERTY = 'dueDate';

    private const SUPPORTED = [
        TaskType::FAILURE,
        TaskType::REVIEW,
    ];

    private Message $message;

    public function supports(TaskType $taskType): bool
    {
        return in_array($taskType, self::SUPPORTED, true);
    }

    public function setProperty(SearchableInterface $searchable): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $message = $this->getMessage();
        $dueDate = $message->getDueDate();

        try {
            $value = $dueDate !== "" && $dueDate !== null
                ? new \DateTimeImmutable($dueDate)
                : null;
        } catch (\Exception $e) {
            $value = null;
        }

        $propertyAccessor->setValue($searchable, self::PROPERTY, $value);
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }
}
