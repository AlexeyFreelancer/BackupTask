<?php

namespace BackupTask\Command\Backup;

class DirectoryBackup extends BackupAbstract
{

    protected function createBackup($option)
    {
        $tmpDir = sys_get_temp_dir();

        // build command
        $outputFile = $tmpDir . DIRECTORY_SEPARATOR . $option['name'] . '.tar';
        $cmd = strtr('@tar cf @output -C @dir @file', array(
            '@tar' => $this->options['tar_cmd'],
            '@output' => $outputFile,
            '@dir' => dirname($option['path']),
            '@file' => basename($option['path']),
                ));

        // exclude files
        if (!empty($option['exclude'])) {
            foreach (explode(',', $option['exclude']) as $file) {
                $cmd .= strtr(' --exclude="@file"', array(
                    '@file' => trim($file)
                        ));
            }
        }

        // execute command - create tar
        \exec($cmd);

        return $outputFile;
    }

}