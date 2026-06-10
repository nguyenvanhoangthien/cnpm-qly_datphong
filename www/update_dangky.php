<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require_once 'ketnoi.php';

$id = intval($_POST['id']);
$user_id = intval($_POST['user_id']);
$ngaydat = mysqli_real_escape_string($conn, $_POST['ngaydat']);
$ngaysudung = mysqli_real_escape_string($conn, $_POST['ngaysudung']);
$giobatdau = mysqli_real_escape_string($conn, $_POST['giobatdau']);
$gioketthuc = mysqli_real_escape_string($conn, $_POST['gioketthuc']);
$muctieu = mysqli_real_escape_string($conn, $_POST['muctieu']);

$current_user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');

// Kiểm tra quyền sửa
if (!$is_admin && $user_id != $current_user_id) {
    header("Location: phonghop.php?error=no_permission");
    exit();
}

// Lấy id_phong từ đăng ký hiện tại
$get_phong_sql = "SELECT id_phong FROM dangky WHERE id = $id";
$get_phong_result = mysqli_query($conn, $get_phong_sql);
$phong_data = mysqli_fetch_assoc($get_phong_result);
$id_phong = $phong_data['id_phong'];

// Kiểm tra thời gian đăng ký phải là tương lai
$datetime_sudung = $ngaysudung . ' ' . $giobatdau;
$current_datetime = date('Y-m-d H:i:s');

if ($datetime_sudung <= $current_datetime) {
    mysqli_close($conn);
    header("Location: edit_dangky.php?id=$id&error=past_time");
    exit();
}

// Kiểm tra trùng giờ (loại trừ bản ghi hiện tại, chỉ với đăng ký đã duyệt)
$check_sql = "SELECT * FROM dangky 
              WHERE id_phong = $id_phong 
              AND ngaysudung = '$ngaysudung' 
              AND id != $id
              AND trangthai = 'Đã duyệt'
              AND (
                  (giobatdau < '$gioketthuc' AND gioketthuc > '$giobatdau')
              )";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    mysqli_close($conn);
    header("Location: edit_dangky.php?id=$id&error=conflict");
    exit();
}

// Cập nhật lịch đăng ký
$update_sql = "UPDATE dangky 
               SET ngaydat = '$ngaydat', 
                   ngaysudung = '$ngaysudung', 
                   giobatdau = '$giobatdau', 
                   gioketthuc = '$gioketthuc', 
                   muctieu = '$muctieu' 
               WHERE id = $id";

if (mysqli_query($conn, $update_sql)) {
    header("Location: phonghop.php");
    exit();
} else {
    header("Location: phonghop.php?error=1");
    exit();
}

mysqli_close($conn);
?>
