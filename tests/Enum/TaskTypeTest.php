<?php

namespace App\Tests\Enum;

use App\Enum\TaskType;
use PHPUnit\Framework\TestCase;

class TaskTypeTest extends TestCase
{
    public function testGetValue(): void
    {
        $this->assertEquals('failure', TaskType::FAILURE->getValue());
        $this->assertEquals('review', TaskType::REVIEW->getValue());
    }
}
