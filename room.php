<?php
session_start();
include('db.php');

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Thiết lập báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
$room_id = $_GET['room_id'];

// Kiểm tra phòng chat có tồn tại hay không
$room_check = $conn->query("SELECT * FROM rooms WHERE id = $room_id");
if ($room_check->num_rows === 0) {
    echo "Phòng không tồn tại.";
    exit;
}

// Lấy danh sách các kênh trong phòng chat
$channels = $conn->query("SELECT * FROM channels WHERE room_id = $room_id");

$channel_id = isset($_GET['channel_id']) ? $_GET['channel_id'] : null;
$messages = null;

// Kiểm tra kênh
if ($channel_id) {
    if ($_SESSION['role'] == 'teacher') {
        $can_access = true;
    } else {
        $check_membership = $conn->prepare("SELECT * FROM channel_members WHERE channel_id = ? AND user_id = ?");
        $check_membership->bind_param("ii", $channel_id, $user_id);
        $check_membership->execute();
        $membership_result = $check_membership->get_result();

        if ($membership_result->num_rows == 0) {
            echo "Bạn không có quyền truy cập kênh này.";
            exit;
        }
    }

    $messages = $conn->query("SELECT m.*, u.full_name, u.image 
                              FROM messages m 
                              JOIN users u ON m.user_id = u.id
                              WHERE channel_id = $channel_id
                              ORDER BY m.created_at ASC");
}

// Xử lý gửi tin nhắn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $current_time = time();
    if (isset($_SESSION['last_message_time']) && ($current_time - $_SESSION['last_message_time']) < 5) {
        echo "<script>alert('Bạn gửi tin nhắn quá nhanh. Vui lòng thử lại sau.');</script>";
        exit;
    }

    $message = $_POST['message'];
    $sql = "INSERT INTO messages (channel_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $channel_id, $user_id, $message);
    if ($stmt->execute()) {
        $_SESSION['last_message_time'] = $current_time;
        exit;
    } else {
        echo "Lỗi gửi tin nhắn: " . $conn->error;
    }
}

// Xử lý thêm thành viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
    $messagess = "";

    if (!empty(trim($_POST['member_identifiers']))) {
        $members = explode("\n", trim($_POST['member_identifiers']));

        foreach ($members as $member_identifier) {
            $member_identifier = trim($member_identifier);
            if (empty($member_identifier))
                continue;

            $check_user = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR full_name = ?");
            $check_user->bind_param("sss", $member_identifier, $member_identifier, $member_identifier);
            $check_user->execute();
            $user_result = $check_user->get_result();

            if ($user_result->num_rows > 0) {
                $user = $user_result->fetch_assoc();
                $member_id = $user['id'];

                $check_membership = $conn->prepare("SELECT * FROM channel_members WHERE channel_id = ? AND user_id = ?");
                $check_membership->bind_param("ii", $channel_id, $member_id);
                $check_membership->execute();
                $membership_result = $check_membership->get_result();

                if ($membership_result->num_rows == 0) {
                    $insert_member = $conn->prepare("INSERT INTO channel_members (channel_id, user_id) VALUES (?, ?)");
                    $insert_member->bind_param("ii", $channel_id, $member_id);
                    if ($insert_member->execute()) {
                        $messagess .= "<span style='color: green;'>Thêm thành công: $member_identifier</span><br>";
                    } else {
                        $messagess .= "<span style='color: red;'>Lỗi thêm thành viên: $member_identifier</span><br>";
                    }
                } else {
                    $messagess .= "<span style='color: orange;'>Thành viên đã có: $member_identifier</span><br>";
                }
            } else {
                $messagess .= "<span style='color: red;'>Không tìm thấy người dùng: $member_identifier</span><br>";
            }
        }
    } else {
        $messagess .= "<span style='color: red;'>Vui lòng nhập thông tin người dùng.</span>";
    }

    echo $messagess;
    exit;
}

