<?php

namespace BackupTask\Command\Notification;

class NotificationCommand
{

    private $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function run($backupStats)
    {
        foreach ($this->options as $name => $options) {
            $class = '\\BackupTask\\Command\\Notification\\' . ucfirst($name) . 'Notification';
            $notification = new $class($options);
            $notification->run($backupStats);
        }
    }

}