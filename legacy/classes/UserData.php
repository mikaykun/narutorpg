<?php

namespace classes;
use AllowDynamicProperties;

/**
 * User Data
 */
#[AllowDynamicProperties]
final class UserData
{
    public int $id;
    public string $pw;
    public string $Anzeige;
    public bool $Linkunterstrich = false;
    public string $Aussenfarbe;
    public string $Innenfarbe;
    public bool $Gesperrt;
    public int $Charloesch;
    public int $Timetoloesch;
    public string $Linkfarbe;
    public string $admin;
    public int $CoAdmin;
}
