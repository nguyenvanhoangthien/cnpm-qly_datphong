<?php
$conn = mysqli_connect("db", "myuser", "mypassword", "qly_dat_phong");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>