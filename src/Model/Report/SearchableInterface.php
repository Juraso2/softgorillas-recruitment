<?php

namespace App\Model\Report;

use App\Enum\TaskType;
use App\Model\Message;

interface SearchableInterface
{
    public function getMessage(): Message;

    public function getType(): TaskType;

    public function search(string $text): bool;
}
