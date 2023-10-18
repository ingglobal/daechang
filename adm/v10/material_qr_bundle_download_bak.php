<?php
include_once('./_common.php');

if (!extension_loaded('zip')) {
    die('zip 확장 모듈이 설치되어 있지 않습니다.');
}

// echo $mto_idx;

$sql = " SELECT bom.bom_part_no,moi_idx, moi_count
            FROM {$g5['material_order_item_table']} moi
            LEFT JOIN {$g5['bom_table']} bom ON moi.bom_idx = bom.bom_idx
        WHERE moi_status = 'ready'
            AND mto_idx = '{$mto_idx}'
";
$res = sql_query($sql);

$imageUrls = array();
for($i=0;$row=sql_fetch_array($res);$i++){
    // $moiArr[$row['moi_idx']] = $row['moi_count'];
    $imageUrls['http://chart.googleapis.com/chart?chs=140x140&cht=qr&chl='.G5_USER_ADMIN_MOBILE_URL.'/input_check.php?moi_cnt='.$row['moi_idx'].'_'.$row['moi_count']] = $row['bom_part_no'].'_moi_'.$row['moi_idx'].'_cnt_'.$row['moi_count'].'.png';
}

// Zip 파일 생성 함수
function createZip($files, $zipname) {
    $zip = new ZipArchive;
    if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
        foreach ($files as $file) {
            $zip->addFile($file['path'], $file['name']);
        }
        $zip->close();
        return true;
    } else {
        return false;
    }
}


// 이미지 다운로드 및 저장
$images = array();
foreach ($imageUrls as $url => $filename) {
    $path = G5_TMP_PATH.'/'.$filename;
    file_put_contents($filename, file_get_contents($url));
    $images[] = array('path' => $filename, 'name' => basename($filename));
}

// 이미지들을 압축한 후 다운로드할 zip 파일 이름 설정
$zipName = 'mto_idx_'.$mto_idx.'.zip';

// Zip 파일 생성 및 다운로드
if (createZip($images, $zipName)) {
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipName);
    header('Content-Length: ' . filesize($zipName));
    readfile($zipName);
} else {
    echo "Failed to create zip file.";
}