<?php

namespace App\Model;

final class CollectionResult implements CollectionResultInterface
{
    private array $counters = [];
    private array $paths = [];

    public function getCounters(): array
    {
        return $this->counters;
    }

    public function setCounters(array $counters): void
    {
        $this->counters = $counters;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public function addPath(string $type, string $path): void
    {
        if(!array_key_exists($type, $this->paths)) {
            $this->paths[$type] = $path;
        }
    }
}
