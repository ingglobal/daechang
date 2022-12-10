<?php
// $sub_menu = '915110';
include_once('./_common.php');

$g5['title'] = '대시보드';
include_once ('./_head.php');
//$sub_menu : 현재 메뉴코드 915140
//$cur_mta_idx : 현재 메타idx 422

$demo = 0;  // 데모인 경우 1로 설정하세요. (packery 박스가 맨 위에 떠 있어서 디버깅 데이터를 가려버리네요.)

// $cur_mta_idx 변수는 _dashboard_top_submenu.php 에서 생성함 (해당 파일 include는 /adm/v10/admin.head.php에서 해당 파일 include 호출)
$sql = " SELECT * FROM {$g5['dash_grid_table']} WHERE mta_idx = '{$cur_mta_idx}' AND dsg_status = 'ok' ORDER BY dsg_order ";
// echo $sql.'<br>';
$result = sql_query($sql,1);
?>
<style>

</style>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/highstock.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/modules/data.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/modules/exporting.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/themes/high-contrast-dark.js"></script>
<!-- 다양한 시간 표현을 위한 플러그인 -->
<script src="<?php echo G5_URL?>/lib/highcharts/moment.js"></script>

<script>
// 부모창 선택 영역 색상 변경
$('.graph_icons a', parent.document).removeClass('on');
$('.icon_x<?=$column?>', parent.document).addClass('on');

// 로딩 spinner 이미지 표시/비표시
function dta_loading(flag,mbd_idx) {
    var img_loading = $('<i class="fa fa-spin fa-spinner" id="spinner'+mbd_idx+'" style="position:absolute;top:80px;left:46%;font-size:4em;"></i>');
    if(flag=='show') {
        // console.log('show');
        $('#chart'+mbd_idx).append(img_loading);
    }
    else if(flag=='hide') {
        // console.log('hide');
        $('#spinner'+mbd_idx).remove();
    }
}

function createChart(mbd_idx,seriesOptions,dta_item,mmses,mms_count) {
    // console.log(seriesOptions);
    var chart = new Highcharts.stockChart({
        chart: {
            renderTo: 'chart_'+mbd_idx
        },
        scrollbar: {
            enabled: false
        },
        
        animation: false,

        xAxis: {
            // min: 1587635789000,
            // max: 1587643939000,
            type: 'datetime',
            labels: {
                formatter: function() {
                    return moment(this.value).format("MM/DD HH:mm");
                }
            },
        },

        yAxis: {
            // max: 1800,   // 크게 확대해서 보려면 20
            // min: -100,  // 크게 확대해서 보려면 -10, 없애버리면 자동 스케일
            showLastLabel: true,    // 위 아래 마지막 label 보임 (이게 없으면 끝label이 안 보임)
            opposite: false,
            tickInterval: null,
            // minorTickInterval: 5,
            // minorTickLength: 0,
        },

        plotOptions: {
            series: {
                showInNavigator: true,
                turboThreshold: 0,
                dataGrouping: {
                    enabled: false, // dataGrouping 안 함 (range가 변경되면 평균으로 바뀌어서 헷갈림)
                },
                marker: {
                    enabled: true   // point dot display
                }
            }
        },

        navigator: {
            enabled: false
        },

        navigation: {
            buttonOptions: {
                enabled: false, // contextButton (인쇄, 다운로드..) 설정 (기본옵션 사용자들에게는 안 보이게!!)
                align: 'right',
                x: -20,
                y: 15
            }
        },

        legend: {
            enabled: true,
            useHTML: true,
            labelFormatter: function () {
                // console.log(this._i);
                // console.log(mmses);
                var mms_name = (mms_count>1 && mmses[this._i]) ? '<br>'+decodeURIComponent(mmses[this._i]) : '';
                return '<span>'+this.name+mms_name+'</span>';
            }
        },

        rangeSelector: {
            enabled: false,
        },

        tooltip: {
            formatter: function(e) {
                // var tooltip1 =  moment(this.x).format("YYYY-MM-DD HH:mm:ss");
                if(dta_item=='daily'||dta_item=='weekly') {
                    var tooltip1 =  moment(this.x).format("MM/DD");
                }
                else if(dta_item=='monthly') {
                    var tooltip1 =  moment(this.x).format("YYYY-MM");
                }
                else if(dta_item=='yearly') {
                    var tooltip1 =  moment(this.x).format("YYYY");
                }
                else {
                    var tooltip1 =  moment(this.x).format("MM/DD HH:mm:ss");
                }
                // console.log(this);
                var tooltip2 = [];
                $.each(this.points, function () {
                    // console.log(this.point);
                    var this_name = this.series.name;
                    // if 기종 exists
                    if(this.point.dta_mmi_no) {
                        // console.log(this.point.dta_mmi_no);
                        this_name += ' (기종:'+this.point.dta_mmi_no+')';
                    }
                    tooltip1 += '<br/><span style="color:' + this.color + '">\u25CF '+this_name+'</span>: <b>' + this.point.yraw + '</b>';
                    if(this.point.y!=this.point.yraw) {
                        if(this.point.yamp!=1)
                            tooltip2[0] = '×' + this.point.yamp;
                        if(this.point.ymove!=0) {
                            var tooltip2_unit = (this.point.ymove>0) ? '+':'';  // -기호는 자동으로 붙음
                            tooltip2[1] = tooltip2_unit + this.point.ymove;
                        }
                        // console.log(tooltip2);
        
                        if(tooltip2.length>=1) {
                            tooltip1 += '<span style="font-size:0.8em;"> (' + tooltip2.join(" ") + ')</span>';
                        }
                    }
                });
                return tooltip1;
            },
            split: false,
            shared: true
        },
        series: seriesOptions
    });

    dta_loading('hide',mbd_idx);
    removeLogo();
}

