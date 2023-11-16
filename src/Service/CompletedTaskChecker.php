<?php

namespace App\Service;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use App\Tree\TaskTreeNode;
use App\Tree\TaskTreeRepresentation;

class CompletedTaskChecker
{
    public function __construct(
        public TaskRepository         $repository,
        public TaskTreeRepresentation $taskTreeRepresentation,
    )
    {
    }

    /**
     * @param Task $task
     *
     * @return bool
     */
    public function hasCompletedSubtasks(Task $task): bool
    {
        $tasks = $this->repository->findByOwner(
            $task->getOwner()->getId()
        );

        $tree     = $this->taskTreeRepresentation->asTree($tasks);
        $taskNode = $this->findInTree($tree, $task);

        return $this->hasCompleted($taskNode);
    }

    /**
     * @param TaskTreeNode[] $tree
     * @param Task           $task
     *
     * @return TaskTreeNode|null
     */
    private function findInTree(array $tree, Task $task): ?TaskTreeNode
    {
        foreach ($tree as $treeNode) {
            if ($treeNode->getId() === $task->getId()) {
                return $treeNode;
            } elseif ($treeNode->getSubtasks()) {
                $node = $this->findInTree($treeNode->getSubtasks(), $task);

                if ($node) {
                    return $node;
                }
            }
        }

        return null;
    }

    /**
     * @param TaskTreeNode $node
     *
     * @return bool|void
     */
    private function hasCompleted(TaskTreeNode $node)
    {
        if ($node->getStatus() === TaskStatus::Done->value) {
            return true;
        } elseif ($node->getSubtasks()) {
            foreach ($node->getSubtasks() as $subtaskNode) {

                if (!$subtaskNode) {
                    var_dump($node->getSubtasks());
                    die;
                }
                if ($this->hasCompleted($subtaskNode)) {
                    return true;
                }
            }
        }

        return false;
    }

}