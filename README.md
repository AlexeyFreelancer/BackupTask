# BackupTask can help you to backup your directories and databases

Create directories and databases backup, upload it to local directory or to ftp and send notification email with detail statistics.

## Requirements

 * PHP 5.3
 * Unix OS
 
## Installation

### Download library

    git clone git://github.com/AlexeyFreelancer/BackupTask.git
    git clone git://github.com/symfony/ClassLoader.git
    
### Configure

    cp ./BackupTask/config.dist.php config.php
    
Change settings in config.php 

### Usage

Create new file backup.php with following code

    <?php

    require_once 'ClassLoader/UniversalClassLoader.php';

    use Symfony\Component\ClassLoader\UniversalClassLoader;

    $loader = new UniversalClassLoader();
    $loader->register();
    $loader->registerNamespace('BackupTask', '.');

    $config = include 'config.php';
    $backupTask = new BackupTask\BackupTask($config);

    try {
            $backupTask->run();
    } catch (Exception $e) {
            echo $e->getMessage();
    }


### Configure cron job

    @daily  /usr/bin/php /path/to/backup.php daily
    @weekly /usr/bin/php /path/to/backup.php weekly
    @monthly /usr/bin/php /path/to/backup.php monthly




