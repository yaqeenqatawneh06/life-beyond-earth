<?php
session_start();
require 'db.php';

$message = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $check = $conn->prepare("SELECT id FROM farmers WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "البريد الإلكتروني مستخدم مسبقاً!";
                $msg_type = "error";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO farmers (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    $message = "تم إنشاء الحساب بنجاح! يمكنك تسجيل الدخول الآن.";
                    $msg_type = "success";
                } else {
                    $message = "حدث خطأ أثناء التسجيل.";
                    $msg_type = "error";
                }
            }
        } else {
            $message = "كلمتا المرور غير متطابقتين!";
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
    <title>إنشاء حساب مزارع</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="top-buttons">
        <button type="button" class="action-btn" onclick="toggleLanguage()" data-i18n="langBtn">English</button>
        <button type="button" class="action-btn" onclick="toggleTheme()" data-i18n="themeBtn"> dark / light </button>
    </div>

    <h2 data-i18n="regTitle"> إنشاء حساب مزارع</h2>

    <?php if (!empty($message)): ?>
        <div class="message-box <?php echo $msg_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label data-i18n="usernameLabel">اسم المستخدم:</label>
            <input type="text" name="username" placeholder="أدخل اسمك" required>
        </div>
        
        <div class="input-group">
            <label data-i18n="emailLabel">البريد الإلكتروني:</label>
            <input type="email" name="email" placeholder="example@farm.com" required>
        </div>

        <div class="input-group">
            <label data-i18n="passwordLabel">كلمة المرور:</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="input-group">
            <label data-i18n="confirmPasswordLabel">تأكيد كلمة المرور:</label>
            <input type="password" name="confirm_password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="submit-btn" data-i18n="regSubmit">تسجيل الحساب</button>
    </form>

    <a href="login.php" class="switch-link" data-i18n="toLogin">لديك حساب بالفعل؟ سجل دخولك هنا</a>
    <a href="index.php" class="switch-link" style="color: #a5d6a7;">العودة للرئيسية</a>
</div>

<script src="script.js"></script>
</body>
</html>