<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
</head>
<body>

<h1>Сайн байна уу, <?= $_SESSION['username'] ?></h1>

<a href="word_game.php">Word даалгавар</a><br>
<a href="excel_game.php">Excel даалгавар</a><br><br>

<a href="logout.php">Logout</a>

</body>
</html>