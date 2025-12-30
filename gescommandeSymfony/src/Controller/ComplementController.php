<?php

namespace App\Controller;

use App\Entity\Complement;
use App\Repository\ComplementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/complements')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class ComplementController extends AbstractController
{
    #[Route('/', name: 'app_complements_index')]
    public function index(ComplementRepository $complementRepository): Response
    {
        $complements = $complementRepository->findAll();
        
        return $this->render('complement/index.html.twig', [
            'complements' => $complements,
            'current_page' => 'complements'
        ]);
    }
}