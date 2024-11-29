<?php
include '../config/db.php';

$itemId = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM menuitems WHERE item_id = :item_id");
$query->execute(['item_id' => $itemId]);
$item = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = $_POST['item_name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $available = isset($_POST['available']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE menuitems SET item_name = :item_name, Stock = :stock, price = :price, available = :available WHERE item_id = :item_id");
    $stmt->execute([
        'item_name' => $itemName,
        'stock' => $stock,
        'price' => $price,
        'available' => $available,
        'item_id' => $itemId
    ]);

    header('Location: Canteen_Admin.php');
    exit();
}
?>

<?php include '../templates/header.php' ?>

<header>
    <h1>Edit Item</h1>
</header>
<body>
    <div class="container">
        <form method="POST" action="edit_item.php?id=<?php echo $itemId; ?>">
            <div class="form-add">
                <label>Item Name:</label>
                <input type="text" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            </div>
            <div class="form-add">
                <label>Stock:</label>
                <input type="number" name="stock" value="<?php echo $item['Stock']; ?>" min="0" required>
            </div>
            <div class="form-add">
                <label>Price:</label>
                <input type="number" name="price" value="<?php echo $item['price']; ?>" step="0.01" min="0" required>
            </div>
            <div class="form-add">
                <label>Available:</label>
                <input type="checkbox" name="available" value="1" <?php echo $item['available'] ? 'checked' : ''; ?>> Yes
            </div>
            <button type="submit">Update Item</button>
        </form>
    </div>
</body>

<?php include '../templates/footer.php' ?>
