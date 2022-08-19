<?php

header('Content-Type: text/html; charset=utf-8');

include '../connect/connect.php';
$team = $_GET['team'];

$sql = "SELECT a.id,  concat(a.titlename, a.Name, '(',a.NickName,')') as name,  a.Team"
        . " FROM baezenic_people.t_people a"
        . " INNER JOIN bz_timestamp.t_employee_time b ON b.uid=a.id"
        . " WHERE a.status<>'Y' AND a.Office<>'vietnam' AND b.is_operator=1";
if (!empty($team)) {
    $sql.= " AND a.Team='$team'";
}
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    $a[] = array(
        "text" => $row['name'],
        "value" => $row['id']
    );
}
echo json_encode($a);
