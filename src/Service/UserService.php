<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerUser(string $email, string $password, string $name, string $lastname, ?string $phone): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Hash de contraseña
        $user->setName($name);
        $user->setLastname($lastname);
        $user->setPhone($phone);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
