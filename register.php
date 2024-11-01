<?php
include('db.php');

function handleFileUpload($file)
{
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $random_file_name = uniqid('profile_', true) . '.' . $file_extension;
    $target_file = $target_dir . $random_file_name;

    // Kiểm tra loại tệp
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        return ["error" => "Chỉ hỗ trợ các định dạng: " . implode(', ', $allowed_types)];
    }

    if ($file['size'] > 2000000) {
        return ["error" => "Kích thước ảnh quá lớn! (tối đa 2MB)"];
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ["success" => $target_file];
    } else {
        return ["error" => "Lỗi khi tải lên hình ảnh: " . $file['error']];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];
    $school_id = $_POST['school'];

    // Xử lý hình ảnh đại diện
    $image_upload = handleFileUpload($_FILES['profile_image']);
    $image = isset($image_upload['success']) ? $image_upload['success'] : null;

    if (isset($image_upload['error'])) {
        echo "<div class='alert alert-danger'>{$image_upload['error']}</div>";
        $image = null; // Nếu không thành công
    }

    // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại hay chưa
    $check_username = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check_username->bind_param("s", $username);
    $check_username->execute();
    $result_username = $check_username->get_result();

    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result_email = $check_email->get_result();

    if ($result_username->num_rows > 0) {
        echo "<div class='alert alert-danger'>Tên đăng nhập đã tồn tại!</div>";
    } elseif ($result_email->num_rows > 0) {
        echo "<div class='alert alert-danger'>Email đã tồn tại!</div>";
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $sql = "INSERT INTO users (username, password, email, role, full_name, school_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssis", $username, $password, $email, $role, $full_name, $school_id, $image);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
        }
    }
}

// Lấy danh sách các trường
$schools = $conn->query("SELECT * FROM schools");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .register-container h2 {
            margin-bottom: 30px;
            color: #333;
            font-weight: 700;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: border-color 0.3s;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng Ký Tài Khoản</h2>
        <form method="POST" enctype="multipart/form-data">
            <p>Thông tin:</p>
            <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="text" name="full_name" class="form-control" placeholder="Họ tên" required>
            <select name="role" class="form-control" required>
                <option value="" disabled selected>Chọn vai trò</option>
                <option value="teacher">Giáo viên</option>
                <option value="student">Sinh viên</option>
            </select>
            <select name="school" class="form-control" required>
                <option value="" disabled selected>Chọn trường</option>
                <?php while($row = $schools->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['school_name']) ?></option>
                <?php endwhile; ?>
            </select>
            <p>Ảnh đại diện:</p>
            <input type="file" name="profile_image" class="form-control" accept="image/*" required>

            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </form>
    </div>
</body>
</html>
