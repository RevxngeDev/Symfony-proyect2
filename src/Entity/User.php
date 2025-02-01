<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER'; // Definir constante para el rol de usuario
    public const ROLE_ADMIN = 'ROLE_ADMIN'; // Definir constante para el rol de administrador

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: "string")]
    private string $password;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 100)]
    private string $lastname;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $phone;

    #[ORM\Column(type: "string", length: 20)]
    private string $rol = self::ROLE_USER; // Usar la constante para el valor por defecto

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    // Getters y Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;
        return $this;
    }

    // Métodos requeridos por UserInterface
    public function getRoles(): array
    {
        return [$this->rol]; // Devuelve un array con el rol del usuario
    }

    public function eraseCredentials(): void
    {
        // No es necesario hacer nada aquí, pero el método debe estar implementado
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // Devuelve el email como identificador único del usuario
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}