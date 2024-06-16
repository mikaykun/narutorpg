<?php

namespace NarutoRPG\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FrontpageController extends AbstractController
{
    #[Route('/', name: 'app_frontpage')]
    public function index(): Response
    {
        return $this->render('frontpage/index.html.twig');
    }
}
