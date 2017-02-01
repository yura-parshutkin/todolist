<?php

namespace App\Model\Repository;

use App\Model\Entity\User;

interface UserRepository
{
    /**
     * @param int $id
     * @return User
     */
    public function find(int $id);

    /**
     * @param User $user
     */
    public function save(User $user);

    /**
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email);
}