<?php

session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все сохраненные данные</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background: #2c363d;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #ced4da;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .stats {
            background: #e9ecef;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .nav-link {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background: #2c363d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: #495057;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 54, 61, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Все сохраненные данные</h1>
        
        <?php
        
        $data_file = __DIR__ . '/data.txt';
        
        if (file_exists($data_file)) {
            $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $total_records = count($lines);
            
            if ($total_records > 0) {
              
                echo "<div class='stats'>";
                echo "📊 Всего записей: <strong>$total_records</strong>";
                echo "</div>";
                
               
                echo "<table>";
                echo "<tr>
                        <th>#</th>
                        <th>Дата/время</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Дата рождения</th>
                        <th>Тема</th>
                        <th>Материалы</th>
                        <th>Формат</th>
                      </tr>";
                
                foreach ($lines as $index => $line) {
                    
                    $data = explode(';', $line);
                    
                   
                    if (count($data) >= 7) {
                        list($datetime, $name, $email, $birthday, $theme, $materials, $type) = $data;
                        
                        echo "<tr>";
                        echo "<td>" . ($index + 1) . "</td>";
                        echo "<td>" . htmlspecialchars($datetime) . "</td>";
                        echo "<td>" . htmlspecialchars($name) . "</td>";
                        echo "<td>" . htmlspecialchars($email) . "</td>";
                        echo "<td>" . htmlspecialchars($birthday) . "</td>";
                        echo "<td>" . htmlspecialchars($theme) . "</td>";
                        echo "<td>" . ($materials === 'yes' ? '✅ Да' : '❌ Нет') . "</td>";
                        echo "<td>" . htmlspecialchars($type) . "</td>";
                        echo "</tr>";
                    }
                }
                
                echo "</table>";
            } else {
                echo "<div class='no-data'>Файл пуст. Нет сохраненных данных.</div>";
            }
        } else {
            echo "<div class='no-data'>📭 Файл данных еще не создан. Отправьте форму, чтобы создать первую запись.</div>";
        }
        ?>
        
        
        <form method="POST" action="clear_data.php" style="display: inline;" onsubmit="return confirm('Вы уверены, что хотите удалить все данные?');">
            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-right: 10px;">🗑️ Очистить все данные</button>
        </form>
        
        <a href="index.php" class="nav-link">← На главную</a>
        <a href="form.html" class="nav-link">📝 Заполнить форму</a>
    </div>
</body>
</html>