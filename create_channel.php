<?php
session_start();
include('db.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = $_GET['room_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $channel_name = $_POST['channel_name'];
    $sql = "INSERT INTO channels (channel_name, room_id, created_by) 
            VALUES ('$channel_name', $room_id, $user_id)";
    if ($conn->query($sql) === TRUE) {
        header("Location: room.php?room_id=$room_id");
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo kênh mới</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4a4a4a;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tạo kênh mới trong phòng</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="channel_name">Tên kênh</label>
                <input type="text" name="channel_name" id="channel_name" class="form-control"
                    placeholder="Nhập tên kênh" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tạo kênh</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>