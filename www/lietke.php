<?php
session_start();
// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý lịch đặt phòng họp - Khoa CNTT</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <img class="logo" src="images/logo.png" alt="Logo Khoa CNTT" />
        <h1 class="title">Hệ thống Quản lý Đặt phòng</h1>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Trang chủ</a></li>
                <li class="active"><a href="lietke.php">Quản lý phòng</a></li>
                <li><a href="phonghop.php">Lịch sử dụng</a></li>
                <li style="float:right"><a href="profile.php"><i class="fa fa-user"></i> <?php echo $_SESSION['fullname']; ?></a></li>
                <li style="float:right"><a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
        </nav>
    </header>
    <div class="container">
        <h1>Danh sách phòng</h1>
        <?php if ($is_admin): ?>
            <a href="datphong.html" class="btn btn-success">Thêm Phòng Mới</a>
        <?php endif; ?>
        <div class="table-responsive">
        <table class="table">
    <thead class="thead-dark">
      <tr>
        <th>Tên Phòng</th>
        <th>Địa Điểm</th>
        <th>Sức Chứa</th>
        <th>Mô tả</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
        <?php
            //kết nối
            require_once 'ketnoi.php';
            //câu lệnh kết nối
            $lietke_sql = "SELECT * FROM phonghop order by succhua, diadiem";
            //thuc thi câu lệnh
            $result = mysqli_query($conn, $lietke_sql);
            //duyệt qua result và in ra

            while ($r = mysqli_fetch_assoc($result)){
                ?>
                
                <tr>
                    <td><?php echo $r['tenphong'];?></td>
                    <td><?php echo $r['diadiem'];?></td>
                    <td><?php echo $r['succhua'];?></td>
                    <td><?php echo isset($r['mota']) ? $r['mota'] : '-';?></td>
                    <td>
                        <?php if ($is_admin): ?>
                            <a onclick="return confirm('Bạn có muốn xoá phòng này không?');" href="xoa.php?sid=<?php echo $r['id'];?>" class="btn btn-danger">Xoá</a> 
                            <a href="edit.php?sid=<?php echo $r['id'];?>" class="btn btn-info">Sửa</a>
                        <?php endif; ?>
                        <a href="dangkyphong.php?sid=<?php echo $r['id'];?>" class="btn btn-primary">Đăng ký</a>
                    </td>
                </tr>
                <?php
            }
        ?>
         </tbody>
  </table>
  </div>
    </div>
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
