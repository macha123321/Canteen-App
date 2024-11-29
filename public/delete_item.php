<?php
include '../config/db.php';

$itemId = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM menuitems WHERE item_id = :item_id");
$stmt->execute(['item_id' => $itemId]);

header('Location: Canteen_Admin.php');
exit();
?>
