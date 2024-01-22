<?php
// 호출페이지들
// /adm/v10/bom_structure_form.php: 오른편에 나타남
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 메뉴입니다.');

/*
[file_name] => production_form
[bom_idx] => 410
[boc_idx] => 162
[prd_idx] => 30
[prm_idx] => 249
[bom_idx_parent] => 410
[pri_date] => 2024-01-08
[pri_value] => 59
*/
$bom = get_table('bom','bom_idx',$bom_idx);

$msql = " SELECT GROUP_CONCAT(DISTINCT bmw.mms_idx) AS mms_idxs
                , GROUP_CONCAT(DISTINCT mms_serial_no) AS mms_serial_nos
                , GROUP_CONCAT(DISTINCT mms_name) AS mms_names
         FROM {$g5['bom_mms_worker_table']} bmw
         LEFT JOIN {$g5['mms_table']} mms ON bmw.mms_idx = mms.mms_idx
         WHERE bom_idx = '{$bom_idx}'
            AND bmw_status = 'ok'
        ORDER BY bmw_main_yn DESC, bmw.mms_idx
";
$mres = sql_fetch($msql);
$exist_data = 1;
if(!$mres['mms_idxs']) $exist_data = 0;

$mms_arr = ($mres['mms_idxs']) ? explode(',', $mres['mms_idxs']) : array();
$mms_nos = ($mres['mms_serial_nos']) ? explode(',', $mres['mms_serial_nos']) : array();
$mms_names = ($mres['mms_names']) ? explode(',', $mres['mms_names']) : array();


$mms_options = '';
for($i=0; $i<sizeof($mms_arr); $i++){
    $mms_options .= '<option value="'.$mms_arr[$i].'">'.$mms_names[$i].'('.$mms_nos[$i].')</option>';
}

if($exist_data){
    $sql = " SELECT GROUP_CONCAT(DISTINCT bmw.mb_id) AS mb_ids
                    , GROUP_CONCAT(DISTINCT mb_8) AS mb_nos
                    , GROUP_CONCAT(DISTINCT mb_name) AS mb_names
            FROM {$g5['bom_mms_worker_table']} bmw
            LEFT JOIN {$g5['member_table']} mb ON bmw.mb_id = mb.mb_id
                WHERE bom_idx = '{$bom_idx}'
                    AND mms_idx = '{$mms_arr[0]}'
                    AND bmw_status = 'ok'
                ORDER BY mb_name
    ";
    // echo $sql;
    $res = sql_fetch($sql);
    $mb_arr = ($res['mb_ids']) ? explode(',', $res['mb_ids']) : array();
    $mb_nos = ($res['mb_nos']) ? explode(',', $res['mb_nos']) : array();
    $mb_names = ($res['mb_names']) ? explode(',', $res['mb_names']) : array();

    $mb_options = '';
    for($j=0; $j<sizeof($mb_arr); $j++){
        $mb_options .= '<option value="'.$mb_arr[$j].'">'.$mb_names[$j].'('.$mb_nos[$j].')</option>';
    }
}

$g5['title'] = '[ '.$bom['bom_part_no'].' ]의 생산계획제품 생성';
include_once('./_head.sub.php');
?>
<style>
#hd_h1{padding:10px;}
#sch_target_frm{padding:10px;}
#btn_box{text-align:center;padding:20px 0;}
</style>
<h1 id="hd_h1">
    <?php echo $g5['title'] ?><br>
    <?=$bom['bom_name']?>
    <p>생산일 : <?=$pri_date?></p>
</h1>
<div id="sch_target_frm" class="new_win scp_frame">
<form name="form01" id="form01" onsubmit="return form_submit(this);" method="post">
<input type="hidden" name="bom_idx" value="<?=$bom_idx?>">
<input type="hidden" name="boc_idx" value="<?=$boc_idx?>">
<input type="hidden" name="prd_idx" value="<?=$prd_idx?>">
<input type="hidden" name="prm_idx" value="<?=$prm_idx?>">
<input type="hidden" name="bom_idx_parent" value="<?=$bom_idx_parent?>">
<input type="hidden" name="pri_date" value="<?=$pri_date?>">
<div class="tbl_frm01 tbl_wrap">
<table>
<colgroup>
    <col class="grid_4" style="width:15%;">
    <col style="width:35%;">
    <col class="grid_4" style="width:15%;">
    <col style="width:35%;">
</colgroup>
<tbody>
<tr>
    <th scope="row">설비<br>선택</th>
    <td>
        <select name="mms_idx" id="mms_idx" class="mms_idx" onchange="obj_change(this);">
            <?=$mms_options?>
        </select>
    </td>
    <th scope="row">작업자<br>선택</th>
    <td>
        <select name="mb_id" id="mb_id">
            <?=$mb_options?>
        </select>
    </td>
</tr>
<tr>
    <th scope="row">지시<br>수량</th>
    <td>
        <input type="text" name="pri_value" id="pri_value" value="<?=number_format($pri_value)?>" class="frm_input" style="width:60px;text-align:right;">
    </td>
    <th scope="row">작업<br>상태</th>
    <td>
        <select name="pri_ing" id="pri_ing">
            <option value="0">비작업</option>
            <option value="1">작업중</option>
        </select>
    </td>
</tr>
</tbody>
</table>
</div><!--//.tbl_frm01-->
<div id="btn_box">
    <button type="button" class="btn btn_05 btn_close">창닫기</button>
    <a href="javascript:" bom_idx="" mms_idx="" mb_id="" prd_idx="" prm_idx="" boc_idx="" bom_idx_parent="" pri_value="" pri_ing="" pri_date="" onclick="pri_reg(this);" class="btn btn_01 btn_reg">등록</a>