// As we're loading the data asynchronously, we don't know what order it will arrive.
// So we keep a counter and create the chart when all the data is loaded.
function drawChart(data) {
    // find which graph
    var para = urlParaToJSON2(this.url); // get values from Json Url
    // console.log(this.url);
    var dta_group = para.dta_group;
    var mms_idx = para.mms_idx;
    var dta_type = para.dta_type;
    var dta_no = para.dta_no;
    var shf_no = para.shf_no;
    var dta_mmi_no = para.dta_mmi_no;
    var dta_defect = para.dta_defect;
    var dta_defect_type = para.dta_defect_type;
    var dta_code = para.dta_code;
    var dta_item = para.dta_item;
    var graph_name = para.graph_name;
    var graph_id = para.graph_id;
    var mbd_idx = para.mbd_idx;
    // console.log( mbd_idx );

    // 그래프 배열을 할당 (여러개라서 해당 그래프에 개별 할당을 해야 함)
    var graphs = eval('graphs'+mbd_idx);
    var seriesOptions = eval('seriesOptions'+mbd_idx);
    // console.log( graphs );

    // idx 찾기 (비동기라 어떤 idx가 왔는지 모르기 때문!!)
    for(i=0;i<graphs.length;i++) {
        // console.log(i+'번: '+graphs[i].graph_id);
        if( graph_id == graphs[i].graph_id ) {
            var chr_idx = i;
        }
    }
    // console.log(chr_idx + ' arrived.');

    var graph_id1 = getGraphId(graphs[chr_idx].dta_json_file, graphs[chr_idx].dta_group, graphs[chr_idx].mms_idx, graphs[chr_idx].dta_type, graphs[chr_idx].dta_no, graphs[chr_idx].shf_no, graphs[chr_idx].dta_mmi_no, graphs[chr_idx].dta_defect, graphs[chr_idx].dta_defect_type, graphs[chr_idx].dta_code);
    var chr_id = {
        dta_data_url: graphs[chr_idx].dta_data_url,
        dta_json_file: graphs[chr_idx].dta_json_file,
        dta_group: graphs[chr_idx].dta_group,
        mms_idx: graphs[chr_idx].mms_idx,
        dta_type: graphs[chr_idx].dta_type,
        dta_no: graphs[chr_idx].dta_no,
        shf_no: graphs[chr_idx].shf_no,
        dta_mmi_no: graphs[chr_idx].dta_mmi_no,
        dta_defect: graphs[chr_idx].dta_defect,
        dta_defect_type: graphs[chr_idx].dta_defect_type,
        dta_code: graphs[chr_idx].dta_code,
        graph_name: graphs[chr_idx].graph_name,
        graph_id: graph_id1
    };

    // data variable definition <<<<==============================================
    seriesOptions[chr_idx] = {
        name: decodeURIComponent(graph_name),
        id:chr_id,
        type: graphs[chr_idx].graph_type,
        dashStyle: graphs[chr_idx].graph_line,
        data: data
    };

    // Create chart when all data loaded.
    eval( 'seriesCounter'+mbd_idx+' += 1;' );
    // console.log( mbd_idx +'> '+ eval('seriesCounter'+mbd_idx) );
    // seriesCounter += 1;// This is not ok for many graphs.
    if (eval('seriesCounter'+mbd_idx) == graphs.length) {
        console.log('mbd_idx('+mbd_idx+') graph drawing .................................');
        console.log('seriesOptions length: ' + seriesOptions.length);
        eval( 'seriesCounter'+mbd_idx+' = 0;' );
        var mmses = eval('mmses'+mbd_idx);
        var mms_count = eval('mms_count'+mbd_idx);
        // console.log(mmses);
        // console.log(mms_count);
        createChart(mbd_idx,seriesOptions,dta_item,mmses,mms_count);
    }

}
</script>


