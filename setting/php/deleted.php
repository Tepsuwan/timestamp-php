<?php

header('Content-Type: application/json');
include_once('../../libs/connect/connect.php');

class PHPClass {

    private $action = '';
    private $id_comman = '';
    private $mysqli = '';

    public function __construct($mysqli) {

        $this->action = $_POST['action'];
        $this->id_comman = json_decode($_POST['id']);
        $this->mysqli = $mysqli;
        
    }

    public function reuse() {

        foreach ($this->id_comman as $id) {

            $stamp_id = $id;
            $sql = "UPDATE t_stamp SET is_delete=0 WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            /*
             * update is_delete table detail
             */
            $sql = "UPDATE t_late SET is_delete=0 WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            $sql = "UPDATE t_overtime SET is_delete=0 WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            $sql = "UPDATE t_before_time SET is_delete=0 WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            /*
             * 
             */
        }
        $json['success'] = true;
        echo json_encode($json);
    }

    public function delete() {

        foreach ($this->id_comman as $id) {
            $stamp_id = $id;
            $sql = "DELETE FROM t_stamp WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            $sql = "DELETE FROM t_late WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            $sql = "DELETE FROM t_overtime WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            $sql = "DELETE FROM t_before_time WHERE stamp_id='$stamp_id'";
            $this->mysqli->query($sql);
            
        }
        $json['success'] = true;
        echo json_encode($json);
    }

    public function execute() {
        $retVal = false;
        switch ($this->action) {
            case "reuse":
                $retVal = $this->reuse();
                break;
            case "del":
                $retVal = $this->delete();
                break;
            default:
                break;
        }
        return $retVal;
    }

}

if (isset($_POST["action"]) && !empty($_POST["action"])) {
    $myPHPClass = new PHPClass($mysqli);
    $myPHPClass->execute();
}