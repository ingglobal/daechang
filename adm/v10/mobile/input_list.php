<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
//http://daechang2.epcs.co.kr/adm/v10/mobile/check.php?plt_idx=116
if($member['mb_9'] != 'admin_quality' && !$is_admin){
    alert('품질관리권한을 가지고 계신분만 접근 가능합니다.', G5_USER_ADMIN_MOBILE_URL);
}

if(!$mto_idx){
    alert('발주ID가 제대로 넘어오지 않았습니다.');
}
$mto = get_table('material_order','mto_idx', $mto_idx);

$sql_common = " FROM {$g5['material_order_item_table']} moi
                LEFT JOIN {$g5['material_order_table']} mto ON moi.mto_idx = mto.mto_idx
                LEFT JOIN {$g5['bom_table']} bom ON moi.bom_idx = bom.bom_idx
                LEFT JOIN {$g5['bom_customer_table']} boc ON bom.bom_idx = boc.bom_idx AND boc.boc_type = 'provider'
";
// echo "<br><br><br><br><br><br>";
// echo $chk_sql;
$where = array();
$where[] = " moi_status NOT IN ('trash') ";   // 디폴트 검색조건
$where[] = " mto.com_idx = '{$g5['setting']['set_com_idx']}' ";
$where[] = " moi.mto_idx = '{$mto_idx}' ";

