<?php

namespace BackupTask\Command\Backup;

abstract class BackupAbstract
{

    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    abstract protected function createBackup($option);

    public function run($option)
    {
        return $this->createBackup($option);
    }

}