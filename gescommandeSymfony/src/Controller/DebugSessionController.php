<?php
// src/Controller/DebugSessionController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DebugSessionController extends AbstractController
{
    #[Route('/debug/session', name: 'app_debug_session')]
    public function debugSession(Request $request): Response
    {
        $session = $request->getSession();
        $user = $this->getUser();
        
        $html = '<h1>Debug Session & Authentication</h1>';
        
        // Informations sur l'utilisateur
        $html .= '<h2>Utilisateur actuel</h2>';
        if ($user) {
            $html .= '<p style="color: green;">✅ UTILISATEUR CONNECTÉ</p>';
            $html .= '<ul>';
            $html .= '<li>Email: ' . $user->getUserIdentifier() . '</li>';
            $html .= '<li>Roles: ' . implode(', ', $user->getRoles()) . '</li>';
            $html .= '<li>ID: ' . $user->getId() . '</li>';
            $html .= '</ul>';
        } else {
            $html .= '<p style="color: red;">❌ AUCUN UTILISATEUR CONNECTÉ</p>';
        }
        
        // Informations sur la session
        $html .= '<h2>Session</h2>';
        $html .= '<p>Session ID: ' . $session->getId() . '</p>';
        
        // Toutes les données de session
        $html .= '<h3>Données de session:</h3>';
        $allSessionData = $session->all();
        if (empty($allSessionData)) {
            $html .= '<p>Aucune donnée dans la session</p>';
        } else {
            $html .= '<ul>';
            foreach ($allSessionData as $key => $value) {
                $html .= '<li><strong>' . $key . ':</strong> ' . 
                        (is_array($value) ? json_encode($value) : $value) . '</li>';
            }
            $html .= '</ul>';
        }
        
        // Liens pour tester
        $html .= '<h2>Test des routes</h2>';
        $html .= '<ul>';
        $html .= '<li><a href="/">Accueil (/)</a></li>';
        $html .= '<li><a href="/login">Login (/login)</a></li>';
        $html .= '<li><a href="/dashboard">Dashboard (/dashboard)</a></li>';
        $html .= '</ul>';
        
        // Bouton pour vider la session
        $html .= '<form method="post" action="' . $this->generateUrl('app_clear_session') . '">';
        $html .= '<button type="submit" style="padding: 10px; background: red; color: white;">Vider la session</button>';
        $html .= '</form>';
        
        return new Response($html);
    }
    
    #[Route('/debug/clear-session', name: 'app_clear_session', methods: ['POST'])]
    public function clearSession(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        
        $this->addFlash('success', 'Session vidée avec succès!');
        
        return $this->redirectToRoute('app_debug_session');
    }
}