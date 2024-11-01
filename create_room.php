<?php
session_start();
include('db.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'];
    $created_by = $_SESSION['user_id'];

    // Kiểm tra xem người dùng đã tạo tên phòng ban này chưa
    $check_sql = "SELECT * FROM rooms WHERE room_name = '$room_name' AND created_by = $created_by";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Người dùng đã tạo tên phòng ban này trước đó
        $error_message = "Tạo không thành công! Bạn đã tạo phòng ban với tên này rồi.";
    } else {
        // Tiến hành thêm mới nếu chưa tồn tại phòng ban với người dùng này
        $sql = "INSERT INTO rooms (room_name, created_by) VALUES ('$room_name', $created_by)";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Lỗi: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo phòng ban</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Tạo phòng ban mới</h2>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger">
            <?= $error_message ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Tên phòng ban</label>
            <input type="text" name="room_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tạo phòng ban</button>
    </form>
</div>
</body>
</html>
