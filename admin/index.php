<?php
/**
 * AL MAQRA (المَقرَأ) - Admin Dashboard
 * Brand Theme: Dark Emerald (#02110c), Olive Green (#1a422f), Cream Beige (#f3ebd6)
 */

$adminPageTitle = "Dashboard Overview";
require_once __DIR__ . '/includes/header.php';

// Quick status updater handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $studentId = (int)($_POST['student_id'] ?? 0);
    $newStatus = clean($_POST['status'] ?? '');
    $allowed = ['trial_requested', 'trial_scheduled', 'trial_completed', 'enrolled', 'inactive'];
    if ($studentId > 0 && in_array($newStatus, $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE students SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $studentId]);
        setFlash('success', "Student #{$studentId} status updated to " . ucfirst(str_replace('_', ' ', $newStatus)));
        header('Location: ' . baseUrl('/admin/index.php'));
        exit;
    }
}

// Fetch Metrics
$totalTrials = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE status = 'trial_requested'")->fetchColumn();
$totalEnrolled = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE status = 'enrolled'")->fetchColumn();
$totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalTeachers = (int)$pdo->query("SELECT COUNT(*) FROM teachers WHERE status = 'active'")->fetchColumn();
$totalClasses = (int)$pdo->query("SELECT COUNT(*) FROM classes WHERE status = 'active'")->fetchColumn();
$totalCourses = (int)$pdo->query("SELECT COUNT(*) FROM courses WHERE is_active = 1")->fetchColumn();

