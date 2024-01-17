<?php
$sub_menu = "922130";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$tpy = ($_POST['act_button'] == '선택재고입고')?'input':'delete';

foreach($_POST['chk'] as $bom_idx_v) {
    if(!$input_cnt[$bom_idx_v]){
        alert('['.$_POST['act_button'].']수량을 입력해 주세요.');
    }

    $input_cnt[$bom_idx_v] = preg_replace("/,/","",$input_cnt[$bom_idx_v]);
}


foreach($_POST['chk'] as $bom_idx_v){
    if($tpy == 'input'){
        $mtr_sql = " INSERT INTO {$g5['material_table']}
            (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, mtr_name, mtr_part_no, mtr_price, mtr_value, mtr_date, mtr_type, mtr_status, mtr_auth_dt, mtr_reg_dt, mtr_update_dt) VALUES
        ";
        for($i=0;$i<$input_cnt[$bom_idx_v];$i++){
            $mtr_sql .= ($i==0) ? '':',';
            $mtr_sql .= "('{$_SESSION['ss_com_idx']}','{$cst_idx_provider[$bom_idx_v]}','{$cst_idx_customer[$bom_idx_v]}','{$bom_idx_v}','{$bom_name[$bom_idx_v]}','{$bom_part_no[$bom_idx_v]}','{$bom_price[$bom_idx_v]}','1','".G5_TIME_YMD."','{$bom_type[$bom_idx_v]}','ok','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."')";
        }
    }
    else if($tpy == 'delete') {
        $mtr_sql = " UPDATE {$g5['material_table']} 
                        SET mtr_status = 'trash'
                            , mtr_update_dt = '".G5_TIME_YMDHIS."'
                    WHERE mtr_idx IN (
                        SELECT mtr_idx 
                        FROM (
                            SELECT mtr_idx FROM {$g5['material_table']}
                                WHERE bom_idx = '{$bom_idx_v}'
                                    AND mtr_status = 'ok'
                                ORDER BY mtr_reg_dt
                                LIMIT {$input_cnt[$bom_idx_v]}
                        ) m
                    )
        ";
    }
    // echo $mtr_sql."<br>";
    sql_query($mtr_sql,1);
}
// exit;
goto_url('./material_input_list.php?'.$qstr.'&ser_bom_type='.$ser_bom_type);