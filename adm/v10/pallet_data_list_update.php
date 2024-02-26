<?php
$sub_menu = "922160";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();
if ($_POST['act_button'] == "선택빠레트추가") {
    foreach($chk as $cv){
        //
        if($pck_cnt[$cv] && $plt_cnt[$cv]){
            // 재고중에 빠레트에 담을 재고가 존재하는지 확인하자
            $isql = " SELECT COUNT(*) AS cnt, MAX(itm_reg_dt) AS itm_max_dt, itm_date FROM {$g5['item_table']} 
                        WHERE pri_idx = '{$pri_idx[$cv]}' 
                            AND plt_idx = 0
                            AND itm_status IN ('finish','check')
            ";
            // echo $isql;exit;
            $ires = sql_fetch($isql);

            // 재고가 있어야 빠레트에 담지
            if($ires['cnt']){
                $last_dt = get_secAddDateTime($ires['itm_max_dt'],60); //마지막 제품완료뒤 60초후 빠레트 출력작업 시작
                $last_time = substr($ires['itm_max_dt'], 11, 8);
                $dlv_date = get_dayAddDate($ires['itm_date'],1);// 통계일 다음날이 출하일이다.
                $dlv_dt = $dlv_date.' '.$last_time;

                //현 재고량으로 몇 개의 빠레트에 담을 수 있지?
                $vp_cnt = ceil($ires['cnt'] / $pck_cnt[$cv]);// 소수점 나와도 1개의 파레트에 담긴걸로 하자
                $p_cnt = ($vp_cnt < $plt_cnt[$cv]) ? $vp_cnt : $plt_cnt[$cv];
                
                $sec1 = 7;
                $sec2 = 13;
                for($i=0;$i<$p_cnt;$i++) {
                    // 빠레트 생성
                    $plt_sql = " INSERT INTO {$g5['pallet_table']} SET
                                com_idx = '{$g5['setting']['set_com_idx']}'
                                , mb_id_delivery = '{$dlv_mb_id[$cv]}'
                                , mb_id_worker = '{$mb_id[$cv]}'
                                , mms_idx = '{$mms_idx[$cv]}'
                                , plt_check_yn = '1'
                                , plt_status = 'delivery'
                                , plt_date = '{$dlv_date}'
                                , plt_reg_dt = '{$last_dt}'
                                , plt_update_dt = '{$dlv_dt}'
                    ";
                    sql_query($plt_sql,1);
                    $plt_idx = sql_insert_id();
                    // echo $plt_sql."<br>";
                    $sql = " UPDATE {$g5['item_table']} SET
                                plt_idx = '{$plt_idx}'
                                , itm_status = 'delivery'
                                , itm_update_dt = '{$dlv_dt}'
                            WHERE com_idx = '{$g5['setting']['set_com_idx']}'
                                AND plt_idx = '0'
                                AND pri_idx = '{$pri_idx[$cv]}'
                                AND bom_idx = '{$bom_idx[$cv]}'
                                AND itm_status IN ('finish','check')
                            ORDER BY itm_reg_dt
                            LIMIT {$pck_cnt[$cv]}
                    ";
                    sql_query($sql,1);
                    // echo $sql."<br><br><br>";
                    $last_dt = get_secAddDateTime($last_dt,$sec1);
                    $dlv_dt = get_secAddDateTime($dlv_dt,$sec2);
                }
            }
        }
    }
}
else if($_POST['act_button'] == "선택빠레트삭제"){
    foreach($chk as $cv) {
        // 빠레트 수량이 0개이상이면 초기화 할 수 있다.
        if($plt_stock[$cv]){
            $sqlp = " SELECT DISTINCT plt_idx FROM {$g5['item_table']} 
                        WHERE pri_idx = '{$pri_idx[$cv]}'
                            AND plt_idx != '0' 
                            AND itm_status = 'delivery'
            ";
            $resp = sql_query($sqlp);
            for($i=0;$row=sql_fetch_array($resp);$i++){
                // 해당 plt_idx를 가지고 있는 itm재고 레코드를 적재출하 이전의 재고상태로 돌려 놓는다.
                $sqli = "  UPDATE {$g5['item_table']} SET
                            plt_idx = 0
                            , itm_status = 'finish'
                        WHERE com_idx = '{$g5['setting']['set_com_idx']}'
                            AND plt_idx = '{$row['plt_idx']}'
                            AND pri_idx = '{$pri_idx[$cv]}'
                            AND bom_idx = '{$bom_idx[$cv]}'
                            AND itm_status = 'delivery'
                ";
                sql_query($sqli,1);
    
                // 해당 빠레트를 삭제해라
                $sqlp2 = " DELETE FROM {$g5['pallet_table']} WHERE plt_idx = '{$row['plt_idx']}'";
                sql_query($sqlp2,1);
            }
        }
    }
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

goto_url('./pallet_data_list.php?'.$qstr);