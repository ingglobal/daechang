<?php
$sub_menu = "922145";
include_once('./_common.php');

auth_check($auth[$sub_menu],"r");

$g5['title'] = '월간품질관리';
@include_once('./_top_menu_quality.php');
include_once('./_head.php');
echo $g5['container_sub_title'];


// Default month setting
$ym = ($ym) ? $ym : date("Y-m",G5_SERVER_TIME);

// Month prev and next
$ym_prev = ((int)substr($ym,-2) == 1) ? 
				(substr($ym,0,4)-1).'-12' 
				: substr($ym,0,4).'-'.sprintf("%02d",((int)substr($ym,-2)-1));
$ym_next = ((int)substr($ym,-2) == 12) ?
				(substr($ym,0,4)+1).'-01'
				: substr($ym,0,4).'-'.sprintf("%02d",((int)substr($ym,-2)+1));
// echo $ym.BR;


$where = array();
// 디폴트 검색조건 (used 제외)
$where[] = " (1) ";

// 검색어 설정
if ($stx != "") {
    switch ($sfl) {
		case ( $sfl == 'prd.db_idx' || $sfl == 'ori_idx') :
			$where[] = " {$sfl} = '".trim($stx)."' ";
            break;
        default :
			$where[] = " $sfl LIKE '%".trim($stx)."%' ";
            break;
    }
}

// 기간 검색
if ($month)	// 시작일 있는 경우
    $where[] = " stat_date >= '{$month}' ";
if ($ser_en_date)	// 종료일 있는 경우
    $where[] = " stat_date <= '{$ser_en_date}' ";

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);

if (!$sst) {
    $sst = "stat_date DESC, db_idx DESC";
    $sod = "";
}

$sql_order = " ORDER BY {$sst} {$sod} ";

$rows = $config['cf_page_rows'];
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "SELECT SQL_CALC_FOUND_ROWS *
        FROM
        (
            (
            SELECT
                'material' AS bom_type
                , mtr_idx AS db_idx
                , mtr_part_no AS bom_part_no
                , mtr_name AS bom_name
                , mtr_defect_type AS defect_type
                , mtr_defect_text AS defect_text
                , mtr_date AS stat_date
                , mtr_status AS status
                , COUNT(mtr_idx) AS subtotal
            FROM g5_1_material
            WHERE mtr_status = 'defect' AND mtr_date LIKE '".$ym."%'
            GROUP BY mtr_part_no, mtr_date, mtr_defect_type
            )
            UNION ALL
            (
            SELECT
                'product' AS bom_type
                , itm_idx AS db_idx
                , itm_part_no AS bom_part_no
                , itm_name AS bom_name
                , itm_defect_type AS defect_type
                , itm_defect_text AS defect_text
                , itm_date AS stat_date
                , itm_status AS status
                , COUNT(itm_idx) AS subtotal
            FROM g5_1_item
            WHERE itm_status = 'defect' AND itm_date LIKE '".$ym."%'
            GROUP BY itm_part_no, itm_date, itm_defect_type
            )
        ) AS db_table
