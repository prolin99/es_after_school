<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-05-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include_once "header_admin.php";

include_once "../function.php";

include_once "../../tadtools/PHPExcel.php";
require_once '../../tadtools/PHPExcel/IOFactory.php';    
/*-----------function區--------------*/
//取得中文班名
$class_list_c = es_class_name_list_c('long')  ;  

/*-----------執行動作判斷區----------*/
if  ($_GET['mid']) {
	$month_id =$_GET['mid'] ;
	//echo $month_id  ;
	
	//取得各班別資料
	$grade_data=get_month_grade($month_id) ;
	
 	//取得報名學生資料
 	$sign_studs = get_as_signs($month_id , $class_id  , $grade_data ,1) ;


 	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);  //設定預設顯示的工作表
	$objActSheet = $objPHPExcel->getActiveSheet(); //指定預設工作表為 $objActSheet
	$objActSheet->setTitle("課後照顧");  //設定標題	
  	//設定框線
	$objBorder=$objActSheet->getDefaultStyle()->getBorders();
	$objBorder->getBottom()
          	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
          	->getColor()->setRGB('000000'); 
	$objActSheet->getDefaultRowDimension()->setRowHeight(15);

	
	$row= 1 ;
       //標題行
      	$objPHPExcel->setActiveSheetIndex(0) 
            ->setCellValue('A' . $row, 'NO.')
            ->setCellValue('B' . $row, '年級')
            ->setCellValue('C' . $row, '原班')
            ->setCellValue('D' . $row, '學生姓名')
            ->setCellValue('E' . $row, '班別') 
            ->setCellValue('F' . $row, '時段') 
            ->setCellValue('G' . $row, '減免') 
             ->setCellValue('H' . $row, '費用') 
             ->setCellValue('I' . $row, '備註')            ;
            

 	
        //資料區
        foreach ( $sign_studs  as $stud_id => $stud )  {
        	$row++ ;
      	
       		$objPHPExcel->setActiveSheetIndex(0)
            		->setCellValue('A'.$row,$row-1)
            		->setCellValue('B'.$row , $stud['grade_year'])
            		->setCellValue('C'.$row ,$class_list_c[$stud['class_id_base']])
            		->setCellValue('D'.$row, $stud['stud_name'])
            		->setCellValue('E'.$row, $AS_SET['class_set'][$stud['class_id']])
            		->setCellValue('F'.$row, $AS_SET['time'][$stud['time_mode']])
            		->setCellValue('G'.$row, $AS_SET['decrease_set'][$stud['spec']])
            		->setCellValue('H'.$row, $stud['pay_sum'])
            		->setCellValue('I'.$row, $stud['ps'])
            		;
  
	} 	

 	
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename=after'.date("mdHi").'.xlsx' );
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;		 	
}	
?>