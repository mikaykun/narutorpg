<?php

final class Anfrage
{
    private readonly PDO $connection;

    public function __construct()
    {
        $this->connection = nrpg_get_database();
    }

    public function takeRequestMyself($aId, $erst, int $uId): bool
    {
        $row = ['adminZwei', 'adminEins'];
        $query = 'SELECT * FROM `Anfragen` WHERE `id` = \'' . $aId . '\' AND Ausschluss NOT LIKE \'%|' . $uId . '|%\'';
        $anfrage = $this->getAnfrage($query);
        $users = $anfrage[0]->$row[$erst];
        if (!$users[0]->id) {
            $up = 'UPDATE Anfragen SET ' . $row[$erst] . '= \'' . $uId . '\' WHERE id = \'' . $aId . '\' AND Ausschluss NOT LIKE \'%|' . $uId . '|%\'';
            if (mysql_query($up)) {
                echo 'Anfrage erfolgreich &Uuml;bernommen.';
                return true;
            }
        }
        echo 'Fehler beim &Uuml;bernehmen der Anfrage.';
        return false;
    }

    public function removeRequestFromUser($aId, $erst): bool
    {
        $row = ['adminZwei', 'adminEins'];
        $query = 'SELECT * FROM `Anfragen` WHERE `id` = \'' . $aId . '\'';
        $anfrage = $this->getAnfrage($query);
        $users = $anfrage[0]->$row[$erst];
        if ($users[0]->id) {
            $up = 'UPDATE Anfragen SET ' . $row[$erst] . '= \'0\' WHERE id = \'' . $aId . '\'';
            if (mysql_query($up)) {
                echo '<br><br>' . 'Zuweisung erfolgreich entfernt.';
                return true;
            }
        }
        echo '<br><br>Fehler beim Entfernen der Zuweisung.';
        return false;
    }

    public function parseNinja($ninjas): array
    {
        $nin = [];
        $ninjas = explode("|", $ninjas);
        $ninjas = array_filter($ninjas);
        if (!empty($ninjas)) {
            $ninjas = implode(", ", $ninjas);
            $sql = "SELECT id, name FROM user WHERE id IN ($ninjas)";
            $query = mysql_query($sql);
            while ($ninj = mysql_fetch_object($query)) {
                $nin[] = $ninj;
            }
        }
        return $nin;
    }

    public function parseTime($time): array
    {
        $Zeit = time() - $time;
        $Stunden = 0;
        $Tage = 0;
        while ($Zeit >= 86400) {
            $Zeit -= 86400;
            $Tage += 1;
        }
        while ($Zeit >= 3600) {
            $Zeit -= 3600;
            $Stunden += 1;
        }
        return [$time, $Tage, $Stunden];
    }

    public function parseAnfragenArt($art): string
    {
        $sorte = $this->giveAnfragenArten();
        return $sorte[$art];
    }

    public function giveAnfragenArten(): array
    {
        return [
            1 => 'RP-Erlaubnis',
            2 => 'Support-Anfrage',
            3 => 'Dorfoberhaupt-Anfrage',
            5 => 'Sonstige Anfrage',
            6 => 'NPC-/Team-/SL-Anfrage',
        ];
    }

    /**
     * @param string $query
     * @return object[]
     */
    public function getAnfrage(string $query): array
    {
        $result = $this->connection->query($query);
        $anfragen = [];
        while ($anfrage = $result->fetchObject()) {
            $anfrage->Ninja = $this->parseNinja($anfrage->Ninja);
            $anfrage->Ausschluss = $this->parseNinja($anfrage->Ausschluss);
            $anfrage->adminEins = $this->parseNinja($anfrage->adminEins);
            $anfrage->adminZwei = $this->parseNinja($anfrage->adminZwei);
            $anfrage->ArtName = $this->parseAnfragenArt($anfrage->Art);
            $anfrage->lastact = $this->parseTime($anfrage->lastact);
            $anfrage->Adminlast = $this->parseNinja($anfrage->Adminlast);
            $anfrage->Userlast = $this->parseNinja($anfrage->Userlast);
            $anfragen[] = $anfrage;
        }
        return $anfragen;
    }

