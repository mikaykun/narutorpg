<?php

declare(strict_types=1);

namespace App;

/**
 * Hilfsklasse zur Verwaltung von Benutzer-Sitzungen über Cookies.
 */
final class SessionHelper
{
    /**
     * @var string Name des Cookies für die Benutzer-ID.
     */
    private const string COOKIE_USER_ID = 'c_loged';

    /**
     * @var string Name des Cookies für das Passwort.
     */
    private const string COOKIE_PASSWORD = 'c_pw';

    /**
     * Ruft die Benutzer-ID aus dem Cookie ab.
     *
     * @return int|null Die Benutzer-ID oder null, falls das Cookie nicht gesetzt ist.
     */
    public static function getUserId(): ?int
    {
        if (! isset($_COOKIE[self::COOKIE_USER_ID])) {
            return null;
        }

        return (int) $_COOKIE[self::COOKIE_USER_ID];
    }

    /**
     * @var string Session key for the selected character ID.
     */
    private const string SESSION_CHAR_ID = 'char_id';

    /**
     * Ruft das Passwort (Hash) aus dem Cookie ab.
     *
     * @return string|null Das Passwort oder null, falls das Cookie nicht gesetzt ist.
     */
    public static function getPassword(): ?string
    {
        if (! isset($_COOKIE[self::COOKIE_PASSWORD])) {
            return null;
        }

        return (string) $_COOKIE[self::COOKIE_PASSWORD];
    }

    /**
     * Prüft, ob ein Benutzer aktuell eingeloggt ist.
     *
     * @return bool True, wenn ID und Passwort Cookies vorhanden sind, andernfalls false.
     */
    public static function isLoggedIn(): bool
    {
        return self::getUserId() !== null && self::getPassword() !== null;
    }

    /**
     * Returns the ID of the currently selected character.
     *
     * After the character-selection screen writes the chosen char id into the
     * PHP session this method returns it. Falls back to getUserId() so that
     * existing sessions (created before character selection was introduced)
     * continue to work without re-login — in the legacy 1:1 model the account
     * id and the character id are the same value.
     *
     * @return int|null The character ID, or null when not logged in at all.
     */
    public static function getCharacterId(): ?int
    {
        if (isset($_SESSION[self::SESSION_CHAR_ID])) {
            return (int) $_SESSION[self::SESSION_CHAR_ID];
        }

        // Fallback for sessions that pre-date character selection.
        return self::getUserId();
    }

    /**
     * Writes the selected character ID into the PHP session.
     */
    public static function setCharacterId(int $charId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION[self::SESSION_CHAR_ID] = $charId;
    }

    /**
     * Loggt den Benutzer aus, indem die entsprechenden Cookies gelöscht werden.
     */
    public static function logout(): void
    {
        setcookie(self::COOKIE_USER_ID, '', ['expires' => time() - 3600]);
        setcookie(self::COOKIE_PASSWORD, '', ['expires' => time() - 3600]);
    }
}
