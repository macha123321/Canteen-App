<?php 
include '../config/db.php';

// Handle form submission for adding a new item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $available = isset($_POST['available']) ? 1 : 0; // If checked, item is available, else not

    try {
        // Prepare the SQL query to insert the new item
        $stmt = $pdo->prepare("INSERT INTO menuitems (item_name, Stock, price, available) VALUES (:item_name, :stock, :price, :available)");
        $stmt->execute([
            'item_name' => $item_name,
            'stock' => $stock,
            'price' => $price,
            'available' => $available
        ]);

        echo "<script>
                alert('Item added successfully!');
                window.location.href = 'Canteen_Admin.php'; // Redirect to admin dashboard
              </script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<?php include '../templates/header.php' ?>

<header>
    <h1>Add Menu Item</h1>
</header>

<body>
    <div class="container">
        <form method="POST" action="add_item.php" class="form-control">
            <div class="form-add">
                <label>Item Name:</label><br>
                <input type="text" name="item_name" required placeholder="Enter item name">
            </div><br><br>

            <div class="form-add">
                <label>Stock:</label><br>
                <input type="number" name="stock" required placeholder="Enter stock quantity">
            </div><br><br>

            <div class="form-add">
                <label>Price:</label><br>
                <input type="number" name="price" required step="0.01" placeholder="Enter price">
            </div><br><br>

            <div class="form-add">
                <label>Available:</label><br>
                <input type="checkbox" name="available" checked> Available
            </div><br><br>

            <button type="submit">Add Item</button>
        </form>
    </div>
</body>

<?php include '../templates/footer.php' ?>
