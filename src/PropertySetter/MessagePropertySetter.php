<?php

namespace App\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class MessagePropertySetter implements PropertySetterInterface
{
    private const PROPERTY = 'message';

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

        $propertyAccessor->setValue($searchable, self::PROPERTY, $message);
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
