<?php

session_start();
require_once '../connect/connect.php';
require_once ('../PHPDatabase/MysqliDb.php');
$db = new MysqliDb($mysqli);

class PHPClass {

    private $action = "";
    private $id_command = "";
    private $workshiftId = "";
    private $datenow = "";
    private $timenow = "";
    private $ip = "";
    private $userId = "";
    private $mysqli = "";

    function __construct($mysqli) {

        $this->action = $_POST['action'];
        $this->id_command = $_POST['id'];
        $this->workshiftId = $_POST['workshift'];
        $this->datenow = date('Y-m-d');
        $this->timenow = date('Y-m-d H:i:s');
        $this->ip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $this->userId = $_SESSION['userId'];
        $this->mysqli = $mysqli;
    }

    function start() {


        $sql = "SELECT a.work_shift_id,a.team"
                . " FROM bz_timestamp.t_calendar a"
                . " LEFT JOIN bz_timestamp.t_employee_time b ON b.work_shift_id=a.work_shift_id"
                . " WHERE a.uid='$this->userId'"
                . " AND DATE_FORMAT(a.calendar_date_start,'%Y-%m-%d')<='$this->datenow' "
                . " AND DATE_FORMAT(a.calendar_date_end,'%Y-%m-%d')>='$this->datenow' ";
        $result = $this->mysqli->query($sql);
        $num_rows = $result->num_rows;
        $note = "";
        $team = "";
        if ($num_rows > 0) {
            $fetch = $result->fetch_assoc();
            $this->workshiftId = $fetch['work_shift_id'];
            $team = $fetch['team'];
        }
        if ($team == 'PE') {
            $note = '#Day shift';
        }

        $stamp_id = $this->id_command;
        $sql = "INSERT INTO t_stamp("
                . "stamp_id, stamp_uid,work_shift_id, stamp_date, stamp_start,stamp_note,"
                . "stamp_start_ip, create_user, create_date"
                . ") VALUES ("
                . "'$stamp_id','$this->userId','$this->workshiftId','$this->datenow','$this->timenow','$note',"
                . "'$this->ip','$this->userId','" . date('Y-m-d H:i:s') . "'"
                . ")";
        $result2 = $this->mysqli->query($sql) or die('error start' . $this->mysqli->error);

        if ($result2) {
            $json = array("success" => true);
            echo json_encode($json);
        }
    }

    function stop() {


        $sql = "SELECT a.work_shift_id,a.team"
                . " FROM bz_timestamp.t_calendar a"
                . " LEFT JOIN bz_timestamp.t_employee_time b ON b.work_shift_id=a.work_shift_id"
                . " WHERE a.uid='$this->userId'"
                . " AND DATE_FORMAT(a.calendar_date_start,'%Y-%m-%d')<='$this->datenow' "
                . " AND DATE_FORMAT(a.calendar_date_end,'%Y-%m-%d')>='$this->datenow' ";
        $result = $this->mysqli->query($sql);
        $num_rows = $result->num_rows;
        if ($num_rows > 0) {
            $fetch = $result->fetch_assoc();
            $this->workshiftId = $fetch['work_shift_id'];
        }

        $sql = "UPDATE t_stamp SET "
                . "stamp_stop='$this->timenow',"
                . "stamp_stop_ip='$this->ip',"
                . "work_shift_id='$this->workshiftId',"
                . "update_user='$this->userId',"
                . "update_date='" . date('Y-m-d H:i:s') . "'"
                . " WHERE stamp_id='$this->id_command'";
        $result = $this->mysqli->query($sql) or die('error stop' . $this->mysqli->error);

        /*
         * UPdate ---------------------------------------------------------
         */
        //if t_late have data get new work_shift_id-----------------------------
        $sql = "SELECT b.work_shift_start,b.work_shift_stop,b.work_shift_id"
                . " FROM t_late a"
                . " LEFT JOIN t_work_shift b ON b.work_shift_id=a.stamp_shift "
                . " WHERE a.stamp_id='$this->id_command'";
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
                . " WHERE a.stamp_id='$this->id_command'";

        $result = $this->mysqli->query($sql);
        $fetch = $result->fetch_assoc();
        $stamp_date = $fetch['stamp_date'];
        $stamp_start = date('H:i', strtotime($fetch['start']));
        $stamp_stop = date('H:i', strtotime($fetch['stop']));


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
            $start_time = date('Y-m-d ' . $work_shift_start, strtotime($fetch['start']));
        } else {
            //stamp_start
            $start_time = $fetch['start'];
        }
        $finish_time = $this->timenow;
        $date = new DateTime($start_time);
        $now = new DateTime($finish_time);
        $hours = $date->diff($now)->format("%h:%i");
        $hours = $this->hoursToMinutes($hours) - 60;
        /*
         * //get late ot-------------------------------------------------------
         */
        if ($work_shift_start == 'none') {
            $json = array("success" => true);
            echo json_encode($json);
            exit();
        }

        $Late = $this->durationMinute($work_shift_start, $stamp_start);
        $Overtime = $this->durationMinute($work_shift_stop, $stamp_stop);
        $Before = $this->durationBefore($stamp_stop, $work_shift_stop);
        if ($Before < 0) {
            $Before = '';
        }
        /*
         * update t_late--------------------------------------------------------
         */
        $sql = "SELECT stamp_id"
                . " FROM t_late"
                . " WHERE stamp_id='$this->id_command'";
        $result = $this->mysqli->query($sql);
        $num_rows = $result->num_rows;
        $late_id = uniqid();
        if ($num_rows == 0) {
            //if ($Late > 0) {
            $sql = "INSERT INTO t_late"
                    . "("
                    . "late_id, stamp_id,stamp_uid,stamp_date, stamp_shift,late,overtime,before_time,hours"
                    . ") VALUES ("
                    . "'$late_id','$this->id_command',"
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
                    . " WHERE stamp_id='$this->id_command'";
            $this->mysqli->query($sql);
        }






        $json = array("success" => true);
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

    function execute() {
        switch ($this->action) {
            case "start":
                $this->start();
                break;
            case "stop":
                $this->stop();
                break;
            default:
        }
    }

}

/*
 * get new class  
 */
$PHPClass = new PHPClass($mysqli);
$PHPClass->execute();
