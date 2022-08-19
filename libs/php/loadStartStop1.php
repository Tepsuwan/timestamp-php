<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $userId = "";
    private $fdate = "";
    private $tdate = "";
    private $checked = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
        $this->mysqli = $mysqli;
        $this->checked = $_GET['checked'];
        $this->fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['fdate'])));
        $this->tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['tdate'])));
    }

    function loadData() {

        if ($this->checked === 'true') {
            $startDate = date('Y-m-01');
            $endDate = date("Y-m-t");
        } else {
            $startDate = $this->fdate;
            $endDate = $this->tdate;
        }
        while (strtotime($startDate) <= strtotime($endDate)) {


            $date = date("Y-m-d", strtotime($startDate));
            $date_to_show = date("d/m/Y", strtotime($startDate));

            $sql = "SELECT a.staff_work_id, DATE_FORMAT(a.staff_work_date,'%d/%m/%Y') as staff_work_date,"
                    . " if(a.staff_work_start='0000-00-00 00:00:00','',DATE_FORMAT(a.staff_work_start,'%H:%i:%s')) as staff_work_start,"
                    . " a.staff_work_start_ip,"
                    . " if(a.staff_work_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.staff_work_stop,'%H:%i:%s')) as staff_work_stop,"
                    . " a.staff_work_stop_ip, a.staff_work_note,c.reason_name as reason_id"
                    . " FROM bz_staff.t_staff_work a"
                    . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8)=CONVERT(a.staff_work_uid USING utf8)"
                    . " LEFT JOIN bz_staff.t_reason c ON c.reason_id=a.reason_id"
                    . " WHERE a.staff_work_uid='$this->userId' AND a.is_delete=0"
                    . " AND DATE_FORMAT(a.staff_work_date,'%Y-%m-%d') ='$date'"
                    . " ORDER BY a.staff_work_date DESC";
            
            $result = $this->mysqli->query($sql);
            $row = $result->fetch_assoc();
            $num_rows = $result->num_rows;
            if ($num_rows > 0) {
                $this->obj[] = array(
                    "staff_work_id" => $row['staff_work_id'],
                    "staff_work_day" => date('D', strtotime($date)),
                    "staff_work_date" => $date_to_show,
                    "staff_work_start" => $row['staff_work_start'],
                    "staff_work_stop" => $row['staff_work_stop'],
                    "reason_id" => $row['reason_id'],
                    "staff_work_note" => $row['staff_work_note']
                );
            } else {
                $this->obj[] = array(
                    "staff_work_id" => uniqid(),
                    "staff_work_day" => date('D', strtotime($date)),
                    "staff_work_date" => $date_to_show
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






