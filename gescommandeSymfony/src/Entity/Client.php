<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    private ?Users $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT)]
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
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
    
    public function getPrenom(): ?string
    {
        return $this->user?->getPrenom();
    }
    
    public function getTelephone(): ?string
    {
        return $this->user?->getTelephone();
    }
}