<?php
//$Id: config.php,v 1.1.2.1 2004/10/22 15:26:46 prolin Exp $
//預設的引入檔，不可移除。
require_once "./module-cfg.php";
include_once "../../include/config.php";
include_once "../../include/sfs_case_PLlib.php";
//您可以自己加入引入檔

//打折數
$DEF['decount']=array(0, 0.7,0.7,0.7,0.7,0.7,
				 0.7,0.7,0.7,0.7,0.7,
				 0.8,0.85,0.9,1 ,1	) ;
/*
補助身分說明 : 
一、前三類學生代號 : 1. 低收入戶   2. 身心障礙   3. 原住民。  
二、第四類情況特殊學生代號 : 4-1 持有社會局、公所開立之中低收入戶證明  4-2 失業證明  4-3 重大傷病證明  4-4 導師證明
三、外配子女代號: 5
*/
$DEF['spec_array']= array("" ,"1.低收入戶","2.身心障礙","3. 原住民","4-1 社會局公所證明","4-2 失業證明", "4-3 重大傷病證明", "4-4 導師證明") ;
$DEF['time_mode']=array('4:00' , '5:30') ;
$DEF['time_cost']=array('260' , '400') ;
$DEF['SEX']= array('女','男') ;
$DEF['SEL_CLASS']= array(1=>'A', 2=>'B') ;



 //取得模組參數設定
$m_arr =& get_module_setup("AfterSchool");
extract($m_arr, EXTR_OVERWRITE);

$PHP_SELF = $_SERVER["PHP_SELF"] ;

function show_page_point($showpage, $totalpage) {
  $PHP_SELF = $_SERVER["PHP_SELF"] ;
              if ($showpage >1) 
                   $main =  "<a href=\"$PHP_SELF?showpage=" . ($showpage-1) . "\"><img src=\"images/prev.gif\"  alt=\"前一頁\" border=\"0\"></a> \n " ;
                 else 
                   $main =  "<img src=\"images/prev.gif\"  alt=\"已是最前頁\" border=\"0\" class=\"hide\">\n " ;
                 $main .= " | 第 $showpage 頁 | \n" ;
                 if ($showpage < $totalpage) 
                   $main .= "<a href=\"$PHP_SELF?showpage=" . ($showpage+1). "\"><img src=\"images/next.gif\"  alt=\"下一頁\" border=\"0\"></a> \n" ;
                 else   
                   $main .= "<img src=\"images/next.gif\"  alt=\"已是最後頁\" border=\"0\" class=\"hide\">\n " ;

                 
   
     $main = 
        "<table width='98%' border='0' cellspacing='0' cellpadding='0' align='center'>
          <tr>
            <td width='70%'>&nbsp;</td>
            <td width='30%'>   
              $main
            </td>\n
          </tr>
        </table>" ;
    return $main ;    
                 
}  

function Get_stud_data($class_num  , $stud ) {
     //由班級+座號及 $get_arr 陣列中取得：姓名....
     global $CONN ;

   
     
    //由座號
    if (is_numeric($stud) ) {
       $class_num_id = $class_num . sprintf("%02d" ,$stud) ;
   
       $sql="select * from  stud_base  
           where  curr_class_num = '$class_num_id'    and stud_study_cond = 0   ";
    }else {
    	$sql="select * from  stud_base  
           where  stud_name = '$stud'  and stud_study_cond = 0   ";
    	
    }	
    //座號、姓名、生日、地址、電話、家長、家長工作、工作電話

    $result = $CONN->Execute($sql) or user_error("讀取失敗！<br>$sql",256) ;
    
    
    $row = $result->FetchRow() ;
        $have_stud_data_fg = TRUE ;
	$s_addres = $row["stud_addr_1"]  ;
	$s_home_phone = $row["stud_tel_1"]  ;	//家中電話
	$s_offical_phone =stud_tel_2  ;		//工作地電話
	$stud_id = $row["stud_id"];		//學號
	$stud_name = $row["stud_name"];		//姓名
	$stud_person_id = $row["stud_person_id"]; //身份証
	$stud_sex = $row["stud_sex"];		//性別
	
	$s_birthday = $row["stud_birthday"]  ;
	
        $dd[] = $stud_name ;
        $dd[] = $stud_id ;	

    

    return $dd  ;
      	
}


