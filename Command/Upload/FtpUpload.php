<?php

namespace BackupTask\Command\Upload;

class FtpUpload extends UploadAbstract
{

    private $connection;

    protected function connect()
    {
        $this->connection = ftp_connect($this->options['host']);
        if (!$this->connection) {
            throw new \Exception('Could not connect to ftp host ' . $this->options['host']);
        }

        ftp_login($this->connection, $this->options['user'], $this->options['password']);
        if (!$this->connection) {
            throw new \Exception('Could not login to ftp from user ' . $this->options['user']);
        }
    }

    protected function disconnect()
    {
        $success = ftp_close($this->connection);

        if (!$success) {
            throw new \Exception('Could not close the ftp connection');
        }
    }

    protected function uploadFile($pathToLocalFile, $pathToRemoteFile)
    {
        //ftp_pasv($this->connection, true);
        $success = ftp_put($this->connection, $pathToRemoteFile, $pathToLocalFile, FTP_BINARY);

        if (!$success) {
            throw new \Exception(strtr('Could not upload the file "@local_file" to "@remote_file"', array(
                        '@local_file' => $pathToLocalFile,
                        '@remote_file' => $pathToRemoteFile,
                    )));
        }
    }

    protected function getFiles($remoteDirectory)
    {
        $files = ftp_nlist($this->connection, $remoteDirectory);

        if ($files === false) {
            throw new \Exception('Could not get files from directory ' . $remoteDirectory);
        }

        return $files;
    }

    protected function deleteFiles($files)
    {
        foreach ($files as $file) {
            $success = ftp_delete($this->connection, $file);

            if (!$success) {
                throw new \Exception('Could not delete file from ftp: ' . $file);
            }
        }
    }

}