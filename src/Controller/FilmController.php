<?php

namespace App\Controller;

use App\Service\FilmService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class FilmController extends AbstractController
{
    private FilmService $filmService;

    public function __construct(FilmService $filmService)
    {
        $this->filmService = $filmService;
    }
    #[Route('/films', name: 'app_films')]
    public function index(): Response
    {
        // Datos ficticios
        $film = [
            'id' => 1,
            'name' => 'Film Title',
            'picture' => '/assets/images/film-placeholder.jpg',
            'overview' => 'An amazing movie about a hero saving the world.',
            'type' => 'Action',
            'price' => 10.00,
        ];

        return $this->render('films.html.twig', ['film' => $film]);
    }

    #[Route('/addFilms', name: 'app_add_films', methods: ['GET', 'POST'])]
    public function addFilms(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $file = $request->files->get('picture');

            $this->filmService->addFilm($data, $file);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('addFilms.html.twig');
    }
}

