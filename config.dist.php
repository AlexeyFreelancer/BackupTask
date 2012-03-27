<?php

// prefix: daily, weekly, monthly
$prefix = '';
if (!empty($argv[1])) {
	$prefix = $argv[1] . '_';
}

return array(
	// common options
	'common' => array(
		'tar_cmd' => '/bin/tar',
		'gzip_cmd' => '/bin/gzip',

		'backup_filename_prefix' => $prefix, 
		'backup_filename' => 'backupname',
	),
	
	// backup options
	'backup' => array(
		// directory backup
		'directory' => array(
			'tar_cmd' => '/bin/tar',
			'items' => array(
				array(
					'name' => 'home_user1',
					'path' => '/home/user1',
					'exclude' => 'tmp,logs,cache',
				),
				array(
					'name' => 'home_user2',
					'path' => '/home/user2',
					'exclude' => 'tmp',
				)
			)
		),
		
		// database backup
		'mysql' => array(
			'mysqldump_cmd' => '/usr/bin/mysqldump',

			'user' => 'root',
			'password' => 'xxx',
			'host' => 'localhost',

			'items' => array(
				array(
					'db_name' => 'xxx',
					'ignore_tables' => 'test',
					'tables_structure' => 'logs,sessions',
				),
				array(
					'db_name' => 'xxx2',
				),
			),
		),
	),
	
	// upload backup options
	'upload' => array(
		// upload to local directoey
		'directory' => array(
			'max_count' => 3,
			'path' => '/backups',
		),
		
		// upload to ftp
		'ftp' => array(
			'max_count' => 3,
			'path' => '/backups',
			'host' => 'xxx',
			'user' => 'xxx',
			'password' => 'xxx'
		),
	),

	// notification options
	'nofification' => array(
		// email notification
		'email' => array(
			'on_success' => array(
				'to' => 'xxx@xxx.xxx',
				'subject' => 'Success backup',
				'template' => realpath(__DIR__ . '/../Command/Notification/email_templates/success.php')
			),

			'on_failed' => array(
				'to' => 'xxx@xxx.xxx',
				'subject' => 'Failed backup',
				'template' => realpath(__DIR__ . '/../Command/Notification/email_templates/failed.php')
			),
		),
	),
);