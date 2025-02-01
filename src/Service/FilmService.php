<?php

namespace App\Service;

use App\Entity\Film;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FilmService
{
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
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
}

