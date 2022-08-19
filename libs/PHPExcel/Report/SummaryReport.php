<?php

include '../../connect/connect.php';

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Browallia New');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

$styleHeaderLeft = array(
    'font' => array(
        'bold' => false,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => '00ccff'
        )
    )
);
$styleHeaderCenter = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => '00ccff'
        )
    )
);
$styleHeaderCenter = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => '00ccff'
        )
    )
);
$styleHeaderGroupUser = array(
    'font' => array(
        'bold' => true,
    ),
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    )
);
$styleColorRed = array(
    'font' => array(
        'bold' => false,
        'color' => array('rgb' => 'FF0000'),
    )
);
$styleColorGreen = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => '5cb85c'),
    )
);
$styleBorderTop = array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
    )
);
$styleBorderButtom = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
    )
);
$styleCenter = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

//------------------------------------------------------------------------------

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setVisible(false);



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
foreach (range('C', 'I') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth(9);
}
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(50);

$objPHPExcel->getActiveSheet()->freezePane('B2');

$intRow = 1;
$objPHPExcel->getActiveSheet()->getRowDimension($intRow)->setRowHeight(20);
$objPHPExcel->setActiveSheetIndex()
        ->setCellValue('A' . $intRow, "")
        ->setCellValue('B' . $intRow, "Date")
        ->setCellValue('C' . $intRow, "Start")
        ->setCellValue('D' . $intRow, "Stop")
        ->setCellValue('E' . $intRow, "Work Shift")
        ->setCellValue('F' . $intRow, "Work Hours")
        ->setCellValue('G' . $intRow, "Late (m)")
        ->setCellValue('H' . $intRow, "OT (m)")
        ->setCellValue('I' . $intRow, "Absence (m)")
        ->setCellValue('J' . $intRow, "Reason")
        ->setCellValue('K' . $intRow, "Note")
        ->setCellValue('L' . $intRow, "IP Address");

$objPHPExcel->getActiveSheet()->getStyle('A' . $intRow)->applyFromArray($styleHeaderLeft);
foreach (range('B', 'L') as $columnID) {
    $objPHPExcel->getActiveSheet()->getStyle($columnID . $intRow)->applyFromArray($styleHeaderCenter);
}

/*
 * ----------------------------------------------------------------------------
 */

$checked = $_POST['checked'];
$team = $_POST['team'];
$staffId = $_POST['staff'];
$office = $_POST['office'];
$fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fdate'])));
$tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['tdate'])));
$fd = date("d/m/Y", strtotime($fdate));
$td = date("d/m/Y", strtotime($tdate));

if ($checked === 'true') {
    $fdate = date('Y-m-01');
    $tdate = date('Y-m-t');
} else {
    $fdate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fdate'])));
    $tdate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['tdate'])));
}

$sql = "SELECT concat('$fd - $td') as stamp_date, "
        . " concat(b.Name ,' (',b.NickName,')') as stamp_uid,b.id as uid,"
        . " if(SUM(c.late)<=0,'',SUM(c.late)) as stamp_late,"
        . " if(SUM(c.overtime)<=0,'',SUM(c.overtime)) as stamp_ot,"
        . " if(SUM(c.before_time)<=0,'',SUM(c.before_time)) as stamp_before,"
        . " sum(c.hours) as work_hours"
        . " FROM bz_timestamp.t_stamp a"
        . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8)=CONVERT(a.stamp_uid USING utf8)"
        . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id=a.stamp_id"
        . " WHERE a.is_delete=0";
if (!empty($team)) {
    $sql .= " AND b.Team ='$team'";
}
if (!empty($staffId)) {
    $sql .= " AND b.id ='$staffId'";
}
if (!empty($office)){
    $sql .= " AND b.Office ='$office'";
}
if ($checked === 'true') {
    $sql .= " AND DATE_FORMAT(a.stamp_date,'%Y-%m') ='" . date('Y-m') . "'";
} else {
    $sql .= " AND a.stamp_date BETWEEN '$fdate' AND '$tdate'";
}
$sql .= " GROUP BY a.stamp_uid ORDER BY b.Name ASC";

$result = $mysqli->query($sql) or die('x1->' . $mysqli->error);

