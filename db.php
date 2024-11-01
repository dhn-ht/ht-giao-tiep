<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "he_thong_giao_tiep";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>