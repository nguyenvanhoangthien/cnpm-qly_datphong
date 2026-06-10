<?php
session_start();
require_once 'ketnoi.php';

// Kiểm tra kết nối
if (!$conn) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Kiểm tra dữ liệu POST - nếu không có thì chuyển về trang login
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    header("Location: login.html");
    exit();
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Mã hóa mật khẩu
$hashed_password = MD5($password);

// Kiểm tra đăng nhập với bảng users 
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi query
if (!$result) {
    die("Lỗi SQL: " . mysqli_error($conn) . "<br>Có thể bảng 'users' chưa được tạo. Vui lòng chạy file create_users_table.sql");
}

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // Lưu thông tin vào session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role'] = $user['role'];
    
    // Cập nhật last_login
    $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ".$user['id'];
    mysqli_query($conn, $update_sql);
    
    header("Location: index.php");
    exit();
} else {
    header("Location: login.html?error=1");
    exit();
}

mysqli_close($conn);
?>