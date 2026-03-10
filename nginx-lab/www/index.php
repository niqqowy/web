<?php

session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(44, 54, 61, 0.1);
        }
        
        h1 {
            color: #2c363d;
            border-bottom: 2px solid #2c363d;
            padding-bottom: 10px;
        }
        
        .data-box {
            background: #f8f9fa;
            border-left: 4px solid #2c363d;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .data-item {
            margin: 10px 0;
            padding: 5px;
            border-bottom: 1px dashed #ced4da;
        }
        
        .data-label {
            font-weight: bold;
            color: #2c363d;
            display: inline-block;
            width: 150px;
        }
        
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: #721c24;
        }
        
        .error-item {
            margin: 5px 0;
        }
        
        .cookie-info {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: #0c5460;
        }
        
        .nav-links {
            margin: 20px 0;
            text-align: center;
        }
        
        .nav-links a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: #2c363d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            background: #495057;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 54, 61, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Данные из сессии</h1>
        
  
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-box">
                <h3>Ошибки валидации:</h3>
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <div class="error-item">• <?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if(isset($_COOKIE['last_username'])): ?>
            <div class="cookie-info">
                <strong>Из куки:</strong> Последний пользователь: <?= htmlspecialchars($_COOKIE['last_username']) ?>
            </div>
        <?php endif; ?>
   
        <?php if(isset($_SESSION['username'])): ?>
            <div class="data-box">
                <h3>Последние введенные данные:</h3>
                <div class="data-item">
                    <span class="data-label">Имя:</span> 
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </div>
                <div class="data-item">
                    <span class="data-label">Email:</span> 
                    <?= htmlspecialchars($_SESSION['email']) ?>
                </div>
                <?php if(!empty($_SESSION['birthday'])): ?>
                <div class="data-item">
                    <span class="data-label">Дата рождения:</span> 
                    <?= htmlspecialchars($_SESSION['birthday']) ?>
                </div>
                <?php endif; ?>
                <div class="data-item">
                    <span class="data-label">Тема:</span> 
                    <?= htmlspecialchars($_SESSION['theme']) ?>
                </div>
                <div class="data-item">
                    <span class="data-label">Материалы:</span> 
                    <?= $_SESSION['materials'] === 'yes' ? 'Да' : 'Нет' ?>
                </div>
                <div class="data-item">
                    <span class="data-label">Формат:</span> 
                    <?= htmlspecialchars($_SESSION['type']) ?>
                </div>
            </div>
            
     
            <form method="POST" action="clear_session.php" style="display: inline;">
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Очистить сессию</button>
            </form>
        <?php else: ?>
            <p>Данных в сессии пока нет. Заполните форму!</p>
        <?php endif; ?>
        
   
        <div class="nav-links">
            <a href="form.html">Заполнить форму</a>
            <a href="view.php">Посмотреть все данные</a>
        </div>
        
     
        <hr>
        
    </div>
</body>
</html>