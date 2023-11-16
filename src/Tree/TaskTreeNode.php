<?php

namespace App\Tree;

use App\Serializer\TaskTreeNodeNormalizer;
use DateTimeImmutable;
use JsonSerializable;

class TaskTreeNode implements JsonSerializable
{
    public function __construct(
        private readonly int                $id,
        private readonly string             $title,
        private readonly string             $status,
        private readonly DateTimeImmutable  $createdAt,
        private readonly ?DateTimeImmutable $completedAt,
        private readonly array              $subtasks
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSubtasks(): array
    {
        return $this->subtasks;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        return (new TaskTreeNodeNormalizer())->normalize($this);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }
}