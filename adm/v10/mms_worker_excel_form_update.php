<?php
$sub_menu = "940130";
include_once('./_common.php');

$demo = 0;  // 데모모드 = 1

foreach($_FILES as $k1=>$v1) {
    // If file exists. Only one files is processed for conciseness(간결).
    if($k1) {
        $file_name = $k1;
        $upload_file_name = $_FILES[$file_name]['name'];
        $upload_file=$_FILES[$file_name]['tmp_name'];
        break;
    }
}
// exit;

// $upload_file_name = $_FILES['file_excel']['name'];
require_once G5_LIB_PATH.'/PhpSpreadsheet19/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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

// $upload_file=$_FILES['file_excel']['tmp_name'];
// $reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($upload_file);	
$sheetCount = $spreadsheet->getSheetCount();


// 엑셀 파일 저장 (최근 10개만 남겨놓기)
$destfile = date("YmdHis").'.xlsx';
// $destfile = '2024-02-09.xlsx';
$dir = '/data/excels/worker';
if(is_file(G5_PATH.$dir.'/'.$destfile)) {
    @unlink(G5_PATH.$dir.'/'.$destfile);
}
upload_common_file($upload_file, $destfile, $dir);
// exit;




$g5['title'] = '엑셀 업로드';
// include_once('./_top_menu_applicant.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>
<div class="" style="padding:10px;">
	<span>
		작업 시작~~ <font color=crimson><b>[끝]</b></font> 이라는 단어가 나오기 전 중간에 중지하지 마세요.
	</span><br><br>
	<div id="cont"></div>
