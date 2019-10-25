<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-02-16
// $Id:$
// ------------------------------------------------------------------------- //


//需要單位名稱模組(e_stud_import)1.9
if(!file_exists(XOOPS_ROOT_PATH."/modules/e_stud_import/es_comm_function.php")){
 redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=33",3, '需要單位名稱模組(e_stud_import)1.9以上');
}
include_once XOOPS_ROOT_PATH."/modules/e_stud_import/es_comm_function.php";

/********************* 自訂函數 *********************/
//取得參數
//身份
$decrease_set_list  = preg_split( "/\r\n/" , $xoopsModuleConfig['es_as_posit'] ) ;
//1.低收入戶\n2.身心障礙\n3. 原住民\n4-1 社會局公所證明\n4-2 失業證明\n4-3 重大傷病證明\n4-4 導師證明
$i=0 ;
foreach ($decrease_set_list as  $k =>$v)  {
	if (trim($v)<>'')
		$AS_SET['decrease_set'][$i++] = $v ;
}

//時段，金額
$time_set_def = preg_split( "/[;]/" , $xoopsModuleConfig['es_as_timesect'] ) ;

$i=0 ;
foreach ($time_set_def as $t => $v) {
	$i++ ;
	$split_data  = preg_split( "/[,]/" , $v ) ;
	$AS_SET['time'][$i]  = $split_data[0] ;
	$AS_SET['time_cost_set'][$i]  =$split_data[1] ;
}


//開課年級
$grade_set= preg_split( "/[,;]/" , $xoopsModuleConfig['es_as_grade'] ) ;
foreach ($grade_set as $t => $v) {
    $AS_SET['grade_set'][$v]=$v  ;
}


//開班
$AS_SET['class_set']= preg_split( "/[,]/" , $xoopsModuleConfig['es_as_class'] ) ;

//預設 成數
$AS_SET['dc_set']=  $xoopsModuleConfig['es_as_percent']  ;

//預設最多幾人
$AS_SET['studs']=  $xoopsModuleConfig['es_as_studs_in']  ;

//未滿15 人折扣
$AS_SET['stud_down']=   preg_split( "/[,]/" , $xoopsModuleConfig['es_as_studs_down'])  ;

/********************* 預設函數 *********************/



//取得最近的期別
function get_month_list( ){
	global  $xoopsDB ;

		$sql =  "  SELECT   *  ,( deadline >=  (NOW() - INTERVAL 1 DAY ) ) as cando   FROM " . $xoopsDB->prefix("afdb_month") . "  order by month_id DESC  LIMIT 0 , 1   " ;

		$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());

		while($row=$xoopsDB->fetchArray($result)){
			$data=$row ;

		}
	return $data ;

}


//取得各年級的設定
function get_month_grade($month_id) {
	global  $xoopsDB ;

		$sql =  "  SELECT   *   FROM " . $xoopsDB->prefix("afdb_grade") . "  WHERE `month_id` = '$month_id'  order by month_id ,grade_year,time_mode   " ;

		$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());

		while($row=$xoopsDB->fetchArray($result)){
 			$k= $row['grade_year']. '_' .$row['time_mode'] ;
			$data[$k] = $row;

		}
	return $data ;
}

//取得各班人數
function get_month_sign_team($month_id) {
	global  $xoopsDB ,$AS_SET ;

		$sql =  "  SELECT  month_id ,grade_year,time_mode ,  count(*) as cc  FROM " . $xoopsDB->prefix("afdb_sign") . "  WHERE `month_id` = '$month_id'  group  by month_id ,grade_year,time_mode   order by  time_mode DESC " ;

		$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());

		while($row=$xoopsDB->fetchArray($result)){
            //開班數
 			$row['class_num'] = floor($row['cc']  /$AS_SET['studs'] )  +1 ;
 			$k= $row['grade_year']. '_' .$row['time_mode'];

			$data[$k] = $row;

		}

        foreach($data as $k =>$row ) {
            if ( ( $row['cc']>0 ) and ($row['time_mode']>0) ) {
                //比較早時段人數 要加入 後面時段者
                $k_b =  $row['grade_year']. '_' .( $row['time_mode']-1);
                $row_b = $row ;
                $row_b['time_mode']= $row['time_mode']-1 ;
                $row_b['cc'] = $data[$k_b]['cc']  + $row['cc'] +0 ;
                $row_b['stud_num_show'] = " {$data[$k_b]['cc']}  + {$row['cc']} " ;
                $row['cc'] += $data[$k2]['cc'] ;
                $data[$k_b] = $row_b ;
            }

        }

	return $data ;
}


