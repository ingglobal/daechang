<?php
// 호출페이지들
// /adm/v10/bom_structure_form.php: 오른편에 나타남
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 메뉴입니다.');



$sql_common = " FROM {$g5['production_table']} prd
                    LEFT JOIN {$g5['bom_table']} bom ON prd.bom_idx = bom.bom_idx
";


$where = array();
$where[] = " prd.boc_idx = '{$boc_idx}' ";
$where[] = " prd.com_idx = '".$_SESSION['ss_com_idx']."' ";
$where[] = " prd_status NOT IN ('trash','delete') ";


// 검색어 설정
if ($stx != "") {
    switch ($sfl) {
		case ( $sfl == 'prd_date' ) :
			$where[] = " {$sfl} = '".trim($stx)."' ";
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
    $sst = "prd_date";
    $sod = "desc";
}

if (!$sst2) {
    $sst2 = ", prd.bom_idx";
    $sod2 = "desc";
}



?>
<style>
.scp_frame {padding:10px;}
.sp_cat{color:orange;font-size:0.85em;}
.sp_std{color:#e87eee;font-size:0.85em;}
.new_frame_con {margin-top:10px;height:484px;overflow-y:auto;padding-bottom:25px;}
.td_ord_idx
 {width:20%;}
.td_ord_date {width:70%;}
.td_mng{width:10%;}
</style>

<div id="sch_target_frm" class="new_win scp_frame">

    <form name="ftarget" method="get">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sst2" value="<?php echo $sst2 ?>">
    <input type="hidden" name="sod2" value="<?php echo $sod2 ?>">
    <input type="hidden" name="frm" value="<?php echo $_GET['frm']; ?>">
    <input type="hidden" name="file_name" value="<?php echo $_REQUEST['file_name']; ?>">
    <input type="hidden" name="boc_idx" value="<?php echo $_REQUEST['boc_idx']; ?>">
    <input type="hidden" name="com_idx" value="<?php echo $_REQUEST['com_idx']; ?>">

    <div id="div_search">
        <select name="sfl" id="sfl">
            <option value="prd_date"<?php echo get_selected($_GET['sfl'], "ori_date"); ?>>수주일</option>
            <option value="prd_idx"<?php echo get_selected($_GET['sfl'], "ori_idx"); ?>>수주ID</option>
        </select>
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input" style="width:160px;">
        <input type="submit" value="검색" class="btn_frmline">
        <a href="<?php echo $_SERVER['SCRIPT_NAME']?>?file_name=<?=$file_name?>" class="btn btn_b10">취소</a>
    </div>
    
    <div class="tbl_head01 tbl_wrap new_frame_con">
        <table>
        <caption>검색결과</caption>
        <thead>
        <tr>
            <th scope="col"><?php echo subject_sort_link('ord_idx') ?>수주ID</a></th>
            <th scope="col">품명</th>
            <th scope="col">품번</th>
            <th scope="col">수주량</th>
            <th scope="col">수주일</th>
            <th scope="col">생산시작일</th>
            <th scope="col">생산완료일</th>
            <th scope="col">선택</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            $bg = 'bg'.($i%2);
        ?>
        <tr class="<?php echo $bg; ?>" tr_id="<?php echo $row['bom_idx'] ?>">
            <td class="td_prd_idx"><?=$row['prd_idx']?></td>
            <td class="td_bom_name"><?=$row['bom_name']?></td>
            <td class="td_bom_part_no"><?=$row['bom_part_no']?></td>
            <td class="td_prd_value"><?=number_format($row['prd_value'])?></td>
            <td class="td_prd_date"><?=$row['prd_date']?></td>
            <td class="td_prd_start_date"><?=$row['prd_start_date']?></td>
            <td class="td_prd_done_date"><?=$row['prd_done_date']?></td>
            <td class="td_mng td_mng_s">
                <button type="button" class="btn btn_03 btn_select"
                    prd_idx="<?=$row['prd_idx']?>"
                    prd_date="<?=$row['prd_date']?>"
                    prd_value="<?=number_format($row['ori_count'])?>"
                    prd_start_date="<?=$row['prd_start_date']?>"
                    prd_done_date="<?=$row['prd_done_date']?>"
                >선택</button>
            </td>
        </tr>
        <?php
        }
        if($i ==0)
            echo '<tr><td colspan="6" class="empty_table">검색된 자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>
    </form>

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

</div>

<script>
var i = <?=$i?>;
if(i == 0){
    alert('해당 제품의 수주데이터가 없습니다.\n수주데이터를 먼저 등록해 주세요.');
    window.close();
}
$('.btn_select').click(function(e){
    e.preventDefault();
    var prd_idx = $(this).attr('prd_idx');
    var prd_date = $(this).attr('prd_date');
    var prd_value = $(this).attr('prd_value');
    
    <?php
    // BOM 구성
    if($file_name=='production_form') {
    ?>
        $("input[name=prd_idx]", opener.document).val( prd_idx );
        $("input[name=prd_date]", opener.document).val( prd_date );
    <?php
    }
    ?>

    window.close();
});
</script>

<?php
include_once('./_tail.sub.php');
?>