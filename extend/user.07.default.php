<?php

// 로그인을 할 때마다 로그 파일 삭제해야 용량을 확보할 수 있음 
if(basename($_SERVER["SCRIPT_FILENAME"]) == 'login_check.php') {
	// 지난시간을 초로 계산해서 적어주시면 됩니다.
	$del_time_interval = 3600 * 2;	// Default = 2 시간

	// 이력서 파일 삭제
	if ($dir=@opendir(G5_DATA_PATH.'/resume')) {
	    while($file=readdir($dir)) {
            if($file == '.' || $file == '..')
                continue;

            $each_file = G5_DATA_PATH.'/resume/'.$file;
//            echo $each_file.'<br>';
	        if (!$atime=@fileatime($each_file))
	            continue;
	        if (time() > $atime + $del_time_interval)
	            unlink($each_file);
	    }
    }
}


$cache_file = G5_DATA_PATH.'/cache/mms-code.php';
if( file_exists($cache_file) ) {
    include($cache_file);
}
$cache_file = G5_DATA_PATH.'/cache/mms-setting.php';
if( file_exists($cache_file) ) {
    include($cache_file);
}
$cache_file = G5_DATA_PATH.'/cache/socket-setting.php';
if( file_exists($cache_file) ) {
    include($cache_file);
}
$cache_file = G5_DATA_PATH.'/cache/socket-alarm.php';
if( file_exists($cache_file) ) {
    include($cache_file);
}



// 뿌리오 발송결과
$set_values = explode("\n", $g5['setting']['set_ppurio_call_status']);
foreach ($set_values as $set_value) {
	list($key, $value) = explode('=', trim($set_value));
    if($key&&$value) {
        $g5['set_ppurio_call_status'][$key] = $value.' ('.$key.')';
        $g5['set_ppurio_call_status_value'][$key] = $value;
        $g5['set_ppurio_call_status_options'] .= '<option value="'.trim($key).'">'.trim($value).' ('.$key.')</option>';
        $g5['set_ppurio_call_status_value_options'] .= '<option value="'.trim($key).'">'.trim($value).'</option>';
    }
}
unset($set_values);unset($set_value);

// 디비테이블명
$set_values = explode("\n", $g5['setting']['set_db_table_name']);
foreach ($set_values as $set_value) {
	list($key, $value) = explode('=', trim($set_value));
    if($key&&$value) {
        $g5['set_db_table_name'][$key] = $value.' ('.$key.')';
        $g5['set_db_table_name_value'][$key] = $value;
    }
}
unset($set_values);unset($set_value);

// 디비테이블명 스킵해야 할 디비명
$set_values = explode("\n", $g5['setting']['set_db_table_skip']);
foreach ($set_values as $set_value) {
    if(trim($set_value)) {
        $g5['set_db_table_skip'][] = trim($set_value);
    }
}
unset($set_values);unset($set_value);


// 데이타그룹, 데이터그룹별 그래프 초기값도 추출
$set_values = explode(',', preg_replace("/\s+/", "", $g5['setting']['set_data_group']));
foreach ($set_values as $set_value) {
	list($key, $value) = explode('=', trim($set_value));
	$g5['set_data_group'][$key] = $value.' ('.$key.')';
	$g5['set_data_group_value'][$key] = $value;
	$g5['set_data_group_radios'] .= '<label for="set_data_group_'.$key.'" class="set_data_group"><input type="radio" id="set_data_group_'.$key.'" name="set_data_group" value="'.$key.'">'.$value.'</label>';
	$g5['set_data_group_options'] .= '<option value="'.trim($key).'">'.trim($value).' ('.trim($key).')</option>';
	$g5['set_data_group_value_options'] .= '<option value="'.trim($key).'">'.trim($value).'</option>';
    
    // 데이타 그룹별 그래프 디폴트값 추출, $g5['set_graph_run']['default1'], $g5['set_graph_err']['default4'] 등과 같은 배열값으로 디폴트값 추출됨
    $set_values1 = explode(',', preg_replace("/\s+/", "", $g5['setting']['set_graph_'.$key]));
    for($i=0;$i<sizeof($set_values1);$i++) {
        $g5['set_graph_'.$key]['default'.$i] = $set_values1[$i];
    }
    // print_r3($g5['set_graph_'.$key]);
    unset($set_values1);unset($set_value1);
}
unset($set_values);unset($set_value);
// print_r3($g5['set_data_group_value']);

