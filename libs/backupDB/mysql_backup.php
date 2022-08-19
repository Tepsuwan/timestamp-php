<?php

#####################
//CONFIGURATIONS
#####################
// Define the name of the backup directory

$hostname = "192.168.5.101";
$username = "root";
$password = "123456";
$database = "bz_timestamp";
$dir = dirname(__file__) . '/DB';


define('BACKUP_DIR', $dir);
// Define  Database Credentials
define('HOST', $hostname);
define('USER', $username);
define('PASSWORD', $password);
define('DB_NAME', $database);
define("TABLES", '*');


// Set execution time limit
if (function_exists('max_execution_time')) {
    if (ini_get('max_execution_time') > 0)
        set_time_limit(0);
}
//END  OF  CONFIGURATIONS

/*
  Create backupDir (if it's not yet created ) , with proper permissions .
  Create a ".htaccess" file to restrict web-access
 */
if (!file_exists(BACKUP_DIR))
    mkdir(BACKUP_DIR, 0700);
if (!is_writable(BACKUP_DIR))
    chmod(BACKUP_DIR, 0700);


$archiveName = 'mysqlbackup--' . date('d-m-Y') . '@' . date('his') . '.sql';
createNewArchive($archiveName, TABLES);

function createNewArchive($archiveName, $tables = '*') {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s", mysqli_connect_error());
        exit();
    }
    // Introduction information

    $return = "--\n";
    $return .= "-- A Mysql Backup System \n";
    $return .= "--\n";
    $return .= '-- Export created: ' . date("Y/m/d") . ' on ' . date("h:i") . "\n\n\n";
    $return .= "--\n";
    $return .= "-- Database : " . DB_NAME . "\n";
    $return .= "--\n";
    $return .= "-- --------------------------------------------------\n";
    $return .= "-- ---------------------------------------------------\n";
    $return .= 'SET AUTOCOMMIT = 0 ;' . "\n";
    $return .= 'SET FOREIGN_KEY_CHECKS=0 ;' . "\n";
    //$tables = array();


    if ($tables == '*') {
        $tables = array();
        $result = $this->mysqli->query('SHOW TABLES');
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

// Cycle through each  table
    foreach ($tables as $table) {
// Get content of each table
        $result = $mysqli->query('SELECT * FROM ' . $table);
// Get number of fields (columns) of each table
        $num_fields = $mysqli->field_count;
// Add table information
        $return .= "--\n";
        $return .= '-- Tabel structure for table `' . $table . '`' . "\n";
        $return .= "--\n";
        $return.= 'DROP TABLE  IF EXISTS `' . $table . '`;' . "\n";
// Get the table-shema
        $shema = $mysqli->query('SHOW CREATE TABLE ' . $table);
// Extract table shema
        $tableshema = $shema->fetch_row();
// Append table-shema into code
        $return.= $tableshema[1] . ";" . "\n\n";
// Cycle through each table-row
        while ($rowdata = $result->fetch_row()) {
            // Prepare code that will insert data into table
            $return .= 'INSERT INTO `' . $table . '`  VALUES ( ';
            // Extract data of each row
            for ($i = 0; $i < $num_fields; $i++) {
                $return .= '"' . $rowdata[$i] . "\",";
            }
            // Let's remove the last comma
            $return = substr("$return", 0, -1);
            $return .= ");" . "\n";
        }
        $return .= "\n\n";
    }
// Close the connection
    $mysqli->close();
    $return .= 'SET FOREIGN_KEY_CHECKS = 1 ; ' . "\n";
    $return .= 'COMMIT ; ' . "\n";
    $return .= 'SET AUTOCOMMIT = 1 ; ' . "\n";

//$file = file_put_contents($archiveName , $return) ;
    $zip = new ZipArchive();
    $resOpen = $zip->open(BACKUP_DIR . '/' . $archiveName . ".zip", ZIPARCHIVE::CREATE);
    if ($resOpen) {
        $zip->addFromString($archiveName, "$return");
    }
    $zip->close();
    $fileSize = getFileSizeUnit(filesize(BACKUP_DIR . "/" . $archiveName . '.zip'));
    $message = "<BR><h1>BACKUP  completed ,</h1>"
            . "<br>"
            . "the archive has the name of  : <b>  $archiveName  </b> and it's file-size is :   $fileSize  ";
    echo $message;
}

// End of function creatNewArchive
// Function to append proper Unit after a file-size .
function getFileSizeUnit($file_size) {
    switch (true) {
        case ($file_size / 1024 < 1) :
            return intval($file_size) . " Bytes";
            break;
        case ($file_size / 1024 >= 1 && $file_size / (1024 * 1024) < 1) :
            return round(($file_size / 1024), 2) . " KB";
            break;
        default:
            return round($file_size / (1024 * 1024), 2) . " MB";
    }
}

// End of Function getFileSizeUnit
// Funciton getNameOfLastArchieve
function getNameOfLastArchieve($backupDir) {
    $allArchieves = array();
    $iterator = new DirectoryIterator($backupDir);
    foreach ($iterator as $fileInfo) {
        if (!$fileInfo->isDir() && $fileInfo->getExtension() === 'zip') {
            $allArchieves[] = $fileInfo->getFilename();
        }
    }
    return end($allArchieves);
}

// End of Function getNameOfLastArchieve
// Function allowCreateNewArchive
function allowCreateNewArchive($timestampOfLatestArchive, $timestamp = 24) {
    $yesterday = time() - $timestamp * 3600;
    return ($yesterday >= $timestampOfLatestArchive) ? true : false;
}

// End of Function allowCreateNewArchive