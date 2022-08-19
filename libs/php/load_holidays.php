<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $userId = "";
    private $year = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
        $this->mysqli = $mysqli;
        $this->year = date('Y');
    }

    function loadData() {

        $sql = "SELECT reason_id,reason_day,reason_name FROM t_reason WHERE 1";
        $result = $this->mysqli->query($sql);
        while ($fetch = $result->fetch_assoc()) {
            $reason_id = $fetch["reason_id"];
            $sql = "SELECT count(reason_id) as reason FROM t_stamp WHERE stamp_uid='$this->userId' and reason_id='$reason_id' and year(stamp_date)='$this->year' and is_delete=0";
            $result2 = $this->mysqli->query($sql);
            $row = $result2->fetch_assoc();
            $reason = $row['reason'];

            $this->obj[] = array(
                "reason_id" => $fetch["reason_id"],
                "reason_name" => $fetch["reason_name"],
                "reason_day" => $fetch["reason_day"],
                "reason_use" => $reason,
                "reason_balance" => $fetch["reason_day"] - $reason
            );
        }

        echo "{\"data\":" . json_encode($this->obj) . "}";
    }

}

/*
 * get new class  
 */
$PHPClass = new PHPClass($mysqli);
$PHPClass->loadData();