// 불량입력 엑셀항목 설정, 쉼표가 있고 띄어쓰기도 있어서 따로 추출합니다.
$set_values = explode('|', $g5['setting']['set_return_item']);
foreach ($set_values as $set_value) {
	list($key, $value) = explode('=', trim($set_value));
	$g5['set_return_item2'][$key] = $value.' ('.$key.')';
	$g5['set_return_item_value2'][$key] = $value;
	$g5['set_return_item_options2'] .= '<option value="'.trim($key).'">'.trim($value).' ('.trim($key).')</option>';
	$g5['set_return_item_value_options2'] .= '<option value="'.trim($key).'">'.trim($value).'</option>';
}
unset($set_values);unset($set_value);


// 단위별(분,시,일,주,월,년) 초변환수
// 첫번째 변수 = 단위별 초단위 전환값
// 두번째 변수 = 종료일(or시작일)계산시 선택단위, 0이면 기존 선택된 단위값, 아니면 해당숫자 
$seconds = array(
    "daily"=>array(86400,1)
    ,"weekly"=>array(604800,1)
    ,"monthly"=>array(2592000,1)
    ,"yearly"=>array(31536000,1)
    ,"minute"=>array(60,0)
    ,"second"=>array(1,0)
);
$seconds_text = array(
    "86400"=>'일간'
    ,"604800"=>'주간'
    ,"2592000"=>'월간'
    ,"31536000"=>'년간'
    ,"60"=>'분단위'
    ,"1"=>'초단위'
);

// BOM구성 표시
$g5['set_bom_type_displays'] = explode(',', preg_replace("/\s+/", "", $g5['setting']['set_bom_type_display']));

//설비배열
$mms_sql = " SELECT mms_idx,mms_name,mms_serial_no FROM {$g5['mms_table']}
                WHERE mms_status = 'ok'
                    AND com_idx = '{$g5['setting']['set_com_idx']}'
                    AND mms_serial_no != ''
                ORDER BY mms_sort, mms_idx
";
$mms_res = sql_query($mms_sql,1);
$g5['mms_arr'] = array();
$g5['mms_options'] = '';
$g5['mms_options_idx'] = '';
for($j=0;$mrow=sql_fetch_array($mms_res);$j++){
    if(!array_key_exists($mrow['mms_idx'],$g5['mms_arr'])){
        $g5['mms_arr'][$mrow['mms_idx']] = $mrow['mms_name'];
        $g5['mms_options'] .= '<option value="'.$mrow['mms_idx'].'">'.$mrow['mms_name'].'</option>'.PHP_EOL;
        $g5['mms_options_idx'] .= '<option value="'.$mrow['mms_idx'].'">'.$mrow['mms_name'].' ('.$mrow['mms_idx'].')</option>'.PHP_EOL;
        $g5['mms_options_no'] .= '<option value="'.$mrow['mms_idx'].'">'.$mrow['mms_name'].' ('.$mrow['mms_serial_no'].')</option>'.PHP_EOL;
    }
}

$jig_sql = " SELECT GROUP_CONCAT(DISTINCT boj_code) AS boj_codes FROM {$g5['bom_jig_table']} ";
$jig_res = sql_fetch($jig_sql);
$g5['jig_arr'] = ($jig_res['boj_codes'])?explode(',',$jig_res['boj_codes']):array();
$g5['jig_options'] = '';
for($j=0;$j<sizeof($g5['jig_arr']);$j++){
    $g5['jig_options'] .= '<option value="'.$g5['jig_arr'][$j].'">'.$g5['jig_arr'][$j].'</option>'.PHP_EOL;
}


