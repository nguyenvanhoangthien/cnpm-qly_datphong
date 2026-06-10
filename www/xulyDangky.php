<?php
// Kết nối database
require_once 'ketnoi.php';

// Lấy dữ liệu từ form
$id_phong = intval($_POST['id_phong']);
$user_id = intval($_POST['user_id']);
$ngaydat = mysqli_real_escape_string($conn, $_POST['ngaydat']);
$ngaysudung = mysqli_real_escape_string($conn, $_POST['ngaysudung']);
$giobatdau = mysqli_real_escape_string($conn, $_POST['giobatdau']);
$gioketthuc = mysqli_real_escape_string($conn, $_POST['gioketthuc']);
$muctieu = isset($_POST['muctieu']) ? mysqli_real_escape_string($conn, $_POST['muctieu']) : '';

// Kiểm tra thời gian đăng ký phải là tương lai
$datetime_sudung = $ngaysudung . ' ' . $giobatdau;
$current_datetime = date('Y-m-d H:i:s');

if ($datetime_sudung <= $current_datetime) {
    mysqli_close($conn);
    header("Location: dangkyphong.php?error=past_time");
    exit();
}

// Kiểm tra trùng giờ (chỉ với đăng ký đã duyệt)
$check_sql = "SELECT * FROM dangky 
              WHERE id_phong = $id_phong 
              AND ngaysudung = '$ngaysudung' 
              AND trangthai = 'Đã duyệt'
              AND (
                  (giobatdau < '$gioketthuc' AND gioketthuc > '$giobatdau')
              )";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    mysqli_close($conn);
    header("Location: dangkyphong.php?error=conflict");
    exit();
}

// Câu lệnh INSERT - Tự động duyệt
$sql = "INSERT INTO dangky (id_phong, user_id, ngaydat, ngaysudung, giobatdau, gioketthuc, muctieu, trangthai) 
        VALUES ($id_phong, $user_id, '$ngaydat', '$ngaysudung', '$giobatdau', '$gioketthuc', '$muctieu', 'Đã duyệt')";

// Thực thi câu lệnh
if (mysqli_query($conn, $sql)) {
    header("Location: phonghop.php");
    exit();
} else {
    header("Location: dangkyphong.php?error=1");
    exit();
}

// Đóng kết nối
mysqli_close($conn);
?>
