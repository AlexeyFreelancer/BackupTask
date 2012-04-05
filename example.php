<?php

// register autoloader
// git clone git://github.com/symfony/ClassLoader.git
require_once 'ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->register();
$loader->registerNamespace('BackupTask', '.');

$config = include '/path/to/config.php';
$backupTask = new BackupTask\BackupTask($config);
$backupTask->run();