<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $userId = "";
    private $fdate = "";
    private $tdate = "";
    private $monthChecked = "";
    private $todayChecked = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
        $this->mysqli = $mysqli;
        $this->monthChecked = $_GET['month'];
        $this->todayChecked = $_GET['today'];
        $this->fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['fdate'])));
        $this->tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['tdate'])));
    }

    function loadData() {


        $dateNow = date('Y-m-d');
        $date_to_show = date("d/m/Y", strtotime($dateNow));
        $sql = "SELECT a.stamp_id, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,"
                . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start,"
                . " a.stamp_start_ip,"
                . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                . " a.stamp_stop_ip, a.stamp_note,c.reason_name as reason_id"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8)=CONVERT(a.stamp_uid USING utf8)"
                . " LEFT JOIN bz_timestamp.t_reason c ON c.reason_id=a.reason_id"
                . " WHERE a.stamp_uid='$this->userId' AND a.is_delete=0"
                . " AND DATE_FORMAT(a.stamp_date,'%Y-%m-%d') ='$dateNow'"
                . " ORDER BY a.stamp_date DESC";

        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        $num_rows = $result->num_rows;
        if ($num_rows > 0) {
            $this->obj[] = array(
                "stamp_id" => $row['stamp_id'],
                "stamp_day" => date('D', strtotime($dateNow)),
                "stamp_date" => $date_to_show,
                "stamp_start" => $row['stamp_start'],
                "stamp_stop" => $row['stamp_stop'],
                "reason_id" => $row['reason_id'],
                "stamp_note" => $row['stamp_note']
            );
        }
//        $this->obj[] = array(
//            "stamp_id" => "",
//            "stamp_date" => "",
//        );
        /*
         * 
         */
        if ($this->monthChecked === 'true') {
            $startDate = date('Y-m-01');
            $endDate = date("Y-m-t");
        } else if ($this->todayChecked === 'true') {
            $startDate = date('Y-m-d');
            $endDate = date("Y-m-d");
        } else {
            $startDate = $this->fdate;
            $endDate = $this->tdate;
        }

        while (strtotime($startDate) <= strtotime($endDate)) {

            $date = date("Y-m-d", strtotime($startDate));
            $date_to_show = date("d/m/Y", strtotime($startDate));

            $sql = "SELECT a.stamp_id, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,"
                    . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start,"
                    . " a.stamp_start_ip,"
                    . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                    . " a.stamp_stop_ip, a.stamp_note,c.reason_name as reason_id"
                    . " FROM bz_timestamp.t_stamp a"
                    . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8)=CONVERT(a.stamp_uid USING utf8)"
                    . " LEFT JOIN bz_timestamp.t_reason c ON c.reason_id=a.reason_id"
                    . " WHERE a.stamp_uid='$this->userId' AND a.is_delete=0"
                    . " AND DATE_FORMAT(a.stamp_date,'%Y-%m-%d') ='$date'"
                    . " ORDER BY a.stamp_date DESC";

            $result = $this->mysqli->query($sql);
            $row = $result->fetch_assoc();
            $num_rows = $result->num_rows;
            if ($num_rows > 0) {
                $this->obj[] = array(
                    "stamp_id" => $row['stamp_id'],
                    "stamp_day" => date('D', strtotime($date)),
                    "stamp_date" => $date_to_show,
                    "stamp_start" => $row['stamp_start'],
                    "stamp_stop" => $row['stamp_stop'],
                    "reason_id" => $row['reason_id'],
                    "stamp_note" => $row['stamp_note']
                );
            } else {
                $this->obj[] = array(
                    "stamp_id" => uniqid(),
                    "stamp_day" => date('D', strtotime($date)),
                    "stamp_date" => $date_to_show
                );
            }

            $startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
        }
        echo "{\"data\":" . json_encode($this->obj) . "}";
    }

}

/*
 * get new class  
 */
$PHPClass = new PHPClass($mysqli);
$PHPClass->loadData();






