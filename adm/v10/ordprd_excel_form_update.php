<?php
$sub_menu = "918110";
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
$extension = pathinfo($upload_file_name, PATHINFO_EXTENSION);
$destfile = date("YmdHis").'.'.$extension;
// $destfile = '2024-02-09.xlsx';
$dir = '/data/excels/order';
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
    $a['prd_idx'] = 0;
    $a['bom_idx'] = 0;
    $a['bom_price'] = 0;
    $a['boc_idx'] = 0;
    $a['cst_idx'] = trim($sheetData[$i]['A']);//고객사ID
    $a['bct_idx'] = trim($sheetData[$i]['C']);//차종ID
    $a['bom_part_no'] = trim($sheetData[$i]['E']);//품번
    $a['prd_start_date'] = trim($sheetData[$i]['G']);//생산시작일
    $a['prd_done_date'] = trim($sheetData[$i]['H']);//생산완료일
    $a['prd_value'] = trim($sheetData[$i]['I']);//수주수량
    // 우선 수량이 표시되지 않는 행은 건너뛴다.
    if(!$a['prd_value'] || !is_numeric($a['prd_value'])) continue;
    // bom_part_no를 검수한다.
    $bres = sql_fetch(" SELECT bom_idx, bom_price, bom_type FROM {$g5['bom_table']} WHERE bom_part_no = '{$a['bom_part_no']}' AND bom_status = 'ok' ORDER BY bom_idx DESC LIMIT 1 ");
    if(!$bres['bom_idx']){
        array_push($err_boms,'품번 error-'.$a['bom_part_no']);
        continue;
    }
    if(!$bres['bom_type'] == 'product'){
        array_push($err_boms,'Not 완제품-'.$a['bom_part_no']);
        continue;
    }
    $a['bom_idx'] = $bres['bom_idx'];
    $a['bom_price'] = $bres['bom_price'];
    //고객사ID정보를 검수한다.
    if(!array_key_exists($a['cst_idx'], $g5['allcst_key_val'])){
        array_push($err_boms,'고객사ID error-'.$a['bom_part_no']);
        continue;
    }
    //생산시작일검수
    if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$a['prd_start_date'])){
        array_push($err_boms,'생산시작일 error-'.$a['bom_part_no']);
        continue;
    }
    //생산완료일검수
    if(!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$a['prd_done_date'])){
        array_push($err_boms,'생산완료일 error-'.$a['bom_part_no']);
        continue;
    }

    //등록배열 요소중에 동일한 고객처와 품번의 요소가 존재하면 
    if(in_array($a['cst_idx'].'^'.$a['bom_part_no'], $reg_boms)){
        array_push($err_boms,'업체&품번=>중복 error-'.$a['cst_idx'].' & '.$a['bom_part_no']);
        continue;
    }

    //boc_idx데이터를 호출하고 없으면 새롭게 생성한다.
    $boc_idx = cst2boc($a['cst_idx'],$a['bom_idx'],'customer');
    if(!$boc_idx){
        $a['boc_idx'] = create_boc($a['cst_idx'],$a['bom_idx'],'customer');
    } else {
        $a['boc_idx'] = $boc_idx;
    }
    
    //중복요소가 없으면 등록배열에 등록
    array_push($reg_boms,$a['cst_idx'].'^'.$a['bom_part_no']);
    
    /*
    $a['bom_idx'] = 0;
    $a['boc_idx'] = 0;
    $a['cst_idx'] = trim($sheetData[$i]['A']);//고객사ID
    $a['bct_idx'] = trim($sheetData[$i]['C']);//차종ID
    $a['bom_part_no'] = trim($sheetData[$i]['E']);//품번
    $a['prd_start_date'] = trim($sheetData[$i]['G']);//생산시작일
    $a['prd_done_date'] = trim($sheetData[$i]['H']);//생산완료일
    $a['prd_value'] = trim($sheetData[$i]['I']);//수주수량
    */
    $chk_sql = " SELECT prd_idx FROM {$g5['production_table']}
                    WHERE com_idx = '{$g5['setting']['set_com_idx']}'
                        AND boc_idx = '{$a['boc_idx']}'
                        AND bom_idx = '{$a['bom_idx']}'
                        AND prd_date = '{$prd_date}'
                        AND prd_status NOT IN ('trash','delete')
                    ORDER BY prd_reg_dt DESC LIMIT 1
    ";
    // echo $chk_sql."<br>";
    $chk_res = sql_fetch($chk_sql);
    // echo '------------------------>'.$chk_res['prd_idx']."<br>";continue;
    // 기존정보 있으면 UPDATE
    if($chk_res['prd_idx']){
        $a['prd_idx'] = $chk_res['prd_idx'];
        $sql = " UPDATE {$g5['production_table']}
                    SET prd_start_date = '{$a['prd_start_date']}'
                        , prd_done_date = '{$a['prd_done_date']}'
                        , prd_value = '{$a['prd_value']}'
                        , prd_update_dt = '".G5_TIME_YMDHIS."'
                    WHERE prd_idx = '{$chk_res['prd_idx']}'
        ";
        sql_query($sql,1);
    }
    // 기존정보 없으면 INSERT
    else {
        $tdcode = preg_replace('/[ :-]*/','',$prd_date);
        $prd_order_no = "PRD-".$tdcode.wdg_get_random_string('09',10);
        $sql = " INSERT INTO {$g5['production_table']} SET
                    com_idx = '{$g5['setting']['set_com_idx']}'
                    , boc_idx = '{$a['boc_idx']}'
                    , bom_idx = '{$a['bom_idx']}'
                    , prd_type = 'normal'
                    , prd_value = '{$a['prd_value']}'
                    , prd_price = '{$a['bom_price']}'
                    , prd_date = '{$prd_date}'
                    , prd_order_no = '{$prd_order_no}'
                    , prd_start_date = '{$a['prd_start_date']}'
                    , prd_done_date = '{$a['prd_done_date']}'
                    , prd_status = 'confirm'
                    , prd_reg_dt = '".G5_TIME_YMDHIS."'
                    , prd_update_dt = '".G5_TIME_YMDHIS."'
        ";
        sql_query($sql, 1);
        $a['prd_idx'] = sql_insert_id();
    }

    // 생산날짜범위에 속하는 production_item요소에 

    $idx++;

    echo "<script> cont.innerHTML += '".$idx." - (cst:".$a['cst_idx'].") / (boc:".$a['boc_idx'].") [".$a['bom_part_no']."] [".$a['prd_value']."] ---->> 완료<br>';</script>\n";

    
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