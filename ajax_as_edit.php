<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-05-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/


include_once "header.php";


/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
	list($div,$id,$class_id, $row_i)  = preg_split('/_/',$_GET['id'] ) ;
	if ($id) {


 		$sql = " select  * FROM "  . $xoopsDB->prefix("afdb_sign") .    " where id = '$id'  and class_id_base = '$class_id'     	   " ;
 		//echo $sql ;
	     	$result = $xoopsDB->queryF($sql) ;
	     	while($row=$xoopsDB->fetchArray($result)){
			$data=$row ;
		}

		$main =" <form  id='form_{$data['id']}_{$data['class_id_base']}'   class='form-actions'> "  ;

		//可改年段
		$main .= "<span class='col-md-1 col-xs-2'><select  class='form-control'  name='grade_year' title='移動年段(併班情形下使用)'  >" ;
		foreach ($AS_SET['grade_set']  as $k =>$v) {
		 if ( $data['grade_year'] == $v )
		   $main .= "<option value='$v' label='$v'  selected>$v</option> " ;
		 else
		   $main .= "<option value='$v' label='$v'>$v</option> " ;
		}
		$main .= "</select></span>" ;


		$main.= "
		<div class='col-md-1 col-xs-1'> {$data['class_id_base']}</div>
		<div class='col-md-1 col-xs-2'>{$data['stud_name']}</div> "  ;

		$main .= "<span class='col-md-1 col-xs-3'><select  class='form-control'  name='class_id_set' title='指定班別(限多班情形下)'  >" ;
		foreach ($AS_SET['class_set']  as $k =>$v) {
		 if ($data['class_id'] ==$k )
		   $main .= "<option value='$k' label='$v'  selected>$v</option> " ;
		 else
		   $main .= "<option value='$k' label='$v'>$v</option> " ;
		}
		$main .= "</select></span>" ;

		$main .= "<span class='col-md-2 col-xs-4'> <select class='form-control' name='time_mode' title='調整畤段' >   " ;
		foreach ($AS_SET['time']  as $k =>$v) {
		 if ($data['time_mode'] ==$k )
		   $main .= "<option value='$k' label='$v'  selected>$v</option> " ;
		 else
		   $main .= "<option value='$k' label='$v'>$v</option> " ;
		}
		$main .= "</select></span>" ;


		$main .= "<span class='col-md-2 col-xs-4'> <select class='form-control' name='spec'  title='修改身份'>" ;
		foreach ($AS_SET['decrease_set']  as $k =>$v) {
		 if ($data['spec'] ==$k )
		   $main .= "<option value='$k' label='$v'  selected>$v</option> " ;
		 else
		   $main .= "<option value='$k' label='$v'>$v</option> " ;
		}
		$main .= "</select></span>" ;
		$main .= "<span class='col-md-2 col-xs-4'><input class='form-control'  placeholder='備註說明文字' value='{$data['ps']}' name='ps' title='備註說明文字'  ></input></span>" ;

		$main .="<input type='hidden' value='{$data['id']}' name='id'></input>" ;
		$main .="<input type='hidden' value='$row_i' name='row_i'></input>" ;
		$main .= "<span class='save' title='儲存'><span class='glyphicon glyphicon-ok'></span></span>  " ;







	echo $main ;
}


?>
