<?php

namespace NarutoRPG;

final class SessionHelper
{
    private const COOKIE_USER_ID = 'c_loged';
    private const COOKIE_PASSWORD = 'c_pw';

    public static function getUserId(): ?int
    {
        if (!isset($_COOKIE[self::COOKIE_USER_ID])) {
            return null;
        }

        return (int)$_COOKIE[self::COOKIE_USER_ID];
    }

    public static function getPassword(): ?string
    {
        if (!isset($_COOKIE[self::COOKIE_PASSWORD])) {
            return null;
        }

        return (string)$_COOKIE[self::COOKIE_PASSWORD];
    }

    public static function isLoggedIn(): bool
    {
        return self::getUserId() !== null && self::getPassword() !== null;
    }

    public static function logout(): void
    {
        setcookie(self::COOKIE_USER_ID, '', ['expires' => time() - 3600]);
        setcookie(self::COOKIE_PASSWORD, '', ['expires' => time() - 3600]);
    }
}
