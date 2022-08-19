<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $userId = "";
    private $mysqli = "";
    private $obj = "";

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
        $this->mysqli = $mysqli;
    }

    function loadData() {

        $Hour = date('G');
        //echo $Hour . '<br>' . date('Y-m-d') . '<br>';

        if ($Hour >= 5 && $Hour <= 24) {
            $date_now = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        } else if ($Hour >= 0 || $Hour <= 4) {
            $date_now = date('Y-m-d'); 
        }
        

        $sql = "SELECT a.stamp_id, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,"
                . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start,"
                . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8)=CONVERT(a.stamp_uid USING utf8)"
                . " WHERE a.stamp_uid='$this->userId' AND a.is_delete=0"
                . " AND DATE_FORMAT(a.stamp_date,'%Y-%m-%d') ='$date_now'"
                . " AND (a.stamp_start='0000-00-00 00:00:00' or a.stamp_stop='0000-00-00 00:00:00')";
        //echo $sql;
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $num_rows = $result->num_rows;
        if ($num_rows > 0) {
            $this->obj = array(
                "success" => true,
                "stamp_date" => $row['stamp_date'],
                "stamp_start" => $row['stamp_start'],
                "stamp_stop" => $row['stamp_stop']
            );
        } else {
            $this->obj = array(
                "success" => false
            );
        }
        echo json_encode($this->obj);
    }

}

/*
 * get new class  
 */
$PHPClass = new PHPClass($mysqli);
$PHPClass->loadData();






