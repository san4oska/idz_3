<?php
include 'connection.php';

$category_id = $_GET['category_id'];
$format = $_GET['format'] ?? 'text';

$stmt = $pdo->prepare("SELECT * FROM items WHERE FID_Category = ?");
$stmt->execute([$category_id]);
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
