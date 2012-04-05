Success backup

<?php
// common stats
include 'components/common_stats.php';

echo "\n";

// backup stats
foreach ($backup as $key => $value) {
    echo "\n\nBackup {$key} statistics:\n";
    include "components/{$key}_stats.php";
}

echo "\n";

// upload stats
foreach ($upload as $key => $value) {
    echo "\n\nUpload {$key} statistics: \n";
    include "components/{$key}_upload_stats.php";
}
?>