Started at: <?php echo date('Y-m-d H:i:s', $backup['directory']['started_at']) ?> 
Finished at: <?php echo date('Y-m-d H:i:s', $backup['directory']['finished_at']) ?> 
Total: <?php echo ($backup['directory']['finished_at'] - $backup['directory']['started_at']) ?> sec.
Count directories: <?php echo count($backup['directory']['files']) ?>