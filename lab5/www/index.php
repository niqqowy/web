<?php
require_once 'db.php';
require_once 'MasterClass.php';

$masterClass = new MasterClass($pdo);
$total = $masterClass->getCount();

$minAge = $_GET['min_age'] ?? null;
if ($minAge && is_numeric($minAge)) {
    $registrations = $masterClass->getByMinAge((int)$minAge);
} else {
    $registrations = $masterClass->getAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Список участников мастер-класса</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filter { margin-bottom: 20px; padding: 10px; background: #f9f9f9; border-radius: 5px; }
        .btn { display: inline-block; padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h2>Список участников мастер-класса</h2>
    <p><strong>Всего участников:</strong> <?= $total ?></p>

    <div class="filter">
        <form method="get">
            <label>Фильтр по возрасту (старше лет):</label>
            <input type="number" name="min_age" value="<?= htmlspecialchars($minAge ?? '') ?>">
            <button type="submit">Применить</button>
            <a href="index.php" class="btn">Сбросить</a>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Имя</th><th>Дата рождения</th><th>Возраст</th>
            <th>Тема</th><th>Материалы</th><th>Формат</th><th>Дата регистрации</th>
        </tr>
        <?php foreach ($registrations as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['birth_date'] ?></td>
            <td><?= date_diff(date_create($row['birth_date']), date_create('today'))->y ?></td>
            <td><?= htmlspecialchars($row['topic']) ?></td>
            <td><?= $row['materials_included'] ? 'Да' : 'Нет' ?></td>
            <td><?= htmlspecialchars($row['format']) ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="form.html" class="btn">Новая регистрация</a></p>
</body>
</html>