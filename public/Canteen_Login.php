<?php 
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo-> prepare('SELECT * FROM users WHERE  username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header ('Location: index.php');
        exit();
    } else {
        echo "<script>         
                alert('Incorrect Username or Password');         
                window.location.href = 'Canteen_Login.php';     
                </script>";     
                exit();
    }
}
?>

<?php include '../templates/header.php'?>

<header>
        <h1>Login Page</h1>
    </header>
        <form action="Canteen_Login.php" method="POST" class="from-control">
            <div class="form-add">
                <label name="username" >Username:</label><br>
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div><br><br>
            <div class="form-add">
                <label >Password:</label><br>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div><br><br>
            <div class="form-add">
                <input type="submit" value="Submit">
            </div>
        </form>
        <p><a href="Canteen_Register.php">Don't have an account?</a></p>

        <?php include '../templates/footer.php'?>