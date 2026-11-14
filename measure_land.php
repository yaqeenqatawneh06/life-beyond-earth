
<?php
session_start();
if (!isset($_SESSION['farmer_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قياس مساحة الأرض من الصورة</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .measure-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 25px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            width: 95%;
            max-width: 600px;
            text-align: center;
            color: #fff;
            margin: 20px auto;
        }
        .upload-btn-wrapper {
            margin: 15px 0;
        }
        input[type="file"] {
            background: rgba(0, 0, 0, 0.4);
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            border: 1px dashed #4caf50;
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
        }
        .canvas-wrapper {
            position: relative;
            width: 100%;
            margin-top: 15px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: none; /* يظهر فقط بعد رفع الصورة */
        }
        canvas {
            display: block;
            width: 100%;
            height: auto;
            cursor: crosshair;
            touch-action: none;
        }
        .height-box {
            margin: 12px 0;
            font-size: 14px;
            display: none;
        }
        .height-box input {
            width: 70px;
            padding: 6px;
            border-radius: 8px;
            border: 1px solid #4caf50;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            text-align: center;
            font-weight: bold;
        }
        .result-badge {
            background: rgba(46, 125, 50, 0.85);
            color: #ccff90;
            padding: 12px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            display: none;
        }
        .action-row {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            flex: 1;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="measure-container">
    <h2 style="margin-top: 0;">📐 قياس مساحة الأرض من الصورة</h2>
    <p id="status-text" style="font-size: 15px; color: #e8f5e9;">
        ارفع صورة للأرض ملتقطة من مكان مرتفع، ثم حدد أركانها الأربعة
    </p>

    <!-- زر إرفاق الصورة -->
    <div class="upload-btn-wrapper">
        <input type="file" id="image-upload" accept="image/*">
    </div>

    <!-- إدخال الارتفاع التقريبي لمعايرة القياس -->
    <div class="height-box" id="height-box">
        <label>ارتفاع التصوير التقريبي عن الأرض (بالمتر):</label>
        <input type="number" id="capture-height" value="5" min="1" max="500" onchange="calculateArea()">
    </div>

    <!-- مساحة عرض الصورة والرسم عليها -->
    <div class="canvas-wrapper" id="canvas-wrapper">
        <canvas id="land-canvas"></canvas>
    </div>

    <!-- نتيجة المساحة -->
    <div class="result-badge" id="result-box">المساحة المقدرة: 0 متر مربع</div>

    <!-- أزرار التحكم -->
    <div class="action-row" id="controls-row" style="display: none;">
        <button type="button" class="btn-secondary" onclick="resetPoints()">🔄 إعادة التحديد</button>
        <button type="button" class="submit-btn" id="confirm-btn" style="margin-top: 0; flex: 2; display: none;" onclick="saveAndContinue()">
            ✅ اعتماد المساحة والمتابعة
        </button>
    </div>

    <a href="form3.php" class="switch-link" style="margin-top: 15px;">تخطي والانتقال لمعلومات الأرض ⬅</a>
</div>

<script>
    const uploadInput = document.getElementById('image-upload');
    const canvas = document.getElementById('land-canvas');
    const ctx = canvas.getContext('2d');
    const canvasWrapper = document.getElementById('canvas-wrapper');
    
    let uploadedImg = new Image();
    let points = [];
    let finalArea = 0;

    // 1. عند اختيار صورة من الجهاز
    uploadInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            uploadedImg.onload = function() {
                // ضبط أبعاد الكانفاس لتطابق أبعاد الصورة الأصلية
                canvas.width = uploadedImg.width;
                canvas.height = uploadedImg.height;
                
                canvasWrapper.style.display = 'block';
                document.getElementById('height-box').style.display = 'block';
                document.getElementById('controls-row').style.display = 'flex';
                
                resetPoints();
                document.getElementById('status-text').innerHTML = "👆 انقر أو المس <b>4 نقاط</b> تمثل زوايا الأرض على الصورة";
            };
            uploadedImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });

    // 2. التقاط نقرة الماوس أو لمسة الإصبع بدقة مهما كان حجم الشاشة
    canvas.addEventListener('pointerdown', function(e) {
        if (points.length >= 4) return;

        const rect = canvas.getBoundingClientRect();
        // تحويل إحداثيات اللمس على الشاشة إلى الإحداثيات الحقيقية داخل الصورة
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        const x = (e.clientX - rect.left) * scaleX;
        const y = (e.clientY - rect.top) * scaleY;

        points.push({ x, y });
        redraw();

        if (points.length === 4) {
            document.getElementById('status-text').innerHTML = "✅ تم تحديد حدود الأرض بالكامل!";
            calculateArea();
        } else {
            document.getElementById('status-text').innerHTML = `📍 تم تحديد ${points.length} من 4 زوايا... اختر الزاوية التالية`;
        }
    });

    // 3. إعادة رسم الصورة وعليها النقاط والخطوط الخضراء
    function redraw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(uploadedImg, 0, 0);

        if (points.length === 0) return;

        // حجم الخط والنقطة يتناسب مع دقة الصورة المرفوعة
        const lineThickness = Math.max(4, canvas.width * 0.005);
        const pointRadius = Math.max(8, canvas.width * 0.012);

        ctx.beginPath();
        ctx.moveTo(points[0].x, points[0].y);
        for (let i = 1; i < points.length; i++) {
            ctx.lineTo(points[i].x, points[i].y);
        }
        if (points.length === 4) {
            ctx.closePath();
            ctx.fillStyle = "rgba(76, 175, 80, 0.35)";
            ctx.fill();
        }
        ctx.strokeStyle = "#76ff03";
        ctx.lineWidth = lineThickness;
        ctx.stroke();

        // رسم دوائر الزوايا
        points.forEach(pt => {
            ctx.beginPath();
            ctx.arc(pt.x, pt.y, pointRadius, 0, Math.PI * 2);
            ctx.fillStyle = "#ffffff";
            ctx.fill();
            ctx.strokeStyle = "#2e7d32";
            ctx.lineWidth = lineThickness * 0.7;
            ctx.stroke();
        });
    }

    // 4. حساب المساحة هندسياً
    function calculateArea() {
        if (points.length < 4) return;

        let pixelArea = 0;
        for (let i = 0; i < points.length; i++) {
            let j = (i + 1) % points.length;
            pixelArea += points[i].x * points[j].y;
            pixelArea -= points[j].x * points[i].y;
        }
        pixelArea = Math.abs(pixelArea) / 2;

        // حساب النسبة المئوية لمساحة الأرض من إجمالي مساحة الصورة
        const totalImagePixels = canvas.width * canvas.height;
        const areaRatio = pixelArea / totalImagePixels;

        // تقدير المساحة بالمتر المربع بناءً على ارتفاع التصوير
        const height = parseFloat(document.getElementById('capture-height').value) || 5;
        const estimatedFieldView = Math.pow(height * 1.5, 2); // مساحة الرؤية التقريبية من هذا الارتفاع
        finalArea = Math.round(areaRatio * estimatedFieldView);

        const resultBox = document.getElementById('result-box');
        resultBox.style.display = 'block';
        resultBox.innerHTML = `📐 المساحة المقدرة: ${finalArea} متر مربع`;
        document.getElementById('confirm-btn').style.display = 'block';
    }

    function resetPoints() {
        points = [];
        finalArea = 0;
        if (uploadedImg.src) redraw();
        document.getElementById('result-box').style.display = 'none';
        document.getElementById('confirm-btn').style.display = 'none';
        document.getElementById('status-text').innerHTML = "👆 انقر أو المس <b>4 نقاط</b> تمثل زوايا الأرض على الصورة";
    }

    function saveAndContinue() {

    if (points.length !== 4 || finalArea <= 0) {
        alert("يرجى تحديد زوايا الأرض الأربع أولاً");
        return;
    }

    window.location.href =
        "form3.php?measured_area=" + encodeURIComponent(finalArea);
}
</script>
</body>
</html>