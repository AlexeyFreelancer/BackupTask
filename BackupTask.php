<?php

namespace BackupTask;

use BackupTask\Command\Backup\BackupCommand;
use BackupTask\Command\Upload\UploadCommand;
use BackupTask\Command\Notification\NotificationCommand;

class BackupTask
{

    private $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function run()
    {
        $stats = array(
            'started_at' => time(),
            'count_errors' => 0
        );

        // create backup
        $backup = new BackupCommand($this->options['common'], $this->options['backup']);
        $backupArchive = $backup->run();
        $stats['backup'] = $backup->getStats();

        foreach ($stats['backup'] as $backupStat) {
            if (!empty($backupStat['error'])) {
                $stats['count_errors']++;
            }
        }

        if (!empty($stats['backup']['error'])) {
            $stats['count_errors']++;
        }

        // upload backup
        if (!empty($this->options['upload'])) {
            $upload = new UploadCommand($this->options['upload']);
            $upload->run($backupArchive, $this->options['common']['backup_filename_prefix']);
            $stats['upload'] = $upload->getStats();

            foreach ($stats['upload'] as $uploadStat) {
                if (!empty($uploadStat['error'])) {
                    $stats['count_errors']++;
                }
            }
        }

        $stats['finished_at'] = time();

        // send notification
        if (!empty($this->options['nofification'])) {
            $notification = new NotificationCommand($this->options['nofification']);
            $notification->run($stats);
        }
    }

}