<?php
// 불러올 위젯이 있는 경우
if($result->num_rows){ 
echo '<div class="pkr">'.PHP_EOL;
echo '<div class="pkr-sizer"></div>'.PHP_EOL;

$acc_wd = 0;
$pos_x = 0;
$pos_json = '[';
for($i=0;$row=sql_fetch_array($result);$i++) {
    $pkr_item_w = (!$row['dsg_width_num'])?:' pkr-item-w'.$row['dsg_width_num'];
    $pkr_item_h = (!$row['dsg_height_num'])?:' pkr-item-h'.$row['dsg_height_num'];
    $it_wd_per = $g5['set_pkr_size_value'][$row['dsg_width_num']] / 100;
    $test_acc_wd = $acc_wd + $it_wd_per;
    // echo $pkr_item_w.'<br>';
    //첫번째 그리드는 무조건
    if($i==0){
        $pos_x = $acc_wd;
        $acc_wd = $it_wd_per;
        $pos_json .= '{"attr":"'.$row['dsg_idx'].'","x":'.$pos_x.'}';
    }
    else{
        if($test_acc_wd > 1){
            $pos_x = 0;
            $acc_wd = $it_wd_per;
        }
        else{
            $pos_x = $acc_wd;
            $acc_wd += $it_wd_per;
        }
        $pos_json .= ',{"attr":"'.$row['dsg_idx'].'","x":'.$pos_x.'}';
    }

    // 대시보드 내용 구성 -----------------
    $sql1 = "SELECT *
            FROM {$g5['member_dash_table']}
            WHERE dsg_idx = '".$row['dsg_idx']."'
    ";
    // echo $sql1.'<br>';
    $row = sql_fetch($sql1,1);
    $row['sried'] = get_serialized($row['mbd_setting']);
    // unset($row['sried']['data_series']);   // hide temporally for debuging.
    // print_r2($row['sried']);
    $row['data'] = json_decode($row['sried']['data_series'],true);
    unset($row['mbd_setting']);
    unset($row['sried']['data_series']);
    // print_r2($row); // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    // 그래프 정보(여러개 일수 있으므로 배열)
    for($j=0;$j<sizeof($row['data']);$j++) {
        $row['chr_names'][] = $row['data'][$j]['name']; // 그래프 상단 제목 설정
        $row['chr_mms_idxs'][] = $row['data'][$j]['id']['mms_idx']; // 여러 설비 데이터인지 구분
        // target should be from local
        if($row['data'][$j]['id']['dta_json_file']=='output.target') {
            $row['data'][$j]['id']['dta_data_url'] = strip_http(G5_ADMIN_URL).'/v10/ajax';
        }
        // print_r2($row['data'][$j]);
    }
    // 그래프 이름 (tag name array if not desiganated name.)
    $row['mbd_graph_name'] = $row['sried']['graph_name'] ?: implode(", ",$row['chr_names']);
    // mms 다중 아이콘 표현 (mms_idx 중복 제거)
    $row['chr_mms_idxs'] = array_unique($row['chr_mms_idxs']);
    for($j=0;$j<sizeof($row['chr_mms_idxs']);$j++) {
        $row['chr_mms_idx_icons'] .= '<i class="fa fa-circle"></i>';
    }

?>
<div class="pkr-item<?=$pkr_item_w.$pkr_item_h?> pkr-box" dsg_idx="<?=$row['dsg_idx']?>" mbd_idx="<?=$row['mbd_idx']?>">
    <div class="pkr-cont" style="display:<?=$demo?'none':''?>;">
    <div class="pkt_wrapper">
        <!-- 타이틀 부분 -->
        <div class="pkt_title">
            <span class="graph_title" title="<?=$row['mbd_graph_name']?>"><?=$row['mbd_graph_name']?></span>
            <span class='graph_icons'>
                <a href="javascript:" class="chart_view" style="display:none;"><i class='fa fa-bar-chart'></i></a>
                <a href="javascript:" class="chart_setting"><i class='fa fa-gear'></i></a>
            </span>
            <ul class="graph_setting">
                <li><a href="javascript:" class="graph_view">상세보기</a></li>
                <li style="display:none;"><a href="javascript:" class="graph_excel_down">엑셀다운</a></li>
                <li><a href="javascript:" class="graph_name_change">이름변경</a></li>
                <li><a href="javascript:" class="graph_delete">삭제</a></li>
                <li><a href="javascript:" class="graph_move">위젯이동</a></li>
            </ul>
        </div>
        <!--================ 챠트 부분 ==================-->
        <div id="chart_<?=$row['mbd_idx']?>" class="pkt_cont">
            <i class="fa fa-spin fa-circle-o-notch" id="spinner" style="position:absolute;top:80px;left:46%;font-size:4em;color:#38425b;"></i>
        </div>
        <script>
            // initialize script values
            var seriesOptions<?=$row['mbd_idx']?> = [], graphs<?=$row['mbd_idx']?> = [], mmses<?=$row['mbd_idx']?> = []
                , seriesCounter<?=$row['mbd_idx']?> = 0;
            <?php
            // for loop as many times as graph count.
            for($j=0;$j<sizeof($row['data']);$j++) {
                // echo 'console.log("'.$row['data'][$j]['id']['dta_data_url_host'].'");';
                ?>
                var graph_id1 = getGraphId(mms_idx, dta_type, dta_no);

                var dta_data_url_host = '<?=$row['data'][$j]['id']['dta_data_url_host']?>';
                var dta_data_url_path = '<?=$row['data'][$j]['id']['dta_data_url_path']?>';
                var dta_data_url_file = '<?=$row['data'][$j]['id']['dta_data_url_file']?>';
                var mms_idx = <?=$row['data'][$j]['id']['mms_idx']?>;
                var mms_name = '<?=$row['data'][$j]['id']['mms_name']?>';
                var dta_type = '<?=$row['data'][$j]['id']['dta_type']?>';
                var dta_no = '<?=$row['data'][$j]['id']['dta_no']?>';
                var graph_type = '<?=$row['data'][$j]['type']?>';
                var graph_line = '<?=$row['data'][$j]['dashStyle']?>';
                var mbd_idx = <?=$row['mbd_idx']?>;
                var graph_name = '<?=$row['data'][$j]['id']['graph_name']?>';
                var graph_id1 = getGraphId(mms_idx, dta_type, dta_no);
                // console.log(i+' 호출 시:'+graph_id1);

                // 그래프 배열 선언
                graphs<?=$row['mbd_idx']?>[<?=$j?>] = {
                    dta_data_url_host: dta_data_url_host,
                    dta_data_url_path: dta_data_url_path,
                    dta_data_url_file: dta_data_url_file,
                    mms_idx: mms_idx,
                    mms_name: mms_name,
                    dta_type: dta_type,
                    dta_no: dta_no,
                    graph_type: graph_type,
                    graph_line: graph_line,
                    graph_name: graph_name,
                    graph_id: graph_id1
                };
                // MMS names
                // mmses<?=$row['mbd_idx']?>[<?=$j?>] = decodeURIComponent(mms_name);
                mmses<?=$row['mbd_idx']?>[<?=$j?>] = mms_name;

                // mms_count (for displaying mms_name in case multi mmses)
                mms_count<?=$row['mbd_idx']?> = <?=sizeof($row['chr_mms_idxs'])?>;

                var dta_item = '<?=$row['sried']['dta_item']?>';   // 일,주,월,년,분,초
                var dta_file = (dta_item=='minute'||dta_item=='second') ? '' : '.sum'; // measure.php(그룹핑), measure.sum.php(일자이상)
                var dta_unit = '<?=($row['sried']['dta_unit'])?$row['sried']['dta_unit']:1?>';   // 10,20,30,60
                dta_loading('show',mbd_idx);

                <?php
                // the latest time range from now exact!
                $en_timestamp = strtotime($row['sried']['en_date'].' '.$row['sried']['en_time']);
                $st_timestamp = strtotime($row['sried']['st_date'].' '.$row['sried']['st_time']);
                $diff_timestamp = $en_timestamp - $st_timestamp;
                $row['df_seconds'][$row['mbd_idx']][$j] = $diff_timestamp;
                // seconds per unit * item_count = interval seconds
                $row['df_unit'][$row['mbd_idx']][$j] = $seconds[$row['sried']['dta_item']][0]*$row['sried']['dta_unit'];
                
                $row['en_date'] = date("Y-m-d",G5_SERVER_TIME);
                $row['en_time'] = date("H:i:s",G5_SERVER_TIME);
                $row['st_date'] = date("Y-m-d",G5_SERVER_TIME-$diff_timestamp);
                $row['st_time'] = date("H:i:s",G5_SERVER_TIME-$diff_timestamp);
                ?>
                var en_date = '<?=$row['en_date']?>';
                var en_time = '<?=$row['en_time']?>';
                var st_date = '<?=$row['st_date']?>';
                var st_time = '<?=$row['st_time']?>';
                // time range from db stored data. uncomment for testing below.
                // var en_date = '<?=$row['sried']['en_date']?>';
                // var en_time = '<?=$row['sried']['en_time']?>';
                // var st_date = '<?=$row['sried']['st_date']?>';
                // var st_time = '<?=$row['sried']['st_time']?>';

                var dta_url = '//'+dta_data_url_host+dta_data_url_path+'/'+dta_data_url_file+'?token=1099de5drf09'
                                +'&mms_idx='+mms_idx+'&dta_type='+dta_type+'&dta_no='+dta_no
                                +'&st_date='+st_date+'&st_time='+st_time+'&en_date='+en_date+'&en_time='+en_time
                                +'&graph_name='+graph_name+'&graph_id='+graph_id1+'&mbd_idx='+mbd_idx;
                console.log(dta_url);
                // console.log(decodeURIComponent(graph_name));

                Highcharts.getJSON(
                    dta_url,
                    drawChart
                );
            <?php
            }
            // for loop as many times as graph count.
            ?>

            // <?php
            // // -----------------------------------------------------------------------
            // // refresh periodically (10 seconds, 60 seconds,...)
            // // for loop as many times as graph count.
            // for($j=0;$j<sizeof($row['data']);$j++) {
            //     // echo 'console.log("'.$row['data'][$j]['id']['dta_data_url'].'");';
            // ?>
            //     // Set loop interval 
            //     interval_<?=$row['mbd_idx']?>_<?=$j?> = setInterval(callChart_<?=$row['mbd_idx']?>_<?=$j?>, <?=$row['df_unit'][$row['mbd_idx']][$j]*1000?>);
            //     function callChart_<?=$row['mbd_idx']?>_<?=$j?>() {
            //         // console.log( <?=$row['mbd_idx']?>+'-'+<?=$j?> );
            //         // console.log(decodeURIComponent('<?=$row['data'][$j]['id']['graph_name']?>'));

            //         var df_seconds = <?=$row['df_seconds'][$row['mbd_idx']][$j]?>;
            //         var df_unit = <?=$row['df_unit'][$row['mbd_idx']][$j]?>;
            //         // console.log('interval seconds: ' + df_unit);

            //         var dta_data_url = '<?=$row['data'][$j]['id']['dta_data_url']?>';
            //         var dta_json_file = '<?=$row['data'][$j]['id']['dta_json_file']?>';
            //         var dta_group = '<?=$row['data'][$j]['id']['dta_group']?>';
            //         var mms_idx = <?=$row['data'][$j]['id']['mms_idx']?>;
            //         var dta_type = '<?=$row['data'][$j]['id']['dta_type']?>';
            //         var dta_no = '<?=$row['data'][$j]['id']['dta_no']?>';
            //         var shf_no = '<?=$row['data'][$j]['id']['shf_no']?>';
            //         var dta_mmi_no = '<?=$row['data'][$j]['id']['dta_mmi_no']?>';
            //         var dta_defect = '<?=$row['data'][$j]['id']['dta_defect']?>';
            //         var dta_defect_type = '<?=$row['data'][$j]['id']['dta_defect_type']?>'; // 1,2,3,4...
            //         var dta_code = '<?=$row['data'][$j]['id']['dta_code']?>';    // only if err, pre
            //         var graph_type = '<?=$row['data'][$j]['type']?>';
            //         var graph_line = '<?=$row['data'][$j]['dashStyle']?>';
            //         var mbd_idx = <?=$row['mbd_idx']?>;
            //         var graph_name = '<?=$row['data'][$j]['id']['graph_name']?>';
            //         var graph_id1 = getGraphId(dta_json_file, dta_group, mms_idx, dta_type, dta_no, shf_no, dta_mmi_no, dta_defect, dta_defect_type, dta_code);

            //         // Set en_date and st_date for loop call ------
            //         var date1 = new Date(); // current date
            //         // console.log( date1.getFullYear().toString()+"-"
            //         //                 +((date1.getMonth()+1).toString().length==2?(date1.getMonth()+1).toString():"0"+(date1.getMonth()+1).toString())+"-"
            //         //                 +(date1.getDate().toString().length==2?date1.getDate().toString():"0"+date1.getDate().toString())+" "
            //         //                 +(date1.getHours().toString().length==2?date1.getHours().toString():"0"+date1.getHours().toString())+":"
            //         //                 +(date1.getMinutes().toString().length==2?date1.getMinutes().toString():"0"+date1.getMinutes().toString())+":"
            //         //                 +(date1.getSeconds().toString().length==2?date1.getSeconds().toString():"0"+date1.getSeconds().toString()) );
            //         var en_date = date1.getFullYear().toString()+"-"
            //                         +((date1.getMonth()+1).toString().length==2?(date1.getMonth()+1).toString():"0"+(date1.getMonth()+1).toString())+"-"
            //                         +(date1.getDate().toString().length==2?date1.getDate().toString():"0"+date1.getDate().toString());
            //         var en_time = (date1.getHours().toString().length==2?date1.getHours().toString():"0"+date1.getHours().toString())+":"
            //                         +(date1.getMinutes().toString().length==2?date1.getMinutes().toString():"0"+date1.getMinutes().toString())+":"
            //                         +(date1.getSeconds().toString().length==2?date1.getSeconds().toString():"0"+date1.getSeconds().toString());
            //         // console.log('cur dt: ' + en_date + ' ' + en_time);
            //         // console.log(date1 + '에서 ' + df_seconds+'초 전은... ');
            //         date1.setSeconds(date1.getSeconds() - df_seconds); // some amount of seconds prior..
            //         // console.log( date1.getFullYear().toString()+"-"
            //         //                 +((date1.getMonth()+1).toString().length==2?(date1.getMonth()+1).toString():"0"+(date1.getMonth()+1).toString())+"-"
            //         //                 +(date1.getDate().toString().length==2?date1.getDate().toString():"0"+date1.getDate().toString())+" "
            //         //                 +(date1.getHours().toString().length==2?date1.getHours().toString():"0"+date1.getHours().toString())+":"
            //         //                 +(date1.getMinutes().toString().length==2?date1.getMinutes().toString():"0"+date1.getMinutes().toString())+":"
            //         //                 +(date1.getSeconds().toString().length==2?date1.getSeconds().toString():"0"+date1.getSeconds().toString()) );
            //         var st_date = date1.getFullYear().toString()+"-"
            //                         +((date1.getMonth()+1).toString().length==2?(date1.getMonth()+1).toString():"0"+(date1.getMonth()+1).toString())+"-"
            //                         +(date1.getDate().toString().length==2?date1.getDate().toString():"0"+date1.getDate().toString());
            //         var st_time = (date1.getHours().toString().length==2?date1.getHours().toString():"0"+date1.getHours().toString())+":"
            //                         +(date1.getMinutes().toString().length==2?date1.getMinutes().toString():"0"+date1.getMinutes().toString())+":"
            //                         +(date1.getSeconds().toString().length==2?date1.getSeconds().toString():"0"+date1.getSeconds().toString());
            //         // console.log('start dt: ' + st_date + ' ' + st_time);
            //         // console.log( '---------' );

            //         var dta_item = '<?=$row['sried']['dta_item']?>';   // 일,주,월,년,분,초
            //         var dta_file = (dta_item=='minute'||dta_item=='second') ? '' : '.sum'; // measure.php(그룹핑), measure.sum.php(일자이상)
            //         var dta_unit = '<?=($row['sried']['dta_unit'])?$row['sried']['dta_unit']:1?>';   // 10,20,30,60
            //         dta_loading('show',mbd_idx);

            //         var dta_url = '//'+dta_data_url+'/'+dta_json_file+dta_file+'.php?token=1099de5drf09'
            //                         +'&mms_idx='+mms_idx+'&dta_group='+dta_group+'&shf_no='+shf_no+'&dta_mmi_no='+dta_mmi_no
            //                         +'&dta_type='+dta_type+'&dta_no='+dta_no
            //                         +'&dta_defect='+dta_defect+'&dta_defect_type='+dta_defect_type
            //                         +'&dta_code='+dta_code
            //                         +'&dta_item='+dta_item+'&dta_unit='+dta_unit
            //                         +'&st_date='+st_date+'&st_time='+st_time+'&en_date='+en_date+'&en_time='+en_time
            //                         +'&graph_name='+graph_name+'&graph_id='+graph_id1+'&mbd_idx='+mbd_idx;
            //         // console.log(dta_url);
            //         // console.log(decodeURIComponent(graph_name));

            //         // Highcharts.getJSON(
            //         //     dta_url,
            //         //     drawChart
            //         // );

            //     }

            // <?php
            // }
            // // for loop as many times as graph count.
            // ?>
        </script>
        <!--================ // 챠트 부분 ==================-->
    </div>
    </div>
    <i class="fa fa-pencil-square grid_edit grid_mod" aria-hidden="true" style="display:none;"></i>
    <i class="fa fa-window-close grid_edit grid_del" aria-hidden="true" style="display:none;"></i>
</div><!--//.pkr-item-->
<?php 
}
$pos_json .= ']';
// $pos_arr = json_decode($pos_json,true);
// print_r2($pos_arr);
echo '</div>'.PHP_EOL;//.pkr
}
// 불러올 위젯이 없는 경우
else {
?>
<div class="dash_empty" style="display:no ne;">
    <p>대시보드 데이터가 없습니다.</p>
</div>
<?php
}
?>

