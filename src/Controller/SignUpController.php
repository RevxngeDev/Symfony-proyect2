<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    #[Route('/signUp', name: 'app_sign_up')]
    public function signUp(): Response
    {
        return $this->render('signUp.html.twig');
    }
}

