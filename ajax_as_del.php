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
	list($div,$id,$class_id_base)  = preg_split('/_/',$_GET['id'] ) ;
	if ($id) {
 		//取得任教班級
		$class_id = get_my_class_id() ;


		if ($isAdmin){
			$class_id = $class_id_base ;
		}

 		//先清除該學生的減免資料
 		$sql = " DELETE FROM "  . $xoopsDB->prefix("afdb_sign") .    " where id = '$id' and class_id_base = '$class_id'     	   " ;

	     	$result = $xoopsDB->queryF($sql) ;

}
