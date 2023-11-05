<?php

namespace App\Tests\Service;

use App\Exception\FileException;
use App\Service\FileService;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileServiceTest extends KernelTestCase
{
    public function testGetFromFile(): void
    {
        self::bootKernel([
            'environment' => 'test',
        ]);

        $container = self::getContainer();

        $file = new SplFileObject(__DIR__ . '/../data/messages.json');

        $service = $container->get(FileService::class);

        $messages = $service->getFromFile($file->getPathname());

        $this->assertCount(2, $messages);
        $this->assertEquals('Hello World!', $messages[0]->getDescription());
        $this->assertEquals('Hello World 2!', $messages[1]->getDescription());
    }

    public function testGetFromFileWillThrowException(): void
    {
        self::bootKernel([
            'environment' => 'test',
        ]);

        $container = self::getContainer();

        $service = $container->get(FileService::class);

        $this->expectException(FileException::class);
        $this->expectExceptionMessage('Error reading file: ' . __DIR__ . '/../data/messages.txt');

        $service->getFromFile(__DIR__ . '/../data/messages.txt');
    }

    public function testSaveToFile(): void
    {
        self::bootKernel([
            'environment' => 'test',
        ]);

        $container = self::getContainer();
        $service = $container->get(FileService::class);

        $content = [
            [
                'description' => 'Hello World!',
                'type' => 'failure',
            ],
            [
                'description' => 'Hello World 2!',
                'type' => 'failure',
            ],
        ];

        $service->saveToFile(__DIR__ . '/../data/processed/test.json', $content);

        $file = new SplFileObject(__DIR__ . '/../data/processed/test.json');

        $this->assertFileExists($file->getPathname());

        $this->assertEquals(
            json_encode($content, JSON_PRETTY_PRINT),
            $file->fread($file->getSize())
        );
    }
}
