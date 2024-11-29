<?php
session_start();

session_destroy();

header("Location: Canteen_Login.php");
exit();
?>
