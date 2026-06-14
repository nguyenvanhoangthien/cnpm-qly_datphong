<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : $_SESSION['username'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Danh sách đăng ký phòng họp - Khoa CNTT</title>
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
        <h1 class="title">Hệ thống Quản lý Đặt phòng</h1>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="lietke.php">Quản lý phòng</a></li>
                <li class="active"><a href="phonghop.php">Lịch sử dụng</a></li>
                <li style="float:right"><a href="profile.php"><i class="fa fa-user"></i> <?php echo $fullname; ?></a></li>
                <li style="float:right"><a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <h1>Danh sách đăng ký phòng họp</h1>
        <?php if ($is_admin): ?>
        <a href="baocao.php" class="btn btn-success mb-3">
            <i class="fa fa-file-excel"></i> Xuất báo cáo thống kê (CSV)
        </a>
        <?php endif; ?>
        
        <?php
        // Hiển thị thông báo thành công
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo '<strong><i class="fa fa-check-circle"></i> Thành công!</strong><br>';
            echo 'Đăng ký phòng họp thành công. Đăng ký của bạn đã được tự động duyệt.';
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
        }
        
        // Hiển thị thông báo lỗi khác
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            echo '<div class="alert alert-dismissible fade show" role="alert">';
            
            switch ($error) {
                case 'no_permission':
                    echo '<div class="alert-warning">';
                    echo '<strong><i class="fa fa-exclamation-triangle"></i> Không có quyền!</strong><br>';
                    echo 'Bạn không có quyền thực hiện thao tác này.';
                    echo '</div>';
                    break;
                    
                case 'not_found':
                    echo '<div class="alert-danger">';
                    echo '<strong><i class="fa fa-times-circle"></i> Không tìm thấy!</strong><br>';
                    echo 'Đăng ký không tồn tại hoặc đã bị xóa.';
                    echo '</div>';
                    break;
                    
                case '1':
                    echo '<div class="alert-danger">';
                    echo '<strong><i class="fa fa-times-circle"></i> Lỗi hệ thống!</strong><br>';
                    echo 'Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại sau.';
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
        
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>STT</th>
                    <th>Tên phòng</th>
                    <th>Địa điểm</th>
                    <th>Người đặt</th>
                    <th>Ngày đặt</th>
                    <th>Ngày sử dụng</th>
                    <th>Giờ bắt đầu</th>
                    <th>Giờ kết thúc</th>
                    <th>Mục tiêu</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Kết nối database
                    require_once 'ketnoi.php';
                    
                    // Lấy danh sách đăng ký phòng
                    $sql = "SELECT d.*, p.tenphong, p.diadiem, u.fullname, u.username 
                            FROM dangky d 
                            LEFT JOIN phonghop p ON d.id_phong = p.id 
                            LEFT JOIN users u ON d.user_id = u.id 
                            ORDER BY d.trangthai ASC, d.ngaysudung DESC, d.giobatdau ASC";
                    $result = mysqli_query($conn, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        $stt = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Kiểm tra quyền sửa/xóa: admin hoặc người đặt (chỉ khi chờ duyệt)
                            $can_edit = (($is_admin || $row['user_id'] == $current_user_id) && $row['trangthai'] == 'Chờ duyệt');
                            $trangthai = isset($row['trangthai']) ? $row['trangthai'] : 'Chờ duyệt';
                ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td><?php echo $row['tenphong']; ?></td>
                                <td><?php echo $row['diadiem']; ?></td>
                                <td><?php echo $row['fullname'] ? $row['fullname'] : $row['username']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['ngaydat'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['ngaysudung'])); ?></td>
                                <td><?php echo substr($row['giobatdau'], 0, 5); ?></td>
                                <td><?php echo substr($row['gioketthuc'], 0, 5); ?></td>
                                <td><?php echo $row['muctieu']; ?></td>
                                <td>
                                    <?php 
                                        if ($trangthai == 'Chờ duyệt') {
                                            echo '<span class="badge badge-warning">Chờ duyệt</span>';
                                        } elseif ($trangthai == 'Đã duyệt') {
                                            echo '<span class="badge badge-success">Đã duyệt</span>';
                                        } else {
                                            echo '<span class="badge badge-danger">Từ chối</span>';
                                        }
                                    ?>
                                </td>
                                <td style="white-space: nowrap;">
                                    <?php if ($is_admin): ?>
                                        <!-- Admin: Có thể từ chối đăng ký đã duyệt -->
                                        <?php if ($trangthai == 'Đã duyệt'): ?>
                                            <a href="duyet_dangky.php?id=<?php echo $row['id']; ?>&action=tuchoi" 
                                               onclick="return confirm('Bạn có muốn từ chối đăng ký này không?');" 
                                               class="btn btn-warning btn-sm">Từ chối</a>
                                            <br>
                                        <?php endif; ?>
                                        <!-- Admin: Luôn có quyền sửa/xóa -->
                                        <a href="edit_dangky.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Sửa</a>
                                        <a onclick="return confirm('Bạn có muốn xóa lịch đăng ký này không?');" 
                                           href="xoa_dangky.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                                    <?php else: ?>
                                        <!-- User thường: Chỉ sửa/xóa đăng ký của mình khi đã duyệt -->
                                        <?php if ($row['user_id'] == $current_user_id && $trangthai == 'Đã duyệt'): ?>
                                            <a href="edit_dangky.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Sửa</a>
                                            <a onclick="return confirm('Bạn có muốn xóa lịch đăng ký này không?');" 
                                               href="xoa_dangky.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                                        <?php elseif ($row['user_id'] != $current_user_id): ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='11' class='text-center'>Chưa có lịch đăng ký nào</td></tr>";
                    }
                    
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>
        </div>
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
</body>
</html>
