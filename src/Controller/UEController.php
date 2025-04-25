<?php

namespace App\Controller;

use App\Entity\UE;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UEController extends AbstractController
{
    #[Route('/admin/add-ue', name: 'add_ue', methods: ['POST'])]
    public function addUE(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupération des données du formulaire
        $ueCode = $request->request->get('ue_code');  // Récupérer le code
        $ueName = $request->request->get('ue_name');
        $ueDescription = $request->request->get('ue_description');
        $ueCredits = (int)$request->request->get('ue_credits');
        $imageFile = $request->files->get('ue_input_illustration');

        // Création de la nouvelle UE
        $ue = new UE();
        $ue->setCode($ueCode);  // Assigner le code
        $ue->setName($ueName);
        $ue->setDescription($ueDescription);
        $ue->setCredits($ueCredits);
        $ue->setLastUpdateDate(new \DateTime());

        // Gestion de l'upload d'image
        if ($imageFile instanceof UploadedFile) {
            $newFilename = $this->handleImageUpload($imageFile);
            $ue->setIllustration($newFilename);
        }

        // Sauvegarde en base de données
        $entityManager->persist($ue);
        $entityManager->flush();

        $this->addFlash('success', 'UE ajoutée avec succès !');
        return $this->redirectToRoute('admin_home');
    }

    private function handleImageUpload(UploadedFile $imageFile): string
    {
        $uploadsDir = $this->getParameter('ue_images_directory');
        $newFilename = uniqid().'.'.$imageFile->guessExtension();

        $imageFile->move(
            $uploadsDir,
            $newFilename
        );

        return $newFilename;
    }
}