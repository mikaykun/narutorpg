<?php

namespace NarutoRPG\Legacy\Entity;

class RegisterForm
{
    protected string $nick;
    protected string $pw;
    protected string $mail;
    protected ?string $werb = null;
    protected bool $Altersbest =  false;

    public function isAltersbest(): bool
    {
        return $this->Altersbest;
    }

    public function setAltersbest(bool $Altersbest): void
    {
        $this->Altersbest = $Altersbest;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getNick(): string
    {
        return $this->nick;
    }

    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    public function getPw(): string
    {
        return $this->pw;
    }

    public function setPw(string $pw): void
    {
        $this->pw = $pw;
    }

    public function getWerb(): ?string
    {
        return $this->werb;
    }

    public function setWerb(?string $werb): void
    {
        $this->werb = $werb;
    }
}
