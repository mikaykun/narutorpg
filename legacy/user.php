<?php

function nrpg_get_user_by_id(int|null $id): UserData
{
    $pdo = nrpg_get_database();

    $query = $pdo->prepare("SELECT * FROM userdaten WHERE id = :id LIMIT 1");
    $query->execute(['id' => $id]);
    $user = $query->fetchObject(UserData::class);

    if ($user instanceof UserData) {
        return $user;
    }

    return new UserData();
}

function nrpg_get_current_user(): UserData
{
    return nrpg_get_user_by_id(\NarutoRPG\SessionHelper::getUserId());
}

function nrpg_get_current_character(): CharacterData
{
    $pdo = nrpg_get_database();

    $query = $pdo->prepare("SELECT * FROM user WHERE id = :id LIMIT 1");
    $query->execute(['id' => \NarutoRPG\SessionHelper::getUserId()]);
    $user = $query->fetchObject(CharacterData::class);

    if ($user instanceof CharacterData) {
        return $user;
    }

    return new CharacterData();
}

function is_user_logged_in(): bool
{
    return \NarutoRPG\SessionHelper::isLoggedIn();
}

function verify_loggedin_user(): void
{
    if (!is_user_logged_in()) {
        header("Location: /index.php");
        exit;
    }
}
