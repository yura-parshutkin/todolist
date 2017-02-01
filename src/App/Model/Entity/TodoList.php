<?php

namespace App\Model\Entity;

class TodoList
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var integer
     */
    protected $user;

    /**
     * @param int $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isOwner(User $user)
    {
        return $this->getUser() === $user->getId();
    }
}