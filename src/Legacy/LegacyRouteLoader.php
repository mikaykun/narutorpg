<?php

namespace NarutoRPG\Legacy;

use SplFileInfo;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class LegacyRouteLoader extends Loader
{
    private bool $isLoaded = false;

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $type === 'legacy';
    }

    public function load($resource, $type = null): RouteCollection
    {
        if ($this->isLoaded === true) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $collection = new RouteCollection();
        $finder = new Finder();
        $finder->files()->name('*.php');
        $webDir = dirname(__DIR__, 2) . '/web';

        /** @var SplFileInfo $legacyScriptFile */
        foreach ($finder->in($webDir) as $legacyScriptFile) {
            // This assumes all legacy files use ".php" as extension
            $filename = basename($legacyScriptFile->getRelativePathname(), '.php');
            $routeName = sprintf('app.legacy.%s', str_replace('/', '__', $filename));

            $collection->add($routeName, new Route($legacyScriptFile->getRelativePathname(), [
                '_controller' => 'NarutoRPG\Controller\LegacyController::loadLegacyScript',
                'requestPath' => '/' . $legacyScriptFile->getRelativePathname(),
                'legacyScript' => $legacyScriptFile->getPathname(),
            ]));
        }

        return $collection;
    }
}
