Started at: <?php echo date('Y-m-d H:i:s', $upload['directory']['started_at']) ?> 
Finished at: <?php echo date('Y-m-d H:i:s', $upload['directory']['finished_at']) ?> 
Total: <?php echo ($upload['directory']['finished_at'] - $upload['directory']['started_at']) ?> sec.