<?php

namespace App\Tests\Factory;

use App\Enum\TaskType;
use App\Factory\PropertySetterFactory;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use App\PropertySetter\PropertySetterInterface;
use PHPUnit\Framework\TestCase;

class PropertySetterFactoryTest extends TestCase
{
    public function testGetPropertySetter(): void
    {
        $propertySetter = new class() implements PropertySetterInterface {
            public function supports(TaskType $taskType): bool
            {
                return true;
            }

            public function setProperty(SearchableInterface $searchable): void
            {
            }

            public function setMessage(Message $message): void
            {
            }
        };

        $factory = new PropertySetterFactory([$propertySetter]);

        $this->assertSame([$propertySetter], $factory->getPropertySetters(TaskType::FAILURE));
    }

    public function testGetPropertySetterThrowException(): void
    {
        $factory = new PropertySetterFactory([]);

        $this->assertSame([], $factory->getPropertySetters(TaskType::FAILURE));
    }
}
