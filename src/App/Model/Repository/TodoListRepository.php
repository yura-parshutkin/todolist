<?php

namespace App\Model\Repository;

use App\Model\Entity\TodoList;
use App\Model\Entity\User;

interface TodoListRepository
{
    /**
     * @param int $id
     * @return TodoList
     */
    public function find(int $id);

    /**
     * @param TodoList $todoList
     */
    public function save(TodoList $todoList);

    /**
     * @param User $user
     * @return TodoList
     */
    public function findActive(User $user) : TodoList;
}