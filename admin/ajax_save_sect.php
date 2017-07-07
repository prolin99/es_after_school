<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/

include_once "header.php";
include_once "../function.php";

/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
if  ($_GET['month_id']  and $_GET['id']     ) {
	list($sect_tag , $grade, $time_mode)= preg_split( "/[_]/" , $_GET['id'] ) ;

	//更新節數
	$sql = " UPDATE   "  . $xoopsDB->prefix("afdb_grade") .
		" SET `sect_num`='{$_GET['input']}'   WHERE month_id = '{$_GET['month_id']}'  and grade_year='$grade'  and `time_mode`='$_$time_mode'  " ;

 	$result = $xoopsDB->queryF($sql) ;
 	//echo $sql ;

}
