
<?php

include '../connect/connect.php';
$userID = $_GET['uid'];
$shiftID = $_GET['shiftId'];
$Hour = date('G');

if ($shiftID == '55c483de2c235') {// work shift=none
    if ($Hour >= 0 && $Hour <= 18) {

        $date_yesterday = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        $sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop,stamp_note"
                . " FROM t_stamp "
                . " WHERE stamp_date='" . $date_yesterday . "' "
                . " AND stamp_uid='$userID'"
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

    $date_now = getWorkShiftStartDate($userID);
    // $date_now = date('Y-m-d');
}

/*
 * select date from $date_now------------------------------------------------
 */
$sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop"
        . " FROM t_stamp "
        . " WHERE stamp_date='" . $date_now . "' AND stamp_uid='$userID' AND is_delete=0";
//echo "<br>1#$sql<br>";
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

    if ($shiftID == '55c483de2c235') {// work shift=none
        $sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop"
                . " FROM t_stamp "
                . " WHERE stamp_date='" . date('Y-m-d') . "' AND stamp_uid='$userID' AND is_delete=0";
    } else {
        $sql = "SELECT stamp_id,stamp_date,stamp_start,stamp_stop"
                . " FROM t_stamp "
                . " WHERE stamp_date='" . $date_now . "' AND stamp_uid='$userID' AND is_delete=0";
    }
    //echo "2# $sql <br>";

    /* if today no have data start
      ---------------------------------------------------------------------- */
    $rs = $mysqli->query($sql);
    $num_rows = $rs->num_rows;
    if ($num_rows == 0) {//if today no have data start
        $start_disabled = "";
        $txt_start = "START <small class=\"label label-info\">" . date('d/m/Y') . '</small>';
        //work shift = none only
        $sql = "SELECT stamp_id"
                . " FROM t_stamp "
                . " WHERE stamp_date='" . $date_now . "' AND work_shift_id='55c483de2c235' AND stamp_uid='$userID' AND is_delete=0";
        $rs = $mysqli->query($sql);
        $num_rows = $rs->num_rows;
        if ($num_rows > 0) {
            $stop_disabled = "";
            $txt_stop = "STOP  <small class=\"label label-warning\">" . date('d/m/Y', strtotime($date_now)) . '</small>';
        }
    } else {
        /* if today have data start disabled btn start       
          ------------------------------------------------------------------ */
        if ($shiftID == '55c483de2c235') {
            $start_disabled = "disabled";
            $stop_disabled = "";
            $txt_stop = "  STOP ";
            $txt_start = "<i class=\"fa fa-ban text-danger\"></i> START ";
            $date_now = date('Y-m-d');
        } else {

            $start_disabled = "disabled";
            $stop_disabled = "";
            $txt_stop = "  STOP ";
            $txt_start = "<i class=\"fa fa-ban text-danger\"></i> START ";
            // $date_now = date('Y-m-d');
            $date_now = getWorkShiftStartDate($userID);
        }
    }
}
$date_text = date('l F d,Y', strtotime($date_now));
$date_now = date('d/m/Y', strtotime($date_now));

echo '<div class="col-md-6 no-padding"> ';
echo '  <div class="box-body">';
echo '      <button type="button" id="start" data-date="' . date('d/m/Y') . '" data-id="' . $row['stamp_id'] . '" class="btn btn-instagram  btn-block btn-lg ' . $start_disabled . '">' . $txt_start . '</button>';
echo '  </div>';
echo '</div>';
echo '<div class ="col-md-6 no-padding">';
echo '  <div class ="box-body">';
echo '      <button type = "button" id = "stop" data-date = "' . $date_now . '" data-id = "' . $row['stamp_id'] . '" class = "btn btn-instagram btn-block btn-lg ' . $stop_disabled . '">' . $txt_stop . '</small></button>';
echo '  </div>';
echo '</div>';


/*
 * ===========================================================================
 */

function getWorkShiftStartDate($userID) {

    global $mysqli;
    $sql = "SELECT b.work_shift_start as start,b.work_shift_stop as stop,c.stamp_date,c.stamp_start,c.stamp_stop "
            . "FROM bz_timestamp.t_employee_time a "
            . "INNER JOIN t_work_shift b ON a.work_shift_id=b.work_shift_id  "
            . "INNER JOIN t_stamp c ON c.work_shift_id=a.work_shift_id and c.work_shift_id<>'' "
            . "WHERE a.uid='" . $userID . "' order by c.stamp_date DESC limit 1 ";

    //echo "$sql<br>";

    $rs = $mysqli->query($sql);
    $row = $rs->fetch_assoc();
    $start = $row['start'];
    $stampDate = $row['stamp_date'];
    $stampStop = $row['stamp_stop'];
    $startShift = "15:00";
    $startDateTime = date("Y-m-d H:i:s", strtotime($stampDate . $row['start']));
    $hoursFinsh = date('Y-m-d H:i:s', strtotime('+16 hours', strtotime($stampDate . $row['start'])));
    $midnight = date("Y-m-d H:i:s", strtotime($stampDate . ' 23:59:59'));
    $dateTimeNow = date('Y-m-d H:i:s');

//    echo "Stamp Stop $stampStop<br>" . 'xxxx ' . strtotime($stampStop);
//    echo "midnight " . $midnight . "=" . strtotime($midnight) . '<br>';
//    echo 'Afet     ' . $dateTimeNow . "=" . strtotime($dateTimeNow) . '<br>';
//    echo 'start ' . $startDateTime . '=' . $start . $hoursFinsh . '<br>';

    /* If stop work get date now
      ---------------------------------------------------------------------- */
    if (strtotime($stampStop)) {
        $startDate = date('Y-m-d');
    } else {
        //Check If datenow > date start
        if (strtotime($dateTimeNow) >= strtotime($midnight)) {
            // echo"YES";             
            if ($start >= $startShift) {
                if (strtotime($dateTimeNow) >= strtotime($hoursFinsh)) {
                    $startDate = date('Y-m-d');
                } else {
                    $startDate = date('Y-m-d', strtotime('-1 day'));
                }
            } else {
                $startDate = date('Y-m-d');
            }
        } else {
            $startDate = date('Y-m-d');
        }
    }

    return $startDate;
}
