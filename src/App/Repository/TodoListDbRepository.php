<?php

namespace App\Repository;

use App\Model\Entity\TodoList;
use App\Model\Entity\User;
use App\Model\Repository\TodoListRepository;
use Doctrine\DBAL\Connection;

class TodoListDbRepository implements TodoListRepository
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param $id
     * @return TodoList
     */
    public function find(int $id)
    {
        $sql = '
          SELECT t.id, t.user_id  
          FROM todo_lists t
          WHERE t.id = ?';

        $todo = $this->db->fetchAssoc($sql, [ $id ]);

        return ($todo) ? $this->createTodoList($todo) : null;
    }

    /**
     * @param TodoList $todoList
     */
    public function save(TodoList $todoList)
    {
        $params = [
            'user_id'  => $todoList->getUser(),
        ];

        if ($todoList->getId()) {
            $this->db->update('todo_lists', $params, [
                'id' => $todoList->getId()
            ]);
        } else {
            $this->db->insert('todo_lists', $params);
            $this->setId($todoList, $this->db->lastInsertId());
        }
    }

    /**
     * @param User $user
     * @return TodoList
     */
    public function findActive(User $user): TodoList
    {
        $sql = '
          SELECT t.id, t.user_id  
          FROM todo_lists t
          WHERE t.user_id = ?';

        $todo = $this->db->fetchAssoc($sql, [ $user->getId() ]);

        return ($todo) ? $this->createTodoList($todo) : null;
    }


    /**
     * @param TodoList $todoList
     * @param int $id
     */
    protected function setId(TodoList $todoList, int $id)
    {
        $ref  = new \ReflectionClass(TodoList::class);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($todoList, $id);
    }

    /**
     * @param array $todo
     * @return TodoList
     */
    protected function createTodoList(array $todo) : TodoList
    {
        $todoList = new TodoList((int)$todo['user_id']);
        $this->setId($todoList, $todo['id']);

        return $todoList;
    }
}