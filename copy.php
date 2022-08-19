
<?php

$dbname = $_REQUEST['db'];

$filepath = 'http://time.baezeni.com/database/' . $dbname;
$fileto = 'backupDB/' . $dbname;

if (copy($filepath, $fileto)) {
    echo "Copy success!<br>";
} else {
    echo "Copy failed.<br>";
}
//echo $filepath.' '.$fileto;	
$filename = 'backupDB/' . $dbname;
$extractTo = 'backupDB/';

$zip = new ZipArchive;
if ($zip->open($filename) === TRUE) {
    $zip->extractTo($extractTo);
    $zip->close();
    // echo 'ok<br>';
} else {
    echo 'extract failed<br>';
}

$arr = explode('.', $dbname);
$dbname = $arr[0];
//$mysqlDatabaseName ='baezenic_people';
//$mysqlUserName ='root';
//$mysqlPassword ='123456';
//$mysqlHostName ='192.168.5.101';
//$mysqlImportFilename ='backupDB/database/'.$dbname.'.sql';
//echo $mysqlImportFilename;
////DONT EDIT BELOW THIS LINE
////Export the database and output the status to the page
//$command='mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
//$output=array();
//exec($command,$output,$worked);
//switch($worked){
//    case 0:
//        echo 'Import file <b>' .$mysqlImportFilename .'</b> successfully imported to database <b>' .$mysqlDatabaseName .'</b>';
//        break;
//    case 1:
//        echo 'There was an error during import.';
//        break;
//}	
// Name of the file
$filename = 'backupDB/database/' . $dbname . '.sql';
;
// MySQL host
$mysql_host = '192.168.5.101';
// MySQL username
$mysql_username = 'root';
// MySQL password
$mysql_password = '123456';
// Database name
$mysql_database = 'baezenic_people';

// Connect to MySQL server
mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());
// Select database
mysql_select_db($mysql_database) or die('Error selecting MySQL database: ' . mysql_error());

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line) {
// Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

// Add this line to the current segment
    $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
        //echo $templine.'<br>';
        mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
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
                    //echo "Deleting ".$dir.$file." (old) : ";
                    // echo "(date->".date('Y-m-d',filemtime($dir.$file)).")<br/>";
                    unlink($dir . $file);
                    /* 							   echo 
                      "<script>
                      alert('delete old File Back UP Finished.....');
                      window.location='index.php'
                      </script>"; */
                }
            }
        }
    } else {
        echo "ERROR. Could not open directory: $dir<br/>";
    }
}
?>