<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $fdate = "";
    private $tdate = "";
    private $checked = "";
    private $team = "";
    private $staffId = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {


        $this->mysqli = $mysqli;
        $this->checked = $_GET['checked'];
        $this->team = $_GET['team'];
        $this->staffId = $_GET['staff'];
        $this->fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['fdate'])));
        $this->tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['tdate'])));
    }

    function loadData() {

        $fd = date("d/m/Y", strtotime($this->fdate));
        $td = date("d/m/Y", strtotime($this->tdate));

        $sql = "SELECT DATE_FORMAT(a.stamp_date,'%Y-%m-%d') as date, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
                . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
                . " LEFT JOIN bz_timestamp.t_overtime d ON d.stamp_id = a.stamp_id"
                . " LEFT JOIN bz_timestamp.t_before_time e ON e.stamp_id = a.stamp_id"
                . " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
                . " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
                . " WHERE a.is_delete = 0";
        if (!empty($this->team)) {
            $sql.= " AND b.Team = '$this->team'";
        }
        if (!empty($this->staffId)) {
            $sql.= " AND b.id = '$this->staffId'";
        }
        if ($this->checked === 'true') {
            $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m') = '" . date('Y-m') . "'";
        } else {
            $sql .= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') BETWEEN '$this->fdate' AND '$this->tdate'";
        }
        $sql.= "GROUP BY a.stamp_date ORDER BY a.stamp_date ASC";

        $resultG = $this->mysqli->query($sql) or die($this->mysqli->error);
        while ($row = $resultG->fetch_assoc()) {
            $date = $row['date'];
            $this->obj[] = array("gdate" => $row['stamp_date']);
            $sql = "SELECT a.stamp_id, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,"
                    . " concat(b.Name ,' (',b.NickName,')') as stamp_uid,"
                    . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start,"
                    . " a.stamp_start_ip,"
                    . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                    . " a.stamp_stop_ip,a.stamp_note,"
                    . " if(c.stamp_shift='',concat(g.work_shift_start,'-',g.work_shift_stop),c.stamp_shift) as work_shift_id,"
                    . " f.reason_name as reason_id"
                    . " FROM bz_timestamp.t_stamp a"
                    . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
                    . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
                    . " LEFT JOIN bz_timestamp.t_overtime d ON d.stamp_id = a.stamp_id"
                    . " LEFT JOIN bz_timestamp.t_before_time e ON e.stamp_id = a.stamp_id"
                    . " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
                    . " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
                    . " WHERE a.is_delete = 0";
            if (!empty($this->team)) {
                $sql.= " AND b.Team = '$this->team'";
            }
            if (!empty($this->staffId)) {
                $sql.= " AND b.id = '$this->staffId'";
            }
            $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') = '$date'";
            $sql.= " ORDER BY b.Name ASC";            
            $result = $this->mysqli->query($sql) or die($this->mysqli->error);
            while ($obj = $result->fetch_object()) {
                $this->obj[] = $obj;
            }
        }
        echo "{\"data\":" . json_encode($this->obj) . "}";
    }

}

/*
 * get new class  
 */
$PHPClass = new PHPClass($mysqli);
$PHPClass->loadData();