    public function createAndConnections(string $key, string $td, array $access, $allowed = 1, $placeholder = 0): string
    {
        $where = '';
        $sonder = [
            'TeamleiterEinsicht' => ['schueler', 'Ninja'],
            'Landesfuehrer' => ['dorf', 'Dorfer'],
        ];
        foreach ($access[$key] as $art) {
            if ($placeholder === 0) {
                $bed = ' = \'' . $art . '\'';
            } else {
                $bed = ' LIKE \'%|' . $art . '|%\'';
            }
            if (isset($sonder[$art])) {
                $where .= '(`' . $td . '` ' . $bed . ' AND ' . $this->createAndConnections(
                    $sonder[$art][0],
                    $sonder[$art][1],
                    $access
                ) . ') OR ';
            } else {
                $where .= '`' . $td . '` ' . $bed . ' OR ';
            }
        }
        if ($where != '') {
            $where = substr($where, 0, -4);
            $where = ' (' . $where . ')';
            /*if($allowed != 1 && $key == 'art')
            {
                $where = ' ('.$where.' AND ``)';
            }*/
        }
        return $where;
    }

    public function getUserIdByName(string $name): int|bool
    {
        $query = $this->connection->prepare('SELECT id FROM user WHERE `name` = :name LIMIT 1');
        $query->execute(['name' => $name]);
        if ($ninj = $query->fetchObject()) {
            return (int)$ninj->id;
        }
        return false;
    }

    //prüfen welche der vars hier wirklich gebraucht werden!
    public function createQuery(
        object $user,
        object $acc,
        $meins,
        $bearb,
        $mbearb,
        $standby,
        $Searchfor,
        array $access
    ): string {
        $row = $this->createAndConnections('art', 'Zugriffe', $access, 1, '|');
        if (!empty($access['aktiv'])) {
            $row .= ' AND' . $this->createAndConnections('aktiv', 'Zustand', $access, 1, 0);
        }
        $standby = (isset($standby) == 1) ? 1 : 0;
        $row .= ' AND `Standby` = \'' . $standby . '\'';
        if ($bearb != 1 && ($mbearb == 1 || $meins == 1)) {
            $row .= ' AND ';
            $mbq = '(`adminEins` = \'' . $user->id . '\' OR `adminZwei` = \'' . $user->id . '\')';
            $mq = '`Ninja` LIKE \'%|' . $user->id . '|%\'';
            if ($mbearb == 1 && $meins == 1) {
                $row .= '(' . $mbq . ' OR ' . $mq . ')';
            } elseif ($mbearb == 1) {
                $row .= $mbq;
            } else {
                $row .= $mq;
            }
        }
        if ($acc->admin != 3) {
            $row = '(' . $row . ') AND `Ausschluss` NOT LIKE \'%' .
                $user->id . '%\'';
        }
        if ($Searchfor != '') {
            $uId = $this->getUserIdByName($Searchfor);
            $row .= ($uId === false) ? '' : ' AND `Ninja` LIKE \'%|' . $uId . '|%\'';
        }

        //Query erstellen
        return 'SELECT * FROM Anfragen WHERE ' . $row . ' ORDER BY `lastact` ASC';
    }

