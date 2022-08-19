<?php

session_start();
require_once '../../libs/connect/connect.php';
require_once ('../PHPClass/MysqliDb.php');
$db = new MysqliDb($mysqli);


$monthChecked = (empty($_GET['m']) ? '' : $_GET['m']);
$toadyChecked = (empty($_GET['today']) ? '' : $_GET['today']);
$team = (empty($_GET['team']) ? '' : $_GET['team']);
$staffId = (empty($_GET['staff']) ? '' : $_GET['staff']);
$fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['fdate'])));
$tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['tdate'])));


$cols = Array(
    "DATE_FORMAT(a.stamp_date,'%Y-%m-%d') as date",
    "DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date"
);
$db->join("baezenic_people.t_people b", "CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)", "INNER");
$db->join("bz_timestamp.t_late c", "c.stamp_id = a.stamp_id", "LEFT");
$db->join("bz_timestamp.t_reason f", "f.reason_id = a.reason_id", "LEFT");
$db->join("bz_timestamp.t_work_shift g", "g.work_shift_id = a.work_shift_id", "LEFT");
$db->where("a.is_delete", 0);
if (!empty($team))
    $db->where("b.Team", $team);
if (!empty($staffId))
    $db->where("b.id", $staffId);
if ($monthChecked === 'true') {
    $db->where("DATE_FORMAT(a.stamp_date, '%Y-%m')", date('Y-m'));
} else if ($toadyChecked === 'true') {
    $db->where("DATE_FORMAT(a.stamp_date, '%Y-%m-%d')", date('Y-m-d'));
} else {
    $db->where("DATE_FORMAT(a.stamp_date, '%Y-%m-%d')", Array("$fdate", "$tdate"), 'BETWEEN');
}
$db->groupBy("a.stamp_date");
$db->orderBy("a.stamp_date", "asc");
$query = $db->get("bz_timestamp.t_stamp a", null, $cols);
if ($db->count > 0)
    foreach ($query as $query) {

        $date = $query['date'];
        $weekend = date('D', strtotime($query['date']));
        if (empty($staffId)) {
            $obj[] = array("gdate" => $query['stamp_date']);
        }
        /*
         * Group date-----------------------------------------------------------
         */
        $cols = Array("a.uid", "concat(b.titlename,b.Name,' ( ',b.NickName,' )') as uname");
        $db->join("baezenic_people.t_people b", "CONVERT(b.id USING utf8) = CONVERT(a.uid USING utf8)", "INNER");
        $db->where("a.is_operator", 1);
        if (!empty($team))
            $db->where("b.Team", $team);
        if (!empty($staffId))
            $db->where("b.id", $staffId);
        $db->orderBy("b.Name", "asc");
        $query2 = $db->get("bz_timestamp.t_employee_time a", null, $cols);
        $a = array();
        if ($db->count > 0)
            foreach ($query2 as $query2) {
                $uid = $query2['uid'];
                $uname = $query2['uname'];
                /*
                 * detail--------------------- 
                 */
                $cols = Array(
                    "b.stamp_id",
                    "DATE_FORMAT(b.stamp_date,'%d/%m/%Y') as stamp_date",
                    "DATE_FORMAT(b.stamp_date,'%a') as dText",
                    "concat(c.titlename,c.Name ,' (',c.NickName,')') as stamp_uid",
                    "if(b.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_start,'%H:%i:%s')) as stamp_start",
                    "b.stamp_start_ip",
                    "if(b.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_stop,'%H:%i:%s')) as stamp_stop",
                    "b.stamp_stop_ip",
                    "b.stamp_note",
                    "g.reason_name as reason_id"
                );
                $db->join("baezenic_people.t_people c", "CONVERT(c.id USING utf8) = CONVERT(b.stamp_uid USING utf8)", "INNER");
                $db->join("bz_timestamp.t_reason g", "g.reason_id = b.reason_id", "LEFT");
                $db->where("b.is_delete", 0);
                $db->where("DATE_FORMAT(b.stamp_date, '%Y-%m-%d')", $date);
                $db->where("b.stamp_uid", $uid);
                $db->orderBy("c.Name", "asc");
                $query3 = $db->get("bz_timestamp.t_stamp b", null, $cols);
                if ($db->count > 0) {
                    foreach ($query3 as $query3) {
                        $obj[] = $query3;
                    }
                } else {
                    if ($weekend != 'Sat' && $weekend != 'Sun') {
                        $a[] = $uname;
                    }
                }
            }
        foreach ($a as &$value) {
            $obj[] = array("stamp_uid" => $value);
        }
    }
