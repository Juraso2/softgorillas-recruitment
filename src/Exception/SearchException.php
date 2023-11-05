<?php

namespace App\Exception;

class SearchException extends \Exception
{
    public static function fromMessage(string $message): self
    {
        return new self('Error while searching by message: ' . $message);
    }
}
