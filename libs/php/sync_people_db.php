<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $mysqli = "";
    var $dbName = 'baezenic_people';

    function __construct($mysqli) {

        $this->mysqli = $mysqli;
        $data = json_decode($_POST["data"]);
        //echo '<pre>';
        //print_r($data);
        $this->backup_tables($data);
    }

    function backup_tables($data) {        
        $status = false;


        // $mysqli = new mysqli('192.168.5.109', 'root', '!t@Supp0rt', 'bz_timestamp');
        // $mysqli->query("SET NAMES 'utf8'");
       
        // if (mysqli_connect_errno()) {
        //     printf("Connect failed: %s\n", mysqli_connect_error());
        //     exit();
        // }


        try {
            $table = $this->dbName . ".t_people";
            $sql = 'CREATE DATABASE IF NOT EXISTS ' . $this->dbName . ";";
            
            $this->mysqli->query($sql);

            $sql = 'TRUNCATE TABLE ' . $table . ';';

            $this->mysqli->query($sql);            
            $sql = "INSERT INTO " . $table." VALUES";
            $num = 0;
            foreach ($data as $key => $value) {              
                $num++;
                $sql .= "(";
                foreach ($data[$key] as $key02 => $row) {

                    $sql .= "'" . $row . "'";
                    $sql .= ',';
                    
                }
                $lastIndex = strripos($sql, ",");
                $sql = substr($sql, 0, $lastIndex);
                if(count($data) != $num){
                    $sql .= "),";    
                }else{
                    $sql .= ");";
                }
                
            }
            $this->mysqli->query($sql);
            $status = true;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
        echo $status;
    }

}

$PHPClass = new PHPClass($mysqli);


