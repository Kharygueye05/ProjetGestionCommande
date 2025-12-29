<?php 
namespace App\Service\Impl;

use App\Repository\CommandeRepository;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;

class CommandeServiceImpl implements CommandeService
{
    private $commandeRepository;
    private $em;

    public function __construct(
        CommandeRepository $commandeRepository,
        EntityManagerInterface $em
    ) {
        $this->commandeRepository = $commandeRepository;
        $this->em = $em;
    }

    public function filtrerCommandes(array $filters): array
    {
        $formattedFilters = [];
        
        if (!empty($filters['client'])) {
            $formattedFilters['client'] = $filters['client'];
        }
        
        if (!empty($filters['date'])) {
            try {
                $date = \DateTime::createFromFormat('d/m/Y', $filters['date']);
                if ($date) {
                    $formattedFilters['date_debut'] = $date->format('Y-m-d');
                    $formattedFilters['date_fin'] = $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
            }
        }
        
        if (!empty($filters['produitType'])) {
            $formattedFilters['produit_type'] = $filters['produitType'];
        }
        
        if (!empty($filters['etat'])) {
            $formattedFilters['etat'] = $filters['etat'];
        }
        
        return $this->commandeRepository->findByFilters($formattedFilters);
    }

    public function changerEtat(int $commandeId, string $etat): void
    {
        $commande = $this->commandeRepository->find($commandeId);
        
        if (!$commande) {
            throw new \Exception("Commande non trouvée");
        }
        
        $commande->setEtat($etat);
        $this->em->flush();
    }

    public function getCommandeDetails(int $commandeId): ?array
    {
        $commande = $this->commandeRepository->find($commandeId);
        
        if (!$commande) {
            return null;
        }
        
        $details = [
            'commande' => $commande,
            'lignes' => [],
            'montant_total' => (float) $commande->getMontant()
        ];
        
        foreach ($commande->getLigneCommandes() as $ligne) {
            $details['lignes'][] = [
                'produit_nom' => $ligne->getProduitNom(),
                'quantite' => $ligne->getQuantite(),
                'prix_unitaire' => (float) $ligne->getPrixUnitaire(),
                'total_ligne' => $ligne->getQuantite() * (float) $ligne->getPrixUnitaire()
            ];
        }
        
        return $details;
    }
}