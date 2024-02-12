<?php

namespace NarutoRPG\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LiveSearchController extends AbstractController
{
    #[Route('/live/search', name: 'app_live_search')]
    public function index(Request $request, Connection $connection): Response
    {
        $treffer = '';
        $users = $connection->createQueryBuilder()
            ->select('name')
            ->from('user')
            ->where('name LIKE ?')
            ->andWhere('zeigen = ""')
            ->orderBy('name', 'DESC')
            ->setMaxResults(10)
            ->setParameter(0, $request->query->get('q') . '%')
            ->fetchAllAssociative();

        foreach ($users as $user) {
            $treffer .= sprintf(
                '<a href="/userpopup.php?usernam=%s">%s</a><br>',
                urlencode((string) $user["name"]),
                $user["name"]
            );
        }

        return new Response($treffer, 200);
    }
}
