<?php

namespace App\Controller;
use App\Entity\Task;
use App\Form\TaskFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ToDoController extends AbstractController
{
	/**
	 * Show all tasks
	 * @return view with tasks list
	 */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(Task::class);
    	$tasks = $repository->findAll();
        return $this->render('to_do/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    /**
     * Show new task form
     * @return view with form
     */
    public function create()
    {
    	$task = new Task();
    	$form = $this->createForm(TaskFormType::class, $task, [
    		'action' => $this->generateUrl('task.store')
    	]);

    	return $this->render('to_do/create.html.twig', [
    		'form' => $form->createView(),
    	]);
    }
    /**
     * Store new task in  db
     * @param Request $request contains new task name
     * @return redirct to 'todo'
     */
    public function store(Request $request)
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$task = new Task();
    	$task->setName($request->get('task_form')['name']);
    	$task->setDone(false);
    	$task->setCreatedAt(new \DateTime());
    	$task->setUpdatedAt(new \DateTime());

    	$entityManager->persist($task);
    	$entityManager->flush();

    	return $this->redirect($this->generateUrl('todo'));
    }
    /**
     * Mark task as done/undone
     * @param integer $id task id
     * @return redirect to 'todo'
     */
    public function changeDoneMark($id, $done)
    {
    	$entityManager = $this->getDoctrine()->getManager();
   		$task = $entityManager->getRepository(Task::class)->find($id);
        if($done == 1)
        {
            $task->setDone(false);
        }
        else
        {
            $task->setDone(true);
        }
        $task->setUpdatedAt(new \DateTime());

   		$entityManager->flush();

   		return $this->redirect($this->generateUrl('todo'));
    }
    /**
     * Delete task from db
     * @param integer $id task id
     * @return redirect to 'todo'
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('todo'));
    }
}
