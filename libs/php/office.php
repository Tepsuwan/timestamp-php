<?php

header('Content-Type: text/html; charset=utf-8');

include '../connect/connect.php';

$sql = "SELECT distinct Office"
        . " FROM baezenic_people.t_people"
        . " WHERE 1 AND status<>'Y' ";

$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
    if ($row['Office'] == 'All')
        continue;
    $a[] = array(
        "text" => $row['Office'],
        "value" => $row['Office']
    );
}
echo json_encode($a);
