<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UERepository;
use Symfony\Component\HttpFoundation\Request;

final class UeContentController extends AbstractController
{
    #[Route('/ue/content/{id}', name: 'ue_content')]
    public function index(int $id, UERepository $ueRepository): Response
    {
        $ue = $ueRepository->find($id);

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
        ]);
    }

}
