<?php

namespace App\Controller;

use App\Entity\UE;
use App\Entity\User;
use App\Entity\Role;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $ues = $entityManager->getRepository(UE::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();
        $roles = $entityManager->getRepository(Role::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'styles' => ['index_style'],
            'scripts' => ['index_home_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'admin_home',

            'uelist' => 'PageParts/admin_ue_list.html.twig',
            'userlist' => 'PageParts/admin_manage_users.html.twig',
            'assignationlist'=>'PageParts/assignation.html.twig',
            'ues' => $ues,
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new Response('User not found', 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response('User deleted successfully', 200);
    }


    #[Route('/admin/ue/{id}', name: 'admin_delete_ue', methods: ['DELETE'])]
    public function deleteUe(int $id, EntityManagerInterface $entityManager): Response
    {
        $ue = $entityManager->getRepository(UE::class)->find($id);

        if (!$ue) {
            return new Response('UE not found', 404);
        }

        $entityManager->remove($ue);
        $entityManager->flush();

        return new Response('UE deleted successfully', 200);
    }

}
