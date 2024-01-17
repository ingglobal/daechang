<?php
$sub_menu = "922110";
include_once('./_common.php');


auth_check($auth[$sub_menu],'w');


$g5['title'] = '생산계획 엑셀등록';
include_once('./_top_menu_production.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>표준 엑셀 양식과 동일한 구조의 엑셀로 등록하셔야 합니다. 표준엑셀양식 <a href="https://docs.google.com/spreadsheets/d/1BJcwRxXWpiuHuXf_6AjLpjxPb0Erwvl3gs6d8dvJwBY/edit?usp=sharing" target="_blank">바로가기</a></p>
</div>
<?php
// $w_res = sql_fetch(" SELECT GROUP_CONCAT(mb_id) AS mb_ids
//                         , GROUP_CONCAT(mms_idx) AS mms_idxs 
//                     FROM {$g5['bom_mms_worker_table']} 
//                     WHERE bom_idx = 429
//                         AND bmw_type IN('day','night')
//                         AND bmw_status = 'ok'
//                         AND bmw_main_yn = 1
//                     ORDER BY mms_idx, bmw_type
//                     LIMIT 2
// ");
// print_r2($w_res);
// $wks = ($w_res['mb_ids']) ? explode(',',$w_res['mb_ids']) : array();
// $mms = ($w_res['mms_idxs']) ? explode(',',$w_res['mms_idxs']) : array();
// //$g5['set_bmw_type_share_value']['day']
// list($cnt_day,$cnt_night) = day_night_share($cnt,$wks);
// echo ($cnt_day == false)."<br>";
// echo $cnt_night;

?>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4" style="width:15%;">
		<col style="width:85%;">
    </colgroup>
	<tbody>
	<tr>
        <th scope="row">엑셀파일</th>
        <td>
            <?=help('엑셀은 표준 양식으로 등록해 주셔야 합니다.')?>
            <input type="file" name="file_excel_k1" class="frm_input">
        </td>
    </tr>
	</tbody>
	</table>
</div>

<div class="btn_fixed_top">
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>

function form01_submit(f) {
    if(!f.file_excel_k1.value){
        alert('파일을 반드시 등록해 주세요');
        f.file_excel_k1.focus();
        return false;
    }
    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
