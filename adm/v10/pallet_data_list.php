<?php
$sub_menu = "922160";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '파렛트데이터관리';
include_once('./_top_menu_pallet.php');
include_once('./_head.php');
if($is_admin){
    echo $g5['container_sub_title'];
}

// st_date, en_date
$st_date = $ser_st_date ?: date("Y-m-d", G5_SERVER_TIME);
$st_time = $st_time ?: '00:00:00';
$en_time = $en_time ?: '23:59:59';
$st_datetime = $st_date . ' ' . $st_time;
$en_datetime = $st_date . ' ' . $en_time;

foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
                $qstr .= '&'.$key.'[]='.$v2;
                $form_input .= '<input type="hidden" name="'.$key.'[]" value="'.$v2.'" class="frm_input">'.PHP_EOL;
            }
        }
        else {
            $qstr .= '&'.$key.'='.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value);
            $form_input .= '<input type="hidden" name="'.$key.'" value="'.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value).'" class="frm_input">'.PHP_EOL;
        }
    }
}


// 검색일자
$stat_date = $st_date ?: statics_date(G5_TIME_YMDHIS);
// echo $stat_date;

// 계획정지 offwork, 중복 제거가 있어서 불러오는 순서가 중요함
$ser_mms_idxs = $ser_mms_idx ? $ser_mms_idx . ',0' : '0';
$sql = "SELECT off_idx, mms_idx, off_period_type
        , off_start_time
        , off_end_time
        FROM {$g5['offwork_table']}
        WHERE com_idx = '" . $_SESSION['ss_com_idx'] . "'
            AND off_status IN ('ok')
            AND off_start_dt <= '" . $st_datetime . "'
            AND off_end_dt >= '" . $en_datetime . "'
            AND mms_idx IN (" . $ser_mms_idxs . ")
        ORDER BY mms_idx, off_period_type, off_start_time
";
// echo $sql.'<br>';
$rs = sql_query($sql, 1);
for ($i = 0; $row = sql_fetch_array($rs); $i++) {
    // print_r2($row);
    $offwork[$i]['mms_idx'] = $row['mms_idx'];
    $offwork[$i]['start'] = preg_replace("/:/", "", $row['off_start_time']);
    $offwork[$i]['end'] = preg_replace("/:/", "", $row['off_end_time']);
    // print_r2($offwork[$i]);
    // echo $i.'번째  <br>';
    // 중복 제거 처리 (앞에서 정의했던 것들과 겹치는 시간이 있으면 빼야 함, 중복 계산하지 않도록 한다.)
    if (is_array($offwork)) {
        $offworkold = $offwork;
        for ($j = 0; $j < sizeof($offworkold); $j++) {
            // print_r2($offworkold[$j]);
            // 완전 내부 포함인 경우는 중복 제외
            if ($offwork[$i]['start'] > $offworkold[$j]['start'] && $offwork[$i]['end'] < $offworkold[$j]['end']) {
                unset($offwork[$i]);
            }
            // 걸쳐 있는 경우
            else if ($offwork[$i]['start'] < $offworkold[$j]['end'] && $offwork[$i]['end'] > $offworkold[$j]['start']) {
                if ($offwork[$i]['start'] < $offworkold[$j]['start']) {
                    $offwork[$i]['end'] = $offworkold[$j]['start'];
                }
                if ($offwork[$i]['end'] > $offworkold[$j]['end']) {
                    $offwork[$i]['start'] = $offworkold[$j]['end'];
                }
            }
        }
    }
}
// print_r2($offwork);


// 설비별 비가동 downtime, 중복 제거가 있어서 불러오는 순서가 중요함
$day_arr = shift_period($st_date);
// print_r2($day_arr);
$sql = "SELECT dta_idx, mms_idx
        , dta_start_dt
        , dta_end_dt
        FROM {$g5['data_downtime_table']}
        WHERE com_idx = '" . $_SESSION['ss_com_idx'] . "'
            AND dta_start_dt <= '" . $day_arr['end_dt'] . "' AND dta_end_dt >= '" . $day_arr['start_dt'] . "'
        ORDER BY mms_idx, dta_start_dt
";
// echo $sql.'<br>';
$rs = sql_query($sql, 1);
for ($i = 0; $row = sql_fetch_array($rs); $i++) {
    // print_r2($row);
    $downtime[$i]['mms_idx'] = $row['mms_idx'];
    $downtime[$i]['start'] = preg_replace("/:/", "", substr($row['dta_start_dt'], 11));
    $downtime[$i]['end'] = preg_replace("/:/", "", substr($row['dta_end_dt'], 11));
    // print_r2($downtime[$i]);
}
// print_r2($downtime);


