<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $action = "";
    private $id_command = "";
    private $uid = "";
    private $role_key = "";
    private $mysqli = "";
    private $create_uid = "";
    private $create_date = "";
    private $update_uid = "";
    private $update_date = "";

    function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->action = $_POST['action'];
        $this->id_command = (empty($_POST['id']) ? '' : $_POST['id']);
        $this->uid = (empty($_POST['staff']) ? '' : $_POST['staff']);
        $this->role_key = (empty($_POST['role_key']) ? '' : $_POST['role_key']);
        $this->create_uid = $_SESSION['userId'];
        $this->create_date = date('Y-m-d H:i:s');
        $this->update_uid = $_SESSION['userId'];
        $this->update_date = date('Y-m-d H:i:s');
    }

    function insert() {

        $admin_user_id = uniqid();
        $sql = "INSERT INTO t_admin_user("
                . "admin_user_id, uid,role_key,"
                . " create_uid, create_date"
                . ") VALUES ("
                . "'$admin_user_id','$this->uid',$this->role_key,'$this->create_uid','$this->create_date'"
                . ")";
        $result = $this->mysqli->query($sql) or die($this->mysqli->error);
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

        $sql = "UPDATE t_admin_user SET "
                . "uid='$this->uid',role_key=$this->role_key,"
                . "update_uid='$this->update_uid',update_date='$this->update_date'"
                . " WHERE admin_user_id='$this->id_command'";
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
        $sql = "DELETE FROM t_admin_user"
                . " WHERE admin_user_id='$this->id_command'";
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

        $sql = "SELECT admin_user_id, uid, role_key"
                . " FROM t_admin_user WHERE admin_user_id='$this->id_command'";
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
