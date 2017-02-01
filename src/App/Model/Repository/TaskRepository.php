<?php

namespace App\Model\Repository;

use App\Model\Entity\Task;
use App\Model\Entity\TodoList;

interface TaskRepository
{
    /**
     * @param int $id
     * @return Task
     */
    public function find(int $id);

    /**
     * @param TodoList $list
     * @return Task[]
     */
    public function findByTodoList(TodoList $list);

    /**
     * @param Task $task
     * @return boolean
     */
    public function remove(Task $task);

    /**
     * @param Task $task
     */
    public function save(Task $task);

    /**
     * @param TodoList $list
     * @return integer
     */
    public function checkUnComplete(TodoList $list)  :int;

    /**
     * @param TodoList $list
     * @return integer
     */
    public function checkComplete(TodoList $list)  :int;

    /**
     * @param TodoList $list
     * @return int
     */
    public function removeComplete(TodoList $list) :int;
}