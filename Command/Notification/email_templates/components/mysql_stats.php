Started at: <?php echo date('Y-m-d H:i:s', $backup['mysql']['started_at']) ?> 
Finished at: <?php echo date('Y-m-d H:i:s', $backup['mysql']['finished_at']) ?> 
Total: <?php echo ($backup['mysql']['finished_at'] - $backup['mysql']['started_at']) ?> sec.
Count databases: <?php echo count($backup['mysql']['files']) ?>
