<?php
// header('Content-Encoding: none;');

$sub_menu = "940120";
include_once('./_common.php');

if(!$member['mb_manager_yn']) {
    alert('메뉴에 접근 권한이 없습니다.');
}

$demo = 0;  // 데모모드 = 1

// print_r2($_REQUEST);
// exit;

// IP 정보 추출 
$plc_ip = $_REQUEST['ip'] ?: '192.168.100.143';
// 포트 정보 추출 
$ppr_port_no = $_REQUEST['port'] ?: 20480;
// 부모 설비정보 추출
$mms_parent = get_table('mms','mms_idx',$_REQUEST['mms_idx']);


require_once G5_LIB_PATH.'/PhpSpreadsheet19/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$upload_file_name = $_FILES['file_excel']['name'];
$file_type= pathinfo($upload_file_name, PATHINFO_EXTENSION);
if ($file_type =='xls') {
	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();	
}
elseif ($file_type =='xlsx') {
	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
}
else {
	echo '처리할 수 있는 엑셀 파일이 아닙니다';
	exit;
}

$upload_file=$_FILES['file_excel']['tmp_name'];
// $reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($upload_file);	
$sheetCount = $spreadsheet->getSheetCount();
for ($i = 0; $i < $sheetCount; $i++) {
    $sheet = $spreadsheet->getSheet($i);
    $sheetData = $sheet->toArray(null, true, true, true);
    // echo $i.' ------------- <br>';
    // print_r2($sheetData);
    $allData[$i] = $sheetData;
}
// print_r3($allData[0]);
// print_r3(sizeof($allData));
// exit;



$g5['title'] = '엑셀 업로드';
//include_once('./_top_menu_applicant.php');
include_once('./_head.php');
//echo $g5['container_sub_title'];
?>
<div class="" style="padding:10px;">
	<span>
		작업 시작~~ <font color=crimson><b>[끝]</b></font> 이라는 단어가 나오기 전 중간에 중지하지 마세요.
	</span><br><br>
	<span id="cont"></span>
</div>
<?php
include_once ('./_tail.php');
?>

<?php
$countgap = 10; // 몇건씩 보낼지 설정
$sleepsec = 1000;  // 백만분의 몇초간 쉴지 설정, default=200
$maxscreen = 20; // 몇건씩 화면에 보여줄건지?

// tag=측정태그, alarm=알람, count=생산카운터, addup=적산, trigger=트리거, auto=자동선택, autostart=자동시작
<<<<<<< HEAD
$plc_type_array = array('0'=>'alarm','1'=>'tag','2'=>'count','3'=>'trigger','4'=>'auto','5'=>'autostart','6'=>'countercheck','7'=>'runtime');
=======
$plc_type_array = array('0'=>'alarm','1'=>'tag','2'=>'addup','3'=>'trigger','4'=>'auto','5'=>'autostart','6'=>'countercheck','7'=>'runtime');
>>>>>>> 6648baf8549918dacbf80998f646bf2e62ddea83
$pre = 'ppr';

flush();
ob_flush();

