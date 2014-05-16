<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/


include_once "header.php";
include_once XOOPS_ROOT_PATH."/header.php";
//匯出	
  if ($_POST['EXCEL'] ) {
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */
	require_once '../../include/libs/PHPExcel.php';
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("syps")
				->setLastModifiedBy("syps")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
				->setKeywords("office 2007 openxml php")
				->setCategory("Test result file");



      //標題行
      	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序號')
            ->setCellValue('B1', '年級')
            ->setCellValue('C1', '原班')
            ->setCellValue('D1', '姓名')
            ->setCellValue('E1', '班別')	
            ->setCellValue('F1', '時段')	
            ->setCellValue('G1', '減免')	
            ->setCellValue('H1', '費用') 	
            ->setCellValue('I1', '備註');	
  //加入各列、欄
    $col = 'A';
    $rowNumber= 2 ;            
    $row_i= 1 ;
  foreach ( $user as $list) {
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' .$rowNumber , $row_i++)
            ->setCellValue('B'.$rowNumber, $list[grade_year])
            ->setCellValue('C'.$rowNumber, $data[class_name_arr][$list[class_id_base]])
            ->setCellValue('D'.$rowNumber, $list[stud_name])
            ->setCellValue('E'.$rowNumber, $DEF[SEL_CLASS][$list[class_id]] ) 
            ->setCellValue('F'.$rowNumber, $DEF[time_mode][$list[time_mode]])
            ->setCellValue('G'.$rowNumber, $DEF[spec_array][$list[spec]])
            ->setCellValue('H'.$rowNumber, $data[pay_sum][$list[grade_year]][$list[time_mode]])
            ->setCellValue('I'.$rowNumber, $list[ps]);
 	$rowNumber++;
  }	
  
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('student_list');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	//$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="afterclass_data.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;	
 	
 
  }

?>