<?php include_once('./index_1_packery_script.php'); ?>
<script>
<?php if($result->num_rows){ ?>
$(function(){
    //개별 그리드 삭제
    $('.grid_del').on('click',function(){
        if(!confirm("관련 데이터의 복구가 불가능 하오니\n신중하게 결정하세요.\n선택하신 데이터를 정말로 삭제하시겠습니까?")){
            return false;
        }
        var ajax_url = g5_user_admin_ajax_url+'/grid_del.php';
        var mta_idx = <?=$cur_mta_idx?>;
        var dsg_idx = $(this).parent().attr('dsg_idx');
    
        $.ajax({
            type: 'POST',
            url: ajax_url,
            // dataType: 'text',
            timeout: 30000,
            data: {'mta_idx': mta_idx, 'dsg_idx': dsg_idx},
            success: function(res){
                location.reload();
            },
            error: function(req){
                alert('Status: ' + req.status + ' \n\rstatusText: ' + req.statusText + ' \n\rresponseText: ' + req.responseText);
            }
        });
    });
    
    var grid_focus;
    var mta_idx = <?=$cur_mta_idx?>; 
    // 그리드 편집모드 버튼 클릭
    $('.grid_mod').on('click',function(){
        grid_focus = $(this).parent();
        $(this).addClass('focus');
        $(this).siblings('.pkr-cont').addClass('focus');
        $('#dsm').css('display','flex');
    });
    //모달 닫기 버튼 클릭
    $('#dsm_bg,#dsm_close').on('click',function(){
        grid_focus.find('.grid_mod').removeClass('focus');
        grid_focus.find('.pkr-cont').removeClass('focus');
        $('#dsm').css('display','none');

        grid_focus = null;
    });
});
<?php } ?>
//대시보드 타이틀 옆에 표시된 편집모드 토글버튼
$('.ds_edit_btn').on('click',function(){
    if($(this).hasClass('focus')){
        $(this).removeClass('focus');
        $('.bs_edit').hide();
        $('.grid_edit').hide();
    }
    else{
        $(this).addClass('focus');
        $('.bs_edit').show();
        $('.grid_edit').show();
    }
});
</script>

