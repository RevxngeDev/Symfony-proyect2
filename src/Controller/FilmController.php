<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
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
}

