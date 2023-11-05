<?php

namespace App\PropertySetter;

use App\Enum\TaskType;
use App\Model\Message;
use App\Model\Report\SearchableInterface;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PhoneNumberPropertySetter implements PropertySetterInterface
{
    private const PROPERTY = 'phoneNumber';

    private const SUPPORTED = [
        TaskType::FAILURE,
        TaskType::REVIEW,
    ];

    private Message $message;

    public function supports(TaskType $taskType): bool
    {
        return in_array($taskType, self::SUPPORTED, true);
    }

    public function setProperty(SearchableInterface $searchable): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $message = $this->getMessage();
        $phone = $message->getPhone();

        try {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();

            $phoneNumber = $phoneNumberUtil->parse($phone, 'PL');

            $value = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
        } catch (\Exception $e) {
            $value = null;
        }

        $propertyAccessor->setValue($searchable, self::PROPERTY, $value);
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }
}
