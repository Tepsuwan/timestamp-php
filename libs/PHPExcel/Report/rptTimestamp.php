<?php

include '../../connect/connect.php';
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$col = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

foreach ($col as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$intRow = 1;
$objPHPExcel->getActiveSheet()->getRowDimension($intRow)->setRowHeight(20);
$objPHPExcel->setActiveSheetIndex()
        ->setCellValue('A' . $intRow, "")
        ->setCellValue('B' . $intRow, "Name")
        ->setCellValue('C' . $intRow, "Date")
        ->setCellValue('D' . $intRow, "Start")
        ->setCellValue('E' . $intRow, "Stop")
        ->setCellValue('F' . $intRow, "Start IP")
        ->setCellValue('G' . $intRow, "Stop IP")
        ->setCellValue('H' . $intRow, "Reason")
        ->setCellValue('I' . $intRow, "Note");

$monthChecked = $_GET['m'];
$toadyChecked = $_GET['today'];
$team = $_GET['team'];
$staffId = $_GET['staff'];
$fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['fdate'])));
$tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['tdate'])));
//$monthChecked = $_POST['m'];
//$toadyChecked = $_POST['today'];
//$team = $_POST['team'];
//$staffId = $_POST['staff'];
//$fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fdate'])));
//$tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['tdate'])));

$fd = date("d/m/Y", strtotime($fdate));
$td = date("d/m/Y", strtotime($tdate));
//
$sql = "SELECT DATE_FORMAT(a.stamp_date,'%Y-%m-%d') as date, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date"
        . " FROM bz_timestamp.t_stamp a"
        . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
        . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
        . " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
        . " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
        . " WHERE a.is_delete = 0";
if (!empty($team)) {
    $sql.= " AND b.Team = '$team'";
}
if (!empty($staffId)) {
    $sql.= " AND b.id = '$staffId'";
}
if ($monthChecked === 'true') {
    $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m') = '" . date('Y-m') . "'";
} else if ($toadyChecked === 'true') {
    $sql.= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') = '" . date('Y-m-d') . "'";
} else {
    $sql .= " AND DATE_FORMAT(a.stamp_date, '%Y-%m-%d') BETWEEN '$fdate' AND '$tdate'";
}
$sql.= "GROUP BY a.stamp_date ORDER BY a.stamp_date ASC";
$resultG = $mysqli->query($sql) or die($mysqli->error);

$intRow = $intRow + 1;

while ($row = $resultG->fetch_assoc()) {

    $date = $row['date'];
    $weekend = date('D', strtotime($row['date']));
    $objPHPExcel->getActiveSheet()->getRowDimension($intRow)->setRowHeight(18);
    $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A' . $intRow, $row['stamp_date']);

    $sql2 = "SELECT a.id, a.uid,concat(b.titlename,b.Name,' ( ',b.NickName,' )') as uname, a.work_shift_id, a.is_operator"
            . " FROM bz_timestamp.t_employee_time a"
            . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.uid USING utf8) "
            . " WHERE a.is_operator=1  ";
    if (!empty($team)) {
        $sql2.= " AND b.Team = '$team'";
    }
    if (!empty($staffId)) {
        $sql2.= " AND b.id = '$staffId'";
    }
    $sql2.=" ORDER BY b.Name ASC";
    $result2 = $mysqli->query($sql2);
    $intRow2 = $intRow + 1;
    $a = array();
    while ($fetch = $result2->fetch_assoc()) {
        $uid = $fetch['uid'];
        $uname = $fetch['uname'];

        $sql3 = "SELECT b.stamp_id, DATE_FORMAT(b.stamp_date,'%d/%m/%Y') as stamp_date,DATE_FORMAT(b.stamp_date,'%a') as dText,"
                . " concat(c.titlename,c.Name ,' (',c.NickName,')') as stamp_uid,"
                . " if(b.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_start,'%H:%i:%s')) as stamp_start,"
                . " b.stamp_start_ip,"
                . " if(b.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(b.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                . " b.stamp_stop_ip,b.stamp_note,g.reason_name as reason_id"
                . " FROM bz_timestamp.t_stamp b"
                . " INNER JOIN baezenic_people.t_people c ON CONVERT(c.id USING utf8) = CONVERT(b.stamp_uid USING utf8) "
                . " LEFT JOIN bz_timestamp.t_reason g ON g.reason_id = b.reason_id"
                . " WHERE b.is_delete = 0"
                . " AND b.stamp_uid = '$uid'"
                . " AND DATE_FORMAT(b.stamp_date, '%Y-%m-%d') = '$date' ";
        $result3 = $mysqli->query($sql3) or die($mysqli->error);
        if ($result3) {
            $num_rows = $result3->num_rows;
            if ($num_rows > 0) {
                while ($fetch = $result3->fetch_assoc()) {
                    //$obj[] = $obj;
                    $objPHPExcel->getActiveSheet()->getRowDimension($intRow2)->setRowHeight(15);
                    $objPHPExcel->setActiveSheetIndex()
                            ->setCellValue('B' . $intRow2, $fetch['stamp_uid'])
                            ->setCellValue('C' . $intRow2, $fetch['stamp_date'])
                            ->setCellValue('D' . $intRow2, $fetch['stamp_start'])
                            ->setCellValue('E' . $intRow2, $fetch['stamp_stop'])
                            ->setCellValue('F' . $intRow2, $fetch['stamp_start_ip'])
                            ->setCellValue('G' . $intRow2, $fetch['stamp_stop_ip'])
                            ->setCellValue('H' . $intRow2, $fetch['reason_id'])
                            ->setCellValue('I' . $intRow2, $fetch['stamp_note']);
                    ++$intRow2;
                }
            } else {
                if ($weekend != 'Sat' && $weekend != 'Sun') {
                    $a[] = $uname;
                }
            }
        }
    }
    $intRow2 = $intRow2;
    foreach ($a as &$value) {
        $objPHPExcel->getActiveSheet()->getRowDimension($intRow2)->setRowHeight(15);
        $objPHPExcel->setActiveSheetIndex()
                ->setCellValue('B' . $intRow2, $value);
        ++$intRow2;
    }
    $intRow = $intRow2;
    ++$intRow;
}



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
