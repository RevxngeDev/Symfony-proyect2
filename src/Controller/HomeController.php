<?php

namespace App\Controller;

use App\Service\FilmService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private Security $security;
    private FilmService $filmService;
    private LoggerInterface $logger;

    public function __construct(Security $security, FilmService $filmService, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->filmService = $filmService;
        $this->logger = $logger;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Obtener el usuario autenticado
        $user = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'Guest';

        $this->logger->info("El usuario '$username' accedió a la página de inicio.");

        $topLikedFilms = $this->filmService->getTopLikedFilms();

        return $this->render('home/index.html.twig', [
            'user' => $user,
            'topLikedFilms' => $topLikedFilms,
        ]);
    }

    #[Route('/api/home/films', name: 'api_home_films', methods: ['GET'])]
    public function getFilms(): JsonResponse
    {
        $this->logger->info("Se solicitó la lista de películas desde la API.");

        $films = $this->filmService->getAllFilms();
        return $this->json($films);
    }
}
