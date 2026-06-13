<?php
// Kết nối database
require_once 'ketnoi.php';

if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Xử lý POST (đăng ký)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        die("Vui lòng điền đầy đủ thông tin!");
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);

    // Kiểm tra độ dài mật khẩu
    $pw_len = strlen($password);
    if ($pw_len < 8 || $pw_len > 16) {
        header("Location: register.php?error=password_length");
        exit();
    }

    // Kiểm tra mật khẩu khớp
    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    // Kiểm tra username đã tồn tại
    $check_result = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (!$check_result) {
        die("Lỗi SQL: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: register.php?error=username_exists");
        exit();
    }

    $hashed_password = MD5($password);
    $sql = "INSERT INTO users (username, password, email, fullname, role) 
            VALUES ('$username', '$hashed_password', '$email', '$fullname', 'teacher')";

    if (mysqli_query($conn, $sql)) {
        header("Location: login.html?success=1");
        exit();
    } else {
        header("Location: register.php?error=1");
        exit();
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký tài khoản - Hệ thống Quản lý Đặt phòng họp</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <header>
        <img class="logo" src="images/logo.png" alt="Logo Khoa CNTT" />
        <h1>Hệ thống Quản lý Đặt phòng họp</h1>
    </header>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center">Đăng ký tài khoản</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">
                                <?php
                                    switch ($_GET['error']) {
                                        case 'password_length': echo 'Mật khẩu phải từ 8 đến 16 ký tự!'; break;
                                        case 'password_mismatch': echo 'Mật khẩu xác nhận không khớp!'; break;
                                        case 'username_exists': echo 'Tên đăng nhập đã tồn tại!'; break;
                                        default: echo 'Đăng ký thất bại, vui lòng thử lại!';
                                    }
                                ?>
                            </div>
                        <?php endif; ?>

                        <form action="register.php" method="post">
                            <div class="form-group">
                                <label for="username">Tên đăng nhập</label>
                                <input type="text" id="username" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <input type="password" id="password" class="form-control" name="password" minlength="8" maxlength="16" required>
                                <small class="form-text text-muted">Mật khẩu từ 8 đến 16 ký tự.</small>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Xác nhận mật khẩu</label>
                                <input type="password" id="confirm_password" class="form-control" name="confirm_password" minlength="8" maxlength="16" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="fullname">Họ và tên</label>
                                <input type="text" id="fullname" class="form-control" name="fullname" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                        </form>

                        <div class="text-center mt-3">
                            <p>Đã có tài khoản? <a href="login.html">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
