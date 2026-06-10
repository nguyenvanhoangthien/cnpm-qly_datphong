<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

require_once 'ketnoi.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : $username;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'teacher';

// Lấy thông tin chi tiết từ database
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông tin cá nhân - Khoa CNTT</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
                <li style="float:right" class="active"><a href="profile.php"><i class="fa fa-user"></i> <?php echo $fullname; ?></a></li>
                <li style="float:right"><a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3><i class="fa fa-user"></i> Thông tin cá nhân</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Tên đăng nhập</th>
                                <td><?php echo $user['username']; ?></td>
                            </tr>
                            <tr>
                                <th>Họ và tên</th>
                                <td><?php echo $user['fullname']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $user['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Vai trò</th>
                               <td>
                                    <?php 
                                        if ($user['role'] == 'admin') {
                                            echo '<span class="badge badge-danger">Quản trị viên</span>';
                                        } elseif ($user['role'] == 'teacher') {
                                            echo '<span class="badge badge-info">Giảng viên</span>';
                                        } elseif ($user['role'] == 'student') {
                                            echo '<span class="badge badge-success">Sinh viên</span>';
                                        } else {
                                            echo '<span class="badge badge-secondary">Người dùng</span>';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo tài khoản</th>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <th>Đăng nhập lần cuối</th>
                                <td><?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Chưa có'; ?></td>
                            </tr>
                        </table>
                        
                        <div class="text-center mt-3">
                            <a href="index.php" class="btn btn-secondary">Quay lại trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Phần footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-content">
                <h5>Khoa Công nghệ thông tin - Trường Kỹ thuật và Công nghệ - Trường Đại học Trà Vinh © 2025</h5>
                <h6>School of Information Technology, College of Engineering and Technology - Tra Vinh University © 2025</h6>
                
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
        </div>
    </footer>
</body>
</html>
<?php mysqli_close($conn); ?>
