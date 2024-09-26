<?php

declare(strict_types=1);

namespace NarutoRPG\Service;

final class LegacyDatabaseConnection extends \PDO
{
    public function __construct(string $host, string $database, string $username, string $password)
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', $host, $database);
        parent::__construct($dsn, $username, $password);
    }
}
