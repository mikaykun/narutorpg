<?php
declare(strict_types=1);

/**
 * @var \League\Plates\Template\Template $this
 * @var string $title
 */
?>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->

    <title><?= $this->e($title) ?></title>

    <meta name="description" content="Willkommen auf narutorpg.de">
    <meta name="author" content="Achim Kaltz">
    <meta name="robots" content="index">
    <meta name="keywords" content="RPG, Naruto, Narutorpg, Narutogame, Browsergame, Onlinegame, Naruto Onlinegame, Online RPG, Onlinerpg, Narutoonlinegame, Manga, Anime, Welf">

    <link rel="stylesheet" href="/css/legacy.css"/>
    <link rel="stylesheet" href="/Menus/js/themes/blue/style.css" type="text/css" media="print, projection, screen" />

    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/jquery-migrate-1.4.1.min.js"></script>

    <script type="text/javascript" src="/Menus/js/functions.js"></script>
    <script type="text/javascript" src="/Menus/js/image_resize.js"></script>
    <script type="text/javascript" src="/Menus/js/jquery.tablesorter.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#faehs").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#Ninjutsu").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#Taijutsu").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#Genjutsu").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#sMissionenRunde0").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#sMissionenRunde1").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#sMissionenRunde2").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
            $("#sMissionenRunde3").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
        });
    </script>
