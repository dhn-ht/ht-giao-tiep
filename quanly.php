<?php
session_start();
include('db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if ($user_role !== 'teacher') {
    echo "<script>alert('Bạn không có quyền truy cập.'); window.location.href = 'index.php';</script>";
    exit;
}

function showAlertAndReload($message) {
    echo "<script>alert('$message'); window.location.href = '';</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $room_name = $_POST['room_name'];
    $sql = "SELECT * FROM rooms WHERE room_name = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $room_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        showAlertAndReload("Tên phòng đã tồn tại.");
    } else {
        $sql = "INSERT INTO rooms (room_name, created_by) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $room_name, $user_id);
        $stmt->execute();
        showAlertAndReload("Phòng đã được thêm thành công.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_room'])) {
    $room_id = $_POST['room_id'];
    $sql = "DELETE FROM rooms WHERE id = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $room_id, $user_id);
    $stmt->execute();
    showAlertAndReload("Phòng đã được xóa.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_channel'])) {
    $room_id = $_POST['room_id'];
    $channel_name = $_POST['channel_name'];

    $sql = "SELECT * FROM channels WHERE room_id = ? AND channel_name = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $room_id, $channel_name, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        showAlertAndReload("Tên kênh đã tồn tại trong phòng này.");
    } else {
        $sql = "INSERT INTO channels (room_id, channel_name, created_by) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $room_id, $channel_name, $user_id);
        $stmt->execute();
        showAlertAndReload("Kênh đã được thêm thành công.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_channel'])) {
    $channel_id = $_POST['channel_id'];
    $sql = "DELETE FROM channels WHERE id = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $channel_id, $user_id);
    $stmt->execute();
    showAlertAndReload("Kênh đã được xóa.");
}

$sql = "SELECT * FROM rooms WHERE created_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$rooms = $stmt->get_result();

$channels = [];
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $sql = "SELECT * FROM channels WHERE room_id = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $room_id, $user_id);
    $stmt->execute();
    $channels = $stmt->get_result();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Kênh và Phòng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h2, h4 {
            color: #343a40;
        }
        .list-group-item {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
            transition: background-color 0.3s;
        }
        .list-group-item:hover {
            background-color: #f1f1f1;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #138496;
        }
        .form-control {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .container {
            margin-top: 20px;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Quản lý Kênh và Phòng</h2>

    <!-- Phần thêm phòng -->
    <h4>Thêm Phòng</h4>
    <form method="POST">
        <div class="mb-3">
            <label for="room_name" class="form-label">Tên Phòng</label>
            <input type="text" class="form-control" name="room_name" required>
        </div>
        <button type="submit" name="add_room" class="btn btn-primary">Thêm</button>
    </form>

    <!-- Danh sách phòng -->
    <h4 class="mt-4">Danh Sách Phòng</h4>
    <ul class="list-group">
        <?php while ($room = $rooms->fetch_assoc()): ?>
            <li class="list-group-item">
                <?= htmlspecialchars($room['room_name']) ?>
                <form method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng này không?');">
    <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
    <button type="submit" name="delete_room" class="btn btn-danger btn-sm float-end">Xóa</button>
</form>
                <a href="?room_id=<?= $room['id'] ?>" class="btn btn-info btn-sm float-end me-2">Quản lý Kênh</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php if (isset($room_id)): ?>
        <h4 class="mt-4">Quản lý Kênh trong Phòng: <?= htmlspecialchars($room_id) ?></h4>
        <form method="POST">
            <input type="hidden" name="room_id" value="<?= $room_id ?>">
            <div class="mb-3">
                <label for="channel_name" class="form-label">Tên Kênh</label>
                <input type="text" class="form-control" name="channel_name" required>
            </div>
            <button type="submit" name="add_channel" class="btn btn-primary">Thêm Kênh</button>
        </form>

        <!-- Danh sách kênh -->
        <h4 class="mt-4">Danh Sách Kênh</h4>
        <ul class="list-group">
            <?php if ($channels->num_rows > 0): ?>
                <?php while ($channel = $channels->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($channel['channel_name']) ?>
                     <form method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa kênh này không?');">
    <input type="hidden" name="channel_id" value="<?= $channel['id'] ?>">
    <button type="submit" name="delete_channel" class="btn btn-danger btn-sm float-end">Xóa</button>
</form>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item text-danger">Không có kênh nào trong phòng này.</li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</div>
</body>
</html>
