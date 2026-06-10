# =========================================================================
# 1. CHỌN IMAGE NỀN (BASE IMAGE)
# =========================================================================
# Sử dụng phiên bản PHP 8.2 tích hợp sẵn Apache web server (giống XAMPP)
FROM php:8.2-apache

# =========================================================================
# 2. ĐẶT THƯ MỤC LÀM VIỆC (WORKING DIRECTORY)
# =========================================================================
# Đây là nơi chứa code bên trong container (tương đương htdocs của XAMPP)
WORKDIR /var/www/html

# =========================================================================
# 3. CÀI ĐẶT CÁC EXTENSION PHP (QUAN TRỌNG)
# =========================================================================
# Mặc định PHP của Docker rất "sạch", bạn phải tự cài thêm extension để kết nối MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# =========================================================================
# 4. CẤU HÌNH APACHE WEB SERVER
# =========================================================================
# Kích hoạt mod_rewrite của Apache để chạy được các link thân thiện (Clean URL), 
# rất cần cho WordPress, Laravel, CodeIgniter...
RUN a2enmod rewrite

# Thay đổi quyền sở hữu thư mục code cho user 'www-data' (user mặc định của Apache)
# Giúp web của bạn có thể upload file, ghi log mà không bị lỗi "Permission denied"
RUN chown -R www-data:www-data /var/www/html

# =========================================================================
# 5. MỞ CỔNG MẠNG (EXPOSE PORT)
# =========================================================================
# Thông báo cho Docker biết container này sẽ lắng nghe ở cổng 80 (cổng HTTP mặc định)
EXPOSE 80