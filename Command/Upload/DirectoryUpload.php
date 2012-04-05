<?php

namespace BackupTask\Command\Upload;

class DirectoryUpload extends UploadAbstract
{

    protected function connect()
    {
        
    }

    protected function disconnect()
    {
        
    }

    protected function uploadFile($pathToLocalFile, $pathToRemoteFile)
    {
        copy($pathToLocalFile, $pathToRemoteFile);
    }

    protected function getFiles($remoteDirectory)
    {
        $files = scandir($remoteDirectory);

        foreach ($files as &$file) {
            $file = rtrim($remoteDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
        }

        return $files;
    }

    protected function deleteFiles($files)
    {
        foreach ($files as $file) {
            unlink($file);
        }
    }

}