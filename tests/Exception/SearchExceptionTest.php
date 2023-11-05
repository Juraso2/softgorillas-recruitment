<?php

namespace App\Tests\Exception;

use App\Exception\SearchException;
use PHPUnit\Framework\TestCase;

class SearchExceptionTest extends TestCase
{
    public function testFromMessage(): void
    {
        $exception = SearchException::fromMessage('test');

        $this->assertEquals('Error while searching by message: test', $exception->getMessage());
    }
}
