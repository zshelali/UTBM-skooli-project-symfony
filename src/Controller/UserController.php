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
        $profilePictureFile = $request->files->get('user_input_illustration');


        // Création du nouvel utilisateur

        $user = new User();
        $user->setIdRole($role);
        $user->setFirstName($userFirstName);
        $user->setLastName($userLastName);
        $user->setEmail($userEmail);
        $user->setPassword(password_hash($userPassword, PASSWORD_DEFAULT));

        if ($profilePictureFile instanceof UploadedFile && $profilePictureFile->isValid()) {
            $newProfilePictureFile = $this->handleImageUpload($profilePictureFile);
            $user->setProfilePicture($newProfilePictureFile);
        }


        // Sauvegarde en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User successfully added.');
        return $this->redirectToRoute('admin_home');
    }

    #[Route('/admin/update-user/{id}', name: 'update_user', methods: ['POST'])]
    public function updateUser(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $user->setFirstName($request->request->get('first_name'));
        $user->setLastName($request->request->get('last_name'));
        $user->setEmail($request->request->get('email'));

        if (!($request->request->get('password') == null)) {
            $user->setPassword(password_hash($request->request->get('password'), PASSWORD_DEFAULT));
        }

        $role = $entityManager->getRepository(Role::class)->find($request->request->get('role'));
        $user->setIdRole($role);


        $imageFile = $request->files->get('user_input_illustration');
        if ($imageFile instanceof UploadedFile && $imageFile->isValid()) {
            $newFilename = $this->handleImageUpload($imageFile);
            $user->setProfilePicture($newFilename);
        }

        $entityManager->flush();
        $this->addFlash('success', 'User successfully updated.');
        return $this->redirectToRoute('admin_home');
    }


    #[Route('/admin/delete-user/{id}', name: 'delete_user', methods: ['POST'])]
    public function deleteUser(EntityManagerInterface $entityManager, User $user): Response
    {

        $currentUser = $this->getUser();

        if ($currentUser->getId() === $user->getId() || $user->getId() === 1 ){ //make sure the current user and the admin (id=1) can't be deleted
            $this->addFlash('error', 'You can\'t delete yourself or the main admin.');
            return $this->redirectToRoute('admin_home');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User successfully deleted.');
        return $this->redirectToRoute('admin_home');
    }

}