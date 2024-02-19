<?php
$sub_menu = "918110";
include_once('./_common.php');


auth_check($auth[$sub_menu],'w');


// 예전 엑셀 파일 삭제!!

// Define a function to compare files by modification time
function compare_by_mtime($file1, $file2) {
    $time1 = filemtime($file1);
    $time2 = filemtime($file2);
    if ($time1 == $time2) {
      return 0;
    }
    return ($time1 > $time2) ? -1 : 1;
}
  
// Get the files in the images folder
$dir = '/data/excels/order';
$files = glob(G5_PATH.$dir."/*");

// Sort the files by modification time in descending order
usort($files, "compare_by_mtime");

// Get the first 10 files
$latest_files = array_slice($files, 0, 10);


// Delete the files that are older than today
// $threshold = strtotime('today');
// foreach ($files as $file) {
//   if ($threshold >= filemtime($file)) {
//     unlink($file);
//   }
// }

// Delete the files that are not in the last 10 files
$last_files = array_slice($files, -10);
foreach ($files as $file) {
  if (!in_array($file, $last_files)) {
    unlink($file);
  }
}

$g5['title'] = '수주정보 엑셀등록';
include_once('./_top_menu_order.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>표준 엑셀 양식과 동일한 구조의 엑셀로 등록하셔야 합니다. 표준엑셀양식 <a href="https://docs.google.com/spreadsheets/d/1s-xHUx_nGNdLAJOQ9JpD_RuHUfxBSSktsksHOjLY4do/edit?usp=sharing" target="_blank">바로가기</a></p>
</div>

<div class="tbl_frm01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4" style="width:15%;">
		<col style="width:85%;">
    </colgroup>
	<tbody>
	<tr>
        <th scope="row">수주일자</th>
        <td>
            <?=help('수주일자를 반드시 설정해 주세요.')?>
            <input type="text" name="prd_date" value="<?=G5_TIME_YMD?>" readonly class="frm_input readonly" style="width:90px;">
        </td>
    </tr>
	<tr>
        <th scope="row">엑셀파일</th>
        <td>
            <?=help('엑셀은 표준 양식으로 등록해 주셔야 합니다.')?>
            <input type="file" name="file_excel_k1" class="frm_input">
        </td>
    </tr>
	<tr>
        <th scope="row">파일업로드기록</th>
        <td>
            <?php
            foreach ($latest_files as $file) {
                echo $file.BR;
            }
            ?> 
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
$(function() {
    $("input[name=prd_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});

});

function form01_submit(f) {
    if(!f.ori_date.value){
        alert('수주일을 반드시 지정해 주세요');
        f.ori_date.focus();
        return false;
    }
    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
