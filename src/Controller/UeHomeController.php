<?php

namespace App\Controller;

use App\Repository\UERepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class UeHomeController extends AbstractController
{
    #[Route('/ue/home_page', name: 'ue_home')]
    public function index(UERepository $ueRepository, Security $security): Response
    {
        $ues = $ueRepository->findAll();
        $user = $security->getUser(); // récupère l'utilisateur connecté

        return $this->render('ue_home/index.html.twig', [
            'styles' => ['ue_home_style'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'ue_home',
            'ues' => $ues,
            'user' => $user,
        ]);
    }
}

