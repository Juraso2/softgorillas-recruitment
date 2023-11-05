<?php

namespace App\Tests\Service;

use App\Enum\TaskType;
use App\Exception\ProcessingException;
use App\Factory\PropertySetterFactory;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\PropertySetterInterface;
use App\Service\CollectionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use App\Service\SearchService;
use App\Service\CounterService;
use App\Service\FileService;

class CollectionServiceTest extends KernelTestCase
{
    public function testProcessCollection(): void
    {
        self::bootKernel([
            'environment' => 'test',
        ]);

        $container = self::getContainer();

        $service = $container->get(CollectionService::class);

        $result = $service->processCollection(__DIR__ . '/../data/messages.json');

        $this->assertCount(1, $result->getPaths());
        $this->assertEquals(2, $result->getCounters()['failure']);
        $this->assertEquals(0, $result->getCounters()['duplicates']);
    }

    public function testProcessCollectionWithDuplicates(): void
    {
        self::bootKernel([
            'environment' => 'test',
        ]);

        $container = self::getContainer();

        $service = $container->get(CollectionService::class);

        $result = $service->processCollection(__DIR__ . '/../data/messages_duplicates.json');

        $this->assertCount(2, $result->getPaths());
        $this->assertEquals(2, $result->getCounters()['failure']);
        $this->assertEquals(1, $result->getCounters()['duplicates']);
    }

    public function testProcessCollectionWithWrongPropertySetter(): void
    {
        self::bootKernel([
            'environment' => 'test',
        ]);

        $container = self::getContainer();

        $propertySetter = new class() implements PropertySetterInterface {
            public function supports(TaskType $taskType): bool
            {
                return true;
            }

            public function setProperty(SearchableInterface $searchable): void
            {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();

                $propertyAccessor->setValue($searchable, 'test', 'test');
            }

            public function setMessage(Message $message): void
            {
                // TODO: Implement setMessage() method.
            }
        };
        $propertySetterFactory = new PropertySetterFactory([$propertySetter]);

        $service = new CollectionService(
            $container->getParameter('kernel.project_dir'),
            $container->get('logger'),
            $container->get(SearchService::class),
            $container->get(CounterService::class),
            $container->get(FileService::class),
            $propertySetterFactory
        );

        $this->expectException(ProcessingException::class);
        $this->expectExceptionMessage('Error processing message: 1');

        $service->processCollection(__DIR__ . '/../data/messages.json');
    }
}
