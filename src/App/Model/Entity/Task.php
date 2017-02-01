<?php

namespace App\Model\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Task implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var boolean
     */
    protected $isCompleted;

    /**
     * @var integer
     */
    protected $todoList;

    /**
     * @param string $name
     * @param bool $isCompleted
     * @param int $todoList
     */
    public function __construct($name, $isCompleted, $todoList)
    {
        $this->name        = $name;
        $this->isCompleted = $isCompleted;
        $this->todoList    = $todoList;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Task
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * @param bool $isCompleted
     * @return Task
     */
    public function setIsCompleted(bool $isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * @return int
     */
    public function getTodoList()
    {
        return $this->todoList;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'isCompleted' => (boolean)$this->isCompleted()
        ];
    }

    /**
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('isCompleted', new Assert\NotBlank());
        $metadata->addPropertyConstraint('todoList', new Assert\NotBlank());
    }
}