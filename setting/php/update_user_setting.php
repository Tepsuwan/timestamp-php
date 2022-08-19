<?php

@session_start();

include_once('../../libs/connect/connect.php');


if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];

    $id = $_POST['id'];
    $prop = $_POST['prop'];
    $value = $_POST['val'];


    switch ($action) { //Switch case for value of action
        case "set_check":
            if ($value === "true") {
                $val = 1;
            } else {
                $val = 0;
            }
            $id_setting = uniqid();
            $sql = "SELECT id FROM t_user_setting WHERE uid='$id'";
            $result = $mysqli->query($sql);
            $num_rows = $result->num_rows;
            if ($num_rows == 0) {
                $sql = "INSERT INTO t_user_setting("
                        . "id,uid, $prop,"
                        . " create_user, create_date"
                        . ") VALUES ("
                        . "'" . $id_setting . "','" . $id . "','" . $val . "','" . $_SESSION['adminID'] . "','" . date('Y-m-d H:i:s') . "'"
                        . ")";
                $mysqli->query($sql);
                $json["sql"] = $sql;
            } else {

                $sql = "UPDATE t_user_setting SET $prop='$val' ";
                $sql.=" WHERE uid='$id' ";
                $mysqli->query($sql);
                $json["sql"] = $sql;
            }

            $json["success"] = true;
            echo json_encode($json);

            break;
        case "work_shift":

            $id_setting = uniqid();
            $sql = "SELECT id FROM t_user_setting WHERE uid='$id'";
            $result = $mysqli->query($sql);
            $num_rows = $result->num_rows;
            if ($num_rows == 0) {
                $sql = "INSERT INTO t_user_setting("
                        . "id,uid, $prop,"
                        . " create_user, create_date"
                        . ") VALUES ("
                        . "'" . $id_setting . "','" . $id . "','" . $value . "','" . $_SESSION['adminID'] . "','" . date('Y-m-d H:i:s') . "'"
                        . ")";
                $mysqli->query($sql);
                $json["sql"] = $sql;
            } else {

                $sql = "UPDATE t_user_setting SET $prop='$value' ";
                $sql.=" WHERE uid='$id' ";
                $mysqli->query($sql);
                $json["sql"] = $sql;
            }

            $json["success"] = true;
            echo json_encode($json);



            break;
        default:
    }
}
       
