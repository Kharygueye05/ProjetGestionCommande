<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/menus')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class MenuController extends AbstractController
{
    #[Route('/', name: 'app_menus_index')]
    public function index(MenuRepository $menuRepository): Response
    {
        $menus = $menuRepository->findAll();
        
        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'current_page' => 'menus'
        ]);
    }
}