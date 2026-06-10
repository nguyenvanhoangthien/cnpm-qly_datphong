<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require_once 'ketnoi.php';

$id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');

// Kiểm tra quyền xóa
$check_sql = "SELECT user_id FROM dangky WHERE id = $id";
$check_result = mysqli_query($conn, $check_sql);
$dangky = mysqli_fetch_assoc($check_result);

if (!$dangky) {
    header("Location: phonghop.php?error=not_found");
    exit();
}

// Chỉ admin hoặc người đặt mới được xóa
if (!$is_admin && $dangky['user_id'] != $current_user_id) {
    header("Location: phonghop.php?error=no_permission");
    exit();
}

// Xóa lịch đăng ký
$xoa_sql = "DELETE FROM dangky WHERE id = $id";

if (mysqli_query($conn, $xoa_sql)) {
    header("Location: phonghop.php");
    exit();
} else {
    header("Location: phonghop.php?error=1");
    exit();
}

mysqli_close($conn);
?>
