<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2019-10-25
// $Id:$
//配合郵局扣款的格式
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include_once "header.php";
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
 	$sign_studs = get_as_signs_join_charge($month_id , $class_id  , $grade_data ,1) ;


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
       //標題行 年級	班級代號	座號	學生姓名	繳費總額(整數)	轉帳戶名	轉帳戶身份證編號	存款別(P/G)	立帳局號	存簿帳號 	劃撥帳號	現金繳費(設為1)

      	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, '年級')
            ->setCellValue('B' . $row, '班級代號')
            ->setCellValue('C' . $row, '座號')
            ->setCellValue('D' . $row, '學生姓名')
            ->setCellValue('E' . $row, '繳費總額')
            ->setCellValue('F' . $row, '轉帳戶名')
            ->setCellValue('G' . $row, '轉帳戶身份證編號')
            ->setCellValue('H' . $row, '存款別(P/G)')
            ->setCellValue('I' . $row, '立帳局號')
			->setCellValue('J' . $row, '存簿帳號')
			->setCellValue('K' . $row, '劃撥帳號')
			->setCellValue('L' . $row, '現金繳費(0/1)')
			->setCellValue('M' . $row, '班年段')
			->setCellValue('N' . $row, '班別')
            ->setCellValue('O' . $row, '時段')
            ->setCellValue('P' . $row, '減免')
             ->setCellValue('Q' . $row, '費用')
             ->setCellValue('R' . $row, '備註')
			;



        //資料區
        foreach ( $sign_studs  as $stud_id => $stud )  {
        	$row++ ;

			$n_grade_id = substr($stud['class_id_base'] ,0,-2) ;
			$n_class_id = $stud['class_id_base'] -$n_grade_id*100 ;

       		$objPHPExcel->setActiveSheetIndex(0)
            		->setCellValue('A'.$row,$n_grade_id)
            		->setCellValue('B'.$row ,$n_class_id)
            		->setCellValue('C'.$row ,$stud['class_sit_num']  )
            		->setCellValue('D'.$row,$stud['oname'])
            		->setCellValue('E'.$row,$stud['pay_sum'])
            		->setCellValue('F'.$row, $stud['acc_name'])
            		->setCellValue('G'.$row, $stud['acc_person_id'])
            		->setCellValue('H'.$row, $stud['acc_mode'])
            		->setCellValue('I'.$row, $stud['acc_b_id'])
					->setCellValue('J'.$row, $stud['acc_id'])
					->setCellValue('K'.$row, $stud['acc_g_id'])
					->setCellValue('L'.$row, '')
 					->setCellValue('M'.$row , $stud['grade_year'] )
					->setCellValue('N'.$row, $AS_SET['class_set'][$stud['class_id']])
            		->setCellValue('O'.$row, $AS_SET['time'][$stud['time_mode']])
            		->setCellValue('P'.$row, $AS_SET['decrease_set'][$stud['spec']])
            		->setCellValue('Q'.$row, $stud['pay_sum'])
            		->setCellValue('R'.$row, $stud['ps'])
            		;

	}

	ob_clean();
	//header('Content-Type: application/vnd.ms-excel');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=after_acc'.date("mdHi").'.xlsx' );
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_clean();
	$objWriter->save('php://output');
	exit;
}
?>
