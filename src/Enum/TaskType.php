<?php

namespace App\Enum;

enum TaskType: string
{
    case FAILURE = 'failure';
    case REVIEW = 'review';

    public function getValue(): string
    {
        return $this->value;
    }
}
