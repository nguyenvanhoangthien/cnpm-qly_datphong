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
//viết câu lệnh sql thêm dữ liệu
$themsql = "INSERT INTO phonghop (diadiem, tenphong, succhua, mota) VALUES ('$ddiem', '$tp', $succhua, '$mota')";

//thực thi câu lệnh
if (mysqli_query($conn, $themsql)) {
    header("Location: lietke.php");
    exit();
} else {
    header("Location: datphong.html?error=1");
    exit();
}

mysqli_close($conn);
?>