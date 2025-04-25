<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UserController extends AbstractController
{
    #[Route('/admin/add-user', name: 'add_user', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupération des données du formulaire
        $userRole = $request->request->get('role');
        $role = $entityManager->getRepository(Role::class)->find($userRole);
        $userFirstName = $request->request->get('first_name');
        $userLastName = $request->request->get('last_name');
        $userEmail = $request->request->get('email');
        $userPassword = $request->request->get('password');
        $profilePictureFile = $request->files->get('profile_picture');


        // Création du nouvel utilisateur

        $user = new User();
        $user->setIdRole($role);
        $user->setFirstName($userFirstName);
        $user->setLastName($userLastName);
        $user->setEmail($userEmail);
        $user->setPassword(password_hash($userPassword, PASSWORD_DEFAULT));

        if ($profilePictureFile instanceof UploadedFile) {
            $newProfilePictureFile = $this->handleImageUpload($profilePictureFile);
            $user->setProfilePicture($newProfilePictureFile);
        }


        // Sauvegarde en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User successfully added!');
        return $this->redirectToRoute('admin_home');
    }

    private function handleImageUpload(UploadedFile $imageFile): string
    {
        $uploadsDir = $this->getParameter('user_images_directory');
        $newFilename = uniqid().'.'.$imageFile->guessExtension();

        $imageFile->move(
            $uploadsDir,
            $newFilename
        );

        return $newFilename;
    }
}