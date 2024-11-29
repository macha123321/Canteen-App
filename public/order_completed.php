<?php
session_start();
include '../config/db.php';

$orderSummary = isset($_SESSION['last_order']) ? $_SESSION['last_order'] : [];
$total = 0;

if (!empty($orderSummary)) {
    $total = calculateTotal($orderSummary, $pdo);
}

function calculateTotal($cart, $pdo) {
    $total = 0;
    foreach ($cart as $item_id => $item) {
        $stmt = $pdo->prepare("SELECT price FROM menuitems WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $price = $stmt->fetchColumn();
        $total += $price * $item['quantity'];
    }
    return $total;
}
?>

<?php include '../templates/header.php'; ?>

<header>
    <h1>Order Completed</h1>
</header>

<div class="container">
    <h2>Thank you for your order!</h2>
    
    <?php if (!empty($orderSummary)): ?>
        <h3>Order Summary</h3>
        <ul>
            <?php foreach ($orderSummary as $item_id => $item): ?>
                <li>
                    <strong><?= htmlspecialchars($item['item_name']); ?></strong> 
                    (Quantity: <?= $item['quantity']; ?>)
                </li>
            <?php endforeach; ?>
        </ul>
        <h3>Total: £<?= number_format($total, 2); ?></h3>
    <?php else: ?>
        <p>No items were found in your order summary.</p>
    <?php endif; ?>
</div>

<button onclick="window.location.href='Canteen_Staff.php'" class="btn">Return to Dashboard</button>

<?php include '../templates/footer.php'; ?>

<?php unset($_SESSION['last_order']); ?>
