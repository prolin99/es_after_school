<?php

namespace XoopsModules\Es_after_school;

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{


    public static function chk_stud_id()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`stud_id`) FROM ' . $xoopsDB->prefix('afdb_sign');
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_stud_id()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE  ' . $xoopsDB->prefix('afdb_sign') . "
        ADD `stud_id` varchar(20) NOT NULL,
        ADD `class_sit_num` tinyint(4) NOT NULL
        ";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
    }

    public static function go_stud_id_full()
    {
        global $xoopsDB;
        //$sql = ' SELECT A.* , S.stud_id as Ostud_id , S.class_sit_num as Oclass_sit_num  FROM    ' . $xoopsDB->prefix('afdb_sign') . "  A  , "  . $xoopsDB->prefix('e_student') .  "  S  " .
        //"      where A.class_id_base = S.class_id    and A.stud_name= S.name    ";

        $sql =" SELECT A.* , S.stud_id as Ostud_id , S.class_sit_num as Oclass_sit_num  FROM  " .   $xoopsDB->prefix('afdb_sign') . "  A   LEFT JOIN  "  .  $xoopsDB->prefix('e_student') .  "  S  " .
                     " ON   S.class_id=A.class_id_base    and   S.name= A.stud_name  "  ;

     	$result = $xoopsDB->queryF($sql) ;
     	while($row=$xoopsDB->fetchArray($result)){

            $sql_U = " UPDATE   "  . $xoopsDB->prefix("afdb_sign") .
                " SET   `stud_id`='{$row['Ostud_id']}' , `class_sit_num`='{$row['Oclass_sit_num']}'  WHERE id = '{$row['id']}'  " ;
            $xoopsDB->queryF($sql_U)     or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
		}
    }

}
