<?php

session_start();
include '../../libs/connect/connect.php';

class PHPClass {

    private $userId = "";
    private $fdate = "";
    private $tdate = "";
    private $checked = "";
    private $team = "";
    private $staffId = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
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
        $sql = "SELECT a.stamp_id, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,DATE_FORMAT(a.stamp_date,'%a') as dText,"
                . " concat(b.Name ,' (',b.NickName,')') as stamp_uid,"
                . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start,"
                . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                . " a.stamp_note,"
                . " if(c.stamp_shift=''||ISNULL(c.stamp_shift),if(g.work_shift_start='none','none',concat(g.work_shift_start,'-',g.work_shift_stop)),"
                . " if(i.work_shift_start='none','none',if(i.work_shift_start='OT','OT',concat(i.work_shift_start,'-',i.work_shift_stop)))) as work_shift_id,"
                . " c.late as stamp_late, "
                . " c.overtime as stamp_ot, "
                . " if(a.stamp_start_ip = a.stamp_stop_ip, a.stamp_start_ip, if(a.stamp_stop_ip!='', concat(a.stamp_start_ip, ' - ', a.stamp_stop_ip), a.stamp_start_ip)) as stamp_ip"
                . ", c.before_time as stamp_before,f.reason_name as reason_id,c.hours as work_hours"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
                . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
                . " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
                . " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
                . " LEFT JOIN bz_timestamp.t_work_shift i ON i.work_shift_id = c.stamp_shift"
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
        $sql.= " ORDER BY a.stamp_date ASC";
        $result = $this->mysqli->query($sql) or die('xx' . $this->mysqli->error);

        while ($obj = $result->fetch_assoc()) {

            $this->obj[] = array(
                "stamp_id" => $obj['stamp_id'],
                "dText" => $obj['dText'],
                "stamp_date" => $obj['stamp_date'],
                "stamp_uid" => $obj['stamp_uid'],
                "stamp_start" => $obj['stamp_start'],
                "stamp_stop" => $obj['stamp_stop'],
                "work_shift_id" => $obj['work_shift_id'],
                "work_hours" => $this->minutesToHours($obj['work_hours']),
                "stamp_late" => $obj['stamp_late'],
                "stamp_ot" => $obj['stamp_ot'],
                "stamp_before" => $obj['stamp_before'],
                "reason_id" => $obj['reason_id'],
                "stamp_note" => $obj['stamp_note'],
                "stamp_ip" => $obj['stamp_ip']
            );
        }
        //sum-------------------------------------------------------------------
        $sql = "SELECT concat('Total') as stamp_stop, if(SUM(c.late)<=0, '', SUM(c.late)) as stamp_late, "
                . " if(SUM(c.overtime)<=0, '', SUM(c.overtime)) as stamp_ot"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
                . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
                . " WHERE a.is_delete = 0";
        if (!empty($this->team)) {
            $sql.= " AND b.Team = '$this->team'";
        }
        if (!empty($this->staffId)) {
            $sql.= " AND b.id = '$this->staffId'";
        } else {
            $sql.= " AND a.stamp_uid = '$this->userId'";
        }
        if ($this->checked === 'true') {
            $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m') = '" . date('Y-m') . "'";
        } else {
            $sql .= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') BETWEEN '$this->fdate' AND '$this->tdate'";
        }
        $sql.= " ORDER BY a.stamp_date ASC";
        $result = $this->mysqli->query($sql) or die('xx' . $this->mysqli->error);
        while ($obj = $result->fetch_object()) {
            $this->obj[] = $obj;
        }
        echo "{\"data\":" . json_encode($this->obj) . "}";
    }

    function minutesToHours($minutes) {
        $hours = (int) ($minutes / 60);
        $minutes -= $hours * 60;
        if (sprintf("%d.%02.0f", $hours, $minutes) == '0.00' || sprintf("%d.%02.0f", $hours, $minutes) == '-1.00') {
            $h = '';
        } else {
            $h = sprintf("%d.%02.0f", $hours, $minutes);
        }
        return $h;
    }

}

/*
 * get new class  
 */
$PHPClass = new PHPClass($mysqli);
$PHPClass->loadData();
