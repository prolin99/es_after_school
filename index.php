<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/

//$xoopsOption['template_main'] = "as_index_tpl.html";
include_once "header.php";
$xoopsOption['template_main'] = set_bootstrap("as_index_tpl.html");
include_once XOOPS_ROOT_PATH."/header.php";



/*-----------function區--------------*/
//取得中文班名
$data['class_list_c'] = es_class_name_list_c('long')  ;
$data['class_list_c_s'] = es_class_name_list_c()  ;

$data['month_data'] = get_month_list() ;
$month_id = $data['month_data']['month_id'] ;


//增加學生---------------------------------------------------------------------------------------------------
if ($_POST['ADD']  and $_POST['sel_stud'] ) {

	//檢查是否存在
	$sql = "Select *  from "  . $xoopsDB->prefix("afdb_sign") ." where  month_id ='$month_id'  and  class_id_base='{$_POST[OCLASS_ID]}' and stud_name ='{$_POST[sel_stud]} ' "  ;
	$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());
	$row=$xoopsDB->fetchArray($result) ;
	if ($row['id'] ){
		redirect_header($_SERVER['PHP_SELF'],3, $_POST[sel_stud]. ' 已加入'  );
	}else {
		//新增一筆
		$grade_year = substr($_POST['OCLASS_ID'] ,0,1) ;
		$sql = " insert into " . $xoopsDB->prefix("afdb_sign") ."  ( id , month_id , grade_year , class_id , class_id_base , stud_name	  , stud_sex , time_mode, spec , ps )
							   values ('0' , '$month_id'  ,  $grade_year  ,  $_POST[class_id_set] , $_POST[OCLASS_ID]  ,'$_POST[sel_stud] ' ,0 , $_POST[time_mode] ,'$_POST[spec]' , '$_POST[ps]'  ) " ;

		$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());
	}


}




/*-----------執行動作判斷區----------*/
//$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];


 //取得任教班級
$class_id = get_my_class_id() ;


	if ($isAdmin){
	  	//管理者可以選取多班
		$data['admin'] = true ;
		//取得班級
		if ($_POST['admin_class_id'])
			$class_id=$_POST['admin_class_id'] ;
		elseif ( !$class_id)
			$class_id= '101' ;

		//班級名稱列表
		$data['class_list']=get_class_list() ;
		$data['month_data']['cando'] =1 ;
	}

	//取得現在班級姓名
	$data['class_stud']=get_class_students($class_id) ;

	$data['sel_class']  =$class_id ;

	//取得各班別資料
	$grade_data=get_month_grade($month_id) ;

	//取得報名學生資料
 	$data['sign_studs'] = get_as_signs($month_id , $class_id  , $grade_data ,$isAdmin) ;

	//預設為最後一個時段
	$data['def_time_mode']= count($AS_SET['time']) ;


/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
$xoopsTpl->assign( "jquery" , get_jquery(true)) ;

$xoopsTpl->assign( "AS_SET" , $AS_SET ) ;
$xoopsTpl->assign( "data" , $data ) ;



include_once XOOPS_ROOT_PATH.'/footer.php';

?>
