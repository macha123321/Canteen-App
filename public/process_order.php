<?php
include '../config/db.php';

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['orderItems']) || empty($data['orderItems'])) {
    echo json_encode(['success' => false, 'message' => 'No items in the order.']);
    exit();
}

// Start a transaction to ensure data integrity
$pdo->beginTransaction();

try {
    // Insert order items
    foreach ($data['orderItems'] as $orderItem) {
        $stmt = $pdo->prepare("INSERT INTO orders (item_id, quantity) VALUES (:item_id, :quantity)");
        $stmt->execute(['item_id' => $orderItem['item_id'], 'quantity' => $orderItem['quantity']]);

        // Update stock for the item
        $stmt = $pdo->prepare("UPDATE menuitems SET Stock = Stock - :quantity WHERE item_id = :item_id");
        $stmt->execute(['quantity' => $orderItem['quantity'], 'item_id' => $orderItem['item_id']]);
    }

    // Commit the transaction
    $pdo->commit();

    // Respond with success
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback if any error occurs
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error processing the order.']);
}
