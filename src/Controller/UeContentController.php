<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UERepository;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;

final class UeContentController extends AbstractController
{
    #[Route('/ue/content/{id}', name: 'ue_content')]
    public function index(int $id, UERepository $ueRepository, EntityManagerInterface $entityManager): Response
    {
        $ue = $ueRepository->find($id);

        $posts = $entityManager->getRepository(Post::class)->findBy(['id_ue' => $id], ['post_date' => 'DESC']);

        if (!$ue) {
            throw $this->createNotFoundException('UE non trouvée.');
        }

        return $this->render('ue_content/index.html.twig', [
            'styles' => ['UE_page_style', 'UE_prof'],
            'scripts' => ['AddPost_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'ue_content',
            'ue' => $ue,
            'postlist' => $posts,
        ]);
    }

}
