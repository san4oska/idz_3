<?php
include 'connection.php';

$vendor_id = $_GET['vendor_id'];
$format = $_GET['format'] ?? 'text';

$stmt = $pdo->prepare("SELECT * FROM items WHERE FID_Vendor = ?");
$stmt->execute([$vendor_id]);
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

} elseif ($format === 'jsonp') {
    // JSONP-відповідь: виклик функції, переданої в параметрі callback
    $callback = $_GET['callback'] ?? 'callback';
    header('Content-Type: application/javascript');
    echo $callback . '(' . json_encode($items) . ');';

} else {
    foreach ($items as $item) {
        echo "<p>{$item['name']} - {$item['price']}$</p>";
    }
}
?>