    public function getRequestSearchResults(
        $user,
        object $acc,
        $RPCo,
        $RegelCo,
        $SupportCo,
        $TeamleiterEinsicht,
        $Konoha,
        $Kusa,
        $Suna,
        $Iwa,
        $Taki,
        $Kumo,
        $Ame,
        $meins,
        $bearb,
        $mbearb,
        bool $aktiv,
        $standby,
        bool $fertig,
        $Searchfor,
        int $Landesfuehrer = 1
    ): array {
        //teams,Dorf,Art
        $access = $this->UserPossibleAccess($user, $acc);
        $arten = ['dorf', 'art'];
        foreach ($arten as $art) {
            foreach ($access[$art] as $key => $artWert) {
                if (!isset($$artWert) || $$artWert != 1) {
                    unset($access[$art][$key]);
                }
            }
        }
        if (!in_array('TeamleiterEinsicht', $access['art'])) {
            array_pop($access);
        }
        if (empty($access['dorf'])) {
            $pos = array_search('Landesfuehrer', $access['art']);
            if ($pos !== false) {
                unset($access['art'][$pos]);
            }
        }
        if ($fertig) {
            $access['aktiv'] = [1, 2, 3];
        }
        if ($aktiv) {
            $access['aktiv'][] = 0;
        }
        $row = $this->createQuery(
            $user,
            $acc,
            $meins,
            $bearb,
            $mbearb,
            $standby,
            $Searchfor,
            $access
        );
        return $this->getAnfrage($row);
    }

    private function UserPossibleAccess($user, object $acc): array
    {
        //adminzugriff? Wenn ja welche Art Admin?
        $zugriffe = [
            'art' => [],
            'dorf' => [],
            'schueler' => [],
        ];
        if ($acc->CoAdmin == 2 || $acc->admin == 3) {
            $zugriffe['art'][] = 'SupportCo';
        }
        if ($acc->CoAdmin == 3 || $acc->admin == 3) {
            $zugriffe['art'][] = 'RegelCo';
        }
        if ($acc->CoAdmin == 4 || $acc->admin == 3) {
            $zugriffe['art'][] = 'RPCo';
        }

        //Dorfoberhauptzugriff? Wenn ja welche Dörfer?
        if ($acc->admin == 3) {
            $sql = "SELECT `Land` FROM Regierung";
            $query = mysql_query($sql);
            while ($Lander = mysql_fetch_object($query)) {
                $zugriffe['dorf'][] = $Lander->Land;
            }
            $zugriffe['art'][] = 'Landesfuehrer';
        } else {
            $sql = "SELECT Land FROM Regierung WHERE Helfer1 = :user_id OR Helfer2 = :user_id OR Helfer3 = :user_id OR Helfer4 = :user_id OR Kage = :user_name LIMIT 1";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_name', $user->name, PDO::PARAM_STR);
            $stmt->execute();

            $Lander = $stmt->fetch(PDO::FETCH_OBJ);

            if (is_object($Lander)) {
                $zugriffe['art'][] = 'Landesfuehrer';
                $Buendnisse = [
                    ['Konoha', 'Kusa', 'Suna', 'Iwa'],
                    ['Kumo', 'Taki'],
                    ['Ame'],
                ];
                foreach ($Buendnisse as $Buendnis) {
                    if (in_array($Lander->Land, $Buendnis)) {
                        $zugriffe['dorf'] = $Buendnis;
                    }
                }
            }
        }

        // Teamleitereinsicht? Wenn ja von wem? |TeamleiterEinsicht|
        if ($acc->admin == 3) {
            $query = $this->connection->query("SELECT `id` FROM `user`");
            while ($users = $query->fetchObject()) {
                $zugriffe['schueler'][] = $users->id;
                $tl = 1;
            }
        } else {
            $query = $this->connection->prepare("SELECT u.id AS uId FROM user u LEFT JOIN Teams t ON u.Team = t.id WHERE t.Leiter = :userId");
            $query->execute(['userId' => $user->id]);
            while ($team = $query->fetchObject()) {
                $zugriffe['schueler'][] = $team->uId;
                $tl = 1;
            }
        }
        if (isset($tl)) {
            $zugriffe['art'][] = 'TeamleiterEinsicht';
        }
        return $zugriffe;
    }
}