$sql_common = " FROM {$g5['production_item_table']} AS pri
                LEFT JOIN {$g5['production_table']} AS prd USING(prd_idx)
                LEFT JOIN {$g5['bom_table']} AS bom ON bom.bom_idx = pri.bom_idx
";

$where = array();
//$where[] = " (1) ";   // 디폴트 검색조건
$where[] = " prd_start_date = '" . $stat_date . "' ";    // 오늘 것만
$where[] = " bom.bom_type = 'product' ";    // 완제품만

// 해당 업체만
$where[] = " pri.com_idx = '" . $_SESSION['ss_com_idx'] . "' ";

// 설비번호 검색
if ($ser_mms_idx) {
    $where[] = " mms_idx = '" . $ser_mms_idx . "' ";
}

if ($stx && $sfl) {
    switch ($sfl) {
        case ($sfl == $pre . '_id' || $sfl == $pre . '_idx' || $sfl == 'mms_idx'):
            $where[] = " ({$sfl} = '{$stx}') ";
            break;
        case ($sfl == $pre . '_hp'):
            $where[] = " REGEXP_REPLACE(pic_hp,'-','') LIKE '" . preg_replace("/-/", "", $stx) . "' ";
            break;
        default:
            $where[] = " ({$sfl} LIKE '%{$stx}%') ";
            break;
    }
}

// 고객사
if ($ser_cst_idx_customer) {
    $where[] = " mtr.cst_idx_customer = '" . $ser_cst_idx_customer . "' ";
    $cst_customer = get_table('customer', 'cst_idx', $ser_cst_idx_customer);
}
// 공급사
if ($ser_cst_idx_provider) {
    $where[] = " mtr.cst_idx_provider = '" . $ser_cst_idx_provider . "' ";
    $cst_provider = get_table('customer', 'cst_idx', $ser_cst_idx_provider);
}

// 작업자
if ($ser_mb_id) {
    $where[] = " pri.mb_id = '" . $ser_mb_id . "' ";
    $mb1 = get_table('member', 'mb_id', $ser_mb_id, 'mb_name');
}

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE ' . implode(' AND ', $where);


if (!$sst) {
    $sst = "pri_idx";
    //$sst = "pri_sort, ".$pre."_reg_dt";
    $sod = "DESC";
}
$sql_order = " ORDER BY {$sst} {$sod} ";

$rows = $g5['setting']['set_' . $g5['file_name'] . '_page_rows'] ? $g5['setting']['set_' . $g5['file_name'] . '_page_rows'] : $config['cf_page_rows'];
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " SELECT prd_idx, pri_idx, pri.bom_idx, mms_idx, mb_id, pri_value, prd_start_date, bom.*
		{$sql_common}
		{$sql_search}
        {$sql_order}
		LIMIT {$from_record}, {$rows}
";
// echo $sql.BR;
$result = sql_query($sql, 1);

// 전체 게시물 수
$sql = " SELECT COUNT(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산


// 작업자 select list 추출
$sql1 = " SELECT mb_id {$sql_common} 
            WHERE prd_start_date = '" . $stat_date . "' AND pri.com_idx = '" . $_SESSION['ss_com_idx'] . "'
            GROUP BY mb_id
";
// echo $sql1.BR;
$rs = sql_query($sql1, 1);
for ($i = 0; $row = sql_fetch_array($rs); $i++) {
    $row['mb'] = get_table('member', 'mb_id', $row['mb_id'], 'mb_name');
    $row['mb_name'] = $row['mb']['mb_name'];
    // print_r2($row);
    $mb_selects[$i] = array('mb_id' => $row['mb_id'], 'mb_name' => $row['mb_name']);
}
// print_r2($mb_selects);

//배송기사 목록
$dsql = " SELECT mb.mb_name
                        , mb.mb_id
                        , cst.cst_name
                FROM {$g5['customer_member_table']} ctm 
                LEFT JOIN {$g5['customer_table']} cst ON ctm.cst_idx = cst.cst_idx 
                LEFT JOIN {$g5['member_table']} mb ON ctm.mb_id = mb.mb_id
            WHERE ctm.ctm_status = 'ok'
                AND cst.cst_status = 'ok'
                AND ctm.ctm_title = '13'
";
// echo $dsql.BR;
$dres = sql_query($dsql,1);
$dopts = '';
for($k=0;$drow=sql_fetch_array($dres);$k++){
    $dopts .= '<option value="'.$drow['mb_id'].'">'.$drow['mb_name'].'('.$drow['cst_name'].')'.'</option>';
}