</div>
</form>
</div><!--//#sch_target_frm-->
<script>
/*
[file_name] => production_form
[bom_idx] => 410
[boc_idx] => 162
[prd_idx] => 30
[prm_idx] => 249
[bom_idx_parent] => 410
[pri_date] => 2024-01-08
[pri_value] => 59
*/
let bom_idx = <?=$bom_idx?>;
let boc_idx = <?=$boc_idx?>;
let prd_idx = <?=$prd_idx?>;
let prm_idx = <?=$prm_idx?>;
let bom_idx_parent = <?=$bom_idx_parent?>;

let exist_data = <?=$exist_data?>;
if(!exist_data){
    alert('먼저 "편집"을 눌러서 먼저 제품-설비-작업자 등록을 해 주세요.');
    window.close();
}

$(function(){
    //창닫기
    $('.btn_close').on('click',function(){
        window.close();
    });

    // 가격 입력 쉼표 처리
	$(document).on( 'keyup','#pri_value',function(e) {
        var price = thousand_comma($(this).val().replace(/[^0-9]/g,""));
        price = (price == '0') ? '' : price;
        $(this).val(price);
	});
});
/*
[file_name] => production_form
[bom_idx] => 410
[boc_idx] => 162
[prd_idx] => 30
[prm_idx] => 249
[bom_idx_parent] => 410
[pri_date] => 2024-01-08
[pri_value] => 59
*/

function obj_change(obj){
    let mmsSel = $(obj).closest('form').find('#mms_idx');
    let mbSel = $(obj).closest('form').find('#mb_id');
    let priInput = $(obj).closest('form').find('#pri_value');
    let ingSel = $(obj).closest('form').find('#pri_ing');
    let btn = $(obj).closest('form').find('.btn_submit');

    let bom_idx = <?=$bom_idx?>;
    let mms_idx = mmsSel.val();
    let mb_id = mbSel.val();
    let pri_value = priInput.val();
    let pri_ing = ingSel.val();
    let pri_date = <?=$pri_date?>;
    if($(obj).hasClass('mms_idx')){
        // console.log(bom_idx,mms_idx);
        let aurl = '<?=G5_USER_ADMIN_AJAX_URL?>/bom_mms_mb.php';
        // console.log(aurl);
        $.ajax({
            url: aurl,
            type: 'POST',
            dataType: 'html',
            data: {'bom_idx': bom_idx, 'mms_idx': mms_idx},
            success: function (res){
                mbSel.empty();
                mbSel.html(res);
                mb_id = mbSel.val();
                if(btn.hasClass('btn_reg')){
                    btn.attr({
                        'bom_idx': bom_idx,
                        'mms_idx': mms_idx,
                        'mb_id': mb_id,
                        'prd_idx': prd_idx,
                        'prm_idx': prm_idx,
                        'boc_idx': boc_idx,
                        'bom_idx_parent': bom_idx_parent,
                        'pri_value': pri_value.replace(/,/g,''),
                        'pri_date': pri_date,
                        'pri_ing': pri_ing
                    });
                }
                return;
            },
            error: function(xre){
                alert('Status: ' + xre.status + ' \n\rstatusText: ' + xre.statusText + ' \n\rresponseText: ' + xre.responseText);
            }
        });
    }

    if(btn.hasClass('btn_reg')){
        btn.attr({
            'bom_idx': bom_idx,
            'mms_idx': mms_idx,
            'mb_id': mb_id,
            'prd_idx': prd_idx,
            'prm_idx': prm_idx,
            'boc_idx': boc_idx,
            'bom_idx_parent': bom_idx_parent,
            'pri_value': pri_value.replace(/,/g,''),
            'pri_date': pri_date,
            'pri_ing': pri_ing
        });
    }
}

function pri_reg(regBtn){
    let reg_btn = $(regBtn);
    let bom_idx = <?=$bom_idx?>;
    let mms_idx = reg_btn.closest('form').find('#mms_idx').val();
    let mb_id = reg_btn.closest('form').find('#mb_id').val();
    let prd_idx = <?=$prd_idx?>;
    let prm_idx = <?=$prm_idx?>;
    let boc_idx = <?=$boc_idx?>;
    let bom_idx_parent = <?=$bom_idx_parent?>;
    let pri_value = reg_btn.closest('form').find('#pri_value').val();
    let pri_ing = reg_btn.closest('form').find('#pri_ing').val();
    let pri_date = '<?=$pri_date?>';
    let aurl = '<?=G5_USER_ADMIN_AJAX_URL?>/pri_insert.php';
    
    if(mms_idx == ''){
        alert('설비데이터가 없습니다.');
        return;
    }
    if(mb_id == ''){
        alert('작업자데이터가 없습니다.');
        return;
    }
    if(pri_value == '' || pri_value == '0'){
        alert('지시량데이터가 없습니다.');
        return;
    }

    // console.log(aurl);
    $.ajax({
        url: aurl,
        type: 'POST',
        dataType: 'text',
        data: {
            'bom_idx': bom_idx 
            ,'mms_idx': mms_idx
            ,'mb_id': mb_id
            ,'prd_idx': prd_idx
            ,'prm_idx': prm_idx
            ,'boc_idx': boc_idx
            ,'bom_idx_parent': bom_idx_parent
            ,'pri_value': pri_value
            ,'pri_date': pri_date
            ,'pri_ing': pri_ing
        },
        success: function (res){
            if(res == 'ok'){
                window.opener.location.reload();
                window.close();
            }
            else{
                alert(res);
            }
        },
        error: function(xre){
            alert('Status: ' + xre.status + ' \n\rstatusText: ' + xre.statusText + ' \n\rresponseText: ' + xre.responseText);
        }
    });
}


function form_submit(f){

    return false;
}
</script>
<?php
include_once('./_tail.sub.php');