echo "{\"data\":" . json_encode($obj) . "}";
exit();
/*
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */

class PHPClass {

    private $fdate = "";
    private $tdate = "";
    private $monthChecked = "";
    private $toadyChecked = "";
    private $team = "";
    private $staffId = "";
    private $mysqli = "";
    private $obj = array();

    function __construct($mysqli) {


        $this->mysqli = $mysqli;
        $this->monthChecked = $_GET['m'];
        $this->toadyChecked = $_GET['today'];
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
                . " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
                . " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
                . " WHERE a.is_delete = 0";
        if (!empty($this->team)) {
            $sql.= " AND b.Team = '$this->team'";
        }
        if (!empty($this->staffId)) {
            $sql.= " AND b.id = '$this->staffId'";
        }
        if ($this->monthChecked === 'true') {
            $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m') = '" . date('Y-m') . "'";
        } else if ($this->toadyChecked === 'true') {
            $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') = '" . date('Y-m-d') . "'";
        } else {
            $sql .= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') BETWEEN '$this->fdate' AND '$this->tdate'";
        }
        $sql.= "GROUP BY a.stamp_date ORDER BY a.stamp_date ASC";
        $resultG = $this->mysqli->query($sql) or die($this->mysqli->error);
        while ($row = $resultG->fetch_assoc()) {
            $date = $row['date'];
            $weekend = date('D', strtotime($row['date']));
            if (empty($this->staffId)) {
                $this->obj[] = array("gdate" => $row['stamp_date']);
            }

            $sql2 = "SELECT a.id, a.uid,concat(b.titlename,b.Name,' ( ',b.NickName,' )') as uname, a.work_shift_id, a.is_operator"
                    . " FROM bz_timestamp.t_employee_time a"
                    . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.uid USING utf8) "
                    . " WHERE a.is_operator=1  ";
            if (!empty($this->team)) {
                $sql2.= " AND b.Team = '$this->team'";
            }
            if (!empty($this->staffId)) {
                $sql2.= " AND b.id = '$this->staffId'";
            }
            $sql2.=" ORDER BY b.Name ASC";
            $result2 = $this->mysqli->query($sql2);
            $a = array();
            while ($fetch = $result2->fetch_assoc()) {
                $uid = $fetch['uid'];
                $uname = $fetch['uname'];

                $sql3 = "SELECT b.stamp_id, DATE_FORMAT(b.stamp_date,'%d/%m/%Y') as stamp_date,DATE_FORMAT(b.stamp_date,'%a') as dText,"
                        . " concat(c.titlename,c.Name ,' (',c.NickName,')') as stamp_uid,"
                        . " if(b.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_start,'%H:%i:%s')) as stamp_start,"
                        . " b.stamp_start_ip,"
                        . " if(b.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                        . " b.stamp_stop_ip,b.stamp_note,g.reason_name as reason_id"
                        . " FROM bz_timestamp.t_stamp b"
                        . " INNER JOIN baezenic_people.t_people c ON CONVERT(c.id USING utf8) = CONVERT(b.stamp_uid USING utf8) "
                        . " LEFT JOIN bz_timestamp.t_reason g ON g.reason_id = b.reason_id"
                        . " WHERE b.is_delete = 0"
                        . " AND b.stamp_uid = '$uid'"
                        . " AND DATE_FORMAT(b.stamp_date, '%Y-%m-%d') = '$date' ";
                $result3 = $this->mysqli->query($sql3) or die($this->mysqli->error);
                if ($result3) {
                    $num_rows = $result3->num_rows;
                    if ($num_rows > 0) {
                        while ($obj = $result3->fetch_object()) {
                            $this->obj[] = $obj;
                        }
                    } else {
                        if ($weekend != 'Sat' && $weekend != 'Sun') {
                            $a[] = $uname;
                        }
                    }
                }
            }

            foreach ($a as &$value) {
                $this->obj[] = array("stamp_uid" => $value);
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
