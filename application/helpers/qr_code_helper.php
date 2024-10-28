<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function generate_qr_code($data, $size = 200) {
    $data = urlencode($data);
    $baseUrl = "https://api.qrserver.com/v1/create-qr-code/";
    $params = [
        'size' => "{$size}x{$size}",
        'data' => $data
    ];

    $query = http_build_query($params);
    return $baseUrl . '?' . $query;
}
