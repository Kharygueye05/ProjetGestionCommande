<?php
namespace App\Service;

interface LivraisonService
{
    public function getToutesZones(): array;
    public function getZonesAvecCommandes(): array;
    public function getZonesNonAffectees(): array;
    public function getZonesAffectees(): array;
    public function getLivreursAvecCommandes(): array;
    public function affecterLivreurAZone(int $zoneId, int $livreurId): array;
    public function libererZone(int $zoneId): array;
    public function modifierAffectation(int $zoneId, int $nouveauLivreurId): array;
    public function getCommandesParZone(int $zoneId): array;
    public function getCommandesParLivreur(int $livreurId): array;
}