<?php
namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $today = new \DateTime();

        return $this->render('dashboard/index.html.twig', [
            'today' => $today,
            'commandesEnCours' => $commandeRepository->countCommandesEnCoursDuJour(),
            'commandesValidees' => $commandeRepository->countCommandesTermineesDuJour(),
            'commandesAnnulees' => $commandeRepository->countCommandesAnnuleesDuJour(),
            'recettesJournalieres' => $commandeRepository->getRecettesDuJour(),
            'topProduits' => $commandeRepository->getTopProduitsVendusDuJour(),
            'commandesRecentes' => $commandeRepository->findCommandesRecentes(5),
        ]);
    }
}