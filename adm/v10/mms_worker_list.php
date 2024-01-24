<?php
$sub_menu = "940130";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '설비별작업자관리';
include_once('./_top_menu_mms.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
exit;
$sql_common = " FROM {$g5['bom_mms_worker_table']} AS bmw
                LEFT JOIN {$g5['bom_table']} AS bom ON bom.bom_idx = bmw.bom_idx
                LEFT JOIN {$g5['member_table']} AS mb ON mb.mb_id = bmw.mb_id
";
// $sql_common = " FROM {$g5['bom_mms_worker_table']} AS bmw ";

$where = array();
$where[] = " bmw_status NOT IN ('trash','delete') ";   // 디폴트 검색조건

// 검색어 설정
if ($stx != "") {
    switch ($sfl) {
		case ( $sfl == 'bmw_hp' ) :
			$where[] = " $sfl LIKE '%".trim($stx)."%' ";
            break;
        default :
			$where[] = " $sfl LIKE '%".trim($stx)."%' ";
            break;
    }
}
// 검색어 설정
if ($stx2 != "") {
    switch ($sfl2) {
		case ( $sfl2 == 'bmw.bom_idx' || $sfl2 == 'bom_part_no' ) :
			$where[] = " {$sfl2} = '".trim($stx2)."' ";
            break;
    }
}

// 설비번호 검색
if ($ser_mms_idx) {
    $where[] = " bmw.mms_idx = '".$ser_mms_idx."' ";
}
    

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


if (!$sst) {
    $sst = "bmw_reg_dt";
    $sod = "DESC";
}

$sql_order = " ORDER BY {$sst} {$sod} ";

$sql = " SELECT COUNT(*) AS cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];
// echo $sql.BR;

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} LIMIT {$from_record}, {$rows} ";
// echo $sql.BR;
$result = sql_query($sql);

$qstr .= '&sfl2='.$sfl2.'&stx2='.$stx2.'&ser_mms_idx='.$ser_mms_idx; // 추가로 확장해서 넘겨야 할 변수들

$colspan = 16;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총 </span><span class="ov_num"> <?php echo number_format($total_count) ?>건 </span></span>
</div>

<?php
include_once('./mbw_form.php');
?>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="ser_mms_idx" id="ser_mms_idx">
    <option value="">전체설비</option>
    <?php
    // 해당 범위 안의 모든 설비를 select option으로 만들어서 선택할 수 있도록 한다.
    // Get all the mms_idx values to make them optionf for selection.
    $sql2 = "SELECT mms_idx, mms_name
            FROM {$g5['mms_table']}
            WHERE com_idx = '".$_SESSION['ss_com_idx']."'
            ORDER BY mms_name
    ";
    // echo $sql2.'<br>';
    $result2 = sql_query($sql2,1);
    for ($i=0; $row2=sql_fetch_array($result2); $i++) {
        // print_r2($row2);
        echo '<option value="'.$row2['mms_idx'].'" '.get_selected($ser_mms_idx, $row2['mms_idx']).'>'.$row2['mms_name'].'</option>';
    }
    ?>
</select>
<script>$('select[name=ser_mms_idx]').val("<?=$ser_mms_idx?>").attr('selected','selected');</script>
<select name="sfl" id="sfl">
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>작업자이름</option>
    <option value="bmw.mb_id"<?php echo get_selected($_GET['sfl'], "bmw.mb_id"); ?>>작업자아이디</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<select name="sfl2" id="sfl2">
    <option value="bom_part_no"<?php echo get_selected($_GET['sfl2'], "bom_part_no"); ?>>품번</option>
    <option value="bom_name"<?php echo get_selected($_GET['sfl2'], "bom_name"); ?>>품명</option>
</select>
<label for="stx2" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx2" value="<?php echo $stx2 ?>" id="stx2" class="frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>설비별로 제품 생산 시 작업자 할당이 되어 있지 않으면 정상적인 생산량 관리를 할 수 없습니다.</p>
    <p>설비별로 변동사항이 발생하면 (제품이 추가되거나 작업자 입퇴사 시) 기존 설정을 변경하거나 추가해 주셔야 합니다.</p>
    <p>최초 전달 내용은 공유 엑셀을 참고하세요. <a href="https://docs.google.com/spreadsheets/d/1L5akSn_9n6VA3RuK9pUJs4dSq8ELs7kHbXOzWUrs59U/edit?usp=sharing" target="_blank">바로가기</a></p>
</div>


<form name="fmemberlist" id="fmemberlist" action="./mms_worker_list_update.php" onsubmit="return form01_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sfl2" value="<?php echo $sfl2 ?>">
<input type="hidden" name="stx2" value="<?php echo $stx2 ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="w" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="bmw_list_chk">
            <label for="chkall" class="sound_only">항목 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('mms_idx',$qstr) ?>설비명</a></th>
        <th scope="col">메인여부</th>
        <th scope="col">제품ID</th>
        <th scope="col">품번</th>
        <th scope="col">품명</th>
        <th scope="col"><?php echo subject_sort_link('mb_name',$qstr) ?>이름</a></th>
        <th scope="col"><?php echo subject_sort_link('bmw_type',$qstr) ?>타입</a></th>
        <th scope="col">휴대폰</th>
        <th scope="col"><?php echo subject_sort_link('bmw_sort',$qstr) ?>순번</a></th>
        <th scope="col">테스트여부</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // print_r2($row);
        $row['mms'] = get_table('mms','mms_idx',$row['mms_idx']);
        $row['bom'] = get_table('bom','bom_idx',$row['bom_idx']);
        $row['mb'] = get_table('member','mb_id',$row['mb_id']);

        $s_mod = '<a href="./mms_worker_form.php?'.$qstr.'&amp;w=u&amp;bmw_idx='.$row['bmw_idx'].'" class="btn btn_03">수정</a>';

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>" tr_id="<?php echo $row['bmw_idx'] ?>">
        <td class="td_chk">
            <input type="hidden" name="bmw_idx[<?php echo $i ?>]" value="<?php echo $row['bmw_idx'] ?>" id="bmw_idx_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['bmw_nick']); ?>님</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_mms_name"><a href="?sfl=bmw.mms_idx&stx=<?=$row['mms_idx']?>"><?=get_text($row['mms']['mms_name'])?></a> <span class="font_size_7"><?=$row['mms_idx']?></span></td>
        <td class="td_bmw_main_yn font_size_7"><?=$row['bmw_main_yn']?></td>
        <td class="td_bom_idx font_size_7"><?=$row['bom']['bom_idx']?></td>
        <td class="td_bom_part_no font_size_7"><?=$row['bom']['bom_part_no']?></td>
        <td class="td_bom_name font_size_7"><?=$row['bom']['bom_name']?></td>
        <td class="td_mb_name"><a href="?sfl=bmw.mb_id&stx=<?=$row['mb_id']?>"><?=get_text($row['mb']['mb_name'])?></a></td>
        <td class="td_bmw_type"><?php echo $g5['set_bmw_type_value'][$row['bmw_type']] ?></td>
        <td class="td_mb_hp"><?=get_text($row['mb']['mb_hp'])?></td>
        <td class="td_bmw_sort"><?php echo $row['bmw_sort']; ?></td>
        <td class="td_bmw_test_yn"<?=(($row['bmw_test_yn'])?' style="color:orange;"':'')?>><?=(($row['bmw_test_yn'])?'테스트':'아니오')?></td>
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
        <a href="./mms_worker_form.php" id="member_add" class="btn btn_01">추가하기</a>
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
