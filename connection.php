<?php

$host = 'MySQL-8.2';
$dbname = 'lb_pdo_goods';
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Помилка підключення: " . $e->getMessage());
}
?>
