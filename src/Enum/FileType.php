<?php

namespace App\Enum;

enum FileType: string
{
    case CSV = 'csv';
    case XML = 'xml';
    case JSON = 'json';
    case YAML = 'yaml';

    public static function fromFileExtension(string $extension): self
    {
        return match ($extension) {
            'csv' => self::CSV,
            'xml' => self::XML,
            'json' => self::JSON,
            'yaml' => self::YAML,
            default => throw new \InvalidArgumentException(sprintf('File extension "%s" not supported', $extension)),
        };
    }
}
