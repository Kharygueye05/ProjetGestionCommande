<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/burgers')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class BurgerController extends AbstractController
{
    #[Route('/', name: 'app_burgers_index')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findAll();
        
        return $this->render('burger/index.html.twig', [
            'burgers' => $burgers,
            'current_page' => 'burgers'
        ]);
    }
}