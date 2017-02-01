<?php

namespace Api\Controller;

use App\Exception\ValidateException;
use App\Model\Entity\Task;
use App\Model\Repository\TaskRepository;
use App\Repository\TodoListDbRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class TaskController
{
    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @var TodoListDbRepository
     */
    protected $todoRepository;

    /**
     * @var RecursiveValidator
     */
    protected $validator;

    protected $user;

    /**
     * @param TaskRepository $taskRepository
     * @param TodoListDbRepository $todoRepository
     * @param RecursiveValidator $validator
     * @param $user
     */
    public function __construct(TaskRepository $taskRepository, TodoListDbRepository $todoRepository, RecursiveValidator $validator, $user)
    {
        $this->taskRepository = $taskRepository;
        $this->validator      = $validator;
        $this->user           = $user;
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param $list
     * @return \App\Model\Entity\Task[]
     */
    public function getAllAction($list)
    {
        $tasks = $this->taskRepository->findByTodoList(
            $this->todoRepository->find($list)
        );

        return $tasks;
    }

    /**
     * @param int $list
     * @param Request $request
     * @return Task
     */
    public function newAction(int $list, Request $request)
    {
        $task = new Task(
            $request->request->get('name'),
            $request->request->get('isCompleted'),
            $list
        );

        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            throw new ValidateException($errors);
        }

        $this->taskRepository->save($task);

        return $task;
    }

    /**
     * @param Request $request
     * @param $task
     * @return Task
     */
    public function putAction(Request $request, $task)
    {
        $task = $this->taskRepository->find($task);
        $task
            ->setIsCompleted($request->request->get('isCompleted'))
            ->setName($request->request->get('name'));

        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            throw new ValidateException($errors);
        }

        $this->taskRepository->save($task);

        return $task;
    }

    /**
     * @param $list
     * @param $task
     * @return array
     */
    public function deleteAction($list, $task)
    {
        $this->taskRepository->remove(
            $this->taskRepository->find($task)
        );

        return [];
    }

    /**
     * @param $list
     * @return array
     */
    public function removeCompleteAction(int $list)
    {
        $list = $this->todoRepository->find($list);

        $this->taskRepository->removeComplete($list);

        return [];
    }

    /**
     * @param $list
     * @return array
     */
    public function completeAction($list)
    {
        $list = $this->todoRepository->find($list);

        $this->taskRepository->checkComplete($list);

        return [];
    }

    /**
     * @param $list
     * @return array
     */
    public function unCompleteAction($list)
    {
        $list = $this->todoRepository->find($list);

        $this->taskRepository->checkUnComplete($list);

        return [];
    }
}