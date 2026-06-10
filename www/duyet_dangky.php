<?php
session_start();

// Kiểm tra quyền admin
require_once 'check_admin.php';
require_once 'ketnoi.php';

$id = intval($_GET['id']);
$action = $_GET['action'];

if ($action == 'tuchoi') {
    $trangthai = 'Từ chối';
} else {
    header("Location: phonghop.php?error=invalid_action");
    exit();
}

$sql = "UPDATE dangky SET trangthai = '$trangthai' WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: phonghop.php");
    exit();
} else {
    header("Location: phonghop.php?error=1");
    exit();
}

mysqli_close($conn);
?>
