<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtener el error de autenticación si existe
        $error = $authenticationUtils->getLastAuthenticationError();

        // Obtener el último nombre de usuario ingresado
        $lastUsername = $authenticationUtils->getLastUsername();

        // Renderizar la plantilla de login con los datos
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername, // Enviar el último nombre de usuario
            'error' => $error, // Enviar el error de autenticación
        ]);
    }
}