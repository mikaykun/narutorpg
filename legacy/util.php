<?php

function apLL($ll, $APLL, $admin): void
{
    $LLs = explode("|", $ll);
    $LLs = array_unique($LLs);
    array_pop($LLs);
    $LLs = array_merge(array_diff($LLs, array($admin)));
    foreach ($LLs as $ll) {
        if ($APLL) {
            $up = "UPDATE user SET `Aktivitätspunkte` = `Aktivitätspunkte`+0.2 WHERE id = '$ll'";
            mysql_query($up);
        }
    }

    $up = "UPDATE user SET `Aktivitätspunkte` = `Aktivitätspunkte`+0.1 WHERE id = '$admin'";
    mysql_query($up);
}
