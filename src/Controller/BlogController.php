<?php

namespace NarutoRPG\Controller;

use NarutoRPG\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_show')]
    public function show(string $slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBySlug($slug);

        if ($post === null) {
            throw $this->createNotFoundException();
        }

        return $this->render('blog/show.html.twig', $post);
    }
}
