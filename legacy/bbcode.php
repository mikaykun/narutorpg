<?php

function convert_bbcode_basic(string $content): string
{
    $content = preg_replace("#\[b\](.*)\[/b\]#isU", " <b> $1 </b> ", $content);
    $content = preg_replace("#\[i\](.*)\[/i\]#isU", " <i> $1 </i> ", $content);
    $content = preg_replace("#\[u\](.*)\[/u\]#isU", " <u> $1 </u> ", $content);

    $content = preg_replace("#\[COLOR=(.*)\](.*)\[\/COLOR\]#isU", " <font color='$1'> $2 </font> ", $content);
    $content = preg_replace("#\[img\](.*)\[\/img\]#isU", " <a target='_blank' href='$1'> <img style='max-height:550px;max-width:550px' src='$1'></a> ", $content);
    $content = preg_replace("#\[URL=(.*)\](.*)\[\/URL\]#isU", " <a href='$1'>$2</a> ", $content);
    $content = preg_replace("#\[URL\](.*)\[\/URL\]#isU", " <a href='$1'>Link</a> ", $content);

    return $content;
}

function convert_bbcode_fonts(string $content): string
{
    $content = preg_replace("#\[size=7\](.*)\[\/size\]#isU", " <font size='1'> $1 </font> ", $content);
    $content = preg_replace("#\[size=9\](.*)\[\/size\]#isU", " <font size='2'> $1 </font> ", $content);
    $content = preg_replace("#\[size=12\](.*)\[\/size\]#isU", " <font size='3'> $1 </font> ", $content);
    $content = preg_replace("#\[size=14\](.*)\[\/size\]#isU", " <font size='4'> $1 </font> ", $content);
    $content = preg_replace("#\[size=18\](.*)\[\/size\]#isU", " <font size='5'> $1 </font> ", $content);
    $content = preg_replace("#\[size=24\](.*)\[\/size\]#isU", " <font size='6'> $1 </font> ", $content);
    return $content;
}

function convert_bbcode_spoiler(string $text): string
{
    $pattern = "#\[SPOILER\](.*?)\[\/SPOILER\]#is";
    $replacement = '<div class="spoiler"><button type="button" class="spoiler-header">SPOILER</button><div class="spoiler-body">$1</div></div>';

    while (preg_match($pattern, $text)) {
        $text = preg_replace($pattern, $replacement, $text, 1);
    }

    return $text;
}

function convert_bbcode_quote(string $text): string
{
    $Vorher = "";
    while ($Vorher != $text) {
        $Vorher = $text;
        $text = preg_replace("#\[QUOTE=(.*)\](.*)\[\/QUOTE\]#isU", " <table border='1' bordercolor='#27591F' cellspacing='0' cellpadding='0' width='100%'><tr><td><table border='0' width='100%'><tr><td background='/layouts/Uebergang/Oben2.png'> Zitiert von: <b> $1 </b></td></tr><tr><td background='/layouts/Uebergang/Untergrund.png'> $2 </td></tr></table></td></tr></table> ", $text);
        $text = preg_replace("#\[QUOTE\](.*)\[\/QUOTE\]#isU", " <table border='1' bordercolor='#27591F' cellspacing='0' cellpadding='0' width='100%'><tr><td><table border='0' width='100%'><tr><td background='/layouts/Uebergang/Oben2.png'> Zitat </td></tr><tr><td background='/layouts/Uebergang/Untergrund.png'> $1 </td></tr></table></td></tr></table> ", $text);
    }
    return $text;
}
