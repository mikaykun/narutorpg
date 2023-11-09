<?php

function nrpg_get_current_user(): ?UserData
{
    $pdo = nrpg_get_database();

    $query = $pdo->prepare("SELECT * FROM userdaten WHERE id = :id");
    $query->execute(['id' => \NarutoRPG\SessionHelper::getUserId()]);
    $user = $query->fetchObject(UserData::class);

    if ($user instanceof UserData) {
        return $user;
    }

    return null;
}

function nrpg_get_current_character()
{
    $pdo = nrpg_get_database();

    $query = $pdo->prepare("SELECT * FROM user WHERE id = :id");
    $query->execute(['id' => \NarutoRPG\SessionHelper::getUserId()]);
    $user = $query->fetchObject();

    if ($user instanceof UserData) {
        return $user;
    }

    return null;
}

function is_user_logged_in(): bool
{
    return \NarutoRPG\SessionHelper::isLoggedIn();
}
