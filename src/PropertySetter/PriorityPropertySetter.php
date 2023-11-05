<?php

namespace App\PropertySetter;

use App\Enum\TaskPriority;
use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use Fuse\Fuse;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PriorityPropertySetter implements PropertySetterInterface
{
    private const PROPERTY = 'priority';

    private const PRIORITIES = [
        'bardzo pilne' => TaskPriority::CRITICAL,
        'pilne' => TaskPriority::HIGH,
    ];

    private const SUPPORTED = [
        TaskType::FAILURE,
    ];

    private Message $message;

    public function supports(TaskType $taskType): bool
    {
        return in_array($taskType, self::SUPPORTED, true);
    }

    public function setProperty(SearchableInterface $searchable): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $value = $this->getPriority();

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

    private function getPriority(): TaskPriority
    {
        $priority = TaskPriority::NORMAL;

        foreach (self::PRIORITIES as $priorityName => $priorityValue) {
            if ($this->isPriority($priorityName)) {
                $priority = $priorityValue;
                break;
            }
        }

        return $priority;
    }

    private function isPriority(string $priorityName): bool
    {
        $message = $this->getMessage();
        $description = strtolower($message->getDescription());

        $fuse = new Fuse([$description], [
            'includeScore' => true,
            'shouldSort' => true,
            'findAllMatches' => true,
            'threshold' => 0.5,
            'location' => 1,
            'distance' => 150,
            'minMatchCharLength' => 4,
        ]);

        $search = $fuse->search($priorityName);

        return count($search) > 0;
    }
}
