<?php

session_start();
include '../../libs/connect/connect.php';

$myObject = [];
$work_time = array();

$team = $_GET['team'];

$sql = "SELECT work_shift_id, "
        . " CASE work_shift_start"
        . " WHEN 'none' then 'none'"
        . " WHEN 'OT' then 'OT'"
        . " ELSE concat(`work_shift_start`,'-', `work_shift_stop`)"
        . " END as staff_work_shift"
        . " FROM t_work_shift WHERE 1 ORDER BY work_shift_start ASC";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) {
    $work_time[] = $row;
}
//==============================================================================


$sql = "SELECT @rownum := @rownum + 1 AS rownum,"
        . "p.id,CONCAT(p.titlename,' ',Name,' ( ',p.NickName,' )') as name,u.work_shift_id as work_time,u.is_operator as access,p.Team as team "
        . "FROM baezenic_people.t_people p "
        . "LEFT JOIN bz_timestamp.t_employee_time u ON u.uid=p.id "
        . ",(SELECT @rownum := 0) r "
        . "WHERE status<>'Y' AND Office!='Vietnam' ";
if ($team != "All") {
    $sql.=" AND p.Team='$team' ";
}
$sql.=" ORDER BY p.id ASC";

$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) {

    $foo = new StdClass();

    $foo->rownum = $row["rownum"];
    $foo->id = $row["id"];
    $foo->name = $row['name'];
    $foo->access = $row["access"];
    $foo->work_time = $row["work_time"];
    $foo->team = $row["team"];
    $foo->shift = $work_time;

    $myObject[] = $foo;
}



echo "{\"data\":" . json_encode($myObject) . "}";


