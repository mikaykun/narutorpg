<h1>Anmeldung</h1>
<form action="Anmeldung2.php" method="post">
    <table border='0'>
        <tr>
            <td>Nick/Login:</td>
            <td><input name="nick" type="text" maxlength="50" required></td>
        </tr>
        <tr>
            <td>Passwort:</td>
            <td><input name="pw" type="password" required></td>
        </tr>
        <tr>
            <td>E-Mail:</td>
            <td><input name="mail" type="text" required></td>
        </tr>
        <tr>
            <td>Geworben von</td>
            <td>
                <?php
                $geworbenVon = filter_input(INPUT_GET, 'userWerb', FILTER_SANITIZE_SPECIAL_CHARS);
                echo sprintf('<input type="text" name="werb" value="%s" placeholder="Charaktername">', $geworbenVon);
                ?>
            </td>
        </tr>
    </table>

    <p>
        Hiermit bestätige ich, dass ich die
        <a href="https://wiki.narutorpg.de/index.php?title=Nutzungsbedingungen">Nutzungsbedingungen</a>
        gelesen habe und <b>mindestens</b> 12 Jahre oder älter bin.<br>
        <input type="checkbox" name="Altersbest">
    </p>

    <button type="submit">Anmelden</button>
</form>
