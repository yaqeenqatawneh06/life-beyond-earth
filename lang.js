const translations = {
    ar: {
        dir: "rtl",
        langBtn: "English",
        pageTitle: "TerraVision - تسجيل معلومات الأرض",
        txtMainHeading: "معلومات الأرض الزراعية",
        txtSubHeading: "أدخل بيانات أرضك لنساعدك في إدارتها بشكل أفضل",
        secTitle1: "أولاً: معلومات الأرض",
        lblLandNumber: "رقم الأرض",
        lblLandArea: "مساحة الأرض",
        lblAreaUnit: "وحدة المساحة",
        optDunum: "دونم",
        optHectare: "هكتار",
        optSqm: "متر مربع",
        lblLocation: "موقع الأرض / الإحداثيات (GPS)",
        lblSoilType: "نوع التربة",
        optSoilDefault: "اختر نوع التربة",
        optSoilClay: "طينية",
        optSoilSandy: "رملية",
        optSoilSilty: "طميية",
        optSoilCalc: "كلسية",
        optSoilOther: "أخرى",
        secTitle2: "ثانياً: معلومات المحاصيل",
        lblCurrentCrop: "المحصول المزروع حالياً",
        optCurrDefault: "اختر المحصول الحالي",
        optCurrCustom: "أخرى (اكتب اسم المحصول)",
        lblPrevCrop: "المحصول المزروع في السنة الماضية",
        optPrevDefault: "اختر المحصول السابق",
        optPrevCustom: "أخرى (اكتب اسم المحصول)",
        lblFutureCrop: "المحصول المخطط لزراعته مستقبلاً",
        optFutDefault: "اختر المحصول المستقبلي",
        optFutCustom: "أخرى (اكتب اسم المحصول)",
        secTitle3: "ثالثاً: الهدف من الزراعة",
        lblGoal1Title: "هدف مادي (تجاري)",
        lblGoal1Desc: "زراعة المحاصيل بهدف البيع وتحقيق الأرباح.",
        lblGoal2Title: "الاكتفاء الذاتي",
        lblGoal2Desc: "زراعة المحاصيل لتلبية احتياجات الأسرة.",
        btnSubmit: "حفظ معلومات الأرض",
        btnAddLand: "➕ إضافة أرض",
        mapTitle: "معاينة الأرض عبر الأقمار الصناعية (GPS)",
        btnGetLocation: "📍 تحديد موقعي الحالي",
        mapHint: "انقر على أي مكان في الخريطة لتحديد موقع الأرض وإحداثياتها"
    },
    en: {
        dir: "ltr",
        langBtn: "عربي",
        pageTitle: "TerraVision - Farm Registration",
        txtMainHeading: "Agricultural Land Information",
        txtSubHeading: "Enter your land data to help us manage it better",
        secTitle1: "First: Land Information",
        lblLandNumber: "Land Number",
        lblLandArea: "Land Area",
        lblAreaUnit: "Area Unit",
        optDunum: "Dunum",
        optHectare: "Hectare",
        optSqm: "Square Meter",
        lblLocation: "Land Location / Coordinates (GPS)",
        lblSoilType: "Soil Type",
        optSoilDefault: "Select soil type",
        optSoilClay: "Clay",
        optSoilSandy: "Sandy",
        optSoilSilty: "Silty",
        optSoilCalc: "Calcareous",
        optSoilOther: "Other",
        secTitle2: "Second: Crop Information",
        lblCurrentCrop: "Currently Grown Crop",
        optCurrDefault: "Select current crop",
        optCurrCustom: "Other (Type crop name)",
        lblPrevCrop: "Previous Year Crop",
        optPrevDefault: "Select previous crop",
        optPrevCustom: "Other (Type crop name)",
        lblFutureCrop: "Planned Future Crop",
        optFutDefault: "Select future crop",
        optFutCustom: "Other (Type crop name)",
        secTitle3: "Third: Farming Goal",
        lblGoal1Title: "Commercial Goal",
        lblGoal1Desc: "Growing crops for sale and profit.",
        lblGoal2Title: "Self-Sufficiency",
        lblGoal2Desc: "Growing crops to meet family needs.",
        btnSubmit: "Save Land Information",
        btnAddLand: "➕ Add Land",
        mapTitle: "Satellite Land Preview (GPS)",
        btnGetLocation: "📍 Locate My Position",
        mapHint: "Click anywhere on the map to pinpoint your land coordinates"
    }
};

let currentLang = 'ar';

function toggleLanguage() {
    currentLang = currentLang === 'ar' ? 'en' : 'ar';
    document.getElementById('htmlRoot').setAttribute('dir', translations[currentLang].dir);
    document.getElementById('htmlRoot').setAttribute('lang', currentLang);

    for (let key in translations[currentLang]) {
        let el = document.getElementById(key);
        if (el) {
            if (key === 'pageTitle') {
                document.title = translations[currentLang][key];
            } else {
                el.innerText = translations[currentLang][key];
            }
        }
    }
    document.getElementById('langToggleBtn').innerText = translations[currentLang].langBtn;
}