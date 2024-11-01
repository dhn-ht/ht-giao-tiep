<?php
include('db.php');
session_start();

$error_message = ""; // Biến lưu thông báo lỗi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn lấy thông tin người dùng dựa trên tên đăng nhập
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['image'] = $user['image'];
            $_SESSION['full_name'] = $user['full_name'];
            // Chuyển hướng dựa trên role
            if ($user['role'] == 'teacher') {
                header("Location: index.php"); // Chuyển hướng đến trang của giáo viên
            } elseif ($user['role'] == 'student') {
                header("Location: index2.php"); // Chuyển hướng đến trang của học sinh
            } else {
                $error_message = "Vai trò không hợp lệ";
            }
            exit;
        } else {
            $error_message = "Sai mật khẩu";
        }
    } else {
        $error_message = "Tên đăng nhập không tồn tại";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 30px;
            color: #333;
            font-weight: 700;
        }

        .form-control {
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #6e8efb;
            box-shadow: 0 0 5px rgba(110, 142, 251, 0.5);
        }

        .btn-primary {
            background-color: #6e8efb;
            border-color: #6e8efb;
            padding: 10px 20px;
            border-radius: 50px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #a777e3;
            border-color: #a777e3;
        }

        .input-group-text {
            background-color: #6e8efb;
            color: white;
            border-radius: 10px 0 0 10px;
            border: 1px solid #6e8efb;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .alert {
            margin-top: 20px;
        }

        .footer-text {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form method="POST">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="input-group">
                <span class="input-group-text">Tên đăng nhập</span>
                <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required>
            </div>
            <div class="input-group">
                <span class="input-group-text">Mật khẩu</span>
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>

            <!-- Thông báo lỗi hiển thị nếu có -->


            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
        </form>
        <p class="footer-text">Chưa có tài khoản? <a href="register.php">Đăng ký ngay!</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>