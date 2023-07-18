<?php

date_default_timezone_set('Asia/Bangkok');


    define('ENVIRONMENT', 'development');


switch (ENVIRONMENT) {
    case 'development':
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $database = "bz_timestamp";
        break;

    default:
        /* $hostname = "104.199.174.117";
        $username = "root";
        $password = "5*XJ15IL34zYj2o*0y@5";
        $database = "bz_timestamp";*/
		
	$hostname = "192.168.5.109";
        $username = "root";
        $password = "!t@Supp0rt";
        $database = "bz_timestamp";
        break;
}


$mysqli = new mysqli($hostname, $username, $password, $database);
$mysqli->query("SET NAMES 'utf8'");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

