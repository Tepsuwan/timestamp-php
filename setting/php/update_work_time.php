<?php

@session_start();

include_once('../../libs/connect/connect.php');




$id = $_POST['id'];
$prop = $_POST['prop'];
$action = $_POST['action'];




if ($prop === "is_operator") {
    $value = ($_POST['checked'] === 'true' ? 1 : 0);    
} else {
    $value = $_POST['val'];
}



$id_setting = uniqid();
$sql = "SELECT id FROM t_employee_time WHERE uid='$id'";
$result = $mysqli->query($sql);
$num_rows = $result->num_rows;
if ($num_rows == 0) {
    $sql = "INSERT INTO t_employee_time("
            . "id,uid, $prop,"
            . " create_user, create_date"
            . ") VALUES ("
            . "'" . $id_setting . "','" . $id . "','" . $value . "','" . $_SESSION['adminID'] . "','" . date('Y-m-d H:i:s') . "'"
            . ")";
    $mysqli->query($sql);
    $json["sql"] = $sql;
} else {

    $sql = "UPDATE t_employee_time SET $prop='$value' ";
    $sql.=" WHERE uid='$id' ";
    $mysqli->query($sql);
    $json["sql"] = $sql;
}

$json["success"] = true;
echo json_encode($json);



