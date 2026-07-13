<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class LegacyController extends Controller
{
    public function __invoke(Request $request, string $legacyPath): StreamedResponse
    {
        $this->seedMysqlShim();

        $script = $this->resolveLegacyScript($legacyPath);

        if ($script === false) {
            abort(404);
        }

        return response()->stream(function () use ($script): void {
            require $script;
        });
    }

    /**
     * Seeds the mysql_* shim's global mysqli handle so legacy scripts that call
     * mysql_query() find a live connection. The shim (legacy/libraries/mysql.php)
     * stores the connection in $__MYSQLI_WRAPPER_LINK; calling mysql_connect()
     * (the shim function, not the native extension) populates that global.
     *
     * Public so that LegacyController can delegate to this method instead of
     * calling mysql_connect() directly.
     */
    public function seedMysqlShim(): void
    {
        // mysql_connect() is defined by the shim included via nrpg-main.php.
        // It creates a mysqli connection and stores it in $__MYSQLI_WRAPPER_LINK.
        $conn = mysql_connect(
            $_SERVER['DB_HOST'],
            $_SERVER['DB_USERNAME'],
            $_SERVER['DB_PASSWORD'],
        );

        if (! $conn) {
            throw new \RuntimeException('mysql_* shim: mysqli connection could not be established.');
        }

        mysql_select_db($_SERVER['DB_DATABASE']);
    }

    private function resolveLegacyScript(string $legacyPath): string|false
    {
        $roots = array_filter([
            realpath(base_path()),
            realpath(base_path('web')),
        ]);

        foreach ($roots as $root) {
            $candidate = realpath($root.DIRECTORY_SEPARATOR.$legacyPath.'.php');

            if ($candidate !== false && str_starts_with($candidate, $root.DIRECTORY_SEPARATOR)) {
                return $candidate;
            }
        }

        return false;
    }
}
