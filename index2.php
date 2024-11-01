<?php
session_start();
include('db.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách phòng thông qua các kênh mà học sinh đã tham gia
$rooms = $conn->query("SELECT DISTINCT r.* 
                       FROM rooms r 
                       JOIN channels c ON r.id = c.room_id 
                       JOIN channel_members cm ON c.id = cm.channel_id 
                       WHERE cm.user_id = $user_id");

// Lấy danh sách kênh mà học sinh đã tham gia
$channels = $conn->query("SELECT c.*, r.room_name 
                          FROM channels c 
                          JOIN rooms r ON c.room_id = r.id 
                          JOIN channel_members cm ON c.id = cm.channel_id 
                          WHERE cm.user_id = $user_id");

// Hiển thị tên người dùng
$user_info = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bảng Điều Khiển Học Sinh</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
        }

        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-weight: bold;
        }

        h4 {
            color: #555;
            margin-top: 20px;
        }

        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            transition: background-color 0.3s;
        }

        .list-group-item:hover {
            background-color: #d6d9db;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Chào mừng, Học sinh <?= htmlspecialchars($user_info['username']) ?>!</h2>

        <!-- Phần hiển thị danh sách phòng -->
        <div class="row">
            <div class="col-md-6">
                <h4>Phòng Ban Bạn Đã Tham Gia</h4>
                <ul class="list-group">
                    <?php if ($rooms->num_rows > 0): ?>
                        <?php while ($room = $rooms->fetch_assoc()): ?>
                            <li class="list-group-item"><?= htmlspecialchars($room['room_name']) ?></li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">Bạn chưa tham gia phòng nào.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Phần hiển thị danh sách kênh -->
            <div class="col-md-6">
                <h4>Kênh Bạn Đã Tham Gia</h4>
                <ul class="list-group">
                    <?php if ($channels->num_rows > 0): ?>
                        <?php while ($channel = $channels->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($channel['channel_name']) ?></strong> (Phòng:
                                <?= htmlspecialchars($channel['room_name']) ?>)
                                <a href="room.php?room_id=<?= $channel['room_id'] ?>&channel_id=<?= $channel['id'] ?>"
                                    class="btn btn-sm btn-primary float-end">Vào Kênh</a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">Bạn chưa tham gia kênh nào.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2024 Hệ thống học trực tuyến</p>
        </div>
    </div>
</body>

</html>