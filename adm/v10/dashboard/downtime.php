<?php
// http://daechang.epcs.co.kr/adm/v10/dashboard/downtime.php?w=5&h=2
include_once('./_common.php');

$g5['title'] = 'Downtime';
include_once('./_head.sub.php');

if(is_file(G5_USER_ADMIN_PATH.'/'.$g5['dir_name'].'/css/style.css')) {
    add_stylesheet('<link rel="stylesheet" href="'.G5_USER_ADMIN_URL.'/'.$g5['dir_name'].'/css/style.css">', 2);
}
if(is_file(G5_USER_ADMIN_PATH.'/'.$g5['dir_name'].'/css/'.$g5['file_name'].'.css')) {
    add_stylesheet('<link rel="stylesheet" href="'.G5_USER_ADMIN_URL.'/'.$g5['dir_name'].'/css/'.$g5['file_name'].'.css">', 2);
}
?>
<div class="box_header">
    <div class="top_left">
        <p class="title_main"><?=G5_TIME_YMD?> (<?=$g5['week_names'][date("w",G5_SERVER_TIME)]?>)</p>
    </div>
    <div class="top_right">
        <p>
            <a href="../system/manual_downtime_list.php" class="btn_detail" style="margin-right:10px;"><i class="fa fa-list-alt"></i></a>
            <a href="javascript:" class="btn_reload"><i class="fa fa-repeat"></i></a>
        </p>
    </div>
</div>

<div class="tbl_head01 tbl_wrap">
    <?php
    $sql = "SELECT dta.*, mms.mms_name, mst.mst_name, mst.mst_type
            FROM {$g5['data_downtime_table']} AS dta
                LEFT JOIN {$g5['mms_table']} AS mms ON dta.mms_idx = mms.mms_idx
                LEFT JOIN {$g5['mms_status_table']} AS mst ON dta.mst_idx = mst.mst_idx
            WHERE dta.com_idx = '{$_SESSION['ss_com_idx']}'
            ORDER BY dta.dta_reg_dt DESC
            LIMIT 8
    ";
    // echo $sql.'<br>';
    $result = sql_query($sql,1);
    ?>
    <table class="table01">
        <thead class="tbl_head">
        <tr>
            <th scope="col" style="width:100px;">설비</th>
            <th scope="col" style="width:">차종</th>
            <th scope="col" style="width:">타입</th>
            <th scope="col" style="width:">하드웨어구분</th>
            <th scope="col" style="width:260px;">비가동시간</th>
            <th scope="col" style="width:100px;">작업시간</th>
            <th scope="col" style="width:100px;">금액</th>
        </tr>
        </thead>
        <tbody class="tbl_body">
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            //print_r2($row);
            // 합계인 경우
            
            // 작업시간
            $start_time = new DateTime($row['dta_start_dt']);
            $end_time = new DateTime($row['dta_end_dt']);
            $interval = $start_time->diff($end_time);
            // echo $interval->format('%H : %I');
            $row['working_hour'] = $interval->format('%H : %I');

            echo '
            <tr class="'.$row['tr_class'].'">
                <td class="text_left">'.$row['mms_name'].'</td>
                <td class="text_left">'.(($row['dta_category'])?$g5['cats_key_val'][$row['dta_category']]:'기타').'</td>
                <td class="text_left">'.$row['mst_name'].'</td>
                <td class="text_left">'.(($row['dta_hardware'])?$g5['mng_hardware_category_value'][$row['dta_hardware']]:'-').'</td>
                <td class="text_left">'.substr($row['dta_start_dt'],0,16).'~'.substr($row['dta_end_dt'],0,16).'</td>
                <td class="text_center">'.$row['working_hour'].'</td>
                <td class="text_right pr_5">'.(($row['dta_price'])?number_format($row['dta_price']):'-').'</td>
            </tr>
            ';
        }
        if ($i == 0)
            echo '<tr class="tr_empty"><td class="td_empty" colspan="6">자료가 없습니다.</td></tr>';
        ?>
    </tbody>
    </table>
</div>

<script>
$(document).on('click','.btn_detail',function(e){
    e.preventDefault();
    parent.location.href = $(this).attr('href');
});
$(document).on('click','.btn_reload',function(){
    self.location.reload();
});
// 10분에 한번 재로딩
setTimeout(function(e){
    self.location.reload();
},1000*600);
</script>

<?php
include_once ('./_tail.sub.php');
?>
