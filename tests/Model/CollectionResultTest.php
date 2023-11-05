<?php

namespace App\Tests\Model;

use App\Model\CollectionResult;
use PHPUnit\Framework\TestCase;

class CollectionResultTest extends TestCase
{
    public function testCollectionResultSetCountersSuccessfully(): void
    {
        $collectionResult = new CollectionResult();

        $collectionResult->setCounters(['foo' => 'bar']);

        $this->assertIsArray($collectionResult->getCounters());
        $this->assertArrayHasKey('foo', $collectionResult->getCounters());
        $this->assertEquals('bar', $collectionResult->getCounters()['foo']);
    }

    /**
     * @dataProvider addPathDataProvider
     */
    public function testCollectionResultAddPathSuccessfully(string $type, string $path): void
    {
        $collectionResult = new CollectionResult();

        $collectionResult->addPath($type, $path);

        $this->assertIsArray($collectionResult->getPaths());
        $this->assertArrayHasKey($type, $collectionResult->getPaths());
        $this->assertEquals($path, $collectionResult->getPaths()[$type]);
    }

    public function testCollectionResultAddPathDoesNotAddDuplicate(): void
    {
        $collectionResult = new CollectionResult();

        $collectionResult->addPath('foo', 'bar');
        $collectionResult->addPath('foo', 'baz');

        $this->assertIsArray($collectionResult->getPaths());
        $this->assertArrayHasKey('foo', $collectionResult->getPaths());
        $this->assertEquals('bar', $collectionResult->getPaths()['foo']);
    }

    private function addPathDataProvider(): array
    {
        return [
            ['foo', 'bar'],
            ['bar', 'baz'],
            ['baz', 'foo'],
        ];
    }
}
