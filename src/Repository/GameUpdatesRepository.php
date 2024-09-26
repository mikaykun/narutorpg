<?php

namespace NarutoRPG\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NarutoRPG\Entity\GameUpdates;

class GameUpdatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameUpdates::class);
    }

    public function findAllUpdates(): array
    {
        return $this->findBy([], ['id' => 'DESC']);
    }
}
