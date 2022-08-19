<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $action = "";
    private $id_command = "";
    private $uid = "";
    private $shiftId = "";
    private $nextdayId = "";
    private $start = "";
    private $end = "";
    private $bgColor = "";
    private $borderColor = "";
    private $team = "";
    private $mysqli = "";
    private $create_uid = "";
    private $create_date = "";
    private $update_uid = "";
    private $update_date = "";

    function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->action = $_POST['action'];
        $this->id_command = $_POST['event_id'];
        $this->uid = (empty($_POST['uid']) ? '' : $_POST['uid']);
        $this->shiftId = (empty($_POST['shiftId']) ? '' : $_POST['shiftId']);
        $this->nextdayId = (empty($_POST['nextdayId']) ? '' : $_POST['nextdayId']);
        $this->start = (empty($_POST['start']) ? '' : $_POST['start']);
        $this->end = (empty($_POST['end']) ? '' : $_POST['end']);
        $this->bgColor = (empty($_POST['backgroundColor']) ? '' : $_POST['backgroundColor']);
        $this->borderColor = (empty($_POST['borderColor']) ? '' : $_POST['borderColor']);
        $this->team = (empty($_POST['team']) ? '' : $_POST['team']);
        $this->create_uid = $_SESSION['userId'];
        $this->create_date = date('Y-m-d H:i:s');
        $this->update_uid = $_SESSION['userId'];
        $this->update_date = date('Y-m-d H:i:s');
    }

    function insert() {

        $calendar_id = uniqid();

        $sql = "INSERT INTO t_calendar SET "
                . "calendar_id='$calendar_id',"
                . "work_shift_id='$this->shiftId',"
                . "uid='$this->uid',"
                . "calendar_date_start='$this->start',"
                . "calendar_date_end='$this->end', "
                . "calendar_bg_color='$this->bgColor',"
                . "calendar_border_color='$this->borderColor',"
                . "team='$this->team',"
                . "create_uid='$this->create_uid',"
                . "create_date='$this->create_date'";
        $result = $this->mysqli->query($sql);


        if ($this->team == "PE") {

            $tomorrow = date('Y-m-d', strtotime($this->start . "+1 days"));
            $friday = date('l', strtotime($this->start));
            if ($friday == "Friday") {
                $tomorrow = date('Y-m-d', strtotime($this->start . "+3 days"));
            }
            //55bf1082af74b=7.00-16.00|56049c8b4cc0a=13.00-22.00|562dd71e18365=14.00-23.00
            //if ($this->shiftId == "55bf1082af74b" || $this->shiftId == "56049c8b4cc0a" || $this->shiftId == "562dd71e18365") {
//                if ($this->shiftId == "55bf1082af74b") {
//                    $shiftId = "55c94bea93fbe"; //9.00-16.00
//                } else if ($this->shiftId == "56049c8b4cc0a") {
//                    $shiftId = "560ceb1539095"; //8.00-16.00
//                } else if ($this->shiftId == "562dd71e18365") {
//                    $shiftId = "55c94bea93fbe"; //9.00-16.00
//                }

            $calendar_id2 = uniqid();
            $sql = "INSERT INTO t_calendar SET "
                    . "calendar_id='$calendar_id2',"
                    . "calendar_mate_id='$calendar_id',"
                    . "work_shift_id='$this->nextdayId',"//9:00-16:00
                    . "uid='$this->uid',"
                    . "calendar_date_start='$tomorrow',"
                    . "calendar_date_end='$tomorrow', "
                    . "calendar_bg_color='rgb(255, 133, 27)',"
                    . "calendar_border_color='$this->borderColor',"
                    . "team='$this->team',"
                    . "create_uid='$this->create_uid',"
                    . "create_date='$this->create_date'";
            $result = $this->mysqli->query($sql);
        }
        //}

        if ($result) {
            $json = array(
                "success" => true,
                "event_id" => $calendar_id
            );
        } else {
            $json = array(
                "success" => false
            );
        }
        echo json_encode($json);
    }

    function update() {

        $sql = "UPDATE t_calendar SET "
                . "calendar_date_start='$this->start',"
                . "calendar_date_end='$this->end', "
                . "update_uid='$this->update_uid',"
                . "update_date='$this->update_date'"
                . " WHERE calendar_id='$this->id_command'";
        $result = $this->mysqli->query($sql);

        if ($this->team == "PE") {
            $sql = "SELECT calendar_id, calendar_mate_id, work_shift_id"
                    . " FROM t_calendar WHERE calendar_id='$this->id_command'";
            $query = $this->mysqli->query($sql);
            $fetch = $query->fetch_assoc();
            $calendar_mate_id = $fetch['calendar_mate_id'];
            if ($calendar_mate_id != '') {
                $tomorrow = date('Y-m-d', strtotime($this->start . "-1 days"));
                $friday = date('l', strtotime($this->start));
                if ($friday == "Monday") {
                    $tomorrow = date('Y-m-d', strtotime($this->start . "-3 days"));
                }
                $sql = "UPDATE t_calendar SET "
                        . "calendar_date_start='$tomorrow',"
                        . "calendar_date_end='$tomorrow', "
                        . "update_uid='$this->update_uid',"
                        . "update_date='$this->update_date'"
                        . " WHERE calendar_id='$calendar_mate_id'";
                $result = $this->mysqli->query($sql);
            } else {

                $tomorrow = date('Y-m-d', strtotime($this->start . "+1 days"));
                $friday = date('l', strtotime($this->start));
                if ($friday == "Friday") {
                    $tomorrow = date('Y-m-d', strtotime($this->start . "+3 days"));
                }
                $sql = "UPDATE t_calendar SET "
                        . "calendar_date_start='$tomorrow',"
                        . "calendar_date_end='$tomorrow', "
                        . "update_uid='$this->update_uid',"
                        . "update_date='$this->update_date'"
                        . " WHERE calendar_mate_id='$this->id_command'";

                $result = $this->mysqli->query($sql);
            }
        }

        if ($result) {
            $json = array(
                "success" => true
            );
        } else {
            $json = array(
                "success" => false
            );
        }
        echo json_encode($json);
    }

    function delete() {



        $sql = "SELECT calendar_id, calendar_mate_id, work_shift_id"
                . " FROM t_calendar WHERE calendar_id='$this->id_command'";
        $query = $this->mysqli->query($sql);
        $fetch = $query->fetch_assoc();
        $calendar_mate_id = $fetch['calendar_mate_id'];
        if ($calendar_mate_id != '') {
            $sql = "DELETE FROM t_calendar"
                    . " WHERE calendar_id='$calendar_mate_id'";
            $result = $this->mysqli->query($sql);
        } else {
            $sql = "DELETE FROM t_calendar"
                    . " WHERE calendar_mate_id='$this->id_command'";
            $result = $this->mysqli->query($sql);
        }

        $sql = "DELETE FROM t_calendar"
                . " WHERE calendar_id='$this->id_command'";
        $result = $this->mysqli->query($sql);



        if ($result) {
            $json = array(
                "success" => true
            );
        } else {
            $json = array(
                "success" => false
            );
        }
        echo json_encode($json);
    }

    function real_escape($data) {
        return $this->mysqli->real_escape_string($data);
    }

    function execute() {
        switch ($this->action) {
            case "new":
                $this->insert();
                break;
            case "resetdate":
                $this->update();
                break;
            case "delete":
                $this->delete();
                break;
            default:
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
 * get new class  
 */

$PHPClass = new PHPClass($mysqli);
$PHPClass->execute();
