

<?php

$hostname = "192.168.5.101";
$username = "root";
$password = "123456";
$database = "bz_timestamp";

$mysqli = new mysqli($hostname, $username, $password, $database);
$mysqli->query("SET NAMES 'utf8'");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$dir = dirname(__file__);
$dbname = "mysqlbackup--31-08-2015@114024.sql.zip";
$filepath = $dir . '/DB/' . $dbname;
$extractTo = $dir . '/extract/';

$zip = new ZipArchive;
if ($zip->open($filepath) === TRUE) {
    $zip->extractTo($extractTo);
    $zip->close();
    echo 'extract ok<br>';
} else {
    echo 'extract failed<br>';
}
$arr = explode('.', $dbname);
$dbname = $arr[0];
$filename = $extractTo . '/' . $dbname . '.sql';

$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line) {
// Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

    $templine .= $line;
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
        //echo $templine . '<br>';
        $mysqli->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');
        // Reset temp variable to empty
        $templine = '';
    }
}

echo "Tables imported successfully";

$dir = 'backupDB/';
//echo "STARTING<br/>";

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while ($file = readdir($dh)) {
            if (!is_dir($dir . $file)) {
                //if 10 days old, delete
                if ((eregi('.zip', $file)) && (filemtime($dir . $file) < strtotime('-15 days'))) {                    
                    unlink($dir . $file);                 
                }
            }
        }
    } else {
        echo "ERROR. Could not open directory: $dir<br/>";
    }
}
?>