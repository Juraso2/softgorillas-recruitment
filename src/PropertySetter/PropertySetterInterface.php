<?php

namespace App\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;

interface PropertySetterInterface
{
    public function supports(TaskType $taskType): bool;

    public function setProperty(SearchableInterface $searchable): void;

    public function setMessage(Message $message): void;
}
