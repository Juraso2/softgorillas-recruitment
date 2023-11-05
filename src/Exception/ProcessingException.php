<?php

namespace App\Exception;

class ProcessingException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromMessage(string $message): self
    {
        return new self(sprintf('Error processing message: %s', $message));
    }
}