$g5['mmw_arr'] = array();
//설비별 담당자배열
$mmw_sql = " SELECT mmw.mms_idx
                , mmw.mb_id
                , mb.mb_name
                , mmw_type
                , mmw_sort
            FROM {$g5['mms_worker_table']} mmw
                LEFT JOIN {$g5['member_table']} mb ON mmw.mb_id = mb.mb_id
            WHERE mmw_status IN ('ok')
                -- AND mms_name REGEXP '([^포장] | [^검사])$'
                AND mmw.mb_id NOT IN ('없음', '')
                AND mb.mb_name != ''
                AND mb.mb_8 != ''
            ORDER BY mmw.mms_idx, mmw_sort
            ";
$mmw_res = sql_query($mmw_sql,1);

$mmw_types = array(
    'day'=>'주'
    ,'night'=>'야'
    ,'sub'=>'부'
    ,''=>'부'
);

for($l=0;$wrow=sql_fetch_array($mmw_res);$l++){
    if(!array_key_exists($wrow['mms_idx'],$g5['mmw_arr'])){
        $g5['mmw_arr'][$wrow['mms_idx']] = array(
            $wrow['mb_id'] => $wrow['mb_name'].'('.$mmw_types[$wrow['mmw_type']].')'
        );
    }
    else{
        $g5['mmw_arr'][$wrow['mms_idx']][$wrow['mb_id']] = $wrow['mb_name'].'('.$mmw_types[$wrow['mmw_type']].')';
    }   
}
unset($mms_sql);
unset($mms_res);
unset($jig_sql);
unset($jig_res);
unset($mmw_sql);
unset($mmw_res);
unset($mrow);
unset($wrow);
unset($l);
// print_r2($g5['mmw_arr']);

//현장 작업자배열
$mbw_sql = " SELECT mb_id
                    , mb_name
                    , mb_8
            FROM {$g5['member_table']}
            WHERE mb_leave_date = ''
                AND mb_intercept_date = ''
                AND mb_7 = 'ok'
                AND mb_8 != ''
            -- ORDER BY CAST(mb_8 AS UNSIGNED)
            ORDER BY mb_name
 ";
$mbw_res = sql_query($mbw_sql,1);
$g5['mbw_options'] = '';
$g5['mbw_options_no'] = '';
for($i=0;$mrow=sql_fetch_array($mbw_res);$i++){
    $g5['mbw_options'] .= '<option value="'.$mrow['mb_id'].'">'.$mrow['mb_name'].'</option>'.PHP_EOL;
    $g5['mbw_options_no'] .= '<option value="'.$mrow['mb_id'].'">'.$mrow['mb_name'].' ('.$mrow['mb_8'].')</option>'.PHP_EOL;
}

unset($mbw_sql);
unset($mbw_res);
unset($mrow);
unset($i);

//카테고리 관련 배열
$cat_sql = " SELECT bct_idx, bct_name FROM {$g5['bom_category_table']} WHERE com_idx = '{$_SESSION['ss_com_idx']}' ORDER BY bct_order,bct_idx ";
$cat_res = sql_query($cat_sql,1);
$g5['cats_key_val'] = array();
$g5['cats_val_key'] = array();
for($i=0;$row=sql_fetch_array($cat_res);$i++){
    $g5['cats_key_val'][$row['bct_idx']] = $row['bct_name'];
    $g5['cats_val_key'][$row['bct_name']] = $row['bct_idx'];
    $g5['cats_options'] .= '<option value="'.$row['bct_idx'].'">'.$row['bct_name'].'</option>'.PHP_EOL;
}
unset($cat_sql);
unset($cat_res);
unset($i);

//자재공급업체 배열
$prv_sql = "  SELECT boc_idx, boc.cst_idx, cst_name, boc_type FROM {$g5['bom_customer_table']} boc 
                LEFT JOIN {$g5['customer_table']} cst ON boc.cst_idx = cst.cst_idx 
            WHERE boc_type = 'provider' AND boc.cst_idx != 0
            GROUP BY boc.cst_idx
            ORDER BY cst_name
";
$prv_res = sql_query($prv_sql,1);
$g5['provider_key_val'] = array();
$g5['provider_val_key'] = array();
$g5['provider_options'] = '';
for($i=0;$row=sql_fetch_array($prv_res);$i++){
    $g5['provider_key_val'][$row['cst_idx']] = $row['cst_name'];
    $g5['provider_val_key'][$row['cst_name']] = $row['cst_idx'];
    $g5['provider_options'] .= '<option value="'.$row['cst_idx'].'">'.$row['cst_name'].'</option>';
}
unset($prv_sql);
unset($prv_res);
unset($i);

