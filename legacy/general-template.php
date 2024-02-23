<?php

/**
 * Loads header template.
 */
function get_header(): void
{
    include __DIR__ . '/../Menus/layout1.inc';
}

/**
 * Loads footer template.
 */
function get_footer(): void
{
    include __DIR__ . '/../Menus/layout2.inc';
}

/**
 * Loads a view and outputs it.
 */
function get_template_part(string $name, array $data = []): void
{
    static $templates;

    if (!isset($templates)) {
        $templates = new League\Plates\Engine(__DIR__ . '/views');
    }

    echo $templates->render($name, $data);
}
