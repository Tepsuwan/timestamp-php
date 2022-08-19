
<?php

session_start();
date_default_timezone_set('Asia/Bangkok');
include '../libs/connect/connect.php';
$SecertKey = "184d4648c3d47fd6db84d5299ebf36d329423b59d2ef69a3778635c47d13f5a0";

$source = $_GET["source"];
$desource = base64_decode($source);

if ($desource) {

    $params = explode(':', $desource);
    $token = base64_decode($params[0]);
    $email = base64_decode($params[1]);
    $datetime = base64_decode($params[2]);
    $hash = hash_hmac('sha256', $email . $datetime, $SecertKey);

    if ($hash == $token) {

        $result = login($email, $mysqli);
        if ($result) {
            echo "Loading .....................";
            echo "<script>";
            echo "  window.location='http://bztimestamp.corp.net/stamp'";
            echo "</script>";
            exit();
        } else {
            echo "<script>";
            echo "  window.location='http://bztimestamp.corp.net/index.php'";
            echo "</script>";
            exit();
        }
    } else {
        echo "<script>";
        echo "  window.location='/index.php'";
        echo "</script>";
    }
}

function login($email, $mysqli) {

    $sql = "SELECT "
            . " p.id,p.NickName,p.Office,p.Email,a.role_key,u.work_shift_id,p.Team"
            . " FROM baezenic_people.t_people p "
            . " LEFT JOIN bz_timestamp.t_employee_time u ON u.uid=p.id"
            . " LEFT JOIN bz_timestamp.t_admin_user a ON a.uid=p.id"
            . " WHERE p.status<>'Y' AND TRIM(p.Email)='" . $email . "' and u.is_operator=1 ORDER BY p.id ASC";

    $result = $mysqli->query($sql);
    if ($result) {
        $num_rows = $result->num_rows;
        $row = $result->fetch_assoc();
        if ($num_rows != 0) {
            $_SESSION['userId'] = $row['id'];
            $_SESSION['userName'] = $row['NickName'];
            $_SESSION['role_key'] = $row['role_key'];
            $_SESSION['work_shift_id'] = $row['work_shift_id'];
            $_SESSION['team'] = $row['Team'];
            return TRUE;
        } else {
            return FALSE;
        }
    } else {

        return FALSE;
    }
}
