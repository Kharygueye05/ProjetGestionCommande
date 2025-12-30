<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $archive = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_burger', referencedColumnName: 'id')]
    private ?Burger $burger = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_complement_boisson', referencedColumnName: 'id')]
    private ?Complement $complementBoisson = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_complement_frites', referencedColumnName: 'id')]
    private ?Complement $complementFrites = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): static
    {
        $this->archive = $archive;
        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): static
    {
        $this->burger = $burger;
        return $this;
    }

    public function getComplementBoisson(): ?Complement
    {
        return $this->complementBoisson;
    }

    public function setComplementBoisson(?Complement $complementBoisson): static
    {
        $this->complementBoisson = $complementBoisson;
        return $this;
    }

    public function getComplementFrites(): ?Complement
    {
        return $this->complementFrites;
    }

    public function setComplementFrites(?Complement $complementFrites): static
    {
        $this->complementFrites = $complementFrites;
        return $this;
    }

    public function getPrixTotal(): float
    {
        $total = 0;
        
        if ($this->burger) {
            $total += (float) $this->burger->getPrix();
        }
        
        if ($this->complementBoisson) {
            $total += (float) $this->complementBoisson->getPrix();
        }
        
        if ($this->complementFrites) {
            $total += (float) $this->complementFrites->getPrix();
        }
        
        return $total;
    }
}