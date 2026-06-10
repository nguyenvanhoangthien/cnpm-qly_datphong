<?php
session_start();

// KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION["username"];
$fullname = isset($_SESSION["fullname"]) ? $_SESSION["fullname"] : $username;
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
$user_id = $_SESSION['user_id'];

// Kết nối database để lấy thống kê
require_once 'ketnoi.php';

// Thống kê
$total_phong = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM phonghop"))['total'];
$total_dangky = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM dangky"))['total'];
$cho_duyet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM dangky WHERE trangthai='Chờ duyệt'"))['total'];
$da_duyet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM dangky WHERE trangthai='Đã duyệt'"))['total'];

// Lấy đăng ký của user hiện tại
if ($is_admin) {
    $my_dangky_sql = "SELECT d.*, p.tenphong, u.fullname 
                      FROM dangky d 
                      LEFT JOIN phonghop p ON d.id_phong = p.id 
                      LEFT JOIN users u ON d.user_id = u.id 
                      WHERE d.trangthai = 'Chờ duyệt'
                      ORDER BY d.created_at DESC LIMIT 5";
} else {
    $my_dangky_sql = "SELECT d.*, p.tenphong 
                      FROM dangky d 
                      LEFT JOIN phonghop p ON d.id_phong = p.id 
                      WHERE d.user_id = $user_id 
                      ORDER BY d.created_at DESC LIMIT 5";
}
$my_dangky_result = mysqli_query($conn, $my_dangky_sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh sách đăng ký phòng họp - Khoa CNTT</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <img class="logo" src="images/logo.png" alt="Logo Khoa CNTT" />
        <h1 class="title">Hệ thống Quản lý Đặt phòng</h1>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="lietke.php">Quản lý phòng</a></li>
                    <li><a href="phonghop.php">Lịch sử dụng</a></li>
                    <li style="float:right"><a href="profile.php"><i class="fa fa-user"></i> <?php echo $fullname; ?></a></li>
                    <li style="float:right"><a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                </ul>
            </nav>
    </header>
    <section class="features">
        <h2 class="section-title">Tính năng nổi bật của hệ thống</h2>
        <div class="feature">
            <img src="images/1.png" alt="Đặt phòng họp và Lab">
            <h3>Đặt phòng thông minh</h3>
            <p>
                Tìm kiếm và đặt phòng theo ngày giờ, kiểm tra trùng lịch và xem phòng trống ngay lập tức
            </p>
        </div>
        <div class="feature">
            <img src="images/2.png" alt="Quản lý lịch và thiết bị">
            <h3>Quản lý Thiết bị & Dịch vụ</h3>
            <p>
                 Giảng viên/Trợ giảng có thể quản lý lịch sử dụng, 
                kiểm tra thiết bị và nhận thông báo nhắc lịch.
            </p>
        </div>
        <div class="feature">
            <img src="images/3.jpg" alt="Báo cáo phân tích">
            <h3>Duyệt & Quản trị hệ thống</h3>
            <p>
                 Admin được phép duyệt yêu cầu đặt phòng,
                 theo dõi tỷ lệ sử dụng và tạo báo cáo.
            </p>
        </div>
    </section>

<!-- Phần footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-content">
            <h5>Khoa Công nghệ thông tin - Trường Kỹ thuật và Công nghệ - Đại học Trà Vinh © 2026</h5>
            <h6>School of Information Technology, College of Engineering and Technology - Tra Vinh University © 2026</h6>
            <div class="decorator-line"></div>
            <ul class="contact-info">
                <li>
                    <i class="fa-solid fa-location-dot"></i>
                    Số 126, Nguyễn Thiện Thành, Khóm 4, Phường Hòa Thuận, Tỉnh Vĩnh Long
                </li>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    (+84) 294.3855246 (Ext: 135 - 203)
                </li>
                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:khoacntt@tvu.edu.vn">khoacntt@tvu.edu.vn</a>
                </li>
                <li>
                    <i class="fa-solid fa-link"></i>
                    <a href="https://fit.tvu.edu.vn" target="_blank">https://fit.tvu.edu.vn</a>
                </li>
            </ul>

            <div class="tvu-logo">
                <img src="images/7.jpg" alt="Tra Vinh University Logo" class="logo-snippet">
            </div>
            <hr class="footer-hr">
        </div>
        <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
            <a href="#" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
   </footer>
</body>
</html>