// Fetch Recent Leads / Trial Registrations
$recentStudents = $pdo->query("
    SELECT * FROM students 
    ORDER BY id DESC 
    LIMIT 10
")->fetchAll();

// Fetch Active Classes
$activeClasses = $pdo->query("
    SELECT c.*, t.full_name as teacher_name, crs.title as course_name,
           (SELECT COUNT(*) FROM class_students cs WHERE cs.class_id = c.id) as enrolled_count
    FROM classes c
    LEFT JOIN teachers t ON c.teacher_id = t.id
    LEFT JOIN courses crs ON c.course_id = crs.id
    WHERE c.status = 'active'
    ORDER BY c.id DESC
    LIMIT 5
")->fetchAll();
?>

<!-- Welcome Banner -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-[#ebdcb8] shadow-sm">
  <div>
    <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">
      Assalamu Alaikum, <?= e($admin['name'] ?? 'Admin') ?> 👋
    </h1>
    <p class="text-xs sm:text-sm text-gray-600 mt-1">
      Welcome to AL MAQRA administration. <span class="font-arabic font-bold text-[#1a422f]">المَقرَأ</span> &bull; <em>Learn. Recite. Reflect.</em>
    </p>
  </div>
  <div class="flex flex-wrap items-center gap-2">
    <a
      href="<?= baseUrl('/admin/student_form.php') ?>"
      class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] text-xs font-bold shadow-sm transition-all border border-[#f3ebd6]/20"
    >
      <span>+ Add Student</span>
    </a>
    <a
      href="<?= baseUrl('/admin/class_form.php') ?>"
      class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#f3ebd6] hover:bg-[#ebdcb8] text-[#1a422f] border border-[#ebdcb8] text-xs font-bold transition-all"
    >
      <span>+ Schedule Class</span>
    </a>
  </div>
</div>

<!-- Metrics Overview Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
  
  <div class="bg-white p-5 rounded-2xl border border-[#ebdcb8] shadow-sm">
    <div class="flex items-center justify-between text-xs font-bold text-amber-700 mb-2">
      <span>Trial Requests</span>
      <span class="p-1 rounded-md bg-amber-50">⏳</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-[#02110c]"><?= $totalTrials ?></div>
    <p class="text-[11px] text-gray-500 mt-1">Pending trial classes</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#ebdcb8] shadow-sm">
    <div class="flex items-center justify-between text-xs font-bold text-emerald-800 mb-2">
      <span>Enrolled Students</span>
      <span class="p-1 rounded-md bg-[#f3ebd6]">🎓</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-[#02110c]"><?= $totalEnrolled ?></div>
    <p class="text-[11px] text-gray-500 mt-1"><?= $totalStudents ?> total registered</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#ebdcb8] shadow-sm">
    <div class="flex items-center justify-between text-xs font-bold text-[#1a422f] mb-2">
      <span>Active Teachers</span>
      <span class="p-1 rounded-md bg-[#f3ebd6]">👨‍🏫</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-[#02110c]"><?= $totalTeachers ?></div>
    <p class="text-[11px] text-gray-500 mt-1">Qualified Ustadhs</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#ebdcb8] shadow-sm">
    <div class="flex items-center justify-between text-xs font-bold text-blue-700 mb-2">
      <span>Active Classes</span>
      <span class="p-1 rounded-md bg-blue-50">🏫</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-[#02110c]"><?= $totalClasses ?></div>
    <p class="text-[11px] text-gray-500 mt-1">Ongoing batches/slots</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#ebdcb8] shadow-sm col-span-2 lg:col-span-1">
    <div class="flex items-center justify-between text-xs font-bold text-purple-700 mb-2">
      <span>Courses</span>
      <span class="p-1 rounded-md bg-purple-50">📖</span>
    </div>
    <div class="text-2xl sm:text-3xl font-extrabold text-[#02110c]"><?= $totalCourses ?></div>
    <p class="text-[11px] text-gray-500 mt-1">Curriculum programs</p>
  </div>

</div>

<!-- Recent Leads & Registrations Table -->
<div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm overflow-hidden mb-8">
  <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-lg font-extrabold text-[#02110c]">Recent Leads &amp; Trial Registrations</h2>
      <p class="text-xs text-gray-500 mt-0.5">Leads submitted via the website landing page and admissions form.</p>
    </div>
    <a href="<?= baseUrl('/admin/students.php') ?>" class="text-xs font-bold text-[#1a422f] hover:text-[#02110c]">
      View All Students (<?= $totalStudents ?>) →
    </a>
  </div>

  <?php if (empty($recentStudents)): ?>
    <div class="p-12 text-center text-gray-500">
      <div class="text-4xl mb-3">📋</div>
      <p class="font-bold text-base text-gray-800">No student registrations yet.</p>
      <p class="text-xs text-gray-500 mt-1">New leads from the landing page will appear here immediately.</p>
      <div class="mt-4">
        <a href="<?= baseUrl('/admin/student_form.php') ?>" class="inline-block px-4 py-2 bg-[#1a422f] text-[#f3ebd6] rounded-xl text-xs font-bold">
          + Add First Student
        </a>
      </div>
    </div>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#faf7f0] text-gray-600 font-bold uppercase tracking-wider text-[10px] border-b border-[#ebdcb8]">
          <tr>
            <th class="py-3.5 px-5">Student / Contact</th>
            <th class="py-3.5 px-4">Course &amp; Level</th>
            <th class="py-3.5 px-4">Location &amp; Timing</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4">Registered</th>
            <th class="py-3.5 px-5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          <?php foreach ($recentStudents as $s): ?>
            <?php 
              $cleanStudentPhone = preg_replace('/[^0-9]/', '', $s['phone']);
              $studentWhatsAppUrl = "https://wa.me/{$cleanStudentPhone}?text=" . rawurlencode(
                "Assalamu Alaikum " . $s['full_name'] . ",\nThis is AL MAQRA Academy regarding your Free Trial Quran Class registration for " . ($s['course_title'] ?? 'Quran Learning') . ". Are you available to schedule your trial class?"
              );
            ?>
            <tr class="hover:bg-[#faf7f0]/80 transition-colors">
              <td class="py-4 px-5">
                <div class="font-bold text-sm text-[#02110c]"><?= e($s['full_name']) ?></div>
                <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                  <span>Age: <?= e($s['age'] ?? 'N/A') ?></span>
                  <span>•</span>
                  <span><?= e($s['gender'] ?? 'Male') ?></span>
                </div>
              </td>
              <td class="py-4 px-4">
                <div class="text-xs font-semibold text-[#1a422f]"><?= e($s['course_title'] ?? 'General Quran') ?></div>
              </td>
              <td class="py-4 px-4">
                <div class="text-xs font-semibold text-gray-800">🌍 <?= e($s['country'] ?? 'Worldwide') ?></div>
                <div class="text-[11px] text-gray-500 mt-0.5">⏰ <?= e($s['preferred_time'] ?? 'Flexible') ?></div>
              </td>
              <td class="py-4 px-4">
                <form method="POST" action="" class="inline-block">
                  <input type="hidden" name="action" value="update_status" />
                  <input type="hidden" name="student_id" value="<?= $s['id'] ?>" />
                  <select
                    name="status"
                    onchange="this.form.submit()"
                    class="text-[11px] font-semibold rounded-lg border border-slate-300 py-1 px-2 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#1a422f] cursor-pointer"
                  >
                    <option value="trial_requested" <?= $s['status'] === 'trial_requested' ? 'selected' : '' ?>>⏳ Trial Requested</option>
                    <option value="trial_scheduled" <?= $s['status'] === 'trial_scheduled' ? 'selected' : '' ?>>📅 Trial Scheduled</option>
                    <option value="trial_completed" <?= $s['status'] === 'trial_completed' ? 'selected' : '' ?>>✓ Trial Completed</option>
                    <option value="enrolled" <?= $s['status'] === 'enrolled' ? 'selected' : '' ?>>🎓 Enrolled</option>
                    <option value="inactive" <?= $s['status'] === 'inactive' ? 'selected' : '' ?>>○ Inactive</option>
                  </select>
                </form>
              </td>
              <td class="py-4 px-4 text-gray-500 text-[11px]">
                <?= date('d M Y, h:i A', strtotime($s['created_at'])) ?>
              </td>
              <td class="py-4 px-5 text-right space-x-2">
                <a
                  href="<?= $studentWhatsAppUrl ?>"
                  target="_blank"
                  title="Direct WhatsApp Chat"
                  class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-xs shadow-sm transition-colors"
                >
                  <span>💬 WhatsApp</span>
                </a>
                <a
                  href="<?= baseUrl('/admin/student_form.php?id=' . $s['id']) ?>"
                  class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-[#f3ebd6] hover:bg-[#ebdcb8] text-[#1a422f] font-bold text-xs transition-colors border border-[#ebdcb8]"
                >
                  Edit
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- Active Classes Section -->
<div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm overflow-hidden">
  <div class="p-6 border-b border-slate-100 flex items-center justify-between">
    <div>
      <h2 class="text-lg font-extrabold text-[#02110c]">Active Classes &amp; Batches</h2>
      <p class="text-xs text-gray-500 mt-0.5">Assigned teachers, course schedule, and student capacity.</p>
    </div>
    <a href="<?= baseUrl('/admin/classes.php') ?>" class="text-xs font-bold text-[#1a422f] hover:text-[#02110c]">
      Manage Classes →
    </a>
  </div>

  <?php if (empty($activeClasses)): ?>
    <div class="p-8 text-center text-gray-500 text-xs">
      <p>No active classes scheduled yet.</p>
      <a href="<?= baseUrl('/admin/class_form.php') ?>" class="inline-block mt-3 px-3.5 py-1.5 bg-[#1a422f] text-[#f3ebd6] rounded-lg font-bold">
        + Create Class
      </a>
    </div>
  <?php else: ?>
    <div class="divide-y divide-slate-100">
      <?php foreach ($activeClasses as $cls): ?>
        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-[#faf7f0] transition-colors">
          <div>
            <div class="font-bold text-sm text-[#02110c]"><?= e($cls['title']) ?></div>
            <div class="text-xs text-gray-600 mt-1 flex flex-wrap items-center gap-3">
              <span>📖 Course: <strong><?= e($cls['course_name'] ?? 'Unassigned') ?></strong></span>
              <span>•</span>
              <span>👨‍🏫 Teacher: <strong><?= e($cls['teacher_name'] ?? 'Unassigned') ?></strong></span>
            </div>
            <div class="text-[11px] text-gray-500 mt-1">
              🗓 <?= e($cls['schedule_days']) ?> at <?= e($cls['time_slot']) ?>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#f3ebd6] text-[#1a422f] border border-[#ebdcb8]">
              👥 <?= (int)$cls['enrolled_count'] ?> Enrolled
            </span>
            <a
              href="<?= baseUrl('/admin/class_form.php?id=' . $cls['id']) ?>"
              class="px-3 py-1.5 rounded-lg bg-[#f3ebd6] hover:bg-[#ebdcb8] text-[#1a422f] font-bold text-xs border border-[#ebdcb8] transition-colors"
            >
              Manage Class
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>