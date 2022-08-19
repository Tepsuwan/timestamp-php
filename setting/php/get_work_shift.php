<?php

include '../../libs/connect/connect.php';
$id = $_GET['id'];
$obj = array();
$sql = "SELECT work_shift_id as value,"
        . " if(work_shift_start='none',work_shift_start,"
        . " concat(work_shift_start,'-', work_shift_stop)) as text"
        . " FROM t_work_shift"
        . " WHERE work_shift_id='$id' "
        . " ORDER BY work_shift_start ASC";
$result = $mysqli->query($sql);
if ($result) {
    while ($row = $result->fetch_object()) {
        $obj = $row;
    }
}
echo json_encode($obj);
