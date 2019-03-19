<?php

namespace App\Manager;

use App\Entity\TodoItem;
use Doctrine\ORM\EntityManagerInterface;

class TodoManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TodoManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param TodoItem $todoItem
     * @return TodoItem
     */
    public function create(TodoItem $todoItem): TodoItem
    {
        $this->entityManager->persist($todoItem);
        $this->entityManager->flush();

        return $todoItem;
    }

    /**
     * @param TodoItem $todoItem
     * @return TodoItem
     */
    public function done(TodoItem $todoItem): TodoItem
    {
        $todoItem->setDone(true);

        $this->entityManager->persist($todoItem);
        $this->entityManager->flush();

        return $todoItem;
    }
}