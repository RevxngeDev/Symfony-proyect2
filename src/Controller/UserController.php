<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/users', name: 'app_users')]
    public function index(Request $request): Response
    {
        // Obtener parámetros de la solicitud
        $searchType = $request->query->get('searchType'); // Tipo de búsqueda (name o email)
        $searchQuery = $request->query->get('q'); // Término de búsqueda
        $sort = $request->query->get('sort', 'asc'); // Ordenamiento (asc o desc)

        // Obtener los usuarios con el rol ROLE_USER, aplicando búsqueda y ordenamiento
        $usersList = $this->userService->getUsersWithRoleUser($searchType, $searchQuery, $sort);

        return $this->render('users.html.twig', [
            'usersList' => $usersList,
            'searchType' => $searchType,
            'searchQuery' => $searchQuery,
            'sort' => $sort,
        ]);
    }
}