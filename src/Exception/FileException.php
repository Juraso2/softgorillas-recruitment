<?php

namespace App\Exception;

class FileException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromReading(string $filePath): self
    {
        return new self('Error reading file: ' . $filePath);
    }

    public static function fromSaving(string $filePath): self
    {
        return new self('Error saving file: ' . $filePath);
    }
}
