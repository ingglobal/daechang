<?php
$sub_menu = "922145";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');

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
$dir = '/data/excels/quality';
$files = glob(G5_PATH.$dir."/*");

// Sort the files by modification time in descending order
usort($files, "compare_by_mtime");

// Get the first 10 files
$latest_files = array_slice($files, 0, 10);

// Delete the files that are not in the last 10 files
$last_files = array_slice($files, -30);
foreach ($files as $file) {
    if (!in_array($file, $latest_files)) {
        unlink($file);
    }
}


$g5['title'] = '불량엑셀등록';
include_once('./_top_menu_quality.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">
<input type="hidden" name="excel_type" value="01">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>엑셀로 파일을 관리할 때는 <span style="color:darkorange;">표준엑셀 양식</span>을 지켜주셔야 합니다.</p>
    <p>구조가 복잡한 엑셀파일은 등록할 수 없습니다. (ex. <span style="color:darkorange;">복잡한 수식, 외부엑셀 파일 연결 참조, 하단 여러탭...</span>)</p>
    <p>엑셀파일에서 첫번째 탭만 사용됩니다. 업로드 시간이 많이 걸린다면 파일 내부 처리가 복잡한 문서이므로 등록할 수 없는 문서입니다.</p>
    <p>표준 양식과 다른 구조의 엑셀 파일을 사용하여 문제가 생기면 복구가 불가능합니다. 반드시 주의해 주세요.</p>
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
        <th scope="row">표준엑셀</th>
        <td>
            <a href="https://docs.google.com/spreadsheets/d/1g0L8N5-pUcj8KZ30bDeFZ1JHi--QpCt-mZFwmTnp36g/edit?usp=sharing" target="_blank">1. 23년_공정 품질문제 관리현황(08월)</a>
        </td>
    </tr>
	<tr>
        <th scope="row">엑셀 파일</th>
        <td>
            <input type="file" name="file_excel" class="frm_input">
        </td>
    </tr>
	<tr>
        <th scope="row">적용 년월</th>
        <td>
            <input type="text" name="ym" class="frm_input" style="width:65px;" value="<?=substr(G5_TIME_YMD,0,7)?>"> 엑셀에서 제공하는 월 정보가 우선합니다. (엑셀에는 년도가 없음)
        </td>
    </tr>
	<tr>
        <th scope="row">파일업로드기록</th>
        <td>
            <?php
            foreach ($latest_files as $file) {
                // echo $file.BR;
                $file_arr = explode("/",$file);
                $file_name = $file_arr[sizeof($file_arr)-1];
                // print_r2($file_arr);
                // echo $file_arr[sizeof($file_arr)-1].BR;
                $file_fullpath = $file;
                $file_name_orig = $file_name;
                echo '<a href="'.G5_USER_ADMIN_URL.'/lib/download.php?file_fullpath='.$file_fullpath.'&file_name_orig='.$file_name_orig.'">'.$file_arr[sizeof($file_arr)-1].'</a>'.BR;
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

});

function form01_submit(f) {

    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
