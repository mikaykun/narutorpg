<?php

$c_loged = $_COOKIE['c_loged'];
$abfrage2 = "SELECT admin, id, name FROM userdaten WHERE id LIKE '$c_loged'";
$ergebnis2 = mysql_query($abfrage2);
$object = mysql_fetch_object($ergebnis2);
$admin = $object->admin;
$deineid = $object->id;

if ($object->admin != "3") {
    echo "Du bist kein Administrator, $object->name $object->id!";
} elseif ($_GET['log'] == 1) {
    $sql = "SELECT id FROM user WHERE name = '{$_POST['Userlogeinplz']}'";
    $l_log = mysql_query($sql) or die("Fehler - \"$sql\"");
    $l_log3 = mysql_fetch_object($l_log);
    $id = $l_log3->id;
    $sql = "SELECT id,pw FROM userdaten WHERE id = '$id'";
    $l_log = mysql_query($sql) or die("Fehler - \"$sql\"");
    $l_log2 = mysql_fetch_object($l_log);
    $l_idt = $l_log2->id;
    $l_pwt = $l_log2->pw;
    setcookie("c_loged", "$l_idt", time() + 60 * 60 * 24 * 7);
    setcookie("c_pw", $l_pwt, time() + 1 + 60 * 60 * 24 * 7);
    $sql = "SELECT * FROM user WHERE id = '{$_POST['Userlogeinplz']}'";
    $l_log3 = mysql_query($sql) or die("Fehler - \"$sql\"");
    $l_log3 = mysql_fetch_object($l_log3);
    session_start();
    $_SESSION['' . CHAT_SESSION_UID . ''] = $l_log2->id;
    $_SESSION['' . CHAT_SESSION_UNAME . ''] = $l_log3->Vorname;
    $_SESSION["Select_Whos"] = "Users";
    echo "Als $l_log2->name eingeloggt!<br>";
} else {
    echo "<form method='POST' action='Logein.php?log=1'>Als <input type='Text' name='Userlogeinplz'><input type='submit' value='einloggen'></form>";
}
