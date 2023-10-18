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


// 비동기적으로 이미지 다운로드 및 저장
$images = array();
$downloadedCount = 0;
foreach ($imageUrls as $url => $filename) {
    $path = G5_DATA_PATH.'/tmp/'.$filename; // 저장할 경로와 파일명 설정 (여기서는 현재 디렉토리의 temp 폴더에 저장)
    
    // 외부 사이트에서 이미지 다운로드 (비동기적)
    file_put_contents($path, fopen($url, 'r'));
    
    // 비동기적으로 이미지가 다운로드되었음을 카운트
    $downloadedCount++;
    
    // 압축할 이미지 정보 추가
    $images[] = array(
        'path' => $path,
        'name' => basename($filename)
    );
}

// 모든 이미지가 다운로드될 때까지 대기
while ($downloadedCount < count($imageUrls)) {
	sleep(1); // 1초 대기 후 재확인 (필요한 경우 대기 시간 조정 가능)
	clearstatcache(); // 파일 상태 캐시 초기화하여 변경 사항 확인
}

// Zip 파일 생성 함수
function createZip($files, $zipname) {
	$zip = new ZipArchive;
	
	// 압축 파일 열기 및 생성 (CREATE 플래그 사용)
	if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
		foreach ($files as $file) {
			$zip->addFile($file['path'], $file['name']);
		}
		
		$zip->close();
		
        return true;
	} else {
        return false;
	}
}

// 압축된 ZIP 파일 이름 설정
$zipName = G5_DATA_PATH.'/tmp/mto_idx_'.$mto_idx.'.zip';

// Zip 파일 생성 및 다운로드
if (createZip($images, $zipName)) {
	header('Content-Type: application/zip');
	header('Content-disposition: attachment; filename=' . basename($zipName));
	header('Content-Length: ' . filesize($zipName));
	readfile($zipName);
	
	unlink($zipName); // 압축파일 삭제
	
	foreach ($images as $image) {
	    unlink($image['path']); // 각각의 이미지파일 삭제 
	}
} else {
	echo "Failed to create zip file.";
}
