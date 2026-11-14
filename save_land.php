<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

// إرسال استجابة JSON
function sendResponse($status, $data) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// التأكد أن الطلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, [
        'success' => false,
        'message' => 'طريقة الطلب غير مسموحة'
    ]);
}

// التأكد أن المزارع مسجل دخوله
if (!isset($_SESSION['farmer_id'])) {
    sendResponse(401, [
        'success' => false,
        'message' => 'يرجى تسجيل الدخول أولاً'
    ]);
}

// أخذ رقم المزارع من الجلسة وليس من الفورم
$farmerId = (int) $_SESSION['farmer_id'];

// استقبال معلومات الأرض
$landNumber = trim($_POST['landNumber'] ?? '');
$landArea = $_POST['landArea'] ?? '';
$areaUnit = $_POST['areaUnit'] ?? '';
$location = trim($_POST['location'] ?? '');
$soilType = $_POST['soilType'] ?? '';
$currentCrop = $_POST['currentCrop'] ?? '';
$previousCrop = $_POST['previousCrop'] ?? '';

// التحقق من الحقول المطلوبة
if (
    $landNumber === '' ||
    $landArea === '' ||
    $location === '' ||
    $soilType === '' ||
    $currentCrop === '' ||
    $previousCrop === ''
) {
    sendResponse(400, [
        'success' => false,
        'message' => 'يرجى تعبئة جميع معلومات الأرض'
    ]);
}

// التحقق من المساحة
if (!is_numeric($landArea) || (float)$landArea <= 0) {
    sendResponse(400, [
        'success' => false,
        'message' => 'مساحة الأرض غير صحيحة'
    ]);
}

// التحقق من وحدة المساحة
if (!in_array($areaUnit, ['dunum', 'hectare', 'sqm'], true)) {
    sendResponse(400, [
        'success' => false,
        'message' => 'وحدة المساحة غير صحيحة'
    ]);
}

// التحقق من نوع التربة
if (!in_array($soilType, [
    'clay', 'sandy', 'silty', 'calcareous', 'other'
], true)) {
    sendResponse(400, [
        'success' => false,
        'message' => 'نوع التربة غير صحيح'
    ]);
}

// التحقق من المحاصيل
$allowedCrops = [
    'wheat', 'barley', 'olives', 'tomatoes', 'none'
];

if (
    !in_array($currentCrop, $allowedCrops, true) ||
    !in_array($previousCrop, $allowedCrops, true)
) {
    sendResponse(400, [
        'success' => false,
        'message' => 'بيانات المحاصيل غير صحيحة'
    ]);
}

try {

    // حفظ الأرض وربطها بالمزارع
    $stmt = $conn->prepare("
        INSERT INTO lands (
            farmer_id,
            land_number,
            land_area,
            area_unit,
            location_text,
            soil_type,
            current_crop,
            previous_crop
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $areaValue = (float)$landArea;

    $stmt->bind_param(
        "isdsssss",
        $farmerId,
        $landNumber,
        $areaValue,
        $areaUnit,
        $location,
        $soilType,
        $currentCrop,
        $previousCrop
    );

    $stmt->execute();

    $landId = $stmt->insert_id;

    $stmt->close();

    sendResponse(201, [
        'success' => true,
        'message' => 'تم حفظ معلومات الأرض بنجاح',
        'land_id' => $landId
    ]);

} catch (Throwable $e) {

    error_log("Save Land Error: " . $e->getMessage());

    sendResponse(500, [
        'success' => false,
        'message' => 'حدث خطأ أثناء حفظ معلومات الأرض'
    ]);
}
?>