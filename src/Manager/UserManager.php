<?php

namespace App\Manager;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return User
     */
    public function register(User $user): User
    {
        $user->setEnabled(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}