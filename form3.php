<?php
session_start();
require 'db.php';

if (!isset($_SESSION['farmer_id'])) {
    header("Location: login.php");
    exit();
}

// استقبال المساحة القادمة من فورم القياس
if (isset($_GET['measured_area']) && is_numeric($_GET['measured_area'])) {

    $area = (float) $_GET['measured_area'];

    if ($area > 0) {
        $_SESSION['measured_area'] = $area;
    }
}

// قراءة المساحة المحفوظة
$measuredArea = $_SESSION['measured_area'] ?? '';
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">TerraVision - نظام تحليل وإدارة الأراضي الزراعية</title>
    <!-- مكتبة خرائط Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- شريط الأزرار العلوي -->
<div class="top-controls">
    <button id="langToggleBtn" class="control-btn lang-btn" onclick="toggleLanguage()">English</button>
</div>

<!-- الحاوية الرئيسية المقسومة لليمين واليسار -->
<div class="main-layout">

    <!-- القسم الأيمن: الفورم -->
    <div class="form-panel">
        <div class="header">
            <h1 id="txtBrand">TerraVision</h1>
            <div class="subtitle">NASA SPACE APPS CHALLENGE</div>
            <h2 id="txtMainHeading">معلومات الأرض الزراعية</h2>
            <p id="txtSubHeading">أدخل بيانات أرضك لتحليل بيئتها عبر أقمار ناسا الصناعية</p>
        </div>

        <form id="landForm" onsubmit="handleFormSubmit(event); return false;">
            <div class="section-title" id="secTitle1">أولاً: معلومات الأرض</div>
            
            <div class="form-group">
               <input
    type="email"
    id="ownerEmail"
    name="ownerEmail"
    value="<?php echo htmlspecialchars($_SESSION['farmer_email'] ?? ''); ?>"
    required
></div>

            <div class="form-group">
                <label for="landNumber" id="lblLandNumber">رقم الأرض</label>
                <input type="number" id="landNumber" placeholder="أدخل رقم الأرض (مثلاً: 105)" min="1" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="landArea" id="lblLandArea">مساحة الأرض</label>
                    <input
    type="number"
    id="landArea"
    name="landArea"
    value="<?php echo htmlspecialchars((string)$measuredArea); ?>"
    placeholder="مثلاً 10"
    min="0.1"
    step="any"
    required
