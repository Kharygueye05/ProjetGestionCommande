<?php

namespace App\Entity;

use App\Repository\GestionnaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GestionnaireRepository::class)]
class Gestionnaire
{
    #[ORM\Id]
    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    private ?Users $user = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(type: 'text')]
    private ?string $password = null;

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->user?->getId();
    }
    
    public function getNom(): ?string
    {
        return $this->user?->getNom();
    }
}