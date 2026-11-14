<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة المزارع الذكية</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- أزرار التحكم بالشاشة (اللغة والسطوع/الثيم) تتحكم بكل الفورمات -->
    <div class="top-buttons">
        <button type="button" class="action-btn" onclick="toggleLanguage()" data-i18n="langBtn">English</button>
        <button type="button" class="action-btn" onclick="toggleTheme()" data-i18n="themeBtn">dark /  light</button>
    </div>

    <h2 data-i18n="mainTitle"> بوابة المزارع الذكية</h2>
    <p style="color: #ffffff; margin-bottom: 30px; text-shadow: 0 1px 3px rgba(0,0,0,0.5);" data-i18n="welcomeText">
        اختر الإجراء للبدء في إدارة مزرعتك بذكاء
    </p>

    <div class="btn-group">
        <!-- الزر الأول: ينقلك لفورم إنشاء الحساب -->
        <a href="register.php" class="submit-btn" data-i18n="registerBtn">إنشاء حساب جديد</a>
        
        <!-- الزر الثاني: ينقلك لفورم تسجيل الدخول -->
        <a href="login.php" class="submit-btn" style="background-color: transparent; border: 2px solid var(--primary-color);" data-i18n="loginBtn">تسجيل الدخول</a>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>