>
                    </div>
                <div class="form-group">
                    <label for="areaUnit" id="lblAreaUnit">وحدة المساحة</label>
                    <select id="areaUnit">
                        <option value="dunum" id="optDunum">دونم</option>
                        <option value="hectare" id="optHectare">هكتار</option>
                        <option value="sqm" id="optSqm">متر مربع</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="location" id="lblLocation">موقع الأرض / الإحداثيات (GPS)</label>
                <input type="text" id="location" placeholder="انقر على الخريطة أو استخدم زر GPS" required>
            </div>

            <div class="form-group">
                <label for="soilType" id="lblSoilType">نوع التربة</label>
                <select id="soilType" required>
                    <option value="" disabled selected id="optSoilDefault">اختر نوع التربة</option>
                    <option value="clay" id="optSoilClay">طينية</option>
                    <option value="sandy" id="optSoilSandy">رملية</option>
                    <option value="silty" id="optSoilSilty">طميية</option>
                    <option value="calcareous" id="optSoilCalc">كلسية</option>
                    <option value="other" id="optSoilOther">أخرى</option>
                </select>
            </div>

            <div class="section-title" id="secTitle2">ثانياً: معلومات المحاصيل</div>

            <div class="form-group">
                <label for="currentCrop" id="lblCurrentCrop">المحصول المزروع حالياً</label>
                <select id="currentCrop" required>
                    <option value="" disabled selected id="optCurrDefault">اختر المحصول الحالي</option>
                    <option value="wheat">قمح / Wheat</option>
                    <option value="barley">شعير / Barley</option>
                    <option value="olives">زيتون / Olives</option>
                    <option value="tomatoes">بندورة / Tomatoes</option>
                    <option value="none">الأرض غير مزروعة / Bare Soil</option>
                </select>
            </div>

            <div class="form-group">
                <label for="previousCrop" id="lblPrevCrop">المحصول المزروع في السنة الماضية</label>
                <select id="previousCrop" required>
                    <option value="" disabled selected id="optPrevDefault">اختر المحصول السابق</option>
                    <option value="wheat">قمح / Wheat</option>
                    <option value="barley">شعير / Barley</option>
                    <option value="olives">زيتون / Olives</option>
                    <option value="none">لم تكن مزروعة / Bare Soil</option>
                </select>
            </div>

            <!-- خيار عرض الأرض للبيع (اختياري) -->
            <div class="section-title">رابعاً: سوق الاستثمار العقاري الزراعي (اختياري)</div>
            <div class="form-group">
                <label>هل ترغب في عرض هذه الأرض للبيع في سوق TerraVision والمنصات العالمية؟</label>
                <div style="display: flex; gap: 20px; margin-top: 8px;">
                    <label style="cursor: pointer;"><input type="radio" name="wantToSell" value="yes" onclick="toggleSellOptions(true)"> نعم، أرغب في بيعها</label>
                    <label style="cursor: pointer;"><input type="radio" name="wantToSell" value="no" checked onclick="toggleSellOptions(false)"> لا، غير متاح للبيع</label>
                </div>
            </div>

            <!-- إعدادات البيع والروابط العالمية (تظهر فقط إذا اخترت نعم) -->
            <div id="sellOptionsContainer" style="display: none; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px dashed #81c784;">
                <div class="form-group">
                    <label for="askingPrice">السعر المطلوب (بالدينار الأردني / USD):</label>
                    <input type="number" id="askingPrice" placeholder="أدخل السعر الذي تطلبه...">
                </div>
                <div style="background: rgba(46, 125, 50, 0.3); padding: 10px; border-radius: 8px; font-size: 13px; color: #a5d6a7; margin-bottom: 12px;">
                    💡 <strong>السعر التقديري المقترح من الموقع:</strong> <span id="estimatedPriceCalc">ادخل المساحة أولاً...</span>
                </div>
                <div class="form-group">
                    <label for="globalPlatform">اختر المنصة العالمية للنشر:</label>
                    <select id="globalPlatform">
                        <option value="https://agroscout.com">🛰️ AgroScout - سوق الأراضي والمحاصيل المدعوم بالأقمار الصناعية (agroscout.com)</option>
                        <option value="https://www.landwatch.com">🌍 LandWatch Global - منصة عقارات الأراضي (landwatch.com)</option>
                        <option value="https://eos.com/landviewer/">🔭 EOS LandViewer - خرائط وتحليل الأقمار الصناعية (eos.com)</option>
                    </select>
                </div>
            </div>

            <!-- حاوية الأزرار -->
            <div class="buttons-row">
                <button type="submit" class="btn-submit" id="btnSubmit">حفظ معلومات الأرض والتحليل</button>
                <button type="button" class="btn-add-land" id="btnAddLand" onclick="resetForm()">➕ أرض جديدة</button>
            </div>
        </form>
    </div>

    <!-- القسم الأيسر: خريطة GPS -->
    <div class="map-panel">
        <div class="map-header">
            <h3 id="mapTitle">معاينة الأرض عبر الأقمار الصناعية (GPS)</h3>
            <button type="button" class="btn-gps" id="btnGetLocation" onclick="locateUser()">📍 تحديد موقعي الحالي</button>
        </div>
        <p class="map-hint" id="mapHint">انقر على أي مكان في الخريطة لتحديد موقع الأرض وإحداثياتها</p>
        <div id="satelliteMap"></div>
        <div class="coords-box" id="coordsDisplay">GPS: 31.9539, 35.9106</div>
    </div>

</div>

