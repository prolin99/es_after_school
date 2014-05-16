<?php

// $Id: signAdmin.php,v 1.1.2.1 2004/10/22 15:26:46 prolin Exp $

include "config.php";
 
sfs_check();

$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'] ;
if ( !checkid($SCRIPT_FILENAME,1)){
    head("報名表單設計") ;
    print_menu($school_menu_p);
    echo "無管理者權限！<br>請進入 系統管理 / 模組權限管理 修改 After_School模組授權。" ;
    foot();
    exit ;
    
    //Header("Location: signList.php"); 
}


//寫入一期

//新建一期
if ($_POST['ADD']) {
	//取得最後一期的編號，自動加1
	$max = 0 ;
	$sqlstr = " SELECT MAX( `motn_id` )  max FROM `afdb_month` " ;
	$recordSet= $CONN->Execute($sqlstr);	
	if  ($recordSet ) {
		$row =$recordSet->FetchRow() ;
		$max = $row['max']  ;
		
	}
	$month_id = $max+1 ;	

//	$month_id=$_POST['month_id'] ;
	$month_doc=$_POST['month_doc'] ;
	$deadline=$_POST['deadline'] ;
	for($y =1;$y<=6 ; $y++) {
		$sqlstr = " insert into afdb_month (motn_id , monthdoc , grade_year ,time_mode,cost,stud_dc ,sect_num ,teacher_dc ,class_num ,stud_num ,pay ,pay_sum , deadline )
				values ('$month_id' , '$month_doc' , $y ,0,260 ,0, 0 ,0.7,0,0,0,0 ,'$deadline'  ) " ;
		$CONN->Execute($sqlstr);	
		$sqlstr = " insert into afdb_month (motn_id , monthdoc , grade_year ,time_mode,cost,stud_dc ,sect_num ,teacher_dc ,class_num ,stud_num ,pay ,pay_sum ,deadline )
				values ('$month_id'  , '$month_doc'  , $y ,1,400 ,0, 0 ,0.7,0,0,0,0 ,'$deadline'  ) " ;
//				echo $sqlstr .'<br>' ;
		$CONN->Execute($sqlstr);	                       

	}
	//匯入上期資料
	if ($_POST['oldmonth']  and  ( $month_id <> $_POST['oldmonth'] )  ) {
		//取得全部學生名冊
		$sql=" select  * from  afdb_sign where  month_id = '$_POST[oldmonth]'  order by class_id_base	 ";
		$recordSet = $CONN->Execute($sql);
		if  ($recordSet ) {
			while ($row = $recordSet->FetchRow() ) {
				$user[] = $row ;
				$name = addslashes($row[stud_name]) ; 
				$sqlstr_add = " insert into afdb_sign( id , month_id , grade_year , class_id , class_id_base , stud_name  , stud_sex , time_mode, spec , ps )
                       	   		values ('' , '$month_id'  ,  '$row[grade_year]'  , '$row[class_id]' , '$row[class_id_base]' , '$name ' , $row[stud_sex] , '$row[time_mode]' , '$row[spec]' , '$row[ps]' ) " ;
           
              			$CONN->Execute($sqlstr_add);
			} 
		
		}		
		
	}	

}

if ($_POST['SetDate']) {
	//$now= date("Y-m-d")  ;
	$now = $_POST['new_deadline'] ;
	$sqlstr_update = " update   afdb_month   set  deadline =  '$now' where  motn_id = '$_POST[oldmonth]' " ;
	$CONN->Execute($sqlstr_update);   
	
}	