function Get_stud_data2($class_num  , $now_class_id ) {
     //由班級+座號及 $get_arr 陣列中取得：姓名....
     global $CONN ;
    //預設學年
    $curr_year =  curr_year();
    //預設學期
    $curr_seme = curr_seme();

     $curr_class_year= substr($now_class_id,0,1) ;
     
    //把本班姓名放入陣列中

    $class_num_id = $class_num . sprintf("%02d" ,$now_class_id) ;

    $sql="select * from  stud_base  
           where  curr_class_num = '$class_num_id'    and stud_study_cond = 0   ";

    //座號、姓名、生日、地址、電話、家長、家長工作、工作電話

    $result = $CONN->Execute($sql) or user_error("讀取失敗！<br>$sql",256) ;
    
    
    $row = $result->FetchRow() ;
        $have_stud_data_fg = TRUE ;
	$s_addres = $row["stud_addr_1"]  ;
	$s_home_phone = $row["stud_tel_1"]  ;	//家中電話
	$s_offical_phone =stud_tel_2  ;		//工作地電話
	$stud_id = $row["stud_id"];		//學號
	$stud_name = $row["stud_name"];		//姓名
	$stud_person_id = $row["stud_person_id"]; //身份証
	$stud_sex = $row["stud_sex"];		//性別
	
	$s_birthday = $row["stud_birthday"]  ;
	/*
	//轉換民國日期
	if ( substr($s_birthday,0,4)>1911) 
	  $s_birthday = (substr($s_birthday,0,4) - 1911). substr($s_birthday,4) ;
	else 
	  $s_birthday = " " ; 
	*/
    if ($have_stud_data_fg) {	
        $sql="select *    from stud_domicile   where stud_id = '$stud_id'   ";
        $result = $CONN->Execute($sql) or user_error("讀取失敗！<br>$sql",256) ;
    
        $row = $result->FetchRow() ;           

	//家長

         $d_guardian_name = $row["guardian_name"] ;	  
         $fath_name =$row["fath_name"] ;	  
         $moth_name =$row["moth_name"] ;
         

        $stud_class_id = $now_class_id ;

    	
        //可匯出欄位選項
        //$STUD_FIELD = array("學號","生日","身份証字號","座號","性別","電話","地址","監護人","父親","母親") ;	
        $dd[] = $stud_name ;
        $dd[] = $stud_id ;
        $dd[] = $s_birthday ;
        $dd[] = $stud_person_id ;
        $dd[] = $stud_class_id ;
        $dd[] = $stud_sex ;
        $dd[] = $s_home_phone ;
        $dd[] = $s_addres ;
        $dd[] = $d_guardian_name ;
        $dd[] = $fath_name ;
        $dd[] = $moth_name ;


    
        //一併匯出
	$data_str  = @implode("," , $dd) ;

    }
    return $data_str  ;
      	
}

function get_class_data($class_id) {
    global $CONN,$sex_ch ; 
    
    $class_name_arr = class_base() ;
    $seme_year_seme=sprintf("%03d",curr_year()).curr_seme();
   
    $class_name = $class_name_arr[$class_id] ;
 
    $pos = strpos($class_name, "忠");    

    if  ($pos === false) {
    	//$sql= "select s.stud_id,s.seme_num as sit_num ,seme_class , b.stud_name ,b.stud_name_eng ,b.stud_sex from stud_seme s , stud_base b where s.stud_id=b.stud_id and  b.stud_study_cond =0 and s.seme_class = '$class_id' and  s.seme_year_seme='$seme_year_seme' and b.stud_spe_kind<>'2' order by  s.seme_num";
    	$sql= "select s.stud_id,s.seme_num as sit_num ,seme_class , b.stud_name ,b.stud_name_eng ,b.stud_sex from stud_seme s , stud_base b where s.stud_id=b.stud_id and  b.stud_study_cond =0 and s.seme_class = '$class_id' and  s.seme_year_seme='$seme_year_seme' order by  s.seme_num";
    }else{ 
       $class_y = substr($class_id,0,1) ;
       $sql="select s.stud_id, b.spe_sit_num as sit_num ,b.stud_name ,b.stud_sex ,b.stud_name_eng  from stud_seme s , stud_base b where s.stud_id=b.stud_id and  b.stud_study_cond =0 and  b.stud_class_kind='1' and b.stud_spe_kind='2' and s.seme_class like '$class_y%' and  s.seme_year_seme='$seme_year_seme' order by  b.spe_sit_num ";       
    }          
    
    /*
    $class_name = $class_name_arr[$class_id] ;
    
	$sql= "select s.stud_id,s.seme_num as sit_num ,seme_class , b.stud_name ,b.stud_name_eng ,b.stud_sex from stud_seme s , stud_base b where s.stud_id=b.stud_id and  b.stud_study_cond =0 and s.seme_class = '$class_id' and  s.seme_year_seme='$seme_year_seme' order by  s.seme_num";
*/
 
    $rs=$CONN->Execute($sql);

  
	while(!$rs->EOF){
	  unset($tmp) ;	

          $tmp[stud_id] = $rs->fields["stud_id"];	
          $tmp[site_num] = $rs->fields["sit_num"];
          $tmp[stud_name] = $rs->fields["stud_name"];
          $tmp[stud_sex] = $sex_ch[$rs->fields["stud_sex"]];	
          $tmp[stud_name_eng] = $rs->fields["stud_name_eng"];
          
   //       $rs_name=$CONN->Execute("select stud_name , stud_sex ,stud_name_eng   from stud_base where stud_id='$stud_id' and stud_study_cond =0 ");
          $data_array[]= $tmp ;

        $rs->MoveNext();
    }
    return $data_array ;
}      
?>