<?php
// 호출페이지들
// /adm/v10/plc_protocol_form.php
// /adm/v10/maintain_form.php
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 메뉴입니다.');

$sql_common = " FROM {$g5['code_table']} AS cod
                    LEFT JOIN {$g5['mms_table']} AS mms ON mms.mms_idx = cod.mms_idx ";

$where = array();
// 디폴트 검색조건
$where[] = " cod_status NOT IN ('trash','delete') ";

// 업체조건 (관리 권한이 있는 경우)
if($member['mb_manager_yn']) {
    $com_idx = $_REQUEST['com_idx'] ?: $_SESSION['ss_com_idx'];
    $where[] = " cod.com_idx = '".$com_idx."' ";
}
else {
    $where[] = " cod.com_idx = '".$member['com_idx']."' ";
}

// 검색어 설정
if ($sch_word != "") {
    switch ($sch_field) {
		case ( $sch_field == 'cod_type' ) :
			$where[] = " cod_keys REGEXP 'cod_type=[가-힝]*(".trim($sch_word).")+[가-힝]*:' ";
            break;
		case ( $sch_field == 'mms_idx' ) :
			$where[] = " cod.mms_idx = '".trim($sch_word)."' ";
            break;
        default :
			$where[] = " $sch_field LIKE '%".trim($sch_word)."%' ";
            break;
    }
}
else
    $sch_field = 'cod_name';

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


// 정렬기준
$sql_order = " ORDER BY cod_idx ";


// 테이블의 전체 레코드수
$sql = " SELECT COUNT(*) AS cnt " . $sql_common . $sql_search;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$config['cf_write_pages'] = $config['cf_mobile_pages'] = 5;

// 리스트 쿼리
$sql = "SELECT *
        " . $sql_common . $sql_search . $sql_order . "
        LIMIT $from_record, $rows
";
// echo $sql;
$result = sql_query($sql,1);

$qstr = 'frm='.$frm.'&file_name='.$file_name.'&mms_idx='.$mms_idx;
$qstr1 = $qstr.'&sch_field='.$sch_field.'&sch_word='.urlencode($sch_word);

$g5['title'] = '알람 ('.number_format($total_count).')';
include_once('./_head.sub.php');
?>

<div id="sch_target_frm" class="new_win scp_new_win">
    <h1><?php echo $g5['title'];?></h1>

    <form name="ftarget" method="get">
    <input type="hidden" name="frm" value="<?php echo $_GET['frm']; ?>">
    <input type="hidden" name="file_name" value="<?php echo $_REQUEST['file_name']; ?>">
    <input type="hidden" name="com_idx" value="<?php echo $_REQUEST['com_idx']; ?>">

    <div id="scp_list_find">
        <select name="sch_field" id="sch_field">
            <option value="cod_name">설비명</option>
            <option value="cod_code">알람코드</option>
            <option value="cod_name">알람명</option>
        </select>
        <script>$('select[name=sch_field]').val('<?php echo $sch_field?>').attr('selected','selected')</script>
        <input type="text" name="sch_word" id="sch_word" value="<?php echo get_text($sch_word); ?>" class="frm_input required" required size="20">
        <input type="submit" value="검색" class="btn_frmline">
        <a href="<?php echo $_SERVER['SCRIPT_NAME']?>?<?php echo $qstr?>" class="btn btn_b10">검색취소</a>
    </div>
    
    <div class="tbl_head01 tbl_wrap new_win_con">
        <table>
        <caption>검색결과</caption>
        <thead>
        <tr>
            <th scope="col">설비명</th>
            <th scope="col">알람코드</th>
            <th scope="col">알람명</th>
            <th scope="col">선택</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $row['mta'] = get_meta('cod',$row['cod_idx']);
            //print_r2($row['mta']);
        ?>
        <tr>
            <td class="td_mms_name"><?php echo $row['mms_name']; ?></td>
            <td class="td_cod_code"><?php echo $row['cod_code']; ?></td>
            <td class="td_cod_name"><?php echo $row['cod_name']; ?></td>
            <td class="td_mng td_mng_s" cod_idx="<?php echo $row['cod_idx']; ?>"
                                        cod_code="<?php echo $row['cod_code']; ?>"
                                        cod_name="<?php echo $row['cod_name']; ?>"
                                        mms_idx="<?php echo $row['mms_idx']; ?>"
                                        mms_name="<?php echo $row['mms_name']; ?>">
                <button type="button" class="btn btn_03 btn_select">선택</button>
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

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr1.'&amp;page='); ?>

    <div class="win_btn ">
        <button type="button" onclick="window.close();" class="btn btn_close">창닫기</button>
    </div>

</div>

<script>
// 업체검색
$("#btn_company").click(function() {
    var href = $(this).attr("href");
    winCompany = window.open(href, "winCompany", "left=70,top=70,width=520,height=600,scrollbars=1");
    winCompany.focus();
    return false;
});

$('.btn_select').click(function(e){
    e.preventDefault();
    var cod_idx = $(this).closest('td').attr('cod_idx');
    var cod_code = $(this).closest('td').attr('cod_code');  // 
    var cod_name = $(this).closest('td').attr('cod_name');  // 
    var mms_idx = $(this).closest('td').attr('mms_idx');
    var mms_name = $(this).closest('td').attr('mms_name');  // 

    <?php
    if($file_name=='plc_protocol_form'
        ||$file_name=='plc_protocol_form'
        ||$file_name=='plc_protocol_form'
    ) {
    ?>
        $("input[name=cod_idx]", opener.document).val( cod_idx );
        $("input[name=cod_code]", opener.document).val( cod_code );
        $("input[name=cod_name]", opener.document).val( cod_name );
    <?php
    }
    else if($file_name=='manual_downtime_form') {
    ?>
        $("input[name=imp_idx]", opener.document).val( imp_idx );
        $("input[name=cod_idx]", opener.document).val( cod_idx );
        $("#cod_name", opener.document).val( cod_name );
        $("input[name=fcg_idx]", opener.document).val( fcg_idx );
        opener.select_set();
    <?php
    }
    else if($file_name=='maintain_form') {
    ?>
        $("input[name=mnt_db_idx]", opener.document).val( cod_idx );
        $("input[name=cod_name]", opener.document).val( cod_name );
    <?php
    }
    ?>

    window.close();
});
</script>

<?php
include_once('./_tail.sub.php');
?>