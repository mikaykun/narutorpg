<?php

final class PrivateMessage
{
    private int $receiver_id;
    private string $subject;
    private string $body;
    private int $sender_id = 0;
    private string $sender_name = 'System';

    public function from(string $sender_name, int $sender_id): PrivateMessage
    {
        $this->sender_name = $sender_name;
        $this->sender_id = $sender_id;
        return $this;
    }

    public function to(int $receiver_id): PrivateMessage
    {
        $this->receiver_id = $receiver_id;
        return $this;
    }

    public function subject(string $subject): PrivateMessage
    {
        $this->subject = $subject;
        return $this;
    }

    public function body(string $body): PrivateMessage
    {
        $this->body = $body;
        return $this;
    }

    public function send(): bool
    {
        $dbc = nrpg_get_database();

        if ($dbc->inTransaction()) {
            $user_update = $dbc->prepare("UPDATE user SET nm = 1 WHERE id = :id")
                ->execute([':id' => $this->receiver_id]);
            $message_send = $dbc->prepare(
                "INSERT INTO Posteingang (An, Von, Name, Betreff, Text, Datum) VALUES (:an, :von, :name, :subject, :body, :datum)"
            )
                ->execute([
                    ':an' => $this->receiver_id,
                    ':von' => $this->sender_id,
                    ':name' => $this->sender_name,
                    ':subject' => $this->subject,
                    ':body' => $this->body,
                    ':datum' => date("d.m.Y, H:i"),
                ]);

            if ($user_update && $message_send) {
                return true;
            }
        } else {
            $dbc->beginTransaction();

            $user_update = $dbc->prepare("UPDATE user SET nm = 1 WHERE id = :id")
                ->execute([':id' => $this->receiver_id]);
            $message_send = $dbc->prepare(
                "INSERT INTO Posteingang (An, Von, Name, Betreff, Text, Datum) VALUES (:an, :von, :name, :subject, :body, :datum)"
            )
                ->execute([
                    ':an' => $this->receiver_id,
                    ':von' => $this->sender_id,
                    ':name' => $this->sender_name,
                    ':subject' => $this->subject,
                    ':body' => $this->body,
                    ':datum' => date("d.m.Y, H:i"),
                ]);

            if ($user_update && $message_send) {
                $dbc->commit();
                return true;
            }

            $dbc->rollBack();
        }
        return false;
    }
}
