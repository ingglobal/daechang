<?php
include_once('./_common.php');
include_once('./_head.sub.php');
$sql = " SELECT mms_idx
            , mms_name
            , mms_call_yn
        FROM {$g5['mms_table']}
WHERE com_idx = '{$g5['setting']['set_com_idx']}'
    AND mms_status = 'ok'
    AND mms_call_yn = '1'
ORDER BY mms_idx
";
$res = sql_query($sql,1);

if(is_file($g5_monitor_path.'/dashboard/css/style.css')) {
    add_stylesheet('<link rel="stylesheet" href="'.$g5_monitor_url.'/dashboard/css/style.css">', 2);
}
if(is_file($g5_monitor_path.'/dashboard/css/'.$g5['file_name'].'.css')) {
    add_stylesheet('<link rel="stylesheet" href="'.$g5_monitor_url.'/dashboard/css/'.$g5['file_name'].'.css">', 2);
}
// print_r2($res->num_rows);
?>
<style>
html,body{}
.box{padding-top:100px;}
.box_header .title_main{color:#aaa !important;}
.box_body2{height:100%;border:0px solid red;overflow-y:hidden;}
.box_body2.call{
    animation: redCallChange 1s infinite;
}
.box_body2 ul{}
.box_body2 ul li{font-size:5em;font-weight:700;}
.box_body2 ul li:before{content:'- '}
</style>
<div class="box">
    <div class="box_header">
        <p class="title_main">설비호출</p>
    </div>
    <div class="box_body2<?=(($res->num_rows)?' call':'')?>">
        <ul>
            <?php for($i=0;$row=sql_fetch_array($res);$i++){ ?>
            <li><?=$row['mms_name']?></li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
$(function(){
    
});
</script>
<?php
include_once ('./_tail.sub.php');
?>