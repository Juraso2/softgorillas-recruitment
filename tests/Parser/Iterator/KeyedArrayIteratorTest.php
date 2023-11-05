<?php

namespace App\Tests\Parser\Iterator;

use App\Parser\Iterator\KeyedArrayIterator;
use PHPUnit\Framework\TestCase;

class KeyedArrayIteratorTest extends TestCase
{
    public function testIterator(): void
    {
        $array = [
            [
                'name',
                'email',
            ],
            [
                'John Doe',
                'test@mail.com'
            ],
        ];
        $traversable = new \ArrayIterator($array);

        $iterator = new KeyedArrayIterator($traversable);


        foreach ($iterator as $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('email', $value);
        }
    }
}
