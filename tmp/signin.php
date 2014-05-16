<?php

// $Id: signin.php,v 1.1.2.1 2004/10/22 15:26:46 prolin Exp $

include "config.php";


sfs_check();

$session_tea_sn =  $_SESSION['session_tea_sn'] ;
$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'] ;
//取得班級
$data['class_name_arr'] = class_base() ;



if ( checkid($SCRIPT_FILENAME,1)){
	if ($_POST[OCLASS_ID] )
		$_POST['sel_class']=$_POST['OCLASS_ID'];
	if (! $_POST['sel_class'])  
	$_POST['sel_class']='101' ;
	$data['sel_class'] = $_POST['sel_class'] ;
	$admin=1 ;
	$data['admin']= 1 ;
	$data['cando'] = 1 ;
} else {
 
	//取得教師所上年級、班級
	$query =" select class_num  from teacher_post  where teacher_sn  ='$session_tea_sn'  ";
//	echo $query ;
	$result =  $CONN->Execute($query) or user_error("讀取失敗！<br>$query",256) ; 
	$row = $result->FetchRow();
	$data['sel_class'] = $row["class_num"];
  	$class_num= $row["class_num"];
	if ($class_num <= 0)   {
		head("課後照顧") ;
		print_menu($school_menu_p);
		echo "非級任教師無法使用！" ;
		foot();
	   	exit ;
	}	
 
}	

if  ($data['sel_class'] <401) 
    $data['set_time_def'] =  0 ;
else 
	$data['set_time_def'] = 1;


//取得學生姓名
$data['class_stud']= get_class_data($data['sel_class']) ;

//取得最近的期別 --------------------------------------------------------------------------------
$sql=" select  * from  afdb_month order by motn_id DESC  LIMIT 0 , 1 ";

$recordSet = $CONN->Execute($sql);
if  ($recordSet ) {
	while ($row = $recordSet->FetchRow() ) {
		$data['month_id'] = $row['motn_id'] ;  
		$data['deadline'] = $row['deadline'] ;  
		$data['month_doc'] = $row['monthdoc'] ;  
	} 
	$month_id = $data['month_id']  ;  
 	
	if (date("Y-m-d")<=$data['deadline']) 
	$data['cando'] = 1 ;
 
}	
//增加學生---------------------------------------------------------------------------------------------------
if ($_POST['ADD']  and $_POST['sel_stud'] ) {
	//檢查是否存在
	
	//新增一筆
	$grade_year = substr($_POST['OCLASS_ID'] ,0,1) ;
	$sqlstr = " insert into afdb_sign( id , month_id , grade_year , class_id , class_id_base , stud_name	  , stud_sex , time_mode, spec , ps )
                       	   values ('' , '$month_id'  ,  $grade_year  ,  $_POST[class_id_set] , $_POST[OCLASS_ID]  ,'$_POST[sel_stud] ' ,0 , $_POST[time_mode] ,'$_POST[spec]' , '$_POST[ps]'  ) " ;
           
              $CONN->Execute($sqlstr);
  	
}	


//取消報名  --------------------------------------------------
if ($_GET['do']=='del' and ($_GET['id'] )  and ($data['cando']) ) {
	if ($admin) 
		$sqlstr = " delete from afdb_sign where id=  '$_GET[id]'    " ;
	else 
		$sqlstr = " delete from afdb_sign where id=  '$_GET[id]' and class_id_base= '$data[sel_class]'   " ;
      	$CONN->Execute($sqlstr);    
}	

if ($_GET['do']=='edit' and ($_GET['id'] ) ) {
 	$data['editid'] = $_GET['id'] ;
}	


//修改資料 ------------------------------------------------------
if ($_POST['Edit']=='修改' ) {
$sqlstr = " update   afdb_sign set
			class_id  ='$_POST[class_id_set]' ,
			time_mode='$_POST[time_mode]'  ,
			spec='$_POST[spec]' ,
			ps='$_POST[ps]' 
                      	where  id = '$_POST[oid]' " ;  
 
           $CONN->Execute($sqlstr);     	
}

//取得全部學生名冊--------------------------------
if ($admin) 
	$sql=" select  * from  afdb_sign where  month_id = '$month_id'  order by class_id_base	 ";
else 
	$sql=" select  * from  afdb_sign where  month_id = '$month_id'  and   class_id_base = '$data[sel_class]'   order by class_id_base	 ";

$recordSet = $CONN->Execute($sql);
if  ($recordSet ) {
	while ($row = $recordSet->FetchRow() ) {
 		$user[] = $row ;
	} 
 
}		
 
//取得期別的收費
$sql=" select  * from  afdb_month where  motn_id  =  '$month_id'   ";
//echo $sql ;
 
$recordSet = $CONN->Execute($sql);
 
	while ($row = $recordSet->FetchRow() ) {
		$y= $row['grade_year'] ;  
		$t= $row['time_mode'] ;  
 	 	$data[pay_sum][$y][$t] =  $row['pay_sum']  ;
	} 
	

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
 	
/*	
    	$filename =$month_id  . ".csv" ;
 
	     header("Content-disposition: filename=$filename");
	     header("Content-type: application/octetstream");	  
 
    	header("Pragma: no-cache");
	  header("Expires: 0"); 
*/	  
  }

//樣版------------------------------------------------------------------
$smarty->assign("SFS_TEMPLATE",$SFS_TEMPLATE);
$smarty->assign("module_name","課後照顧");

$smarty->assign("SFS_MENU",$school_menu_p);
$smarty->assign("DEF",$DEF);
$smarty->assign("user",$user);
$smarty->assign("data",$data);
/*
$smarty->assign("class_list",$class_list);
$smarty->assign("edit_id",$edit_id);
*/
if ($_POST['EXCEL'])  {
    	$smarty->display("csv.htm");
}else{	
	$smarty->display("signin.htm");
}
?>