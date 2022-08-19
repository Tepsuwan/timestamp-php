<?php

header("Content-type: application/json");
include_once('../connect/connect.php');

$sql = "SELECT `work_shift_id`, "
         . "CASE work_shift_start
                WHEN 'none' then 'none'
                WHEN 'OT' then 'OT'
                ELSE concat(`work_shift_start`,'-', `work_shift_stop`)
            END as staff_work_shift"      
        . " FROM `t_work_shift` WHERE 1 ORDER BY work_shift_start ASC";
$arr = array();
$rs = $mysqli->query($sql);
while ($obj = $rs->fetch_assoc()) {
    $arr[] = trim($obj['staff_work_shift']);
}

function match($val) {
    global $query;
    if (strpos(strtolower($val), $query) !== false) {
        return true;
    } else {
        return false;
    }
}

if (!empty($_GET['query'])) {
    $query = strtolower(trim($_GET['query']));
    $out = array_values(array_filter($arr, 'match'));
} else {
    $out = $arr;
}

echo json_encode($out);
