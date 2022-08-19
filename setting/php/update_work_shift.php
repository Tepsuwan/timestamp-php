<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $action = "";
    private $id_command = "";
    private $start = "";
    private $stop = "";
    private $mysqli = "";
    private $create_uid = "";
    private $create_date = "";
    private $update_uid = "";
    private $update_date = "";

    function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->action = (empty($_POST['action'])?'':$_POST['action']);
        $this->id_command = (empty($_POST['id']) ? '' : $_POST['id']);
        $this->start = $this->real_escape((empty($_POST['work_shift_start']) ? '' : $_POST['work_shift_start']));
        $this->stop = $this->real_escape((empty($_POST['work_shift_stop']) ? '' : $_POST['work_shift_stop']));
        $this->create_uid = $_SESSION['userId'];
        $this->create_date = date('Y-m-d H:i:s');
        $this->update_uid = $_SESSION['userId'];
        $this->update_date = date('Y-m-d H:i:s');
    }

    function insert() {

        $work_shift_id = uniqid();
        $sql = "INSERT INTO t_work_shift("
                . "work_shift_id, work_shift_start, work_shift_stop,"
                . " create_uid, create_date"
                . ") VALUES ("
                . "'$work_shift_id','$this->start','$this->stop','$this->create_uid','$this->create_date'"
                . ")";
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

    function update() {

        $sql = "UPDATE t_work_shift SET "
                . "work_shift_start='$this->start',work_shift_stop='$this->stop',"
                . "update_uid='$this->update_uid',update_date='$this->update_date'"
                . " WHERE work_shift_id='$this->id_command'";
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

    function delete() {
        $sql = "DELETE FROM t_work_shift"
                . " WHERE work_shift_id='$this->id_command'";
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

    function edit_to_text() {

        $sql = "SELECT work_shift_id, work_shift_start, work_shift_stop"
                . " FROM t_work_shift WHERE work_shift_id='$this->id_command'";
        $result = $this->mysqli->query($sql); //
        $obj = $result->fetch_object();
        $json = $obj;
        echo json_encode($json);
    }

    function real_escape($data) {
        return $this->mysqli->real_escape_string($data);
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
            default:
        }
    }

}

/*
 * get new class  
 */

$PHPClass = new PHPClass($mysqli);
$PHPClass->execute();
