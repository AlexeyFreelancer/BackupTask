Started at: <?php echo date('Y-m-d H:i:s', $upload['ftp']['started_at']) ?> 
Finished at: <?php echo date('Y-m-d H:i:s', $upload['ftp']['finished_at']) ?> 
Total: <?php echo ($upload['ftp']['finished_at'] - $upload['ftp']['started_at']) ?> sec.