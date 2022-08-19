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
    private $office = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {

        $this->userId = (isset($_GET['uid']) ? $_GET['uid'] : "");
        $this->mysqli = $mysqli;
        $this->checked = $_GET['checked'];
        $this->team = $_GET['team'];
        $this->staffId = $_GET['staff'];
        $this->office = $_GET['office'];
        $this->fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['fdate'])));
        $this->tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['tdate'])));
    }

    function loadData() {


        $fd = date("d/m/Y", strtotime($this->fdate));
        $td = date("d/m/Y", strtotime($this->tdate));


        $sql = "SELECT a.stamp_uid,a.stamp_id, concat('$fd - $td') as stamp_date,"
                . " concat(b.Name ,' (',b.NickName,')') as stamp_uid,b.id as uid,"
                . " if(SUM(c.late)<=0,'',SUM(c.late)) as stamp_late,"
                . " if(SUM(c.overtime)<=0,'',SUM(c.overtime)) as stamp_ot,"
                . " if(SUM(c.before_time)<=0,'',SUM(c.before_time)) as stamp_before,"
                . " if(f.work_shift_start='none',f.work_shift_start,concat(f.work_shift_start,'-', f.work_shift_stop)) as work_shift_id,"
                . " sum(c.hours) as work_hours"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8)=CONVERT(a.stamp_uid USING utf8)"
                . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id=a.stamp_id"
                . " LEFT JOIN bz_timestamp.t_work_shift f ON f.work_shift_id=a.work_shift_id"
                . " WHERE a.is_delete=0";
        if (!empty($this->team)) {
            $sql.= " AND b.Team ='$this->team'";
        }
        if (!empty($this->staffId)) {
            $sql.= " AND b.id ='$this->staffId'";
        }
        if (!empty($this->office)) {
            $sql.= " AND b.Office ='$this->office'";
        }
        if ($this->checked === 'true') {
            $sql.= " AND DATE_FORMAT(a.stamp_date,'%Y-%m') ='" . date('Y-m') . "'";
        } else {
            $sql.= " AND a.stamp_date BETWEEN '$this->fdate' AND '$this->tdate'";
        }
        $sql.= " GROUP BY a.stamp_uid ORDER BY b.Name ASC";

        $result = $this->mysqli->query($sql) or die('x1->' . $this->mysqli->error);
        while ($obj = $result->fetch_assoc()) {

            $sql = "SELECT concat(b.work_shift_start,'-',work_shift_stop) as work FROM t_employee_time a LEFT JOIN t_work_shift b ON b.work_shift_id=a.work_shift_id WHERE `uid`='" . $obj['uid'] . "'";
            $r = $this->mysqli->query($sql);
            $f = $r->fetch_assoc();

            $this->obj[] = array(
                "stamp_id" => $obj['stamp_id'],
                "stamp_date" => $obj['stamp_date'],
                "stamp_uid" => "<a href=\"javascript:shiftDetial('" . $obj['uid'] . "');\" class=\"shift-detail\">" . $obj['stamp_uid'] . "</a>",
                "work_shift_id" => $f["work"],//$obj['work_shift_id'],
                "work_hours" => $this->minutesToHours($obj['work_hours']),
                "stamp_late" => $obj['stamp_late'],
                "stamp_ot" => $obj['stamp_ot'], //$this->convertToHoursMins($obj['stamp_ot'], '%02d h %02d m'),//
                "stamp_before" => $obj['stamp_before']
            );
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
