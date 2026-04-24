<?php
require_once '/var/www/html/QueueManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'Unknown';
    $group = $_POST['group'] ?? 'Unknown';
    $timestamp = date('Y-m-d H:i:s');

    $data = [
        'name' => $name,
        'group' => $group,
        'timestamp' => $timestamp,
        'action' => 'registration'
    ];

    try {
        $qm = new QueueManager();
        $qm->publish($data);
        echo "<h1>✅ Данные успешно отправлены в очередь!</h1>";
        echo "<p><a href='http://localhost:8080'>Вернуться назад</a></p>";
    } catch (Exception $e) {
        echo "<h1>❌ Ошибка: " . $e->getMessage() . "</h1>";
    }
} else {
    header("Location: http://localhost:8080");
}