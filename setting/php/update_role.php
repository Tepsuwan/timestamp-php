<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $action = "";
    private $id_command = "";
    private $role_name = "";
    private $role_discription = "";
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
        $this->role_name = $this->real_escape((empty($_POST['role_name']) ? '' : $_POST['role_name']));
        $this->role_discription = $this->real_escape((empty($_POST['role_discription']) ? '' : $_POST['role_discription']));
        $this->role_key = (empty($_POST['roleKey']) ? '' : $_POST['roleKey']);
        $this->create_uid = $_SESSION['userId'];
        $this->create_date = date('Y-m-d H:i:s');
        $this->update_uid = $_SESSION['userId'];
        $this->update_date = date('Y-m-d H:i:s');
    }

    function insert() {

        $role_id = uniqid();
        $sql = "INSERT INTO t_role("
                . "role_id, role_name, role_discription,role_key,"
                . " create_uid, create_date"
                . ") VALUES ("
                . "'$role_id','$this->role_name','$this->role_discription',$this->role_key,'$this->create_uid','$this->create_date'"
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

        $sql = "UPDATE t_role SET "
                . "role_name='$this->role_name',role_discription='$this->role_discription',role_key=$this->role_key,"
                . "update_uid='$this->update_uid',update_date='$this->update_date'"
                . " WHERE role_id='$this->id_command'";
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
        $sql = "DELETE FROM t_role"
                . " WHERE role_id='$this->id_command'";
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

    function key() {

        $sql = "SELECT role_id, role_key"
                . " FROM t_role ORDER BY role_key DESC";
        $result = $this->mysqli->query($sql) or die($this->mysqli->error); //
        $fetch = $result->fetch_assoc();
        if ($fetch['role_key'] == null) {
            $key = 1;
        } else {
            $key=$fetch['role_key']+1;
        }
        $json = $key;
        echo json_encode($json);
    }

    function edit_to_text() {

        $sql = "SELECT role_id, role_name, role_discription,role_key"
                . " FROM t_role WHERE role_id='$this->id_command'";
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
            case "key":
                $this->key();
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
