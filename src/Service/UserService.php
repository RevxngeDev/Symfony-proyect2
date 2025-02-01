<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerUser(string $email, string $password, string $name, string $lastname, ?string $phone, string $rol = User::ROLE_USER): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Hash de contraseña
        $user->setName($name);
        $user->setLastname($lastname);
        $user->setPhone($phone);
        $user->setRol($rol);
        $user->setCreatedAt(new \DateTime());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUsersWithRoleUser(?string $searchType = null, ?string $searchQuery = null, string $sort = 'asc'): array
    {
        $queryBuilder = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.rol = :role')
            ->setParameter('role', 'ROLE_USER');

        // Aplicar búsqueda
        if ($searchType && $searchQuery) {
            if ($searchType === 'name') {
                $queryBuilder->andWhere('u.name LIKE :query')
                    ->setParameter('query', '%' . $searchQuery . '%');
            } elseif ($searchType === 'email') {
                $queryBuilder->andWhere('u.email LIKE :query')
                    ->setParameter('query', '%' . $searchQuery . '%');
            }
        }

        // Aplicar ordenamiento
        if ($sort === 'asc') {
            $queryBuilder->orderBy('u.createdAt', 'ASC');
        } else {
            $queryBuilder->orderBy('u.createdAt', 'DESC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

}