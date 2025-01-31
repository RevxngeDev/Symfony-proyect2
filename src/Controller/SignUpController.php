<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/signUp', name: 'app_sign_up', methods: ['GET', 'POST'])]
    public function signUp(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $name = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $phone = $request->request->get('phone');

            $this->userService->registerUser($email, $password, $name, $lastname, $phone);

            return $this->redirectToRoute('app_login'); // Redirige después del registro
        }

        return $this->render('signUp.html.twig');
    }
}

