<?php
session_start();
require_once 'ApiClient.php';


setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['registration'] = [
        'name'      => $_POST['name'] ?? '',
        'email'     => $_POST['email'] ?? '',
        'technique' => $_POST['technique'] ?? '',
        'time'      => date('Y-m-d H:i:s')
    ];
}


$api = new ApiClient();
$url = 'https://api.artic.edu/api/v1/artworks?limit=10&fields=id,title,artist_title,medium_display';

$cacheFile = 'api_cache.json';
$cacheTtl = 300; 

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    
    $apiData = json_decode(file_get_contents($cacheFile), true);
} else {
    
    $apiData = $api->request($url);
    
    
    if (!isset($apiData['error'])) {
        file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}

$_SESSION['api_data'] = $apiData;


header('Location: index.php');
exit;