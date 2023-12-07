<?php
$sub_menu = "922130";
include_once('./_common.php');

auth_check($auth[$sub_menu],"r");

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'bom';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_list/","",$g5['file_name']); // _list을 제외한 파일명
//print_r3($_REQUEST);
// 추가 변수 생성
foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
    //    print_r3($key.'='.$value);
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
//                print_r3($key.$k2.'='.$v2);
                $qstr .= '&'.$key.'[]='.$v2;
                $form_input .= '<input type="hidden" name="'.$key.'[]" value="'.$v2.'" class="frm_input">'.PHP_EOL;
            }
        }
        else {
            $qstr .= '&'.$key.'='.$value;
            $form_input .= '<input type="hidden" name="'.$key.'" value="'.$value.'" class="frm_input">'.PHP_EOL;
        }
    }
}
// print_r3($qstr);

$g5['title'] = '자재별 재고현황';
@include_once('./_top_menu_material.php');
include_once('./_head.php');
echo $g5['container_sub_title'];

$sql_common = " FROM {$g5_table_name} AS ".$pre."
                LEFT JOIN {$g5['bom_category_table']} AS bct USING(bct_idx)
";

$where = array();
//$where[] = " (1) ";   // 디폴트 검색조건
$where[] = " ".$pre."_status NOT IN ('delete', 'trash') AND bom_type IN ('".implode("','",$g5['set_mtr_type_key'])."') ";

// 해당 업체만
$where[] = " bom.com_idx = '".$_SESSION['ss_com_idx']."' ";

if ($stx) {
    switch ($sfl) {
		case ( $sfl == $pre.'_id' || $sfl == $pre.'_idx' ) :
            $where[] = " ({$sfl} = '{$stx}') ";
            break;
		case ($sfl == $pre.'_hp') :
            $where[] = " REGEXP_REPLACE(itm_hp,'-','') LIKE '".preg_replace("/-/","",$stx)."' ";
            break;
        default :
            $where[] = " ({$sfl} LIKE '%{$stx}%') ";
            break;
    }
}


// 기간 검색
if ($ser_st_date)	// 시작일 있는 경우
    $where[] .= " bom_reg_dt >= '{$ser_st_date} 00:00:00' ";
if ($ser_en_date)	// 종료일 있는 경우
    $where[] .= " bom_reg_dt <= '{$ser_en_date} 23:59:59' ";

// 고객사
if ($ser_cst_idx_customer) {
    $where[] = " cst_idx_customer = '".$ser_cst_idx_customer."' ";
    $cst_customer = get_table('customer','cst_idx',$ser_cst_idx_customer);
}
// 공급사
if ($ser_cst_idx_provider) {
    $where[] = " cst_idx_provider = '".$ser_cst_idx_provider."' ";
    $cst_provider = get_table('customer','cst_idx',$ser_cst_idx_provider);
}

// 단가
if ($ser_st_price) {
    $where[] = " bom_price >= '".preg_replace("/,/","",$ser_st_price)."' ";
}
if ($ser_en_price) {
    $where[] = " bom_price <= '".preg_replace("/,/","",$ser_en_price)."' ";
}

// 상태
if($ser_bom_status) {
    $where[] = " bom_status = '".$ser_bom_status."' ";
}

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


if (!$sst) {
	$sst = "bom_idx";
    //$sst = "bom_sort, ".$pre."_reg_dt";
    $sod = "DESC";
}
$sql_order = " ORDER BY {$sst} {$sod} ";

$rows = $g5['setting']['set_'.$fname.'_page_rows'] ? $g5['setting']['set_'.$fname.'_page_rows'] : $config['cf_page_rows'];
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " SELECT *
		{$sql_common}
		{$sql_search}
        {$sql_order}
		LIMIT {$from_record}, {$rows}
";
// echo $sql.BR;
$result = sql_query($sql,1);

// 전체 게시물 수
$sql = " SELECT COUNT(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

// status count
$sql = " SELECT COUNT(*) AS cnt {$sql_common} {$sql_search} AND bom_status = 'pending' ";
$row = sql_fetch($sql);
$pending_count = $row['cnt'];


?>
<style>
.td_mng {width:90px;max-width:90px;}
.td_bom_subject a, .td_mb_name a {text-decoration: underline;}
.td_bom_price {width:80px;}
</style>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총건수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>건</span></span>
</div>

<div class="local_desc01 local_desc" style="display:none;">
    <p>생산중에는 재고 수량이 계속 바뀌고 있으므로 현재고 항목은 다소간의 차이가 있을 수 있습니다. 현황 페이지를 통해서 보다 명확하게 확인하시기 바랍니다.</p>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" style="width:100%;">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="">검색항목</option>
    <option value="bom_part_no" <?=get_selected($sfl, 'bom_part_no')?>>품번</option>
    <option value="bom_name" <?=get_selected($sfl, 'bom_name')?>>품명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit btn_submit2" value="검색">
