<?php
include '../config/db.php';
$query = $pdo->query("SELECT * FROM menuitems");
$items = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../templates/header.php' ?>

<header>
    <h1>Administrator Dashboard</h1>
</header>
<body>
    <div class="container">
        <h2>Manage Menu Items</h2>
        <div class="menu-container">
            <?php foreach ($items as $item): ?>
                <div class="container-menu-items">
                    <strong>Item:</strong> <?php echo htmlspecialchars($item["item_name"]); ?> <br>
                    <strong>Stock:</strong> <?php echo htmlspecialchars($item["Stock"]); ?> <br>
                    <strong>Price:</strong> £<?php echo htmlspecialchars($item["price"]); ?> <br>
                    <strong>Available:</strong> <?php echo $item["available"] ? "Yes" : "No"; ?> <br>
                    <a href="edit_item.php?id=<?php echo $item['item_id']; ?>">Edit</a>
                    <a href="delete_item.php?id=<?php echo $item['item_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endforeach; ?>
        </div>
        <h2>Add New Item</h2>
        <form method="POST" action="add_item.php">
            <div class="form-add">
                <label>Item Name:</label>
                <input type="text" name="item_name" required>
            </div>
            <div class="form-add">
                <label>Stock:</label>
                <input type="number" name="stock" min="0" required>
            </div>
            <div class="form-add">
                <label>Price:</label>
                <input type="number" name="price" step="0.01" min="0" required>
            </div>
            <div class="form-add">
                <label>Available:</label>
                <input type="checkbox" name="available" value="1" checked> Yes
            </div>
            <button type="submit">Add Item</button>
        </form>
        <button id="logout-btn" class="btn">Logout</button>
    </div>
    <p><a href="Canteen_Staff.php">Staff Dashboard</a></p>
</body>


<?php include '../templates/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('logout-btn').addEventListener('click', function () {
        window.location.href = 'logout.php';
    });
});
</script>