<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $userId = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
        $this->mysqli = $mysqli;
    }

    function loadData() {

        $result = $this->mysqli->query("SELECT work_shift_id,CONCAT(work_shift_start,'-',work_shift_stop) as work_shifts FROM bz_timestamp.t_work_shift WHERE (work_shift_start!='OT' and work_shift_start!='none') ORDER BY work_shift_start ASC");
        while ($fetch = $result->fetch_assoc()) {
            $this->obj[] = array(
                "work_shift_id" => $fetch["work_shift_id"],
                "work_shifts" => $fetch["work_shifts"]
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
