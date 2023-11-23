<?php
$sub_menu = "940112";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

// print_r2($_POST);
// exit;
auth_check($auth[$sub_menu], 'w');

check_admin_token();

if ($_POST['act_button'] == "선택수정") {
    // print_r2($_POST);exit;
    foreach($_POST['chk'] as $i){
        // alert($_POST['mb_8'][$i] == null);
        if($_POST['mb_8'][$i] != null){
            $chk_sql = " SELECT COUNT(*) AS cnt FROM {$g5['member_table']} 
                                WHERE mb_8 = '{$_POST['mb_8'][$i]}' 
                                    AND mb_leave_date = '' 
                                    AND mb_intercept_date = '' 
                                    AND mb_7 = 'ok' 
                                    AND mb_id != '{$_POST['mb_id'][$i]}' ";
            $chk_res = sql_fetch($chk_sql);
            if($chk_res['cnt']) continue;
        }

        $mb = get_member($_POST['mb_id'][$i]);

        if (!$mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 회원자료가 존재하지 않습니다.\\n';
        } else {
            
            $sql = "UPDATE {$g5['member_table']} SET
                        mb_8 = '".sql_real_escape_string($_POST['mb_8'][$i])."'
                    WHERE mb_id = '".sql_real_escape_string($_POST['mb_id'][$i])."'
            ";
            // echo $sql.'<br>';
            sql_query($sql,1);
        }
        
    }

} else if ($_POST['act_button'] == "선택탈퇴") {
    foreach($_POST['chk'] as $k){
    // for ($i=0; $i<count($_POST['chk']); $i++){
        // 실제 번호를 넘김
        // $k = $_POST['chk'][$i];


        $mb = get_member($_POST['mb_id'][$k]);

        if (!$mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 회원자료가 존재하지 않습니다.\\n';
        } else if ($member['mb_id'] == $mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 로그인 중인 관리자는 삭제 할 수 없습니다.\\n';
        } else if (is_admin($mb['mb_id']) == 'super') {
            $msg .= $mb['mb_id'].' : 최고 관리자는 삭제할 수 없습니다.\\n';
        } else if ($is_admin != 'super' && $mb['mb_level'] > $member['mb_level']) {
            $msg .= $mb['mb_id'].' : 자신보다 권한이 높거나 같은 회원은 삭제할 수 없습니다.\\n';
        } else {
			// 회원자료 삭제
			//member_delete($mb['mb_id']);
			// 직원 탈퇴 처리
			// 삭제(탈퇴)일자 입력
			$sql = "UPDATE {$g5['member_table']} SET
                        mb_leave_date = '".date('Ymd', G5_SERVER_TIME)."'
                    WHERE mb_id = '".$mb['mb_id']."'
            ";
			sql_query($sql,1);

			// 사원자료 초기화
            $mb_memo = date('Y-m-d H:i', G5_SERVER_TIME)." 탈퇴처리 by ".$member['mb_name']."\n".$mb['mb_memo'];
			$sql = "	UPDATE {$g5['member_table']} SET 
							mb_level = 1
							, mb_memo = '".$mb_memo."'
						WHERE mb_id = '".$mb['mb_id']."' ";
			sql_query($sql);
        }
    }
}

if ($msg)
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);

// exit;
goto_url('./worker_list.php?'.$qstr);
?>
