<?php

class ChataccViewModel
{
    public $acc;
    public $username;

    public function __construct() {}

    public function SetAcc($acc, $username)
    {
        $this->acc = $acc;
        $this->username = $username;
    }

    public function GetChataccsByUser()
    {
        $chatAccsSelect = "select * from ajax_chat_user where aid = '" . mysql_real_escape_string($this->acc) . "'";
        $chatAccsResult = mysql_query($chatAccsSelect);
        $accs = array();

        while ($singleAcc = mysql_fetch_array($chatAccsResult)) {
            $acc = new ChatAccount();
            $acc->SetValues($singleAcc);
            array_push($accs, $acc);
        }
        return $accs;
    }

    //WIP
    public function CreateOrDeleteChatacc($name, $NPC = 0, $vorlage, $del = null, $caId = null)
    {
        if ($this->acc == null || $this->username == null) {
            return;
        }
        if ($del == null) {
            $name = str_replace(" ", "", $name);
            $chatAccsSelect = "select `uid` from ajax_chat_user where REPLACE(`uname`, ' ', '') = '" . mysql_real_escape_string($name) . "'";
            $chatAccsResult = mysql_query($chatAccsSelect);
            if (($foreignCa = mysql_fetch_array($chatAccsResult)) != false) {
                $vorrang = (str_replace(" ", "", $this->username) == $name) ? 1 : 0;
                if ($vorrang == 0) {
                    echo 'Dieser Chataccount wird schon von jemandem verwendet.';
                    return false;
                } else {
                    $this->DeleteChataccById($foreignCa['uid']);
                }
            }
            $chataccQuery = "insert into ajax_chat_user (uname,aid,npc)" .
                " values('" . $name . "','" . $this->acc . "','" . $NPC . "')";
        } else {
            $chataccQuery = "DELETE FROM ajax_chat_user WHERE `uid` = '" . $caId . "' AND `aid` = '" . $this->acc . "'";
        }
        mysql_query($chataccQuery);
        if ($vorlage != 0) {
            $this->UpdateChataccToTemplate($name, $vorlage);
        }
    }

    public function UpdateChataccToTemplate($caname, $tmplId)
    {
        $tmplSelect = "select * from ajax_chat_user WHERE `uid` = '" . mysql_real_escape_string($tmplId) . "' AND `aid` = '" . $this->acc . "'";
        $tmplResult = mysql_query($tmplSelect);
        if (($tmplCa = mysql_fetch_array($tmplResult, MYSQL_ASSOC)) != false) {
            $unsetting = array('npc', 'firstlogin', 'cookie', 'uname', 'uid');
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
        } else {
            return false;
        }
        mysql_query($optionsUp);
    }

    public function DeleteChataccById($caId): void
    {
        $chataccQuery = "DELETE FROM ajax_chat_user WHERE `uid` = '" . $caId . "'";
        mysql_query($chataccQuery);
    }
}