<script>
// 상세보기로 이동
$(document).on('click','.graph_view, .chart_view',function(e){
    e.preventDefault();
    var my_mbd_idx = $(this).closest('div[mbd_idx]').attr('mbd_idx');
    self.location.href = './system/graph.php?mbd_idx='+my_mbd_idx;
});

// 이름변경
$(document).on('click','.graph_name_change',function(e){
    e.preventDefault();
    var my_mbd_idx = $(this).closest('div[mbd_idx]').attr('mbd_idx');
    var my_mbd_title = $(this).closest('div[mbd_idx]').find('.graph_title').attr('title');
    var my_mbd_title_input = '<input class="input_name" style="width:89%;height:19px;" value="'+my_mbd_title+'">';
    $(this).closest('div[mbd_idx]').find('.graph_title').empty().append(my_mbd_title_input).find('input').select().focus();
    $('.graph_setting').hide(); // 다른 모든 설정 팝오버 숨김
    $('.graph_setting').closest('div').find('.chart_setting i').removeClass('fa-times').addClass('fa-gear');

});
// 이름변경 enterkey - action for change by ajax
$(document).on('keyup focusout','.graph_title input',function(e){
    e.preventDefault();
    var my_title_span = $(this).closest('div[mbd_idx]').find('.graph_title');
    var my_mbd_idx = $(this).closest('div[mbd_idx]').attr('mbd_idx');
    var my_mbd_title = $(this).closest('div[mbd_idx]').find('.graph_title').attr('title');
    if (e.type == 'focusout') {
        my_title_span.text(my_mbd_title);
    }
    else if(e.keyCode == 13) {
        // alert('Enter key was pressed.');
        var chg_mbd_title = $(this).val();  // due to blur event bubbling, unique name required. 
        var blur = false;
        //-- 디버깅 Ajax --//
        $.ajax({
            url:g5_user_admin_ajax_url+'/dash.php',
            data:{"aj":"tit","mbd_idx":my_mbd_idx,"mbd_title":chg_mbd_title},
            dataType:'json', timeout:10000, beforeSend:function(){}, success:function(res){
                // console.log(res);
                //var prop1; for(prop1 in res.rows) { console.log( prop1 +': '+ res.rows[prop1] ); }
                if(res.result == true) {
                    alert(res.msg);
                    // time delay is needed.
                    setTimeout(function(){
                        if (!blur) {
                            blur = true;
                            my_title_span.text(chg_mbd_title);
                            my_title_span.attr('title',chg_mbd_title);
                            blur = false;
                        }
                    }, 150);
                }
                else {
                    alert(res.msg);
                    my_title_span.text(my_mbd_title);
                }
            },
            error:function(xmlRequest) {
                alert('Status: ' + xmlRequest.status + ' \n\rstatusText: ' + xmlRequest.statusText 
                    + ' \n\rresponseText: ' + xmlRequest.responseText);
            } 
        });
    }
});

