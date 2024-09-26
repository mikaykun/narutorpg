<?php

namespace NarutoRPG\Entity;

use Doctrine\ORM\Mapping as ORM;
use NarutoRPG\Repository\GameUpdatesRepository;

#[ORM\Entity(repositoryClass: GameUpdatesRepository::class)]
#[ORM\Table(name: 'Neuerungen')]
class GameUpdates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(name: 'Text')]
    public string $content;

    #[ORM\Column(name: 'Datum', type: 'text')]
    public string $publishedAt;

    #[ORM\Column(name: 'time', type: 'integer')]
    public int $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }
}
