<?php

namespace Frontend\Controller;

use App\Model\Entity\TodoList;
use App\Model\Entity\User;
use App\Model\Repository\TodoListRepository;
use Symfony\Component\HttpFoundation\Response;

class HomepageController
{
    /**
     * @var \Twig_Environment
     */
    protected $template;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var TodoListRepository
     */
    protected $todoListRepository;

    /**
     * @param \Twig_Environment $template
     * @param TodoListRepository $todoList
     * @param User $user
     */
    public function __construct(\Twig_Environment $template, TodoListRepository $todoList, User $user)
    {
        $this->template           = $template;
        $this->user               = $user;
        $this->todoListRepository = $todoList;
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        $todo = $this->todoListRepository->findActive($this->user);

        return new Response($this->template->render('homepage/index.twig', [
            'todo' => $todo
        ]));
    }
}