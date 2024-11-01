<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$channel_id = $_GET['channel_id']; // Lấy ID kênh từ URL

// Kiểm tra xem người dùng đã là thành viên chưa
$check_member = $conn->query("SELECT * FROM channel_members WHERE channel_id = $channel_id AND user_id = $user_id");

if ($check_member->num_rows === 0) {
    // Thêm người dùng vào kênh nếu chưa là thành viên
    $sql = "INSERT INTO channel_members (channel_id, user_id) 
            VALUES ($channel_id, $user_id)";
    if ($conn->query($sql) === TRUE) {
        header("Location: room.php?channel_id=$channel_id");
    } else {
        echo "Lỗi: " . $conn->error;
    }
} else {
    header("Location: room.php?channel_id=$channel_id");
}
?>
