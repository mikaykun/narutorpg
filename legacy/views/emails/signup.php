<?php

declare(strict_types=1);

/**
 * @var \League\Plates\Template\Template $this
 * @var string $name
 * @var string $Code
 * @var string $po
 */
?>
Hallo <?= $name ?>,
vielen Dank für deine Registrierung auf www.narutorpg.de
Damit du anfangen kannst zu spielen, musst du noch deine E-Mail-Adresse bestätigen. Bitte klicke auf den folgenden Link, um dies zu tun:

https://www.narutorpg.de/mailConf.php?Code=<?= $Code ?>&namen=<?= $po . PHP_EOL ?>

Sollte deine E-Mail-Adresse fälschlicherweise in unser System geraten sein, so kannst du diese E-Mail einfach ignorieren.