$rowIndex = $intRow + 1;
while ($row = $result->fetch_assoc()) {

    $uid = $row['uid'];

    $objPHPExcel->getActiveSheet()->getRowDimension($rowIndex)->setRowHeight(20);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $rowIndex . ':E' . $rowIndex);
    $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A' . $rowIndex, "")
            ->setCellValue('B' . $rowIndex, $row['stamp_uid'])
            ->setCellValue('F' . $rowIndex, minutesToHours($row['work_hours']))
            ->setCellValue('G' . $rowIndex, $row['stamp_late'])
            ->setCellValue('H' . $rowIndex, $row['stamp_ot'])
            ->setCellValue('I' . $rowIndex, $row['stamp_before']);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $rowIndex . ':L' . $rowIndex)
            ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    foreach (range('A', 'L') as $columnID) {
        $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex)->applyFromArray($styleHeaderGroupUser);
    }
    //$objPHPExcel->getActiveSheet()->getStyle('B' . $rowIndex)->applyFromArray($styleCenter);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $rowIndex)->applyFromArray($styleCenter);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $rowIndex)->applyFromArray($styleCenter);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $rowIndex)->applyFromArray($styleCenter);
    $objPHPExcel->getActiveSheet()->getStyle('G' . $rowIndex)->applyFromArray($styleColorRed);
    $objPHPExcel->getActiveSheet()->getStyle('G' . $rowIndex)->applyFromArray($styleCenter);
    $objPHPExcel->getActiveSheet()->getStyle('H' . $rowIndex)->applyFromArray($styleColorGreen);
    $objPHPExcel->getActiveSheet()->getStyle('I' . $rowIndex)->applyFromArray($styleColorRed);
    /*
     * ======================================================================
     */
    $rowIndex2 = $rowIndex + 1;
    $startDate = $fdate;
    $endDate = $tdate;
    while (strtotime($startDate) <= strtotime($endDate)) {

        $sql = "SELECT a.stamp_id, DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,DATE_FORMAT(a.stamp_date,'%a') as dText,"
                . " concat(b.Name ,' (',b.NickName,')') as stamp_uid,"
                . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start,"
                . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                . " a.stamp_note,"
                . " if(c.stamp_shift=''||ISNULL(c.stamp_shift),if(g.work_shift_start='none','none',concat(g.work_shift_start,'-',g.work_shift_stop)),"
                . " if(i.work_shift_start='none','none',if(i.work_shift_start='OT','OT',concat(i.work_shift_start,'-',i.work_shift_stop)))) as work_shift_id,"
                . " c.late as stamp_late, "
                . " c.overtime as stamp_ot, "
                . " if(a.stamp_start_ip = a.stamp_stop_ip, a.stamp_start_ip, if(a.stamp_stop_ip!='', concat(a.stamp_start_ip, ' - ', a.stamp_stop_ip), a.stamp_start_ip)) as stamp_ip"
                . ", c.before_time as stamp_before,f.reason_name as reason_id,c.hours as work_hours"
                . " FROM bz_timestamp.t_stamp a"
                . " INNER JOIN baezenic_people.t_people b ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
                . " LEFT JOIN bz_timestamp.t_late c ON c.stamp_id = a.stamp_id"
                . " LEFT JOIN bz_timestamp.t_reason f ON f.reason_id = a.reason_id"
                . " LEFT JOIN bz_timestamp.t_work_shift g ON g.work_shift_id = a.work_shift_id"
                . " LEFT JOIN bz_timestamp.t_work_shift i ON i.work_shift_id = c.stamp_shift"
                . " WHERE a.is_delete = 0"
                . " AND b.id = '$uid'";
        $sql .= " AND a.stamp_date = '$startDate' ";
        $sql .= " ORDER BY a.stamp_date ASC";

        $query = $mysqli->query($sql) or die('xx' . $mysqli->error);
        $numRows = $query->num_rows;
        if ($numRows > 0) {
            $fetch = $query->fetch_assoc();

//            $tis620 = iconv("utf-8", "tis-620", $fetch['stamp_note']);//$fetch['stamp_note']
//            $utf8 = iconv("tis-620", "utf-8", $tis620);
//            $stamp_note = $utf8;

            $stamp_note = $fetch['stamp_note'];

            $objPHPExcel->getActiveSheet()->getRowDimension($rowIndex2)->setRowHeight(16);
            $objPHPExcel->setActiveSheetIndex()
                    ->setCellValue('B' . $rowIndex2, $fetch['stamp_date'])
                    ->setCellValue('C' . $rowIndex2, $fetch['stamp_start'])
                    ->setCellValue('D' . $rowIndex2, $fetch['stamp_stop'])
                    ->setCellValue('E' . $rowIndex2, $fetch['work_shift_id'])
                    ->setCellValue('F' . $rowIndex2, minutesToHours($fetch['work_hours']))
                    ->setCellValue('G' . $rowIndex2, $fetch['stamp_late'])
                    ->setCellValue('H' . $rowIndex2, $fetch['stamp_ot'])
                    ->setCellValue('I' . $rowIndex2, $fetch['stamp_before'])
                    ->setCellValue('J' . $rowIndex2, $fetch['reason_id'])
                    ->setCellValue('K' . $rowIndex2, $stamp_note)
                    ->setCellValue('L' . $rowIndex2, $fetch['stamp_ip']);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowIndex2)->applyFromArray($styleCenter);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $rowIndex2)->applyFromArray($styleCenter);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $rowIndex2)->applyFromArray($styleCenter);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $rowIndex2)->applyFromArray($styleCenter);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $rowIndex2)->applyFromArray($styleColorRed);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $rowIndex2)->applyFromArray($styleCenter);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $rowIndex2)->applyFromArray($styleColorGreen);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $rowIndex2)->applyFromArray($styleColorRed);

            if ($fetch['dText'] == 'Sun') {

                foreach (range('B', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex2)->applyFromArray($styleColorRed);
                    $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex2)->applyFromArray($styleBorderTop);
                }
            }
        } else {

            $objPHPExcel->getActiveSheet()->getRowDimension($rowIndex2)->setRowHeight(16);
            $objPHPExcel->setActiveSheetIndex()
                    ->setCellValue('B' . $rowIndex2, date("d/m/Y", strtotime($startDate)));

            if (date("D", strtotime($startDate)) == 'Sun') {
                foreach (range('B', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex2)->applyFromArray($styleColorRed);
                    $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex2)->applyFromArray($styleBorderButtom);
                }
            } else if (date("D", strtotime($startDate)) == 'Sat') {

                foreach (range('B', 'L') as $columnID) {
                    $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex2)->applyFromArray($styleColorRed);
                    $objPHPExcel->getActiveSheet()->getStyle($columnID . $rowIndex2)->applyFromArray($styleBorderTop);
                }
            }
            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowIndex2)->applyFromArray($styleCenter);
        }
        ++$rowIndex2;

        $startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
    }


    $rowIndex = $rowIndex2;
    //setBreak
    $objPHPExcel->getActiveSheet()->setBreak('A' . $rowIndex, PHPExcel_Worksheet::BREAK_ROW);

    ++$rowIndex;
}


$objPHPExcel->getActiveSheet()->setTitle('Summary Report');

$objPHPExcel->setActiveSheetIndex(0);
$callStartTime = microtime(true);
$filename = "Summary Report " . date('F d,Y') . ".xlsx";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$name = str_replace(__FILE__, __DIR__ . '\\ExcelFile\\' . $filename, __FILE__);

$result = $objWriter->save(str_replace('\\', '/', $name));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

if ($result) {
    $response = array(
        "success" => true,
        "filename" => $filename
    );
} else {
    $response = array(
        "success" => FALSE,
        "filename" => $filename
    );
}
echo json_encode($response);

function minutesToHours($minutes) {
    $hours = (int) ($minutes / 60);
    $minutes -= $hours * 60;
    if (sprintf("%d.%02.0f", $hours, $minutes) == '0.00' || sprintf("%d.%02.0f", $hours, $minutes) == '-1.00') {
        $h = '';
    } else {
        $h = sprintf("%d.%02.0f", $hours, $minutes);
    }
    return $h;
}

exit;
