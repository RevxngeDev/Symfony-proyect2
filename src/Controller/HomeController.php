<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Obtener el usuario autenticado
        $user = $this->security->getUser();

        // Pasar los datos del usuario a la plantilla
        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }
}