//=================================================================================================
function get_class_list(  ) {
	//取得全校班級列表
	global  $xoopsDB ;

		$sql =  "  SELECT  class_id  FROM " . $xoopsDB->prefix("e_student") . "   group by class_id   " ;

		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $xoopsDB->error());
		while($row=$xoopsDB->fetchArray($result)){

			$data[$row['class_id']]=$row['class_id'] ;

		}
	return $data ;

}


function get_class_students( $class_id , $mode='class') {
	//取得該班的學生姓名資料   $mode =class ,grade (全學年) , all (全校)
	global  $xoopsDB ;
	if ($mode =='all')
		$sql =  "  SELECT  *  FROM " . $xoopsDB->prefix("e_student") . "     ORDER BY class_id,  `class_sit_num`  " ;
	elseif ( $mode=='grade') {
		$grade = substr($class_id,0,1) ;
		$sql =  "  SELECT  *  FROM " . $xoopsDB->prefix("e_student") . "  where class_id like '$grade%'   ORDER BY class_id,  `class_sit_num`  " ;
	}else
		$sql =  "  SELECT  *  FROM " . $xoopsDB->prefix("e_student") . "   where class_id='$class_id'   ORDER BY  `class_sit_num`  " ;

		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $xoopsDB->error());
		while($stud=$xoopsDB->fetchArray($result)){

			$data[$stud['stud_id'] ]=$stud ;

		}
	return $data ;
	//echo $sql ;

}

function get_as_signs($month_id , $class_id , $grade_data , $isAdmin=0) {
	//取得目前已報名資料
	global  $xoopsDB ;
	if ($isAdmin)
		$sql=" select  * from  " . $xoopsDB->prefix("afdb_sign")  ."  where  month_id = '$month_id'  order by grade_year,class_id,time_mode,class_id_base ,stud_name	 ";
	else
		$sql=" select  * from  " . $xoopsDB->prefix("afdb_sign")  ."  where  month_id = '$month_id'  and class_id_base= '$class_id'  order by stud_name	 ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $xoopsDB->error());

	//var_dump ($grade_data) ;
	while($row=$xoopsDB->fetchArray($result)){
		$grade_time= $row['grade_year'] . '_' . $row['time_mode'] ;
		//echo $grade_data[$grade_time]['pay_sum'] ;
		$row['pay_sum'] =  $grade_data[$grade_time]['pay_sum'] ;
        //如果年級和年段不同做提醒
        if ($row['grade_year'] <> substr($row['class_id_base'],0,1))
            $row['joinfg'] = true ;

		$data[]=$row ;

	}
	return $data ;

}


function get_as_signs_join_charge($month_id , $class_id , $grade_data , $isAdmin=0) {
	//取得目前已報名資料
	global  $xoopsDB ;
	if ($isAdmin)
		$sql=" select  a.* , c.* from  " . $xoopsDB->prefix("afdb_sign")  . "  a , " .  $xoopsDB->prefix("charge_account")  . "  c   " .
        "  where  a.month_id = '$month_id'  and a.stud_id = c.stud_sn " .
        "  order by a.grade_year, a.class_id, a.time_mode, a.class_id_base , a.stud_name	 ";
	else
		$sql=" select  * from  " . $xoopsDB->prefix("afdb_sign")  ."  where  month_id = '$month_id'  and class_id_base= '$class_id'  order by stud_name	 ";
 
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $xoopsDB->error());

	//var_dump ($grade_data) ;
	while($row=$xoopsDB->fetchArray($result)){
		$grade_time= $row['grade_year'] . '_' . $row['time_mode'] ;
		//echo $grade_data[$grade_time]['pay_sum'] ;
		$row['pay_sum'] =  $grade_data[$grade_time]['pay_sum'] ;
        //如果年級和年段不同做提醒
        if ($row['grade_year'] <> substr($row['class_id_base'],0,1))
            $row['joinfg'] = true ;

		$data[]=$row ;

	}
	return $data ;

}



