<?php
include 'connection.php';

$min_price = $_GET['min_price'];
$max_price = $_GET['max_price'];
$format = $_GET['format'] ?? 'text';

$stmt = $pdo->prepare("SELECT * FROM items WHERE price BETWEEN ? AND ?");
$stmt->execute([$min_price, $max_price]);
$items = $stmt->fetchAll();

if ($format === 'json') {
    header('Content-Type: application/json');
    echo json_encode($items);
} elseif ($format === 'xml') {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo "<items>";
    foreach ($items as $item) {
        echo "<item><name>{$item['name']}</name><price>{$item['price']}</price></item>";
    }
    echo "</items>";
} else {
    foreach ($items as $item) {
        echo "<p>{$item['name']} - {$item['price']}$</p>";
    }
}
?>