";
// $sql = "SELECT SQL_CALC_FOUND_ROWS *
//         FROM
//         (
//             (
//             SELECT
//                 'material' AS bom_type
//                 , mtr_idx AS db_idx
//                 , mtr_part_no AS bom_part_no
//                 , mtr_name AS bom_name
//                 , mtr_defect_type AS defect_type
//                 , mtr_defect_text AS defect_text
//                 , mtr_date AS stat_date
//                 , mtr_status AS status
//             FROM {$g5['material_table']} WHERE mtr_status = 'defect'
//             )
//             UNION ALL
//             (
//             SELECT
//                 'product' AS bom_type
//                 , itm_idx AS db_idx
//                 , itm_part_no AS bom_part_no
//                 , itm_name AS bom_name
//                 , itm_defect_type AS defect_type
//                 , itm_defect_text AS defect_text
//                 , itm_date AS stat_date
//                 , itm_status AS status
//             FROM {$g5['item_table']} WHERE itm_status = 'defect'
//             )
//         ) AS db_table
//         {$sql_search}
//         {$sql_order}
// 		LIMIT {$from_record}, {$rows} 
// ";
// print_r3($sql);//exit;
$result = sql_query($sql,1);
$count = sql_fetch_array( sql_query(" SELECT FOUND_ROWS() as total ") ); 
$total_count = $count['total'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
$qstr .= '&sca='.$sca.'&month='.$month.'&ser_en_date='.$ser_en_date; // 추가로 확장해서 넘겨야 할 변수들

?>
<style>
.month_box {display:inline-block;position:relative;margin-right:10px !important;}
.month_box a {padding:5px 10px;vertical-align:middle;}
.month_box a i{font-size:1.3em;}
</style>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총 </span><span class="ov_num"> <?php echo number_format($total_count) ?>건 </span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
    <div class="month_box">
        <a href="?ym=<?=$ym_prev?>"><i class="fa fa-chevron-circle-left icon_prev_next" style="left:5px"></i></a>
        <input type="text" name="ym" value="<?=$ym?>" id="ym" class="frm_input" style="width:70px;text-align:center;">
        <a href="?ym=<?=$ym_next?>"><i class="fa fa-chevron-circle-right icon_prev_next" style="right:5px"></i></a>
    </div>
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="bom_part_no"<?php echo get_selected($_GET['sfl'], "bom_part_no"); ?>>품번</option>
        <option value="bom_name"<?php echo get_selected($_GET['sfl'], "bom_name"); ?>>품명</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" class="btn_submit" value="검색">
</form>

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>완성품, 반제품 모두 리스트에 나타납니다.</p>
</div>

<form name="form01" id="form01" action="./production_list_update.php" onsubmit="return form01_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="st_date" value="<?php echo $st_date ?>">
<input type="hidden" name="en_date" value="<?php echo $en_date ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="orp_list_chk">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">일자</th>
        <th scope="col">품번</th>
        <th scope="col">품명</th>
        <th scope="col">구분</th>
        <th scope="col">불량유형</th>
        <th scope="col">문제점및원인</th>
        <th scope="col">발생수량</th>
    </tr>
    <tr>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // print_r2($row);
        $s_mod = '<a href="./production_form.php?'.$qstr.'&amp;w=u&amp;db_idx='.$row['db_idx'].'" class="btn btn_03">수정</a>';
        $s_copy = '<a href="./order_practice_form.php?'.$qstr.'&w=c&orp_idx='.$row['orp_idx'].'" class="btn btn_03" style="margin-right:5px;">복제</a>';

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>" tr_id="<?php echo $row['db_idx'] ?>">
        <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?=$i?>" id="chk_<?php echo $i ?>">
            <input type="hidden" name="db_idx[<?=$i?>]" value="<?=$row['db_idx']?>">
        </td>
        <td class="td_stat_date"><?=$row['stat_date']?></td><!-- 일자 -->
        <td class="td_bom_part_no"><?=$row['bom_part_no']?></td><!-- 품번 -->
        <td class="td_bom_name font_size_7"><!-- 품명 -->
            <?=$row['bom_name']?>
        </td>
        <td class="td_bom_type"><?=$row['bom_type']?></td><!-- 구분 -->
        <td class="td_defect_type"><?=$g5['set_defect_type_value'][$row['defect_type']]?></td><!-- 불량유형 -->
        <td class="td_defect_text"><?=$row['defect_text']?></td><!-- 문제점및원인 -->
        <td class="td_subtotal"><?=$row['subtotal']?></td><!-- 발생수량 -->
    </tr>
    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan='20' class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <?php if (!auth_check($auth[$sub_menu],'w')) { ?>
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php } ?>
    <a href="./production_form.php" id="member_add" class="btn btn_01" style="display:none;">추가하기</a>
</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
$(function(){
    $("input[name$=_date]").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true,
        yearRange: "c-99:c+99",
        //maxDate: "+0d"
    });
});

    
function form01_submit(f){
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?"))
            return false;
    }
    else if(document.pressed == "선택수정" || document.pressed == "선택문자전송") {
        $('.prd_start_date').each(function(){
            if(!$(this).val()){
                alert('생산시작일을 설정해 주세요');
                $(this).focus();
                return false;
            }
        });
        $('.prd_status').each(function(){
            if(!$(this).val()){
                alert('상태값을 선택해 주세요');
                $(this).focus();
                return false;
            }
        });

        if(document.pressed == "선택문자전송") {
            var status_confirm = true;
            $('.prd_status').each(function(){
                if($(this).val() != 'confirm'){
                    status_confirm = false;
                    alert('상태값이 [확정]일때만 문자를 전송할 수 있습니다.');
                    $(this).focus();
                    return false;
                }
            });
            if(status_confirm == true){
                if(!confirm("선택한 생산계획의 내용으로 각 작업자들에게\n일괄적으로 문자를 보내시겠습니까?"))
                    return false;
            }
            else{
                return status_confirm;
            }
        }
    }

    return true;
}
</script>
<?php
include_once ('./_tail.php');