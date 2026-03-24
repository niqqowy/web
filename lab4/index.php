<?php
session_start();
require_once 'UserInfo.php';

$userInfo = UserInfo::getInfo();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация на мастер-класс</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .block { border: 1px solid #ccc; margin-bottom: 20px; padding: 15px; border-radius: 5px; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; }
        .artwork { border-bottom: 1px solid #eee; padding: 10px 0; }
        .artwork-title { font-weight: bold; font-size: 1.1em; }
        .artwork-artist { color: #666; margin-top: 5px; }
        .artwork-medium { color: #888; font-size: 0.9em; margin-top: 5px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        input, select { padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        input[type="submit"] { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        input[type="submit"]:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="block">
        <h2>Регистрация на мастер-класс по художественным техникам</h2>
        
        <?php if (isset($_SESSION['registration'])): ?>
            <div style="background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <strong>✓ Вы успешно зарегистрированы!</strong><br>
                Имя: <?= htmlspecialchars($_SESSION['registration']['name']) ?><br>
                Email: <?= htmlspecialchars($_SESSION['registration']['email']) ?><br>
                Техника: <?= htmlspecialchars($_SESSION['registration']['technique']) ?><br>
                Время регистрации: <?= htmlspecialchars($_SESSION['registration']['time']) ?>
            </div>
        <?php endif; ?>
        
        <form action="save.php" method="post">
            <p>
                <label>Имя:</label><br>
                <input type="text" name="name" required size="40">
            </p>
            <p>
                <label>Email:</label><br>
                <input type="email" name="email" required size="40">
            </p>
            <p>
                <label>Выберите технику:</label><br>
                <select name="technique">
                    <option value="Масло">Масло</option>
                    <option value="Акварель">Акварель</option>
                    <option value="Акрил">Акрил</option>
                    <option value="Графика">Графика</option>
                    <option value="Коллаж">Коллаж</option>
                </select>
            </p>
            <p>
                <input type="submit" value="Зарегистрироваться">
            </p>
        </form>
    </div>
    
    <div class="block">
        <h3>Информация о пользователе</h3>
        <p><strong>IP адрес:</strong> <?= htmlspecialchars($userInfo['ip']) ?></p>
        <p><strong>User Agent:</strong> <?= htmlspecialchars($userInfo['user_agent']) ?></p>
        <p><strong>Время посещения:</strong> <?= htmlspecialchars($userInfo['time']) ?></p>
        
        <?php if (isset($_COOKIE['last_submission'])): ?>
            <p><strong>Последняя регистрация:</strong> <?= htmlspecialchars($_COOKIE['last_submission']) ?></p>
        <?php endif; ?>
    </div>
    
    <div class="block">
        <h3>Произведения искусства (из API)</h3>
        
        <?php if (isset($_SESSION['api_data'])): ?>
            <?php if (isset($_SESSION['api_data']['error'])): ?>
                <div class="error">
                    <strong>Ошибка API:</strong><br>
                    <?= htmlspecialchars($_SESSION['api_data']['error']) ?>
                </div>
            <?php elseif (isset($_SESSION['api_data']['data']) && !empty($_SESSION['api_data']['data'])): ?>
                <?php foreach ($_SESSION['api_data']['data'] as $artwork): ?>
                    <div class="artwork">
                        <div class="artwork-title">
                            <?= htmlspecialchars($artwork['title'] ?? 'Без названия') ?>
                        </div>
                        <?php if (!empty($artwork['artist_title'])): ?>
                            <div class="artwork-artist">
                                Художник: <?= htmlspecialchars($artwork['artist_title']) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($artwork['medium_display'])): ?>
                            <div class="artwork-medium">
                                Техника: <?= htmlspecialchars($artwork['medium_display']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <p><small>Всего произведений в ответе: <?= count($_SESSION['api_data']['data']) ?></small></p>
            <?php else: ?>
                <p>Нет данных из API</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Данные API не загружены. Заполните форму выше.</p>
        <?php endif; ?>
    </div>
    
    <div class="block">
        <button onclick="refreshData()">Обновить данные из API</button>
        <div id="refresh-result" style="margin-top: 10px;"></div>
    </div>
    
    <script>
        function refreshData() {
            const resultDiv = document.getElementById('refresh-result');
            resultDiv.innerHTML = '<p>Загрузка...</p>';
            
            fetch('api.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        resultDiv.innerHTML = '<div class="error">Ошибка: ' + data.error + '</div>';
                    } else if (data.data) {
                        let html = '<h4>Обновленные данные:</h4>';
                        data.data.forEach(artwork => {
                            html += '<div class="artwork">';
                            html += '<div class="artwork-title">' + (artwork.title || 'Без названия') + '</div>';
                            if (artwork.artist_title) {
                                html += '<div class="artwork-artist">Художник: ' + artwork.artist_title + '</div>';
                            }
                            html += '</div>';
                        });
                        resultDiv.innerHTML = html;
                        
                       
                        location.reload();
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = '<div class="error">Ошибка загрузки: ' + error + '</div>';
                });
        }
    </script>
</body>
</html>