<?php

namespace NarutoRPG\Controller;

use Doctrine\DBAL\Connection;
use NarutoRPG\Repository\GameUpdatesRepository;
use NarutoRPG\Types\Villages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GameDataController extends AbstractController
{
    #[Route('/gamedata/deceased-ninja', name: 'app_deceased_ninja')]
    public function index(Connection $connection): Response
    {
        $landNames = Villages::all();
        $lands = [];

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->select('name', 'Missing', 'Datum')->from('Gedenken')->orderBy('id', 'DESC');

        foreach ($landNames as $name) {
            $queryBuilder->setParameter('land', $name);
            $lands[$name] = $queryBuilder->fetchAllAssociative();
        }

        return $this->render('game_data/index.html.twig', [
            'lands' => $lands,
        ]);
    }

    #[Route('/neuerungen', name: 'app_game_updates')]
    public function neuerungen(GameUpdatesRepository $gameUpdatesRepository): Response
    {
        $gameUpdates = $gameUpdatesRepository->findAllUpdates();

        return $this->render('game_data/neuerungen.html.twig', [
            'gameUpdates' => $gameUpdates,
        ]);
    }

    #[Route('/online', name: 'app_online_users')]
    public function onlineUsers(Connection $connection): Response
    {
        $sql = "SELECT COUNT(*) FROM onlineuser WHERE name = 'Gast'";
        $guests = (int)$connection->executeQuery($sql)->fetchOne();

        $doerfer = ['Konoha', 'Kusa', 'Iwa', 'Ame', 'Suna', 'Taki', 'Kumo', 'Landlos'];
        $userList = [];

        foreach ($doerfer as $dorf) {
            $sql = "SELECT name FROM onlineuser WHERE Land = :dorf";
            $users = $connection->executeQuery($sql, ['dorf' => $dorf])->fetchAllAssociative();

            if (empty($users)) {
                continue;
            }

            $userList[$dorf] = $users;
        }

        return $this->render('game_data/online.html.twig', [
            'guests' => $guests,
            'users' => $userList,
        ]);
    }

    #[Route('/gamedata/deleted-user', name: 'app_deleted_users')]
    public function deletedUsers(Request $request, Connection $connection): Response
    {
        $maxid = max(0, $request->query->getInt('maxid'));

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->select('Text')
            ->from('Killing')
            ->orderBy('id', 'DESC')
            ->setMaxResults(50)
            ->setFirstResult($maxid);
        $users = $queryBuilder->fetchAllAssociative();

        return $this->render('game_data/deleted.html.twig', [
            'maxid' => $maxid,
            'users' => $users,
        ]);
    }
}
