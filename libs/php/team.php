<?php

header('Content-Type: text/html; charset=utf-8');

include '../connect/connect.php';
$team = $_GET['team'];
$sql = "SELECT distinct Team"
        . " FROM baezenic_people.t_people"
        . " WHERE 1 AND status<>'Y' ";
if (!empty($team)) {
    $sql.=" AND Team='$team'";
}else{
    $sql.=" AND Team<>''";
}
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    if ($row['Team'] == 'All')
        continue;
    $a[] = array(
        "text" => $row['Team'],
        "value" => $row['Team']
    );
}
echo json_encode($a);
