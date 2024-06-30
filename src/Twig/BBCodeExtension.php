<?php

namespace NarutoRPG\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BBCodeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('bbcode', [$this, 'convertBBCode']),
        ];
    }

    public function convertBBCode(string $content): string
    {
        $content = convert_bbcode_basic($content);
        $content = convert_bbcode_spoiler($content);
        $content = convert_bbcode_fonts($content);
        $content = convert_bbcode_quote($content);

        return nl2br($content);
    }
}