// 그래프 설정
$(document).on('click','.chart_setting',function(e){
    e.preventDefault();
    var my_graph_setting = $(this).closest('div.pkr-box').find('.graph_setting');
    if( my_graph_setting.is(':hidden') ) {
        $('.graph_setting').hide(); // 다른 모든 설정 팝오버 숨김
        $('.graph_setting').closest('div.pkr-box').find('.chart_setting i').removeClass('fa-times').addClass('fa-gear');
        my_graph_setting.show();
        $(this).find('i').removeClass('fa-gear').addClass('fa-times');
    }
    else {
        my_graph_setting.hide();
        $(this).find('i').removeClass('fa-times').addClass('fa-gear');
    }
});

// 그래프 이동
$(document).on('click','.graph_move',function(e){
    e.preventDefault();
    if($(this).hasClass('blink')) {
        $(this).removeClass('blink');
    }
    else {
        $('a.graph_move').removeClass('blink');
        $(this).addClass('blink');
    }
});
$(document).on('click','.li_dash_submenu a.gnb_2da',function(e){
    e.preventDefault();
    var href = $(this).attr('href');
    // 깜빡이는 놈이 있으면 위젯이동해 주고 페이지 링크이동
    var mbd_idx = $('a.graph_move.blink').closest('div[mbd_idx]').attr('mbd_idx');
    var mta_idx = $(this).closest('li').attr('mta_idx');
    if( mbd_idx ) {
        // alert(mbd_idx+' move to '+mta_idx);
        // 디버깅 Ajax ---------- 동기방식으로 처리해 줘야 함(async:false)
        $.ajax({
            url:g5_user_admin_ajax_url+'/dash.php',
            data:{"aj":"mv1","mbd_idx":mbd_idx,"mta_idx":mta_idx},
            async:false,dataType:'json', timeout:10000, beforeSend:function(){}, success:function(res){
        //$.getJSON(g5_board_skin_url+'/ajax.calendar_update.php',{"w":"u1","data_serialized":data_serialized},function(res){
            if(res.result == true) {
                location.href = href;
            }
            else {
                alert(res.msg);
            }
            }, error:this_ajax_error    // 디버깅 Ajax ----------
        });
    }
    else {
        location.href = href;
    }
});

