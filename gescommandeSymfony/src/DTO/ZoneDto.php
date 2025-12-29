<?php
// src/DTO/ZoneDto.php
namespace App\DTO;

use App\Entity\Zone;

class ZoneDto
{
    public int $id;
    public string $nomZone;
    public float $prixLivraison;
    public array $quartiers = [];
    public int $nombreCommandes = 0;
    public float $totalMontant = 0;
    public ?int $livreurId = null;
    public ?string $livreurNom = null;
    public bool $estAffectee = false;

    public static function fromEntity(Zone $zone, ?array $commandes = null, ?bool $estAffectee = false): self
    {
        $dto = new self();
        $dto->id = $zone->getId();
        $dto->nomZone = $zone->getNomZone();
        $dto->prixLivraison = (float)$zone->getPrixLivraison();
        
        $quartiers = $zone->getQuartiers();
        if ($quartiers) {
            $dto->quartiers = array_map('trim', explode(',', $quartiers));
        }

        if ($commandes) {
            $dto->nombreCommandes = count($commandes);
            $dto->totalMontant = array_sum(array_map(fn($c) => (float)$c->getMontant(), $commandes));
            
            if (!empty($commandes) && $commandes[0]->getLivreur()) {
                $livreur = $commandes[0]->getLivreur();
                $dto->livreurId = $livreur->getId();
                $dto->livreurNom = $livreur->getUser()->getPrenom() . ' ' . $livreur->getUser()->getNom();
                $dto->estAffectee = true;
            }
        } else {
            $dto->estAffectee = $estAffectee;
        }

        return $dto;
    }

    public static function fromEntities(array $zonesData): array
    {
        return array_map(function($zoneData) {
            $zone = $zoneData['zone'];
            $commandes = $zoneData['commandes'] ?? [];
            $estAffectee = $zoneData['est_affectee'] ?? false;
            
            return self::fromEntity($zone, $commandes, $estAffectee);
        }, $zonesData);
    }
}