<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
class Livreur
{
    #[ORM\Id]
    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    private ?Users $user = null;

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;
        return $this;
    }
    
    // Méthodes d'accès aux infos Users
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
}