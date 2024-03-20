<?php
$sub_menu = "922110";
include_once('./_common.php');

$demo = 0;  // 데모모드 = 1

foreach ($_FILES as $k1 => $v1) {
    // If file exists. Only one files is processed for conciseness(간결).
    if ($k1) {
        $file_name = $k1;
        $upload_file_name = $_FILES[$file_name]['name'];
        $upload_file = $_FILES[$file_name]['tmp_name'];
        break;
    }
}
// exit;

// $upload_file_name = $_FILES['file_excel']['name'];
require_once G5_LIB_PATH . '/PhpSpreadsheet19/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$file_type = pathinfo($upload_file_name, PATHINFO_EXTENSION);
if ($file_type == 'xls') {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
} elseif ($file_type == 'xlsx') {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
} else {
    echo '처리할 수 있는 엑셀 파일이 아닙니다';
    exit;
}


// $upload_file=$_FILES['file_excel']['tmp_name'];
// $reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($upload_file);
$sheetCount = $spreadsheet->getSheetCount();


// 엑셀 파일 저장 (최근 10개만 남겨놓기)
$extension = pathinfo($upload_file_name, PATHINFO_EXTENSION);
$destfile = date("YmdHis") . '.' . $extension;
// $destfile = '2024-02-09.xlsx';
$dir = '/data/excels/production';
if (is_file(G5_PATH . $dir . '/' . $destfile)) {
    @unlink(G5_PATH . $dir . '/' . $destfile);
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
include_once('./_tail.php');

$countgap = 50; // 몇건씩 보낼지 설정
$sleepsec = 1000;  // 백만분의 몇초간 쉴지 설정, default=200
$maxscreen = 100; // 몇건씩 화면에 보여줄건지?
$reg_boms = array(); //등록된 배열
$err_boms = array(); //저장이 안된 bom요소
flush();
ob_flush();
$sheet = $spreadsheet->getSheet(0);
$sheetData = $sheet->toArray(null, true, true, true);
$prd_date = $prd_date ? $prd_date : G5_TIME_YMD; // 발주일
$idx = 0;
$d = array();
$pattern_date = '/^\d{4}-\d{2}-\d{2}$/';
$pattern_code = '/^[0-9A-Z]+(?:-[0-9A-Z]+)*$/';
$pattern_num = '/^[1-9][0-9]*$/';
for ($i = 1; $i <= sizeof($sheetData); $i++) {
    // 행중에 날짜데이터도 없고, 품번에 해당하는 데이터도 없으면 거너뛴다.
    // if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',trim($sheetData[$i]['E']) && !preg_match('/^[0-9A-Z]+(?:-[0-9A-Z]+)*$/'),trim($sheetData[$i]['C']) && !preg_match('/^[1-9][0-9]*$/',trim($sheetData[$i]['A']))) continue;
    if (
        preg_match($pattern_num, trim($sheetData[$i]['A']))
        || preg_match($pattern_code, trim($sheetData[$i]['C']))
        || preg_match($pattern_date, trim($sheetData[$i]['E']))
    ) {
        // 행에서 날짜 데이터가 있으면 날짜데이터만 배열에 담고 다음줄로 넘어간다.
        if (preg_match($pattern_date, trim($sheetData[$i]['E']))) {
            array_push($d, trim($sheetData[$i]['E'])); //생산시작일
            array_push($d, trim($sheetData[$i]['F'])); //생산시작일
            array_push($d, trim($sheetData[$i]['G'])); //생산시작일
            array_push($d, trim($sheetData[$i]['H'])); //생산시작일
            array_push($d, trim($sheetData[$i]['I'])); //생산시작일
            array_push($d, trim($sheetData[$i]['J'])); //생산시작일
            array_push($d, trim($sheetData[$i]['K'])); //생산시작일
            array_push($d, trim($sheetData[$i]['L'])); //생산시작일
            array_push($d, trim($sheetData[$i]['M'])); //생산시작일
            array_push($d, trim($sheetData[$i]['N'])); //생산시작일
            array_push($d, trim($sheetData[$i]['O'])); //생산시작일
            array_push($d, trim($sheetData[$i]['P'])); //생산시작일
            array_push($d, trim($sheetData[$i]['Q'])); //생산시작일
            continue;
        }

        $c = array();
        $a['cst_idx'] = trim($sheetData[$i]['A']); //고객사ID
        $a['bom_part_no'] = trim($sheetData[$i]['C']); //품번

        // bom데이터의 존재유무를 확인하고 없으면 건너뛴다.
        $bom = sql_fetch(" SELECT bom_idx, bom_type FROM {$g5['bom_table']} 
                        WHERE com_idx = '{$g5['setting']['set_com_idx']}'
                            AND bom_part_no = '{$a['bom_part_no']}'
                            AND bom_status = 'ok'
                            LIMIT 1
        ");
        $bom_idx = $bom['bom_idx'];
        if (!$bom_idx) {
            array_push($err_boms, '품번 error-' . $a['bom_part_no']);
            continue;
        }

        // bom데이터의 타입이 'product'가 아니면 건너뛴다.
        if ($bom['bom_type'] != 'product') {
            array_push($err_boms, 'Not 완제품-' . $a['bom_part_no']);
            continue;
        }

        // boc_idx여부를 확인하고 없으면 등록한다.
        $boc_idx = cst2boc($a['cst_idx'], $bom_idx, 'customer');
        if (!$boc_idx) {
            $boc_idx = create_boc($a['cst_idx'], $bom_idx, 'customer');
        }

        array_push($c, (int)trim($sheetData[$i]['E'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['F'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['G'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['H'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['I'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['J'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['K'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['L'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['M'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['N'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['O'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['P'])); //일별지시수량
        array_push($c, (int)trim($sheetData[$i]['Q'])); //일별지시수량


        foreach ($c as $k => $v) {
            //pri_date로 production테이블의 생산계획의 날짜범위 조건에 부합하는 prd_idx를 찾기
            $prd_res = sql_fetch(" SELECT prd_idx FROM {$g5['production_table']}
                    WHERE com_idx = '{$g5['setting']['set_com_idx']}'
                        AND prd_done_date >= '{$d[$k]}'
                        AND boc_idx = '{$boc_idx}'
                        AND bom_idx = '{$bom_idx}'
                    ORDER BY prd_done_date DESC, prd_reg_dt DESC LIMIT 1
            ");
            if (!$prd_res['prd_idx']) continue; //prd_idx가 없으면 건너뛴다.
            if (!$v) continue; //지시량이 없으면 건너뛴다.

            $prd_idx = $prd_res['prd_idx'];

            $prm_res = sql_fetch(" SELECT prm_idx, prd_idx FROM {$g5['production_main_table']}
                WHERE bom_idx = '{$bom_idx}'
                    AND boc_idx = '{$boc_idx}'
                    AND prm_date = '{$d[$k]}'
                    AND prm_status NOT IN ('trash','delete')
                LIMIT 1
            ");

            $prm_idx = $prm_res['prm_idx'];
            $prd_idx = ($prm_res['prd_idx']) ? $prm_res['prd_idx'] : $prd_idx;

            if (!$prm_idx) {
                $tdcode = preg_replace('/[ :-]*/', '', $d[$k]);
                $prm_order_no = "PRD-" . $tdcode . wdg_get_random_string('09', 10);
                $sql = " INSERT INTO {$g5['production_main_table']} SET
                           com_idx = '{$g5['setting']['set_com_idx']}'
                           , prd_idx = '{$prd_idx}'
                           , bom_idx = '{$bom_idx}'
                           , boc_idx = '{$boc_idx}'
                           , prm_order_no = '{$prm_order_no}'
                           , prm_date = '{$d[$k]}'
                           , prm_value = {$v}
                           , prm_status = 'confirm'
                           , prm_reg_dt = '" . G5_TIME_YMDHIS . "'
                           , prm_update_dt = '" . G5_TIME_YMDHIS . "'
                ";
                sql_query($sql, 1);
                $prm_idx = sql_insert_id();
            }

            // 생산계획 완제품그룹 등록
            // print_r3($d[$k]);
            insert_production_item($prd_idx, $prm_idx, $boc_idx, $bom_idx, $d[$k], $v, 'confirm');

            $idx++;
            echo "<script> cont.innerHTML += '" . $idx . " - (cst:" . $a['cst_idx'] . ") / [" . $a['bom_part_no'] . "] / [" . $d[$k] . "] : [" . $v . "] ---->> 완료<br>';</script>\n";
            // echo "<script> cont.innerHTML += '".$sql." <br>';</script>\n";
            flush();
            ob_flush();
            ob_end_flush();
            usleep($sleepsec);
            // 보기 쉽게 묶음 단위로 구분 (단락으로 구분해서 보임)
            if ($idx % $countgap == 0)
                echo "<script> cont.innerHTML += '<br>'; </script>\n";

            // 화면 정리 부하를 줄임(화면을 싹 지움)
            if ($idx % $maxscreen == 0)
                echo "<script> cont.innerHTML = ''; </script>\n";
        } //--foreach($c as $k => $v)
    } //--if(preg_match($pattern_num,trim($sheetData[$i]['A'])) ||
} //--for($i=1;$i<=sizeof($sheetData);$i++)

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
    var err_boms = <?= json_encode($err_boms) ?>;
    cont.innerHTML += "<br><br>총 <?php echo number_format($idx) ?>건 완료<br><br><font color=crimson><b>[끝]</b></font><br><br>";

    cont.innerHTML += "<br>등록되지 않은 제품<br>----------------------------<br><br>";
    if (err_boms.length > 0) {
        for (var idx in err_boms) {
            cont.innerHTML += err_boms[idx] + "<br>";
        }
    } else {
        cont.innerHTML += "등록되지 않은 제품이 없습니다.<br>";
    }
</script>