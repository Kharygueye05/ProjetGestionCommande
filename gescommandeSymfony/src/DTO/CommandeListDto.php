<?php
namespace App\DTO;

use App\Entity\Commande;

class CommandeListDto
{
    public int $id;
    public string $date;
    public string $heure;
    public string $clientNom;
    public string $clientPrenom;
    public string $clientTelephone;
    public string $typeCommande;
    public string $etat;
    public string $montant;
    public array $produits = [];

    public static function fromEntity(Commande $commande): self
    {
        $dto = new self();
        $dto->id = $commande->getId();
        $dto->date = $commande->getDateCommande()->format('d/m/Y');
        $dto->heure = $commande->getDateCommande()->format('H:i');
        $dto->clientNom = $commande->getClient()->getUser()->getNom();
        $dto->clientPrenom = $commande->getClient()->getUser()->getPrenom();
        $dto->clientTelephone = $commande->getClient()->getUser()->getTelephone();
        $dto->typeCommande = $commande->getTypeCommande();
        $dto->etat = $commande->getEtat();
        $dto->montant = number_format((float)$commande->getMontant(), 0, ',', ' ');

        foreach ($commande->getLigneCommandes() as $ligne) {
            $dto->produits[] = [
                'nom' => $ligne->getProduitNom(),
                'quantite' => $ligne->getQuantite(),
                'type' => $ligne->getTypeProduit()
            ];
        }

        return $dto;
    }

    public static function fromEntities(array $commandes): array
    {
        return array_map(function(Commande $commande) {
            return self::fromEntity($commande);
        }, $commandes);
    }

    public function getBadgeTypeClass(): string
    {
        return 'badge-type';
    }

    public function getBadgeEtatClass(): string
    {
        switch ($this->etat) {
            case 'reçue':
                return 'badge-status received';
            case 'annulee':
                return 'badge-status cancelled';
            case 'terminee':
                return 'badge-status completed';
            case 'preparation':
                return 'badge-status preparation';
            default:
                return 'badge-status';
        }
    }

    public function getEtatDisplay(): string
    {
        $map = [
            'reçue' => 'Reçue',
            'annulee' => 'Annulée',
            'terminee' => 'Terminée',
            'preparation' => 'Préparation'
        ];
        return $map[$this->etat] ?? $this->etat;
    }

    public function getTypeDisplay(): string
    {
        $map = [
            'sur_place' => 'Sur place',
            'a_emporter' => 'A emporter',
            'livraison' => 'Livraison'
        ];
        return $map[$this->typeCommande] ?? $this->typeCommande;
    }


    public function canChangeEtat(): bool
    {
        return in_array($this->etat, ['reçue', 'preparation']);
    }

    public function getAvailableEtatActions(): array
    {
        $actions = [];
        
        if ($this->etat === 'reçue') {
            $actions = [
                'preparation' => 'Mettre en préparation',
                'terminee' => 'Terminer',
                'annulee' => 'Annuler'
            ];
        } elseif ($this->etat === 'preparation') {
            $actions = [
                'terminee' => 'Terminer'
            ];
        }
        
        return $actions;
    }
}