<?php
include('db.php');
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .navbar {
        background-color: #004080;
        min-height: 100px;
    }

    .navbar-nav .nav-link {
        color: #ffffff;
    }

    .navbar-nav .nav-link:hover {
        color: #f0f0f0;
    }

    .dropdown-menu {
        background-color: #004080;
    }

    .dropdown-item {
        color: #ffffff;
    }

    .dropdown-item:hover {
        background-color: #0059b3;
    }

    .btn-custom {
        margin-left: 10px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .avatar {
            width: 30px;
            height: 30px;
        }

        .dropdown-menu {
            position: static !important;
            float: none;
            width: auto;
            text-align: left;
        }
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- <a class="navbar-brand" href="/index.php">Chat</a> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="/index.php">Trang chủ</a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>

                        <li class="nav-item">
                            <a class="nav-link active" href="/index2.php">Trang chủ</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'): ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="/quanly.php">Quản lý kênh & phòng</a>
                        </li>
                    <?php endif; ?>

                </ul>

                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>

                        <div class="dropdown ms-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                id="dropdownAvatar" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?php echo $_SESSION['image']; ?>" alt="Avatar" class="avatar">
                                <span class="ms-2 text-light"><?php echo $_SESSION['full_name']; ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAvatar">
                                <li><a class="dropdown-item"
                                        href="/viewprofile.php?id=<?php echo $_SESSION['user_id']; ?>">Quản lý tài khoản</a>
                                </li>
                                <li><a class="dropdown-item" href="/logout.php">Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="/login.php" class="btn btn-outline-light btn-custom">Đăng nhập</a>
                        <a href="/register.php" class="btn btn-light btn-custom">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>