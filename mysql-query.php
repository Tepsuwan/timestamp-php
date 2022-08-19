<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "baezenic_plandev";


$mysqli = new mysqli($hostname, $username, $password, $database);
$mysqli->query("SET NAMES 'utf8'");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


$sql = "SELECT a.project_id,a.project_name,a.style_id,b.customer_id,b.customer_name,b.tempPresetId,c.presetName,b.productListId,d.packetName,e.paketId,e.featureId,"
        . "h.cataloge_name,h.cataloge_id"
        . " FROM bz_project a "
        . " LEFT JOIN bz_custmer b ON a.style_id=b.customer_id"
        . " LEFT JOIN bz_template_preset c ON c.presetID=b.tempPresetId"
        . " LEFT JOIN bz_template_packet d ON d.packetID=b.productListId"
        . " LEFT JOIN bz_pakage_feature e ON e.paketId=d.packetID"
        . " LEFT JOIN bz_style f ON f.style_id=e.featureId"
        . " LEFT JOIN bz_feature g ON g.feature_code=f.feature_code"
        . " LEFT JOIN bz_feature_cataloge h ON h.cataloge_id=g.feature_cataloge_id"
        . " WHERE a.project_id='55d6afdb92d4a'"
        . " GROUP BY h.cataloge_name";
$query = $mysqli->query($sql);
//echo $sql . "<br>";
while ($fetch = $query->fetch_assoc()) {
    $project_id = $fetch['project_id'];
    $cataloge_id = $fetch['cataloge_id'];
    $cataloge_name = $fetch['cataloge_name'];
    echo 'cataloge_name=> ' . $cataloge_name . "<br>";
    //echo "------------------------------------------------------------"."<br>";
    //--------------------------------
    $sql = "SELECT a.project_id,a.project_name,a.style_id,b.customer_id,b.customer_name,b.tempPresetId,c.presetName,b.productListId,d.packetName,e.paketId,e.featureId,"
            . "h.cataloge_name,h.cataloge_id"
            . " FROM bz_project a "
            . " LEFT JOIN bz_custmer b ON a.style_id=b.customer_id"
            . " LEFT JOIN bz_template_preset c ON c.presetID=b.tempPresetId"
            . " LEFT JOIN bz_template_packet d ON d.packetID=b.productListId"
            . " LEFT JOIN bz_pakage_feature e ON e.paketId=d.packetID"
            . " LEFT JOIN bz_style f ON f.style_id=e.featureId"
            . " LEFT JOIN bz_feature g ON g.feature_code=f.feature_code"
            . " LEFT JOIN bz_feature_cataloge h ON h.cataloge_id=g.feature_cataloge_id"
            . " WHERE a.project_id='$project_id' AND  h.cataloge_id='$cataloge_id'";
    // echo $sql . "<br>";
    $query2 = $mysqli->query($sql);
    while ($fetch2 = $query2->fetch_assoc()) {
        $featureId = $fetch2['featureId'];
        echo "featureId=> " . $fetch2['featureId'] . "<br>";

        $sql = "SELECT b.style_id, c.style_id as style_id_detail, c.material, b.type, b.style_note, b.feature_code, b.status"
                . " FROM  bz_pakage_feature  a "
                . " LEFT JOIN bz_style b ON b.style_id=a.featureId"
                . " LEFT JOIN bz_style_detail c ON b.style_id=c.style_id"
                . " WHERE a.featureId='$featureId'";

        echo $sql . "<br>";
//        $query3 = $mysqli->query($sql);
//        while ($fetch3 = $query3->fetch_assoc()) {
//        }
    }
    echo "------------------------------------------------------------" . "<br>";
}
?>




