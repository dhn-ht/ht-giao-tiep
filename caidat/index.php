<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/db.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Admin</h1>
    <ul class="nav nav-tabs mt-4" id="adminTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="user-tab" data-bs-toggle="tab" href="#user" role="tab" aria-controls="user" aria-selected="true">Quản lý User</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="school-tab" data-bs-toggle="tab" href="#school" role="tab" aria-controls="school" aria-selected="false">Quản lý Trường</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="room-tab" data-bs-toggle="tab" href="#room" role="tab" aria-controls="room" aria-selected="false">Quản lý Phòng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="channel-tab" data-bs-toggle="tab" href="#channel" role="tab" aria-controls="channel" aria-selected="false">Quản lý Kênh</a>
        </li>
    </ul>
    
    <div class="tab-content mt-4">
        <!-- Quản lý User -->
    <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
    <?php
    // Xử lý cập nhật thông tin người dùng
    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssii", $full_name, $username, $email, $role, $user_id);
        $stmt->execute();
    }

    // Xử lý xóa người dùng
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    // Tìm kiếm người dùng
    if (isset($_POST['search_user'])) {
        $keyword = $_POST['keyword'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE full_name LIKE ? OR username LIKE ? OR email LIKE ? OR role LIKE ?");
        $searchTerm = "%{$keyword}%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $users = $stmt->get_result();
    } else {
        $users = $conn->query("SELECT * FROM users");
    }
    ?>

    <!-- Form Tìm kiếm Người dùng -->
    <form method="post" class="mb-3">
        <input type="text" name="keyword" placeholder="Tìm kiếm Người dùng" class="form-control">
        <button type="submit" name="search_user" class="btn btn-primary mt-2">Tìm kiếm</button>
    </form>

    <!-- Bảng Danh sách Người dùng -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Tên Đăng Nhập</th>
                <th>Email</th>
                <th>Role</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <!-- Nút Chỉnh sửa Người dùng (Mở Modal) -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>">Chỉnh sửa</button>

                        <!-- Modal Chỉnh sửa Người dùng -->
                        <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo $user['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel<?php echo $user['id']; ?>">Chỉnh sửa Người dùng</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <div class="mb-3">
                                                <label for="full_name" class="form-label">Họ và Tên</label>
                                                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Tên Đăng Nhập</label>
                                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select name="role" class="form-control">
                                                    <option value="user" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Sinh Viên</option>
                                                    <option value="admin" <?php echo ($user['role'] == 'teacher') ? 'selected' : ''; ?>>Giáo Viên</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" name="update_user" class="btn btn-primary">Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Nút Xóa Người dùng -->
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>




        <!-- Quản lý Trường -->
        <div class="tab-pane fade" id="school" role="tabpanel" aria-labelledby="school-tab">
<?php
if (isset($_POST['add_school'])) {
    $school_name = $_POST['school_name'];
    $stmt = $conn->prepare("INSERT INTO schools (school_name) VALUES (?)");
    $stmt->bind_param("s", $school_name);
    $stmt->execute();
}

if (isset($_POST['search_school'])) {
    $keyword = $_POST['keyword'];
    $stmt = $conn->prepare("SELECT * FROM schools WHERE school_name LIKE ?");
    $searchTerm = "%{$keyword}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $schools = $stmt->get_result();
} else {
    $schools = $conn->query("SELECT * FROM schools");
}
?>

<form method="post" class="mb-3">
    <input type="text" name="school_name" placeholder="Tên Trường" class="form-control" required>
    <button type="submit" name="add_school" class="btn btn-success mt-2">Thêm Trường</button>
</form>

<!-- Form Tìm kiếm Trường -->
<form method="post" class="mb-3">
    <input type="text" name="keyword" placeholder="Tìm kiếm Trường" class="form-control">
    <button type="submit" name="search_school" class="btn btn-primary mt-2">Tìm kiếm</button>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Trường</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($school = $schools->fetch_assoc()): ?>
            <tr>
                <td><?php echo $school['id']; ?></td>
                <td><?php echo htmlspecialchars($school['school_name']); ?></td>
                <td>
                    <a href="delete_school.php?id=<?php echo $school['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
        </div>

        <!-- Quản lý Phòng -->
        <div class="tab-pane fade" id="room" role="tabpanel" aria-labelledby="room-tab">
<?php
// Tìm kiếm Phòng
if (isset($_POST['search_room'])) {
    $keyword = $_POST['keyword'];
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_name LIKE ?");
    $searchTerm = "%{$keyword}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $rooms = $stmt->get_result();
} else {
    $rooms = $conn->query("SELECT * FROM rooms");
}
?>

<!-- Form Tìm kiếm Phòng -->
<form method="post" class="mb-3">
    <input type="text" name="keyword" placeholder="Tìm kiếm Phòng" class="form-control">
    <button type="submit" name="search_room" class="btn btn-primary mt-2">Tìm kiếm</button>
</form>

<!-- Bảng Danh sách Phòng -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Phòng</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($room = $rooms->fetch_assoc()): ?>
            <tr>
                <td><?php echo $room['id']; ?></td>
                <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                <td>
                    <a href="delete_room.php?id=<?php echo $room['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
        </div>

        <!-- Quản lý Kênh -->
        <div class="tab-pane fade" id="channel" role="tabpanel" aria-labelledby="channel-tab">
<?php
// Tìm kiếm Kênh
if (isset($_POST['search_channel'])) {
    $keyword = $_POST['keyword'];
    $stmt = $conn->prepare("SELECT * FROM channels WHERE channel_name LIKE ?");
    $searchTerm = "%{$keyword}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $channels = $stmt->get_result();
} else {
    $channels = $conn->query("SELECT * FROM channels");
}
?>

<!-- Form Tìm kiếm Kênh -->
<form method="post" class="mb-3">
    <input type="text" name="keyword" placeholder="Tìm kiếm Kênh" class="form-control">
    <button type="submit" name="search_channel" class="btn btn-primary mt-2">Tìm kiếm</button>
</form>

<!-- Bảng Danh sách Kênh -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Kênh</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($channel = $channels->fetch_assoc()): ?>
            <tr>
                <td><?php echo $channel['id']; ?></td>
                <td><?php echo htmlspecialchars($channel['channel_name']); ?></td>
                <td>
                    <a href="delete_channel.php?id=<?php echo $channel['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
