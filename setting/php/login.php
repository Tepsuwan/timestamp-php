<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $action = "";
    private $username = "";
    private $password = "";
    private $mysqli = "";

    function __construct($mysqli) {
        $this->action = $_POST['action'];
        $this->username = (empty($_POST['username']) ? '' : $_POST['username']);
        $this->password = (empty($_POST['password']) ? '' : $_POST['password']);
        $this->mysqli = $mysqli;
    }

    function login() {

        $sql = "SELECT "
                . " p.id,p.NickName,p.Office,p.Email,u.role_key"
                . " FROM bz_timestamp.t_admin_user u "
                . " LEFT JOIN baezenic_people.t_people p ON u.uid=p.id"
                . " WHERE p.status<>'Y' AND  u.role_key =1"
                . " AND p.Email='" . $this->username . "' AND p.password='" . md5($this->password) . "'"
                . " ORDER BY p.id ASC";
        echo $sql;
        $result = $this->mysqli->query($sql);
        if ($result) {
            $num_rows = $result->num_rows;
            $row = $result->fetch_assoc();
            if ($num_rows != 0) {

                $_SESSION['userId'] = $row['id'];
                $_SESSION['userName'] = $row['NickName'];
                $_SESSION['role_key'] = $row['role_key'];

                $json = array(
                    "success" => true,
                    "status" => "login",
                    "role_key" => $row['role_key']
                );
            } else {
                $json = array(
                    "success" => false,
                    "status" => "login"
                );
            }
        } else {
            $json = array(
                "success" => false,
                "status" => "login"
            );
        }
        echo json_encode($json);
    }

    function logout() {

        unset($_SESSION['userId']);
        unset($_SESSION['userName']);
        unset($_SESSION['role_key']);
        $json = array(
            "success" => true,
            "status" => "logout"
        );
        echo json_encode($json);
    }

    function real_escape($data) {
        return $this->mysqli->real_escape_string($data);
    }

    function execute() {
        switch ($this->action) {
            case "login":
                $this->login();
                break;
            case "logout":
                $this->logout();
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
