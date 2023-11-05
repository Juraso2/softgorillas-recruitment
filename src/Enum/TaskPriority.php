<?php

namespace App\Enum;

enum TaskPriority: string
{
    case CRITICAL = 'critical';
    case HIGH = 'high';
    case NORMAL = 'normal';
}
