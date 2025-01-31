<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }
    #[Route('/addFilms', name: 'app_add_films')]
    public function addFilms(): Response
    {
        // Datos ficticios para previsualización si fueran necesarios.
        return $this->render('addFilms.html.twig');
    }
}
