<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Datos ficticios (pueden ser reemplazados con datos reales del backend en el futuro)
        $userData = [
            'userName' => 'John Doe',
            'email' => 'johndoe@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'profilePicture' => '/assets/images/profile-placeholder.jpg'
        ];

        return $this->render('profile.html.twig', $userData);
    }
}
