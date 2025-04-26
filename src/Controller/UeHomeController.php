<?php

namespace App\Controller;

use App\Repository\UERepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class UeHomeController extends AbstractController
{
    #[Route('/ue/home_page', name: 'ue_home')]
    public function index(UERepository $ueRepository, PostRepository $postRepository, Security $security): Response
    {
        $ues = $ueRepository->findAll();
        $user = $security->getUser(); // récupère l'utilisateur connecté

        // Utiliser une requête SQL native pour récupérer les 5 derniers posts
        $conn = $postRepository->getEntityManager()->getConnection();
        $sql = '
            SELECT id, title, content, post_date 
            FROM post 
            ORDER BY post_date DESC 
            LIMIT 5
        ';
        $stmt = $conn->prepare($sql);
        $recentPosts = $stmt->executeQuery()->fetchAllAssociative();

        return $this->render('ue_home/index.html.twig', [
            'styles' => ['ue_home_style'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'ue_home',
            'ues' => $ues,
            'user' => $user,
            'recentPosts' => $recentPosts, // on envoie aussi les posts à Twig
        ]);
    }
}