// Include header chỉ khi không phải là yêu cầu AJAX
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/header.php');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Phòng Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <style>
        .chat-box div {
            word-wrap: break-word;
            margin-bottom: 10px;
        }

        .user-image {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            vertical-align: middle;
        }

        a {
            text-decoration: none;
            /* Loại bỏ viền xanh */
        }

        a:hover {
            text-decoration: underline;
            /* Thêm viền khi hover nếu cần */
        }
    </style>
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 900px;
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2,
        h4 {
            color: #007bff;
        }

        .list-group-item {
            color: #495057;
            transition: background-color 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .chat-box {
            height: 400px;
            background-color: #f1f1f1;
            border-radius: 8px;
            padding: 15px;
            overflow-y: scroll;
        }

        .chat-box div {
            word-wrap: break-word;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .chat-box strong {
            font-size: 1rem;
            color: #007bff;
        }

        .chat-box p {
            font-size: 0.95rem;
            margin: 5px 0;
        }

        .text-muted {
            font-size: 0.85rem;
            color: #6c757d;
        }

        textarea {
            resize: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .message-feedback span {
            font-weight: bold;
        }
    </style>
    <script>
        let channelId = <?= json_encode($channel_id) ?>;
        let roomId = <?= json_encode($room_id) ?>;

        function fetchMessages() {
            $.get("fetch_messages.php", { channel_id: channelId }, function (data) {
                const chatBox = $(".chat-box");
                chatBox.empty();

                data.forEach(message => {
                    chatBox.append(`
                        <div>
                        
                            <a href="viewprofile.php?id=${message.user_id}">
                        <img src="${message.image}" alt="User Image" class="user-image">
                    </a>
                            <strong>${message.full_name}:</strong>
                            <p>${message.content}</p>
                            <small class="text-muted">${message.created_at}</small>
                        </div>
                    `);
                });

                chatBox.scrollTop(chatBox[0].scrollHeight);
            });
        }

        $(document).ready(function () {
            let editorInstance;

            ClassicEditor.create(document.querySelector("#message"))
                .then(editor => {
                    editorInstance = editor;
                })
                .catch(error => console.error(error));

            fetchMessages();
            setInterval(fetchMessages, 2000);

            $("form#sendMessageForm").submit(function (e) {
                e.preventDefault();
                //   $("#message").show(); // Hiển thị textarea trước khi kiểm tra

                if (editorInstance) {
                    const messageContent = editorInstance.getData();
                    if (messageContent === "") {
                        alert("Vui lòng nhập tin nhắn trước khi gửi.");
                        return;
                    }
                    $.post("room.php?room_id=" + roomId + "&channel_id=" + channelId, { message: messageContent, send_message: true }, function () {
                        editorInstance.setData("");
                        fetchMessages();
                    });
                }
            });

            $("form#addMemberForm").submit(function (e) {
                e.preventDefault();

                const memberIdentifiers = $("#member_identifiers").val();
                $.post("room.php?room_id=" + roomId + "&channel_id=" + channelId, { member_identifiers: memberIdentifiers, add_member: true }, function (data) {
                    $(".message-feedback").html(data).show();
                    $("#member_identifiers").val("");
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>Phòng Chat</h2>
        <div class="row">
            <div class="col-md-4">
                <h4>Các kênh</h4>
                <ul class="list-group mb-3">
                    <?php while ($channel = $channels->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <a href="room.php?room_id=<?= $room_id ?>&channel_id=<?= $channel['id'] ?>">
                                <?= htmlspecialchars($channel['channel_name']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">Thêm thành
                        viên</button>
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <?php if ($channel_id && $messages): ?>
                    <h4>Chat trong kênh</h4>
                    <div class="chat-box">
                        <?php while ($message = $messages->fetch_assoc()): ?>
                            <div>
                                <a href="viewprofile.php?id=<?= $message['user_id'] ?>">
                                    <img src="<?= $message['image'] ?>" alt="User Image" class="user-image">
                                </a>
                                <strong><?= htmlspecialchars($message['full_name']) ?>:</strong>
                                <p><?= htmlspecialchars($message['content']) ?></p>
                                <small class="text-muted"><?= htmlspecialchars($message['created_at']) ?></small>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <form id="sendMessageForm" method="post">
                        <textarea id="message" name="message" rows="3" class="form-control mt-3"
                            placeholder="Nhập tin nhắn..." style="display: none;"></textarea>
                        <button type="submit" class="btn btn-primary mt-2">Gửi</button>
                    </form>
                <?php else: ?>
                    <p class="text-muted">Vui lòng chọn kênh để bắt đầu trò chuyện.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Thành Viên -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">Thêm Thành Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMemberForm" method="post">
                        <div class="mb-3">
                            <label for="member_identifiers" class="form-label">Tên đăng nhập, Email hoặc Họ tên (mỗi
                                người trên một dòng)</label>
                            <textarea id="member_identifiers" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="message-feedback" style="display: none;"></div>
                        <button type="submit" class="btn btn-success">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>