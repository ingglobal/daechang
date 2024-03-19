<?php
$sub_menu = "922150";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();

// print_r2($_POST);exit;

if($mtyp == 'moi'){
    if($act_button == '선택수정'){
        foreach($_POST['chk'] as $moi_idx_v){
            if(!$moi_count[$moi_idx_v]){
                alert('발주량을 입력해 주세요.');
            }
            $moi_count[$moi_idx_v] = preg_replace("/,/","",$moi_count[$moi_idx_v]);
        }
    
        foreach($_POST['chk'] as $moi_idx_v){
            $moi_chk_yn = 0;
            $moi_chk_txt = '';
            $mb_chk = '';
            $moi_inp_dt = '0000-00-00 00:00:00';

            if($moi_status[$moi_idx_v] == 'input'){
                $moi_chk_yn = 1;
                $moi_inp_dt = G5_TIME_YMDHIS;
                $mb_chk = $member['mb_id'];
            } else if($moi_status[$moi_idx_v] == 'reject') {
                $moi_chk_txt = '반려처리';
                $mb_chk = $member['mb_id'];
            } else {
                ;
            }

            $moi_sql = " UPDATE {$g5['material_order_item_table']}
                            SET moi_count = '{$moi_count[$moi_idx_v]}'
                             , mb_id_check = '{$mb_chk}'
                             , moi_input_date = '{$moi_input_date[$moi_idx_v]}'
                             , moi_input_dt = '{$moi_inp_dt}'
                             , moi_check_yn = '{$moi_chk_yn}'
                             , moi_check_text = '{$moi_chk_txt}'
                             , moi_history = CONCAT(moi_history,'\n{$moi_status[$moi_idx_v]}|".G5_TIME_YMDHIS."')
                             , moi_status = '{$moi_status[$moi_idx_v]}'
                             , moi_update_dt = '".G5_TIME_YMDHIS."'
                        WHERE moi_idx = '{$moi_idx_v}'
            ";
            sql_query($moi_sql,1);

            //변동사항이 있을 수 있으므로 무조건 mto_price 갱신하자
            $moi = sql_fetch(" SELECT SUM(moi_price * moi_count) AS mto_price
                        FROM {$g5['material_order_item_table']}
                        WHERE mto_idx = '{$mto_idx[$moi_idx_v]}'
                        AND moi_status IN('pending','ok','used','delivery','scrap')
                        GROUP BY mto_idx
            ");

            $sql = " UPDATE {$g5['material_order_table']}
                        SET mto_price = '{$moi['mto_price']}'
                        WHERE mto_idx = '{$mto_idx[$moi_idx_v]}'
            ";
            sql_query($sql,1);

            // 혹시모를 이전 등록된 레코드를 삭제하거나, 실제로 reject시 삭제하기
            $mtr_del_sql = " DELETE FROM {$g5['material_table']} WHERE moi_idx = '{$moi_idx_v}'
            ";
            sql_query($mtr_del_sql,1);

            if($moi_status[$moi_idx_v] == 'reject'){

            } else if($moi_status[$moi_idx_v] == 'input'){
                $bom = get_table('bom','bom_idx',$bom_idx[$moi_idx_v]);
                //발주개수만큼 재고테이블에 입고처리한다.
                $mtr_sql = " INSERT INTO {$g5['material_table']}
                    (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, moi_idx, mtr_name, mtr_part_no, mtr_price, mtr_value, mtr_date, mtr_type, mtr_status, mtr_auth_dt, mtr_reg_dt, mtr_update_dt) VALUES
                ";

                for($i=0;$i<$moi_count[$moi_idx_v];$i++){
                    $mtr_sql .= ($i==0) ? '':',';
                    $mtr_sql .= "('{$_SESSION['ss_com_idx']}','{$bom['cst_idx_provider']}','{$bom['cst_idx_customer']}','{$bom['bom_idx']}', '{$moi_idx_v}','".addslashes($bom['bom_name'])."','{$bom['bom_part_no']}','{$bom['bom_price']}','1','".G5_TIME_YMD."','material','ok','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."')";
                }
                sql_query($mtr_sql,1);
            } else {

            }
        }
    }
    else if($act_button == '선택삭제'){
        foreach($_POST['chk'] as $moi_idx_v){
            $moi_sql = " UPDATE {$g5['material_order_item_table']}
                            SET moi_status = 'trash'
                             , moi_update_dt = '".G5_TIME_YMDHIS."'
                        WHERE moi_idx = '{$moi_idx_v}'
            ";
            sql_query($moi_sql,1);

            //변동사항이 있을 수 있으므로 무조건 mto_price 갱신하자
            $moi = sql_fetch(" SELECT SUM(moi_price * moi_count) AS mto_price
                        FROM {$g5['material_order_item_table']}
                        WHERE mto_idx = '{$mto_idx[$moi_idx_v]}'
                        AND moi_status IN('pending','ok','used','delivery','scrap')
                        GROUP BY mto_idx
            ");

            $sql = " UPDATE {$g5['material_order_table']}
                        SET mto_price = '{$moi['mto_price']}'
                        WHERE mto_idx = '{$mto_idx[$moi_idx_v]}'
            ";
            sql_query($sql,1);

            // trash시 삭제하기
            $mtr_del_sql = " DELETE FROM {$g5['material_table']} WHERE moi_idx = '{$moi_idx_v}'
            ";
            sql_query($mtr_del_sql,1);
        }
    }
}
else if($mtyp == 'mto'){
    if($act_button == '선택수정'){
        foreach($_POST['chk'] as $mto_idx_v){
            $mto_sql = " UPDATE {$g5['material_order_table']}
                            SET mto_type = '{$mto_type[$mto_idx_v]}'
                                , mto_location = '{$mto_location[$mto_idx_v]}'
                                , mto_input_date = '{$mto_input_date[$mto_idx_v]}'
                                , mto_status = '{$mto_status[$mto_idx_v]}'
                                , mto_update_dt = '".G5_TIME_YMDHIS."'
                            WHERE mto_idx = '{$mto_idx_v}'
            ";
            sql_query($mto_sql,1);

            //변동사항이 있을 수 있으므로 무조건 mto_price 갱신하자
            $moi = sql_fetch(" SELECT SUM(moi_price * moi_count) AS mto_price
                        FROM {$g5['material_order_item_table']}
                        WHERE mto_idx = '{$mto_idx_v}'
                        AND moi_status IN('pending','ok','used','delivery','scrap')
                        GROUP BY mto_idx
            ");

            $sql = " UPDATE {$g5['material_order_table']}
                        SET mto_price = '{$moi['mto_price']}'
                        WHERE mto_idx = '{$mto_idx_v}'
            ";
            sql_query($sql,1);
        }
    }
    else if($act_button == '선택삭제'){
        foreach($_POST['chk'] as $mto_idx_v){
            $mto_sql = " UPDATE {$g5['material_order_table']}
                            SET mto_status = 'trash'
                                , mto_update_dt = '".G5_TIME_YMDHIS."'
                            WHERE mto_idx = '{$mto_idx_v}'
            ";
            sql_query($mto_sql,1);

            $moi_sql = " UPDATE {$g5['material_order_item_table']}
                            SET moi_status = 'trash'
                                , moi_update_dt = '".G5_TIME_YMDHIS."'
                            WHERE mto_idx = '{$mto_idx_v}'
            ";
            sql_query($moi_sql,1);
        }
    }
}
if($mtyp){
    $qstr .= '&mtyp='.$mtyp; 
}
if($sch_from_date){
    $qstr .= '&sch_from_date='.$sch_from_date; 
}
if($sch_to_date){
    $qstr .= '&sch_to_date='.$sch_to_date; 
}
goto_url('./material_order_list.php?'.$qstr);