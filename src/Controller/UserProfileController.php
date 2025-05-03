<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileController extends AbstractController
{
    #[Route(path: '/profile', name: 'app_profile')]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $password = $request->request->get('password');
            $image = $request->files->get('image');

            // met à jour
            $user->setEmail($email);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);

            // Si un nouveau mot de passe est entré, le hasher et le sauvegarder
            if (!empty($password)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            if ($image) {
                // Génère un nom unique pour l'image
                $imageName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('profile_images_directory'), $imageName);
                $user->setImage($imageName);
            }

            $entityManager->flush();
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


