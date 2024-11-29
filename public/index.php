<?php 
session_start();

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    if ($role == 'Staff') {
        header('Location: Canteen_Staff.php');
        exit();
    } else {
        header('Location: Canteen_Admin.php');
        exit();
    }
} else {
    header('Location: Canteen_Login.php');
    exit();
}