
<?php

//echo '<div class="col-md-2"></div>';
echo '<div class="col-md-6"> ';

include '../connect/connect.php';
$userId = $_GET['uid'];
$shiftID = $_GET['shiftId'];
$Hour = date('G');

if ($shiftID == '55c483de2c235') {// work shift=none
    if ($Hour >= 0 && $Hour <= 18) {

        $date_yesterday = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        $sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop,stamp_note"
                . " FROM t_stamp "
                . " WHERE stamp_date='" . $date_yesterday . "' "
                . " AND stamp_uid='$userId'"
                . " AND stamp_stop='0000-00-00 00:00:00' AND is_delete=0 ";
        $rs = $mysqli->query($sql);
        $row = $rs->fetch_assoc();
        $num_rows = $rs->num_rows;
        //select data if stamp_stop not have data -1 day
        if ($num_rows > 0) {
            if ($row['stamp_note'] != '') {
                $date_now = date('Y-m-d');
            } else {
                $date_now = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
            }
        } else {
            $date_now = date('Y-m-d');
        }
    } else {
        $date_now = date('Y-m-d');
    }
} else {
    $date_now = date('Y-m-d');
}
/*
 * select date from $date_now-----------------------
 */
$sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop"
        . " FROM t_stamp "
        . " WHERE stamp_date='" . $date_now . "' AND stamp_uid='$userId' AND is_delete=0";



$rs = $mysqli->query($sql);
$row = $rs->fetch_assoc();
$num_rows = $rs->num_rows;
$start = $row['stamp_start'];
$stop = $row['stamp_stop'];
$start_disabled = "";
$stop_disabled = "disabled ";
$txt_stop = "<i class=\"fa fa-ban text-danger\"></i> STOP ";
$txt_start = "START";


if ($start != "0000-00-00 00:00:00" && $start != "") {

    $sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop"
            . " FROM t_stamp "
            . " WHERE stamp_date='" . date('Y-m-d') . "' AND stamp_uid='$userId' AND is_delete=0";

//if today no have data start-------------------
    $rs = $mysqli->query($sql);
    $num_rows = $rs->num_rows;
    if ($num_rows == 0) {//if today no have data start
        $start_disabled = "";
        $txt_start = "START <small class=\"label label-info\">" . date('d/m/Y') . '</small>';
        //work shift = none only
        $sql = "SELECT stamp_id"
                . " FROM t_stamp "
                . " WHERE stamp_date='" . $date_now . "' AND work_shift_id='55c483de2c235' AND stamp_uid='$userId' AND is_delete=0";
        $rs = $mysqli->query($sql);
        $num_rows = $rs->num_rows;
        if ($num_rows > 0) {
            $stop_disabled = "";
            $txt_stop = "STOP  <small class=\"label label-warning\">" . date('d/m/Y', strtotime($date_now)) . '</small>';
        }
    } else {//if today have data start disabled btn start
        $start_disabled = "disabled";
        $stop_disabled = "";
        $txt_stop = "  STOP ";
        $txt_start = "<i class=\"fa fa-ban text-danger\"></i> START ";
        $date_now = date('Y-m-d');
    }
}
$date_now = date('d/m/Y', strtotime($date_now));


echo '<div class="box-body">';
echo '  <button type="button" id="start" data-date="' . date('d/m/Y') . '" data-id="' . $row['stamp_id'] . '" class="btn btn-instagram  btn-block btn-lg ' . $start_disabled . '">' . $txt_start . '</button>';
echo '</div>';
echo '</div>';
echo '<div class = "col-md-6">';
echo '<div class = "box-body">';
echo '  <button type = "button" id = "stop" data-date = "' . $date_now . '" data-id = "' . $row['stamp_id'] . '" class = "btn btn-instagram btn-block btn-lg ' . $stop_disabled . '">' . $txt_stop . '</button>';
echo '</div>';
echo '</div>';



