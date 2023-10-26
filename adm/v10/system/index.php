<?php
$sub_menu = "925110";
include_once('./_common.php');

auth_check($auth[$sub_menu],"r");

$g5['title'] = '설비 실시간모니터링';
// include_once('./_top_menu_db.php');
include_once('./_head.php');
// echo $g5['container_sub_title'];


$com_idx = $_SESSION['ss_com_idx'];

$st_time_ahead = 86400*10;
$en_date = ($en_date) ? $en_date : date("Y-m-d",G5_SERVER_TIME);
$en_time = ($en_time) ? $en_time : date("H:i:s",G5_SERVER_TIME);
$st_date = ($st_date) ? $st_date : date("Y-m-d",strtotime($en_date.' '.$en_time)-$st_time_ahead);
$st_time = ($st_time) ? $st_time : date("H:i:s",strtotime($en_date.' '.$en_time)-$st_time_ahead);

?>
<style>
.div_container {position:relative;}
.div_container:after {display:block;visibility:hidden;clear:both;content:'';}
.div_container > div {border: solid 1px #5b5b5d;border-radius: 5px;min-height: 450px;padding: 10px;width: 49.5%;margin-bottom:10px;}
.div_left {float:left;}
.div_right {float:right;}
.div_container .more, .div_container_full .more {float:right;font-size:0.8em;}
.text01 {font-size:0.8em;margin:0 auto;}
.div_container_full {border: solid 1px #5b5b5d;border-radius: 5px;min-height: 150px;padding: 10px;margin-bottom:10px;}
.span_title {display:inline-block;margin-bottom:10px;font-size:1.2em;}
</style>

<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/highstock.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/modules/data.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/modules/exporting.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/themes/high-contrast-dark.js"></script>
<!-- 다양한 시간 표현을 위한 플러그인 -->
<script src="<?php echo G5_URL?>/lib/highcharts/moment.js"></script>


<div id="div_wrapper">
    <div class="div_container">
        <?php
        $sql = "SELECT (CASE WHEN n='1' THEN ymd_date ELSE 'total' END) AS item_name
                    , SUM(arm_count_sum) AS arm_count_sum
                    , SUM(arm_alarm_sum) AS arm_alarm_sum
                    , SUM(arm_predict_sum) AS arm_predict_sum
                FROM
                (
                    SELECT 
                        ymd_date
                        , SUM(arm_count_sum) AS arm_count_sum
                        , SUM(arm_alarm_sum) AS arm_alarm_sum
                        , SUM(arm_predict_sum) AS arm_predict_sum
                    FROM
                    (
                        (
                        SELECT 
                            CAST(ymd_date AS CHAR) AS ymd_date
                            , 0 AS arm_count_sum
                            , 0 AS arm_alarm_sum
                            , 0 AS arm_predict_sum
                        FROM g5_5_ymd AS ymd
                        WHERE ymd_date BETWEEN '".$st_date."' AND '".$en_date."'
                        ORDER BY ymd_date
                        )
                    UNION ALL
                        (
                        SELECT
                            substring( CAST(arm_reg_dt AS CHAR),1,10) AS ymd_date
                            , COUNT(arm_idx) AS arm_count_sum
                            , SUM( IF(arm_cod_type IN ('a','p','p2'),1,0) ) AS arm_alarm_sum
                            , SUM( IF(arm_cod_type IN ('p','p2'),1,0) ) AS arm_predict_sum
                        FROM g5_1_alarm
                        WHERE arm_reg_dt >= '".$st_date." 00:00:00' AND arm_reg_dt <= '".$en_date." 23:59:59'
                            AND com_idx='".$com_idx."'
                        GROUP BY ymd_date
                        ORDER BY ymd_date
                        )
                    ) AS db_table
                    GROUP BY ymd_date
                ) AS db2, g5_5_tally AS db_no
                WHERE n <= 2
                GROUP BY item_name
                ORDER BY n DESC, item_name
        ";
        // echo $sql;
        $result = sql_query($sql,1);
        $alarm_data = $predict_data = array();
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // print_r2($row);
            // 합계인 경우
            if($row['item_name'] == 'total') {
                $row['item_name'] = '합계';
            }
            else {
                array_push($alarm_data, array($row['item_name'],(int)$row['arm_alarm_sum']));
                array_push($predict_data, array($row['item_name'],(int)$row['arm_predict_sum']));
            }
        }
        // print_r2($alarm_data);
        ?>
        <div class="div_left">
            <span class="span_title">알람</span>
            <a href="./alarm_data_list.php" class="more">더보기</a>
            <div id="chart1"></div>
            <script>
            Highcharts.chart('chart1', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                navigation: {
                    buttonOptions: {
                        enabled: false, // contextButton (인쇄, 다운로드..) 설정 (기본옵션 사용자들에게는 안 보이게!!)
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y:.0f}</b>'
                },
                series: [{
                    name: 'Population',
                    data: <?=json_encode($alarm_data)?>,
                    // data: [
                    //     ['Tokyo', 37.33],
                    //     ['Delhi', 31.18],
                    //     ['Shanghai', 27.79],
                    //     ['Sao Paulo', 22.23],
                    //     ['Mexico City', 21.91],
                    //     ['Dhaka', 21.74],
                    //     ['Cairo', 21.32],
                    //     ['Beijing', 20.89],
                    //     ['Mumbai', 20.67],
                    //     ['Osaka', 19.11],
                    //     ['Karachi', 16.45],
                    //     ['Chongqing', 16.38],
                    //     ['Istanbul', 15.41],
                    //     ['Buenos Aires', 15.25],
                    //     ['Kolkata', 14.974],
                    //     ['Kinshasa', 14.970],
                    //     ['Lagos', 14.86],
                    //     ['Manila', 14.16],
                    //     ['Tianjin', 13.79],
                    //     ['Guangzhou', 13.64]
                    // ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.0f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });
            </script>
        </div>
        <div class="div_right">
            <span class="span_title">예지</span>
            <a href="./pre_data_list.php" class="more">더보기</a>
            <div id="chart2"></div>
            <script>
            Highcharts.chart('chart2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                navigation: {
                    buttonOptions: {
                        enabled: false, // contextButton (인쇄, 다운로드..) 설정 (기본옵션 사용자들에게는 안 보이게!!)
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y:.0f}</b>'
                },
                series: [{
                    name: 'Population',
                    data: <?=json_encode($predict_data)?>,
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.0f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });
            </script>
        </div>
    </div>
    <div class="div_container">
        <div class="div_left" style="display:none;">
        <span class="span_title">로봇데이터</span>
            <a href="./robot_list.php" class="more">더보기</a>
            <div id="chart3">
                <span class="text01"><i class="fa fa-spinner fa-spin"></i></span>
            </div>
            <script>
            // Highcharts.getJSON('http://hanjoo.epcs.co.kr/user/json/stat_robot.php?token=1099de5drf09&st_date=<?=$st_date?>&en_date=<?=$en_date?>', function (data) {
            //     // create the chart
            //     Highcharts.setOptions({
            //         lang: {
            //             thousandsSep: ',',
            //             decimalPoint: '.'
            //         }
            //     });
            //     Highcharts.chart('chart3', {
            //         chart: {
            //             type: 'column'
            //         },
            //         title: {
            //             text: ''
            //         },
            //         xAxis: {
            //             type: 'category',
            //             labels: {
            //                 style: {
            //                     fontSize: '10px',
            //                     fontFamily: 'Verdana, sans-serif'
            //                 }
            //             }
            //         },
            //         navigation: {
            //             buttonOptions: {
            //                 enabled: false, // contextButton (인쇄, 다운로드..) 설정 (기본옵션 사용자들에게는 안 보이게!!)
            //             }
            //         },
            //         legend: {
            //             enabled: false
            //         },
            //         tooltip: {
            //             pointFormat: '<b>{point.y:,.0f}</b>'
            //         },
            //         series: [{
            //             name: 'Population',
            //             data: data,
            //             dataLabels: {
            //                 enabled: true,
            //                 rotation: -90,
            //                 color: '#FFFFFF',
            //                 align: 'right',
            //                 format: '{point.y:,.0f}', // one decimal
            //                 y: 10, // 10 pixels down from the top
            //                 style: {
            //                     fontSize: '10px',
            //                     fontFamily: 'Verdana, sans-serif'
            //                 }
            //             }
            //         }]
            //     });
            // });
            </script>
        </div>
        <div class="div_right" style="display:none;">
            <span class="span_title">설비데이터</span>
            <a href="../intelli/data_measure_list.php" class="more">더보기</a>
            <div id="chart4">
                <span class="text01"><i class="fa fa-spinner fa-spin"></i></span>
            </div>
            <script>
            // Highcharts.getJSON('http://hanjoo.epcs.co.kr/user/json/stat_facility.php?token=1099de5drf09&st_date=<?=$st_date?>&en_date=<?=$en_date?>', function (data) {
            //     // create the chart
            //     Highcharts.setOptions({
            //         lang: {
            //             thousandsSep: ',',
            //             decimalPoint: '.'
            //         }
            //     });
            //     Highcharts.chart('chart4', {
            //         chart: {
            //             type: 'column'
            //         },
            //         title: {
            //             text: ''
            //         },
            //         xAxis: {
            //             type: 'category',
            //             labels: {
            //                 style: {
            //                     fontSize: '10px',
            //                     fontFamily: 'Verdana, sans-serif'
            //                 }
            //             }
            //         },
            //         navigation: {
            //             buttonOptions: {
            //                 enabled: false, // contextButton (인쇄, 다운로드..) 설정 (기본옵션 사용자들에게는 안 보이게!!)
            //             }
            //         },
            //         legend: {
            //             enabled: false
            //         },
            //         tooltip: {
            //             pointFormat: '<b>{point.y:,.0f}</b>'
            //         },
            //         series: [{
            //             name: 'Population',
            //             data: data,
            //             dataLabels: {
            //                 enabled: true,
            //                 rotation: -90,
            //                 color: '#FFFFFF',
            //                 align: 'right',
            //                 format: '{point.y:,.0f}', // one decimal
            //                 y: 10, // 10 pixels down from the top
            //                 style: {
            //                     fontSize: '10px',
            //                     fontFamily: 'Verdana, sans-serif'
            //                 }
            //             }
            //         }]
            //     });
            // });
            </script>
        </div>
    </div>
    <div class="div_container_full prelative">
        <span class="span_title">비가동</span>
        <a href="./manual_downtime_list.php" class="more">더보기</a>
        <div class="tbl_head01 tbl_wrap">
            <?php
            $sql = "SELECT dta.*, mms.mms_name, mst.mst_name, mst.mst_type
                    FROM {$g5['data_downtime_table']} AS dta
                        LEFT JOIN {$g5['mms_table']} AS mms ON dta.mms_idx = mms.mms_idx
                        LEFT JOIN {$g5['mms_status_table']} AS mst ON dta.mst_idx = mst.mst_idx
                    WHERE dta.com_idx = '{$_SESSION['ss_com_idx']}'
                    ORDER BY dta.dta_reg_dt DESC
                    LIMIT 10
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
    </div>
</div>




<?php
include_once ('./_tail.php');
?>
