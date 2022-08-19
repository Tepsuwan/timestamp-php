<?php

include '../../libs/connect/connect.php';
$work_shift_id = $_GET['id'];
$team = $_GET['team'];
$obj = array();
$sql = "SELECT a.calendar_id, a.uid,if(a.uid='1234567890','Vietnam',b.NickName) as title,"
        . " concat(a.calendar_date_start,' ',c.work_shift_start) as event_start, a.calendar_date_end as event_end,"
        . " calendar_bg_color,"
        . " calendar_border_color"
        . " FROM bz_timestamp.t_calendar a"
        . " LEFT JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.uid USING utf8)"
        . " LEFT JOIN bz_timestamp.t_work_shift c ON c.work_shift_id=a.work_shift_id"
        . " WHERE 1 AND a.team='$team'";
if ($work_shift_id != "All") {
    $sql.= " AND a.work_shift_id='$work_shift_id'";
}

$result = $mysqli->query($sql);
if ($result) {
    while ($row = $result->fetch_object()) {
        $obj[] = $row;
    }
    $json = array(
        "success" => true,
        "data" => $obj
    );
} else {
    $json = array(
        "success" => false,
        "data" => $obj
    );
}
echo json_encode($json);
