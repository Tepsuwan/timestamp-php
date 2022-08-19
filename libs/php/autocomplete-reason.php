<?php

header("Content-type: application/json");
include_once('../connect/connect.php');

$sql = "SELECT `reason_name`"
        . " FROM `t_reason` WHERE 1 ORDER BY reason_name ASC";
$arr = array();
$rs = $mysqli->query($sql);
while ($obj = $rs->fetch_assoc()) {
    $arr[] = trim($obj['reason_name']);
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
