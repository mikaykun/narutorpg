<?php

final class LoggingTool
{
    private $logInfo;
    private $user;
    private $logDate;
    private $logKat;
    private $userIP;

    public function defineLogEntry(string $logKat, string $logInfo): void
    {
        $this->user = $_COOKIE["c_loged"];
        $this->logKat = $logKat;
        $this->logInfo = mysql_real_escape_string(htmlspecialchars($logInfo));
        $this->logDate = date("d.m.Y, H:i");
        $this->userIP = $_SERVER['REMOTE_ADDR'] . " " . gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }

    public function logUpload(): void
    {
        $logUpload = "INSERT INTO Adminlog(Was,Wer,Wann,Bereich,IP) VALUES ('$this->logInfo',$this->user,'$this->logDate','$this->logKat','$this->userIP')";
        mysql_query($logUpload);
    }
}
