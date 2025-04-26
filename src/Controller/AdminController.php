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

}
