<?php

namespace BackupTask\Command\Upload;

class UploadCommand
{

    private $options;
    private $stats;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function run($file, $prefix = '')
    {
        foreach ($this->options as $name => $options) {
            $class = '\\BackupTask\\Command\\Upload\\' . ucfirst($name) . 'Upload';
            $upload = new $class($options);

            $stat = array(
                'started_at' => time(),
                'path'       => $options['path'],
            );

            // upload backup
            try {
                $upload->upload($file, $options['path'], $prefix, $options['max_count']);
            } catch (\Exception $e) {
                $stat['error'] = $e->getMessage();
            }

            $stat['deleted_files'] = $upload->getDeletedFiles();
            $stat['finished_at'] = time();

            $this->stats[$name] = $stat;
        }

        // delete tmp file after upload
        unlink($file);
    }

    public function getStats()
    {
        return $this->stats;
    }

}