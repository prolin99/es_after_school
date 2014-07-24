<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-05-1
// $Id:$
// ------------------------------------------------------------------------- //

//---基本設定---//

$modversion['name'] ='課後照顧報名';				//模組名稱
$modversion['version']	= '0.4';				//模組版次
$modversion['author'] = 'prolin(prolin@tn.edu.tw)';		//模組作者
$modversion['description'] ='課後照顧報名、費用計算';			//模組說明
$modversion['credits']	= 'prolin';				//模組授權者
$modversion['license']		= "GPL see LICENSE";		//模組版權
$modversion['official']		= 0;				//模組是否為官方發佈1，非官方0
$modversion['image']		= "images/logo.png";		//模組圖示
$modversion['dirname'] = basename(dirname(__FILE__));		//模組目錄名稱

//---模組狀態資訊---//
//$modversion['status_version'] = '0.8';
$modversion['release_date'] = '2014-05-20';
$modversion['module_website_url'] = 'https://github.com/prolin99/es_after_school';
$modversion['module_website_name'] = 'prolin';
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'http://www.syps.tn.edu.tw';
$modversion['author_website_name'] = 'prolin';
$modversion['min_php']= 5.2;



//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "afdb_month";
$modversion['tables'][2] = "afdb_sign";
$modversion['tables'][3] = "afdb_grade";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
 
//---使用者主選單設定---//
$modversion['hasMain'] = 1;


//---樣板設定---要有指定，才會編譯動作，//
$modversion['templates'] = array();
$i=1;
$modversion['templates'][$i]['file'] = 'ad_index_tpl.html';
$modversion['templates'][$i]['description'] = 'ad_index_tpl.html';
$i++ ;
$modversion['templates'][$i]['file'] = 'as_index_tpl.html';
$modversion['templates'][$i]['description'] = 'as_index_tpl.html';

 
$i=0 ;
//偏好設定

$i++ ;
$modversion['config'][$i]['name'] = 'es_as_posit';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITLE1';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC1';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="0.無\r\n1.低收入戶\r\n2.身心障礙\r\n3. 原住民\r\n4-1 社會局公所證明\r\n4-2 失業證明\r\n4-3 重大傷病證明\r\n4-4 導師證明" ;

$i++ ;
$modversion['config'][$i]['name'] = 'es_as_timesect';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITL2';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC2';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="4:00,260;5:30,400" ;


$i++ ;
$modversion['config'][$i]['name'] = 'es_as_grade';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITLE3';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC3';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="1,2,3,4,5,6" ;

$i++ ;
$modversion['config'][$i]['name'] = 'es_as_class';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITLE4';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC4';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="A,B" ;



$i++ ;
$modversion['config'][$i]['name'] = 'es_as_percent';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITLE5';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC5';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="0.7" ;

$i++ ;
$modversion['config'][$i]['name'] = 'es_as_studs_in';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITLE6';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC6';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="25" ;

$i++ ;
$modversion['config'][$i]['name'] = 'es_as_studs_down';
$modversion['config'][$i]['title']   = '_MI_ESAFTER_CONFIG_TITLE7';
$modversion['config'][$i]['description'] = '_MI_ESAFTER_CONFIG_DESC7';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default'] ="0, 0.7,0.7,0.7,0.7,0.7,0.7,0.7,0.7,0.7,0.7,0.8,0.85,0.9,1 ,1" ;
/*
學生數	15	14	13	12	11	10	9	8	7
打折數	1	1	0.9	0.85	0.8	0.7	0.7	0.7	0.7
*/


?>