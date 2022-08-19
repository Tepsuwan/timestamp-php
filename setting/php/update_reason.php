<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $action = "";
    private $id_command = "";
    private $reason_name = "";
    private $reason_day = "";
    private $mysqli = "";
    private $create_uid = "";
    private $create_date = "";
    private $update_uid = "";
    private $update_date = "";

    function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->action = $_POST['action'];
        $this->id_command = $_POST['id'];
        $this->reason_name = $this->real_escape($_POST['reason_name']);
        $this->reason_day = $this->real_escape($_POST['reason_day']);
        $this->create_uid = $_SESSION['userId'];
        $this->create_date = date('Y-m-d H:i:s');
        $this->update_uid = $_SESSION['userId'];
        $this->update_date = date('Y-m-d H:i:s');
    }

    function insert() {

        $reason_id = uniqid();
        $sql = "INSERT INTO t_reason("
                . "reason_id, reason_name, reason_day,"
                . " create_uid, create_date"
                . ") VALUES ("
                . "'$reason_id','$this->reason_name','$this->reason_day','$this->create_uid','$this->create_date'"
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

        $sql = "UPDATE t_reason SET "
                . "reason_name='$this->reason_name',reason_day='$this->reason_day',"
                . "update_uid='$this->update_uid',update_date='$this->update_date'"
                . " WHERE reason_id='$this->id_command'";
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
        $sql = "DELETE FROM t_reason"
                . " WHERE reason_id='$this->id_command'";
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

        $sql = "SELECT reason_id, reason_name, reason_day"
                . " FROM t_reason WHERE reason_id='$this->id_command'";
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
