<?php
$sub_menu = "940130";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " FROM {$g5['plc_protocol_table']} AS ppr
                LEFT JOIN {$g5['mms_table']} AS mms ON mms.mms_idx = ppr.mms_idx
                LEFT JOIN {$g5['code_table']} AS cod ON cod.cod_idx = ppr.cod_idx
";

$where = array();
$where[] = " (1) ";   // 디폴트 검색조건

// 해당 업체만
// $where[] = " ppr.com_idx = '".$_SESSION['ss_com_idx']."' ";


// 검색어 설정
if ($stx != "") {
    switch ($sfl) {
		case ( $sfl == 'ppr_idx' || $sfl == 'ppr.mms_idx' || $sfl == 'ppr.bom_idx' ) :
			$where[] = " {$sfl} = '".trim($stx)."' ";
            break;
		case ( $sfl == 'ppr_hp' ) :
			$where[] = " $sfl LIKE '%".trim($stx)."%' ";
            break;
        default :
			$where[] = " $sfl LIKE '%".trim($stx)."%' ";
            break;
    }
}


// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


if (!$sst) {
    $sst = "ppr_reg_dt";
    $sod = "DESC";
}

$sql_order = " ORDER BY {$sst} {$sod} ";

$sql = " SELECT COUNT(*) AS cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];
//print_r3($sql).'<br>';

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = 'PLC통신규약';
include_once('./_top_menu_mms.php');
include_once('./_head.php');
echo $g5['container_sub_title'];

$sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} LIMIT {$from_record}, {$rows} ";
// print_r3($sql);
$result = sql_query($sql);

$colspan = 16;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총 </span><span class="ov_num"> <?php echo number_format($total_count) ?>건 </span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="ppr_name"<?php echo get_selected($_GET['sfl'], "ppr_name"); ?>>태그명</option>
    <option value="fct_name"<?php echo get_selected($_GET['sfl'], "fct_name"); ?>>설비명</option>
    <option value="ppr.mms_idx"<?php echo get_selected($_GET['sfl'], "ppr.mms_idx"); ?>>설비번호</option>
    <option value="ppr_ip"<?php echo get_selected($_GET['sfl'], "ppr_ip"); ?>>아이피</option>
    <option value="ppr_port"<?php echo get_selected($_GET['sfl'], "ppr_port"); ?>>포트</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>

<div class="local_desc01 local_desc" style="display:none;">
    <p>새로운 고객을 등록</p>
</div>


<form name="fmemberlist" id="fmemberlist" action="./plc_protocol_list_update.php" onsubmit="return form01_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="w" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="ppr_list_chk">
            <label for="chkall" class="sound_only">항목 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">고유번호</th>
        <th scope="col">설비명</th>
        <th scope="col">타입</th>
        <th scope="col">태그명</th>
        <th scope="col"><?php echo subject_sort_link('ppr_ip') ?>PLC IP</a></th>
        <th scope="col"><?php echo subject_sort_link('ppr_port_no') ?>PLC Port</a></th>
        <th scope="col"><?php echo subject_sort_link('ppr_no') ?>PLC No</a></th>
        <th scope="col"><?php echo subject_sort_link('ppr_bit') ?>Bit No</a></th>
        <th scope="col"><?php echo subject_sort_link('ppr_reg_dt') ?>등록일</a></th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $s_mod = '<a href="./plc_protocol_form.php?'.$qstr.'&amp;w=u&amp;ppr_idx='.$row['ppr_idx'].'" class="btn btn_03">수정</a>';

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>" tr_id="<?php echo $row['ppr_idx'] ?>">
        <td class="td_chk">
            <input type="hidden" name="ppr_idx[<?php echo $i ?>]" value="<?php echo $row['ppr_idx'] ?>" id="ppr_idx_<?php echo $i ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_ppr_idx"><?=$row['ppr_idx']?></td>
        <td class="td_mms_name"><?php echo get_text($row['mms_name']); ?></td>
        <td class="td_ppr_data_type"><?=$row['ppr_data_type']?></td>
        <td class="td_ppr_name"><?=$row['ppr_name']?></td>
        <td class="td_ppr_ip"><a href="?sfl=ppr_ip&stx=<?=$row['ppr_ip']?>"><?=$row['ppr_ip']?></a></td>
        <td class="td_ppr_port"><a href="?sfl=ppr_port&stx=<?=$row['ppr_port_no']?>"><?=$row['ppr_port_no']?></a></td>
        <td class="td_ppr_no"><?=$row['ppr_no']?></td>
        <td class="td_ppr_bit"><?=$row['ppr_bit']?></td>
        <td class="td_ppr_reg_dt"><?=$row['ppr_reg_dt']?></td>
        <td class="td_mng td_mng_s">
			<?php echo $s_mod ?><!-- 수정 -->
		</td>
    </tr>

        <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02" style="display:none;">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php if (!auth_check($auth[$sub_menu],'w',1)) { ?>
        <a href="./plc_protocol_form.php" id="member_add" class="btn btn_01">추가하기</a>
    <?php } ?>

</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function form01_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	if(document.pressed == "선택수정") {
		$('input[name="w"]').val('u');
	}
	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 항목(들)을 정말 삭제 하시겠습니까?\n복구가 어려우니 신중하게 결정 하십시오.")) {
			return false;
		}
		else {
			$('input[name="w"]').val('d');
		} 
	}
    return true;
}
</script>

<?php
include_once ('./_tail.php');
?>
