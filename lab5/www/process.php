<?php
require_once 'db.php';
require_once 'MasterClass.php';

$masterClass = new MasterClass($pdo);

$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$birth_date = $_POST['birth_date'] ?? '';
$topic = htmlspecialchars($_POST['topic'] ?? '');
$materials_included = isset($_POST['materials_included']) ? 1 : 0;
$format = $_POST['format'] ?? '';

$errors = [];
if (empty($name)) $errors[] = 'Имя обязательно';
if (empty($birth_date)) $errors[] = 'Дата рождения обязательна';
if (empty($topic)) $errors[] = 'Тема обязательна';
if (empty($format)) $errors[] = 'Формат участия обязателен';

if (!empty($errors)) {
    header('Location: form.html?error=' . urlencode(implode(', ', $errors)));
    exit;
}

$masterClass->add($name, $birth_date, $topic, $materials_included, $format);

header('Location: index.php');
exit;