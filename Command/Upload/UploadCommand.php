<?php

namespace BackupTask\Command\Upload;

class UploadCommand
{
	private $options;
	private $stats;
	
	public function __construct($options)
	{
		$this->options = $options;
	}
	
	public function run($file, $prefix = '')
	{
		foreach ($this->options as $name => $options) {
			$class = '\\BackupTask\\Command\\Upload\\' . ucfirst($name) . 'Upload';
			$upload = new $class($options);
			
			$this->stats[$name] = array('started_at' => time());
			
			// upload backup
			try {
				$upload->upload($file, $options['path'], $prefix, $options['max_count']);
			} catch (\Exception $e) {
				$this->stats[$name]['error'] = $e->getMessage();
			}
			
			$this->stats['deleted_files'] = $upload->getDeletedFiles();
			$this->stats[$name]['finished_at'] = time();
		}
		
		// delete tmp file after upload
		unlink($file);
	}
	
	public function getStats()
	{
		return $this->stats;
	}

}