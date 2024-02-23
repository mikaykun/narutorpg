<?php

function str_replace_once(string|array $search, string|array $replace, string $subject, int $offset = 0): string
{
    if (is_array($search)) {
        if (is_array($replace)) {
            foreach ($search as $x => $value) {
                $subject = str_replace_once($value, $replace[$x], $subject, $offset);
            }
        } else {
            foreach ($search as $value) {
                $subject = str_replace_once($value, $replace, $subject, $offset);
            }
        }
    } elseif (is_array($replace)) {
        foreach ($replace as $value) {
            $subject = str_replace_once($search, $value, $subject, $offset);
        }
    } else {
        $pos = strpos($subject, $search, $offset);
        if ($pos !== false) {
            $offset = $pos + strlen($search);
            $subject = substr($subject, 0, $pos) . $replace . substr($subject, $offset);
        }
    }
    return $subject;
}

/**
 * Checks if $needle is found in $haystack and returns a boolean value
 * (true/false) whether or not the $needle was found.
 */
function array_contains(array $haystack, string $needle): bool
{
    foreach ($haystack as $value) {
        if (str_contains((string)$value, $needle)) {
            return true;
        }
    }
    return false;
}
