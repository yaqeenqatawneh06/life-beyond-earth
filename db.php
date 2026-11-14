<?php
$host = "localhost";
$user = "root";      // الاسم الافتراضي في XAMPP
$pass = "";          // كلمة المرور الافتراضية فارغة في XAMPP
$dbname = "farm_db";

$conn = new mysqli($host, $user, $pass, $dbname);

// التأكد من نجاح الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// لدعم اللغة العربية
$conn->set_charset("utf8mb4");
?>