Function get_class_teacher_list() {
	//取得全部級任名冊
	global  $xoopsDB ;
	$sql =  "  SELECT  t.uid, t.class_id , u.name  FROM " . $xoopsDB->prefix("e_classteacher") .'  t  , ' .   $xoopsDB->prefix("users")  .'  u    ' .
	               " where t.uid= u.uid    " ;

	$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());
	while($data_row=$xoopsDB->fetchArray($result)){
 			$class_id[$data_row['class_id']] = $data_row['name'] ;
	}
	return $class_id  ;
}

function get_my_class_id($uid =0   ) {
	//取得$uid 的任教班級
	global  $xoopsDB ,$xoopsUser  ;
	if (!$uid)
		$uid = $xoopsUser->uid() ;
	$sql =  "  SELECT  class_id  FROM " . $xoopsDB->prefix("e_classteacher") .
	               " where uid= '$uid'   " ;

	$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());
	while($data_row=$xoopsDB->fetchArray($result)){
 			$class_id = $data_row['class_id'] ;
	}
	return $class_id  ;
}


function do_calc(	$data , $stud_count ,$save_fg = false ) {
	global  $xoopsDB ,$AS_SET  ;
	foreach ($data as $k => $v) {
		$grade= $v['grade_year']+0 ;
		$time_mode= $v['time_mode']+0 ;
		$cost= $v['cost']+0 ;
		$stud_dc= $v['stud_dc']+0 ;
		$sect_num= $v['sect_num']+0 ;
		$teacher_dc= $v['teacher_dc']+0 ;
		$class_num= $v['class_num']+0 ;

		$pay= $v['pay']+ 0 ;
		$pay_sum= $v['pay_sum'] + 0;

		//人數
		$data[$k]['stud_num'] =$stud_count[$k]['cc'] +0;
		$stud_num= $stud_count[$k]['cc'] +0;
        $data[$k]['stud_num_show']= $stud_count[$k]['stud_num_show'] ;

		//班數
		$data[$k]['class_num'] = $stud_count[$k]['class_num']+0 ;
		$class_num= $stud_count[$k]['class_num'] +0;

		//計算折扣數
		$stud_in_class=0 ;
		if  ( $class_num)
			$stud_in_class  = ceil($stud_num  / $class_num )   ;
		$stud_dc = $AS_SET['stud_down'][$stud_in_class] +0;
		if ($stud_dc==0)  $stud_dc =1 ;
		$data[$k]['stud_dc']= $stud_dc+0 ;

		//計算
		$pay =0 ;
		if ($sect_num) {
			//計算式 : 鐘點費×鐘點折數×每期節數÷鐘點費佔成數×班級數÷學生數
            if ($stud_num>0 and $sect_num>0)
			     $pay=floor($cost *  $stud_dc *$sect_num /$teacher_dc *$class_num /$stud_num)  ;
            else
                $pay=0 ;
			//$data['pay'][$y2] = floor( $DEF['time_cost'][$t]  * $data['dc'][$y2]  * $_POST['sect_num'][$y2]/0.7*$data['class_num'][$y2]  /$data['stud_num'][$y2] ) ;
		}
		$data[$k]['pay']= $pay ;


		$grade_pay_sum[$grade] += $pay ;
		$data[$k]['pay_sum']= $grade_pay_sum[$grade]  ;


		//寫入資料庫中
		if ($save_fg ) {
			$sql = " update   " . $xoopsDB->prefix("afdb_grade")  .
					"  set    stud_dc='$stud_dc' , class_num ='$class_num'  ,stud_num ='$stud_num' ,pay='$pay' , pay_sum='{$data[$k]['pay_sum']}'   where  id = '{$data[$k]['id']}'   " ;

 			$result = $xoopsDB->query($sql) or die($sql."<br>". $xoopsDB->error());
		}

	}
	return $data ;

}

function clear_data($month_id) {
	//刪除  $month_id 之前的資料。
	global  $xoopsDB ;

		$sql =  "  DELETE       FROM " . $xoopsDB->prefix("afdb_grade") . "  WHERE `month_id` < '$month_id'    " ;
 		$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());

		$sql =  "  DELETE       FROM " . $xoopsDB->prefix("afdb_month") . "  WHERE `month_id` < '$month_id'    " ;
 		$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());

		$sql =  "  DELETE       FROM " . $xoopsDB->prefix("afdb_sign") . "  WHERE `month_id` < '$month_id'    " ;
 		$result = $xoopsDB->query($sql)  or die($sql."<br>". $xoopsDB->error());

}
