<?php

namespace BackupTask\Command\Upload;

abstract class UploadAbstract
{

    protected $options;
    private $deletedFiles = 0;

    public function __construct($options)
    {
        $this->options = $options;
    }

    abstract protected function connect();

    abstract protected function disconnect();

    abstract protected function uploadFile($pathToLocalFile, $pathToRemoteFile);

    /**
     * Upload local file to server
     * 
     * @param string $pathToLocalFile
     * @param string $remoteDirectory
     * @param string $prefix
     * @param int $maxCountCopies
     * @throws Exception - if something wrong
     */
    public function upload($pathToLocalFile, $remoteDirectory, $prefix = 'daily_', $maxCountCopies = 2)
    {
        $pathToRemoteFile = $remoteDirectory . DIRECTORY_SEPARATOR . basename($pathToLocalFile);

        $this->connect();
        $this->uploadFile($pathToLocalFile, $pathToRemoteFile);
        $this->deleteOlderBackups($remoteDirectory, $prefix, $maxCountCopies);
        $this->disconnect();
    }

    abstract protected function getFiles($remoteDirectory);

    abstract protected function deleteFiles($files);

    private function deleteOlderBackups($remoteDirectory, $prefix, $maxCountCopies)
    {
        $files = $this->getFiles($remoteDirectory);
        $files = $this->filterFiles($files, $prefix);

        // not necessary delete files
        if (count($files) <= $maxCountCopies) {
            return false;
        }

        $countDeleteFiles = count($files) - $maxCountCopies;
        $deleteFiles = $this->getFilesToDelete($files, $countDeleteFiles);
        $this->deleteFiles($deleteFiles);

        $this->deletedFiles = count($deleteFiles);
    }

    public function getDeletedFiles()
    {
        return $this->deletedFiles;
    }

    private function filterFiles($files, $prefix = '')
    {
        $filteredFiles = array();

        foreach ($files as $file) {
            // check if the file starts with prefix
            if (strpos(basename($file), $prefix) !== 0) {
                continue;
            }

            $filteredFiles[] = $file;
        }

        return $filteredFiles;
    }

    private function getFilesToDelete($files, $countDeleteFiles)
    {
        // get archives created time
        $filesTimes = array();
        foreach ($files as $file) {
            if (!preg_match('/_(\d+)\.tar\.gz$/', basename($file), $matches)) {
                continue;
            }

            $filesTimes[$matches[1]] = $file;
        }

        ksort($filesTimes, SORT_NUMERIC);

        $deleteFiles = array_slice($filesTimes, 0, $countDeleteFiles);

        return $deleteFiles;
    }

}