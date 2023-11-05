<?php

namespace App\Model\Report;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Model\Message;
use DateTime;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: 0)]
class Failure implements SearchableInterface, \JsonSerializable
{
    private Message $message;

    private string $description;

    private TaskType $type = TaskType::FAILURE;

    private TaskPriority $priority;

    private ?DateTimeInterface $dueDate;

    private TaskStatus $status;

    private ?string $serviceNote = null;

    private ?string $phoneNumber = null;

    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = DateTime::createFromFormat('U', time());
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): void
    {
        $this->message = $message;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    public function getType(): TaskType
    {
        return $this->type;
    }

    public function setType(TaskType $type): void
    {
        $this->type = $type;
    }

    public function getPriority(): TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(TaskPriority $priority): void
    {
        $this->priority = $priority;
    }

    public function getDueDate(): ?DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTimeInterface $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function getServiceNote(): ?string
    {
        return $this->serviceNote;
    }

    public function setServiceNote(string $serviceNote): void
    {
        $this->serviceNote = $serviceNote;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function search(string $text): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'description' => $this->getDescription(),
            'type' => $this->getType()->value,
            'priority' => $this->getPriority()->value,
            'dueDate' => $this->getDueDate()?->format('Y-m-d'),
            'status' => $this->getStatus()->value,
            'serviceNote' => $this->getServiceNote(),
            'phoneNumber' => $this->getPhoneNumber(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
