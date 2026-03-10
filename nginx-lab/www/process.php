<?php
session_start();

$errors = [];

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$theme = $_POST['theme'] ?? 'Home';
$materials = isset($_POST['materials']) ? 'yes' : 'no';
$type = $_POST['type'] ?? 'Offline';

if (empty($username)) {
    $errors[] = "Имя не может быть пустым";
}

if (empty($email)) {
    $errors[] = "Email не может быть пустым";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный формат email";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST; 
    header("Location: form.html");
    exit();
}

$safe_username = htmlspecialchars($username);
$safe_email = htmlspecialchars($email);
$safe_birthday = htmlspecialchars($birthday);
$safe_theme = htmlspecialchars($theme);
$safe_type = htmlspecialchars($type);

$_SESSION['username'] = $safe_username;
$_SESSION['email'] = $safe_email;
$_SESSION['birthday'] = $safe_birthday;
$_SESSION['theme'] = $safe_theme;
$_SESSION['materials'] = $materials;
$_SESSION['type'] = $safe_type;

$line = date('Y-m-d H:i:s') . ";" . 
        $username . ";" . 
        $email . ";" . 
        $birthday . ";" . 
        $theme . ";" . 
        $materials . ";" . 
        $type . "\n";

file_put_contents(__DIR__ . "/data.txt", $line, FILE_APPEND);
й
setcookie('last_username', $username, time() + (86400 * 30), '/'); 

header("Location: index.php");
exit();