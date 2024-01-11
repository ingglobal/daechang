<?php
// 호출페이지들
// /adm/v10/tag_code_form.php
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 메뉴입니다.');

$sql_common = " FROM {$g5['plc_protocol_table']} AS ppr 
                LEFT JOIN {$g5['mms_table']} AS mms USING(mms_idx)
";

$where = array();
// 디폴트 검색조건
$where[] = " ppr_data_type IN ('tag','count','addup') AND ppr_set_time = 0 ";

// 업체조건
$where[] = " ppr.com_idx = '".$_SESSION['ss_com_idx']."' ";

// 설비조건
if($_REQUEST['mms_idx']) {
    $where[] = " ppr.mms_idx = '".$_REQUEST['mms_idx']."' ";
}

// 검색어 설정
if ($sch_word != "") {
    switch ($sch_field) {
		case ( $sch_field == 'ppr_type' ) :
			$where[] = " ppr_keys REGEXP 'ppr_type=[가-힝]*(".trim($sch_word).")+[가-힝]*:' ";
            break;
		case ( $sch_field == 'com_idx' ) :
			$where[] = " mms.com_idx = '".trim($sch_word)."' ";
            break;
        default :
			$where[] = " $sch_field LIKE '%".trim($sch_word)."%' ";
            break;
    }
}
else
    $sch_field = 'ppr_name';

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


// 정렬기준
$sql_order = " ORDER BY mms_idx_parent, fct_management_no ";


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
// echo $sql.BR;
$result = sql_query($sql,1);

$qstr = 'frm='.$frm.'&file_name='.$file_name.'&mms_idx='.$mms_idx;
$qstr1 = $qstr.'&sch_field='.$sch_field.'&sch_word='.urlencode($sch_word);

$g5['title'] = '태그 ('.number_format($total_count).')';
include_once('./_head.sub.php');
?>

<div id="sch_target_frm" class="new_win scp_new_win">
    <h1><?php echo $g5['title'];?></h1>

    <form name="ftarget" method="get">
    <input type="hidden" name="frm" value="<?php echo $_GET['frm']; ?>">
    <input type="hidden" name="file_name" value="<?php echo $_REQUEST['file_name']; ?>">
    <input type="hidden" name="mms_idx" value="<?php echo $_REQUEST['mms_idx']; ?>">

    <div id="scp_list_find">
        <select name="sch_field" id="sch_field">
            <option value="ppr_name">태그명</option>
            <option value="fct_name">설비명</option>
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
            <th scope="col">태그명</th>
            <th scope="col">설비명</th>
            <th scope="col">선택</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $row['fct'] = get_table_e3('facility','mms_idx',$row['mms_idx_parent']);
            $row['fct_name_disp'] = ($row['mms_idx']==$row['mms_idx_parent']) ? $row['fct']['fct_name'] : $row['fct']['fct_name'].'>'.$row['fct_name'];
        ?>
        <tr>
            <td class="td_ppr_name td_left"><?php echo $row['ppr_name']; ?></td>
            <td class="td_fct_name td_left font_size_7"><?php echo $row['fct_name_disp']; ?></td>
            <td class="td_mng td_mng_s" ppr_idx="<?php echo $row['ppr_idx']; ?>"
                                        ppr_name="<?php echo $row['ppr_name']; ?>"
                                        mms_idx="<?php echo $row['mms_idx']; ?>"
                                        fct_name="<?php echo $row['fct_name']; ?>">
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
    var ppr_idx = $(this).closest('td').attr('ppr_idx');
    var ppr_name = $(this).closest('td').attr('ppr_name');  // 
    var mms_idx = $(this).closest('td').attr('mms_idx');
    var fct_name = $(this).closest('td').attr('fct_name');  // 

    <?php
    if($file_name=='tag_code_form'
        ||$file_name=='tag_code_form'
        ||$file_name=='tag_code_form'
    ) {
    ?>
        $("input[name=ppr_idx]", opener.document).val( ppr_idx );
        $("input[name=ppr_name]", opener.document).val( ppr_name );
    <?php
    }
    else if($file_name=='other_form') {
    ?>
        $("input[name=mms_idx]", opener.document).val( mms_idx );
        $("input[name=ppr_idx]", opener.document).val( ppr_idx );
        $("input[name=ppr_name]", opener.document).val( ppr_name );
    <?php
    }
    ?>

    window.close();
});
</script>

<?php
include_once('./_tail.sub.php');
?>