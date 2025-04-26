<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use App\Entity\UE;

final class UeContentController extends AbstractController
{
    #[Route('/ue/content/{CodeUE}', name: 'ue_content')]
    public function index(EntityManagerInterface $entityManager, string $CodeUE): Response
    {
        $ue = $entityManager->getRepository(UE::class)->find($CodeUE);
        $posts = $entityManager->getRepository(Post::class)->findBy(
            ['ueID' => $CodeUE],
            ['Post.postdate' => 'DESC']
        );
        ;


        return $this->render('ue_content/index.html.twig', [
            'styles' => ['UE_page_style', 'UE_prof'],
            'scripts' => ['AddPost_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'ue_content',
            'postlist' => $posts,
            'ue' => $ue,
        ]);
    }
}
