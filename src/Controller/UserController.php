<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(): Response
    {
        // Datos ficticios
        $usersList = [
            ['id' => 1, 'email' => 'johndoe@example.com', 'firstName' => 'John', 'lastName' => 'Doe'],
            ['id' => 2, 'email' => 'janedoe@example.com', 'firstName' => 'Jane', 'lastName' => 'Doe'],
        ];

        return $this->render('users.html.twig', [
            'usersList' => $usersList,
        ]);
    }
}
