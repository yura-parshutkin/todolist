<?php

namespace App\Repository;

use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserDbRepository implements UserRepository, UserProviderInterface
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
     * @return User
     */
    public function find(int $id)
    {
        $sql = '
          SELECT u.id, u.email, u.password  
          FROM users u
          WHERE u.id = ?';

        $user = $this->db->fetchAssoc($sql, [$id]);

        return $user ? $this->createUser($user) : null;
    }

    /**
     * @param string $username
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findByEmail($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email)
    {
        $sql = '
          SELECT u.id, u.email, u.password  
          FROM users u
          WHERE u.email = ?';

        $user = $this->db->fetchAssoc($sql, [$email]);

        if (!$user) {
            return null;
        }

        return $this->createUser($user);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $params = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ];

        if ($user->getId()) {
            $this->db->update('users', $params, [
                'id' => $user->getId()
            ]);
        } else {
            $this->db->insert('users', $params);
            $this->setId($user, $this->db->lastInsertId());
        }
    }

    /**
     * @param User $user
     * @param int $id
     */
    protected function setId(User $user, int $id)
    {
        $ref = new \ReflectionClass(User::class);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($user, $id);
    }

    /**
     * @param $data
     * @return User
     */
    protected function createUser(array $data) : User
    {
        $user = new User($data['email'], $data['password']);

        $this->setId($user, $data['id']);

        return $user;
    }
}