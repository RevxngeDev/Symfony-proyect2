<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use App\Repository\FilmRepository;
use App\Service\FilmService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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

    #[Route('/film/{id}', name: 'film_details')]
    public function details(FilmRepository $filmRepository, int $id): Response
    {
        $film = $filmRepository->find($id);
        if (!$film) {
            throw $this->createNotFoundException('Película no encontrada.');
        }
        return $this->render('films.html.twig', [
            'film' => $film,
            'pricePerSeat' => $film->getPrice()
        ]);
    }

    #[Route('/film/{id}/like', name: 'film_like', methods: ['POST'])]
    public function likeFilm(Request $request, Film $film): JsonResponse
    {
        $user = $this->getUser(); // Obtiene el usuario actual

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        // Verifica si el usuario ya dio "like"
        if ($this->filmService->hasUserLikedFilm($user, $film)) {
            $this->filmService->unlikeFilm($user, $film);
            $action = 'unliked';
        } else {
            $this->filmService->likeFilm($user, $film);
            $action = 'liked';
        }

        // Devuelve la respuesta JSON con el nuevo conteo de "likes"
        return new JsonResponse([
            'action' => $action,
            'likes' => $film->getLikes(),
        ]);
    }

    #[Route('/film/{id}/reserve-seats', name: 'reserve_seats', methods: ['POST'])]
    public function reserveSeats(
        Request $request,
        Film $film,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $seats = $request->request->all('seats');
        $totalPrice = $request->request->get('total_price');

        if (!empty($seats)) {
            // Reservar los asientos
            $film->reserveSeats($seats);
            $em->persist($film);
            $em->flush();

            // Obtener el usuario actual (asumiendo que está autenticado)
            $user = $this->getUser();
            if ($user) {
                // Enviar correo de confirmación
                $email = (new TemplatedEmail())
                    ->from(new Address('no-reply@cine.com', 'Cine App'))
                    ->to($user->getEmail()) // Asegúrate de que tu entidad User tenga un método getEmail()
                    ->subject('Confirmación de Reserva')
                    ->htmlTemplate('emails/reservation_confirmation.html.twig')
                    ->context([
                        'user' => $user->getName(), // O cualquier otro campo que identifique al usuario
                        'film' => $film->getName(),
                        'seats' => $seats,
                        'total_price' => $totalPrice,
                    ]);

                $mailer->send($email);
            }

            $this->addFlash('success', 'Seats reserved successfully!');
        } else {
            $this->addFlash('error', 'No seats selected.');
        }

        return $this->redirectToRoute('film_details', ['id' => $film->getId()]);
    }

}

