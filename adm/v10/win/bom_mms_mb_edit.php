<?php
// 호출페이지들
// /adm/v10/bom_structure_form.php: 오른편에 나타남
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 메뉴입니다.');

$bom = get_table('bom','bom_idx',$bom_idx);

$sql_common = " FROM {$g5['bom_mms_worker_table']} bmw
                LEFT JOIN {$g5['bom_table']} bom ON bmw.bom_idx = bom.bom_idx
                LEFT JOIN {$g5['member_table']} mb ON bmw.mb_id = mb.mb_id
                LEFT JOIN {$g5['mms_table']} mms ON bmw.mms_idx = mms.mms_idx
";

$where = array();
$where[] = " bmw_status = 'ok' ";
$where[] = " bmw.bom_idx = '{$bom_idx}' ";

// 검색어 설정
if ($stx != "") {
    switch ($sfl) {
		case ( $sfl == 'mms_serial_no' || $sfl == 'mb_8' ) :
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
    $sst = "mms.mms_sort";
    $sod = "";
}
if (!$sst2) {
    $sst2 = ", bmw_type";
    $sod2 = "";
}


$sql_order = " ORDER BY {$sst} {$sod} {$sst2} {$sod2} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
// $rows = 20;//10
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "SELECT *
        {$sql_common} {$sql_search} {$sql_order}
        LIMIT {$from_record}, {$rows}
";
// print_r2($sql);
$result = sql_query($sql,1);

$qstr .= '&sca='.$sca.'&file_name='.$file_name.'&bom_idx='.$bom_idx; // 추가로 확장해서 넘겨야 할 변수들

$g5['title'] = '[ '.$bom['bom_part_no'].' ]의 설비-작업자 추가/삭제'.(($total_count)?'('.number_format($total_count).')':'');
include_once('./_head.sub.php');
?>
<style>
#hd_h1{padding:10px;}
#top_div{}
#top_div:after{display:block;visibility:hidden;clear:both;content:'';}
#div_reg{padding:10px;float:left;text-align:left;}
form#ftarget{float:right;}
#div_search{padding:10px;}
.tbl_head01 tbody td.td_align_left{text-align:left;}
.btn_del{line-height:26px !important;}
</style>
<h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
<div id="sch_target_frm" class="new_win scp_frame">
<div id="top_div">
<div id="div_reg">
    <select name="mms_idx" id="mms_idx">
        <?=$g5['mms_options_no']?>
    </select>
    <select name="mb_id" id="mb_id">
        <?=$g5['mbw_options_no']?>
    </select>
    <select name="bmw_type" id="bmw_type">
        <?=$g5['set_bmw_type_options']?>
    </select>
    <button class="btn btn_05 btn_reg">추가</button>
</div>
<form name="ftarget" id="ftarget" method="get">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sst2" value="<?php echo $sst2 ?>">
<input type="hidden" name="sod2" value="<?php echo $sod2 ?>">
<input type="hidden" name="frm" value="<?php echo $_GET['frm']; ?>">
<input type="hidden" name="file_name" value="<?php echo $_REQUEST['file_name']; ?>">
<input type="hidden" name="bom_idx" value="<?php echo $_REQUEST['bom_idx']; ?>"> 
    <div id="div_search">
        <select name="sfl" id="sfl">
            <option value="mms_serial_no"<?php echo get_selected($_GET['sfl'], "mms_serial_no"); ?>>설비코드</option>
            <option value="mms_name"<?php echo get_selected($_GET['sfl'], "mms_name"); ?>>설비명</option>
            <option value="mb_8"<?php echo get_selected($_GET['sfl'], "mb_8"); ?>>작업자번호</option>
            <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>작업자명</option>
        </select>
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input" style="width:160px;">
        <input type="submit" value="검색" class="btn_frmline">
        <a href="<?php echo $_SERVER['SCRIPT_NAME']?>?file_name=<?=$file_name?>&bom_idx=<?=$bom_idx?>" class="btn btn_b10">취소</a>
    </div>
</form>
</div> 
<div class="tbl_head01 tbl_wrap new_frame_con">
    <table>
    <caption>검색결과</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('bmw_idx') ?>BMWidx</a></th>
        <th scope="col">제품</th>
        <th scope="col">설비</th>
        <th scope="col">작업자</th>
        <th scope="col">작업유형</th>
        <th scope="col">삭제</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?=$bg?>">
        <td class="td_bmw_idx"><?=$row['bmw_idx']?></td>
        <td class="td_bom td_align_left">
            <span class="sp_bom_part_no">[ <?=$row['bom_part_no']?> ]</span>
            <br><span class="sp_bom_name"><?=$row['bom_name']?></span>
        </td>
        <td class="td_mms td_align_left">
            <span class="sp_mms_serial_no">[ <?=$row['mms_serial_no']?> ]</span>
            <br><span class="sp_mms_name"><?=$row['mms_name']?></span>
        </td>
        <td class="td_mb td_align_left">
            <span class="sp_mb_no">[No: <?=$row['mb_8']?>]</span>
            <br><span class="sp_mb_id">[ID: <?=$row['mb_id']?>]</span>
            <br><span class="sp_mb_name"><?=$row['mb_name']?></span>
        </td>
        <td class="td_bmw_type"><?=$g5['set_bmw_type_value'][$row['bmw_type']]?></td>
        <td class="td_mng td_mng_s">
            <button type="button" bmw_idx="<?=$row['bmw_idx']?>" class="btn btn_04 btn_del">삭제</button>
        </td>
    </tr>
    <?php
    }
    if($i ==0)
        echo '<tr><td colspan="9" class="empty_table">검색된 자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div><!--//.new_frame_con-->
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>
</div><!--//#sch_target_frm-->
<script>
let bom_idx = <?=$bom_idx?>;
let aurl = '<?=G5_USER_ADMIN_AJAX_URL?>/bmw_insert.php';
let durl = '<?=G5_USER_ADMIN_AJAX_URL?>/bmw_del.php';
$('.btn_reg').on('click',function(){
    let mms_idx = $(this).closest('#div_reg').find('#mms_idx').val();
    let mb_id = $(this).closest('#div_reg').find('#mb_id').val();
    let bmw_type = $(this).closest('#div_reg').find('#bmw_type').val();
    // alert(mms_idx+','+mb_id+','+bmw_type);return;
    $.ajax({
        url: aurl,
        data:{ 'bom_idx': bom_idx, 'mms_idx': mms_idx, 'mb_id': mb_id, 'bmw_type': bmw_type },
        dataType:'text',
        success: function (res){
            if(res == 'ok'){
                alert('성공적으로 추가했습니다.');
                // location.reload();
                window.opener.location.reload();
                window.close();
            }
            else {
                alert(res);
            }
        },
        error:function(xre) {
            alert('Status: ' + xre.status + ' \n\rstatusText: ' + xre.statusText + ' \n\rresponseText: ' + xre.responseText);
        }
    });
});
$('.btn_del').on('click',function(){
    let bmw_idx = $(this).attr('bmw_idx');
    $.ajax({
        url: durl,
        data:{ 'bmw_idx': bmw_idx },
        dataType:'text',
        success: function (res){
            alert('성공적으로 삭제했습니다.');
            // location.reload();
            window.opener.location.reload();
            window.close();
        },
        error:function(xre) {
            alert('Status: ' + xre.status + ' \n\rstatusText: ' + xre.statusText + ' \n\rresponseText: ' + xre.responseText);
        }
    });
});
</script>
<?php
include_once('./_tail.sub.php');