<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_page')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'styles' => ['index_style'],
            'scripts' => ['index_home_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'home_page',
        ]);
    }
}
