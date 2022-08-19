<?php

session_start();
include_once('../connect/connect.php');

class PHPClass {

    private $stamp_shift = "";
    private $stamp_id = "";
    private $updateId = '';
    private $data = '';
    private $userId = "";
    private $mysqli = "";

    function __construct($mysqli) {
        $this->mysqli = $mysqli;
//$this->stamp_id = $_POST['id'];
        $this->data = json_decode($_POST['data']);
        $this->updateId = json_decode($_POST['updateId']);
        $this->userId = $_POST["uid"];
    }

    function update() {


        $int = 0;
        if (isset($this->data) && $this->data) {
            //start foreach-----------------------------------------------------
            foreach ($this->data as $change) {

                $rowId = $change[0];
                $colId = $change[1];
                $oldVal = $change[2];
                $newVal = $change[3];
                //--------------------------------------------------------------
                $this->stamp_id = $this->updateId[$int];
                $int++;

                $sql = "SELECT a.stamp_date,DATE_FORMAT(a.stamp_start,'%Y-%m-%d %H:%i:%s') as start,"
                        . " DATE_FORMAT(a.stamp_stop,'%Y-%m-%d %H:%i:%s') as stop,b.work_shift_start,b.work_shift_stop"
                        . " FROM t_stamp a"
                        . " LEFT JOIN t_work_shift b ON b.work_shift_id=a.work_shift_id "
                        . " WHERE a.stamp_id='$this->stamp_id'";
                
                $result = $this->mysqli->query($sql);
                $fetch = $result->fetch_assoc();
                $num_rows = $result->num_rows;
                if ($num_rows > 0) {

                    $Late = '';
                    $Overtime = '';
                    $Before = '';
                    $stamp_date = $fetch['stamp_date'];
                    $stamp_start = date('H:i', strtotime($fetch['start']));
                    $stamp_stop = date('H:i', strtotime($fetch['stop']));


                    if ($stamp_start == "00:00") {
                        $json = array(
                            "success" => true,
                            "uid" => $this->userId
                        );
                        echo json_encode($json);
                        return;
                    }
                    //get late ot-----------------------------------------------

                    if ($newVal !== "") {

                        if ($newVal == 'none') {
                            $Late = '';
                            $Overtime = '';
                            $Before = '';
                            $shiftId = $newVal;
                            $start_time = $fetch['start'];
                        } else if ($newVal == 'OT') {
                            $Late = '';
                            $Overtime = '';
                            $Before = '';
                            $shiftId = $newVal;
                            $start_time = $fetch['start'];
                        } else {
                            $epl = explode('-', $newVal);
                            $begin = $epl[0];
                            $end = $epl[1];
                            $Late = $this->durationMinute($begin, $stamp_start);
                            $Overtime = $this->durationMinute($end, $stamp_stop);
                            $Before = $this->durationBefore($stamp_stop, $end);
                            if ($Before < 0) {
                                $Before = '';
                            }
                            $work_shift_start = $begin;
                            $shiftId = $work_shift_start;

                            if (strtotime($stamp_start) <= strtotime($work_shift_start)) {
                                //08:00 $work_shift_start
                                $start_time = date('Y-m-d ' . $work_shift_start, strtotime($fetch['start']));
                            } else {
                                //stamp_start
                                $start_time = $fetch['start'];
                            }
                        }
                        //get work_shift_id-------------------------------------
                        $sql = "SELECT work_shift_id FROM t_work_shift WHERE work_shift_start='$shiftId'";
                        $result8 = $this->mysqli->query($sql);
                        $row8 = $result8->fetch_assoc();
                        $work_shift_id = $row8['work_shift_id'];

                        $finish_time = $fetch['stop'];
                        $date = new DateTime($start_time);
                        $now = new DateTime($finish_time);
                        $hours = $date->diff($now)->format("%h:%i");
                        if ($newVal == 'OT') {
                            $hours = $this->hoursToMinutes($hours);
                        } else {
                            $hours = $this->hoursToMinutes($hours) - 60;
                        }
                        //update t_late-----------------------------------------

                        $sql = "SELECT stamp_id"
                                . " FROM t_late"
                                . " WHERE stamp_id='$this->stamp_id'";
                        $result = $this->mysqli->query($sql);
                        $num_rows = $result->num_rows;
                        $row = $result->fetch_assoc();
                        $late_id = uniqid();
                        if ($num_rows == 0) {
                            $sql = "INSERT INTO t_late"
                                    . "("
                                    . "late_id, stamp_id, stamp_shift,late,overtime,before_time,hours"
                                    . ") VALUES ("
                                    . "'$late_id','$this->stamp_id','$work_shift_id','$Late','$Overtime','$Before','$hours'"
                                    . ")";
                            $result = $this->mysqli->query($sql);
                        } else {

                            $sql = "UPDATE t_late SET"
                                    . " stamp_shift='$work_shift_id',"
                                    . "late='$Late',"
                                    . "overtime='$Overtime',"
                                    . "before_time='$Before',"
                                    . "hours='$hours'"
                                    . " WHERE stamp_id='$this->stamp_id'";
                            $result = $this->mysqli->query($sql);
                        }
                        //echo $sql;
                        //UPDATE t_late SET stamp_shift='55bf108f52d8a',late='',overtime='',before_time='960',hours='402' WHERE stamp_id='577e118cb36b4'
                    }
                }
            }
//start foreach-----------------------------------------------------
        }

        $json = array(
            "success" => true,
            "uid" => $this->userId
        );
        echo json_encode($json);
    }

    function real_escape($data) {
        return $this->mysqli->real_escape_string($data);
    }

    function hoursToMinutes($hours) {
        $minutes = 0;
        if (strpos($hours, ':') !== false) {
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours);
        }
        return $hours * 60 + $minutes;
    }

    function duration($begin, $end) {
        $remain = intval(strtotime($end) - strtotime($begin));
        //$wan = floor($remain / 86400);
        $l_wan = $remain % 86400;
        $hour = floor($l_wan / 3600);
        $l_hour = $l_wan % 3600;
        $minute = floor($l_hour / 60);
        //$hminute = ($hour * 60) + $minute;
        if ($hour < 0 || $minute < 0) {
            return '';
        } else {
            return $hour . ':' . $minute;
        }
    }

    function durationMinute($begin, $end) {
        $remain = intval(strtotime($end) - strtotime($begin));
//$wan = floor($remain / 86400);
        $l_wan = $remain % 86400;
        $hour = floor($l_wan / 3600);
        $l_hour = $l_wan % 3600;
        $minute = floor($l_hour / 60);
        $hminute = ($hour * 60) + $minute;
        if ($hminute < 0) {
            return '';
        } else {
            return $hminute;
        }
    }

    function durationBefore($begin, $end) {
        $remain = intval(strtotime($end) - strtotime($begin));
//$wan = floor($remain / 86400);
        $l_wan = $remain % 86400;
        $hour = floor($l_wan / 3600);
        $l_hour = $l_wan % 3600;
        $minute = floor($l_hour / 60);
        $hminute = ($hour * 60) + $minute;

        return $hminute;
    }

}

/*
 * 
 */

$PHPClass = new PHPClass($mysqli);
$PHPClass->update();


