<?php

namespace App\Enum;

enum TaskStatus: string
{
    case NEW = 'new';
    case PLANNED = 'planned';
    case DEADLINE = 'deadline';

}