// 그래프 제거하기
$(document).on('click','.graph_delete',function(e){
    e.preventDefault();
    my_mbd_idx = $(this).closest('div[mbd_idx]').attr('mbd_idx');
    $(this).closest('div[mbd_idx]').find('.chart_setting').trigger('click'); // 설정팝오버닫기
    if(confirm('선택한 그래프를 삭제하시겠습니까?')) {

        var ajax_url = g5_user_admin_ajax_url+'/grid_del.php';
        var mta_idx = <?=$cur_mta_idx?>;
        var dsg_idx = $(this).closest('div.pkr-item').attr('dsg_idx');
        $.ajax({
            type: 'POST',
            url: ajax_url,
            // dataType: 'text',
            timeout: 30000,
            data: {'mta_idx': mta_idx, 'dsg_idx': dsg_idx},
            success: function(res){
                location.reload();
            },
            error: function(req){
                alert('Status: ' + req.status + ' \n\rstatusText: ' + req.statusText + ' \n\rresponseText: ' + req.responseText);
            }
        });

        // //-- 디버깅 Ajax --//
        // $.ajax({
        //     url:g5_user_admin_ajax_url+'/dash.php',
        //     data:{"aj":"del","mbd_idx":my_mbd_idx},
        //     dataType:'json', timeout:10000, beforeSend:function(){}, success:function(res){
        //         // console.log(res);
        //         //var prop1; for(prop1 in res.rows) { console.log( prop1 +': '+ res.rows[prop1] ); }
        //         if(res.result == true) {
        //             $('div[mbd_idx='+my_mbd_idx+']').toggle('scale'); // drop, fold, highlight, scale, slide
        //         }
        //         else {
        //             alert(res.msg);
        //         }
        //     },
        //     error:function(xmlRequest) {
        //         alert('Status: ' + xmlRequest.status + ' \n\rstatusText: ' + xmlRequest.statusText 
        //             + ' \n\rresponseText: ' + xmlRequest.responseText);
        //     } 
        // });

    }
});
</script>

<?php
include_once ('./index_2_dash_modal.php');
include_once ('./_tail.php');
?>
