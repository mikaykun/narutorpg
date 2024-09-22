<?php

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/gegner',
        __DIR__ . '/Kampfscript/Operatoren',
        __DIR__ . '/legacy',
        __DIR__ . '/Menus/layout1.inc',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/web',
    ])
    ->withFileExtensions(['php', 'inc'])
    ->withAttributesSets(
        symfony: true,
        doctrine: true
    )
    ->withPhpSets(php81: true)
    ->withCache(
        cacheDirectory: __DIR__ . '/var/cache/rector',
        cacheClass: FileCacheStorage::class
    );
