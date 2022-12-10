<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
if($g5['dir_name'] == 'v10' && preg_match('/^index(\.)*/',$g5['file_name'])){
    include_once(G5_USER_ADMIN_PATH.'/_dashboard_menu_javascript.php');
}
include_once(G5_USER_ADMIN_PATH.'/admin.tail.php');
?>