//寫入並計算
if ($_POST['Sumit']) {
	//計算人數
 	
	$sql="  SELECT  `grade_year` , `time_mode` , COUNT( * ) AS dd FROM  `afdb_sign`  where month_id = '$_POST[oldmonth]' GROUP BY  `grade_year` ,  `time_mode`   order by  `grade_year` , `time_mode`  " ;
	$recordSet = $CONN->Execute($sql);
 
	while ($row = $recordSet->FetchRow() ) {
		$y2 = $row['grade_year'] *10+ $row['time_mode'] + 1 ;
		$y1 = $row['grade_year'] *10+  1 ;
		$data['stud_num'][$y2]  = $row[dd] ;
	}	
	for($i = 1; $i<=6 ;$i++) {
		for ($t=0 ;$t<=1 ; $t++) {
			$y2 = $i *10+ $t + 1 ;
			$y1 = $i *10+  1 ;		
			$y12 = $i *10+  2 ;		
			
			if ($t==0)  {
				$data['stud_num'][$y2] += $data['stud_num'][$y12] ;
			}	
			
			$data['class_num'][$y2] = floor($data['stud_num'][$y2]  /25 )  +1 ;
			$data['dc'][$y2]=$DEF['decount'][$data['stud_num'][$y2]]  ;  
			
			
			if ( $data['dc'][$y2]==0  )
				$data['dc'][$y2]= 1 ;
	
			if (  	$data['stud_num'][$y2]  >0 ) {
				$data['pay'][$y2] = floor( $DEF['time_cost'][$t]  * $data['dc'][$y2]  * $_POST['sect_num'][$y2]/0.7*$data['class_num'][$y2]  /$data['stud_num'][$y2] ) ;
			}	
			if  ( $t ==1)
			$play_sum = $data['pay'][$y2] + $data['pay'][$y1] ;
			else
			$play_sum = $data['pay'][$y2] ;
			
			$sqlstr_update = " update   afdb_month   set 
					stud_dc = '{$data[dc][$y2]}'  , sect_num = '{$_POST[sect_num][$y2]}'  ,  class_num ='{$data[class_num][$y2]}'   ,stud_num = '{$data[stud_num][$y2]}'  ,
					pay ='{$data[pay][$y2]}'   ,pay_sum='$play_sum'  
					where motn_id = '$_POST[oldmonth]' and grade_year =  $i  and time_mode =$t		" ;  
		//	echo $sqlstr_update  ."<br/>" ;		
			$CONN->Execute($sqlstr_update);   
			
		} 	
	}
	//計算式 : 鐘點費×鐘點折數×每期節數÷鐘點費佔成數×班級數÷學生數
	//
	
}	


//取得最近的期別---------------------------------------------------------------------------------------------------------------------
$sql=" select  * from  afdb_month order by motn_id DESC  LIMIT 0 , 1 ";
$recordSet = $CONN->Execute($sql);
if  ($recordSet ) {
	while ($row = $recordSet->FetchRow() ) {
		$data['month_id'] = $row['motn_id'] ;  
		$data['month_doc'] = $row['monthdoc'] ;  
		$data['deadline'] = $row['deadline'] ;  
	} 
	$month_id = $data['month_id']  ;  
	
	
	$sql=" select  * from  afdb_month where motn_id ='$month_id'  order by motn_id ,grade_year,time_mode    ";
 //echo $sql ;
	$recordSet = $CONN->Execute($sql); 
	while ($row = $recordSet->FetchRow() ) {
		$data['month_id'] = $row['motn_id'] ;  
 
		$data['deadline'] = $row['deadline'] ;  
		$y2 = $row['grade_year'] *10+ $row['time_mode'] + 1 ;
 
		$data['dc'][$y2]=$row['stud_dc']  ;  
 
		$data['sect_num'][$y2] =$row['sect_num'] ;  
		$data['td'][$y2] =$row['teacher_dc'] ;  
		$data['class_num'][$y2] =$row['class_num'] ;  
		$data['stud_num'][$y2] =$row['stud_num'] ;  
		$data['pay'][$y2] =$row['pay'] ;  
		$data['pay2'][$y2] =$row['pay_sum'] ;  	
		
 
	} 
 
}
 

//樣版------------------------------------------------------------------
$smarty->assign("SFS_TEMPLATE",$SFS_TEMPLATE);
$smarty->assign("module_name","課後照顧");

$smarty->assign("SFS_MENU",$school_menu_p);
$smarty->assign("DEF",$DEF);
$smarty->assign("data",$data);

/*
$smarty->assign("class_list",$class_list);
$smarty->assign("edit_id",$edit_id);
*/
	$smarty->display("signinAdmin.htm");
?>