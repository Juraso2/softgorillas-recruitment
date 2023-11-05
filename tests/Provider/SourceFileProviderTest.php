<?php

namespace App\Tests\Provider;

use App\Provider\SourceFileProvider;
use PHPUnit\Framework\TestCase;

class SourceFileProviderTest extends TestCase
{
    public function testGetSourceFile(): void
    {
        $provider = new SourceFileProvider(
            __DIR__ . '/..',
            'data'
        );

        $files = $provider->provide();

        $this->assertCount(6, $files);
        $this->assertContainsOnlyInstancesOf(
            \SplFileInfo::class,
            $files
        );
        $this->assertArrayHasKey(__DIR__ . '/../data/messages.csv', $files);
        $this->assertArrayHasKey(__DIR__ . '/../data/messages.json', $files);
        $this->assertArrayHasKey(__DIR__ . '/../data/messages.yaml', $files);
        $this->assertArrayHasKey(__DIR__ . '/../data/messages.xml', $files);
        $this->assertArrayHasKey(__DIR__ . '/../data/messages_invalid.json', $files);
    }
}
