<?php
// 사용자의 IP 주소 얻기
$user_ip = $_SERVER['REMOTE_ADDR'];

// IP 정보를 얻기 위한 API 호출 (예: ip-api.com)
$location_data = file_get_contents("http://ip-api.com/json/$user_ip");
$location_info = json_decode($location_data, true);

// 필요한 정보 추출
$location = $location_info['city'] . ', ' . $location_info['regionName'] . ', ' . $location_info['country'];

// Flask 서버로 전송할 데이터 설정
$data = json_encode([
    'ip' => $user_ip,
    'location' => $location
]);

// Flask 서버 URL
$flask_server_url = 'http://your-flask-server-ip:5000/send_info'; // Flask 서버의 IP 주소와 포트 입력

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => $data,
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($flask_server_url, false, $context);

if ($result === FALSE) {
    // 에러 처리
    echo 'Flask 서버로 정보를 전송할 수 없습니다.';
} else {
    echo '정보가 성공적으로 전송되었습니다.';
}
?>
