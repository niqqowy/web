<?php
header('Content-Type: application/json');
require_once 'ApiClient.php';

$api = new ApiClient();
$url = 'https://api.artic.edu/api/v1/artworks?limit=10&fields=id,title,artist_title,medium_display';
$cacheFile = 'api_cache.json';
$cacheTtl = 300; 

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    $data = json_decode(file_get_contents($cacheFile), true);
} else {
    $data = $api->request($url);
    if (!isset($data['error'])) {
        file_put_contents($cacheFile, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}

echo json_encode($data);