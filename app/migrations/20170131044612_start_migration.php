<?php

use Phinx\Migration\AbstractMigration;

class StartMigration extends AbstractMigration
{
    public function change()
    {
        $users = $this->table('users');
        $users
            ->addColumn('email', 'string')
            ->addIndex(['email'], ['unique' => true, 'name' => 'idx_users_email'])
            ->addColumn('password', 'string')
            ->save();

        $todo  = $this->table('todo_lists');
        $todo
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id')
            ->save();

        $tasks = $this->table('tasks');
        $tasks
            ->addColumn('name', 'string')
            ->addColumn('is_completed', 'boolean')
            ->addColumn('todo_list_id', 'integer')
            ->addForeignKey('todo_list_id', 'todo_lists', 'id')
            ->save();
    }
}
