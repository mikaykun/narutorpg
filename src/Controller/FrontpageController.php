<?php

namespace NarutoRPG\Controller;

use Doctrine\DBAL\Connection;
use NarutoRPG\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FrontpageController extends AbstractController
{
    #[Route('/frontpage', name: 'app_frontpage')]
    public function index(Connection $connection, PostRepository $postRepository): Response
    {
        $dorfs = nrpg_get_current_user();
        $dorfs2 = nrpg_get_current_character();

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder
            ->select('l.Land', 'l.Datum', 'l.Text', 'u.name AS Verfasser', 'l.Titel')
            ->from('Landnews', 'l')
            ->where('l.Land = :land OR l.Land = ""')
            ->orderBy('l.id', 'DESC')
            ->leftJoin('l', 'user', 'u', 'u.id = l.Verfasser')
            ->setMaxResults(5)
            ->setParameter('land', $dorfs2->Heimatdorf);

        if ($dorfs->CoAdmin > 0 || $dorfs->admin >= 3) {
            $queryBuilder->orWhere("l.Land IN ('Konoha', 'Suna', 'Kumo', 'Iwa', 'Taki', 'Ame', 'Kusa')");
        }

        return $this->render('frontpage/index.html.twig', [
            'newsList' => $queryBuilder->fetchAllAssociative(),
            'devUpdates' => $postRepository->findAll(),
        ]);
    }

    #[Route('/Impressum.php', name: 'app_imprint')]
    public function imprint(): Response
    {
        return $this->render('frontpage/imprint.html.twig');
    }
}
