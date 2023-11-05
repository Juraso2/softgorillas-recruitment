<?php

namespace App\Model\Report;

use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Model\Message;
use DateTime;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: 10)]
class Review implements SearchableInterface, \JsonSerializable
{
    private const SEARCH_QUERY = 'przegląd';

    private Message $message;

    private string $description;

    private TaskType $type = TaskType::REVIEW;

    private ?DateTimeInterface $dueDate;

    private ?string $week;

    private TaskStatus $status;

    private ?string $recommendation = null;

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

    public function getDueDate(): ?DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTimeInterface $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getWeek(): ?string
    {
        return $this->week;
    }

    public function setWeek(?string $week): void
    {
        $this->week = $week;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function setRecommendation(string $recommendation): void
    {
        $this->recommendation = $recommendation;
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
        $words = explode(' ', $text);

        foreach ($words as $word) {
            $percent = 0;

            similar_text($word, self::SEARCH_QUERY, $percent);

            if ($percent > 75) {
                return true;
            }
        }

        return false;
    }

    public function jsonSerialize(): array
    {
        return [
            'description' => $this->getDescription(),
            'type' => $this->getType()->value,
            'dueDate' => $this->getDueDate()?->format('Y-m-d'),
            'week' => $this->getWeek(),
            'status' => $this->getStatus()->value,
            'recommendation' => $this->getRecommendation(),
            'phoneNumber' => $this->getPhoneNumber(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
