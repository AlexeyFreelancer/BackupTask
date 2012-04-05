<?php

namespace BackupTask\Command\Notification;

class EmailNotification extends NotificationAbstract
{

    protected function sendNotification($to, $subject, $message)
    {
        mail($to, $subject, $message);
    }

}