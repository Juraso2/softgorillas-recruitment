<?php

namespace App\Factory;

use App\Enum\TaskType;
use App\PropertySetter\PropertySetterInterface;

final readonly class PropertySetterFactory
{
    public function __construct(private iterable $propertySetters)
    {
    }

    /**
     * @return PropertySetterInterface[]
     */
    public function getPropertySetters(TaskType $type): array
    {
        $propertySetters = [];

        /** @var PropertySetterInterface $propertySetter */
        foreach ($this->propertySetters as $propertySetter) {
            if ($propertySetter->supports($type)) {
                $propertySetters[] = $propertySetter;
            }
        }

        return $propertySetters;
    }
}
