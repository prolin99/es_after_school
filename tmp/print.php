<?php

// $Id: signin.php,v 1.1.2.1 2004/10/22 15:26:46 prolin Exp $

include "config.php";


sfs_check();
$session_tea_sn =  $_SESSION['session_tea_sn'] ;
$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'] ;
//取得班級
$data['class_name_arr'] = class_base() ;




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

}	

//取得學生班別
$sql=" select  grade_year ,class_id  from  afdb_sign where  month_id = '$month_id'  group by grade_year ,class_id 	 ";
 
$recordSet = $CONN->Execute($sql);
if  ($recordSet ) {
	while ($row = $recordSet->FetchRow() ) {
		$key = $row['grade_year'] *10+$row['class_id'] ;
		$v =  $row['grade_year'] . $DEF['SEL_CLASS'][$row['class_id']] ;
 		$data['now_class']= $key ;
 		$data['afclass'][$key] = $v ;
	} 
}

if ($_POST['sel_class'] ) 
	$data['now_class'] = $_POST['sel_class']  ;
	
if ($_POST['Submit1']) 
  $act =1 ;
  
//取得全部學生名冊--------------------------------
$g= substr($data['now_class'],0,1) ;
$c= substr($data['now_class'],1,1) ;

 $sql=" select  * from  afdb_sign where  month_id = '$month_id'  and grade_year = $g and  class_id =$c  order by class_id, time_mode,class_id_base	 ";


$recordSet = $CONN->Execute($sql);
if  ($recordSet ) {
	while ($row = $recordSet->FetchRow() ) {
 		$user[] = $row ;
	} 
 
}		
 




//樣版------------------------------------------------------------------
$smarty->assign("SFS_TEMPLATE",$SFS_TEMPLATE);
$smarty->assign("module_name","課後照顧");

$smarty->assign("SFS_MENU",$school_menu_p);
$smarty->assign("DEF",$DEF);
$smarty->assign("user",$user);
$smarty->assign("data",$data);
$smarty->assign("act",$act);
/*
$smarty->assign("class_list",$class_list);
$smarty->assign("edit_id",$edit_id);
*/
	$smarty->display("print.htm");
?>