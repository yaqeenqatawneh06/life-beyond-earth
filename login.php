<?php
session_start();
require 'db.php';

$message = "";
$msg_type = "";
$show_measure_modal = false; // متغير للتحكم بظهور النافذة المنبثقة

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM farmers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['farmer_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // تفعيل ظهور النافذة المنبثقة في منتصف الشاشة بدلاً من التحويل الفوري
                $show_measure_modal = true; 
            } else {
                $message = "كلمة المرور غير صحيحة!";
                $msg_type = "error";
            }
        } else {
            $message = "البريد الإلكتروني غير مسجل!";
            $msg_type = "error";
        }
    } else {
        $message = "يرجى تعبئة جميع الحقول.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المزارع</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* تنسيق النافذة المنبثقة (المسج في نص الشاشة) */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-box {
            background: rgba(30, 45, 30, 0.95);
            border: 2px solid #4caf50;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            color: #fff;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            animation: popIn 0.3s ease;
        }
        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-btns {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            justify-content: center;
        }
        .btn-yes {
            background: #4caf50; color: white; padding: 12px 25px;
            border-radius: 10px; text-decoration: none; font-weight: bold; flex: 1;
            text-align: center;
        }
        .btn-no {
            background: transparent; border: 1px solid #aaa; color: #ddd;
            padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: bold; flex: 1;
            text-align: center;
        }
        .btn-yes:hover { background: #388e3c; }
        .btn-no:hover { background: rgba(255,255,255,0.1); color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <div class="top-buttons">
        <button type="button" class="action-btn" onclick="toggleLanguage()" data-i18n="langBtn">English</button>
        <button type="button" class="action-btn" onclick="toggleTheme()" data-i18n="themeBtn">🌙 / ☀️</button>
    </div>

    <h2 data-i18n="logTitle">🌱 تسجيل الدخول</h2>

    <?php if (!empty($message)): ?>
        <div class="message-box <?php echo $msg_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label data-i18n="emailLabel">البريد الإلكتروني:</label>
            <input type="email" name="email" placeholder="example@farm.com" required>
        </div>

        <div class="input-group">
            <label data-i18n="passwordLabel">كلمة المرور:</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="submit-btn" data-i18n="logSubmit">دخول للمزرعة</button>
    </form>

    <a href="register.php" class="switch-link" data-i18n="toRegister">ليس لديك حساب؟ أنشئ حساباً جديداً</a>
    <a href="index.php" class="switch-link" style="color: #a5d6a7;">العودة للرئيسية</a>
</div>

<!-- النافذة المنبثقة (المسج) في منتصف الشاشة بعد نجاح تسجيل الدخول -->
<?php if ($show_measure_modal): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <div style="font-size: 40px; margin-bottom: 10px;">📐🌱</div>
        <h3 style="margin: 0 0 10px 0; color: #a5d6a7;">أهلاً بك يا <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
        <p style="font-size: 16px; line-height: 1.5;">هل تود قياس مساحة أرضك الآن من خلال إرفاق صورة؟</p>
        <div class="modal-btns">
            <!-- زر نعم: ينقله لصفحة قياس المساحة عبر الصورة -->
            <a href="measure_land.php" class="btn-yes">نعم</a>
            <!-- زر لا: ينقله مباشرة إلى لوحة التحكم (معلومات الأرض) -->
            <a href="form3.php" class="btn-no">لا</a>

        </div>
    </div>
</div>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>