<?php
include_once('./_common.php');
// include_once(G5_LIB_PATH.'/thumbnail.lib.php');
//http://daechang2.epcs.co.kr/adm/v10/mobile/production_list.php?mms_idx=157
$where_mms_idx = "";
$mms_name = "";
$mms = array();
if($mms_idx){
    $mms = get_table_meta('mms','mms_idx',$mms_idx);
    $mms_name = $mms['mms_name']; //$g5['mms_arr'][$mms_idx];
    $where_mms_idx = " AND pri.mms_idx = '{$mms_idx}' ";
}
$plt_arr = array();
$sql = " SELECT prd.prd_idx
            , prm.prm_date
            , pri.prm_idx
            , pri.pri_idx
            , pri.bom_idx
            , bom.bct_idx
            , bom.bom_part_no
            , bom.bom_name
            , bom.cst_idx_customer
            , bom.bom_type
            , boi.bit_main_yn
            , boi.bom_idx AS bom_idx_parent
            , cst.cst_name
            , pri_value
            , pri.mms_idx
            , pri.mb_id
            , pri_memo
            , pri_ing
            , pri_status
            , pri_reg_dt
            , pri_update_dt
        FROM {$g5['production_item_table']} pri
            LEFT JOIN {$g5['production_main_table']} prm ON pri.prm_idx = prm.prm_idx
            LEFT JOIN {$g5['production_table']} prd ON pri.prd_idx = prd.prd_idx
            LEFT JOIN {$g5['bom_table']} bom ON pri.bom_idx = bom.bom_idx
            LEFT JOIN {$g5['bom_item_table']} boi ON bom.bom_idx = boi.bom_idx_child
            LEFT JOIN {$g5['customer_table']} cst ON bom.cst_idx_customer = cst.cst_idx
            LEFT JOIN {$g5['member_table']} mb ON pri.mb_id = mb.mb_id
        WHERE pri.mb_id = '{$member['mb_id']}'
            AND pri.mms_idx = '{$mms_idx}'
            AND prm.prm_date = '".statics_date(G5_TIME_YMDHIS)."'
            AND prm.prm_status = 'confirm'
            AND bom.bom_type IN ('product','half','material')
";
// echo $sql;
$result = sql_query($sql,1);

$g5['title'] = '생산작업설정(Production Setting)';
$g5['box_title'] = $member['mb_name'].'님의 '.statics_date(G5_TIME_YMDHIS).' 생산설정';
$g5['box_title'] .= '<br>Prodution for '.$member['mb_name'].' at '.statics_date(G5_TIME_YMDHIS);
$mms_call_yn = $mms['mms_call_yn'];
$mms_manual_yn = $mms['mms_manual_yn'];
$mms_testmanual_yn = $mms['mms_testmanual_yn'];
include_once('./_head.php');
?>
<div id="plt_list">
    <h4 id="plt_ttl"><?=(($mms_name)?'['.$mms_name.']설비에서의 ':'')?>생산제품</h4>
    <ul class="ul_item">
        <?php for($i=0;$row=sql_fetch_array($result);$i++){ 
            
            $tsql = " SELECT SUM(pic_value) AS total FROM {$g5['production_item_count_table']}
                        WHERE pri_idx = '{$row['pri_idx']}'
            ";

            $tres = sql_fetch($tsql);
            $total = ($tres['total']) ? $tres['total'] : 0;
            
            
            $psql = " SELECT SUM(pic_value) AS ptotal FROM {$g5['production_item_count_table']}
                        WHERE pri_idx = '{$row['pri_idx']}'
                            AND mb_id = '{$member['mb_id']}'
            ";
            $pres = sql_fetch($psql);
            $ptotal = ($pres['ptotal']) ? $pres['ptotal'] : 0;
            
            $row['main_box_exist'] = 0;
            if(!$row['bom_idx_parent'] && $row['bom_type'] == 'product'){
                $row['main_box_exist'] = main_bom_exist($row['bom_idx']);
            }
            else if($row['bom_idx_parent']){
                $row['main_box_exist'] = main_bom_exist($row['bom_idx_parent']);
            }
        ?>
        <li class="li_desc">
            <dd class="dd_des dd_mms">
                <?php if(!$mms_call_yn){ ?>
                    <?php if($row['pri_ing']){ ?>
                        <strong class="st_ing">Running!</strong>
                    <?php } ?>
                <?php } else { ?>
                    <strong class="st_call">Calling!</strong>
                <?php } ?>
            </dd>
            <dd class="dd_des">
                <?=$row['bom_name']?><br>
                <strong class="part_no">[ <?=$row['bom_part_no']?> ]</strong>
                <span class="part_nm"><?=$g5['set_bom_type_value'][$row['bom_type']]?></span>
            </dd>
            <div class="num_box">
                <div class="num">
                    <span>Goal Count</span>
                    <strong><?=$row['pri_value']?></strong>
                </div>
                <div class="num">
                    <span>My Count</span>
                    <strong><?=$ptotal?></strong>
                </div>
                <div class="num">
                    <span>Total Count</span>
                    <strong><?=$total?></strong>
                </div>
            </div>
            <div class="btn_box">
                <form name="formA<?=$i?>" class="formA" id="formA<?=$i?>" action="./production_list_update.php" onsubmit="return form01_submit(this);" method="post">
                    <input type="hidden" name="call" value="0">
                    <input type="hidden" name="mms_idx" value="<?=$row['mms_idx']?>">
                    <input type="hidden" name="prd_idx" value="<?=$row['prd_idx']?>">
                    <input type="hidden" name="prm_idx" value="<?=$row['prm_idx']?>">
                    <input type="hidden" name="pri_idx" value="<?=$row['pri_idx']?>">
                    <input type="hidden" name="bom_idx" value="<?=$row['bom_idx']?>">
                    <input type="hidden" name="bom_type" value="<?=$row['bom_type']?>">
                    <input type="hidden" name="bit_main_yn" value="<?=$row['bit_main_yn']?>">
                    <input type="hidden" name="bom_idx_parent" value="<?=$row['bom_idx_parent']?>">
                    <input type="hidden" name="main_bom_exist" value="<?=$row['main_box_exist']?>">
                    <input type="hidden" name="pri_ing" value="<?=$row['pri_ing']?>">
                    <input type="submit" value="<?=(($row['pri_ing'])?'END':'START')?>" onclick="document.pressed=this.value" class="mbtn btn_toggle<?=(($row['pri_ing'])?' focus':'')?>">
                    <?php if($row['pri_ing'] && ($mms_manual_yn || $mms_testmanual_yn)){ ?>
                    <div class="tooltip">
                        <span class="tooltip_close"><i class="fa fa-times-circle" aria-hidden="true"></i></span>
                        <input type="text" name="pri_cnt" value="" placeholder="생산수량" class="frm_input pri_cnt" autocomplete="off">
                    </div>
                    <?php } ?>
                </form>
                <form name="formB<?=$i?>" class="formB" id="formB<?=$i?>" action="./production_list_update.php" onsubmit="return form01_submit(this);" method="post">
                    <input type="hidden" name="call" value="1">
                    <input type="hidden" name="mms_idx" value="<?=$row['mms_idx']?>">
                    <input type="hidden" name="mb_name" value="<?=$member['mb_name']?>">
                    <input type="hidden" name="mms_name" value="<?=$mms_name?>">
                    <input type="hidden" name="prd_idx" value="<?=$row['prd_idx']?>">
                    <input type="hidden" name="pri_idx" value="<?=$row['pri_idx']?>">
                    <input type="hidden" name="bom_idx" value="<?=$row['bom_idx']?>">
                    <input type="hidden" name="call_yn" value="<?=(($mms_call_yn)?'0':'1')?>">
                    <input type="submit" value="<?=(($mms_call_yn)?'NoCall':'Call')?>" class="mbtn btn_call<?=(($mms_call_yn)?' focus':'')?>">
                </form>
            </div>
        </li>
        <?php } ?>
        <?php if($i == 0){ ?>
        <li class="li_empty">데이터가 존재하지 않습니다.<br>설비고유번호를 입력박스에<br>입력하고 검색해 주세요.</li>
        <li>
        <form id="fmms_idx" method="GET">
            <input type="text" name="mms_idx" value="<?=$mms_idx?>">
            <input type="submit" value="검색">
        </form>
        </li>
        <?php } ?>
    </ul>