if (isset($stx)&&$stx!='') {
    switch ($sfl) {
		case ($sfl == 'moi_idx' || $sfl == 'bom_part_no') :
            $where[] = " {$sfl} = '{$stx}' ";
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
    $sst = "moi_idx";
    $sod = "DESC";
}
$sql_order = " ORDER BY {$sst} {$sod} ";

$rows = 100;//$config['cf_page_rows'];
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

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?mto_idx='.$mto_idx.'" class="ov_listall">전체</a>';

$g5['title'] = '품질검사 및 출하처리';
$g5['box_title'] = '발주ID[ '.$mto['mto_idx'].' ] '.cst2name($mto['cst_idx'])."<br>";
$g5['box_title'] .= '입고처리할 제품은 체크버튼을 누르세요.';

include_once('./_head.php');
?>
<div id="mto_cont_box">
    <?php if($mto_idx && $result->num_rows){ ?>
    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="bom_part_no"<?php echo get_selected($_GET['sfl'], "bom_part_no"); ?>>품번</option>
        <option value="moi_idx"<?php echo get_selected($_GET['sfl'], "moi_idx"); ?>>발주제품ID</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" class="btn_submit" value="검색">
    <?=$listall?>
    </form>
    <ul id="lst">
        <?php for($i=0; $row=sql_fetch_array($result); $i++){ ?>
        <li class="<?=($row['moi_status']=='input'?'focus':'')?>">
            <div class="moi_up_box">
                <div class="moi_con moi_idx"><?=$row['moi_idx']?></div>
                <div class="moi_con bom_info">
                    <span class="sp_bom_part_no"><?=$row['bom_part_no']?></span>
                    <span class="sp_bom_name"><?=cut_str($row['bom_name'],17,'...')?></span>
                    <?php if($row['bom_stock_check_yn']){ ?>
                        <?php if($row['moi_status'] == 'reject') { ?>
                        <strong class="sp_moi_status sp_moi_reject">반려처리</strong>
                        <?php } else if($row['moi_status'] == 'input') { ?>
                        <strong class="sp_moi_status sp_moi_input">입고완료</strong>
                        <?php } else { ?>
                        <strong class="sp_moi_status sp_moi_check">검사필요</strong>
                        <?php } ?>
                    <?php } else { ?>
                        <?php if($row['moi_status'] == 'input') { ?>
                        <strong class="sp_moi_status sp_moi_input">입고완료</strong>   
                        <?php } else { ?>
                        <strong class="sp_moi_status sp_moi_pending">입고대기</strong>   
                        <?php } ?>
                    <?php } ?>
                    <span class="sp_moi_count"><?=number_format($row['moi_count'])?> 개</span>
                </div>
                <?php
                $moi_btn = '';
                if($row['bom_stock_check_yn']){
                    $moi_btn = 'moi_qlt';
                } else {
                    $moi_btn = 'moi_chk';
                }
                ?>
                <div class="moi_con <?=$moi_btn?>" id="moi_idx_<?=$row['moi_idx']?>" mto_idx="<?=$row['mto_idx']?>" moi_idx="<?=$row['moi_idx']?>" moi_count="<?=$row['moi_count']?>">
                    <i class="fa fa-check" aria-hidden="true"></i>
                </div>
            </div><!--//.moi_up_box-->
            <?php if($row['bom_stock_check_yn']){ ?>
            <div class="moi_down_box">
                <input type="text" class="frm_input err_msg" placeholder="반려사유" value="<?=$row['moi_check_text']?>">
                <button type="button" class="btn btn_pass" mto_idx="<?=$row['mto_idx']?>" moi_idx="<?=$row['moi_idx']?>" moi_count="<?=$row['moi_count']?>">합격</button>
                <button type="button" class="btn btn_nopass" mto_idx="<?=$row['mto_idx']?>" moi_idx="<?=$row['moi_idx']?>" moi_count="<?=$row['moi_count']?>">불합격</button>
            </div>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
    <?php } else { ?>
    <div class="moi_empty">발주ID 데이터가 없습니다.<br>발주ID 번호를 입력박스에<br>입력하고 검색해 주세요.</div>
    <form id="fmoi_idx" method="GET">
        <input type="text" name="mto_idx" value="<?=$mto_idx?>">
        <input type="submit" value="검색">
    </form>
    <?php } ?>
</div><!--//#mto_cont_box-->
<script>
var checker = '<?=$member['mb_id']?>';
$('.moi_qlt').on('click',function(){
    var moi_down_box = $(this).closest('.moi_up_box').siblings('.moi_down_box');
    var moi_open = (moi_down_box.hasClass('open')) ? '' : 'open';
    $('.moi_down_box').removeClass('open');
    moi_down_box.addClass(moi_open);
});

$('.err_msg').on('input', function(){

});

$('.btn_pass').on('click', function(){
    var flag = 1;
    var mto_idx = $(this).attr('mto_idx');
    var moi_idx = $(this).attr('moi_idx');
    var moi_count = $(this).attr('moi_count');
    var status_obj = $(this).closest('li').find('.sp_moi_status');
    $('.moi_down_box').removeClass('open'); //열려진 하위 박스를 모두 닫아라
    moi_input_update(mto_idx,moi_idx,moi_count,flag,status_obj,checker,1,0,'');
});

$('.btn_nopass').on('click', function(){
    var flag = 0;
    var mto_idx = $(this).attr('mto_idx');
    var moi_idx = $(this).attr('moi_idx');
    var moi_count = $(this).attr('moi_count');
    var status_obj = $(this).closest('li').find('.sp_moi_status');
    var moi_check_text = $(this).siblings('.err_msg').val();
    $('.moi_down_box').removeClass('open'); //열려진 하위 박스를 모두 닫아라
    moi_input_update(mto_idx,moi_idx,moi_count,flag,status_obj,checker,0,1,moi_check_text);
});

$('.moi_chk').on('click', function(){
    var flag = $(this).closest('li').hasClass('focus') ? 0 : 1;
    var mto_idx = $(this).attr('mto_idx');
    var moi_idx = $(this).attr('moi_idx');
    var moi_count = $(this).attr('moi_count');
    var status_obj = $(this).closest('li').find('.sp_moi_status');
    $('.moi_down_box').removeClass('open'); //열려진 하위 박스를 모두 닫아라
    // console.log(flag,moi_idx,moi_count,status_obj);
    moi_input_update(mto_idx,moi_idx,moi_count,flag,status_obj,checker);
});

function moi_input_update(mto_idx,moi_idx,moi_cnt,flag,stat_obj,chker,pass=0,nopass=0,msg=''){
    $('#loading_box').addClass('focus');
    var class_arr = stat_obj.attr('class').split(' ');
    var cur_status = class_arr[class_arr.length - 1];
    var ajx_url = '<?=G5_USER_ADMIN_MOBILE_AJAX_URL?>/moi_input_update.php';
    // alert(ajx_url+'/'+mms_idx+'/'+flag);
    // return false;
    $.ajax({
        type: "POST",
        url: ajx_url,
        dataType: "json",
        data: {
            "mto_idx": mto_idx,
            "moi_idx": moi_idx,
            "moi_count": moi_cnt,
            "flag":flag,
            "moi_status":cur_status,
            "mb_id_check":chker,
            "pass":pass,
            "nopass":nopass,
            "moi_check_text":msg,
        },
        async: false,
        success: function(res){
            // console.log(res['ok'],res['msg']);
            // if(res == 'ok_1'){
            //     $('#moi_idx_'+moi_idx).closest('li').addClass('focus');
            // } else if(res == 'ok_0'){
            //     $('#moi_idx_'+moi_idx).closest('li').removeClass('focus');
            // } else {
            //     alert(res);
            // }
            if(res['ok']){
                location.reload();
            }
            else{
                alert(res['msg']);
                $('#loading_box').removeClass('focus');
            }
        },
        error: function(xmlReq){
            alert('Status: ' + xmlReq.status + ' \n\rstatusText: ' + xmlReq.statusText + ' \n\rresponseText: ' + xmlReq.responseText);
            $('#loading_box').removeClass('focus');
        }
    });
}
</script>
<?php
include_once('./_tail.php');