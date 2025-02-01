<?php

namespace App\Service;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FilmService
{
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;
    private FilmRepository $filmRepository;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger, FilmRepository $filmRepository)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->filmRepository = $filmRepository;
    }

    public function addFilm(array $data, ?UploadedFile $file): Film
    {
        $film = new Film();
        $film->setName($data['name']);
        $film->setType($data['type']);
        $film->setOverview($data['overview']);
        $film->setYoutubeLink($data['youtubeLink']);
        $film->setPrice($data['price']);

        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            $uploadDir = __DIR__ . '/../../public/uploads/film_pictures/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $file->move($uploadDir, $fileName);
            $film->setPicture('/uploads/film_pictures/' . $fileName);
        }

        $this->entityManager->persist($film);
        $this->entityManager->flush();

        return $film;
    }
    public function getAllFilms(): array
    {
        $films = $this->filmRepository->findAll();
        return array_map(function ($film) {
            return [
                'id' => $film->getId(),
                'name' => $film->getName(),
                'picture' => $film->getPicture(), // Mostrar la imagen también
            ];
        }, $films);
    }

    public function deleteFilm(int $id): void
    {
        $film = $this->filmRepository->find($id);
        if ($film) {
            $this->entityManager->remove($film);
            $this->entityManager->flush();
        }
    }
}

