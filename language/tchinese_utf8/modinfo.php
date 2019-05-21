<?php
//  ------------------------------------------------------------------------ //
// 本模組由 prolin 製作
// 製作日期：2014-02-16
// $Id:$
// ------------------------------------------------------------------------- //
xoops_loadLanguage('modinfo_common', 'tadtools');
define("_MI_ESAFTER_NAME","課後照顧");
define("_MI_ESAFTER_AUTHOR","prolin (prolin@tn.edu.tw)");
define("_MI_ESAFTER_CREDITS","prolin");
define("_MI_ESAFTER_DESC","課後照顧報名、費用計算");

define("_MI_ESAFTER_CONFIG_TITLE1","減免身份");
define("_MI_ESAFTER_CONFIG_DESC1","提供申請補助使用。分行設定");

define("_MI_ESAFTER_CONFIG_TITLE2","時段");
define("_MI_ESAFTER_CONFIG_DESC2","時段,金額，以逗號分隔。學生參加後面時段會一併加總前面時段的費用。(例 4:00,260;5:30,400)");

define("_MI_ESAFTER_CONFIG_TITLE3","年級");
define("_MI_ESAFTER_CONFIG_DESC3","開辦年級，以逗號分隔(3,4,5,6)");

define("_MI_ESAFTER_CONFIG_TITLE4","班別");
define("_MI_ESAFTER_CONFIG_DESC4","預計開辦班別，以逗號分隔(如A,B)");

define("_MI_ESAFTER_CONFIG_TITLE5","鐘點費佔成數");
define("_MI_ESAFTER_CONFIG_DESC5","預設鐘點費佔成數(0.7)");

define("_MI_ESAFTER_CONFIG_TITLE6","每班人數");
define("_MI_ESAFTER_CONFIG_DESC6","超過人數，需拆班");

define("_MI_ESAFTER_CONFIG_TITLE7","未滿15人鐘點費打折");
define("_MI_ESAFTER_CONFIG_DESC7","由0~15人，例:0, 0.7,0.7,0.7,0.7,0.7, 0.7,0.7,0.7,0.7,0.7,0.8,0.85,0.9,1 ,1");
?>
