<?php

namespace App\Service;

class CounterService
{
    public function count(array $messages, array $duplicates): array
    {
        $counters = [];

        foreach ($messages as $message) {
            $counters[$message->getType()->getValue()] ??= 0;
            $counters[$message->getType()->getValue()]++;
        }

        $counters['duplicates'] = count($duplicates);

        return $counters;
    }
}
