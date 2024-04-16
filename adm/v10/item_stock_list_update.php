<?php
$sub_menu = "922140";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button'] . " 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$tpy = ($_POST['act_button'] == '선택재고입고') ? 'input' : 'delete';

foreach ($_POST['chk'] as $bom_idx_v) {
    if (!$input_cnt[$bom_idx_v]) {
        alert('[' . $_POST['act_button'] . ']수량을 입력해 주세요.');
    }

    $input_cnt[$bom_idx_v] = preg_replace("/,/", "", $input_cnt[$bom_idx_v]);
}


foreach ($_POST['chk'] as $bom_idx_v) {
    if ($tpy == 'input') {
        $itm_sql = " INSERT INTO {$g5['item_table']}
            (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, itm_name, itm_part_no, itm_price, itm_value, itm_date, itm_type, itm_status, itm_auth_dt, itm_reg_dt, itm_update_dt) VALUES
        ";
        for ($i = 0; $i < $input_cnt[$bom_idx_v]; $i++) {
            $itm_sql .= ($i == 0) ? '' : ',';
            $itm_sql .= "('{$_SESSION['ss_com_idx']}','{$cst_idx_provider[$bom_idx_v]}','{$cst_idx_customer[$bom_idx_v]}','{$bom_idx_v}','{$bom_name[$bom_idx_v]}','{$bom_part_no[$bom_idx_v]}','{$bom_price[$bom_idx_v]}','1','" . G5_TIME_YMD . "','{$bom_type[$bom_idx_v]}','finish','" . G5_TIME_YMDHIS . "','" . G5_TIME_YMDHIS . "','" . G5_TIME_YMDHIS . "')";
        }
        // bom테이블의 입력한 재고수량을 더한 값으로 업데이트 해준다. 
        $bom_sql = " UPDATE {$g5['bom_table']} SET bom_stock = bom_stock + {$input_cnt[$bom_idx_v]} WHERE bom_idx = '{$bom_idx_v}' ";
        sql_query($bom_sql,1);
    } else if ($tpy == 'delete') {
        $itm_sql = " UPDATE {$g5['item_table']} 
                        SET itm_status = 'trash'
                            , itm_update_dt = '" . G5_TIME_YMDHIS . "'
                    WHERE itm_idx IN (
                        SELECT itm_idx 
                        FROM (
                            SELECT itm_idx FROM {$g5['item_table']}
                                WHERE bom_idx = '{$bom_idx_v}'
                                    AND itm_status IN ('ok','finish')
                                ORDER BY itm_reg_dt
                                LIMIT {$input_cnt[$bom_idx_v]}
                        ) m
                    )
        ";
        // bom테이블의 입력한 재고수량을 차감한 값으로 업데이트 해준다. 
        $bom_sql = " UPDATE {$g5['bom_table']} 
                        SET bom_stock = CASE
                            WHEN (bom_stock - {$input_cnt[$bom_idx_v]}) < 0 THEN 0
                            ELSE (bom_stock - {$input_cnt[$bom_idx_v]})
                            END
                    WHERE bom_idx = '{$bom_idx_v}' ";
        sql_query($bom_sql,1);
    }
    // echo $itm_sql."<br>";
    sql_query($itm_sql, 1);
}
// exit;

foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
                $qstr .= '&'.$key.'[]='.$v2;
            }
        }
        else {
            $qstr .= '&'.$key.'='.$value;
        }
    }
}

// exit;
goto_url('./item_stock_list.php?'.$qstr);
