<?php 
namespace App\Service;

interface CommandeService
{
    public function filtrerCommandes(array $filters): array;
    public function changerEtat(int $commandeId, string $etat): void;
    public function getCommandeDetails(int $commandeId): ?array;
}