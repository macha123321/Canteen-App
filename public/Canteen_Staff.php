<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $item_id = intval($_POST['item_id']);
        $stmt = $pdo->prepare("SELECT item_name, Stock, price FROM menuitems WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $currentQuantity = isset($_SESSION['cart'][$item_id]) ? $_SESSION['cart'][$item_id]['quantity'] : 0;

            if ($currentQuantity < $item['Stock']) {
                if (!isset($_SESSION['cart'][$item_id])) {
                    $_SESSION['cart'][$item_id] = ['quantity' => 0, 'item_name' => $item['item_name']];
                }
                $_SESSION['cart'][$item_id]['quantity']++;
            }

            $response = ['success' => true, 'cart' => $_SESSION['cart'], 'total' => calculateTotal($_SESSION['cart'], $pdo)];
        } else {
            $response = ['success' => false, 'message' => 'Item not found'];
        }

        echo json_encode($response);
        exit();
    }

    if (isset($_POST['remove_from_cart'])) {
        $item_id = intval($_POST['item_id']);
        if (isset($_SESSION['cart'][$item_id])) {
            if ($_SESSION['cart'][$item_id]['quantity'] > 1) {
                $_SESSION['cart'][$item_id]['quantity']--;
            } else {
                unset($_SESSION['cart'][$item_id]);
            }
        }

        echo json_encode(['success' => true, 'cart' => $_SESSION['cart'], 'total' => calculateTotal($_SESSION['cart'], $pdo)]);
        exit();
    }

    if (isset($_POST['increment_cart'])) {
        $item_id = intval($_POST['item_id']);
        $stmt = $pdo->prepare("SELECT Stock FROM menuitems WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $stock = $stmt->fetchColumn();

        if ($stock && isset($_SESSION['cart'][$item_id])) {
            if ($_SESSION['cart'][$item_id]['quantity'] < $stock) {
                $_SESSION['cart'][$item_id]['quantity']++;
            }
        }

        echo json_encode(['success' => true, 'cart' => $_SESSION['cart'], 'total' => calculateTotal($_SESSION['cart'], $pdo)]);
        exit();
    }

    if (isset($_POST['reset_cart'])) {
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart' => [], 'total' => 0]);
        exit();
    }

    if (isset($_POST['finish_order'])) {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $_SESSION['last_order'] = $_SESSION['cart'];
            foreach ($_SESSION['cart'] as $item_id => $item) {
                $quantity = $item['quantity'];
                $stmt = $pdo->prepare("UPDATE menuitems SET Stock = Stock - ? WHERE item_id = ? AND Stock >= ?");
                $stmt->execute([$quantity, $item_id, $quantity]);
            }
        }
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'redirect' => 'order_completed.php']);
        exit();
    }
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

$query = $pdo->query("SELECT * FROM menuitems WHERE available = 1");
$items = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../templates/header.php'; ?>

<header>
    <h1>Staff Dashboard</h1>
</header>
<div class="container">
    <h2>Menu Items</h2>
    <div class="menu-container">
        <?php foreach ($items as $item): ?>
            <button class="menu-items" data-item-id="<?= $item['item_id'] ?>" 
            <?= $item['Stock'] <= 0 ? 'disabled style="background-color: grey;"' : '' ?>>
                <?= htmlspecialchars(ucfirst($item['item_name'])); ?> <br>
                <small>Stock: <?= $item['Stock'] ?> | £<?= $item['price'] ?></small>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="order-summary">
        <ul id="order-summary-list"></ul>
        <div id="total-box">Total: £0.00</div>
        <button id="reset-cart" class="btn">Reset Cart</button>
        <button id="finish-order" class="btn">Finish Order</button>
    </div>
    <button id="logout-btn" class="btn">Logout</button>
</div>

<?php
if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    echo '<p><a href="Canteen_Admin.php">Admin Dashboard</a></p>';
}
?>

<?php include '../templates/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('logout-btn').addEventListener('click', function () {
        window.location.href = 'logout.php';
    });

    document.querySelector('.menu-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('menu-items') && !event.target.disabled) {
            event.preventDefault();
            const itemId = event.target.getAttribute('data-item-id');

            fetch('Canteen_Staff.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ 'add_to_cart': '1', 'item_id': itemId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartSummary(data.cart, data.total);
                } else {
                    alert(data.message);
                }
            });
        }
    });

document.querySelector('#order-summary-list').addEventListener('click', function (event) {
    if (event.target.classList.contains('remove-item') || event.target.classList.contains('add-item')) {
        const itemId = event.target.getAttribute('data-item-id');
        const action = event.target.classList.contains('add-item') ? 'increment_cart' : 'remove_from_cart';

        fetch('Canteen_Staff.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ [action]: '1', 'item_id': itemId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartSummary(data.cart, data.total);
            }
        });
    }
});

    document.querySelector('#reset-cart').addEventListener('click', function () {
        fetch('Canteen_Staff.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 'reset_cart': '1' })
        })
        .then(response => response.json())
        .then(data => updateCartSummary(data.cart, data.total));
    });

    document.querySelector('#finish-order').addEventListener('click', function () {
        fetch('Canteen_Staff.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 'finish_order': '1' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            }
        });
    });

    function updateCartSummary(cart, total) {
        const orderSummaryList = document.getElementById('order-summary-list');
        const totalBox = document.getElementById('total-box');

        orderSummaryList.innerHTML = '';

        if (Object.keys(cart).length === 0) {
            orderSummaryList.innerHTML = '<li>No items in cart</li>';
        } else {
            for (const itemId in cart) {
                const item = cart[itemId];
                const listItem = document.createElement('li');
                listItem.innerHTML = `
                    <strong>${item.item_name} (Quantity: ${item.quantity})</strong>
                    <button class="remove-item" data-item-id="${itemId}">-</button>
                    <button class="add-item" data-item-id="${itemId}">+</button>
                `;
                orderSummaryList.appendChild(listItem);
            }
        }

        totalBox.textContent = `Total: £${total.toFixed(2)}`;
    }
});
</script>

<style>
    .btn-small {
        font-size: 10px;
        padding: 2px 5px;
        margin-left: 5px;
        cursor: pointer;
    }
    .order-summary {
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        background: #f9f9f9;
        max-height: 300px;
        overflow-y: auto;
    }
    #total-box {
        margin-top: 10px;
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
        text-align: right;
        padding: 5px 10px;
        border-top: 1px solid #ccc;
    }
</style>
