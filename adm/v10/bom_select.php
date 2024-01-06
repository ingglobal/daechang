<?php
// 호출페이지들
// /adm/v10/order_form.php
// /adm/v10/item_form.php
// /adm/v10/material_form.php
// /adm/v10/bom_jig_form.php
// /adm/v10/production_form.php
// /adm/v10/mms_worker_form.php
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 페이지입니다.');

// 검색어 
$stx = isset($_REQUEST['stx']) ? clean_xss_tags($_REQUEST['stx'], 1, 1) : '';


$g5['title'] = '제품검색';
include_once(G5_PATH.'/head.sub.php');

// $sql_common = " FROM {$g5['bom_table']} AS bom
//                 LEFT JOIN {$g5['customer_table']} AS cst ON cst.cst_idx = bom.cst_idx_provider
// ";
$sql_common = " FROM {$g5['bom_customer_table']} boc
                LEFT JOIN {$g5['bom_table']} bom ON boc.bom_idx = bom.bom_idx
                LEFT JOIN {$g5['customer_table']} cst ON boc.cst_idx = cst.cst_idx
";
$sql_where = " WHERE bom_status NOT IN ('delete','trash') ";
$sql_where .= " AND boc.cst_idx != 0 ";
$sql_where .= " AND boc.com_idx = '{$_SESSION['ss_com_idx']}' ";

if($file_name == 'material_order_form' && $cst_idx){
    $sql_where .= " AND cst_idx_provider = '".$cst_idx."' ";
    $sql_where .= " AND bom_type = 'material' ";
}
else if($file_name == 'ordprd_form' || $file_name == 'production_form'){
    $sql_where .= " AND bom_type = 'product' ";
    $sql_where .= " AND boc_type = 'customer' ";
}

if($stx != ""){
    switch($sfl){
        case ($sfl == 'bom_part_no'):
            $sql_where .= " AND bom_part_no = '".$stx."' ";
            break;
        default:
            $stx = preg_replace('/\!\?\*$#<>()\[\]\{\}/i', '', strip_tags($stx));
            $sql_where .= " AND (bom_name LIKE '%".sql_real_escape_string($stx)."%' OR bom_names LIKE '%".sql_real_escape_string($stx)."%') ";
            break;
    }
}

if($ser_cst_idx) {
    $sql_where .= " AND boc.cst_idx = '".$ser_cst_idx."' ";
}

$sql_group = " GROUP BY boc.bom_idx, boc.cst_idx ";

// $sst = " boc.bom_idx";
// $sod = "DESC";
// $sst2 = ", boc.cst_idx";
// $sod2 = "";

// $sql_order = " ORDER BY {$sst} {$sod} {$sst2} {$sod2} ";


// 테이블의 전체 레코드수만 얻음
$sql = " SELECT COUNT(*) cnt FROM ( SELECT boc_idx AS cnt {$sql_common} {$sql_where} {$sql_group} ) gboc ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "SELECT boc.bom_idx
            , bom_part_no
            , bom_name
            , bom_type
            , bom_price
            , bom_status
            , boc_idx
            , boc.cst_idx
            , boc_type
            , cst_name
            {$sql_common}
            {$sql_where}
            {$sql_group}
        ORDER BY boc.bom_idx DESC, boc.cst_idx
        LIMIT $from_record, $rows
";
// echo $sql.'<br>';
$result = sql_query($sql);

$qstr1 = 'sfl='.urlencode($sfl).'&stx='.urlencode($stx).'&file_name='.$file_name.'&item='.$item.'&ser_cst_idx='.$ser_cst_idx;
?>

