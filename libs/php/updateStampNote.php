<?php
error_reporting(-1);
ini_set('display_errors', 'On');

session_start();
include_once('../connect/connect.php');

class PHPClass {

    private $data = "";
    private $id_command = "";
    private $updateId = "";
    private $ip = "";
    private $dateId = "";
    private $userId = "";
    private $mysqli = "";

    function __construct($mysqli) {

        $this->mysqli = $mysqli;
        $this->updateId = json_decode($_POST['updateId']);
        $this->dateId = json_decode($_POST['dateId']);
        $this->data = json_decode($_POST['data']);
        $this->ip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $this->userId = $_SESSION["userId"];
    }

    function update() {

        $int = 0;
        if (isset($this->data) && $this->data) {
            //start foreach-----------------------------------------------------
            foreach ($this->data as $change) {

                $rowId = $change[0];
                $colId = $change[1];
                $oldVal = $change[2];
                $newVal = $change[3];
                //--------------------------------------------------------------
                $this->id_command = $this->updateId[$int];
                $date = date('Y-m-d', strtotime(str_replace('/', '-', $this->dateId[$int])));
                $int++;

                if ($colId == "reason_id") {
                    $sql = "SELECT reason_id FROM t_reason WHERE reason_name='$newVal'";
                    $result = $this->mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    $newVal = $row['reason_id'];
                   
                    if ($newVal) {
                        $reason_count = $this->reason_count($this->userId, $newVal, date("Y"));

                        if ($reason_count["more_than"] == "YES") {

                            $reason_detail = $this->reason_day($this->userId, $newVal, date("Y"));

                            //var_dump($reason_detail);
                            $json = array(
                                "success" => FALSE,
                                "reason" => "YES",
                                "reason_day" => $reason_count["reason_day"],
                                "reason_name" => $reason_count["reason_name"],
                                "reason_detail" => $reason_detail
                            );
                            echo json_encode($json);
                            return;
                        }
                    }
                }

                $newVal = $this->real_escape($newVal);

                $sql = "SELECT stamp_id FROM t_stamp WHERE stamp_id='$this->id_command'";
                $result = $this->mysqli->query($sql);
                $num_rows = $result->num_rows;

                if ($num_rows == "0") {

                    $sql = " INSERT t_stamp SET ";
                    if ($colId == 'stamp_start' || $colId == 'stamp_stop') {
                        $newVal = $date . ' ' . $newVal;
                        $sql.= "$colId='$newVal'";
                    } else {
                        $sql.= "$colId='$newVal'";
                    }
                    $sql.= ",stamp_date='$date',stamp_id='$this->id_command',stamp_uid='$this->userId'";
                    if ($colId == 'reason_id') {
                        $sql.= ",stamp_start_ip='$this->ip'"
                                . ",stamp_stop_ip='$this->ip'";
                    }
                    $sql . ",stamp_date='$date',create_user='$this->userId',create_date='" . date('Y-m-d H:i:s') . "'";
                    $setDefault = "SET SESSION sql_mode = ''";
                    $this->mysqli->query($setDefault);
                    $this->mysqli->query($sql);
					// echo 'TEST_UPDaTE_COMMENT_case true : '.$setDefault.' '.$sql;
                    /*
                     * 
                     */
                } else {

                    $sql = " UPDATE t_stamp SET ";
                    if ($colId == 'stamp_start' || $colId == 'stamp_stop') {
                        if ($newVal == "") {
                            $newVal = '';
                        } else {
                            $newVal = $date . ' ' . $newVal;
                        }
                        $sql.= "$colId='$newVal'";
                    } else {
                        $sql.= "$colId='$newVal'";
                    }
                    if ($colId == 'reason_id') {
                        $sql.= ",stamp_start_ip='$this->ip',stamp_stop_ip='$this->ip'";
                    }
                    $sql .= ",update_user='$this->userId',update_date='" . date('Y-m-d H:i:s') . "'"
                            . " WHERE stamp_id='$this->id_command' ";
                    $this->mysqli->query($sql);

                    echo 'TEST_UPDaTE_COMMENT_case else : '.$sql;

                    /*
                     * 
                     */
                }
            }
        }
        echo json_encode($json = array("success" => true));
    }

    function real_escape($data) {
        return $this->mysqli->real_escape_string($data);
    }

    function reason_count($uid, $reason_id, $year) {

        $sql = "SELECT reason_day,reason_name FROM t_reason WHERE reason_id='$reason_id'";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $reason_day = $row['reason_day'];
        $reason_name = $row['reason_name'];

        $sql = "SELECT count(reason_id) as reason FROM `t_stamp` WHERE stamp_uid='$uid' and reason_id='$reason_id' and year(stamp_date)='$year' and is_delete=0";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $reason = $row['reason'];
    
        if ($reason >= $reason_day) {
            $obj = array(
                "more_than" => "YES",
                "reason_day" => $reason_day,
                "reason_name" => $reason_name,
                "reason" => $reason,
            );
            return $obj;
        } else {
            $obj = array(
                "more_than" => "NO",
                "reason_day" => $reason_day,
                "reason_name" => $reason_name,
                "reason" => $reason,
            );
            return $obj;
        }
    }

    function reason_day($uid, $reason_id, $year) {


        $sql = "SELECT a.reason_id,b.reason_name,a.stamp_note,a.stamp_date FROM t_stamp a LEFT JOIN t_reason b ON a.reason_id=b.reason_id  WHERE a.stamp_uid='$uid' and a.reason_id='$reason_id' and year(a.stamp_date)='$year' and a.is_delete=0";

        $result = $this->mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $obj[] = array(
                "reason_id" => $row["reason_id"],
                "reason_name" => $row["reason_name"],
                "stamp_note" => $row["stamp_note"],
                "stamp_date" => $row["stamp_date"]
            );
        }
        return $obj;
    }

}

$PHPClass = new PHPClass($mysqli);
$PHPClass->update();


