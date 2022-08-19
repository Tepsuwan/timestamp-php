<?php

header('Content-Type: application/json');
include_once('../../libs/connect/connect.php');

class PHPClass {

    private $obj = array();
    private $team = "";
    private $shiftId = "";
    private $mysqli = '';

    public function __construct($mysqli) {

        $this->team = (empty($_GET['team']) ? '' : $_GET['team']);
        $this->shiftId = (empty($_GET['shiftId']) ? '' : $_GET['shiftId']);
        $this->mysqli = $mysqli;
    }

    public function select() {


        $sql = "SELECT "
                . " p.id,p.NickName as name"
                . " FROM baezenic_people.t_people p "
                . " LEFT JOIN bz_timestamp.t_employee_time u ON u.uid=p.id"
                . " WHERE p.status<>'Y' AND p.Team='$this->team' AND  p.Office<>'Vietnam' AND is_operator=1"
                . " ORDER BY p.id ASC";

        $result = $this->mysqli->query($sql);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $this->obj[] = $row;
            }
            echo json_encode($this->obj);
        }
    }

}

$myPHPClass = new PHPClass($mysqli);
$myPHPClass->select();
