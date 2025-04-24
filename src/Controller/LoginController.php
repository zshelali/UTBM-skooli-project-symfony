<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'login_page')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'styles' => ['register_login_style'],
            'scripts' => ['login_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'admin_home'
        ]);
    }
}
