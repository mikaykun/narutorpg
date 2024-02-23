<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping as ORM;

/**
 * User Data
 */
#[AllowDynamicProperties]
#[ORM\Table(name: 'userdaten')]
final class UserData
{
    #[ORM\Id]
    public int $id = 0;
    public bool $Gesperrt;
    public bool $Linkunterstrich = false;
    public int $Charloesch = 0;
    public int $CoAdmin = 0;
    public int $CoAdminRang = 0;
    public int $SLVerbot;
    public int $Timetoloesch = 0;
    public int $admin = 0;
    public string $Anzeige = '';
    public string $Aussenfarbe = 'b0cfac';
    public string $Innenfarbe = '8a8b6a';
    public string $Linkfarbe = '0000FF';
    public string $pw = '';
    public string $mail;
    public $LayoutArt;
    public string $Charerstellt;
    public int $Mod = 0;

    public static function findById(int $id): self
    {
        $dbc = nrpg_get_database();
        $query = $dbc->query("SELECT * FROM userdaten WHERE id = {$id} LIMIT 1");
        $result = $query->fetchObject(self::class);
        return $result === false ? new self() : $result;
    }

    public function isGuest(): bool
    {
        return $this->id === 0;
    }
}
