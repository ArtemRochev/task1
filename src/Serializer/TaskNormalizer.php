<?php
declare(strict_types = 1);

namespace App\Serializer;

use App\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskNormalizer implements NormalizerInterface
{
//    public function __construct(
//        private UrlGeneratorInterface $router,
//        private ObjectNormalizer $normalizer,
//    ) {
//    }

    public function normalize($task, string $format = null, array $context = []): array
    {
        return [
            'id'          => $task->getId(),
            'title'       => $task->getTitle(),
            'description' => $task->getDescription(),
        ];
        var_dump($task);
        var_dump(1);
        die;

        $data = $this->normalizer->normalize($task, $format, $context);

        // Here, add, edit, or delete some data:
        $data['href']['self'] = $this->router->generate('topic_show', [
            'id' => $topic->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
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
        return $data instanceof Task;
    }
}