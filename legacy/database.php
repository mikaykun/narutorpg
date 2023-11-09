<?php

function nrpg_get_database(): PDO
{
    static $pdo;

    if ($pdo === null) {
        $connection_url = 'mysql:host=' . $_SERVER['DB_SERVER'] . ';dbname=' . $_SERVER['DB_NAME'];
        $pdo = new PDO($connection_url, $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);
    }

    return $pdo;
}
