<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

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

$head = "LOG STOP";
$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', $head);
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(17);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderCenter);
$intRow = 2;
$objPHPExcel->setActiveSheetIndex()
        ->setCellValue('A' . $intRow, "UID")
        ->setCellValue('B' . $intRow, "Name")
        ->setCellValue('C' . $intRow, "Stop Time")
        ->setCellValue('D' . $intRow, "IP");
foreach (range('A', 'D') as $columnID) {
    $objPHPExcel->getActiveSheet()->getStyle($columnID . $intRow)->applyFromArray($styleHeaderCenter);
}
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(30);
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(14);


$intRow = $intRow + 1;

$logStopFile = '../../../logfile/logStop.txt';
$fh = fopen($logStopFile, 'r');
while ($line = fgets($fh)) {

    $content = explode(",", $line);
    $objPHPExcel->setActiveSheetIndex()
            ->setCellValueExplicit('A' . $intRow, $content[1], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('B' . $intRow, $content[0])
            ->setCellValue('C' . $intRow, $content[2])
            ->setCellValue('D' . $intRow, trim($content[3]));
    //$objPHPExcel->getActiveSheet()->setCellValueExplicit('A1', '0029', PHPExcel_Cell_DataType::TYPE_STRING);

    ++$intRow;
}
fclose($fh);



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('LOG');

//// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="log-stop.xls"');
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


