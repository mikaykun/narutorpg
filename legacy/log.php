<?php

namespace NarutoLegacy\Log;

function Log(string $message): void
{
    $pdo = nrpg_get_database();
    $stmt = $pdo->prepare("INSERT INTO log (log) VALUES (:message)");
    $stmt->bindParam(':message', $message);
    $stmt->execute();
}

function AdminLog(
    string $section,
    string $message,
    int $user
): void {
    $c_IP = $_SERVER['REMOTE_ADDR'] . ' : ' . gethostbyaddr(
            $_SERVER['REMOTE_ADDR']
        ) . ' : ' . $_SERVER["HTTP_USER_AGENT"];

    $pdo = nrpg_get_database();
    $stmt = $pdo->prepare(
        "INSERT INTO Adminlog (Was, Wer, Wann, Monat, Bereich, IP) VALUES (:was, :wer, :wann, MONTH(CURRENT_DATE()), :bereich, :ip)"
    );
    $stmt->bindParam(':bereich', $section);
    $stmt->bindParam(':was', $message);
    $stmt->bindParam(':wer', $user, \PDO::PARAM_INT);
    $stmt->bindValue(':wann', date("d.m.Y, H:i"));
    $stmt->bindParam(':ip', $c_IP);
    $stmt->execute();
}
