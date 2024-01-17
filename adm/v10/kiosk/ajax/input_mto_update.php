<?php
include_once('./_common.php');
$res = array('ok' => true);

if(!$mto_idx){
    $res['ok'] = false;
    $res['msg'] = '발주ID번호가 넘어오지 않았습니다.';
}

if(!$mb_id_driver){
    $res['ok'] = false;
    $res['msg'] = '배송기사의 아이디가 제대로 넘어오지 않았습니다.';
}

//우선 해당 mto_idx정보가 존재하는지 확인한다.
$mto_sql = " SELECT * FROM {$g5['material_order_table']}
                WHERE mto_idx = '{$mto_idx}'
                    AND mto_status = 'ok'
";
$mto = sql_fetch($mto_sql);

if(!$mto['mto_idx']){
    $res['ok'] = false;
    $res['msg'] = '발주완료된 발주ID가 아닙니다.';
}

// 스캔한 기사분의 아이디를 해당 mto_idx레코드에 저장한다.
$drv_sql = " UPDATE {$g5['material_order_table']} SET 
                mb_id_driver = '{$mb_id_driver}'
            WHERE mto_idx = '{$mto_idx}'
";
sql_query($drv_sql);
// 해당mto_idx를 가지고, moi_status가 'ready'인  moi_idx레코드들을 추출한다.
$sql = " SELECT * FROM {$g5['material_order_item_table']} moi
            LEFT JOIN {$g5['bom_table']} bom ON moi.bom_idx = bom.bom_idx
        WHERE mto_idx = '{$mto_idx}'
            AND moi_status IN ('ready')
";
$result = sql_query($sql, 1);
$res['moi_chk_yn'] = 0;
if($result->num_rows){
    for($i=0;$row=sql_fetch_array($result);$i++){
        // 품질검사해야하는 제품일경우에는 검사제품이 존재한다라는 의미의 
        // $res['moi_chk_yn'] = 1 로한다.
        if($row['bom_stock_check_yn']){
            $res['moi_chk_yn'] = 1;
            continue;
        }

        //해당발주제품의 내용을 업데이트한다.
        $moi_sql = " UPDATE {$g5['material_order_item_table']} SET
                mb_id_driver = '{$mb_id_driver}'
                , moi_check_yn = '1'
                , moi_check_text = ''
                , moi_history = CONCAT(moi_history,'\ninput|".G5_TIME_YMDHIS."')
                , moi_status = 'input'
                , moi_input_dt = '".G5_TIME_YMDHIS."'
                , moi_update_dt = '".G5_TIME_YMDHIS."'
            WHERE moi_idx = '{$row['moi_idx']}'
        ";
        sql_query($moi_sql,1);

        // 혹시라도 존재할지 모르는 해당moi_idx가진 레코드를 삭제하고 생성하자
        $del_sql = " DELETE FROM {$g5['material_table']}
                    WHERE moi_idx = '{$row['moi_idx']}'
        ";
        sql_query($del_sql,1);

        //발주개수만큼 재고테이블에 입고처리한다.
        $mtr_sql = " INSERT INTO {$g5['material_table']}
            (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, moi_idx, mtr_name, mtr_part_no, mtr_price, mtr_value, mtr_date, mtr_type, mtr_status, mtr_auth_dt, mtr_reg_dt, mtr_update_dt) VALUES
        ";
        for($j=0;$j<$row['moi_count'];$j++){
            $mtr_sql .= ($j==0) ? '':',';
            $mtr_sql .= "('{$_SESSION['ss_com_idx']}','{$row['cst_idx_provider']}','{$row['cst_idx_customer']}','{$row['bom_idx']}', '{$row['moi_idx']}','{$row['bom_name']}','{$row['bom_part_no']}','{$row['bom_price']}','1','".G5_TIME_YMD."','material','ok','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."')";
        }
        sql_query($mtr_sql,1);
    }
}
else{
    $res['ok'] = false;
    $res['msg'] = '해당 발주ID의 발주제품이 존재하지 않습니다.';
}

echo json_encode($res);