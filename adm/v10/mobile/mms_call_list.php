<?php
include_once('./_common.php');
//http://daechang2.epcs.co.kr/adm/v10/mobile/mms_check_list.php
if($member['mb_9'] != 'admin_tech' && !$is_admin){
    alert('생기공무권한을 가지고 계신분만 접근 가능합니다.', G5_USER_ADMIN_MOBILE_URL);
}

$sql_common = " FROM {$g5['mms_table']}
";


$where = array();
$where[] = " mms_status NOT IN ('trash','delete') ";   // 디폴트 검색조건

// com_idx 조건
$where[] = " com_idx IN (".$_SESSION['ss_com_idx'].") ";
$where[] = " mms_name NOT IN ('-') ";


if (isset($stx)&&$stx!='') {
    switch ($sfl) {
		case ($sfl == 'mms_idx') :
            $where[] = " {$sfl} = '{$stx}' ";
            break;
		case ($sfl == 'mms_name') :
            $where[] = " {$sfl} LIKE '%{$stx}%' ";
            break;
        default :
            $where[] = " {$sfl} LIKE '%{$stx}%' ";
            break;
    }
}


// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


if (!$sst) {
    $sst = "mms_idx";
    $sod = "DESC";
}
$sql_order = " ORDER BY {$sst} {$sod} ";

$rows = 100;$config['cf_page_rows'];
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " SELECT SQL_CALC_FOUND_ROWS DISTINCT *
        {$sql_common}
		{$sql_search}
        {$sql_order}
		LIMIT {$from_record}, {$rows} 
";
// echo $sql;
$result = sql_query($sql,1);
$count = sql_fetch_array( sql_query(" SELECT FOUND_ROWS() as total ") ); 
$total_count = $count['total'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체</a>';



$g5['title'] = '설비호출현황';
$g5['box_title'] = '설비호출현황을 확인하고 호출해제';
include_once('./_head.php');
?>
<div id="mobile_cont_box">
    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="mms_name"<?php echo get_selected($_GET['sfl'], "mms_name"); ?>>설비명</option>
        <option value="mms_idx"<?php echo get_selected($_GET['sfl'], "mms_idx"); ?>>설비ID</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" class="btn_submit" value="검색">
    <?=$listall?>
    </form>
    <ul id="lst">
        <?php for($i=0; $row=sql_fetch_array($result); $i++){ ?>
        <li class="<?=($row['mms_call_yn']?'focus':'')?>">
            <div class="mms_con mms_idx"><?=$row['mms_idx']?></div>
            <div class="mms_con mms_name"><?=$row['mms_name']?></div>
            <div class="mms_con mms_chk" id="mms_idx_<?=$row['mms_idx']?>" mms_idx="<?=$row['mms_idx']?>">
                <i class="fa fa-check" aria-hidden="true"></i>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>

<script>
$('.mms_chk').on('click', function(){
    // var flag = $(this).parent().hasClass('focus') ? 1 : 0;
    var flag = $(this).parent().hasClass('focus') ? 0 : 1;
    var mms_idx = $(this).attr('mms_idx');
    // if(flag){
    //     $(this).parent().removeClass('focus');
    // }
    // else {
    //     $(this).parent().addClass('focus');
    // }
    // flag = flag ? 0 : 1;
    // console.log(flag);
    mms_call_update(mms_idx,flag);
});

function mms_call_update(mms_idx,flag){
    var ajx_url = '<?=G5_USER_ADMIN_MOBILE_AJAX_URL?>/mms_call_update.php';
    // alert(ajx_url+'/'+mms_idx+'/'+flag);
    // return false;
    $.ajax({
        type: "POST",
        url: ajx_url,
        dataType: "text",
        data: {
            "mms_idx": mms_idx,
            "flag":flag,
        },
        async: false,
        success: function(res){
            if(res == 'ok_1'){
                $('#mms_idx_'+mms_idx).parent().addClass('focus');
            } else if(res == 'ok_0'){
                $('#mms_idx_'+mms_idx).parent().removeClass('focus');
            } else {
                alert(res);
            }
        },
        error: function(xmlReq){
            alert('Status: ' + xmlReq.status + ' \n\rstatusText: ' + xmlReq.statusText + ' \n\rresponseText: ' + xmlReq.responseText);
        }
    });
}
</script>
<?php
include_once('./_tail.php');