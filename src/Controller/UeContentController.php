<?php

namespace App\Controller;

use App\Entity\UE;
use App\Repository\PostRepository;
use App\Repository\UERepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;

final class UeContentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ue/content/{id}', name: 'ue_content')]
    public function index(int $id, UERepository $ueRepository, PostRepository $postRepository): Response
    {
        $ue = $ueRepository->find($id);

        if (!$ue) {
            throw $this->createNotFoundException('UE non trouvée.');
        }

        $posts = $postRepository->findBy(['id_ue' => $ue], ['post_date' => 'DESC']);

        $users = $ue->getUsers();
        return $this->render('ue_content/index.html.twig', [
            'styles' => ['UE_page_style', 'UE_prof', 'index_style'],
            'scripts' => ['AddPost_script'],
            'header' => 'PageParts/header.html.twig',
            'footer' => 'PageParts/footer.html.twig',
            'currentPage' => 'ue_content',
            'ue' => $ue,
            'posts' => $posts,
            'users' => $users,
        ]);
    }



    #[Route('/post/add/{id}', name: 'post_add')]
    public function addPost(Request $request, EntityManagerInterface $em, UE $ue): Response
    {
        $post = new Post();
        $post->setIdUe($ue);

        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $icon = $request->request->get('icon');
        $file = $request->files->get('file');

        $post->setTitle($title);
        $post->setContent($content);
        $post->setIcon($icon);

        if ($file) {
            $filename = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir') . '/public/uploads', $filename);
            $post->setFile($filename);
        }

        $post->setIdUser($this->getUser());

        $post->setPostDate(new \DateTime());

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('ue_content', ['id' => $ue->getId()]);
    }

    #[Route('/post/{id}/delete', name: 'post_delete')]
    public function delete(int $id): Response
    {
        // Utiliser l'entityManager injecté pour supprimer le post
        $post = $this->entityManager->getRepository(Post::class)->find($id);

        if ($post) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('ue_content', ['id' => $post->getIdUe()->getId()]);
    }

    #[Route('/post/{id}/update', name: 'post_update', methods: ['POST'])]
    public function update(Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $title = $request->request->get('title');
        $content = $request->request->get('content');

        $post->setTitle($title);
        $post->setContent($content);

        $entityManager->flush();

        // après update on peut revenir sur la page UE
        return $this->redirect($request->headers->get('referer'));
    }



}