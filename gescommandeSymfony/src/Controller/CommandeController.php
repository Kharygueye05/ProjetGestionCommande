<?php 
namespace App\Controller;

use App\DTO\CommandeListDto;
use App\DTO\SearchFiltersDto;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(
        CommandeRepository $commandeRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $searchDto = new SearchFiltersDto();
        $searchDto->client = $request->query->get('client');
        $searchDto->date = $request->query->get('date');
        $searchDto->produitType = $request->query->get('produitType');
        $searchDto->etat = $request->query->get('etat');

        $filters = [];
        
        if ($searchDto->client) {
            $filters['client'] = $searchDto->client;
        }
        
        if ($searchDto->date) {
            $filters['date'] = $searchDto->date;
        }
        
        if ($searchDto->produitType) {
            $filters['produit_type'] = $searchDto->produitType;
        }
        
        if ($searchDto->etat) {
            $filters['etat'] = $searchDto->etat;
        }

        $query = $commandeRepository->createQueryByFilters($filters);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 
        );

        $commandes = $pagination->getItems();
        $commandesDto = CommandeListDto::fromEntities($commandes);

        $etats = [
            'reçue' => 'Reçue',
            'preparation' => 'Préparation',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée'
        ];

        $produitTypes = [
            'burger' => 'Burgers',
            'menu' => 'Menus',
        ];
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandesDto,
            'pagination' => $pagination,
            'filters' => $searchDto,
            'etats' => $etats,
            'produitTypes' => $produitTypes,
        ]);
    }

    #[Route('/{id}/etat', name: 'app_commande_changer_etat', methods: ['POST'])]
    public function changerEtat(
        int $id,
        Request $request,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $em
    ): Response {
        $etat = $request->request->get('etat');
        
        if (!$etat) {
            $this->addFlash('error', 'État non spécifié');
            return $this->redirectToRoute('app_commande_index');
        }

        $commande = $commandeRepository->find($id);
        
        if (!$commande) {
            $this->addFlash('error', 'Commande non trouvée');
            return $this->redirectToRoute('app_commande_index');
        }

        $commande->setEtat($etat);
        $em->flush();
        
        $this->addFlash('success', 'État de la commande mis à jour');
        return $this->redirectToRoute('app_commande_index');
    }

    #[Route('/{id}/annuler', name: 'app_commande_annuler', methods: ['POST'])]
    public function annuler(
        int $id,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $em
    ): Response {
        $commande = $commandeRepository->find($id);
        
        if (!$commande) {
            $this->addFlash('error', 'Commande non trouvée');
            return $this->redirectToRoute('app_commande_index');
        }

        $commande->setEtat('annulee');
        $em->flush();
        
        $this->addFlash('success', 'Commande annulée avec succès');
        return $this->redirectToRoute('app_commande_index');
    }

    #[Route('/{id}/modal', name: 'app_commande_modal', methods: ['GET'])]
    public function modal(
        int $id,
        CommandeRepository $commandeRepository
    ): Response {
        $commande = $commandeRepository->find($id);
        
        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        $details = [
            'commande' => $commande,
            'lignes' => $commande->getLigneCommandes()->toArray(),
            'montant_total' => (float) $commande->getMontant()
        ];

        return $this->render('commande/modal.html.twig', $details);
    }
}