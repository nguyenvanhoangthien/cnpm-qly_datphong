<?php
// Kiểm tra quyền admin
require_once 'check_admin.php';

//lay du id can sua
$id = $_GET['sid'];
//echo $id;
//ketnoi
require_once 'ketnoi.php';
//cau lệnh lay thong tin ve phong co id =$id
$edit_sql = "SELECT * FROM phonghop WHERE id=$id";
$result = mysqli_query($conn, $edit_sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit phong hop</title>
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
        <h1 class="title">Hệ thống Quản lý Đặt phòng họp</h1>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Trang chủ</a></li>
        </nav>
    </header>
        <div class="container">
            <h1>Thêm danh sách phòng mới</h1>
            <form action="update.php" method="post">
                <input type="hidden" name="sid" value="<?php echo $id;?>" id="">
                <div class="form-group">
                    <label for="tenphong">Tên Phòng</label>
                    <input type="text" id="tenphong"  class="form-control" 
                    name="tenphong" value="<?php echo  $row['tenphong']?>">
                </div>
                <div class="form-group">
                    <label for="diadiem">Địa điểm</label>
                    <input type="text" name="diadiem" id="diadiem"
                     class="form-control" value="<?php echo  $row['diadiem']?>">
                </div>
                <div class="form-group">
                    <label for="succhua">Sức chứa</label>
                    <input type="number" id="succhua" name="succhua"
                     class="form-control" value="<?php echo  $row['succhua']?>" required>
                </div>
                <div class="form-group">
                    <label for="mota">Mô tả</label>
                    <textarea id="mota" name="mota" class="form-control" rows="3"><?php echo isset($row['mota']) ? $row['mota'] : ''; ?></textarea>
                </div>
                <button class="btn btn-success">Cập nhật phòng đặt</button>
            </form>
        </div>    
    <!-- Phần footer -->
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <h5>Khoa Công nghệ thông tin - Trường Kỹ thuật và Công nghệ - Trường Đại học Trà Vinh © 2026</h5>
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
    </div>
</footer>
    <script src="booking.js"></script>
</body>
</html>