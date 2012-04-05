<?php

namespace BackupTask\Command\Backup;

class BackupCommand
{

    private $options;
    private $commonOptions;
    private $stats;

    public function __construct($commonOptions, $options)
    {
        $this->options = $options;
        $this->commonOptions = $commonOptions;
    }

    /**
     * Create backups
     * 
     * @return string $backupArchive - path to archive
     * @throws \Exception - if something is wrong
     */
    public function run()
    {
        $this->checkConfiguration();

        $backupFilesAll = array();
        foreach ($this->options as $name => $options) {
            $stat = array('started_at' => time());

            // class name, e.g. DirectoryBackup, MysqlBackup
            $className = '\\BackupTask\\Command\\Backup\\' . ucfirst($name) . 'Backup';

            $backup = new $className($options);

            // create backups
            $backupFiles = array();
            foreach ($options['items'] as $option) {
                $backupFiles[] = $backup->run($option);
            }

            // check if the backups are valid
            if (!$this->isValidBackups($backupFiles)) {
                throw new \Exception('Invalid backup file. Check ' . implode(', ', $backupFiles));
            }

            $stat['files'] = $backupFiles;
            $stat['finished_at'] = time();

            $this->stats[$name] = $stat;

            $backupFilesAll = array_merge($backupFilesAll, $backupFiles);
        }

        // check if backups are exists
        if (!$backupFilesAll) {
            throw new \Exception('No backup files');
        }

        $stat = array(
            'started_at' => time(),
        );

        // add all backups to one archive
        $backupArchive = $this->addFilesToArchive($backupFilesAll);

        $stat['finished_at'] = time();
        $stat['file'] = $backupArchive;
        $stat['size'] = filesize($backupArchive);
        $this->stats['archive'] = $stat;

        return $backupArchive;
    }

    public function getStats()
    {
        return $this->stats;
    }

    /**
     * Add one archive with all files
     * 
     * @param array $backupFiles - pathes to files
     * @return string - path to archive 
     */
    private function addFilesToArchive($backupFiles)
    {
        $tmpDir = sys_get_temp_dir();

        // create tar with all files
        $path = $tmpDir . DIRECTORY_SEPARATOR . $this->commonOptions['backup_filename_prefix'] . $this->commonOptions['backup_filename'] . '_' . date('Ymd') . '.tar.gz';
        $cmd = strtr('@tar -czf @output -C @dir @files ', array(
            '@tar' => $this->commonOptions['tar_cmd'],
            '@output' => $path,
            '@dir' => dirname($backupFiles[0]),
            '@files' => implode(' ', array_map('basename', $backupFiles))
                ));

        \exec($cmd);

        // delete tmp files
        foreach ($backupFiles as $backupFile) {
            unlink($backupFile);
        }

        return $path;
    }

    /**
     * Check if che backups are valid
     * 
     * @param array $files
     * @return boolean  - true if valid, otherweise - false
     */
    private function isValidBackups($files)
    {
        foreach ($files as $file) {
            if (!is_file($file)) {
                return false;
            }

            if (!filesize($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check configuration
     * 
     * @throws \Exception  - if config is incorrect
     */
    private function checkConfiguration()
    {
        $requiredOptions = array('tar_cmd', 'backup_filename');

        foreach ($requiredOptions as $requiredOption) {
            if (empty($this->commonOptions[$requiredOption])) {
                throw new \Exception("$requiredOption option are required");
            }
        }
    }

}