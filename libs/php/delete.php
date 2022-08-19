<?php

include '../connect/connect.php';
$stamp_id = $_POST['id'];

$sql = "UPDATE t_stamp SET is_delete=1 WHERE stamp_id='$stamp_id'";
$mysqli->query($sql);
/*
 * update is_delete table detail
 */
$sql = "UPDATE t_late SET is_delete=1 WHERE stamp_id='$stamp_id'";
$mysqli->query($sql);
$sql = "UPDATE t_overtime SET is_delete=1 WHERE stamp_id='$stamp_id'";
$mysqli->query($sql);
$sql = "UPDATE t_before_time SET is_delete=1 WHERE stamp_id='$stamp_id'";
$mysqli->query($sql);



