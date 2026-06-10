<?php
// Kết nối database
require_once 'ketnoi.php';

// Kiểm tra kết nối
if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Kiểm tra dữ liệu POST
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die("Vui lòng điền đầy đủ thông tin!");
}

// Lấy dữ liệu từ form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$email = mysqli_real_escape_string($conn, $_POST['email']);
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);

// Kiểm tra mật khẩu khớp
if ($password !== $confirm_password) {
    header("Location: register.html?error=password_mismatch");
    exit();
}

// Kiểm tra username đã tồn tại
$check_sql = "SELECT * FROM users WHERE username = '$username'";
$check_result = mysqli_query($conn, $check_sql);

// Kiểm tra lỗi query
if (!$check_result) {
    die("Lỗi SQL: " . mysqli_error($conn) . "<br>Có thể bảng 'users' chưa được tạo. Vui lòng chạy file create_users_table.sql");
}

if (mysqli_num_rows($check_result) > 0) {
    header("Location: register.html?error=username_exists");
    exit();
}

// Mã hóa mật khẩu
$hashed_password = MD5($password);

// Thêm user mới
$sql = "INSERT INTO users (username, password, email, fullname, role) 
        VALUES ('$username', '$hashed_password', '$email', '$fullname', 'teacher')";

if (mysqli_query($conn, $sql)) {
    header("Location: login.html?success=1");
    exit();
} else {
    header("Location: register.html?error=1");
    exit();
}

mysqli_close($conn);
?>
