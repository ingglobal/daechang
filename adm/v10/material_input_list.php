<?php
$sub_menu = "922130";
include_once('./_common.php');

auth_check($auth[$sub_menu],"r");

$g5['title'] = '자재별입고';
@include_once('./_top_menu_material.php');
include_once('./_head.php');
echo $g5['container_sub_title'];

$sql_common = " FROM {$g5['bom_table']} bom
                LEFT JOIN {$g5['material_table']} mtr ON bom.bom_idx = mtr.bom_idx
";

$where = array();
//디폴트 검색조건
// $where[] = " mtr_status = 'ok' ";
// $where[] = " mtr_type IN ('half','material','goods') ";
$where[] = " bom_type IN ('half','material','goods') ";
$where[] = " bom_status = 'ok' ";
$where[] = " bom.com_idx = '{$_SESSION['ss_com_idx']}' ";

//검색어 설정
if($stx != '') {
    switch($sfl){
        case ($sfl == 'bom_part_no' || $sfl == 'bom_idx'):
            $where[] = " {$sfl} = '".trim($stx)."' ";
            break;
        default:
            $where[] = " {$sfl} LIKE '%".trim($stx)."%' ";
            break;
    }
}


if($ser_bom_type){
    $where[] = " bom_type = '{$ser_bom_type}' ";
}

//최종 WHERE 분리정리
if($where)
    $sql_search = " WHERE ".implode(' AND',$where);

if(!$sst) {
    $sst = "bom.bom_idx";
    $sod = "DESC";
}

$sql_group = " GROUP BY bom.bom_idx ";
$sql_order = " ORDER BY {$sst} {$sod} ";

$basic_sql = " SELECT count(*) AS cnt {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$sql = " SELECT COUNT(*) AS cnt FROM ({$basic_sql}) q  ";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];
//print_r3($sql).'<br>';

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$sql = " SELECT bom.bom_idx
            ,bom.bom_name
            ,bom.bom_part_no
            ,bom.bom_price
            ,bom.bom_type
            ,SUM(CASE WHEN mtr.mtr_status = 'ok' THEN mtr.mtr_value ELSE 0 END) AS mtr_sum
        {$sql_common} {$sql_search} {$sql_group} {$sql_having} {$sql_order}
        limit {$from_record}, {$rows}
";
// print_r3($sql);
$result = sql_query($sql);

$colspan = 9;

$qstr .= '&ser_bom_type='.$ser_bom_type;
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총 </span><span class="ov_num"> <?php echo number_format($total_count) ?>건 </span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="bom.bom_part_no"<?php echo get_selected($_GET['sfl'], "bom_part_no"); ?>>품번코드</option>
        <option value="bom_name"<?php echo get_selected($_GET['sfl'], "bom_name"); ?>>품명</option>
        <option value="bom.bom_idx"<?php echo get_selected($_GET['sfl'], "bom_idx"); ?>>품번</option>
    </select>
    <script>
        $('#cst_idx_provider').val('<?=$cst_idx_provider?>');
    </script>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <select name="ser_bom_type" id="bom_type">
        <option value="">::유형선택::</option>
        <?=$g5['set_bom_type_options']?>
    </select>
    <script>
        $('#bom_type').find('option[value="product"]').remove(); //완제품은 대상이 아니니 제거한다.
        $('#bom_type').val('<?=$ser_bom_type?>');
    </script>
    <input type="submit" class="btn_submit" value="검색">
</form>

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>
        각 제품별 재고현황과 재고를 발주제품과는 상관없이 등록하는 페이지 입니다.
    </p>
</div>
<form name="form01" id="form01" action="./material_input_list_update.php" onsubmit="return form01_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="qstr" value="<?php echo $qstr ?>">
<input type="hidden" name="ser_bom_type" value="<?php echo $ser_bom_type ?>">

<div class="tbl_head01 tbl_wrap">
<table>
<caption><?php echo $g5['title']; ?> 목록</caption>
<thead>
<tr>
    <th scope="col" id="moi_list_chk">
        <label for="chkall" class="sound_only">전체</label>
        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
    </th>
    <th scope="col">제품ID</th>
    <!-- <th scope="col">공급업체</th> -->
    <!-- <th scope="col">차종</th> -->
    <th scope="col">품명</th>
    <th scope="col">품번</th>
    <th scope="col">유형</th>
    <th scope="col">수량</th>
    <th scope="col">현재고량</th>
</tr>
</thead><!--//thead-->
<tbody>
<?php
for($i=0;$row=sql_fetch_array($result);$i++){
    $bg = 'bg'.($i%2);
?>
<tr class="<?=$bg?>">
    <td class="td_chk">
        <label for="chk_<?=$i?>" class="sound_only"><?php echo get_text($row['bom_idx']); ?></label>
        <input type="checkbox" name="chk[]" value="<?=$row['bom_idx']?>" id="chk_<?=$i?>">
        <div class="chkdiv_btn" chk_no="<?=$i?>"></div>

        <input type="hidden" name="bom_idx[<?=$row['bom_idx']?>]" value="<?=$row['bom_idx']?>">
        <input type="hidden" name="bom_name[<?=$row['bom_idx']?>]" value="<?=$row['bom_name']?>">
        <input type="hidden" name="bom_part_no[<?=$row['bom_idx']?>]" value="<?=$row['bom_part_no']?>">
        <input type="hidden" name="bom_type[<?=$row['bom_idx']?>]" value="<?=$row['bom_type']?>">
        <!-- <input type="hidden" name="cst_idx_provider[<?=$row['bom_idx']?>]" value="<?=$row['cst_idx_provider']?>"> -->
        <input type="hidden" name="cst_idx_customer[<?=$row['bom_idx']?>]" value="<?=$row['cst_idx_customer']?>">
        <input type="hidden" name="bom_price[<?=$row['bom_idx']?>]" value="<?=$row['bom_price']?>">
    </td><!--체크박스-->
    <td class="td_bom_idx"><?=$row['bom_idx']?></td>
    <!-- <td class="td_cst_name"><?php //$g5['provider_key_val'][$row['cst_idx_provider']]?></td> -->
    <!-- <td class="td_bct_idx"><?php //$g5['cats_key_val'][$row['bct_idx']]?></td> -->
    <td class="td_bom_name"><?=$row['bom_name']?></td>
    <td class="td_bom_part_no"><?=$row['bom_part_no']?></td>
    <td class="td_bom_type"><?=$g5['set_bom_type_value'][$row['bom_type']]?></td>
    <td class="td_input_cnt">
        <input type="text" name="input_cnt[<?=$row['bom_idx']?>]" onclick="javascript:numtoprice(this)" class="frm_input input_cnt wg_wdx60 wg_right">
    </td>
    <td class="td_mtr_sum"><?=$row['mtr_sum']?></td>
</tr>
<?php }
if ($i == 0)
    echo "<tr><td colspan='".$colspan."' class=\"empty_table\">자료가 없습니다.</td></tr>";
?>
</tbody><!--//tbody-->
</table>
</div><!--//.tbl_wrap-->

<div class="btn_fixed_top">
    <?php if (!auth_check($auth[$sub_menu],'w')) { ?>
    <input type="submit" name="act_button" value="선택재고입고" onclick="document.pressed=this.value" class="btn wg_btn_success">
    <input type="submit" name="act_button" value="선택재고차감" onclick="document.pressed=this.value" class="btn wg_btn_danger">
    <?php } ?>
</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?schrows='.$schrows.'&'.$qstr.'&amp;page='); ?>

<script>

function form01_submit(f){
    if(!is_checked("chk[]")) {
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
include_once('./_tail.php');