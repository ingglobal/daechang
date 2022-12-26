<?php
$sub_menu = "940120";
include_once('./_common.php');

if(!$member['mb_manager_yn']) {
    alert('메뉴에 접근 권한이 없습니다.');
}

$demo = 0;  // 데모모드 = 1

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
print_r2($allData[0]);
print_r2(sizeof($allData));
exit;




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
$sleepsec = 200;  // 백만분의 몇초간 쉴지 설정
$maxscreen = 200; // 몇건씩 화면에 보여줄건지?

flush();
ob_flush();

// 시트를 돌면서 배열로 먼저 생성해 두고 나중에 사용
for ($x=0;$x<sizeof($allData);$x++) {
    // print_r3($x);
    // print_r3(sizeof($allData[$x]));
    // print_r3($allData[$x]);
    for($i=0;$i<=sizeof($allData[$x]);$i++) {
        // print_r3($allData[$x][$i]);
        // 초기화
        unset($arr);
        unset($list);
        // 한 라인씩 $list 숫자 배열로 변경!!
        if(is_array($allData[$x][$i])) {
            foreach($allData[$x][$i] as $k1=>$v1) {
                // print_r3($k1.'='.$v1);
                $list[] = trim($v1);
            }
        }
        // print_r3($list);
        $arr['machine_no'] = $list[0];
        $arr['machine_name'] = $list[1];
        $arr['mnt_date'] = $list[2];
        $arr['alarm_no'] = $list[13];
        $arr['alarm_name'] = $list[14];
        $arr['alarm_code'] = $list[15];
        // print_r3($arr);

        // 조건에 맞는 해당 라인만 추출
        if( preg_match("/[-0-9A-Z]/",$arr['machine_no'])
            && preg_match("/[가-힝]/",$arr['machine_name'])
            && preg_match("/[-0-9]/",$arr['mnt_date'])
            && preg_match("/[0-9A-Z]/",$arr['alarm_code']) )
        {
            // print_r3($arr);

            // 배열생성
            $alarm_arr[$arr['machine_no']][$arr['alarm_no']] = $arr['alarm_code'];

        }
        else {continue;}

    }
}
// print_r3($alarm_arr);


// print_r3($allData);
$idx = 0;

for($i=0;$i<=sizeof($allData[0]);$i++) {
    // print_r3($allData[0][$i]);
	if($demo) {
        if($i>4) {break;}
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
    $arr['machine_no'] = $list[0];
    $arr['machine_name'] = $list[1];
    $arr['mnt_date'] = $list[2];
    $arr['mnt_reason'] = $list[3];    // 사유
    $arr['mnt_content'] = $list[4];
    $arr['mnt_part'] = $list[5];    // 고장부위
    $arr['mnt_name'] = $list[6];
    // $arr['mnt_start_time'] = $list[7];
    $arr['mnt_start_time'] = '23:40';
    // $arr['mnt_end_time'] = $list[8];
    $arr['mnt_end_time'] = '00:40';
    $arr['mnt_minutes'] = $list[9];
    $arr['mnt_company'] = $list[10];
    // print_r3($arr);

    // 조건에 맞는 해당 라인만 추출
    if( preg_match("/[-0-9A-Z]/",$arr['machine_no'])
        && preg_match("/[가-힝]/",$arr['machine_name'])
        && preg_match("/[-0-9]/",$arr['mnt_date']) )
    {
        // print_r3($arr);

        // 제목, 내용
        $arr['mnt_content_arr'] = explode("=>",$arr['mnt_content']);
        // print_r3(sizeof($arr['mnt_content_arr']));
        // print_r3($arr['mnt_content_arr']);
        $arr['mnt_content_new'] = $arr['mnt_content_arr'][sizeof($arr['mnt_content_arr'])-1];
        for($j=0;$j<=sizeof($arr['mnt_content_arr'])-2;$j++) {
            // print_r3($arr['mnt_content_arr'][$j]);
            $arr['mnt_subject_arr'][] = $arr['mnt_content_arr'][$j];
        }
        $arr['mnt_content'] = $arr['mnt_content_new'];
        $arr['mnt_subject'] = implode(" => ",$arr['mnt_subject_arr']);

        // 데이터 입력&수정&삭제
        $db_idx = func_db_update($arr);

        $idx++;
    }
    else {continue;}


    // 메시지 보임
    if($arr['mnt_subject']) {
        echo "<script> document.all.cont.innerHTML += '".$idx
                .". ".$arr['mnt_subject']." [".$arr['mnt_part']."]: ".$arr['mnt_content']
                ." ----------->> 완료<br>'; </script>\n";
    }

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
for($i=0;$i<=sizeof($allData[1]);$i++) {
    // print_r3($allData[1][$i]);
}





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