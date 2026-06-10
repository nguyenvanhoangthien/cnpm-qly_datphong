<?php
// Kiểm tra quyền admin
require_once 'check_admin.php';

//kết nối cơ sở dữ liệu
require_once 'ketnoi.php';

//nhận dữ liệu từ form
$tp = mysqli_real_escape_string($conn, $_POST['tenphong']);
$ddiem = mysqli_real_escape_string($conn, $_POST['diadiem']);
$succhua = intval($_POST['succhua']);
$mota = isset($_POST['mota']) ? mysqli_real_escape_string($conn, $_POST['mota']) : '';
$sid = intval($_POST['sid']);

//viết câu lệnh sql cập nhật
$updatesql = "UPDATE phonghop SET tenphong='$tp', diadiem='$ddiem', succhua=$succhua, mota='$mota' WHERE id=$sid";

//thực thi câu lệnh
if (mysqli_query($conn, $updatesql)) {
    header("Location: lietke.php");
    exit();
} else {
    header("Location: lietke.php?error=1");
    exit();
}

mysqli_close($conn);
?> 
