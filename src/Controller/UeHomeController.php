<?php

namespace App\Controller;

use App\Repository\UERepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class UeHomeController extends AbstractController
{
    #[Route('/ue/home_page', name: 'ue_home')]
    public function index(
        UERepository $ueRepository,
        PostRepository $postRepository,
        Security $security,
        ManagerRegistry $doctrine
    ): Response
    {
        $ues = $ueRepository->findAll();
        $user = $security->getUser(); // récupère l'utilisateur connecté

        // Utiliser une requête SQL
        $connection = $doctrine->getConnection();
        $sql = "
    SELECT p.id, p.title, p.content, p.post_date, u.code AS ue_code, 
           u2.first_name, u2.last_name
    FROM post p
    INNER JOIN ue u ON p.id_ue_id = u.id
    INNER JOIN \"user\" u2 ON p.id_user_id = u2.id
    ORDER BY p.post_date DESC
    LIMIT 5
";




        $stmt = $connection->executeQuery($sql);
        $recentPosts = $stmt->fetchAllAssociative();

        return $this->render('ue_home/index.html.twig', [
            'styles' => ['ue_home_style'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'ue_home',
            'ues' => $ues,
            'user' => $user,
            'recentPosts' => $recentPosts,
        ]);
    }
}
