<?php



function logToFile($filename, $msg) {
    // open file
    $fd = fopen($filename, "a");
    // write string
    fwrite($fd, $msg . "\r\n");
    // close file
    fclose($fd);
}

function writeLogLogIn($uid, $db) {

    // Get IP address
    if (($remote_addr = gethostbyaddr($_SERVER['REMOTE_ADDR'])) == '') {
        $remote_addr = "REMOTE_ADDR_UNKNOWN";
    }
    // Escape values 
    $remote_addr = $db->escape_string($remote_addr);

    // Construct query   
    $sql = "SELECT * FROM t_log WHERE DATE_FORMAT(log_date,'Y-m-d')=''";

    $sql = "INSERT INTO `t_log`(`remote_addr`, `uid`, `log_date`"
            . ") VALUES ("
            . "'$remote_addr','$uid','" . date('Y-m-d H:i:s') . "')";

    // Execute query and save data
    $result = $db->query($sql)or die('Unable to write to the database');
}

function writeLogStart($uid, $db) {

    // Get IP address
    if (($remote_addr = gethostbyaddr($_SERVER['REMOTE_ADDR'])) == '') {
        $remote_addr = "REMOTE_ADDR_UNKNOWN";
    }
    // Escape values  
    $remote_addr = $db->escape_string($remote_addr);

    // Construct query   
    $sql = "INSERT INTO `t_log`(`remote_addr`, `uid`, `start_date`"
            . ") VALUES ("
            . "'$remote_addr','$uid','$start')";

    // Execute query and save data
    $result = $db->query($sql) or die('Unable to write to the database');
}

function writeLogStop($uid, $stop, $db) {

    // Get IP address
    if (($remote_addr = gethostbyaddr($_SERVER['REMOTE_ADDR'])) == '') {
        $remote_addr = "REMOTE_ADDR_UNKNOWN";
    }
    // Escape values   
    $remote_addr = $db->escape_string($remote_addr);

    // Construct query   
    $sql = "INSERT INTO `t_log`(`remote_addr`, `uid`, `stop_date`"
            . ") VALUES ("
            . "'$remote_addr','$uid','$stop')";

    // Execute query and save data
    $result = $db->query($sql) or die('Unable to write to the database');
}
