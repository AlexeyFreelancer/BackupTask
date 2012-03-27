Started at: <?php echo date('Y-m-d H:i:s', $backup['archive']['started_at']) ?> 
Finished at: <?php echo date('Y-m-d H:i:s', $backup['archive']['finished_at']) ?> 
Total: <?php echo ($backup['archive']['finished_at'] - $backup['archive']['started_at']) ?> sec.
Size: <?php echo $backup['archive']['size'] ?> bytes
