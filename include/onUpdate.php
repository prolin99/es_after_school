<?php
use XoopsModules\Es_after_school\Update;
use XoopsModules\Tadtools\Utility;

function xoops_module_update_es_after_school(&$module, $old_version) {
    GLOBAL $xoopsDB;

    if( ! Update::chk_stud_id() ){
        Update::go_stud_id();
        Update::go_stud_id_full() ;
    }


    return true;
}


?>
