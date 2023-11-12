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
