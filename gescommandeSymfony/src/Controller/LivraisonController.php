<?php
namespace App\Controller;

use App\DTO\ZoneDto;
use App\DTO\LivreurDto;
use App\Service\LivraisonService;
use App\Repository\LivreurRepository;
use App\Repository\ZoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livraisons')]
class LivraisonController extends AbstractController
{
    private $livraisonService;
    private $livreurRepository;
    private $zoneRepository;

    public function __construct(
        LivraisonService $livraisonService,
        LivreurRepository $livreurRepository,
        ZoneRepository $zoneRepository
    ) {
        $this->livraisonService = $livraisonService;
        $this->livreurRepository = $livreurRepository;
        $this->zoneRepository = $zoneRepository;
    }

    #[Route('/', name: 'app_livraisons_index')]
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig');
    }

    #[Route('/zones', name: 'app_livraisons_zones')]
    public function zones(): Response
    {
        $zones = $this->livraisonService->getToutesZones();
        
        $zonesDto = [];
        foreach ($zones as $zone) {
            $dto = new ZoneDto();
            $dto->id = $zone->getId();
            $dto->nomZone = $zone->getNomZone();
            $dto->prixLivraison = (float)$zone->getPrixLivraison();
            
            $quartiers = $zone->getQuartiers();
            if ($quartiers) {
                $dto->quartiers = array_map('trim', explode(',', $quartiers));
            } else {
                $dto->quartiers = [];
            }
            
            $zonesDto[] = $dto;
        }

        return $this->render('livraison/partials/zones.html.twig', [
            'zones' => $zonesDto
        ]);
    }

    #[Route('/livreurs', name: 'app_livraisons_livreurs')]
    public function livreurs(): Response
    {
        $livreursData = $this->livraisonService->getLivreursAvecCommandes();
        $livreursDto = LivreurDto::fromEntities($livreursData);

        return $this->render('livraison/partials/livreurs.html.twig', [
            'livreurs' => $livreursDto
        ]);
    }

    #[Route('/affectations', name: 'app_livraisons_affectations')]
    public function affectations(): Response
    {
        $zonesNonAffecteesData = $this->livraisonService->getZonesNonAffectees();
        $zonesNonAffecteesDto = [];
        
        foreach ($zonesNonAffecteesData as $zoneData) {
            $dto = new ZoneDto();
            $dto->id = $zoneData['zone']->getId();
            $dto->nomZone = $zoneData['zone']->getNomZone();
            $dto->prixLivraison = (float)$zoneData['zone']->getPrixLivraison();
            
            $quartiers = $zoneData['zone']->getQuartiers();
            if ($quartiers) {
                $dto->quartiers = array_map('trim', explode(',', $quartiers));
            } else {
                $dto->quartiers = [];
            }
            
            $dto->nombreCommandes = count($zoneData['commandes']);
            $dto->estAffectee = false;
            
            $zonesNonAffecteesDto[] = $dto;
        }
        
        $zonesAffecteesData = $this->livraisonService->getZonesAffectees();
        
        $livreursAvecZones = [];
        foreach ($zonesAffecteesData as $data) {
            $livreurDto = new LivreurDto();
            $livreurDto->id = $data['livreur']->getId();
            $livreurDto->nom = $data['livreur']->getUser()->getNom();
            $livreurDto->prenom = $data['livreur']->getUser()->getPrenom();
            $livreurDto->telephone = $data['livreur']->getUser()->getTelephone();
            
            $zonesAffecteesDto = [];
            foreach ($data['zones'] as $zoneData) {
                $zoneDto = new ZoneDto();
                $zoneDto->id = $zoneData['zone']->getId();
                $zoneDto->nomZone = $zoneData['zone']->getNomZone();
                $zoneDto->prixLivraison = (float)$zoneData['zone']->getPrixLivraison();
                
                $quartiers = $zoneData['zone']->getQuartiers();
                if ($quartiers) {
                    $zoneDto->quartiers = array_map('trim', explode(',', $quartiers));
                } else {
                    $zoneDto->quartiers = [];
                }
                
                $zoneDto->nombreCommandes = count($zoneData['commandes']);
                $zoneDto->estAffectee = true;
                $zonesAffecteesDto[] = $zoneDto;
            }
            
            $livreurDto->zonesAffectees = $zonesAffecteesDto;
            $livreurDto->nombreZones = count($zonesAffecteesDto);
            $livreursAvecZones[] = $livreurDto;
        }
        
        $allLivreurs = $this->livreurRepository->findAll();

        return $this->render('livraison/partials/affectations.html.twig', [
            'livreurs' => $livreursAvecZones,
            'zonesNonAffectees' => $zonesNonAffecteesDto,
            'allLivreurs' => $allLivreurs
        ]);
    }
    #[Route('/affecter', name: 'app_livraisons_affecter', methods: ['POST'])]
    public function affecter(Request $request): JsonResponse
    {
        $zoneId = $request->request->get('zone_id');
        $livreurId = $request->request->get('livreur_id');

        if (!$zoneId || !$livreurId) {
            return $this->json([
                'success' => false,
                'message' => 'Données manquantes'
            ], 400);
        }

        $result = $this->livraisonService->affecterLivreurAZone((int)$zoneId, (int)$livreurId);

        return $this->json($result);
    }

    #[Route('/liberer/{zoneId}', name: 'app_livraisons_liberer', methods: ['POST'])]
    public function liberer(int $zoneId): JsonResponse
    {
        $result = $this->livraisonService->libererZone($zoneId);

        return $this->json($result);
    }

    #[Route('/modifier', name: 'app_livraisons_modifier', methods: ['POST'])]
    public function modifier(Request $request): JsonResponse
    {
        $zoneId = $request->request->get('zone_id');
        $livreurId = $request->request->get('livreur_id');

        if (!$zoneId || !$livreurId) {
            return $this->json([
                'success' => false,
                'message' => 'Données manquantes'
            ], 400);
        }

        $result = $this->livraisonService->modifierAffectation((int)$zoneId, (int)$livreurId);

        return $this->json($result);
    }

    #[Route('/zone/{id}/commandes', name: 'app_livraisons_commandes_zone', methods: ['GET'])]
    public function commandesZone(int $id): Response
    {
        $commandes = $this->livraisonService->getCommandesParZone($id);
        $zone = $this->zoneRepository->find($id);

        return $this->render('livraison/partials/commandes_zone.html.twig', [
            'commandes' => $commandes,
            'zone' => $zone
        ]);
    }

    #[Route('/livreur/{id}/commandes', name: 'app_livraisons_commandes_livreur', methods: ['GET'])]
    public function commandesLivreur(int $id): Response
    {
        $commandes = $this->livraisonService->getCommandesParLivreur($id);
        $livreur = $this->livreurRepository->find($id);

        return $this->render('livraison/partials/commandes_livreur.html.twig', [
            'commandes' => $commandes,
            'livreur' => $livreur
        ]);
    }
}