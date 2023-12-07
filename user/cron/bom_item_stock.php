<?php
include_once('./_common.php');

$demo = 0; //demo mode = 1

$g5['title'] = 'BOM재고량 업데이트';
include_once('./_head.sub.php');

$days = 30;
$bom_sql = " SELECT bom_idx
                , itm_type AS bom_type
                , itm_reg_dt AS bom_reg_dt
                , SUM(itm_value) AS bom_cnt
            FROM {$g5['item_table']}
                WHERE itm_reg_dt >= (NOW() - INTERVAL {$days} DAY)
                    AND itm_status IN ('ok','finish')
            GROUP BY bom_idx
            UNION ALL
            SELECT bom_idx
                , mtr_type AS bom_type
                , mtr_reg_dt AS bom_reg_dt
                , SUM(mtr_value) AS bom_cnt
            FROM {$g5['material_table']}
                WHERE mtr_reg_dt >= (NOW() - INTERVAL {$days} DAY)
                    AND mtr_status IN ('ok','finish')
            GROUP BY bom_idx
            ORDER BY bom_reg_dt DESC, bom_idx
";
$bom_res = sql_query($bom_sql,1);
// echo $bom_sql;
// exit;
?>
<div class="" style="padding:10px;">
    <span>
        작업시작~~ <font color="crimson"><b>[끝]</b></font> 이라는 단어가 나오기 전 중간에 중지하지 마세요.
    </span><br><br>
    <span id="cont"></span>
</div>
<?php
include(G5_PATH.'/tail.sub.php');

$countgap = 10; //몇건씩 보낼지 설정
$sleepsec = 10000; //백만분에 몇초간 쉴지 설정(20000/1000000=0.02)(10000/1000000=0.01)(5000/1000000=0.005)
$maxscreen = 50; // 몇건씩 화면에 보여줄건지 설정

flush();
ob_flush();


$cnt = 0;
$result = $bom_res->num_rows;
// echo $result;
// exit;
for($i=0;$row=sql_fetch_array($bom_res);$i++){
    $cnt++;

    $stock_update_sql = " UPDATE {$g5['bom_table']} SET
            bom_stock = '{$row['bom_cnt']}'
            , bom_update_dt = '".G5_TIME_YMDHIS."'
        WHERE bom_idx = '{$row['bom_idx']}'
    ";
    sql_query($stock_update_sql,1);

    echo "<script>document.all.cont.innerHTML += ['".$cnt."] - ".$row['bom_idx']." 처리됨<br>';</script>\n";

    flush();
    ob_flush();
    ob_end_flush();
    usleep($sleepsec);

    //보기 쉽게 묶음 단위로 구분 (단락으로 구분해서 보임)
    if($cnt % $countgap == 0){
        echo "<script>document.all.cont.innerHTML += '<br>';</script>\n";
    }

    //화면 정리! 부하를 줄임 (화면을 싹 지움)
    if($cnt % $maxscreen == 0){
        echo "<script>document.all.cont.innerHTML = '';</script>\n";
    }
}
?>
<script>
    document.all.cont.innerHTML += "<br><br>총 <?php echo number_format($i); ?>건 완료<br><br><font color='crimson'><b>[끝]</b></font>";
</script>