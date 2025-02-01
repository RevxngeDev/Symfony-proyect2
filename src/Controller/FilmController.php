<?php

namespace App\Controller;

use App\Service\FilmService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/addFilms', name: 'app_add_films', methods: ['GET', 'POST'])]
    public function addFilms(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $file = $request->files->get('picture');

            $this->filmService->addFilm($data, $file);

            return $this->redirectToRoute('app_add_films');
        }

        // Cargar las películas existentes desde la base de datos
        $films = $this->filmService->getAllFilms();

        return $this->render('addFilms.html.twig', ['films' => $films]);
    }

    #[Route('/api/films', name: 'get_films', methods: ['GET'])]
    public function getFilms(): JsonResponse
    {
        $films = $this->filmService->getAllFilms();
        return $this->json($films);
    }

    #[Route('/films/{id}', name: 'delete_film', methods: ['DELETE'])]
    public function deleteFilm(int $id): JsonResponse
    {
        $this->filmService->deleteFilm($id);
        return new JsonResponse(null, 204); // Respuesta 204 (No Content)
    }
}

