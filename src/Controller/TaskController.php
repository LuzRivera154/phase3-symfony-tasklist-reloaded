<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\Status;
use App\Form\FolderType;
use App\Form\TaskType;
use App\Repository\FolderRepository;
use App\Repository\PriorityRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function PHPUnit\Framework\throwException;

#[Route('/task')]
final class TaskController extends AbstractController
{
    //defaults: ['folderId' => null]
    //Si el usuario entra a /task (sin nada después), asume que folderId es nulo y ejecuta la función igua
    //requirements: ['folderId' => '\d+']: Esto es fundamental. 
    //Le dice a Symfony que esa ruta solo se active si el parámetro son números (\d+).
    #[Route('/{folderId}', name: 'app_task_index', methods: ['GET'], defaults: ['folderId' => null], requirements: ['folderId' => '\d+'])]
    public function index(?int $folderId, TaskRepository $taskRepository, FolderRepository $folderRepository, PriorityRepository $priorityRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $criteria = ['user' => $user];
        if ($folderId) {
            $criteria['Folder'] = $folderId;
        };

        $tasks = $taskRepository->findBy(
            $criteria,
            ['isPinned' => 'DESC', 'id' => 'DESC']
        );
        $folders = $folderRepository->findBy([
            'user' => $user,
        ]);
        $priorities = $priorityRepository->findBy([
            'user' => $user,
        ]);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'folders' => $folders,
            'priorities' => $priorities,
            'currentFolderId' => $folderId,
        ]);
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $user->addTask($task);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/toggle', name: 'app_task_toggle', methods: ['POST'])]
    public function toggle(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid(
            'toggle' . $task->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException();
        }
        $newStatus = match ($task->getStatus()) {
            Status::pending => Status::completed,
            Status::completed => Status::pending,
            Status::archived => Status::completed,
            default           => Status::pending,
        };

        $task->setStatus($newStatus);
        $entityManager->flush();
        return $this->redirectToRoute('app_task_index');
    }

    #[Route('/{id}/pin', name: 'app_task_pin', methods: ['POST'])]
    public function pin(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid(
            'pin' . $task->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException();
        }
        $isNowPinned = !$task->isPinned();
        $task->setIsPinned($isNowPinned);

        $entityManager->flush();
        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/delete', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('id');
        $task = $taskRepository->find($id);

        if ($task && $this->isCsrfTokenValid(
            'delete' . $id,
            $request->getPayload()->getString('_token')
        )) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
