<?php
session_start();
include('db.php'); // Kết nối với cơ sở dữ liệu

// Kiểm tra quyền truy cập của admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập vào trang này.";
    exit;
}

// Kiểm tra xem ID có hợp lệ không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Chuẩn bị câu truy vấn xóa
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Kiểm tra xem việc xóa có thành công không
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?message=Phòng đã được xóa thành công.");
    } else {
        echo "Không thể xóa phòng. Vui lòng thử lại.";
    }
} else {
    echo "ID không hợp lệ.";
}
?>
