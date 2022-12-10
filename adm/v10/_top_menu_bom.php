<?php
if (!defined('_GNUBOARD_')) exit;
// 카테고리 관리 상단 공통 탭 링크들입니다.

${'active_'.$g5['file_name']} = ' btn_top_menu_active';
$g5['container_sub_title'] = '
<h2 id="container_sub_title">
	<a href="./bom_list.php" class="btn_top_menu '.$active_bom_list.'">제품관리</a>
	<a href="./bom_category_list.php" class="btn_top_menu '.$active_bom_category_list.'">제품카테고리</a>
</h2>
';
?>
