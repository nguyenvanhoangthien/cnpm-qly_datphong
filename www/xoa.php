<?php
// Kiểm tra quyền admin
require_once 'check_admin.php';

//ketnoi
require_once 'ketnoi.php';

//lay du id can xoa
$phid = intval($_GET['sid']);

//cau lệnh sql
$xoa_sql = "DELETE FROM phonghop WHERE id=$phid";

if (mysqli_query($conn, $xoa_sql)) {
    header("Location: lietke.php");
    exit();
} else {
    header("Location: lietke.php?error=1");
    exit();
}

mysqli_close($conn);
?>