<?php
session_start();
include('db.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng hiện tại
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $image = $user['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_dir = 'uploads/';
            $upload_file = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_file)) {
                $image = $upload_file;
            } else {
                $error = "Lỗi tải lên hình ảnh.";
            }
        } else {
            $error = "Chỉ chấp nhận các tệp hình ảnh (jpg, jpeg, png, gif).";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $full_name, $email, $image, $user_id);

        if ($stmt->execute()) {
            $success = "Cập nhật thông tin thành công!";
            $user['full_name'] = $full_name;
            $user['email'] = $email;
            $user['image'] = $image;
        } else {
            $error = "Lỗi khi cập nhật thông tin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa hồ sơ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 1.8em;
            color: #333;
            font-weight: bold;
        }

        .card-body {
            font-size: 1.1em;
            color: #666;
        }

        .btn-primary {
            background-color: #4CAF50;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .alert {
            border-radius: 5px;
        }

        input[type="file"] {
            cursor: pointer;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        label {
            font-weight: 600;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h2 class="card-title text-center mt-3">Chỉnh sửa thông tin cá nhân</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger mt-3"><?= $error ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success mt-3"><?= $success ?></div>
            <?php endif; ?>

            <div class="text-center my-4">
                <?php if (!empty($user['image'])): ?>
                    <img src="<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-image mb-3">
                <?php else: ?>
                    <img src="default-avatar.png" alt="Profile Image" class="profile-image mb-3">
                <?php endif; ?>
            </div>

            <form action="editprofile.php" method="POST" enctype="multipart/form-data" class="p-3">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Tên đầy đủ</label>
                    <input type="text" class="form-control" id="full_name" name="full_name"
                        value="<?= htmlspecialchars($user['full_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh đại diện</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>