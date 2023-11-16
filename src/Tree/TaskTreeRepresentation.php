<?php

namespace App\Tree;

use App\Entity\Task;

class TaskTreeRepresentation
{
    /**
     * @param Task[] $taskList
     *
     * @return TaskTreeNode[]
     */
    public function asTree(array $taskList): array
    {
        $tree = [];

        foreach ($taskList as $task) {
            if (!$task->getParent()) {
                $tree[] = $this->getNode($taskList, $task);
            }
        }

        return $tree;
    }

    /**
     * @param array $taskList
     * @param Task  $task
     *
     * @return TaskTreeNode
     */
    private function getNode(array $taskList, Task $task)
    {
        return new TaskTreeNode(
            $task->getId(),
            $task->getTitle(),
            $task->getStatus(),
            $task->getCreatedAt(),
            $task->getCompletedAt(),
            $this->getChildren($taskList, $task->getId())
        );
    }

    /**
     * @param array $tasks
     * @param int   $parentTaskId
     *
     * @return array
     */
    private function getChildren(array $tasks, int $parentTaskId): array
    {
        $children = [];

        /** @var Task $task */
        foreach ($tasks as $task) {
            if ($task->getParent() && $task->getParent()->getId() === $parentTaskId) {
                $children[] = $this->getNode($tasks, $task);
            }
        }

        return $children;
    }
}