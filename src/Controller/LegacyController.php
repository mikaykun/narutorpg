<?php

namespace NarutoRPG\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LegacyController extends AbstractController
{
    /**
     * @throws \Exception If the database connection could not be established
     */
    public function loadLegacyScript(string $requestPath, string $legacyScript): StreamedResponse
    {
        // Legacy database connection
        $conn = @mysql_connect($_SERVER['DB_SERVER'], $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);

        if ($conn) {
            mysql_select_db($_SERVER['DB_NAME']);
        } else {
            throw new \Exception("Database connection could not be established!");
        }

        return new StreamedResponse(
            function () use ($requestPath, $legacyScript): void {
                $_SERVER['PHP_SELF'] = $requestPath;
                $_SERVER['SCRIPT_NAME'] = $requestPath;
                $_SERVER['SCRIPT_FILENAME'] = $legacyScript;

                chdir(dirname($legacyScript));

                require $legacyScript;
            }
        );
    }
}
