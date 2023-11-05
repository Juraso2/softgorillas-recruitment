<?php

namespace App\Parser\Iterator;

use IteratorIterator;

class KeyedArrayIterator extends IteratorIterator
{
    private mixed $keys;

    public function rewind(): void
    {
        parent::rewind();
        $this->keys = parent::current();
        parent::next();
    }

    public function current(): mixed
    {
        return array_combine($this->keys, parent::current());
    }
}
