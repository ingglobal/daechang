<?php
$sub_menu = "940115";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');
// print_r2($_POST);
// foreach($_POST['chk'] as $i => $k){
//     echo $i."->".$k."<br>";
// }
// exit;
if($w == 'u') {
    foreach($_POST['chk'] as $k){
		$sql = " UPDATE {$g5['customer_table']} SET
                    cst_status = '{$_POST['cst_status'][$k]}'
                WHERE cst_idx = '{$_POST['cst_idx'][$k]}' ";
        sql_query($sql,1);
    }

}
// 삭제할 때
else if($w == 'd') {
    foreach($_POST['chk'] as $i => $k){

        // 레코드 삭제
        $sql = " UPDATE {$g5['customer_table']} SET cst_status = 'trash' WHERE cst_idx = '{$_POST['cst_idx'][$k]}' ";
        sql_query($sql,1);

        $csql = " UPDATE {$g5['customer_member_table']} SET ctm_status = 'trash' WHERE cst_idx = '{$_POST['cst_idx'][$k]}' ";
        sql_query($csql,1);
    }
}

if ($msg)
    alert($msg);
    //echo '<script> alert("'.$msg.'"); </script>';
	
goto_url('./customer_list.php?'.$qstr.'&amp;ser_trm_idx_com_type='.$ser_trm_idx_com_type.'&amp;ser_trm_idx_salesarea='.$ser_trm_idx_salesarea, false);
?>
