<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/

include_once "header.php";
//include_once XOOPS_ROOT_PATH."/header.php";

/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
if  ($_POST['id']     ) {
	$sql = " UPDATE   "  . $xoopsDB->prefix("afdb_sign") .
		" SET `grade_year`='{$_POST['grade_year']}'  , `class_id`='{$_POST['class_id_set']}' , `time_mode`='{$_POST['time_mode']}',`spec`='{$_POST['spec']}',`ps`='{$_POST['ps']}' WHERE id = '{$_POST['id']}'  " ;

 	$result = $xoopsDB->query($sql) ;


		$sql = " select  * FROM "  . $xoopsDB->prefix("afdb_sign") .    " where id = '{$_POST['id']}'   	   " ;
 		//echo $sql ;
	     	$result = $xoopsDB->queryF($sql) ;
	     	while($row=$xoopsDB->fetchArray($result)){
			$data=$row ;
		}

			 	$main ="
				 <span class='col-md-1 col-xs-1'><span class='badge badge-success'>{$_POST['row_i']}</span>{$data['grade_year']} </span>
				 <span class='col-md-1 col-xs-1'> {$data['class_id_base']} </span>
				 <span class='col-md-2  col-xs-2'><span class='edit' ><span class='glyphicon glyphicon-pencil' title='修改'></span></span>  {$data['stud_name']}
				 <span class='del' ><span class='glyphicon glyphicon-trash' title='刪除'></span></span> </span>
				 <span class='col-md-1 col-xs-1'>" . $AS_SET['class_set'][$data['class_id']]  ." </span>
				 <span class='col-md-1 col-xs-1'>". $AS_SET['time'][$data['time_mode']] ." </span>
				 <span class='col-md-2  col-xs-2'>" .$AS_SET['decrease_set'][$data['spec']] ."  </span>
				 <span class='col-md-1 col-xs-1'>" . $datapay_sum[$grade][$time] 	." </span>
				 <span class='col-md-2  col-xs-2'> {$data['ps']}  </span>
				 " ;


	echo $main ;

}
