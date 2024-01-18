<?php
$sub_menu = "922150";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');
// print_r2($_POST);exit;
// 추가로 확장해서 넘겨야 할 변수들
if($mtyp){
    $qstr .= '&mtyp='.$mtyp; 
}
if($sch_from_date){
    $qstr .= '&sch_from_date='.$sch_from_date; 
}
if($sch_to_date){
    $qstr .= '&sch_to_date='.$sch_to_date; 
}

if($mtyp == 'mto'){
    //addslashes($row2['mto_memo'])
    if(!$cst_idx)
        alert('공급업체를 반드시 선택해 주세요.');
    if(!$mto_input_date)
        alert('납기일을 반드시 설정해 주세요.');
    
    $mto_memo = addslashes($mto_memo);
    if($w == ''){
        $mto_sql = " INSERT INTO {$g5['material_order_table']}
                        SET com_idx = '{$_SESSION['ss_com_idx']}'
                            , cst_idx = '{$cst_idx}'
                            , mb_id = '{$member['mb_id']}'
                            , mto_input_date = '{$mto_input_date}'
                            , mto_memo = '{$mto_memo}'
                            , mto_status = '{$mto_status}'
                            , mto_reg_dt = '".G5_TIME_YMDHIS."'
                            , mto_update_dt = '".G5_TIME_YMDHIS."'
        ";
        sql_query($mto_sql,1);
        $mto_idx = sql_insert_id();
    }
    else if($w == 'u'){
        $mto_sql = " UPDATE {$g5['material_order_table']}
                SET cst_idx = '{$cst_idx}'
                    , mto_type = '{$mto_type}'
                    , mto_input_date = '{$mto_input_date}'
                    , mto_memo = '{$mto_memo}'
                    , mto_status = '{$mto_status}'
                    , mto_update_dt = '".G5_TIME_YMDHIS."'
            WHERE mto_idx = '{$mto_idx}'
        ";
        sql_query($mto_sql,1);
    }
}
else if($mtyp == 'moi'){
    if(!$mto_idx)
        alert('발주ID를 반드시 선택해 주세요.');
    if(!$bom_idx)
        alert('제품을을 반드시 선택해 주세요.');
    if(!$moi_count)
        alert('발주수량을 반드시 설정해 주세요.');
    if(!$moi_input_date)
        alert('납기일을 반드시 설정해 주세요.');
    
    $bom = sql_fetch(" SELECT * FROM {$g5['bom_table']} WHERE bom_idx = '{$bom_idx}' ");
    $bom_stock_check_yn = $bom['bom_stock_check_yn'];
    
    $moi_count = preg_replace("/,/","",$moi_count);
    $moi_memo = addslashes($moi_memo);
    if($w == ''){
        //동일한 발주ID에 동일한 제품이 존재하면 반려
        $chk_sql = " SELECT COUNT(*) AS cnt FROM {$g5['material_order_item_table']}
                WHERE mto_idx = '{$mto_idx}'
                    AND bom_idx = '{$bom_idx}'
                    AND moi_status NOT IN ('trash','delete');
        ";
        $chk = sql_fetch($chk_sql);

        if($chk['cnt'])
            alert('동일한 발주ID에 동일한 제품이 이미 등록되어 있습니다.');
        $bom = sql_fetch(" SELECT * FROM {$g5['bom_table']} WHERE bom_idx = '{$bom_idx}' ");
        $moi_checked_yn = ($bom_stock_check_yn) ? '0' : '1';

        $moi_input_dt = '0000-00-00 00:00:00';

        if($moi_status == 'input'){
            $moi_checked_yn = 1;
            $moi_input_dt = G5_TIME_YMDHIS;
            $mb_id_check = $member['mb_id'];
        } else if($moi_status == 'reject') {
            $moi_check_text = '반려처리';
            $mb_id_check = $member['mb_id'];
        } else {
            ;
        }

        $moi_sql = " INSERT INTO {$g5['material_order_item_table']}
                        SET mto_idx = '{$mto_idx}'
                            , bom_idx = '{$bom_idx}'
                            , moi_count = '{$moi_count}'
                            , moi_price = '{$bom['bom_price']}'
                            , mb_id_driver = '{$mb_id_driver}'
                            , mb_id_check = '{$mb_id_check}'
                            , moi_input_date = '{$moi_input_date}'
                            , moi_input_dt = '{$moi_input_dt}'
                            , moi_check_yn = '{$moi_checked_yn}'
                            , moi_memo = '{$moi_memo}'
                            , moi_check_text = '{$moi_check_text}'
                            , moi_status = '{$moi_status}'
                            , moi_history = CONCAT(moi_history,'\n{$moi_status}|".G5_TIME_YMDHIS."')
                            , moi_reg_dt = '".G5_TIME_YMDHIS."'
                            , moi_update_dt = '".G5_TIME_YMDHIS."'
        ";
        sql_query($moi_sql,1);
        $moi_idx = sql_insert_id();

        //변동사항이 있을 수 있으므로 무조건 mto_price 갱신하자
        // $moi = sql_fetch(" SELECT SUM(moi_price * moi_count) AS mto_price
        //         FROM {$g5['material_order_item_table']}
        //         WHERE mto_idx = '{$mto_idx]}'
        //         AND moi_status IN('pending','ok','used','delivery','scrap')
        //         GROUP BY mto_idx
        // ");

        // $sql = " UPDATE {$g5['material_order_table']}
        //         SET mto_price = '{$moi['mto_price']}'
        //         WHERE mto_idx = '{$mto_idx[$moi_idx_v]}'
        // ";
        // sql_query($sql,1);

        // 혹시모를 이전 등록된 레코드를 삭제하거나, 실제로 reject시 삭제하기
        $mtr_del_sql = " DELETE FROM {$g5['material_table']} WHERE moi_idx = '{$moi_idx}'
        ";
        sql_query($mtr_del_sql,1);

        if($moi_status == 'reject'){

        } else if($moi_status == 'input'){
            //발주개수만큼 재고테이블에 입고처리한다.
            $mtr_sql = " INSERT INTO {$g5['material_table']}
                (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, moi_idx, mtr_name, mtr_part_no, mtr_price, mtr_value, mtr_date, mtr_type, mtr_status, mtr_auth_dt, mtr_reg_dt, mtr_update_dt) VALUES
            ";

            for($i=0;$i<$moi_count;$i++){
                $mtr_sql .= ($i==0) ? '':',';
                $mtr_sql .= "('{$_SESSION['ss_com_idx']}','{$bom['cst_idx_provider']}','{$bom['cst_idx_customer']}','{$bom['bom_idx']}', '{$moi_idx}','{$bom['bom_name']}','{$bom['bom_part_no']}','{$bom['bom_price']}','1','".G5_TIME_YMD."','material','ok','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."')";
            }
            sql_query($mtr_sql,1);
        } else {

        }

    }
    else if($w == 'u'){
        $moi_input_dt = '0000-00-00 00:00:00';

        if($moi_status == 'input'){
            $moi_checked_yn = 1;
            $moi_input_dt = G5_TIME_YMDHIS;
            $mb_id_check = $member['mb_id'];
        } else if($moi_status == 'reject') {
            $moi_check_text = '반려처리';
            $mb_id_check = $member['mb_id'];
        } else {
            ;
        }

        $moi_sql = " UPDATE {$g5['material_order_item_table']}
                        SET moi_count = '{$moi_count}'
                            , mb_id_driver = '{$mb_id_driver}'
                            , mb_id_check = '{$mb_id_check}' 
                            , moi_input_date = '{$moi_input_date}'
                            , moi_input_dt = '{$moi_input_dt}'
                            , moi_check_yn = '{$moi_checked_yn}'
                            , moi_memo = '{$moi_memo}'
                            , moi_history = CONCAT(moi_history,'\n{$moi_status}|".G5_TIME_YMDHIS."')
                            , moi_check_text = '{$moi_check_text}'
                            , moi_status = '{$moi_status}'
                            , moi_update_dt = '".G5_TIME_YMDHIS."'
                    WHERE moi_idx = '{$moi_idx}'
        ";
        sql_query($moi_sql,1);

        // 혹시모를 이전 등록된 레코드를 삭제하거나, 실제로 reject시 삭제하기
        $mtr_del_sql = " DELETE FROM {$g5['material_table']} WHERE moi_idx = '{$moi_idx}'
        ";
        sql_query($mtr_del_sql,1);

        if($moi_status == 'reject'){

        } else if($moi_status == 'input'){
            // $bom = get_table('bom','bom_idx',$bom_idx);
            //발주개수만큼 재고테이블에 입고처리한다.
            $mtr_sql = " INSERT INTO {$g5['material_table']}
                (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, moi_idx, mtr_name, mtr_part_no, mtr_price, mtr_value, mtr_date, mtr_type, mtr_status, mtr_auth_dt, mtr_reg_dt, mtr_update_dt) VALUES
            ";

            for($i=0;$i<$moi_count;$i++){
                $mtr_sql .= ($i==0) ? '':',';
                $mtr_sql .= "('{$_SESSION['ss_com_idx']}','{$bom['cst_idx_provider']}','{$bom['cst_idx_customer']}','{$bom['bom_idx']}', '{$moi_idx}','{$bom['bom_name']}','{$bom['bom_part_no']}','{$bom['bom_price']}','1','".G5_TIME_YMD."','material','ok','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."')";
            }
            sql_query($mtr_sql,1);
        } else {

        }
    }
}
//변동사항이 있을 수 있으므로 무조건 mto_price 갱신하자
$moi = sql_fetch(" SELECT SUM(moi_price * moi_count) AS mto_price
                FROM {$g5['material_order_item_table']}
            WHERE mto_idx = '{$mto_idx}'
                AND moi_status IN('pending','ok','used','delivery','scrap')
            GROUP BY mto_idx
");

$sql = " UPDATE {$g5['material_order_table']}
            SET mto_price = '{$moi['mto_price']}'
        WHERE mto_idx = '{$mto_idx}'
";
sql_query($sql,1);

$qstr .= '&w=u&'.$mtyp.'_idx='.${$mtyp.'_idx'};
// echo $qstr;exit;
goto_url('./material_order_form.php?'.$qstr,false);