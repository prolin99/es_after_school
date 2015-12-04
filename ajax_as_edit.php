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

     if ($_SESSION['bootstrap'] == '3') {
					 $main =" <form  id='form_{$data['id']}_{$data['class_id_base']}'   class='form-actions'>
					 <div class='col-md-1 col-xs-1'>{$data['grade_year']}</div>
					 <div class='col-md-1 col-xs-1'> {$data['class_id_base']}</div>
					 <div class='col-md-1 col-xs-2'>{$data['stud_name']}</div> "  ;

					 $main .= "<span class='col-md-1 col-xs-3'><select  class='form-control'  name='class_id_set'>" ;
					 foreach ($AS_SET['class_set']  as $k =>$v) {
					  if ($data['class_id'] ==$k )
					    $main .= "<option value='$k' label='$v'  selected>$v</option> " ;
					  else
					    $main .= "<option value='$k' label='$v'>$v</option> " ;
					 }
					 $main .= "</select></span>" ;

					 $main .= "<span class='col-md-2 col-xs-4'> <select class='form-control' name='time_mode'>" ;
					 foreach ($AS_SET['time']  as $k =>$v) {
					  if ($data['time_mode'] ==$k )
					    $main .= "<option value='$k' label='$v'  selected>$v</option> " ;
					  else
					    $main .= "<option value='$k' label='$v'>$v</option> " ;
					 }
					 $main .= "</select></span>" ;


					 $main .= "<span class='col-md-2 col-xs-4'> <select class='form-control' name='spec'>" ;
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

     }else {
		       $main =" <form  id='form_{$data['id']}_{$data['class_id_base']}'   class='form-actions'>
		       <div class='span1'>{$data['grade_year']}</div>
		       <div class='span1'> {$data['class_id_base']}</div>
		       <div class='span1'>{$data['stud_name']}</div> "  ;

		       $main .= "時段：<select class='span2' name='time_mode'>" ;
		       foreach ($AS_SET['time']  as $k =>$v) {
		       	if ($data['time_mode'] ==$k )
		       		$main .= "<option value='$k' label='$v'  selected>$v</option> " ;
		       	else
		       		$main .= "<option value='$k' label='$v'>$v</option> " ;
		       }
		       $main .= "</select>" ;
		       $main .= "班別：<select class='span1' name='class_id_set'>" ;
		       foreach ($AS_SET['class_set']  as $k =>$v) {
		       	if ($data['class_id'] ==$k )
		       		$main .= "<option value='$k' label='$v'  selected>$v</option> " ;
		       	else
		       		$main .= "<option value='$k' label='$v'>$v</option> " ;
		       }
		       $main .= "</select>" ;
		       $main .= "減免：<select class='span2' name='spec'>" ;
		       foreach ($AS_SET['decrease_set']  as $k =>$v) {
		       	if ($data['spec'] ==$k )
		       		$main .= "<option value='$k' label='$v'  selected>$v</option> " ;
		       	else
		       		$main .= "<option value='$k' label='$v'>$v</option> " ;
		       }
		       $main .= "</select>" ;
		       $main .= "<input class='span2' placeholder='備註說明文字' value='{$data['ps']}' name='ps'></input>" ;

		       $main .="<input type='hidden' value='{$data['id']}' name='id'></input>" ;
		       $main .="<input type='hidden' value='$row_i' name='row_i'></input>" ;
		       $main .= "<span class='save' title='儲存'><i class='icon-ok'></i></span>  " ;
     }



	echo $main ;
}


?>
