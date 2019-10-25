<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/

//樣版
$xoopsOption['template_main'] ="ad_index_tpl.html";
include_once "header.php";
include_once "../function.php";


/*-----------function區--------------*/
//取得參數



/*-----------執行動作判斷區----------*/
//新建一期
if ($_POST['ADD']) {

	$month_doc=$_POST['month_doc'] ;
	$deadline=$_POST['deadline'] ;
	$sql = " insert into  " . $xoopsDB->prefix("afdb_month") . "  ( monthdoc ,  deadline )
		values ( '$month_doc' , '$deadline'  ) " ;
	$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());

	//取得 month_ID
	$month_id = $xoopsDB->getInsertId() ;

 	//年級
	foreach ($AS_SET['grade_set'] as $gk =>$y) {
		//班別
		foreach ($AS_SET['time_cost_set']  as $tk =>$time_cost ) {
			//$time_mode = $AS_SET['time'][$tk] ;
			$sql = " insert into " . $xoopsDB->prefix("afdb_grade") . "  (month_id , grade_year ,time_mode,cost,stud_dc ,sect_num ,teacher_dc ,class_num ,stud_num ,pay ,pay_sum  )
				values ('$month_id'  , '$y' , '$tk', '$time_cost'  ,0, 0 ,'{$AS_SET['dc_set']}',0,0,0,0    ) " ;
			//echo $sqlstr .'<br>' ;
			$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());
		}

	}



	//匯入上期資料
	if ($_POST['oldmonth']  and  ( $month_id <> $_POST['oldmonth'] )  ) {

		//取得全部學生名冊
		$sql=" select  * from  " .  $xoopsDB->prefix("afdb_sign") . "   where  month_id = '{$_POST[oldmonth]}'    ";

		$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());

		while($row=$xoopsDB->fetchArray($result)){
				//$user[] = $row ;
				$name = addslashes($row[stud_name]) ;
				$sql2 = " insert into  " .  $xoopsDB->prefix("afdb_sign") . "  ( id , month_id , grade_year , class_id , class_id_base , stud_name  , stud_sex , time_mode, spec , ps ,stud_id  , class_sit_num )
                       	   		values ('0' , '$month_id'  ,  '{$row[grade_year]}'  , '{$row[class_id]}' , '{$row[class_id_base]}' , '$name ' , '{$row[stud_sex]}' , '{$row[time_mode]}' , '{$row[spec]}' , '{$row[ps]}'  , '{$row[stud_id]}'  , '{$row[class_sit_num]}'   ) " ;

              	$result2 = $xoopsDB->query($sql2) or die($sql2."<br>". $xoopsDB->error());
		}


	}



}

if ($_POST['SetDate']) {
	//更改填報截止日期
	$now = $_POST['new_deadline'] ;
	$sql = " update   " . $xoopsDB->prefix("afdb_month") . "  set  deadline =  '$now' where  month_id = '$_POST[oldmonth]' " ;
 	$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());

}


//寫入並計算
if ($_POST['calc'] =='calc') {
	//計算人數

	 //取得年級班別設定
	$data=get_month_grade($_POST['oldmonth']) ;

	//取得人數
	$stud_count =  get_month_sign_team($_POST['oldmonth']) ;

	do_calc($data ,$stud_count , 1 ) ;



}

//刪除舊期別資料
if ($_POST['clear_old_data'] =='clear_old_data') {
	//只留下最新的期別
 	clear_data($_POST['oldmonth']) ;

}

//取得最近的期別---------------------------------------------------------------------------------------------------------------------
$month_data = get_month_list() ;

$month_id = $month_data['month_id'] ;

//如果沒有 stud_id  修正 ，程式更新過程中未補，再次檢查
stud_id_fix( $month_id );


//取得年級班別設定
$data=get_month_grade($month_id) ;


//取得人數
$stud_count =  get_month_sign_team($month_id) ;
//加入到各年級統計表中

//計算
$data = do_calc($data ,$stud_count ) ;



/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "month_data" , $month_data ) ;
$xoopsTpl->assign( "data" , $data ) ;
$xoopsTpl->assign( "AS_SET" , $AS_SET ) ;


include_once 'footer.php';
?>
