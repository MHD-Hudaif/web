<?php
/**
 * AL MAQRA (المقرأ) - Create / Edit Class Form
 */

require_once __DIR__ . '/includes/header.php';

$classId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$class = null;
$isEdit = false;

if ($classId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$classId]);
    $class = $stmt->fetch();
    if ($class) {
        $isEdit = true;
    }
}

// Fetch all courses and teachers for dropdowns
$courses = getActiveCourses($pdo);
$teachers = $pdo->query("SELECT * FROM teachers WHERE status = 'active' ORDER BY full_name ASC")->fetchAll();

// Fetch all enrolled student IDs for this class
$enrolledStudentIds = [];
if ($isEdit) {
    $stmtE = $pdo->prepare("SELECT student_id FROM class_students WHERE class_id = ?");
    $stmtE->execute([$classId]);
    $enrolledStudentIds = $stmtE->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch all students available for enrollment
$allStudents = $pdo->query("SELECT id, full_name, phone, country, course_title, status FROM students ORDER BY id DESC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean($_POST['title'] ?? '');
    $courseId = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;
    $teacherId = !empty($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : null;
    $scheduleDays = clean($_POST['schedule_days'] ?? 'Mon, Wed, Fri');
    $timeSlot = clean($_POST['time_slot'] ?? '');
    $meetingLink = clean($_POST['meeting_link'] ?? '');
    $maxStudents = (int)($_POST['max_students'] ?? 1);
    $status = clean($_POST['status'] ?? 'active');
    $selectedStudents = $_POST['students'] ?? [];

    if (empty($title)) {
        $error = 'Class title is required.';
    } elseif (empty($timeSlot)) {
        $error = 'Time slot is required.';
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("
                UPDATE classes SET
                    title = :title,
                    course_id = :course_id,
                    teacher_id = :teacher_id,
                    schedule_days = :schedule_days,
                    time_slot = :time_slot,
                    meeting_link = :meeting_link,
                    max_students = :max_students,
                    status = :status
                WHERE id = :id
            ");
            $stmt->execute([
                ':title' => $title,
                ':course_id' => $courseId,
                ':teacher_id' => $teacherId,
                ':schedule_days' => $scheduleDays,
                ':time_slot' => $timeSlot,
                ':meeting_link' => $meetingLink,
                ':max_students' => $maxStudents,
                ':status' => $status,
                ':id' => $classId
            ]);
            $currentClassId = $classId;
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO classes (
                    title, course_id, teacher_id, schedule_days,
                    time_slot, meeting_link, max_students, status
                ) VALUES (
                    :title, :course_id, :teacher_id, :schedule_days,
                    :time_slot, :meeting_link, :max_students, :status
                )
            ");
            $stmt->execute([
                ':title' => $title,
                ':course_id' => $courseId,
                ':teacher_id' => $teacherId,
                ':schedule_days' => $scheduleDays,
                ':time_slot' => $timeSlot,
                ':meeting_link' => $meetingLink,
                ':max_students' => $maxStudents,
                ':status' => $status
            ]);
            $currentClassId = $pdo->lastInsertId();
        }

        // Sync enrolled students in class_students
        $delStmt = $pdo->prepare("DELETE FROM class_students WHERE class_id = ?");
        $delStmt->execute([$currentClassId]);

        if (!empty($selectedStudents) && is_array($selectedStudents)) {
            $insStudent = $pdo->prepare("INSERT INTO class_students (class_id, student_id, status) VALUES (?, ?, 'active')");
            foreach ($selectedStudents as $stdId) {
                $insStudent->execute([$currentClassId, (int)$stdId]);
            }
        }

        setFlash('success', "Class '" . e($title) . "' saved successfully.");
        header('Location: ' . baseUrl('/admin/classes.php'));
        exit;
    }
}
?>

<div class="max-w-4xl mx-auto">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">
        <?= $isEdit ? 'Edit Class &amp; Batch' : 'Schedule New Class' ?>
      </h1>
      <p class="text-xs sm:text-sm text-gray-500 mt-1">
        Configure class schedule, assign Ustadh, and enroll students.
      </p>
    </div>
    <a href="<?= baseUrl('/admin/classes.php') ?>" class="text-xs font-bold text-gray-600 hover:text-[#02110c]">
      ← Back to Classes
    </a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
      ⚠️ <?= e($error) ?>
    </div>
  <?php endif; ?>

  <div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm p-6 sm:p-8">
    <form method="POST" action="" class="space-y-6">
      
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
          Class Title *
        </label>
        <input
          type="text"
          name="title"
          required
          value="<?= e($class['title'] ?? '') ?>"
          placeholder="e.g. 1-on-1 Noorani Qaida (Evening Slot) - Ustadh Bilal"
          class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Course Program
          </label>
          <select
            name="course_id"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          >
            <option value="">-- Select Course --</option>
            <?php foreach ($courses as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($class['course_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>>
                <?= e($c['title']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Assigned Teacher / Ustadh
          </label>
          <select
            name="teacher_id"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          >
            <option value="">-- Select Teacher --</option>
            <?php foreach ($teachers as $t): ?>
              <option value="<?= $t['id'] ?>" <?= ($class['teacher_id'] ?? 0) == $t['id'] ? 'selected' : '' ?>>
                <?= e($t['full_name']) ?> (<?= e($t['gender']) ?> - <?= e($t['specialization']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Schedule Days
          </label>
          <input
            type="text"
            name="schedule_days"
            value="<?= e($class['schedule_days'] ?? 'Mon, Wed, Fri') ?>"
            placeholder="e.g. Mon, Wed, Fri or Daily"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Time Slot *
          </label>
          <input
            type="text"
            name="time_slot"
            required
            value="<?= e($class['time_slot'] ?? '07:30 PM - 08:15 PM GST') ?>"
            placeholder="e.g. 07:30 PM - 08:15 PM"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Max Students (Capacity)
          </label>
          <input
            type="number"
            name="max_students"
            min="1"
            max="50"
            value="<?= e($class['max_students'] ?? 1) ?>"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
          <span class="text-[10px] text-gray-400 mt-1 block">Set to 1 for 1-on-1 private class.</span>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Classroom Meeting Link (Zoom / Meet / WhatsApp)
          </label>
          <input
            type="text"
            name="meeting_link"
            value="<?= e($class['meeting_link'] ?? '') ?>"
            placeholder="https://zoom.us/j/... or Google Meet"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Status
          </label>
          <select
            name="status"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none font-bold"
          >
            <option value="active" <?= ($class['status'] ?? '') === 'active' ? 'selected' : '' ?>>● Active</option>
            <option value="upcoming" <?= ($class['status'] ?? '') === 'upcoming' ? 'selected' : '' ?>>○ Upcoming</option>
            <option value="completed" <?= ($class['status'] ?? '') === 'completed' ? 'selected' : '' ?>>✓ Completed</option>
          </select>
        </div>
      </div>

      <!-- Student Enrollment Picker -->
      <div class="pt-4 border-t border-slate-100">
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
          Enroll Students into this Class
        </label>
        <p class="text-xs text-gray-500 mb-3">
          Check the students who should be enrolled in this class session.
        </p>

        <?php if (empty($allStudents)): ?>
          <p class="text-xs text-gray-400 italic">No registered students found to enroll.</p>
        <?php else: ?>
          <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-2xl p-3 divide-y divide-gray-100 bg-slate-50/50">
            <?php foreach ($allStudents as $std): ?>
              <?php $isEnrolled = in_array($std['id'], $enrolledStudentIds); ?>
              <label class="flex items-center gap-3 py-2.5 px-2 hover:bg-white rounded-xl cursor-pointer transition-colors">
                <input
                  type="checkbox"
                  name="students[]"
                  value="<?= $std['id'] ?>"
                  class="h-4 w-4 text-[#1a422f] rounded border-gray-300 focus:ring-[#1a422f]"
                  <?= $isEnrolled ? 'checked' : '' ?>
                />
                <div class="flex-1 text-xs">
                  <div class="font-bold text-[#02110c] flex items-center gap-2">
                    <span><?= e($std['full_name']) ?></span>
                    <span class="text-[10px] text-gray-500 font-normal">🌍 <?= e($std['country']) ?></span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 font-semibold"><?= e($std['status']) ?></span>
                  </div>
                  <div class="text-[11px] text-gray-500">
                    Phone: <?= e($std['phone']) ?> • Course: <?= e($std['course_title'] ?? 'N/A') ?>
                  </div>
                </div>
              </label>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <a
          href="<?= baseUrl('/admin/classes.php') ?>"
          class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-slate-50 transition-colors"
        >
          Cancel
        </a>
        <button
          type="submit"
          class="px-6 py-2.5 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-md transition-all"
        >
          <?= $isEdit ? 'Save Changes' : 'Schedule Class' ?>
        </button>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
