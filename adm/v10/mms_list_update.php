<?php
$sub_menu = "940130";
include_once('./_common.php');

check_demo();

if (!(isset($_POST['chk']) && is_array($_POST['chk']))) {
    alert($_POST['act_button'] . " 하실 항목을 하나 이상 체크하세요.");
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();
$msg = '';
// print_r2($_POST);
// exit;
if ($_POST['act_button'] == "선택수정") {
    for ($i = 0; $i < count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;

        $mms_manual_yn[$k] = isset($mms_manual_yn[$k]) ? (int) $mms_manual_yn[$k] : 0;
        $mms_call_yn[$k] = isset($mms_call_yn[$k]) ? (int) $mms_call_yn[$k] : 0;
        $mms_item_check_yn[$k] = isset($mms_item_check_yn[$k]) ? (int) $mms_item_check_yn[$k] : 0;
        $mms_testmanual_yn[$k] = isset($mms_testmanual_yn[$k]) ? (int) $mms_testmanual_yn[$k] : 0;
        $mms_sort[$k] = isset($mms_sort[$k]) ? (int) $mms_sort[$k] : 0;
        
        $sql = " UPDATE {$g5['mms_table']}
                    SET mms_manual_yn = '{$mms_manual_yn[$k]}',
                        mms_call_yn = '{$mms_call_yn[$k]}',
                        mms_item_check_yn = '{$mms_item_check_yn[$k]}',
                        mms_testmanual_yn = '{$mms_testmanual_yn[$k]}',
                        mms_sort = '{$mms_sort[$k]}'
                WHERE mms_idx = '{$mms_idx[$k]}'
        ";
        // echo $sql."<br>";
        sql_query($sql);
    }
} elseif ($_POST['act_button'] == "선택삭제") {
    for ($i = 0; $i < count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = isset($_POST['chk'][$i]) ? (int) $_POST['chk'][$i] : 0;
        
        $sql = " UPDATE {$g5['mms_table']}
                    SET mms_status = 'trash'
                WHERE mms_idx = '{$mms_idx[$k]}'
        ";
        sql_query($sql);
    }
}

if ($msg) {
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);
}
// exit;
goto_url('./mms_list.php?' . $qstr);