$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';
?>
<style>
    .td_mng {
        width: 90px;
        max-width: 90px;
    }

    .td_pri_subject a,
    .td_mb_name a {
        text-decoration: underline;
    }

    .td_pri_price {
        width: 80px;
    }

    .td_pic_value a {
        color: #ff5e5e;
    }
    .tr_total td {
        background-color: #162037;
    }

</style>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총건수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>건</span></span>
</div>

<div class="local_desc01 local_desc" style="display:no ne;">
    <p><?= $st_date ?> 각 작업자별 생산 현황입니다.</p>
    <p>10분 정도 시차 Delay(딜레이)를 두고 실시간 반영됩니다. 시스템 부하를 분산시키기 위한 불가피한 지연 시간입니다.</p>
    <p>항목 중에서 비가동 시간이 의미하는 바는 (<a href="<?= G5_USER_ADMIN_URL ?>/system/offwork_list.php">계획정지</a> + <a href="<?= G5_USER_ADMIN_URL ?>/system/manual_downtime_list.php">설비비가동</a>)입니다. 해당 페이지에서 설정해 주세요.</p>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" style="width:100%;">
    <label for="sfl" class="sound_only">검색대상</label>
    <input type="text" name="ser_st_date" value="<?= $st_date ?>" id="st_date" class="frm_input" autocomplete="off" style="width:90px;">
    <select name="ser_mms_idx" id="ser_mms_idx">
        <option value="">설비전체</option>
        <?php
        // 해당 범위 안의 모든 설비를 select option으로 만들어서 선택할 수 있도록 한다.
        // Get all the mms_idx values to make them optionf for selection.
        $sql2 = "SELECT mms_idx, mms_name
            FROM {$g5['mms_table']}
            WHERE com_idx = '" . $_SESSION['ss_com_idx'] . "'
            ORDER BY mms_sort, mms_idx       
    ";
        // echo $sql2.'<br>';
        $result2 = sql_query($sql2, 1);
        for ($i = 0; $row2 = sql_fetch_array($result2); $i++) {
            // print_r2($row2);
            echo '<option value="' . $row2['mms_idx'] . '" ' . get_selected($ser_mms_idx, $row2['mms_idx']) . '>' . $row2['mms_name'] . '(' . $row2['mms_idx'] . ')</option>';
        }
        ?>
    </select>
    <script>
        $('select[name=ser_mms_idx]').val("<?= $ser_mms_idx ?>").attr('selected', 'selected');
    </script>

    <select name="ser_mb_id" id="ser_mb_id">
        <option value="">작업자전체</option>
        <?php
        for ($i = 0; $i < sizeof($mb_selects); $i++) {
            echo '<option value="' . $mb_selects[$i]['mb_id'] . '">' . $mb_selects[$i]['mb_name'] . ' (' . $mb_selects[$i]['mb_id'] . ')</option>';
        }
        ?>
    </select>
    <script>
        $('#ser_mb_id').val('<?= $ser_mb_id ?>');
    </script>
    <select name="sfl" id="sfl">
        <option value="">검색항목</option>
        <option value="bom_part_no" <?= get_selected($sfl, 'bom_part_no') ?>>품번</option>
        <option value="bom_name" <?= get_selected($sfl, 'bom_name') ?>>품명</option>
        <option value="pri.bom_idx" <?= get_selected($sfl, 'pri.bom_idx') ?>>BOM번호</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" class="btn_submit btn_submit2" value="검색">
</form>