<!-- قسم تقرير ناسا وسوق البيع (يظهر تلقائياً بعد الحفظ) -->
<div id="nasaReportSection" style="max-width: 1400px; margin: 30px auto; display: none;">
    <div class="form-panel" style="width: 100%; border-top: 6px solid #ff9800;">
        <div class="header">
            <h1 style="color: #ffb74d;">TerraVision - Analytics & Global Marketplace</h1>
            <h2>تقرير تحليل الأقمار الصناعية وعرض الاستثمار العقاري</h2>
        </div>

        <div class="section-title">أولاً: ملخص بيانات الأرض المسجلة</div>
        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1; background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; text-align: center;">
                <span style="color: #a5d6a7; display: block; font-size: 13px;">رقم الأرض</span>
                <strong id="repLandNum" style="color: #fff; font-size: 18px;">-</strong>
            </div>
            <div style="flex: 1; background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; text-align: center;">
                <span style="color: #a5d6a7; display: block; font-size: 13px;">المساحة</span>
                <strong id="repArea" style="color: #fff; font-size: 18px;">-</strong>
            </div>
            <div style="flex: 1; background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; text-align: center;">
                <span style="color: #a5d6a7; display: block; font-size: 13px;">نوع التربة</span>
                <strong id="repSoil" style="color: #fff; font-size: 18px;">-</strong>
            </div>
        </div>

        <!-- قسم عرض البيع والنشر المباشر للموقع المختار -->
        <div id="marketSection" style="display: none; margin-bottom: 20px;">
            <div class="section-title" style="color: #ffb74d; border-bottom-color: #ffb74d;">🛒 سوق الاستثمار والربط مع المنصة العالمية</div>
            <div style="background: rgba(255, 152, 0, 0.2); border: 1.5px solid #ffb74d; border-radius: 12px; padding: 20px;">
                <p style="color: #fff; margin: 0 0 10px 0;">السعر المطلوب: <strong id="repPrice" style="color: #81c784;">-</strong></p>
                <p style="color: #fff; margin: 0 0 15px 0;">رابط الموقع العالمي المختار للنشر: <a href="#" id="platformDirectLink" target="_blank" style="color: #81c784; text-decoration: underline;">-</a></p>
                
                <!-- كبسة النشر الفعلي والتوجه للموقع -->
                <button type="button" onclick="publishToGlobalMarket()" style="background: #1565c0; color: #fff; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; margin-bottom: 15px;">🚀 نشر وتوجيه إلى الموقع العالمي</button>
                
                <div style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; margin-top: 15px;">
                    <span style="color: #ffb74d; display: block; font-size: 12px; font-weight: bold;">محاكاة طلب شراء من مستثمر:</span>
                    <input type="email" id="buyerEmailInput" placeholder="أدخل بريد المشتري المهتم..." style="margin-top: 5px; margin-bottom: 5px; padding: 8px; width: 100%;">
                    <button type="button" onclick="simulateBuyRequest()" style="background: #2e7d32; color: #fff; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold;">إرسال عرض شراء (تجريبي)</button>
                    <p id="buyMsg" style="color: #81c784; font-size: 13px; margin: 5px 0 0 0;"></p>
                </div>
            </div>
        </div>

        <div class="section-title">ثانياً: مؤشرات الرصد الفضائي (NASA Datasets)</div>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
                <strong style="color: #81c784;">🛰️ رطوبة التربة (SMAP):</strong>
                <p id="repSmap" style="color: #fff; font-size: 13px; margin: 5px 0 0 0;">جاري تحليل السطح...</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
                <strong style="color: #81c784;">🌧️ الهطول المطري (GPM IMERG):</strong>
                <p id="repGpm" style="color: #fff; font-size: 13px; margin: 5px 0 0 0;">تقييم معدلات الأمطار...</p>
            </div>
        </div>

        <div class="section-title">ثالثاً: توصية الدورة الزراعية الذكية</div>
        <div style="background-color: rgba(46, 125, 50, 0.3); border: 1.5px solid #81c784; border-radius: 12px; padding: 20px;">
            <p id="repRecommendation" style="color: #ffffff; font-size: 14px; line-height: 1.6; margin: 0;">جاري المطابقة...</p>
        </div>
    </div>
</div>

<!-- تذييل حقوق ناسا -->
<footer class="data-disclaimer" style="text-align: center; color: #ccc; font-size: 12px; margin: 30px 0;">
    مصادر البيانات العلمية: NASA Earth Observations & ESA Copernicus Sentinel-2
