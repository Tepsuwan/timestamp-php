<?php

session_start();
include_once('../connect/connect.php');

class PHPClass {

    private $stamp_id = "";
    private $updateId = '';
    private $dateId = '';
    private $userId = '';
    private $data = '';
    private $mysqli = "";

    function __construct($mysqli) {

        $this->mysqli = $mysqli;
        $this->userId = $_SESSION['userId'];
        $this->data = json_decode($_POST['data']);
        $this->updateId = json_decode($_POST['updateId']);
        $this->dateId = json_decode($_POST['dateId']);
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
                $date = date('Y-m-d', strtotime(str_replace('/', '-', $this->dateId[$int])));
                $int++;

                if ($colId == "reason_id") {
                    $sql = "SELECT reason_id "
                            . " FROM t_reason"
                            . " WHERE reason_name='$newVal'";
                    $result = $this->mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    $newVal = $row['reason_id'];
                }

                $newVal = $this->real_escape($newVal);
                $sql = " UPDATE t_stamp SET ";
                if ($colId == 'stamp_start' || $colId == 'stamp_stop') {
                    if ($newVal == "") {
                        $newVal = '';
                    } else {
                        $newValNodate = $newVal;
                        $newVal = $date . ' ' . $newVal;
                    }
                    $sql.= "$colId='$newVal'";
                } else {
                    $sql.= "$colId='$newVal'";
                }
                $sql .= ",update_user='$this->userId'"
                        . ",update_date='" . date('Y-m-d H:i:s') . "'"
                        . " WHERE stamp_id='$this->stamp_id' ";
                $this->mysqli->query($sql);
            }

            //------------------------------------------------------------------
            /*
             * UPdate ---------------------------------------------------------
             */
            if ($colId == 'stamp_start' || $colId == 'stamp_stop') {
                //if t_late have data get new work_shift_id-------------------------
                $sql = "SELECT b.work_shift_start,b.work_shift_stop,b.work_shift_id"
                        . " FROM t_late a"
                        . " LEFT JOIN t_work_shift b ON b.work_shift_id=a.stamp_shift "
                        . " WHERE a.stamp_id='$this->stamp_id'";
                $result = $this->mysqli->query($sql);
                $fetch2 = $result->fetch_assoc();
                $num_rows = $result->num_rows;
                $late_start = "";
                $late_stop = "";
                $late_work_shift_id = "";
                if ($num_rows > 0) {
                    $late_work_shift_id = $fetch2['work_shift_id'];
                }
                ////////////////////////////////////////////////////////////////////////
                $sql = "SELECT a.stamp_date,DATE_FORMAT(a.stamp_start,'%Y-%m-%d %H:%i:%s') as start,"
                        . " DATE_FORMAT(a.stamp_stop,'%Y-%m-%d %H:%i:%s') as stop,b.work_shift_start,b.work_shift_stop,b.work_shift_id"
                        . " FROM t_stamp a"
                        . " LEFT JOIN t_work_shift b ON b.work_shift_id=a.work_shift_id "
                        . " WHERE a.stamp_id='$this->stamp_id'";

                $result = $this->mysqli->query($sql);
                $fetch = $result->fetch_assoc();
                $stamp_date = $fetch['stamp_date'];


                if ($colId == 'stamp_start') {
                    if ($newVal == "") {
                        $newVal = '';
                    } else {
                        $stamp_start_date = $date . ' ' . $newValNodate;
                        $stamp_start = $newVal;
                    }
                } else {
                    $stamp_start_date = $fetch['start'];
                    $stamp_start = date('H:i', strtotime($fetch['start']));
                }
                if ($colId == 'stamp_stop') {
                    if ($newVal == "") {
                        $newVal = '';
                    } else {
                        $stamp_stop_date = $date . ' ' . $newValNodate;
                        $stamp_stop = $newVal;
                    }
                } else {
                    $stamp_stop_date = $fetch['stop'];
                    $stamp_stop = date('H:i', strtotime($fetch['stop']));
                }

                if ($late_work_shift_id == "") {
                    $work_shift_start = $fetch['work_shift_start'];
                    $work_shift_stop = $fetch['work_shift_stop'];
                    $work_shift_id = $fetch['work_shift_id'];
                } else {
                    $work_shift_start = $fetch2['work_shift_start'];
                    $work_shift_stop = $fetch2['work_shift_stop'];
                    $work_shift_id = $fetch2['work_shift_id'];
                }

                if (strtotime($stamp_start) <= strtotime($work_shift_start)) {
                    //08:00 $work_shift_start
                    $start_time = date('Y-m-d ' . $work_shift_start, strtotime($stamp_start_date));
                } else {
                    //stamp_start
                    $start_time = $stamp_start_date; //$fetch['start'];
                }

                /*
                 * //get late ot-------------------------------------------------------
                 */
                $finish_time = $stamp_stop_date;
                $date = new DateTime($start_time);
                $now = new DateTime($finish_time);
                $hours = $date->diff($now)->format("%h:%i");
                $hours = $this->hoursToMinutes($hours) - 60;

                $Late = $this->durationMinute($work_shift_start, $stamp_start);
                $Overtime = $this->durationMinute($work_shift_stop, $stamp_stop);
                $Before = $this->durationBefore($stamp_stop, $work_shift_stop);
                if ($Before < 0) {
                    $Before = '';
                }
                if ($work_shift_start == 'none') {
                    $Late = '';
                    $Overtime = '';
                    $Before = '';
                }
                /*
                 * update t_late--------------------------------------------------------
                 */
                $sql = "SELECT stamp_id"
                        . " FROM t_late"
                        . " WHERE stamp_id='$this->stamp_id'";
                $result = $this->mysqli->query($sql);
                $num_rows = $result->num_rows;
                $late_id = uniqid();
                if ($num_rows == 0) {
                    //if ($Late > 0) {
                    $sql = "INSERT INTO t_late"
                            . "("
                            . "late_id, stamp_id,stamp_uid,stamp_date, stamp_shift,late,overtime,before_time,hours"
                            . ") VALUES ("
                            . "'$late_id','$this->stamp_id',"
                            . "'$this->userId','$stamp_date',"
                            . "'$work_shift_id','$Late',"
                            . "'$Overtime','$Before','$hours'"
                            . ")";
                    $this->mysqli->query($sql);
                    //}
                } else {
                    $sql = "UPDATE t_late SET"
                            . " stamp_shift='$work_shift_id',"
                            . "late='$Late',"
                            . "overtime='$Overtime',"
                            . "before_time='$Before',"
                            . "hours='$hours'"
                            . " WHERE stamp_id='$this->stamp_id'";
                    $this->mysqli->query($sql);
                   
                }
            }

            //start foreach-----------------------------------------------------
        }

        $json = array(
            "success" => true
        );
        echo json_encode($json);
    }

    function real_escape($data) {
        return $this->mysqli->real_escape_string($data);
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

    // Transform hours like "1:45" into the total number of minutes, "105". 
    function hoursToMinutes($hours) {
        $minutes = 0;
        if (strpos($hours, ':') !== false) {
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours);
        }
        return $hours * 60 + $minutes;
    }

}

/*
 * 
 */

$PHPClass = new PHPClass($mysqli);
$PHPClass->update();


