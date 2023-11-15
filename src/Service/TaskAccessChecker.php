<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskAccessChecker
{
    public function check(Task $task, User $user): void
    {
        if ($task->getOwner()->getId() !== $user->getId()) {
            throw new NotFoundHttpException();
        }
    }
}