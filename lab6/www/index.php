<?php
require_once 'vendor/autoload.php';

use App\GameRedis;

$game = new GameRedis();

if ($game->getTotalPlayers() == 0) {
    $game->setPlayerProfile('1', 'Alice', 5, 1200);
    $game->setPlayerProfile('2', 'Bob', 3, 800);
    $game->setPlayerProfile('3', 'Charlie', 7, 2100);
    $game->setPlayerProfile('4', 'Diana', 2, 450);
    
    $game->addScore('1', 150);
    $game->addScore('3', 300);
}

$topPlayers = $game->getTopPlayers(3);
$total = $game->getTotalPlayers();
$alice = $game->getPlayerProfile('1');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Игровой лидерборд (Redis)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; max-width: 600px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .profile { margin-top: 20px; padding: 10px; background: #e9ecef; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Топ игроков</h2>
    <p>Всего игроков: <?= $total ?></p>
    
    <table>
        <tr><th>Место</th><th>ID</th><th>Имя</th><th>Уровень</th><th>Очки</th></tr>
        <?php foreach ($topPlayers as $index => $player): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($player['id']) ?></td>
            <td><?= htmlspecialchars($player['name']) ?></td>
            <td><?= $player['level'] ?></td>
            <td><?= $player['score'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="profile">
        <h3>Профиль Alice</h3>
        <p>Имя: <?= htmlspecialchars($alice['name'] ?? 'Unknown') ?></p>
        <p>Уровень: <?= $alice['level'] ?? 0 ?></p>
        <p>Очки: <?= $alice['score'] ?? 0 ?></p>
    </div>
</body>
</html>