<form name="form01" id="form01" action="./<?= $g5['file_name'] ?>_update.php" onsubmit="return form01_submit(this);" method="post">
<!-- <div name="form01" id="form01"> -->
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">
    <input type="hidden" name="w" value="">
    <input type="hidden" name="target_day" value="<?php echo $st_date ?>">
    <?= $form_input ?>
    <script>
    
    </script>
    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <th scope="col" id="pri_list_chk" style="display:no ne;">
                        <label for="chkall" class="sound_only">전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col">생산계획ID</th>
                    <th scope="col" style="min-width:200px;">품번/품명</th>
                    <th scope="col">구분</th>
                    <th scope="col">차종</th>
                    <th scope="col">설비</th>
                    <th scope="col">작업자</th>
                    <th scope="col">생산시간</th>
                    <th scope="col">비가동</th>
                    <th scope="col">목표</th>
                    <th scope="col">생산수량</th>
                    <th scope="col">빠레트수량</th>
                    <th scope="col" style="width:150px;">배송기사</th>
                    <th scope="col">적재수량</th>
                    <th scope="col">빠레트개수</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $testmatnual_total = 0;
                $plt_stock_total = 0;
                // $pic_sum_total = 0;
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    // print_r2($row);
                    $row['cst_customer'] = get_table('customer', 'cst_idx', $row['cst_idx_customer'], 'cst_name');
                    $row['bct'] = get_table('bom_category', 'bct_idx', $row['bct_idx'], 'bct_name');
                    $row['mb1'] = get_table('member', 'mb_id', $row['mb_id'], 'mb_name');
                    // print_r2($row['cst_customer']);

                    // 현재 생산수량 합계
                    // $sql1 = " SELECT SUM(pic_value) AS pic_sum FROM {$g5['production_item_count_table']} WHERE pri_idx = '" . $row['pri_idx'] . "' AND pic_date = '" . $stat_date . "' ";
                    $sql1 = " SELECT COUNT(itm_value) AS pic_sum FROM {$g5['item_table']} WHERE pri_idx = '{$row['pri_idx']}' AND itm_date = '{$stat_date}' AND itm_status IN ('finish','check','delivery')  ";
                    // echo $sql1.BR;
                    $row['pic'] = sql_fetch($sql1, 1);

                    // 현재 파렛트 갯수
                    $sqlp = " SELECT COUNT(DISTINCT plt_idx) AS cnt FROM {$g5['item_table']} WHERE pri_idx = '{$row['pri_idx']}' AND plt_idx != '0' AND itm_status NOT IN ('defect','scrap','trash')
                    ";
                    $resp = sql_fetch($sqlp);
                    $row['plt_stock'] = $resp['cnt'];
                    $plt_stock_total += $row['plt_stock'];

                    // 생산 시작 및 종료시간 ----------------------------------------------------------
                    $sql1 = "   SELECT MIN(pic_reg_dt) AS pic_min_dt, MAX(pic_reg_dt) AS pic_max_dt
                    FROM {$g5['production_item_count_table']} 
                    WHERE pri_idx = '" . $row['pri_idx'] . "' AND pic_date = '" . $stat_date . "' ";
                    // echo $sql1.BR;
                    $row['dt'] = sql_fetch($sql1, 1);
                    // print_r2($row['dt']);
                    $row['pri_hours'] = $row['dt']['pic_min_dt'] ? substr($row['dt']['pic_min_dt'], 11, -3) : '';
                    $row['pri_hours'] .= $row['dt']['pic_max_dt'] ? '~' . substr($row['dt']['pic_max_dt'], 11, -3) : '';
                    // 생산 시작 및 종료시간이 존재할 때 ----------------------------------------------------------
                    if ($row['dt']['pic_min_dt'] && $row['dt']['pic_max_dt']) {
                        // print_r2($row['dt']);
                        $row['pri_work_seconds'] = strtotime($row['dt']['pic_max_dt']) - strtotime($row['dt']['pic_min_dt']);
                        $row['pri_work_min'] = $row['pri_work_seconds'] / 60;
                        $row['pri_work_min_text'] = $row['pri_work_min'] ? '<br>(' . number_format($row['pri_work_min'], 2) . '분)' : '';
                        // echo $row['pri_work_seconds'].BR;
                        $row['pri_work_hour'] = $row['pri_work_seconds'] / 3600;  // 1. 1차 작업시간 계산 //<-----------
                        // echo $row['pri_work_hour'].BR;

                        // 실제 적용시간 범위
                        $row['dta_start_his'] = preg_replace("/:/", "", substr($row['dt']['pic_min_dt'], 11));
                        $row['dta_end_his'] = preg_replace("/:/", "", substr($row['dt']['pic_max_dt'], 11));
                        

                        // 계획정지 (일단은 설비 상관없이 전체 적용), 위에서 만들어둔 배열 활용
                        for ($j = 0; $j < @sizeof($offwork); $j++) {
                            
                            // 같은 값도 있네요. (통과)
                            if ($row['dta_start_his'] == $row['dta_end_his']) {
                                continue;
                            }
                            // 완전 벗어난 경우는 무조건 건너뜀
                            else if ($row['dta_start_his'] >= $offwork[$j]['start'] && $row['dta_end_his'] <= $offwork[$j]['end']) {
                                continue;
                            }
                            // 완전 포함인 경우는 무조건 공제시간
                            else if ($row['dta_start_his'] <= $offwork[$j]['start'] && $row['dta_end_his'] >= $offwork[$j]['end']) {
                                $row['offwork_arr'][$i][$j]['start'] = $offwork[$j]['start'];  // 하단 비가동에서 재활용
                                $row['offwork_arr'][$i][$j]['end'] = $offwork[$j]['end'];      // 하단 비가동에서 재활용
                                $row['offwork_sec'][$i] += num2seconds($offwork[$j]['end']) - num2seconds($offwork[$j]['start']);
                            }
                            // 걸쳐 있는 경우
                            else if ($row['dta_start_his'] <= $offwork[$j]['end'] && $row['dta_end_his'] >= $offwork[$j]['start']) {
                               
                                if ($row['dta_start_his'] >= $offwork[$j]['start']) {
                                    $row['offwork_arr'][$i][$j]['start'] = $row['dta_start_his'];  // 하단 비가동에서 재활용
                                    $row['offwork_arr'][$i][$j]['end'] = $offwork[$j]['end'];      // 하단 비가동에서 재활용
                                    
                                    $row['offwork_sec'][$i] += num2seconds($offwork[$j]['end']) - num2seconds($row['dta_start_his']);
                                  
                                }
                                if ($row['dta_end_his'] <= $offwork[$j]['end']) {
                                    $row['offwork_arr'][$i][$j]['start'] = $offwork[$j]['start'];  // 하단 비가동에서 재활용
                                    $row['offwork_arr'][$i][$j]['end'] = $row['dta_end_his'];      // 하단 비가동에서 재활용
                                    // $offwork[$j]['end'] = $row['dta_end_his']; // 원본을 바꾸면 안 됨 (for문에서 변경되므로)
                                    $row['offwork_sec'][$i] += num2seconds($row['dta_end_his']) - num2seconds($offwork[$j]['start']);
                                }
                            }
                        }
                        
                        $row['offwork_hour'][$i] = $row['offwork_sec'][$i] ? $row['offwork_sec'][$i] / 3600 : 0;  // convert to hour unit.
                        $row['pri_work_hour'] -= $row['offwork_hour'][$i];  // 2. 2차 작업시간 계산: 계획정지 시간 제외해 줌 //<-----------



                        // 비가동정지 (downtime), 위에서 만들어둔 배열 활용
                        for ($j = 0; $j < @sizeof($downtime); $j++) {

                            // 해당 설비인 경우만 적용함
                            if ($downtime[$j]['mms_idx'] == $row['mms_idx']) {
                                
                                if ($row['bom_idx'] == 261 && $row['mms_idx'] == 140 && $row['mb_id'] == '01056058011') {
                                
                                    // 같은 값도 있네요. (통과)
                                    if ($row['dta_start_his'] == $row['dta_end_his']) {
                                        continue;
                                    }
                                    // 완전 벗어난 경우는 무조건 건너뜀
                                    else if ($row['dta_start_his'] >= $downtime[$j]['start'] && $row['dta_end_his'] <= $downtime[$j]['end']) {
                                        continue;
                                    }
                                    // 완전 포함인 경우는 무조건 공제
                                    else if ($row['dta_start_his'] <= $downtime[$j]['start'] && $row['dta_end_his'] >= $downtime[$j]['end']) {
                                        $row['downtime_arr'][$i][$j]['start'] = $downtime[$j]['start'];  // 하단 중복처리에서 재활용
                                        $row['downtime_arr'][$i][$j]['end'] = $downtime[$j]['end'];      // 하단 중복처리에서 재활용
                                        $row['downtime_sec'][$i] += num2seconds($downtime[$j]['end']) - num2seconds($downtime[$j]['start']);
                                        // echo $downtime[$j]['end'].' - '.$downtime[$j]['start'].' --- 1 완전포함'.BR;
                                    }
                                    // 걸쳐 있는 경우
                                    else if ($row['dta_start_his'] <= $downtime[$j]['end'] && $row['dta_end_his'] >= $downtime[$j]['start']) {
                                        // echo $j.BR;
                                        // echo $row['dta_start_his'] .'<='. $downtime[$j]['end'] .'&&'. $row['dta_end_his'] .'>='. $downtime[$j]['start'].BR;
                                        if ($row['dta_start_his'] >= $downtime[$j]['start']) {
                                            $row['downtime_arr'][$i][$j]['start'] = $row['dta_start_his'];  // 하단 중복처리에서 재활용
                                            $row['downtime_arr'][$i][$j]['end'] = $downtime[$j]['end'];      // 하단 중복처리에서 재활용
                                            $row['downtime_sec'][$i] += num2seconds($downtime[$j]['end']) - num2seconds($row['dta_start_his']);
                                            // echo $downtime[$j]['end'].' - '.$row['dta_start_his'].' --- 2 앞쪽'.BR;
                                        }
                                        if ($row['dta_end_his'] <= $downtime[$j]['end']) {
                                            $row['downtime_arr'][$i][$j]['start'] = $downtime[$j]['start'];  // 하단 중복처리에서 재활용
                                            $row['downtime_arr'][$i][$j]['end'] = $row['dta_end_his'];      // 하단 중복처리에서 재활용
                                            $row['downtime_sec'][$i] += num2seconds($row['dta_end_his']) - num2seconds($downtime[$j]['start']);
                                            // echo $row['dta_end_his'].' - '.$downtime[$j]['start'].' --- 3 뒤쪽'.BR;
                                        }
                                    }
                                    // echo $row['downtime_sec'][$i].' 초'.BR;
                                    // print_r2($row['downtime_arr'][$i]);  // 최종 적용한 downtime 배열


                                } // text print <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< 89311-S8530, 유말, 50호기
                            }
                        }
                        $row['downtime_hour'][$i] = $row['downtime_sec'][$i] ? $row['downtime_sec'][$i] / 3600 : 0;  // convert to hour unit.
                        $row['pri_work_hour'] -= $row['downtime_hour'][$i];  // 3. 3차 작업시간 계산: 비가동 시간 제외해 줌 //<-----------


                        // 비가동과 계획정비가 중복되는 시간은 다시 공제에서 제외 (두번 제외된 구간 복구)
                        if ($row['downtime_sec'][$i]) {
                            // print_r2($row['offwork_arr'][$i]);
                            // print_r2($row['downtime_arr'][$i]);  // 최종 적용한 downtime 배열
                            // 계획정비 배열 전체를 돌면서 중복 부분 제거 (비가동을 돌면서 계획정비를 처리해도 마찬가지!)
                            if (is_array($row['offwork_arr'][$i])) {
                                foreach ($row['offwork_arr'][$i] as $k1 => $v1) {
                                    // print_r2($v1);
                                    // echo $v1['start'].'~'.$v1['end'].' 계획정지 구간<br>';

                                    // 각 구간마다 중복되는 부분 추출
                                    if (is_array($row['downtime_arr'][$i])) {
                                        foreach ($row['downtime_arr'][$i] as $k2 => $v2) {
                                            // print_r2($v2);
                                            // echo '---> '.$v2['start'].'~'.$v2['end'].' 비가동 구간<br>';

                                            // 비가동이 계획정비에 완전 포함되는 경우는 중복된 시간이므로 추출
                                            if ($v2['start'] >= $v1['start'] && $v2['end'] <= $v1['end']) {
                                                $row['duplicated_sec'][$i] += num2seconds($v2['end']) - num2seconds($v2['start']);
                                                // echo $v2['end'].' - '.$v2['start'].' --- 2 완전포함'.BR;
                                            }
                                            // 걸쳐 있는 경우
                                            else if ($v2['end'] >= $v1['start'] && $v2['start'] <= $v1['end']) {
                                                // echo $v2['start'].'~'.$v2['end'].' 2 적용시간범위<br>';
                                                // print_r2($v1);
                                                // 앞쪽 구간에 걸친 경우
                                                if ($v2['end'] >= $v1['start']) {
                                                    // print_r2($v1);
                                                    $row['duplicated_sec'][$i] += num2seconds($v2['end']) - num2seconds($v1['start']);
                                                    // echo $v2['end'].' - '.$v1['start'].' --- 2 앞쪽구간'.BR;
                                                }
                                                // 뒤쪽 구간에 걸친 경우
                                                else if ($v2['start'] <= $v1['end']) {
                                                    // print_r2($v1);
                                                    $row['duplicated_sec'][$i] += num2seconds($v1['end']) - num2seconds($v2['start']);
                                                    // echo $v1['end'].' - '.$v2['start'].' --- 3 뒤쪽구간'.BR;
                                                }
                                            }
                                        }
                                    }
                                    // echo '============='.BR;

                                }
                            }
                            // echo $row['duplicated_sec'][$i].' 중복더블차감된 부분이므로 다시 복구해 줘야 하는 초'.BR;
                        }
                        $row['duplicated_hour'][$i] = $row['duplicated_sec'][$i] ? $row['duplicated_sec'][$i] / 3600 : 0;  // convert to hour unit.
                        $row['pri_work_hour'] += $row['duplicated_hour'][$i];  // 4. 4차 작업시간 계산: 계획정비와 비가동에서 중복제외된 시간 복구해 줌 //<-----------


                        // 비가동전체 = 계획정지 + 비가동 - 중복적용시간
                        $row['offdown_seconds'] = $row['offwork_sec'][$i] + $row['downtime_sec'][$i] - $row['duplicated_sec'][$i];
                        $row['offdown_min'] = $row['offdown_seconds'] ? $row['offdown_seconds'] / 60 : 0;
                        $row['offdown_text'] = $row['offdown_min'] ? number_format($row['offdown_min'], 1) . '분' : '';

                    }
                    //// 생산 시작 및 종료시간이 존재할 때 ----------------------------------------------------------

                    // 비율
                    $row['rate'] = ($row['pri_value']) ? $row['pic']['pic_sum'] / $row['pri_value'] * 100 : 0;
                    $row['rate_color'] = '#d1c594';
                    $row['rate_color'] = ($row['rate'] >= 80) ? '#72ddf5' : $row['rate_color'];
                    $row['rate_color'] = ($row['rate'] >= 100) ? '#ff9f64' : $row['rate_color'];

                    
                    // 테스트 수동입력
                    $mit_sql = " SELECT mit_value FROM {$g5['material_item_table']}
                                    WHERE pri_idx = '{$row['pri_idx']}'
                                        AND mms_idx = '{$row['mms_idx']}'
                                        AND bom_idx = '{$row['bom_idx']}'
                                        AND mb_id = '{$row['mb_id']}'
                                        AND mit_date = '{$st_date}'
                    ";
                    $mit_res = sql_fetch($mit_sql);
                    // 버튼들
                    $s_mod = '<a href="./' . $fname . '_form.php?' . $qstr . '&amp;w=u&' . $pre . '_idx=' . $row[$pre . '_idx'] . '" class="btn btn_03">수정</a>';


                    
                    

                    $bg = 'bg' . ($i % 2);
                ?>
                    <tr class="<?= $bg ?>" tr_id="<?= $row[$pre . '_idx'] ?>">
                        <td class="td_chk" style="display:no ne;">
                            <input type="checkbox" name="chk[]" value="<?=$i?>" id="chk_<?=$i?>">
                            <input type="hidden" name="prd_idx[<?=$i?>]" value="<?=$row['prd_idx']?>">
                            <input type="hidden" name="pri_idx[<?=$i?>]" value="<?=$row['pri_idx']?>">
                            <input type="hidden" name="bom_idx[<?=$i?>]" value="<?=$row['bom_idx']?>">
                            <input type="hidden" name="bom_type[<?=$i?>]" value="<?=$row['bom_type']?>">
                            <input type="hidden" name="bom_part_no[<?=$i?>]" value="<?=$row['bom_part_no']?>">
                            <input type="hidden" name="bom_name[<?=$i?>]" value="<?=$row['bom_name']?>">
                            <input type="hidden" name="mms_idx[<?=$i?>]" value="<?=$row['mms_idx']?>">
                            <input type="hidden" name="plt_stock[<?=$i?>]" value="<?=$row['plt_stock']?>">
                            <input type="hidden" name="mb_id[<?=$i?>]" value="<?=$row['mb_id']?>">
                            <input type="hidden" name="pri_value[<?=$i?>]" value="<?=$row['pri_value']?>">
                        </td>
                        <td class=""><?=$row['pri_idx']?></td><!-- 생산계획ID -->
                        <td class="td_part_no_name td_left"><!-- 품번/품명 -->
                            <?= $row['bom_part_no'] ?><br><?= $row['bom_name'] ?>
                        </td>
                        <td class="td_pri_type font_size_7"><?= $g5['set_bom_type_value'][$row['bom_type']] ?></td><!-- 구분 -->
                        <td class="td_bct_idx font_size_7"><?= $row['bct']['bct_name'] ?></td><!-- 차종 -->
                        <td class="td_mms_name"><a href="?ser_mms_idx=<?= $row['mms_idx'] ?>"><?= $g5['mms'][$row['mms_idx']]['mms_name'] ?></a></td><!-- 설비 -->
                        <td class="td_mb_name"><a href="?ser_mb_id=<?= $row['mb_id'] ?>"><?= $row['mb1']['mb_name'] ?></a></td><!-- 작업자 -->
                        <td class="td_pri_hours font_size_7"><?= $row['pri_hours'] ?><?= $row['pri_work_min_text'] ?></td><!-- 생산시간 -->
                        <td class="td_pri_offdown font_size_7"><?= $row['offdown_text'] ?></td><!-- 비가동 -->
                        <td class="td_pri_value"><?= $row['pri_value'] ?></td><!-- 목표 -->
                        <td class="td_pic_value color_red">
                            <input type="hidden" name="pic_sum[<?=$i?>]" value="<?=(int)$row['pic']['pic_sum']?>">
                            <?=(int)$row['pic']['pic_sum']?>
                        </td><!-- 수량 -->
                        <td class="">
                            <?=$row['plt_stock']?>
                        </td><!-- 빠레트수량 -->
                        <td class="td_dlv_man">
                            <select name="dlv_mb_id[<?=$i?>]" id="dlv_mb_id_<?=$i?>">
                                <?=$dopts?>
                            </select>
                        </td><!-- 배송기사 -->
                        <td class="td_pkg_cnt">
                            <input type="text" name="pck_cnt[<?=$i?>]" id="pck_cnt_<?=$i?>" no="<?=$i?>" stock="<?=(int)$row['pic']['pic_sum']?>" value="" onclick="javascript:pck_Number(this)" class="frm_input wg_wdx60 wg_right inp_pic_sum">
                        </td><!-- 적재수량 -->
                        <td class="td_plt_cnt">
                            <input type="text" name="plt_cnt[<?=$i?>]" id="plt_cnt_<?=$i?>" no="<?=$i?>" stock="<?=(int)$row['pic']['pic_sum']?>" value="" onclick="javascript:plt_Number(this)" class="frm_input wg_wdx60 wg_right inp_pic_sum">
                        </td><!-- 빠레트개수 -->
                    </tr>
                <?php
                    // 목표 합계
                    $target_goal += $row['pri_value'];
                    $production_total += (int)$row['pic']['pic_sum'];
                }
                if ($i == 0) {
                    echo '<tr><td colspan="20" class="empty_table">자료가 없습니다.</td></tr>';
                } else {
                    
                ?>
                    <tr class="tr_total" tr_id="">
                        <td class="td_chk" style="display:no ne;"></td>
                        <td colspan="6">합계</td>
                        <td class="td_pri_hours font_size_7"></td><!-- 생산시간 -->
                        <td class="td_offdown"></td>
                        <td class="td_pri_value"><?= number_format($target_goal) ?></td>
                        <td class="td_pic_value color_red"><?= number_format($production_total) ?></td>
                        <td class="td_plt_stock"><?=$plt_stock_total?></td><!-- 빠레트수량 -->
                        <td class="td_dlv_man"></td><!-- 배송기사 -->
                        <td class="td_pkg_cnt"><!-- 적재수량 -->
                        <td class="td_plt_cnt"><!-- 빠레트개수 -->
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <?php if (!auth_check($auth[$sub_menu],'w',1)) { ?>
        <input type="submit" name="act_button" value="선택빠레트추가" onclick="document.pressed=this.value" class="btn btn_02" style="display:no ne;">
        <input type="submit" name="act_button" value="선택빠레트삭제" onclick="document.pressed=this.value" class="btn btn_02" style="display:no ne;">
        <?php } ?>
    </div>
<!-- </div> -->
</form><!--#form01-->
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?>

<script>
    var posY;
    $(function(e) {
        // 생산현황동기화
        $(".btn_production_sync").click(function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            winProductionSync = window.open(href, "winProductionSync", "left=300,top=150,width=550,height=600,scrollbars=1");
            winProductionSync.focus();
        });

        $("input[name$=_date]").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "c-99:c+99",
            maxDate: "+0d"
        });
    });

    function pck_Number(object){
        var no = $(object).attr('no');
        var stock = $(object).attr('stock');
        $(object).keyup(function(){
            $(this).val($(this).val().replace(/[^0-9|-]/g,""));

            if($(this).val() == '0') {
                $(this).val('');
            }
            else {
                
            }
        });
    }

    function plt_Number(object){
        $(object).keyup(function(){
            $(this).val($(this).val().replace(/[^0-9|-]/g,""));

            if($(this).val() == '0') {
                $(this).val('');
            }
        });
    }


    function form01_submit(f) {

        if (!is_checked("chk[]")) {
            alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if (document.pressed == "선택빠레트추가") {
            $('input[name="w"]').val('u');
        } else if (document.pressed == "선택빠레트삭제") {
            if (!confirm("선택한 항목(들)의 빠레트를 정말 삭제 하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }
</script>

<?php
include_once('./_tail.php');
?>