<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['channel_id'])) {
    http_response_code(403);
    exit;
}

$user_id = $_SESSION['user_id'];
$channel_id = $_GET['channel_id'];

// Lấy tin nhắn từ cơ sở dữ liệu
$messages = $conn->query("SELECT m.*, u.full_name,  u.image 
                          FROM messages m 
                          JOIN users u ON m.user_id = u.id
                          WHERE channel_id = $channel_id
                          ORDER BY m.created_at ASC");

$messages_array = [];

while ($message = $messages->fetch_assoc()) {
    $messages_array[] = $message;
}

// Trả về kết quả dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($messages_array);
?>
