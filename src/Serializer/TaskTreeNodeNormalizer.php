<?php
declare(strict_types = 1);

namespace App\Serializer;

use App\Tree\TaskTreeNode;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskTreeNodeNormalizer implements NormalizerInterface
{
    public function normalize($task, string $format = null, array $context = []): array
    {
        return [
            'id'          => $task->getId(),
            'title'       => $task->getTitle(),
            'createdAt'   => $task->getCreatedAt()?->format('Y-m-d H:i:s'), // format can be given from config
            'completedAt' => $task->getCompletedAt()?->format('Y-m-d H:i:s'),
            'subtasks'    => $task->getSubtasks(),
        ];
    }

    /**
     * @param             $data
     * @param string|null $format
     * @param array       $context
     *
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof TaskTreeNode;
    }
}