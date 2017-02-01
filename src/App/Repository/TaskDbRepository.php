<?php

namespace App\Repository;

use App\Model\Entity\Task;
use App\Model\Entity\TodoList;
use App\Model\Repository\TaskRepository;
use Doctrine\DBAL\Connection;

class TaskDbRepository implements TaskRepository
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
     * @param int $id
     * @return Task
     */
    public function find(int $id)
    {
        $sql = '
          SELECT t.id, t.name, t.is_completed, t.todo_list_id  
          FROM tasks t
          WHERE t.id = ?';

        $task = $this->db->fetchAssoc($sql, [ $id ]);

        return $task ? $this->createTask($task) : null;
    }

    /**
     * @param Task $task
     */
    public function save(Task $task)
    {
        $params = [
            'name'         => $task->getName(),
            'is_completed' => (integer)$task->isCompleted(),
            'todo_list_id' => $task->getTodoList()
        ];

        if ($task->getId()) {
            $this->db->update('tasks', $params, [
                'id' => $task->getId()
            ]);
        } else {
            $this->db->insert('tasks', $params);
            $this->setId($task, $this->db->lastInsertId());
        }
    }

    /**
     * @param TodoList $list
     * @return Task[]
     */
    public function findByTodoList(TodoList $list)
    {
        $sql = '
          SELECT t.id, t.name, t.is_completed, t.todo_list_id
          FROM tasks t
          WHERE t.todo_list_id = :todo_list
        ';

        $params = [
            'todo_list' => $list->getId()
        ];

        return array_map(function($item){
            return $this->createTask($item);
        }, $this->db->fetchAll($sql, $params));
    }

    /**
     * @param Task $task
     * @return int
     */
    public function remove(Task $task)
    {
        return $this->db->delete('tasks', [
            'id' => $task->getId()
        ]);
    }

    /**
     * @param TodoList $list
     * @return int
     */
    public function checkUnComplete(TodoList $list)  :int
    {
        $params = [
            'is_completed' => 0
        ];

        return $this->db->update('tasks', $params, [
            'todo_list_id' => $list->getId()
        ]);
    }

    /**
     * @param TodoList $list
     * @return int
     */
    public function checkComplete(TodoList $list)  :int
    {
        $params = [
            'is_completed' => 1
        ];

        return $this->db->update('tasks', $params, [
            'todo_list_id' => $list->getId()
        ]);
    }

    /**
     * @param TodoList $list
     * @return int
     */
    public function removeComplete(TodoList $list) :int
    {
        return $this->db->delete('tasks', [
            'todo_list_id' => $list->getId(),
            'is_completed' => 1
        ]);
    }

    /**
     * @param $params
     * @return Task
     */
    protected function createTask(array $params) : Task
    {
        $task = new Task($params['name'], $params['is_completed'], $params['todo_list_id']);
        $this->setId($task, $params['id']);

        return $task;
    }

    /**
     * @param Task $task
     * @param int $id
     */
    protected function setId(Task $task, int $id)
    {
        $ref  = new \ReflectionClass(Task::class);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($task, $id);
    }
}