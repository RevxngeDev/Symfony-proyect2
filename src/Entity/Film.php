<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $picture;

    #[ORM\Column(length: 50)]
    private string $type;

    #[ORM\Column(type: 'text')]
    private string $overview;

    #[ORM\Column(length: 255)]
    private string $youtubeLink;

    #[ORM\Column(type: 'float')]
    private float $price;

    // Getters y Setters
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getPicture(): string { return $this->picture; }
    public function setPicture(string $picture): self { $this->picture = $picture; return $this; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }

    public function getOverview(): string { return $this->overview; }
    public function setOverview(string $overview): self { $this->overview = $overview; return $this; }

    public function getYoutubeLink(): string { return $this->youtubeLink; }
    public function setYoutubeLink(string $youtubeLink): self { $this->youtubeLink = $youtubeLink; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }
}
