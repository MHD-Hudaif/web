<?php
require_once __DIR__ . '/../includes/db.php';

$count = (int)$pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
if ($count === 0) {
    $stmt = $pdo->prepare("
        INSERT INTO classes (title, course_id, teacher_id, schedule_days, time_slot, meeting_link, max_students, status)
        VALUES (:title, :course_id, :teacher_id, :schedule_days, :time_slot, :meeting_link, :max_students, :status)
    ");
    $stmt->execute([
        ':title' => 'Evening Noorani Qaida 1-on-1 (UAE Slot)',
        ':course_id' => 1,
        ':teacher_id' => 1,
        ':schedule_days' => 'Mon, Wed, Fri',
        ':time_slot' => '07:30 PM - 08:15 PM GST',
        ':meeting_link' => 'https://meet.google.com/maq-ra-qur',
        ':max_students' => 1,
        ':status' => 'active'
    ]);
    $classId = $pdo->lastInsertId();

    // Check if student 1 exists to link
    $studentExists = $pdo->query("SELECT id FROM students LIMIT 1")->fetchColumn();
    if ($studentExists) {
        $pdo->prepare("INSERT INTO class_students (class_id, student_id, status) VALUES (?, ?, 'active')")
            ->execute([$classId, $studentExists]);
    }

    echo "Sample class seeded: ID {$classId}\n";
} else {
    echo "Classes already exist.\n";
}
