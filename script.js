const translations = {
    ar: {
        mainTitle: " بوابة المزارع الذكية",
        welcomeText: "اختر الإجراء للبدء في إدارة مزرعتك بذكاء",
        registerBtn: "إنشاء حساب جديد",
        loginBtn: "تسجيل الدخول",
        regTitle: " إنشاء حساب مزارع",
        logTitle: " تسجيل الدخول",
        usernameLabel: "اسم المستخدم:",
        emailLabel: "البريد الإلكتروني:",
        passwordLabel: "كلمة المرور:",
        confirmPasswordLabel: "تأكيد كلمة المرور:",
        regSubmit: "تسجيل الحساب",
        logSubmit: "دخول للمزرعة",
        toLogin: "لديك حساب بالفعل؟ سجل دخولك هنا",
        toRegister: "ليس لديك حساب؟ أنشئ حساباً جديداً",
        langBtn: "English",
        themeBtn: "  ليل  /   نهار "
    },
    en: {
        mainTitle: " Smart Farmer Portal",
        welcomeText: "Choose an action to start managing your farm smartly",
        registerBtn: "Create New Account",
        loginBtn: "Login",
        regTitle: " Farmer Registration",
        logTitle: " Farmer Login",
        usernameLabel: "Username:",
        emailLabel: "Email Address:",
        passwordLabel: "Password:",
        confirmPasswordLabel: "Confirm Password:",
        regSubmit: "Register Account",
        logSubmit: "Enter Farm",
        toLogin: "Already have an account? Login here",
        toRegister: "Don't have an account? Create one",
        langBtn: "عربي",
        themeBtn: " DARK /  LIGHT"
    }
};

function applyLanguage(lang) {
    document.documentElement.lang = lang;
    document.documentElement.dir = (lang === 'ar') ? 'rtl' : 'ltr';

    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (translations[lang][key]) {
            el.textContent = translations[lang][key];
        }
    });

    localStorage.setItem('farm_lang', lang);
}

function toggleLanguage() {
    const current = localStorage.getItem('farm_lang') || 'ar';
    applyLanguage(current === 'ar' ? 'en' : 'ar');
}

function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('farm_theme', isDark ? 'dark' : 'light');
}

document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('farm_lang') || 'ar';
    applyLanguage(savedLang);

    if (localStorage.getItem('farm_theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
});