</div>
<?php
echo "<script>var cont = document.getElementById('cont');</script>";
include_once ('./_tail.php');
$bmw_types = array('주' => 'day', '주간' => 'day', '야' => 'night', '야간' => 'night', '부' => 'sub', '서브' => 'sub');
$countgap = 50; // 몇건씩 보낼지 설정
$sleepsec = 1000;  // 백만분의 몇초간 쉴지 설정, default=200
$maxscreen = 100; // 몇건씩 화면에 보여줄건지?
$reg_boms = array(); //등록된 배열
$err_boms = array();//저장이 안된 bom요소
flush();
ob_flush();
$sheet = $spreadsheet->getSheet(0);
$sheetData = $sheet->toArray(null, true, true, true);
$prd_date = $prd_date ? $prd_date : G5_TIME_YMD; // 발주일
$idx = 0;
for($i=1;$i<=sizeof($sheetData);$i++){
    $a['bom_part_no'] = trim($sheetData[$i]['S']);//품번
    $a['mms_serial_no'] = trim($sheetData[$i]['X']);//설비시리얼번호
    $a['mb_8'] = trim($sheetData[$i]['Z']);//작업자번호
    $a['bmw_type'] = $bmw_types[trim($sheetData[$i]['AA'])];//작업자유형

    // 품번이 없거나 형식에 맞지 않으면 건너뛴다.
    if(!$a['bom_part_no'] || !preg_match('/[A-Z0-9-]{5,20}/',$a['bom_part_no'])){
        array_push($err_boms,'품번 error-'.$i.' 번째라인');
        continue;
    }
    // 설비 시리얼번호가 없거나 형식에 맞지 않으면 건너뛴다.
    if(!$a['mms_serial_no'] || !preg_match('/[A-Z0-9-]{5,20}/',$a['mms_serial_no'])){
        array_push($err_boms,'설비시리얼번호 error-'.$i.' 번째라인');
        continue;
    }
    // 작업자 번호가 없거나 형식에 맞지 않으면 건너뛴다.
    if(!$a['mb_8'] || !preg_match('/[0-9]{1,4}/',$a['mb_8'])){
        array_push($err_boms,'작업자번호 error-'.$i.' 번째라인');
        continue;
    }
    
    $bmw_type = '';
    // 해당 bmw_type이 없으면 기본 day(주간,주)로 설정된다.
    if(!$a['bmw_type']) $bmw_type = 'day';
    else $bmw_type = $a['bmw_type'];
    
    //################# 해당 데이터의 존재 여부확인 ##################
    $bom_idx = 0;
    $mms_idx = 0;
    $mb_id = '';
    // 해당 bom_part_no의 존재여부를 확인
    $bom_res = sql_fetch(" SELECT bom_idx FROM {$g5['bom_table']} WHERE bom_part_no = '{$a['bom_part_no']}' 
                    AND bom_status = 'ok' ORDER BY bom_reg_dt DESC LIMIT 1
    ");
    if(!$bom_res['bom_idx']){
        array_push($err_boms,'품번검색 error-'.$a['bom_part_no']);
        continue;
    }
    else $bom_idx = $bom_res['bom_idx'];
    
    // 해당 mms_serial_no의 존재여부를 확인
    $mms_res = sql_fetch(" SELECT mms_idx FROM {$g5['mms_table']} WHERE mms_serial_no = '{$a['mms_serial_no']}'
                    AND mms_status = 'ok'
    ");
    if(!$mms_res['mms_idx']){
        array_push($err_boms,'설비검색 error-'.$a['mms_serial_no']);
        continue;
    }
    else $mms_idx = $mms_res['mms_idx'];
    
    // 해당 mb_8(작업자번호)의 존재여부를 확인 [mb_7(현장작업자여부)]
    $mb_res = sql_fetch(" SELECT mb_id FROM {$g5['member_table']} WHERE mb_8 = '{$a['mb_8']}'
                    AND mb_7 = 'ok' AND mb_leave_date = '' AND mb_intercept_date = ''
    ");
    if(!$mb_res['mb_id']){
        array_push($err_boms,'작업자검색 error-'.$a['mb_8']);
        continue;
    }
    else $mb_id = $mb_res['mb_id'];


    /*
    $a['bom_part_no'] = trim($sheetData[$i]['S']);//품번
    $a['mms_serial_no'] = trim($sheetData[$i]['X']);//설비시리얼번호
    $a['mb_8'] = trim($sheetData[$i]['Z']);//작업자번호
    $a['bmw_type'] = $bmw_types[trim($sheetData[$i]['AA'])];//자업자유형
    */
    

    $chk_sql = " SELECT bmw_idx
                        , MAX(bmw_sort) OVER (PARTITION BY bom_idx, mms_idx) AS bmw_sort 
                    FROM {$g5['bom_mms_worker_table']}
                    WHERE bom_idx = '{$bom_idx}'
                        AND mms_idx = '{$mms_idx}'
                        AND mb_id = '{$mb_id}'
                        AND bmw_type = '{$bmw_type}'
                        AND bmw_status NOT IN ('trash','delete')
                    ORDER BY bmw_reg_dt DESC LIMIT 1
    ";
    // echo $chk_sql."<br>";
    $chk_res = sql_fetch($chk_sql);
    // echo '------------------------>'.$chk_res['prd_idx']."<br>";continue;
    // 해당 bom_idx와 mms_idx조건의 작업자들의 정렬순서중에 가장 큰값을 가져온다.
    $bmw_sort_res = sql_fetch(" SELECT MAX(bmw_sort) AS bmw_sort FROM {$g5['bom_mms_worker_table']}
            WHERE bom_idx = '{$bom_idx}'
                AND mms_idx = '{$mms_idx}'
                AND bmw_status NOT IN ('trash','delete')
    ");
    $bmw_sort = ($bmw_sort_res['bmw_sort']) ? $bmw_sort_res['bmw_sort'] : 0;
    // 기존정보 있으면 건너뛰기
    if($chk_res['bmw_idx'])
        continue;
    // 기존정보 없으면 INSERT
    else {
        //혹시라도 같은 제품의 같은 설비에서 day또는 night가 존재하면 등록할 수 없다.
        //반은 같은 제품과 같은 설비조건에서 day 1명, night1명 존재해야 하고 나머지는 전부 sub이다.
        if($bmw_type == 'day' || $bmw_type == 'night'){
            $chk_sql = " SELECT COUNT(*) AS cnt
                            FROM {$g5['bom_mms_worker_table']}
                            WHERE bom_idx = '{$bom_idx}'
                                AND mms_idx = '{$mms_idx}'
                                AND bmw_type = '{$bmw_type}'
                                AND bmw_status NOT IN ('trash','delete')
            ";
            // echo $chk_sql."<br>";
            $chk_res = sql_fetch($chk_sql);
            if($chk_res['cnt']){
                array_push($err_boms,$a['bmw_type'].'(중복) error- 설비:'.$a['mms_serial_no'].' / 품번: '.$a['bom_part_no'].' / 작업자번호:'.$a['mb_8']);
                continue;
            }
        }

        $bmw_main_yn = 0;
        if($bmw_type == 'day' || $bmw_type == 'night'){
            $mchk_res = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$g5['bom_mms_worker_table']}
                            WHERE bom_idx = '{$bom_idx}'
                                AND mms_idx != '{$mms_idx}'
                                AND bmw_main_yn = 1
                                AND bmw_status = 'ok'
            ");
            if(!$mchk_res['cnt']){
                $bmw_main_yn = 1;
            }
        }


        $bmw_sort = $bmw_sort + 1;
        $sql = " INSERT INTO {$g5['bom_mms_worker_table']} SET
                    bom_idx = '{$bom_idx}'
                    , mms_idx = '{$mms_idx}'
                    , mb_id = '{$mb_id}'
                    , bmw_type = '{$bmw_type}'
                    , bmw_sort = '{$bmw_sort}'
                    , bmw_main_yn = '{$bmw_main_yn}'
                    , bmw_status = 'ok'
                    , bmw_reg_dt = '".G5_TIME_YMDHIS."'
                    , bmw_update_dt = '".G5_TIME_YMDHIS."'
        ";
        sql_query($sql, 1);
        $a['bmw_idx'] = sql_insert_id();

        if($bmw_type == 'day' || $bmw_type == 'night'){
            $m_sql = " UPDATE {$g5['bom_mms_worker_table']} SET bmw_main_yn = '{$bmw_main_yn}'
                            WHERE bom_idx = '{$bom_idx}'
                                AND mms_idx = '{$mms_idx}'
                                AND bmw_type IN ('day','night')
            ";
            sql_query($m_sql,1);
        }
    }

    // 생산날짜범위에 속하는 production_item요소에 

    $idx++;

    echo "<script> cont.innerHTML += '".$idx." - (bom:".$a['bom_part_no'].") / (mms:".$a['mms_serial_no'].") / (mb: ".$mb_id.") /  (mb_no: ".$a['mb_8'].") ---->> 완료<br>';</script>\n";

    
    flush();
    ob_flush();
    ob_end_flush();
    usleep($sleepsec);
    // 보기 쉽게 묶음 단위로 구분 (단락으로 구분해서 보임)
    if($idx % $countgap == 0)
        echo "<script> cont.innerHTML += '<br>'; </script>\n";

    // 화면 정리 부하를 줄임(화면을 싹 지움)
    if($idx % $maxscreen == 0)
        echo "<script> cont.innerHTML = ''; </script>\n";
}

?>
<script>
    var err_boms = <?=json_encode($err_boms)?>;
	cont.innerHTML += "<br><br>총 <?php echo number_format($idx) ?>건 완료<br><br><font color=crimson><b>[끝]</b></font><br><br>";

	cont.innerHTML += "<br>등록되지 않은 제품<br>----------------------------<br><br>";
    if(err_boms.length > 0){
        // reg_boms.forEach(function(e){
        //     document.all.cont.innerHTML += e+"<br>";
        // });
        for(var idx in err_boms){
            cont.innerHTML += err_boms[idx]+"<br>"; 
        }
    } else {
        cont.innerHTML += "등록되지 않은 제품이 없습니다.<br>"; 
    }
</script>