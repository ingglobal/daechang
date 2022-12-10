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


$mms_code_file = G5_DATA_PATH.'/cache/mms-code.php';
if( file_exists($mms_code_file) ) {
    include($mms_code_file);
}
$mms_setting_file = G5_DATA_PATH.'/cache/mms-setting.php';
if( file_exists($mms_setting_file) ) {
    include($mms_setting_file);
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




