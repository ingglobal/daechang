<div class="container">
    <div class="grid grid1">
        <iframe id="frame_02" src="<?=$g5_monitor_url?>/dashboard/uph.php?w=2&h=1" frameborder="0" scrolling="no"></iframe>
    </div>
    <div class="grid grid2">
        <iframe id="frame_02" src="<?=$g5_monitor_url?>/dashboard/production_today.php?w=2&h=1" frameborder="0" scrolling="no"></iframe>
    </div>
    <div class="grid grid3">
        <iframe id="frame_03" src="<?=$g5_monitor_url?>/dashboard/mms_call.php" frameborder="0" scrolling="no"></iframe>
    </div>
    <div class="grid grid4">
        <iframe id="frame_03" src="<?=$g5_monitor_url?>/dashboard/production_weekly.php?w=5&h=2" frameborder="0" scrolling="no"></iframe>
    </div>
    <!-- <div class="grid grid5"></div> -->
    <!-- <div class="grid grid6"></div> -->
</div>
<script>
// 10분에 한번 재로딩
setTimeout(function(e){
    self.location.reload();
},20000);
</script>