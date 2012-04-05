<?php

namespace BackupTask\Command\Backup;

class MysqlBackup extends BackupAbstract
{

    protected function createBackup($option)
    {
        $tmpDir = sys_get_temp_dir();
        $outputFile = $tmpDir . DIRECTORY_SEPARATOR . $option['db_name'] . '.sql';

        // create empty file
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        touch($outputFile);

        // /usr/bin/mysqldump -hlocalhost -uroot -pxxx --no-data dbname logs >> /tmp/dump.sql; 
        // /usr/bin/mysqldump -hlocalhost -uroot -pxxx --opt parser --ignore-table=dbname.logs --ignore-table=dbname.test >> /tmp/dump.sql

        $tablesStructure = array();
        if (!empty($option['tables_structure'])) {
            $tablesStructure = explode(',', $option['tables_structure']);
            $tablesStructure = array_map('trim', $tablesStructure);
        }

        $tablesIgnore = array();
        if (!empty($option['ignore_tables'])) {
            $tablesIgnore = explode(',', $option['ignore_tables']);
            $tablesIgnore = array_map('trim', $tablesIgnore);
        }

        $tablesExclude = array_merge($tablesStructure, $tablesIgnore);

        $cmd = '';

        // tables structure dump
        if ($tablesStructure) {
            $cmd .= '@mysqldump -h@host -u@user -p@password --no-data @db_name @tables_structure >> @output; ';
        }

        $cmd .= '@mysqldump -h@host -u@user -p@password --opt @db_name';

        // ignore tables
        if ($tablesExclude) {
            foreach ($tablesExclude as $table) {
                $cmd .= strtr(' --ignore-table=@db_name.@table', array(
                    '@db_name' => $option['db_name'],
                    '@table'   => trim($table))
                );
            }
        }

        $cmd .= ' >> @output';

        $cmd = strtr($cmd, array(
            '@mysqldump'        => $this->options['mysqldump_cmd'],
            '@user'             => $this->options['user'],
            '@password'         => $this->options['password'],
            '@host'             => $this->options['host'],
            '@db_name'          => $option['db_name'],
            '@tables_structure' => implode(' ', $tablesStructure),
            '@output'           => $outputFile)
        );

        // execute the command - create database backup
        \exec($cmd);

        return $outputFile;
    }

}