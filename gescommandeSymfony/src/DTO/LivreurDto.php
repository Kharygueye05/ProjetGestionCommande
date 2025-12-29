<?php
// src/DTO/LivreurDto.php
namespace App\DTO;

use App\Entity\Livreur;

class LivreurDto
{
    public int $id;
    public string $nom;
    public string $prenom;
    public string $telephone;
    public array $zonesAffectees = [];
    public int $nombreZones = 0;
    public int $nombreCommandes = 0;

    public static function fromEntity(Livreur $livreur, ?array $commandes = null): self
    {
        $dto = new self();
        $dto->id = $livreur->getId();
        $dto->nom = $livreur->getUser()->getNom();
        $dto->prenom = $livreur->getUser()->getPrenom();
        $dto->telephone = $livreur->getUser()->getTelephone();

        $zones = [];
        if ($commandes) {
            foreach ($commandes as $commande) {
                if ($zone = $commande->getZone()) {
                    $zoneId = $zone->getId();
                    if (!isset($zones[$zoneId])) {
                        $zones[$zoneId] = [
                            'zone' => $zone,
                            'commandes' => []
                        ];
                    }
                    $zones[$zoneId]['commandes'][] = $commande;
                }
            }
            
            $dto->zonesAffectees = $zones;
            $dto->nombreZones = count($zones);
            $dto->nombreCommandes = count($commandes);
        }

        return $dto;
    }

    public static function fromEntities(array $livreursData): array
    {
        return array_map(function($livreurData) {
            $livreur = $livreurData['livreur'];
            $commandes = $livreurData['commandes'] ?? [];
            
            return self::fromEntity($livreur, $commandes);
        }, $livreursData);
    }
}