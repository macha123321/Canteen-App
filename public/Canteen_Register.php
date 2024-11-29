<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $admin = $_POST['admin_password'];
    $admin_pass = 'admin123';

    try {
        if ($role == 'admin') {
            if ($admin == $admin_pass) {
                $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
                $stmt->execute(['username' => $username, 'password' => $password, 'role' => $role]);
                echo "<script>         
                alert('Successfully Registered');         
                window.location.href = 'Canteen_Login.php';     
                </script>";     
                exit();
            } else {     
                echo "<script>         
                alert('Incorrect Admin Password');         
                window.location.href = 'Canteen_Register.php';     
                </script>";     
                exit();
            }
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
            $stmt->execute(['username' => $username, 'password' => $password, 'role' => $role]);
            echo "<script>         
                alert('Successfully Registered');         
                window.location.href = 'Canteen_Login.php';     
                </script>";     
                exit();
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>         
                alert('Username already exists. Please choose another.');         
                window.location.href = 'Canteen_Register.php';     
                </script>";     
                exit();
        } else {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<?php include '../templates/header.php'?>

<header>
    <h1>Register Page</h1>
</header>
<form method="POST" action="Canteen_Register.php" id="register" class="form-control">
    <div class="form-add">
        <label>Username:</label><br>
        <input type="text" name="username" id="username" placeholder="Username" required>
    </div><br><br>
    <div class="form-add">
        <label>Password:</label><br>
        <input type="password" name="password" id="password" placeholder="Password" required>
    </div><br><br>
    <div class="form-add">
        <label>Role:</label><br>
        <input type="radio" name="role" id="staff" value="staff" required>
        <label for="staff" style="display: inline;">Staff</label>
        <input type="radio" name="role" id="admin" value="admin">
        <label for="admin" style="display: inline;">Admin</label>
    </div><br><br>
    <div class="form-add-admin" id="form-add-admin" style="display: none;">
        <label>Admin Password:</label><br>
        <input type="password" name="admin_password" id="admin_password" placeholder="Admin Password">
    </div><br><br>
    <button type="submit">Register</button>
</form>
<p><a href="Canteen_Login.php">Already have an account?</a></p>
<script src="js/script.js"></script>

<?php include '../templates/footer.php'?>
