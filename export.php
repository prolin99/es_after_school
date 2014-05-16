<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-05-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/

include_once "header.php";

include_once "../tadtools/PHPExcel.php";
require_once '../tadtools/PHPExcel/IOFactory.php';    
/*-----------function區--------------*/

function  newpage($page ,$title ){
	global $objPHPExcel  ;

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($page );  //設定預設顯示的工作表
	$objActSheet = $objPHPExcel->getActiveSheet(); //指定預設工作表為 $objActSheet
	$objActSheet->setTitle($title);  //設定標題	

  	//設定框線
	$objBorder=$objActSheet->getDefaultStyle()->getBorders();
	$objBorder->getBottom()
          	->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
          	->getColor()->setRGB('000000'); 
	$objActSheet->getDefaultRowDimension()->setRowHeight(15);

	$row= 1 ;
	$objPHPExcel->setActiveSheetIndex($page)->getRowDimension(1)->setRowHeight(30);
	$objPHPExcel->setActiveSheetIndex($page)->getColumnDimension('G')->setWidth(50);
       //標題行
      	$objPHPExcel->setActiveSheetIndex($page) 
            ->setCellValue('A' . $row, 'NO.')
            ->setCellValue('B' . $row, '年級')
            ->setCellValue('C' . $row, '時段')  
            ->setCellValue('D' . $row, '班別')  
            ->setCellValue('E' . $row, '原班')
            ->setCellValue('F' . $row, '學生姓名')           ;	
            $col = 'F' ;
	    	for ($i = 1; $i <=20 ; $i++) {	
			$col++ ;
			$col_str =$col . '1' ;
			$objPHPExcel->setActiveSheetIndex($page)->setCellValue($col_str, $i. "\n   /") ;
			$objPHPExcel->setActiveSheetIndex($page)->getColumnDimension($col)->setWidth(5);
		}            
		 
 
}


/*-----------執行動作判斷區----------*/
if  ($_GET['mid']) {
	$month_id =$_GET['mid'] ;
 
 	//取得各班別資料
	$grade_data=get_month_grade($month_id) ;
 	//取得報名學生資料
 	$sign_studs = get_as_signs($month_id , $class_id  , $grade_data ,1) ;


 	$objPHPExcel = new PHPExcel();
 	

  	$page =-1 ;
 	$old_grade='' ;
        //資料區
        
        foreach ( $sign_studs  as $stud_id => $stud )  {
		//依年級班別分頁
 		$grade_grade = $stud['grade_year'].$AS_SET['class_set'][$stud['class_id']] ;
 
 		if  ($old_grade <>$grade_grade) {
 
			newpage($page+1 ,$grade_grade ) ;
			$old_grade = $grade_grade ;
			$page++ ;
			$row=1 ;
 		}	
        	$row++ ;
        	
      	
       		$objPHPExcel->setActiveSheetIndex($page)
            		->setCellValue('A'.$row,$row-1)
            		->setCellValue('B'.$row , $stud['grade_year'])
            		->setCellValue('C'.$row, $AS_SET['time'][$stud['time_mode']])
            		->setCellValue('D'.$row, $AS_SET['class_set'][$stud['class_id']])
            		->setCellValue('E'.$row ,$stud['class_id_base'])
            		->setCellValue('F'.$row, $stud['stud_name'])
            		;
  
	} 	

 	
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename=after'.date("mdHi").'.xls' );
	header('Cache-Control: max-age=0');

	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;		 	
}	
?>