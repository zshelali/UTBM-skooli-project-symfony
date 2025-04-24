<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UeHomeController extends AbstractController
{
    #[Route('/ue/home', name: 'ue_home')]
    public function index(): Response
    {
        return $this->render('ue_home/index.html.twig', [
            'controller_name' => 'UeHomeController',
        ]);
    }
}
