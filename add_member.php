<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $channel_id = $_POST['channel_id'];
    $username_or_id = $_POST['username_or_id'];

    // Tìm kiếm người dùng theo ID hoặc username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR id = ?");
    $stmt->bind_param("si", $username_or_id, $username_or_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Kiểm tra xem người dùng đã có trong kênh hay chưa
        $check = $conn->prepare("SELECT * FROM channel_members WHERE channel_id = ? AND user_id = ?");
        $check->bind_param("ii", $channel_id, $user_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows == 0) {
            // Thêm thành viên vào kênh
            $insert = $conn->prepare("INSERT INTO channel_members (channel_id, user_id) VALUES (?, ?)");
            $insert->bind_param("ii", $channel_id, $user_id);
            $insert->execute();

            echo "Thành viên đã được thêm vào kênh thành công.";
        } else {
            echo "Thành viên đã có trong kênh.";
        }
    } else {
        echo "Không tìm thấy người dùng.";
    }
}
?>