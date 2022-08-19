<?php

session_start();
include_once('../../libs/connect/connect.php');

class PHPClass {

    private $obj = array();
    private $days = "";
    private $date = "";
    private $work_shift = "";
    private $id_command = "";
    private $action = "";
    private $uid = "";
    private $create_uid = "";
    private $create_date = "";
    private $update_uid = "";
    private $update_date = "";
    private $mysqli = '';

    public function __construct($mysqli) {

        $this->days = (empty($_POST['days']) ? '' : $_POST['days']);
        $this->date = (empty($_POST['txt_date']) ? '' : $_POST['txt_date']);
        $this->work_shift = (empty($_POST['work_shift_modal']) ? '' : $_POST['work_shift_modal']);
        $this->action = (empty($_POST['action']) ? '' : $_POST['action']);
        $this->id_command = (empty($_POST['id']) ? '' : $_POST['id']);
        $this->uid = (empty($_POST['uid']) ? '' : $_POST['uid']);
        $this->create_uid = $_SESSION['userId'];
        $this->create_date = date('Y-m-d H:i:s');
        $this->update_uid = $_SESSION['userId'];
        $this->update_date = date('Y-m-d H:i:s');
        $this->mysqli = $mysqli;
    }

    function insert() {

        if ($this->days === '') {

            $id = uniqid();
            $sql = "SELECT id FROM t_extra_dayshift WHERE (date='$this->date') and uid='$this->uid' and status=0";
            $result = $this->mysqli->query($sql);
            $num_row = $result->num_rows;
            if ($num_row === 0) {
                $days = date("l", strtotime($this->date));
                $sql = "INSERT INTO bz_timestamp.t_extra_dayshift (id, work_shift_id, days,date, uid, create_uid, create_date) VALUES ('$id', '$this->work_shift', '$days','$this->date', '$this->uid', '$update_uid', '$this->update_date')";

                $result = $this->mysqli->query($sql);
            } else {
                echo json_encode(array("success" => false, "msg_text" => "Have already"));
                return true;
            }
        } else {

            foreach ($this->days as $days) {
                $id = uniqid();
                $sql = "SELECT id FROM t_extra_dayshift WHERE (days='$days') and uid='$this->uid' and status=0";
                $result = $this->mysqli->query($sql);
                $num_row = $result->num_rows;
                if ($num_row === 0) {

                    $sql = "INSERT INTO bz_timestamp.t_extra_dayshift (id, work_shift_id, days, uid, create_uid, create_date) VALUES ('$id', '$this->work_shift', '$days', '$this->uid', '$update_uid', '$this->update_date')";

                    $result = $this->mysqli->query($sql);
                } else {
                    echo json_encode(array("success" => false, "msg_text" => "Have already"));
                    return true;
                }
            }
        }
        echo json_encode(array("success" => true, "msg_text" => "Successfully"));
    }

    function update() {

        foreach ($this->days as $days) {

            $sql = "UPDATE t_extra_dayshift SET "
                    . "uid='$this->uid',work_shift_id='$this->work_shift',"
                    . "days='$days',date='$this->date',"
                    . "update_uid='$this->update_uid',update_date='$this->update_date'"
                    . " WHERE id='$this->id_command'";
            $result = $this->mysqli->query($sql);
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
        $sql = "UPDATE t_extra_dayshift SET "
                . "status=1"
                . " WHERE id='$this->id_command'";
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

    function select() {

        $sql = "SELECT a.id,a.days,if(a.date='0000-00-00','',a.date) as date,concat(b.work_shift_start,\"-\",b.work_shift_stop) as work_shift "
                . "FROM t_extra_dayshift a "
                . "INNER JOIN t_work_shift b ON a.work_shift_id=b.work_shift_id "
                . "WHERE a.uid='$this->uid' and a.status=0";
        $result = $this->mysqli->query($sql);
        $arr = array();
        while ($obj = $result->fetch_object()) {
            $arr[] = $obj;
        }
        echo json_encode($arr);
    }

    function edit_to_text() {
        $sql = "SELECT a.id,a.days,if(a.date='0000-00-00','',a.date) as date,a.work_shift_id "
                . "FROM t_extra_dayshift a "
                . "WHERE a.id='$this->id_command' and a.status=0";
        $result = $this->mysqli->query($sql);
        $arr = array();
        while ($obj = $result->fetch_object()) {
            $arr[] = $obj;
        }
        echo json_encode($arr);
    }

    function execute() {
        switch ($this->action) {
            case "add":
                $this->insert();
                break;
            case "edit":
                $this->update();
                break;
            case "del":
                $this->delete();
                break;
            case "edit_to_text":
                $this->edit_to_text();
                break;
            case "select":
                $this->select();
                break;
            default:
        }
    }

}

$PHPClass = new PHPClass($mysqli);
$PHPClass->execute();
