<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/

include_once "header.php";
include_once XOOPS_ROOT_PATH."/header.php";

/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
if  ($_POST['id']     ) {
	$sql = " UPDATE   "  . $xoopsDB->prefix("afdb_sign") .  
		" SET `class_id`='{$_POST['class_id_set']}' , `time_mode`='{$_POST['time_mode']}',`spec`='{$_POST['spec']}',`ps`='{$_POST['ps']}' WHERE id = '{$_POST['id']}'  " ; 
	
 	$result = $xoopsDB->query($sql) ;
 
 
		$sql = " select  * FROM "  . $xoopsDB->prefix("afdb_sign") .    " where id = '{$_POST['id']}'   	   " ; 
 		//echo $sql ;
	     	$result = $xoopsDB->queryF($sql) ;
	     	while($row=$xoopsDB->fetchArray($result)){
			$data=$row ;
		}
		$main ="   
        <span class='span1'><span class='badge badge-success'>{$_POST['row_i']}</span>{$data['grade_year']} </span>
        <span class='span1'> {$data['class_id_base']} </span>
        <span class='span2'><span class='edit' ><i class='icon-pencil'></i></span>  {$data['stud_name']} <span class='del' ><i class='icon-trash'></i></span> </span>
        <span class='span1'>" . $AS_SET['class_set'][$data['class_id']]  ." </span>
        <span class='span1'>". $AS_SET['time'][$data['time_mode']] ." </span>
        <span class='span2'>" .$AS_SET['decrease_set'][$data['spec']] ."  </span>
        <span class='span1'>" . $datapay_sum[$grade][$time] 	." </span>	
 	<span class='span2'> {$data['ps']}  </span>
        
        " ;

	echo $main ;
 
} 
 