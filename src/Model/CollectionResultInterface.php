<?php

namespace App\Model;

interface CollectionResultInterface
{
    public function getCounters(): array;

    public function setCounters(array $counters): void;

    public function getPaths(): array;

    public function addPath(string $type, string $path): void;
}
