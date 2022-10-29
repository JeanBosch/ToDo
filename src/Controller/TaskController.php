<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(TaskRepository $repository)
    {
        $tasks = $repository->findBy(['isDone' => false]);
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

     /**
     * @Route("/finished-tasks", name="finished_tasks")
     */
    public function listFinished (TaskRepository $repository)
    {
        $tasks = $repository->findBy(['isDone' => true]);
        return $this->render('task/finished_list.html.twig', ['tasks' => $tasks]);
    }
    

    /**
     * @Route("/task/{id}", name="task_show")
     */
    public function showAction(Task $task, TaskRepository $repository, $id)
    {
        $task = $repository->find($id);
        return $this->render('task/show.html.twig', ['task' => $task]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $task = new Task();       
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());           
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * 
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER') and user == task.getUser()")
     */
     
     
    
    public function editAction(Task $task, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $em->persist($task);
              $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER') and user == task.getUser()")
     */
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        if($task->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }
        else {
            $this->addFlash('danger', 'Vous n\'avez pas les droits pour effectuer cette action.');
        }

        return $this->redirectToRoute('task_list');
    }
    

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER') and user == task.getUser()")
     */
    public function deleteTaskAction(Task $task, EntityManagerInterface $em)
    {
        if($task->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }else{
            $this->addFlash('error', 'Vous n\'avez pas le droit de supprimer cette tâche.');
        }
        

        return $this->redirectToRoute('task_list');
    }
    
}