</footer>

<!-- السكربتات -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="lang.js"></script>
<script src="script.js"></script>
<script>
    function toggleSellOptions(show) {
        const container = document.getElementById('sellOptionsContainer');
        container.style.display = show ? 'block' : 'none';
    }

    // حساب سعر تقديري بناء على المساحة
    document.getElementById('landArea').addEventListener('input', function() {
        let area = parseFloat(this.value) || 0;
        let unit = document.getElementById('areaUnit').value;
        let factor = unit === 'hectare' ? 10 : (unit === 'sqm' ? 0.001 : 1);
        let estimated = Math.round(area * factor * 3500);
        document.getElementById('estimatedPriceCalc').innerText = estimated > 0 ? `${estimated} دينار أردني تقريباً` : 'ادخل المساحة أولاً...';
    });

    let savedOwnerEmail = "";
    let savedLandNum = "";
    let selectedPlatformUrl = "";

    function handleFormSubmit(event) {
        event.preventDefault();

        savedOwnerEmail = document.getElementById('ownerEmail').value;
        savedLandNum = document.getElementById('landNumber').value;
        const landArea = document.getElementById('landArea').value;
        const areaUnit = document.getElementById('areaUnit').value;
        const soilType = document.getElementById('soilType').value;
        const wantToSell = document.querySelector('input[name="wantToSell"]:checked').value;
        const askingPrice = document.getElementById('askingPrice').value || 'غير محدد';
        
        const platformSelect = document.getElementById('globalPlatform');
        selectedPlatformUrl = platformSelect.value;
        const platformName = platformSelect.options[platformSelect.selectedIndex].text;

        document.getElementById('repLandNum').innerText = savedLandNum;
        document.getElementById('repArea').innerText = `${landArea} ${areaUnit}`;
        document.getElementById('repSoil').innerText = soilType;

        const marketSection = document.getElementById('marketSection');
        if (wantToSell === 'yes') {
            marketSection.style.display = 'block';
            document.getElementById('repPrice').innerText = `${askingPrice} دينار / USD`;
            
            let linkEl = document.getElementById('platformDirectLink');
            linkEl.href = selectedPlatformUrl;
            linkEl.innerText = `${platformName} (${selectedPlatformUrl})`;
        } else {
            marketSection.style.display = 'none';
        }

        document.getElementById('repSmap').innerText = `قراءة رطوبة السطح (SMAP) تسجل كفاءة تخزين مناسبة للاستثمار الزراعي.`;
        document.getElementById('repGpm').innerText = `تحليل الهطول المطري عبر GPM IMERG يظهر استقراراً موسمياً داعماً لقيمة الأرض.`;
        document.getElementById('repRecommendation').innerText = "بناءً على مؤشرات رطوبة التربة ومعدلات الهطول، يوصي نموذج ناسا باعتماد دورة زراعية ترفع من القيمة الإنتاجية والتسويقية للأرض.";

        const reportSection = document.getElementById('nasaReportSection');
        reportSection.style.display = 'block';
        reportSection.scrollIntoView({ behavior: 'smooth' });
    }

    function publishToGlobalMarket() {
        alert('🚀 تم نشر الإعلان بنجاح، جاري تحويلك الآن إلى الموقع العالمي المختار...');
        window.open(selectedPlatformUrl, '_blank');
    }

    function simulateBuyRequest() {
        let buyer = document.getElementById('buyerEmailInput').value;
        if (!buyer) {
            alert('الرجاء إدخال البريد الإلكتروني للمشتري المهتم أولاً');
            return;
        }
        document.getElementById('buyMsg').innerText = `✅ تم إرسال رسالة فورية إلى إيميل المالك (${savedOwnerEmail}) تفيد بوجود مهتم بالشراء (${buyer}) للأرض رقم ${savedLandNum}!`;
    }

    function resetForm() {
        document.getElementById('landForm').reset();
        document.getElementById('nasaReportSection').style.display = 'none';
        document.getElementById('sellOptionsContainer').style.display = 'none';
    }
</script>

</body>
</html>