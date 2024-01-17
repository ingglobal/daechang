<?php
$sub_menu = "922140";
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
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
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

$g5['title'] = '완제품재고관리';
@include_once('./_top_menu_item.php');
include_once('./_head.php');
echo $g5['container_sub_title'];

$sql_common = " FROM {$g5_table_name} bom
                LEFT JOIN {$g5['item_table']} itm ON bom.bom_idx = itm.bom_idx
";

$where = array();
//$where[] = " (1) ";   // 디폴트 검색조건
$where[] = " bom.bom_status = 'ok' ";
$where[] = " bom_type = 'product' ";

// 해당 업체만
$where[] = " bom.com_idx = '".$_SESSION['ss_com_idx']."' ";

if ($stx) {
    switch ($sfl) {
		case ( $sfl == 'bom.bom_idx' || $sfl == 'bom_part_no' ) :
            $where[] = " ({$sfl} = '{$stx}') ";
            break;
        default :
            $where[] = " ({$sfl} LIKE '%{$stx}%') ";
            break;
    }
}

if($ser_cats){
    $where[] = " bct_idx = ".$ser_cats;
}


// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


if (!$sst) {
	$sst = "bom.bom_idx";
    //$sst = "bom_sort, ".$pre."_reg_dt";
    $sod = "DESC";
}
$sql_group = " GROUP BY bom.bom_idx ";
$sql_order = " ORDER BY {$sst} {$sod} ";

$rows = $g5['setting']['set_'.$fname.'_page_rows'] ? $g5['setting']['set_'.$fname.'_page_rows'] : $config['cf_page_rows'];
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " SELECT bom.bom_idx
            , bct_idx
            , bom_part_no
            , bom_name
            , bom_spec
            , bom_usage
            , bom_lead_time
            , bom_price
            , bom_safe_stock
            , bom_min_cnt
            , bom_stock
            , bom_type
            , itm.cst_idx_provider
            , itm.cst_idx_customer
		{$sql_common}
		{$sql_search}
        {$sql_group}
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

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>생산중에는 재고 수량이 계속 바뀌고 있으므로 현재고 항목은 다소간의 차이가 있을 수 있습니다. 현황 페이지를 통해서 보다 명확하게 확인하시기 바랍니다.</p>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" style="width:100%;">
<select name="ser_cats" id="ser_cats">
    <option value="">::차종::</option>
    <?=$g5['cats_options']?>
</select>
<script>
$('#ser_cats').val('<?=$ser_cats?>');
</script>
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
        <th scope="col" id="bom_list_chk">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">제품ID</th>
        <th scope="col" style="width:100px;">품번</th>
        <th scope="col">품명</th>
        <th scope="col">차종</th>
        <th scope="col" style="width:50px;">사양</th>
        <th scope="col" style="width:60px;">U/S</th>
        <th scope="col" style="width:60px;">리드타임</th>
        <th scope="col">판매가</th>
        <th scope="col">재료비</th>
        <th scope="col">안전재고</th>
        <th scope="col">재고알림</th>
        <th scope="col">수량</th>
        <th scope="col">현재고</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // print_r2($row);
        $row['cst_customer'] = get_table('customer','cst_idx',$row['cst_idx_customer'],'cst_name');
        $row['mb1'] = get_table('member','mb_id',$row['mb_id'],'mb_name');
        // print_r2($row['cst_customer']);

        // 버튼들
        $s_mod = '<a href="./'.$fname.'_form.php?'.$qstr.'&amp;w=u&'.$pre.'_idx='.$row[$pre.'_idx'].'" class="btn btn_03">수정</a>';

        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?=$bg?>" tr_id="<?=$row[$pre.'_idx']?>">
        <td class="td_chk">
            <label for="chk_<?=$i?>" class="sound_only"><?php echo get_text($row['bom_idx']); ?></label>
            <input type="checkbox" name="chk[]" value="<?=$row['bom_idx']?>" id="chk_<?=$i?>">
            <div class="chkdiv_btn" chk_no="<?=$i?>"></div>

            <input type="hidden" name="bom_idx[<?=$row['bom_idx']?>]" value="<?=$row['bom_idx']?>">
            <input type="hidden" name="bom_name[<?=$row['bom_idx']?>]" value="<?=$row['bom_name']?>">
            <input type="hidden" name="bom_part_no[<?=$row['bom_idx']?>]" value="<?=$row['bom_part_no']?>">
            <input type="hidden" name="bom_type[<?=$row['bom_idx']?>]" value="<?=$row['bom_type']?>">
            <input type="hidden" name="cst_idx_provider[<?=$row['bom_idx']?>]" value="<?=$row['cst_idx_provider']?>">
            <input type="hidden" name="cst_idx_customer[<?=$row['bom_idx']?>]" value="<?=$row['cst_idx_customer']?>">
            <input type="hidden" name="bom_price[<?=$row['bom_idx']?>]" value="<?=$row['bom_price']?>">
        </td>
        <td class="td_bom_idx font_size_7"><?=$row['bom_idx']?></td><!-- 제품ID -->
        <td class="td_bom_part_no font_size_7"><?=$row['bom_part_no']?></td><!-- 품번 -->
        <td class="td_bom_name font_size_7"><?=$row['bom_name']?></td><!-- 품명 -->
        <td class="td_bct_name font_size_7"><?=$g5['cats_key_val'][$row['bct_idx']]?></td><!-- 차종 -->
        <td class="td_bom_spec font_size_7"><?=$row['bom_spec']?></td><!-- 사양 -->
        <td class="td_bom_usage font_size_7"><?=$row['bom_usage']?></td><!-- U/S -->
        <td class="td_bom_lead_time font_size_7"><?=$row['bom_lead_time']?></td><!-- 리드타임 -->
        <td class="td_bom_price font_size_7"><?=number_format($row['bom_price'])?></td><!-- 판매가 -->
        <td class="td_bom_price font_size_7"><?=number_format($row['bom_price'])?></td><!-- 재료비 -->
        <td class="td_bom_safe_stock font_size_8"><?=number_format($row['bom_safe_stock'])?></td><!-- 안전재고 -->
        <td class="td_bom_min_cnt font_size_8"><?=number_format($row['bom_min_cnt'])?></td><!-- 재고알림 -->
        <td class="td_input_cnt">
            <input type="text" name="input_cnt[<?=$row['bom_idx']?>]" onclick="javascript:numtoprice(this)" class="frm_input input_cnt wg_wdx60 wg_right">
        </td>
        <td class="td_bom_stock font_size_8"><?=number_format($row['bom_stock'])?></td><!-- 현재고 -->
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="14" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    
<?php if (!auth_check($auth[$sub_menu],'w')) { ?>
    <input type="submit" name="act_button" value="선택재고입고" onclick="document.pressed=this.value" class="btn wg_btn_success">
    <input type="submit" name="act_button" value="선택재고차감" onclick="document.pressed=this.value" class="btn wg_btn_danger">
    <?php } ?>
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

    if(!is_exist_input_count()){
        alert("선택된 항목의 수량을 반드시 입력하셔야 합니다.");
        return false;
    }

    return true;
}

//선택된 품목중에 수량을 입력하지 않은 항목이 있는지 확인하는 함수
function is_exist_input_count(){
    var blank_exist = true;
    var chk = $('input[name="chk[]"]:checked');
    chk.each(function(){
        if(!$('input[name="input_cnt['+$(this).val()+']"]').val()){
            blank_exist = false;
        }
    });
    
    return blank_exist;
}
</script>

<?php
include_once ('./_tail.php');
?>
