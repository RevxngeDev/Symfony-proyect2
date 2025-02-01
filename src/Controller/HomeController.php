<?php

namespace App\Controller;

use App\Service\FilmService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private Security $security;
    private FilmService $filmService;

    public function __construct(Security $security, FilmService $filmService)
    {
        $this->security = $security;
        $this->filmService = $filmService;
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

    #[Route('/api/home/films', name: 'api_home_films', methods: ['GET'])]
    public function getFilms(): JsonResponse
    {
        $films = $this->filmService->getAllFilms();
        return $this->json($films);
    }
}