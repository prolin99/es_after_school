<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-03-01
// $Id:$
// ------------------------------------------------------------------------- //
$i=0 ;

$adminmenu[$i]['title'] = '首頁';
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['desc'] = '首頁' ;
$adminmenu[$i]['icon'] = 'images/admin/home.png' ;

$i++ ;
$adminmenu[$i]['title'] = '期別管理';
$adminmenu[$i]['link'] = "admin/main.php";
$adminmenu[$i]['desc'] = '期別管理' ;
$adminmenu[$i]['icon'] = 'images/admin/home.png' ;

$i++ ;
$adminmenu[$i]['title'] = "關於";
$adminmenu[$i]['link'] = "admin/about.php";
$adminmenu[$i]['desc'] = '說明';
$adminmenu[$i]['icon'] = 'images/admin/about.png';

?>