</form>












<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="w" value="">
<?=$form_input?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" style="width:100px;">품번</th>
        <th scope="col">품명</th>
        <th scope="col">구분</th>
        <th scope="col">차종</th>
        <th scope="col" style="width:50px;">사양</th>
        <th scope="col" style="width:60px;">U/S</th>
        <th scope="col" style="width:60px;">리드타임</th>
        <th scope="col">판매가</th>
        <th scope="col">재료비</th>
        <th scope="col">안전재고</th>
        <th scope="col">재고알림</th>
        <th scope="col">현재고</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // print_r2($row);
        $row['cst_customer'] = get_table('customer','cst_idx',$row['cst_idx_customer'],'cst_name');
        $row['cst_provider'] = get_table('customer','cst_idx',$row['cst_idx_provider'],'cst_name');
        $row['mb1'] = get_table('member','mb_id',$row['mb_id'],'mb_name');
        // print_r2($row['cst_customer']);

        // 현재고 추출
		$sql2 = " SELECT COUNT(mtr_idx) AS mtr_sum FROM {$g5['material_table']}
                    WHERE bom_idx = '".$row['bom_idx']."' AND mtr_type IN ('".implode("','",$g5['set_mtr_type_key'])."') AND mtr_status = 'ok'
        ";
        // echo $sql2.BR;
		$row['mtr'] = sql_fetch($sql2,1);
        // print_r2($row['mtr']);
        $row['bom_stock'] = $row['mtr']['mtr_sum'];

        //재료비
        $sql3 = " SELECT SUM(bit_count * bom_price) AS bit_sum
                    FROM {$g5['v_bom_item_table']} boi
                WHERE bom_idx_product = '{$row['bom_idx']}'
                GROUP BY bom_idx_product
        ";
        $res3 = sql_fetch($sql3);
        $row['mtr_prices'] = $res3['bit_sum'];

        // 버튼들
        $s_mod = '<a href="./'.$fname.'_form.php?'.$qstr.'&amp;w=u&'.$pre.'_idx='.$row[$pre.'_idx'].'" class="btn btn_03">수정</a>';

        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?=$bg?>" tr_id="<?=$row[$pre.'_idx']?>">
        <td class="td_bom_part_no font_size_7"><?=$row['bom_part_no']?></td><!-- 품번 -->
        <td class="td_bom_name font_size_7"><?=$row['bom_name']?></td><!-- 품명 -->
        <td class="td_bom_type font_size_7"><?=$g5['set_bom_type_value'][$row['bom_type']]?></td><!-- 구분 -->
        <td class="td_bct_name font_size_7"><?=$row['bct_name']?></td><!-- 차종 -->
        <td class="td_bom_spec font_size_7"><?=$row['bom_spec']?></td><!-- 사양 -->
        <td class="td_bom_usage font_size_7"><?=$row['bom_usage']?></td><!-- U/S -->
        <td class="td_bom_lead_time font_size_7"><?=$row['bom_lead_time']?></td><!-- 리드타임 -->
        <td class="td_bom_price font_size_7"><?=number_format($row['bom_price'])?></td><!-- 판매가 -->
        <td class="td_mtr_prices font_size_7"><?=number_format($row['mtr_prices'])?></td><!-- 재료비 -->
        <td class="td_bom_safe_stock font_size_8"><?=number_format($row['bom_safe_stock'])?></td><!-- 안전재고 -->
        <td class="td_bom_min_cnt font_size_8"><?=number_format($row['bom_min_cnt'])?></td><!-- 재고알림 -->
        <td class="td_bom_stock font_size_8"><?=number_format($row['bom_stock'])?></td><!-- 현재고 -->
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
var posY;
$(function(e) {
    $("input[name$=_date]").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true,
        yearRange: "c-99:c+99",
        //maxDate: "+0d"
    });	 
});


function form01_submit(f)
{

    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	if(document.pressed == "선택수정") {
		$('input[name="w"]').val('u');
	}
	else if(document.pressed == "선택삭제") {
		if (!confirm("선택한 항목(들)을 정말 삭제 하시겠습니까?\n복구가 어려우니 신중하게 결정 하십시오.")) {
			return false;
		}
		// else {
		// 	$('input[name="w"]').val('d');
		// }
	}
	else if(document.pressed == "선택복제") {
		if (!confirm("선택한 항목(들)을 정말 복제 하시겠습니까?")) {
			return false;
		}
	}

    return true;
}
</script>

<?php
include_once ('./_tail.php');
?>
