<?php
require_once '/var/www/html/QueueManager.php';

$qm = new QueueManager();

$processMessage = function($data) {
    echo "------------------------------------------\n";
    echo "📥 Получено: " . json_encode($data) . "\n";
    echo "⚙️  Обработка...\n";
    sleep(2);
    file_put_contents('/var/www/html/processed.log', json_encode($data) . PHP_EOL, FILE_APPEND);
    echo "✅ Готово\n";
    echo "------------------------------------------\n";
};

echo "🚀 Worker запущен...\n";
$qm->consume($processMessage);