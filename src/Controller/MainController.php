<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/form.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/', name: 'app_root')]
    public function main(): Response
    {
        return $this->render('main/main.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/aboutUs.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
