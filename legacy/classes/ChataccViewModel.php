<?php

final class ChataccViewModel
{
    public $acc;
    public $username;
    private PDO $connection;

    public function __construct()
    {
        $this->connection = nrpg_get_database();
    }

    public function SetAcc($acc, $username): void
    {
        $this->acc = $acc;
        $this->username = $username;
    }

    /**
     * @return \ChatAccount[]
     */
    public function GetChataccsByUser(): array
    {
        $chatAccsSelect = "select * from ajax_chat_user where aid = '" . mysql_real_escape_string($this->acc) . "'";
        $chatAccsResult = mysql_query($chatAccsSelect);
        $accs = [];

        while ($singleAcc = mysql_fetch_array($chatAccsResult)) {
            $acc = new ChatAccount();
            $acc->SetValues($singleAcc);
            $accs[] = $acc;
        }
        return $accs;
    }

    //WIP
    public function CreateOrDeleteChatacc($name, $NPC = 0, $vorlage = 0, bool $del = false, $caId = null): void
    {
        if ($this->acc == null || $this->username == null) {
            return;
        }
        if ($del) {
            $chataccQuery = "DELETE FROM ajax_chat_user WHERE `uid` = '" . $caId . "' AND `aid` = '" . $this->acc . "'";
        } else {
            $name = str_replace(" ", "", (string)$name);
            $chatAccsSelect = "select `uid` from ajax_chat_user where REPLACE(`uname`, ' ', '') = '" . $name . "'";
            $chatAccsResult = $this->connection->query($chatAccsSelect);
            $foreignCa = $chatAccsResult->fetch(PDO::FETCH_ASSOC);
            if ($foreignCa !== false) {
                $vorrang = (str_replace(" ", "", (string)$this->username) == $name) ? 1 : 0;
                if ($vorrang == 0) {
                    echo 'Dieser Chataccount wird schon von jemandem verwendet.';
                    return;
                }
                $this->DeleteChataccById($foreignCa['uid']);
            }
            $chataccQuery = "insert into ajax_chat_user (cookie,uname,aid,npc) values('','" . $name . "','" . $this->acc . "','" . $NPC . "')";
        }
        $this->connection->exec($chataccQuery);
        if ($vorlage != 0) {
            $this->UpdateChataccToTemplate($name, $vorlage);
        }
    }

    public function UpdateChataccToTemplate(string $caname, $tmplId): bool
    {
        $tmplSelect = "select * from ajax_chat_user WHERE `uid` = '" . mysql_real_escape_string($tmplId) . "' AND `aid` = '" . $this->acc . "'";
        $tmplResult = mysql_query($tmplSelect);
        if (($tmplCa = mysql_fetch_array($tmplResult, MYSQL_ASSOC)) != false) {
            $unsetting = ['npc', 'firstlogin', 'cookie', 'uname', 'uid'];
            foreach ($unsetting as $unsetThis) {
                unset($tmplCa[$unsetThis]);
            }
            $optionsUp = 'UPDATE `ajax_chat_user` SET ';
            $first = 1;
            foreach ($tmplCa as $tmplOpt => $tmplSet) {
                if ($first != 1) {
                    $optionsUp .= ', ';
                } else {
                    $first = 0;
                }
                $optionsUp .= $tmplOpt . ' = \'' . $tmplSet . '\' ';
            }
            $optionsUp .= ' WHERE `uname` = \'' . $caname . '\' AND `aid` = \'' . $this->acc . '\'';
            $this->connection->exec($optionsUp);
            return true;
        }
        return false;
    }

    public function DeleteChataccById(string $caId): void
    {
        $chataccQuery = "DELETE FROM ajax_chat_user WHERE `uid` = '" . $caId . "'";
        $this->connection->exec($chataccQuery);
    }
}
