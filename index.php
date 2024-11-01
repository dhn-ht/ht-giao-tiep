<?php
session_start();
include('db.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // 'teacher' hoặc 'student'

// Lấy danh sách phòng do giáo viên (hoặc người dùng) đã tạo
$rooms = $conn->query("SELECT * FROM rooms WHERE created_by = $user_id");

// Lấy danh sách kênh thuộc các phòng mà giáo viên (hoặc người dùng) đã tạo
$channels = $conn->query("SELECT c.*, r.room_name 
                          FROM channels c 
                          JOIN rooms r ON c.room_id = r.id 
                          WHERE r.created_by = $user_id");

// Hiển thị thông tin người dùng
$user_info = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bảng Điều Khiển</title>
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

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-control {
            border-radius: 8px;
        }

        .form-label {
            font-weight: bold;
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
        <h2>Chào mừng, <?= htmlspecialchars($user_info['username']) ?>!</h2>
        <div class="row">
            <!-- Phần hiển thị danh sách phòng -->
            <div class="col-md-6">
                <h4>Phòng Ban</h4>
                <ul class="list-group">
                    <?php if ($rooms->num_rows > 0): ?>
                        <?php while ($room = $rooms->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <?= htmlspecialchars($room['room_name']) ?>
                                <?php if ($role == 'teacher'): ?>
                                    <a href="create_channel.php?room_id=<?= $room['id'] ?>" class="btn btn-sm btn-secondary">Tạo
                                        Kênh</a>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">Bạn chưa tạo phòng nào.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Phần hiển thị danh sách kênh -->
            <div class="col-md-6">
                <h4>Kênh Của Bạn</h4>
                <ul class="list-group">
                    <?php if ($channels->num_rows > 0): ?>
                        <?php while ($channel = $channels->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($channel['channel_name']) ?></strong> (Phòng:
                                <?= htmlspecialchars($channel['room_name']) ?>)
                                <a href="room.php?room_id=<?= $channel['room_id'] ?>&channel_id=<?= $channel['id'] ?>"
                                    class="btn btn-sm btn-primary">Vào Kênh</a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">Bạn chưa tạo kênh nào.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Phần tạo phòng mới cho giáo viên -->
        <?php if ($role == 'teacher'): ?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h4>Tạo Phòng Ban Mới</h4>
                    <form method="POST" action="create_room.php">
                        <div class="mb-3">
                            <label for="room_name" class="form-label">Tên Phòng Ban</label>
                            <input type="text" name="room_name" id="room_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tạo Phòng Ban</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>&copy; 2024</p>
        </div>
    </div>
</body>

</html>