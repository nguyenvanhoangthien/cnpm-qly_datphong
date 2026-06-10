-- Tạo database
CREATE DATABASE IF NOT EXISTS qly_dat_phong CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qly_dat_phong;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    fullname VARCHAR(100),
    role ENUM('admin', 'teacher', 'student') DEFAULT 'teacher',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME NULL
);

-- Bảng phonghop
CREATE TABLE IF NOT EXISTS phonghop (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenphong VARCHAR(100) NOT NULL,
    diadiem VARCHAR(200),
    succhua INT DEFAULT 0,
    mota TEXT
);

-- Bảng dangky
CREATE TABLE IF NOT EXISTS dangky (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_phong INT NOT NULL,
    user_id INT NOT NULL,
    ngaydat DATE NOT NULL,
    ngaysudung DATE NOT NULL,
    giobatdau TIME NOT NULL,
    gioketthuc TIME NOT NULL,
    muctieu TEXT,
    trangthai ENUM('Chờ duyệt', 'Đã duyệt', 'Từ chối') DEFAULT 'Chờ duyệt',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_phong) REFERENCES phonghop(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tài khoản admin mặc định (password: admin123)
INSERT INTO users (username, password, email, fullname, role) VALUES
('admin', MD5('admin123'), 'admin@tvu.edu.vn', 'Quản trị viên', 'admin');
INSERT INTO users (username, password, email, fullname, role) VALUES
('hoangthien', MD5('18052005'), 'hoangthien@tvu.edu.vn', 'Giảng Viên', 'teacher');

-- Dữ liệu phòng mẫu
INSERT INTO phonghop (tenphong, diadiem, succhua, mota) VALUES
('Phòng họp A', 'Tầng 1 - Nhà A', 20, 'Phòng họp đa năng'),
('Phòng Lab 1', 'Tầng 2 - Nhà B', 40, 'Phòng thực hành máy tính'),
('Phòng hội thảo', 'Tầng 3 - Nhà C', 100, 'Phòng hội thảo lớn');
