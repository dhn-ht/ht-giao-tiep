<?php
session_start();
include('db.php'); // Kết nối với cơ sở dữ liệu

// Kiểm tra tham số id
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Truy vấn thông tin người dùng từ bảng users
    $query = $conn->prepare("SELECT full_name, email, image, role, school_id FROM users WHERE id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    // Kiểm tra xem người dùng có tồn tại không
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Người dùng không tồn tại.";
        exit;
    }
} else {
    echo "ID người dùng không hợp lệ.";
    exit;
}

// Kiểm tra quyền sở hữu
$is_owner = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id;

// Kiểm tra và chuyển đổi role
$role_name = '';
if ($user['role'] === 'student') {
    $role_name = 'Sinh viên';
} elseif ($user['role'] === 'teacher') {
    $role_name = 'Giáo viên';
} else {
    $role_name = 'Không xác định';
}

// Truy vấn tên trường dựa trên school_id
$school_name = 'Không xác định';
if (!empty($user['school_id'])) {
    $school_query = $conn->prepare("SELECT school_name FROM schools WHERE id = ?");
    $school_query->bind_param("i", $user['school_id']);
    $school_query->execute();
    $school_result = $school_query->get_result();
    if ($school_result->num_rows > 0) {
        $school_row = $school_result->fetch_assoc();
        $school_name = $school_row['school_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản <?= htmlspecialchars($user['full_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1c1c1c;
            color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
        }
        .profile-card {
            background: radial-gradient(circle, rgba(34,193,195,1) 0%, rgba(253,187,45,1) 100%);
            border: none;
            border-radius: 20px;
            color: white;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            border: 5px solid #ffffff;
        }
        .profile-image:hover {
            transform: scale(1.1);
        }
        .card-title {
            font-size: 2em;
            font-weight: bold;
            text-shadow: 1px 2px 2px rgba(0, 0, 0, 0.4);
        }
        .card-text {
            font-size: 1.2em;
            line-height: 1.6;
        }
        .btn-custom {
            background-color: #fd5e53;
            color: white;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #e53935;
            transform: scale(1.1);
        }
         .btn-custom2 {
            background-color: #03cb3e;
            color: white;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn-custom2:hover {
            background-color: #e53935;
            transform: scale(1.1);
        }
        .profile-info {
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="profile-card mt-5 p-4">
        <?php if (!empty($user['image'])): ?>
            <img src="<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-image">
        <?php else: ?>
            <img src="default-avatar.png" alt="Profile Image" class="profile-image">
        <?php endif; ?>
        <h2 class="card-title mt-3"><?= htmlspecialchars($user['full_name']) ?></h2>
        <div class="profile-info mt-3">
            <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p class="card-text"><strong>Chức vụ:</strong> <?= htmlspecialchars($role_name) ?></p>
            <p class="card-text"><strong>Tên trường:</strong> <?= htmlspecialchars($school_name) ?></p>
        </div>
                    <a href="/index.php" class="btn btn-custom2 mt-4">Quay lại trang đầu</a>

        <?php if ($is_owner): ?>
            <a href="editprofile.php?id=<?= $user_id ?>" class="btn btn-custom mt-4">Chỉnh sửa</a>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
