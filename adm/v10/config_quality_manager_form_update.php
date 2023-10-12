<?php
$sub_menu = "910143";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

//-- 필드명 추출 mb_ 와 같은 앞자리 3자 추출 --//
$r = sql_query(" desc {$g5['setting_table']} ");
while ( $d = sql_fetch_array($r) ) {$db_fields[] = $d['Field'];}
$db_prefix = 'mng';//substr($db_fields[0],0,3);

//-- 메타 입력 (디비에 있는 설정된 값은 입력하지 않는다.) --//
$db_fields[] = "set_bg_pattern";	// 건너뛸 변수명은 배열로 추가해 준다.
$db_fields[] = "var_name";

// echo $db_prefix."<br>";
// print_r2($_REQUEST);exit;

foreach($_REQUEST as $key => $value ) {
	//-- 해당 테이블에 있는 필드 제외하고 테이블 prefix 로 시작하는 변수들만 업데이트, array 타입 변수들도 저장 안 함 --//
	if(!in_array($key,$db_fields) && substr($key,0,3)==$db_prefix && gettype($value) != 'array') {
		// echo $key."=".$_REQUEST[$key]."<br>";
		setting_update(array(
			"set_key"=>"manager",	// key 값을 별도로 주면 환경설정값 그룹으로 분리됩니다.
			"com_idx"=>$_SESSION['ss_com_idx'],
			"set_name"=>$key,
			"set_value"=>$value,
			"set_auto_yn"=>1
		));
	}
}




// exit;
goto_url('./config_quality_manager_form.php?'.$qstr, false);
?>