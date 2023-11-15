<?php

namespace App\DoctrineType;

use App\Enum\TaskStatus;

class TaskStatusEnumType extends AbstractEnumType
{
    public static function getEnumsClass(): string
    {
        return TaskStatus::class;
    }

    public function getName()
    {
        return 'TaskStatus';
    }
}