//완제품고객업체 배열
$cst_sql = " SELECT boc_idx, boc.cst_idx, cst_name, boc_type FROM {$g5['bom_customer_table']} boc 
                LEFT JOIN {$g5['customer_table']} cst ON boc.cst_idx = cst.cst_idx 
            WHERE boc_type = 'customer' AND boc.cst_idx != 0
            GROUP BY boc.cst_idx
            ORDER BY cst_name
";
$cst_res = sql_query($cst_sql,1);
$g5['customer_key_val'] = array();
$g5['customer_val_key'] = array();
$g5['customer_options'] = '';
for($i=0;$row=sql_fetch_array($cst_res);$i++){
    $g5['customer_key_val'][$row['cst_idx']] = $row['cst_name'];
    $g5['customer_val_key'][$row['cst_name']] = $row['cst_idx'];
    $g5['customer_options'] .= '<option value="'.$row['cst_idx'].'">'.$row['cst_name'].'</option>';
}
unset($cst_sql);
unset($cst_res);
unset($i);

$cst_all_sql = " SELECT cst_idx, cst_name, cst_type FROM {$g5['customer_table']}
                WHERE cst_status = 'ok'
                    AND cst_type IN ('customer','provider')
                GROUP BY cst_idx
                ORDER BY cst_name
";
$cst_all_res = sql_query($cst_all_sql,1);
$g5['allcst_key_val'] = array();
$g5['allcst_val_key'] = array();
$g5['allcst_options'] = '';
for($i=0;$row=sql_fetch_array($cst_all_res);$i++){
    $g5['allcst_key_val'][$row['cst_idx']] = $row['cst_name'];
    $g5['allcst_val_key'][$row['cst_name']] = $row['cst_idx'];
    $g5['allcst_options'] .= '<option value="'.$row['cst_idx'].'">'.$row['cst_name'].'</option>';
}
unset($cst_all_sql);
unset($cst_all_res);
unset($i);

/*
$driver_sql = " SELECT cmm.mb_id, mb.mb_name FROM {$g5['company_member_table']} cmm
                    LEFT JOIN {$g5['member_table']} mb ON cmm.mb_id = mb.mb_id
                WHERE cmm_type = 'driver'
                    AND com_idx = '{$_SESSION['ss_com_idx']}'
                    AND mb_leave_date = ''
                    AND mb_intercept_date = ''
                    AND mb_6 = ''
";
$driver_res = sql_query($driver_sql,1);
$driver_opions = '';
if($driver_res->num_rows){
for($d=0;$dr=sql_fetch_array($driver_res);$d++){
    $driver_options .= '<option value="'.$dr['mb_id'].'">'.$dr['mb_name'].'</option>';
}
}
*/
//mb_6 = '' 은 소속업체가 없다는 뜻이기 때문에 대창소속 driver라는 뜻이다.
$dvr_deachang_sql = " SELECT cmm.mb_id, mb.mb_name FROM {$g5['company_member_table']} cmm
                        LEFT JOIN {$g5['member_table']} mb ON cmm.mb_id = mb.mb_id
                    WHERE cmm_type = 'driver'
                        AND com_idx = '{$_SESSION['ss_com_idx']}'
                        AND mb_leave_date = ''
                        AND mb_intercept_date = ''
                        AND mb_6 = ''
";
$dvr_deachang_res = sql_query($dvr_deachang_sql,1);
$g5['dvr_deachang_key_val'] = array();
$g5['dvr_deachang_val_key'] = array();
$g5['dvr_deachang_options'] = '';
for($i=0;$row=sql_fetch_array($cst_all_res);$i++){
    $g5['dvr_deachang_key_val'][$row['mb_id']] = $row['mb_name'];
    $g5['dvr_deachang_val_key'][$row['mb_name']] = $row['mb_id'];
    $g5['dvr_deachang_options'] .= '<option value="'.$row['mb_id'].'">'.$row['mb_name'].'</option>';
}
unset($dvr_deachang_sql);
unset($dvr_deachang_res);
unset($i);
