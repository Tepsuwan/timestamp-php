<?php

include '../../libs/connect/connect.php';
$obj = array();
$sql = "SELECT work_shift_id as value,"
        . "CASE work_shift_start
                WHEN 'none' then 'none'
                WHEN 'OT' then 'OT'
                ELSE concat(`work_shift_start`,'-', `work_shift_stop`)
            END as text"
        . " FROM t_work_shift"
        . " WHERE 1 "
        . " ORDER BY work_shift_start ASC";
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