<div id="sch_member_frm" class="new_win scp_new_win">
    <h1><?=$g5['title']?></h1>

    <form name="fmember" method="get">
    <input type="hidden" name="file_name" value="<?php echo $file_name; ?>" class="frm_input">
    <input type="hidden" name="item" value="<?php echo $item; ?>" class="frm_input">
    <div id="scp_list_find">
        <select name="ser_cst_idx" id="ser_cst_idx">
            <option value="">거래처</option>
            <?php
            if($file_name == 'ordprd_form'){
                echo $g5['customer_options'];
            }
            ?>
        </select>
        <script>$('select[name=ser_cst_idx]').val("<?=$ser_cst_idx?>").attr('selected','selected');</script>
        <select name="sfl" id="sfl">
            <option value="bom_part_no">품번</option>
            <option value="bom_name">품명</option>
        </select>
        <input type="text" name="stx" id="stx" value="<?php echo get_text($stx); ?>" class="frm_input" placeholder="검색어">
        <input type="submit" value="검색" class="btn_frmline">
        <a href="<?=$_SERVER['SCRIPT_NAME']?>?file_name=<?=$file_name?>&item=<?=$item?>" class="ov_listall">전체목록</a>
    </div>
    <div class="tbl_head01 tbl_wrap new_win_con">
        <table>
        <caption>검색결과</caption>
        <thead>
        <tr>
            <th>품번</th>
            <th>품명</th>
            <th>유형</th>
            <th>거래처</th>
            <th>선택</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $row['cst_provider'] = get_table('customer','cst_idx',$row['cst_idx_provider'],'cst_name');
        ?>
        <tr>
            <td class="td_bom_part_no td_left">
                <?=get_text($row['bom_part_no'])?>
            </td>
            <td class="td_bom_part_name td_left">
                <?=$row['bom_name']?>
            </td>
            <td class="td_bom_type td_left">
                <?=$g5['set_bom_type_value'][$row['bom_type']]?>
            </td>
            <td class="td_cst_name td_left">
                <?=$row['cst_name']?>
            </td>
            <td class="scp_find_select td_mng td_mng_s">
                <button type="button" class="btn btn_03 btn_select"
                    boc_idx="<?=$row['boc_idx']?>"
                    cst_idx="<?=$row['cst_idx']?>"
                    cst_name="<?=$row['cst_name']?>"
                    bom_idx="<?=$row['bom_idx']?>"
                    bom_part_no="<?=$row['bom_part_no']?>"
                    bom_name="<?=$row['bom_name']?>"
                    bom_type="<?=$row['bom_type']?>"
                    bom_price="<?=$row['bom_price']?>"
                >선택</button>
            </td>
        </tr>
        <?php
        }

        if($i ==0)
            echo '<tr><td colspan="5" class="empty_table">검색된 자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>
    </form>

    <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr1.'&amp;page='); ?>

</div>

<script>
$('.btn_select').click(function(e){
    e.preventDefault();
    var boc_idx = $(this).attr('boc_idx');
    var cst_idx = $(this).attr('cst_idx');
    var cst_name = $(this).attr('cst_name');
    var bom_idx = $(this).attr('bom_idx');
    var bom_part_no = $(this).attr('bom_part_no');
    var bom_name = $(this).attr('bom_name');
    var bom_type = $(this).attr('bom_type');
    var bom_price = $(this).attr('bom_price');

    <?php
    if($file_name=='order_form'||$file_name=='production_form'||$file_name=='mms_worker_form') {
    ?>
        $("input[name=boc_idx]", opener.document).val( boc_idx );
        $("input[name=bom_idx]", opener.document).val( bom_idx );
        $("input[name=bom_name]", opener.document).val( bom_name );
        $("input[name=ori_price]", opener.document).val( bom_price );
        $(".span_bom_part_no", opener.document).text( bom_part_no );
    <?php
    }
    else if($file_name=='item_form'||$file_name=='bom_jig_form') {
    ?>
        $("input[name=bom_idx]", opener.document).val( bom_idx );
        $("input[name=bom_name]", opener.document).val( bom_name );
        $("input[name=itm_price]", opener.document).val( bom_price );
        $("select[name=itm_type]", opener.document).val( bom_type );
        $(".span_bom_part_no", opener.document).text( bom_part_no );
    <?php
    }
    else if($file_name=='material_form') {
    ?>
        $("input[name=bom_idx]", opener.document).val( bom_idx );
        $("input[name=mtr_part_no]", opener.document).val( bom_part_no );
        $("input[name=mtr_name]", opener.document).val( bom_name );
        $("input[name=mtr_price]", opener.document).val( bom_price );
        $("select[name=mtr_type]", opener.document).val( bom_type );
        $(".span_bom_part_no", opener.document).text( bom_part_no );
    <?php
    }
	// 계약관리 수정
	else if($file_name=='bom_form') {
		if($item=='customer') {
    ?>
			$("input[name=bom_idx_customer]", opener.document).val( bom_idx );
			$("input[name=bom_name_customer]", opener.document).val( bom_name );
		<?php
		}
		else if ($item=='provider') {
		?>
			$("input[name=bom_idx_provider]", opener.document).val( bom_idx );
			$("input[name=bom_name_provider]", opener.document).val( bom_name );
    <?php
		}
    }
	// 계약관리 수정
	else if($file_name=='material_order_form') {
    ?>
        $("input[name=bom_idx]", opener.document).val( bom_idx );
        $("input[name=bom_name]", opener.document).val( bom_name );
    <?php
    } else if($file_name=='ordprd_form'){ 
    ?>
        $("input[name=bom_idx]", opener.document).val( bom_idx );
        $("input[name=bom_name]", opener.document).val( bom_name );
        $("#bom_part_no", opener.document).text( bom_part_no );
        $("input[name=boc_idx]", opener.document).val( boc_idx );
        $("#cst_name", opener.document).val( cst_name );
    <?php
    }
    ?>

    // 창닫기
    window.close();
});
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');