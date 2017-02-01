<?php

namespace App\Job;

use App\Exception\ValidateException;
use App\Model\Entity\TodoList;
use App\Model\Entity\User;
use App\Model\Repository\TodoListRepository;
use App\Model\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class RegistrationJob
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * @var TodoListRepository
     */
    protected $todoListRepository;

    /**
     * @param UserRepository $userRepository
     * @param PasswordEncoderInterface $encoder
     * @param RecursiveValidator $validator
     * @param TodoListRepository $todoListRepository
     */
    public function __construct(UserRepository $userRepository, PasswordEncoderInterface $encoder, RecursiveValidator $validator, TodoListRepository $todoListRepository)
    {
        $this->userRepository     = $userRepository;
        $this->encoder            = $encoder;
        $this->validator          = $validator;
        $this->todoListRepository = $todoListRepository;
    }

    /**
     * @param $job
     */
    public function handle($job)
    {
        $user = new User(
            $job->email,
            $job->password ? $this->encoder->encodePassword($job->password, null) : null
        );

        if ($this->userRepository->findByEmail($job->email)) {
            throw new ValidateException([
                'email' => 'This email is already exists'
            ]);
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new ValidateException($errors);
        }

        $this->userRepository->save($user);
        $this->todoListRepository->save(new TodoList($user->getId()));
    }
}