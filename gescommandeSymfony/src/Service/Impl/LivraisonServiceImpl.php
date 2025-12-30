<?php
namespace App\Service\Impl;

use App\Service\LivraisonService;
use App\Repository\CommandeRepository;
use App\Repository\ZoneRepository;
use App\Repository\LivreurRepository;
use Doctrine\ORM\EntityManagerInterface;

class LivraisonServiceImpl implements LivraisonService
{
    private $em;
    private $commandeRepository;
    private $zoneRepository;
    private $livreurRepository;

    public function __construct(
        EntityManagerInterface $em,
        CommandeRepository $commandeRepository,
        ZoneRepository $zoneRepository,
        LivreurRepository $livreurRepository
    ) {
        $this->em = $em;
        $this->commandeRepository = $commandeRepository;
        $this->zoneRepository = $zoneRepository;
        $this->livreurRepository = $livreurRepository;
    }

    public function getToutesZones(): array
    {
        return $this->zoneRepository->findAll();
    }

    public function getZonesAvecCommandes(): array
    {
        $zones = $this->zoneRepository->findAll();
        $commandesALivrer = $this->commandeRepository->findBy([
            'type_commande' => 'livraison',
            'etat' => 'preparation'
        ]);

        $zonesAvecCommandes = [];
        foreach ($zones as $zone) {
            $commandesZone = array_filter($commandesALivrer, fn($c) => $c->getZone()?->getId() === $zone->getId());
            
            if (!empty($commandesZone)) {
                $commandesZone = array_values($commandesZone);
                
                $zonesAvecCommandes[] = [
                    'zone' => $zone,
                    'commandes' => $commandesZone,
                    'livreur_affecte' => $commandesZone[0]->getLivreur(),
                    'est_affectee' => $commandesZone[0]->getLivreur() !== null
                ];
            }
        }

        return $zonesAvecCommandes;
    }

    public function getZonesNonAffectees(): array
    {
        $zonesAvecCommandes = $this->getZonesAvecCommandes();
        return array_filter($zonesAvecCommandes, function($z) {
            return isset($z['est_affectee']) && !$z['est_affectee'];
        });
    }
    public function getZonesAffectees(): array
    {
        $zonesAvecCommandes = $this->getZonesAvecCommandes();
        $zonesAffectees = array_filter($zonesAvecCommandes, function($z) {
            return isset($z['est_affectee']) && $z['est_affectee'];
        });
        
        $zonesParLivreur = [];
        foreach ($zonesAffectees as $zoneData) {
            $livreur = $zoneData['livreur_affecte'];
            if ($livreur) {
                $livreurId = $livreur->getId();
                if (!isset($zonesParLivreur[$livreurId])) {
                    $zonesParLivreur[$livreurId] = [
                        'livreur' => $livreur,
                        'zones' => []
                    ];
                }
                $zonesParLivreur[$livreurId]['zones'][] = $zoneData;
            }
        }
        
        return array_values($zonesParLivreur);
    }
    public function getLivreursAvecCommandes(): array
    {
        $livreurs = $this->livreurRepository->findAll();
        $commandesAffectees = $this->commandeRepository->findBy([
            'type_commande' => 'livraison',
            'etat' => 'preparation'
        ], ['livreur' => 'ASC']);

        $livreursAvecCommandes = [];
        foreach ($livreurs as $livreur) {
            $commandesLivreur = array_filter($commandesAffectees, fn($c) => $c->getLivreur()?->getId() === $livreur->getId());
            
            $livreursAvecCommandes[] = [
                'livreur' => $livreur,
                'commandes' => $commandesLivreur
            ];
        }

        return $livreursAvecCommandes;
    }

    public function affecterLivreurAZone(int $zoneId, int $livreurId): array
    {
        $zone = $this->zoneRepository->find($zoneId);
        $livreur = $this->livreurRepository->find($livreurId);

        if (!$zone || !$livreur) {
            return ['success' => false, 'message' => 'Zone ou livreur non trouvé'];
        }

        $commandes = $this->commandeRepository->findBy([
            'zone' => $zone,
            'type_commande' => 'livraison',
            'etat' => 'preparation'
        ]);

        if (empty($commandes)) {
            return ['success' => false, 'message' => 'Aucune commande à affecter dans cette zone'];
        }

        $count = 0;
        foreach ($commandes as $commande) {
            $commande->setLivreur($livreur);
            $count++;
        }

        $this->em->flush();

        return [
            'success' => true,
            'message' => sprintf('Livreur affecté à %d commande(s) de la zone %s', $count, $zone->getNomZone()),
            'count' => $count
        ];
    }

    public function libererZone(int $zoneId): array
    {
        $zone = $this->zoneRepository->find($zoneId);

        if (!$zone) {
            return ['success' => false, 'message' => 'Zone non trouvée'];
        }

        $commandes = $this->commandeRepository->findBy([
            'zone' => $zone,
            'type_commande' => 'livraison',
            'etat' => 'preparation'
        ]);

        $count = 0;
        foreach ($commandes as $commande) {
            $commande->setLivreur(null);
            $count++;
        }

        $this->em->flush();

        return [
            'success' => true,
            'message' => sprintf('Zone %s libérée (%d commande(s))', $zone->getNomZone(), $count),
            'count' => $count
        ];
    }

    public function modifierAffectation(int $zoneId, int $nouveauLivreurId): array
    {
        return $this->affecterLivreurAZone($zoneId, $nouveauLivreurId);
    }

    public function getCommandesParZone(int $zoneId): array
    {
        $zone = $this->zoneRepository->find($zoneId);
        if (!$zone) {
            return [];
        }

        return $this->commandeRepository->findBy([
            'zone' => $zone,
            'type_commande' => 'livraison',
            'etat' => 'preparation'
        ]);
    }

    public function getCommandesParLivreur(int $livreurId): array
    {
        $livreur = $this->livreurRepository->find($livreurId);
        if (!$livreur) {
            return [];
        }

        return $this->commandeRepository->findBy([
            'livreur' => $livreur,
            'type_commande' => 'livraison',
            'etat' => 'preparation'
        ]);
    }
}