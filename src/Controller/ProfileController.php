<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends AbstractController
{
    private Security $security;
    private SluggerInterface $slugger;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Security $security,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request): Response
    {
        // Obtener el usuario autenticado
        $user = $this->security->getUser();

        // Verificar si el usuario está autenticado
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Procesar la subida de la imagen de perfil
        if ($request->isMethod('POST')) {
            $file = $request->files->get('file');

            if ($file instanceof UploadedFile) {
                // Eliminar la imagen anterior si existe
                $oldProfilePicture = $user->getProfilePicture();
                if ($oldProfilePicture && file_exists($this->getParameter('kernel.project_dir') . '/public' . $oldProfilePicture)) {
                    unlink($this->getParameter('kernel.project_dir') . '/public' . $oldProfilePicture);
                }

                // Generar un nombre único para el archivo
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Guardar la imagen en una carpeta dentro de "public"
                $publicPath = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_images/';
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }

                $file->move($publicPath, $fileName);

                // Guardar solo la ruta relativa en la base de datos
                $relativePath = '/uploads/profile_images/' . $fileName;
                $user->setProfilePicture($relativePath);
                $this->entityManager->flush();

                // Si es una solicitud AJAX, devolver una respuesta JSON
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'status' => 'success',
                        'message' => 'Profile picture updated successfully.',
                        'profilePicture' => $relativePath,
                    ]);
                }

                // Redirigir para evitar que el formulario se envíe nuevamente al recargar
                return $this->redirectToRoute('app_profile');
            }
        }

        // Pasar los datos del usuario a la plantilla
        return $this->render('profile.html.twig', [
            'userName' => $user->getName() . ' ' . $user->getLastname(),
            'email' => $user->getEmail(),
            'firstName' => $user->getName(),
            'lastName' => $user->getLastname(),
            'profilePicture' => $user->getProfilePicture() ?? '/assets/images/profile-placeholder.jpg',
        ]);
    }
}