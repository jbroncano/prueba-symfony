<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/task')]
#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        // Obtener solo las tareas del usuario actual
        $tasks = $taskRepository->findBy(
            ['createdBy' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/new', name: 'task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Asignar el usuario actual como creador
            $task->setCreatedBy($this->getUser());
            
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Tarea creada exitosamente.');

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        // Verificar que la tarea pertenece al usuario actual
        if ($task->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para ver esta tarea.');
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // Verificar que la tarea pertenece al usuario actual
        if ($task->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para editar esta tarea.');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tarea actualizada exitosamente.');

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // Verificar que la tarea pertenece al usuario actual
        if ($task->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar esta tarea.');
        }

        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();

            $this->addFlash('success', 'Tarea eliminada exitosamente.');
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle', name: 'task_toggle', methods: ['POST'])]
    public function toggle(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // Verificar que la tarea pertenece al usuario actual
        if ($task->getCreatedBy() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para modificar esta tarea.');
        }

        if ($this->isCsrfTokenValid('toggle'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $task->setIsCompleted(!$task->isCompleted());
            $entityManager->flush();

            $this->addFlash('success', $task->isCompleted() ? 'Tarea marcada como completada.' : 'Tarea marcada como pendiente.');
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}