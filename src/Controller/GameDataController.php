<?php

namespace NarutoRPG\Controller;

use Doctrine\DBAL\Connection;
use NarutoRPG\Repository\GameUpdatesRepository;
use NarutoRPG\Service\LegacyDatabaseConnection;
use NarutoRPG\Types\Villages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GameDataController extends AbstractController
{
    #[Route('/gamedata/deceased-ninja', name: 'app_deceased_ninja')]
    public function index(LegacyDatabaseConnection $connection): Response
    {
        $landNames = Villages::all();
        $lands = [];

        foreach ($landNames as $name) {
            $lands[$name] = $connection
                ->query("SELECT name, Missing, Datum FROM Gedenken WHERE Land = '" . $name . "' ORDER BY id DESC")
                ->fetchAll(\PDO::FETCH_OBJ);
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
            $users = $connection->executeQuery($sql, ['dorf'=>$dorf])->fetchAllAssociative();

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
    public function deletedUsers(Request $request, LegacyDatabaseConnection $connection): Response
    {
        $maxid = max(0, (int)$request->query->get('maxid', 0));

        $query = $connection->query("SELECT * FROM `Killing` ORDER BY `id` DESC LIMIT $maxid, 50");
        $users = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $this->render('game_data/deleted.html.twig', [
            'maxid' => $maxid,
            'users' => $users,
        ]);
    }
}