$idx = 0;
// ==============================================================================
// 첫번째 시트
for($i=0;$i<=sizeof($allData[0]);$i++) {
    // print_r3($allData[0][$i]);
    if($demo) {
        if($i>51) {break;} // 51
    }

    // 초기화
    unset($arr);
    unset($list);
    // 한 라인씩 $list 숫자 배열로 변경!!
    if(is_array($allData[0][$i])) {
        foreach($allData[0][$i] as $k1=>$v1) {
            // print_r3($k1.'='.$v1);
            $list[] = trim($v1);
        }
    }
    // print_r3($list);
    $arr['title'] = trim($list[0]);  // 설비명
    $arr['word_no'] = trim($list[1]);
    $arr['bit_no'] = trim($list[2]);
    $arr['byte_no'] = trim($list[3]);
    $arr['unit_no'] = trim($list[4]);  // 유닛번호
    $arr['fct_name'] = trim($list[5]);  // 설비구분 (대창인 경우 설비고유번호)
    $arr['tag_name'] = addslashes(preg_replace("/\n/"," ",$list[6]));
    $arr['tag_names'] = explode("\n",$list[6]);
    $arr['tag_name1'] = trim($arr['tag_names'][0]); // 줄바꿈이 있는 경우 1번째 줄
    $arr['tag_name2'] = trim($arr['tag_names'][1]); // 줄바꿈 2번째줄
    $arr['db_type'] = trim($list[7]);  // 디비유형
    $arr['set_time'] = trim($list[8]);  // 시간
    $arr['tag_type'] = trim($list[9]);
    $arr['word_address'] = trim($list[10]);
    $arr['decimal'] = trim($list[11]);   // 소수점
    $arr['desc'] = trim($list[12]);   // data 설명
    $arr['word_no2'] = intval($list[13]);
    $arr['bit_no2'] = intval($list[14]);
    $arr['byte_no2'] = intval($list[15]);
    unset($arr['tag_names']);
    // if(is_numeric($arr['unit_no'])) {
    //     print_r3($arr);
    // }

    $arr['word_no2'] = $arr['word_no2'] ?: $arr['word_no']; // 엑셀 뒷부분 없을 수도 있음
    $arr['word_no2'] = $arr['word_no2'] ?: $word_no_old; // 그래도 없으면 이전값
    $arr['byte_no2'] = $arr['word_no2'] ?: $arr['byte_no'];
    
    // if(!$arr['unit_no']) {continue;} // 유닛번호 없으면 통과
    // tag name 없거나 디비유형 없으면 통과
    if(!$arr['tag_name'] || !is_numeric($arr['db_type'])) {continue;}

    // 설비정보 추출 - 없으면 이전 $mms 계속 유지됨
    if($arr['fct_name']) {
        $sql = " SELECT * FROM {$g5['mms_table']} WHERE mms_serial_no = '".$arr['fct_name']."' ";
        // print_r3($sql);
        $mms = sql_fetch($sql,1);
    }

    $cod['cod_idx'] = 0;    // 초기화
    // 디비유형 => alarm=알람, trigger=트리거, auto=자동선택, autostart=자동시작
    if( is_numeric($arr['bit_no']) && in_array($arr['db_type'], array('0','3','4','5')) )
    {
        // 알람코드 추출 & 생성
        if($arr['db_type']=='0') {
            preg_match('/\[([A-Za-z0-9]+)\]/', $arr['tag_name1'], $matches);
            $ar1['cod_code'] = $matches[1];
            $ar1['cod_code'] = $arr['tag_name1'];
            if(!$ar1['cod_code']) {continue;}
            // print_r3($ar1['cod_code']);
            $ar1['com_idx'] = $mms['com_idx']; // 대창공업
            $ar1['mms_idx'] = $mms['mms_idx']; // 설비 mms_idx
            $ar1['cod_name'] = $arr['tag_name1'];
            $ar1['trm_idx_category'] = '42';
            $ar1['cod_plc_ip'] = $_REQUEST['ip'];
            $ar1['cod_plc_port'] = $_REQUEST['port'];
            $ar1['cod_plc_no'] = intval($arr['word_no2'])-1;
            $ar1['cod_plc_bit'] = intval($arr['bit_no']);
            $ar1['cod_group'] = 'err';
            $ar1['cod_type'] = 'a';
            $ar1['cod_interval'] = '3600';
            $ar1['cod_count'] = '10';
            $ar1['cod_min_sec'] = '0';
            $ar1['cod_count_limit'] = '10';
            $ar1['cod_send_type'] = 'email';
            $ar1['cod_update_ny'] = '1';
            $ar1['cod_status'] = 'ok';
            $ar1['cod_update_dt'] = G5_TIME_YMDHIS;
            $pre1 = 'cod';
    
            // 공통쿼리
            $skips1 = array($pre1.'_idx',$pre1.'_reg_dt');
            foreach($ar1 as $k1=>$v1) {
                // print_r3($k1.'/'.$v1);
                if(in_array($k1,$skips1)) {continue;}
                $sql_alarms[$i][] .= " ".$k1." = '".$v1."' ";
            }
            $sql_alarm[$i] = (is_array($sql_alarms[$i])) ? implode(",",$sql_alarms[$i]) : '';
            // print_r3($sql_alarms[$i]);
            // 중복 체크
            $sql = "SELECT * FROM {$g5['code_table']} 
                    WHERE mms_idx = '".$ar1['mms_idx']."' AND cod_code = '".$ar1['cod_code']."'
            ";
            // print_r3($sql);
            $cod = sql_fetch($sql,1);
            if($cod[$pre1."_idx"]) {
                $sql = "UPDATE {$g5['code_table']} SET 
                            {$sql_alarm[$i]} 
                        WHERE ".$pre1."_idx = '".$cod[$pre1."_idx"]."'
                ";
                sql_query($sql,1);
            }
            else {
                $sql = "INSERT INTO {$g5['code_table']} SET 
                            {$sql_alarm[$i]} 
                            , ".$pre1."_reg_dt = '".G5_TIME_YMDHIS."'
                ";
                sql_query($sql,1);
                $cod[$pre1."_idx"] = sql_insert_id();
            }
            // print_r3($sql);
        }
        
<<<<<<< HEAD
    // plc_protocol 입력 정보
=======
        // plc_protocol 입력 정보
>>>>>>> 6648baf8549918dacbf80998f646bf2e62ddea83
        $ar['ppr_decimal'] = 0;
        $ar['ppr_set_time'] = 0;
    }
    // 디비유형 => tag(1)=측정태그, count=생산카운터, addup(2)=적산
    else if( is_numeric($arr['word_no']) && in_array($arr['db_type'], array('1','2')) )
    {
        $ar['ppr_decimal'] = $arr['decimal'];
        $ar['ppr_set_time'] = $arr['set_time'];
<<<<<<< HEAD
        // get the jig code
        $pattern = '/([LR]\d)/';
        preg_match($pattern, $arr['tag_name1'], $matches1);
        // print_r3($matches1[1]);
        $ar['ppr_jig_code'] = $matches1[1];
        
=======
>>>>>>> 6648baf8549918dacbf80998f646bf2e62ddea83
    }
    // 디비유형 => countercheck(6)=카운터체크
    else if( is_numeric($arr['bit_no']) && in_array($arr['db_type'], array('6')) )
    {
        $ar['ppr_idx_parent'] = $ppr_idx[$arr['unit_no']];
        $ar['ppr_decimal'] = $arr['decimal'];
        $ar['ppr_set_time'] = $arr['set_time'];
<<<<<<< HEAD
        // print_r3($ar);
=======
>>>>>>> 6648baf8549918dacbf80998f646bf2e62ddea83
    }
    // 디비유형 => runtime(7)=가동시간
    else if( is_numeric($arr['word_no']) && in_array($arr['db_type'], array('7')) )
    {
        $ar['ppr_decimal'] = 1;
        $ar['ppr_set_time'] = 1;
    }
    
    

    // 통신프로토콜 입력 ==================================================
    $ar['com_idx'] = $mms['com_idx']; // 대창공업
    $ar['mms_idx'] = $mms['mms_idx']; // 설비 mms_idx
    $ar['cod_idx'] = $cod['cod_idx'];    // 알람코드idx
    $ar['ppr_data_type'] = $plc_type_array[$arr['db_type']]; // tag=측정태그, alarm=알람, count=생산카운터, addup=적산, trigger=트리거, auto=자동선택, autostart=자동시작, countercheck=카운터체크, runtime=가동시간
    $ar['ppr_name'] = $arr['tag_name1'];
    $ar['ppr_ip'] = $plc_ip;
    $ar['ppr_port_no'] = $ppr_port_no;
    $ar['ppr_no'] = intval($arr['word_no2'])-1;
    $ar['ppr_bit'] = intval($arr['bit_no']);
    $ar['ppr_update_dt'] = G5_TIME_YMDHIS;
    // print_r3($ar);
    
    // 공통쿼리
    $skips = array($pre.'_idx',$pre.'_reg_dt');
    foreach($ar as $k1=>$v1) {
        // print_r3($k1.'/'.$v1);
        if(in_array($k1,$skips)) {continue;}
        $sql_commons[$i][] .= " ".$k1." = '".$v1."' ";
    }
    $sql_common[$i] = (is_array($sql_commons[$i])) ? implode(",",$sql_commons[$i]) : '';
    // print_r3($sql_common[$i]);
    // 중복 체크
    $sql = "SELECT * FROM {$g5['plc_protocol_table']} 
            WHERE mms_idx = '".$ar['mms_idx']."'
            AND ppr_port_no = '".$ar['ppr_port_no']."' AND ppr_no = '".$ar['ppr_no']."' AND ppr_bit = '".$ar['ppr_bit']."'
            AND ppr_set_time = '".$ar['ppr_set_time']."'
    ";
    // print_r3($sql);
    // if(is_numeric($arr['unit_no'])) {
    //     print_r3($sql);
    // }
    $row = sql_fetch($sql,1);
    if($row[$pre."_idx"]) {
        $sql = "UPDATE {$g5['plc_protocol_table']} SET 
                    {$sql_common[$i]} 
                WHERE ".$pre."_idx = '".$row[$pre."_idx"]."'
        ";
        sql_query($sql,1);
    }
    else {
        $sql = "INSERT INTO {$g5['plc_protocol_table']} SET 
                    {$sql_common[$i]} 
                    , ".$pre."_reg_dt = '".G5_TIME_YMDHIS."'
        ";
        sql_query($sql,1);
        $row[$pre."_idx"] = sql_insert_id();
    }
    // print_r3($sql);
    // if(is_numeric($arr['unit_no'])) {
    //     print_r3($sql);
    // }
    
    $idx++; 
    echo "<script> document.all.cont.innerHTML += '".$idx
            .". ".$arr['tag_name']." (WordNo:".$arr['word_no2'].", ".$arr['set_time'].", val x ".$arr['decimal'].")"
            ." ----------->> 완료<br>'; </script>\n";


    // 카운터체크를 위해 엑셀의 word 배열 번호를 저장해 놓고 있다가 해당 카운터체크 위치에서 관련 변수 재활용합니다.
    $ppr_idx[$arr['word_no2']] = $row[$pre."_idx"];

    // 이전값 저장
    $word_no_old = $arr['word_no2'];
    // 변수 초기화
    unset($ar);unset($ar1);

    flush();
    ob_flush();
    ob_end_flush();
    usleep($sleepsec);
    
    // 보기 쉽게 묶음 단위로 구분 (단락으로 구분해서 보임)
    if ($i % $countgap == 0)
        echo "<script> document.all.cont.innerHTML += '<br>'; </script>\n";
    
    // 화면 정리! 부하를 줄임 (화면 싹 지움)
    if ($i % $maxscreen == 0)
        echo "<script> document.all.cont.innerHTML = ''; </script>\n";

}
// ==============================================================================
// 두번째 시트
// for($i=0;$i<=sizeof($allData[1]);$i++) {
//     print_r3($allData[1][$i]);
// }





// 관리자 디버깅 메시지
if( is_array($g5['debug_msg']) ) {
    for($i=0;$i<sizeof($g5['debug_msg']);$i++) {
        echo '<div class="debug_msg">'.$g5['debug_msg'][$i].'</div>';
    }
?>
    <script>
    $(function(){
        $("#container").prepend( $('.debug_msg') );
    });
    </script>
<?php
}
?>


<script>
	document.all.cont.innerHTML += "<br><br>총 <?php echo number_format($idx) ?>건 완료<br><br><font color=crimson><b>[끝]</b></font>";
</script>