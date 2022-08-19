<?php

session_start();
include '../connect/connect.php';
include './logToFile.php';

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
                . " p.id,p.NickName,p.Office,p.Email,a.role_key,u.work_shift_id,p.Team"
                . " FROM baezenic_people.t_people p "
                . " LEFT JOIN bz_timestamp.t_employee_time u ON u.uid=p.id"
                . " LEFT JOIN bz_timestamp.t_admin_user a ON a.uid=p.id"
                . " WHERE p.status<>'Y' "
                //. " AND (p.NickName='" . $this->username . "' or TRIM(p.Email)='" . $this->username . "') AND p.password='" . md5($this->password) . "' and u.is_operator=1"
                . " AND  TRIM(p.Email)='" . $this->username . "' AND p.password='" . md5($this->password) . "' and u.is_operator=1"
                . " ORDER BY p.id ASC";
        $result = $this->mysqli->query($sql);
        if ($result) {
            $num_rows = $result->num_rows;
            $row = $result->fetch_assoc();
            if ($num_rows != 0) {

                $_SESSION['userId'] = $row['id'];
                $_SESSION['userName'] = $row['NickName'];
                $_SESSION['role_key'] = $row['role_key'];
                $_SESSION['work_shift_id'] = $row['work_shift_id'];
                $_SESSION['team'] = $row['Team'];

                $msg = $_SESSION['userName'] . ',' . $row['id'] . ',' . date('Y-m-d H:i:s') . ',' . gethostbyaddr($_SERVER['REMOTE_ADDR']);
                logToFile('../../logfile/logLogin.txt', $msg);

                $json = array(
                    "success" => true,
                    "status" => "login"
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
        unset($_SESSION['team']);
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
