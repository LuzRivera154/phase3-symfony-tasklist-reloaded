<?php

namespace App\Controller;

use App\Entity\Priority;
use App\Form\PriorityType;
use App\Repository\PriorityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/priority')]
final class PriorityController extends AbstractController
{
    #[Route('/new', name: 'app_priority_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PriorityRepository $priorityRepository): Response
    {
        $priority = new Priority();
        $form = $this->createForm(PriorityType::class, $priority);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $priority->setUser($this->getUser());
            $entityManager->persist($priority);
            $entityManager->flush();

            return $this->redirectToRoute('app_priority_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('priority/new.html.twig', [
            'priority' => $priority,
            'priorities' => $priorityRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'app_priority_delete', methods: ['POST'])]
    public function delete(Request $request, PriorityRepository $priorityRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('id');
        $priority = $priorityRepository->find($id);

        if ($priority &&  $this->isCsrfTokenValid(
            'delete' . $id,
            $request->getPayload()->getString('_token')
        )) {
            $defaultPriority = $priorityRepository->findOneBy(['name' => 'normal']);

            foreach ($priority->getTask() as $task) {
                $task->setPriority($defaultPriority);
            }

            $entityManager->remove($priority);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_priority_new', [], Response::HTTP_SEE_OTHER);
    }
}
