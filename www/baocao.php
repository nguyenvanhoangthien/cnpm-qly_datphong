<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Chỉ admin mới được xuất báo cáo
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

require_once 'ketnoi.php';

// Thống kê số lần đặt theo từng phòng
$sql_thongke = "SELECT p.tenphong, p.diadiem, p.succhua,
                    COUNT(d.id) AS tong_dat,
                    SUM(CASE WHEN d.trangthai = 'Đã duyệt' THEN 1 ELSE 0 END) AS da_duyet,
                    SUM(CASE WHEN d.trangthai = 'Chờ duyệt' THEN 1 ELSE 0 END) AS cho_duyet,
                    SUM(CASE WHEN d.trangthai = 'Từ chối' THEN 1 ELSE 0 END) AS tu_choi
                FROM phonghop p
                LEFT JOIN dangky d ON p.id = d.id_phong
                GROUP BY p.id, p.tenphong, p.diadiem, p.succhua
                ORDER BY tong_dat DESC";
$result = mysqli_query($conn, $sql_thongke);

// Xuất file CSV
$filename = 'baocao_thongke_' . date('d-m-Y') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// BOM UTF-8 để Excel hiển thị đúng tiếng Việt
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Tiêu đề báo cáo
fputcsv($output, ['BÁO CÁO THỐNG KÊ SỬ DỤNG PHÒNG HỌP']);
fputcsv($output, ['Ngày xuất: ' . date('d/m/Y H:i')]);
fputcsv($output, []);

// Header bảng
fputcsv($output, [
    'STT',
    'Tên phòng',
    'Địa điểm',
    'Sức chứa',
    'Tổng lượt đặt',
    'Đã duyệt',
    'Chờ duyệt',
    'Từ chối'
]);

// Dữ liệu
$stt = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $stt++,
        $row['tenphong'],
        $row['diadiem'],
        $row['succhua'],
        $row['tong_dat'],
        $row['da_duyet'],
        $row['cho_duyet'],
        $row['tu_choi']
    ]);
}

fclose($output);
mysqli_close($conn);
exit();
?>
