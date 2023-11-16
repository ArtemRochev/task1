<?php

namespace App\Service;

use App\Entity\Task;
use App\Enum\TaskStatus;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class TaskManager
{
    public function __construct(
        public EntityManagerInterface $em
    )
    {
    }

    public function done(Task $task)
    {
        $task->setStatus(TaskStatus::Done->value);
        $task->setCompletedAt(new DateTimeImmutable());

        $this->em->persist($task);
        $this->em->flush();
    }

    public function delete(Task $task)
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}