Example (note: uses Symfony Class Loader: https://github.com/symfony/ClassLoader)

<?php
require_once __DIR__.'/../lib/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->register();
$loader->registerNamespace('BackupTask', realpath(__DIR__.'/../lib'));

$config = '/path/to/config.php'; // see example in config.dist.php
$backup = new BackupTask\BackupTask($config);
try {
	$backup->run();
} catch (Exception $e) {
	echo $e->getMessage();
}
?>