<?php
$servername = "localhost";
$username = "root";
$password = "86849298Bb@";
$database = "gamified_learning_web";

// Холболт үүсгэх
$conn = new mysqli($servername, $username, $password, $database);

// Холболт шалгах
if ($conn->connect_error) {
    die("Холболт амжилтгүй: " . $conn->connect_error);
}
?>