</div>
<script>
var url = '<?=G5_USER_ADMIN_MOBILE_URL?>/production_list.php<?=(($mms_idx)?'?mms_idx='.$mms_idx:'')?>';
var mms_idx = <?=$mms_idx?>;
var mms_manual_yn = <?=$mms_manual_yn?>;
var mms_testmanual_yn = <?=$mms_testmanual_yn?>;
// alert(mms_testmanual_yn);

$('.tooltip_close').on('click',function(){
    $(this).siblings('input').val('');
    $(this).parent().removeClass('focus');
});

$('input[name="pri_cnt"]').on('input',function(){
    var num = $(this).val().replace(/[^0-9]/g,"");
    num = (num == '0') ? '' : num;
    $(this).val(num);
});

function form01_submit(f){ //inp
    var num = 0;
    var flag = true;
    if(document.pressed == 'END'){
        
        // if(mms_manual_yn || mms_testmanual_yn){
        if(!f.pri_cnt.value && (mms_manual_yn || mms_testmanual_yn)){
            $(f).find('.tooltip').addClass('focus');
            flag = false;
            return flag;
        }
        else{
            $(f).find('.tooltip').removeClass('focus');
        }
    }

    if(!confirm(f.pri_cnt.value + '개가 정확합니까?')){
        f.pri_cnt.value = '';
        flag = false;
        return flag;
    }
    else{
        $('#loading_box').addClass('focus');
        var ajax_stock_insert_url = '<?=G5_USER_ADMIN_MOBILE_URL?>/ajax/end_stock_insert.php';
        $.ajax({
            type: "POST",
            url: ajax_stock_insert_url,
            dataType: "text",
            data: {
                "prd_idx": f.prd_idx.value,
                "prm_idx": f.prm_idx.value,
                "pri_idx":f.pri_idx.value,
                "mms_idx": mms_idx,
                "mb_id": '<?=$member['mb_id']?>',
                "bom_idx": f.bom_idx.value,
                "bom_type": f.bom_type.value,
                "bit_main_yn": f.bit_main_yn.value,
                "bom_idx_parent": f.bom_idx_parent.value,
                "main_bom_exist": f.main_bom_exist.value,
                "pri_cnt": f.pri_cnt.value,
                "mms_manual_yn": mms_manual_yn,
                "mms_testmanual_yn": mms_testmanual_yn
            },
            async: false,
            success: function(res){
                // console.log(res);return false;
                if(res == 'ok')
                    $('#loading_box').removeClass('focus');
            },
            error: function(xmlReq){
                alert('Status: ' + xmlReq.status + ' \n\rstatusText: ' + xmlReq.statusText + ' \n\rresponseText: ' + xmlReq.responseText);
            }
        });
    }
    return flag;
}

</script>
<?php
include_once('./_tail.php');