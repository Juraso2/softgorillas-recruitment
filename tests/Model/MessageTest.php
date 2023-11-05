<?php

namespace App\Tests\Model;

use App\Model\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testMessageSetNumberSuccessfully(): void
    {
        $message = new Message();

        $message->setNumber(1);

        $this->assertEquals(1, $message->getNumber());
    }

    public function testMessageSetDescriptionSuccessfully(): void
    {
        $message = new Message();

        $message->setDescription('foo');

        $this->assertEquals('foo', $message->getDescription());
    }

    public function testMessageSetDueDateSuccessfully(): void
    {
        $message = new Message();

        $message->setDueDate('foo');

        $this->assertEquals('foo', $message->getDueDate());
    }

    public function testMessageSetPhoneSuccessfully(): void
    {
        $message = new Message();

        $message->setPhone('foo');

        $this->assertEquals('foo', $message->getPhone());
    }
}
