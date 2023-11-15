<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatus;
use App\Form\TaskType;
use App\Form\TaskUpdateType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Serializer\TaskNormalizer;
use App\Service\TaskAccessChecker;
use App\Service\TaskManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(TaskRepository $taskRepository, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user  = $this->getUser();
        $tasks = [];

        $serializer = new Serializer([new TaskNormalizer()], []);

        foreach ($taskRepository->findByOwner($user->getId()) as $task) {
            $tasks[] = $serializer->normalize($task);
        }

        return new JsonResponse($tasks);
    }

    #[Route('/new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $task->setOwner($this->getUser());
            $entityManager->persist($task);
            $entityManager->flush();

            return new JsonResponse(['id' => $task->getId()]);
        }

        $errors = [];
        foreach ($form->getErrors(true, false) as $error) {
            $errors[$error->getForm()->getName()] = (string)$error;
        }

        return new JsonResponse($errors);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', methods: ['POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager, TaskAccessChecker $taskAccessChecker): Response
    {
        $taskAccessChecker->check($task, $this->getUser());

        $form = $this->createForm(TaskUpdateType::class, $task);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return new JsonResponse(['ok']);
        }

        return new JsonResponse([
            'error' => (string)$form->getErrors(true, false),
        ]);
    }

    #[Route('/{id}/done', methods: ['POST'])]
    public function done(Task $task, TaskAccessChecker $taskAccessChecker, TaskManager $taskManager): Response
    {
        $taskAccessChecker->check($task, $this->getUser());
        $taskManager->done($task);

        return new JsonResponse(['ok']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager, TaskAccessChecker $taskAccessChecker): Response
    {
        $taskAccessChecker->check($task, $this->getUser());

        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
