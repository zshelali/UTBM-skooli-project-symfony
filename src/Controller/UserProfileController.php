<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface; // Ajout de l'import pour EntityManagerInterface

class UserProfileController extends AbstractController
{
    #[Route(path: '/profile', name: 'app_profile')]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Handling the form submission
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $password = $request->request->get('password');
            $image = $request->files->get('image');

            // Update email, first name, and last name
            $user->setEmail($email);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);

            // If a new password is provided, hash and update it
            if (!empty($password)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            // Handle image upload (if provided)
            if ($image) {
                // Save image logic here (e.g., upload to a directory and set the path in the user)
                $imageName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('profile_images_directory'), $imageName);
                $user->setImage($imageName); // Assuming you have an 'image' field in your User entity
            }

            // Save the updated user
            $entityManager->flush(); // Utilisation de l'entityManager injecté

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'last_username' => $user->getUserIdentifier(),
            'styles' => ['register_login_style'],
            'scripts' => ['profile_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'app_profile',
        ]);
    }
}


