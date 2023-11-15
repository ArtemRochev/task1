<?php

namespace App\Enum;

enum TaskStatus: string
{
    case Todo = 'todo';
    case Done = 'done';
}