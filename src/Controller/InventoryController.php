<?php

declare(strict_types=1);

namespace NarutoRPG\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class InventoryController extends AbstractController
{
    #[Route('/inventory', name: 'inventory')]
    public function index(): Response
    {
        $character = nrpg_get_current_character();
        $inventory = $character->getInventory();

        return $this->json($inventory);
    }

    #[Route('/inventory/info', name: 'inventory_info')]
    public function info(): Response
    {
        return $this->render('inventory/info.html.twig');
    }
}
