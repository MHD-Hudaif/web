<?php
/**
 * AL MAQRA (المقرأ) - Free Trial Registration API Endpoint
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Support both form-urlencoded POST and JSON body
$input = $_POST;
if (empty($input)) {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}

$fullName = clean($input['firstName'] ?? $input['full_name'] ?? '');
$age = !empty($input['age']) ? (int)$input['age'] : null;
$gender = clean($input['gender'] ?? 'Male');
$courseTitle = clean($input['course_title'] ?? 'Noorani Qaida Course (Beginners & Kids)');
$country = clean($input['country'] ?? 'India');
$dialCode = clean($input['dialCode'] ?? '+91');
$phoneRaw = clean($input['whatsapp'] ?? $input['phone'] ?? '');
$email = clean($input['email'] ?? '');
$preferredTime = clean($input['preferredTime'] ?? 'Flexible / Any Time');
$notes = clean($input['message'] ?? $input['notes'] ?? '');

// Validation
if (empty($fullName) || mb_strlen($fullName) < 2) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please provide the student’s full name.']);
    exit;
}

if (empty($phoneRaw) || strlen(preg_replace('/[^0-9]/', '', $phoneRaw)) < 6) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please provide a valid WhatsApp phone number.']);
    exit;
}

// Assemble clean full phone number with country code
$cleanDial = preg_replace('/[^0-9+]/', '', $dialCode);
if (empty($cleanDial)) {
    $cleanDial = '+91';
}
$cleanPhoneDigits = preg_replace('/[^0-9]/', '', $phoneRaw);
$fullPhoneNumber = $cleanDial . ' ' . $cleanPhoneDigits;

// Find course ID if possible
$courseId = null;
try {
    $stmtC = $pdo->prepare("SELECT id FROM courses WHERE title LIKE ? LIMIT 1");
    $stmtC->execute(['%' . $courseTitle . '%']);
    $c = $stmtC->fetch();
    if ($c) {
        $courseId = $c['id'];
    }
} catch (Exception $e) {
    // Ignore course lookup error
}

try {
    $insert = $pdo->prepare("
        INSERT INTO students (
            full_name, age, gender, email, phone, country,
            course_id, course_title, preferred_time, notes, status, created_at
        ) VALUES (
            :full_name, :age, :gender, :email, :phone, :country,
            :course_id, :course_title, :preferred_time, :notes, 'trial_requested', datetime('now', 'localtime')
        )
    ");

    // Handle MySQL vs SQLite datetime syntax
    if (($GLOBALS['DB_DRIVER'] ?? '') === 'mysql') {
        $insert = $pdo->prepare("
            INSERT INTO students (
                full_name, age, gender, email, phone, country,
                course_id, course_title, preferred_time, notes, status, created_at
            ) VALUES (
                :full_name, :age, :gender, :email, :phone, :country,
                :course_id, :course_title, :preferred_time, :notes, 'trial_requested', NOW()
            )
        ");
    }

    $insert->execute([
        ':full_name' => $fullName,
        ':age' => $age,
        ':gender' => $gender,
        ':email' => $email,
        ':phone' => $fullPhoneNumber,
        ':country' => $country,
        ':course_id' => $courseId,
        ':course_title' => $courseTitle,
        ':preferred_time' => $preferredTime,
        ':notes' => $notes
    ]);

    $studentId = $pdo->lastInsertId();

    // Generate WhatsApp deep-link addressed to AL MAQRA team
    $msg = buildAdmissionWhatsAppMessage([
        'full_name' => $fullName,
        'age' => $age,
        'gender' => $gender,
        'course_title' => $courseTitle,
        'country' => $country,
        'preferred_time' => $preferredTime,
        'notes' => $notes
    ]);

    $waUrl = formatWhatsAppUrl(WHATSAPP_NUMBER, $msg);

    echo json_encode([
        'success' => true,
        'message' => 'Free trial booking recorded successfully! Redirecting to WhatsApp...',
        'student_id' => $studentId,
        'whatsapp_url' => $waUrl
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}
