<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Kết nối database
require_once 'ketnoi.php';

// Lấy thông tin user đang đăng nhập
$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];
$current_fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : $current_username;
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');

// Lấy ID đăng ký từ URL
$dangky_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đăng ký
$dangky_sql = "SELECT d.*, p.tenphong, p.diadiem 
               FROM dangky d 
               LEFT JOIN phonghop p ON d.id_phong = p.id 
               WHERE d.id = $dangky_id";
$dangky_result = mysqli_query($conn, $dangky_sql);
$dangky = mysqli_fetch_assoc($dangky_result);

if (!$dangky) {
    header("Location: phonghop.php?error=not_found");
    exit();
}

// Kiểm tra quyền sửa
if (!$is_admin && $dangky['user_id'] != $current_user_id) {
    header("Location: phonghop.php?error=no_permission");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sửa đăng ký phòng họp - Khoa CNTT</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <img class="logo" src="images/logo.png" alt="Logo Khoa CNTT" />
        <h1>Hệ thống Quản lý Đặt phòng họp</h1>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="lietke.php">Quản lý phòng</a></li>
                <li><a href="phonghop.php">Lịch sử dụng</a></li>
                <li style="float:right"><a href="profile.php"><i class="fa fa-user"></i> <?php echo $current_fullname; ?></a></li>
                <li style="float:right"><a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <h1>Sửa đăng ký sử dụng phòng họp</h1>
        
        <?php
        // Hiển thị thông báo lỗi
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            echo '<div class="alert alert-dismissible fade show" role="alert">';
            
            switch ($error) {
                case 'conflict':
                    echo '<div class="alert-danger">';
                    echo '<strong><i class="fa fa-exclamation-triangle"></i> Trùng lịch đặt phòng!</strong><br>';
                    
                    // Hiển thị thông tin chi tiết về xung đột
                    if (isset($_GET['info'])) {
                        $conflict_info = json_decode(urldecode($_GET['info']), true);
                        if ($conflict_info) {
                            echo '<div class="mt-2">';
                            echo '<strong>Thông tin đăng ký trùng lịch:</strong><br>';
                            echo '• <strong>Phòng:</strong> ' . htmlspecialchars($conflict_info['tenphong']) . '<br>';
                            echo '• <strong>Ngày:</strong> ' . date('d/m/Y', strtotime($conflict_info['ngaysudung'])) . '<br>';
                            echo '• <strong>Thời gian:</strong> ' . $conflict_info['giobatdau'] . ' - ' . $conflict_info['gioketthuc'] . '<br>';
                            echo '• <strong>Người đặt:</strong> ' . htmlspecialchars($conflict_info['nguoidat']) . '<br>';
                            echo '</div>';
                            echo '<div class="mt-2">';
                            echo '<small class="text-muted"><i class="fa fa-info-circle"></i> Vui lòng chọn thời gian khác.</small>';
                            echo '</div>';
                        }
                    } else {
                        echo 'Phòng này đã được đặt trong khoảng thời gian bạn chọn. Vui lòng chọn thời gian khác.';
                    }
                    echo '</div>';
                    break;
                    
                case 'past_time':
                    echo '<div class="alert-warning">';
                    echo '<strong><i class="fa fa-clock"></i> Thời gian không hợp lệ!</strong><br>';
                    echo 'Không thể cập nhật đăng ký với thời gian trong quá khứ. Vui lòng chọn thời gian từ hiện tại trở đi.';
                    echo '</div>';
                    break;
                    
                default:
                    echo '<div class="alert-info">';
                    echo '<strong><i class="fa fa-info-circle"></i> Thông báo!</strong><br>';
                    echo 'Có thông báo từ hệ thống.';
                    echo '</div>';
                    break;
            }
            
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
        }
        ?>
        
        <form action="update_dangky.php" method="post">
            <input type="hidden" name="id" value="<?php echo $dangky_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $dangky['user_id']; ?>">
            
            <div class="form-group">
                <label for="tenphong">Tên Phòng</label>
                <input type="text" id="tenphong" class="form-control" 
                       value="<?php echo $dangky['tenphong']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="diadiem">Địa điểm</label>
                <input type="text" id="diadiem" class="form-control" 
                       value="<?php echo $dangky['diadiem']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="nguoidat">Người đặt</label>
                <input type="text" id="nguoidat" class="form-control" 
                       value="<?php echo $current_fullname; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="ngaydat">Ngày đặt</label>
                <input type="date" id="ngaydat" class="form-control" name="ngaydat" 
                       value="<?php echo $dangky['ngaydat']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="ngaysudung">Ngày sử dụng</label>
                <input type="date" id="ngaysudung" class="form-control" name="ngaysudung" 
                       value="<?php echo $dangky['ngaysudung']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="giobatdau">Giờ bắt đầu</label>
                <input type="time" id="giobatdau" class="form-control" name="giobatdau" 
                       value="<?php echo $dangky['giobatdau']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="gioketthuc">Giờ kết thúc</label>
                <input type="time" id="gioketthuc" class="form-control" name="gioketthuc" 
                       value="<?php echo $dangky['gioketthuc']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="muctieu">Mục tiêu sử dụng</label>
                <textarea id="muctieu" class="form-control" name="muctieu" rows="3"><?php echo $dangky['muctieu']; ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="phonghop.php" class="btn btn-secondary">Quay lại</a>
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

    <script>
        // Validation thời gian
        document.querySelector('form').addEventListener('submit', function(e) {
            const ngaysudung = document.getElementById('ngaysudung').value;
            const giobatdau = document.getElementById('giobatdau').value;
            const gioketthuc = document.getElementById('gioketthuc').value;
            
            // Kiểm tra ngày sử dụng phải từ hôm nay trở đi
            const today = new Date().toISOString().split('T')[0];
            if (ngaysudung < today) {
                alert('Ngày sử dụng phải từ hôm nay trở đi!');
                e.preventDefault();
                return false;
            }
            
            // Kiểm tra nếu là hôm nay thì giờ phải từ hiện tại trở đi
            if (ngaysudung === today) {
                const now = new Date();
                const currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                                  now.getMinutes().toString().padStart(2, '0');
                if (giobatdau <= currentTime) {
                    alert('Giờ bắt đầu phải sau thời gian hiện tại!');
                    e.preventDefault();
                    return false;
                }
            }
            
            // Kiểm tra giờ kết thúc phải sau giờ bắt đầu
            if (gioketthuc <= giobatdau) {
                alert('Giờ kết thúc phải sau giờ bắt đầu!');
                e.preventDefault();
                return false;
            }
        });
        
        // Set ngày tối thiểu là hôm nay
        document.getElementById('ngaysudung').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>
