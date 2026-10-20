<?php
/**
 * AL MAQRA (المقرأ) - Students & Leads Directory
 */

$adminPageTitle = "Students & Leads Directory";
require_once __DIR__ . '/includes/header.php';

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $delId = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$delId]);
    setFlash('success', "Student #{$delId} removed successfully.");
    header('Location: ' . baseUrl('/admin/students.php'));
    exit;
}

// Search and Filter parameters
$search = clean($_GET['q'] ?? '');
$filterStatus = clean($_GET['status'] ?? '');
$filterCourse = clean($_GET['course'] ?? '');

$sql = "SELECT * FROM students WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR country LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if (!empty($filterStatus)) {
    $sql .= " AND status = ?";
    $params[] = $filterStatus;
}

if (!empty($filterCourse)) {
    $sql .= " AND course_title LIKE ?";
    $params[] = "%{$filterCourse}%";
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

$allCourses = getActiveCourses($pdo);
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">Students &amp; Trial Inquiries</h1>
    <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage trial class leads, enrollments, and student contact records.</p>
  </div>
  <a
    href="<?= baseUrl('/admin/student_form.php') ?>"
    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-sm transition-all"
  >
    <span>+ Register New Student</span>
  </a>
</div>

<!-- Filters Bar -->
<div class="bg-white p-5 rounded-3xl border border-[#ebdcb8] shadow-sm mb-6">
  <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
    
    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Search</label>
      <input
        type="text"
        name="q"
        value="<?= e($search) ?>"
        placeholder="Search name, phone, email..."
        class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs text-[#02110c] focus:border-[#1a422f] focus:outline-none"
      />
    </div>

    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Status</label>
      <select
        name="status"
        class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs text-[#02110c] focus:border-[#1a422f] focus:outline-none cursor-pointer"
      >
        <option value="">All Statuses</option>
        <option value="trial_requested" <?= $filterStatus === 'trial_requested' ? 'selected' : '' ?>>Trial Requested</option>
        <option value="trial_scheduled" <?= $filterStatus === 'trial_scheduled' ? 'selected' : '' ?>>Trial Scheduled</option>
        <option value="trial_completed" <?= $filterStatus === 'trial_completed' ? 'selected' : '' ?>>Trial Completed</option>
        <option value="enrolled" <?= $filterStatus === 'enrolled' ? 'selected' : '' ?>>Enrolled</option>
        <option value="inactive" <?= $filterStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>

    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Course</label>
      <select
        name="course"
        class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs text-[#02110c] focus:border-[#1a422f] focus:outline-none cursor-pointer"
      >
        <option value="">All Courses</option>
        <?php foreach ($allCourses as $c): ?>
          <option value="<?= e($c['title']) ?>" <?= $filterCourse === $c['title'] ? 'selected' : '' ?>>
            <?= e($c['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="flex items-center gap-2">
      <button
        type="submit"
        class="flex-1 h-10 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors"
      >
        Apply Filters
      </button>
      <?php if (!empty($search) || !empty($filterStatus) || !empty($filterCourse)): ?>
        <a
          href="<?= baseUrl('/admin/students.php') ?>"
          class="h-10 px-3 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-gray-700 text-xs font-bold transition-colors"
        >
          Reset
        </a>
      <?php endif; ?>
    </div>

  </form>
</div>

<!-- Student List Table -->
<div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm overflow-hidden">
  <div class="p-5 border-b border-slate-100 flex items-center justify-between">
    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">
      Found <?= count($students) ?> Students
    </div>
  </div>

  <?php if (empty($students)): ?>
    <div class="p-12 text-center text-gray-500">
      <div class="text-4xl mb-2">🔍</div>
      <p class="font-bold text-gray-800">No students match your criteria.</p>
      <p class="text-xs text-gray-500 mt-1">Try clearing filters or search keywords.</p>
    </div>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 text-gray-500 font-bold uppercase tracking-wider text-[10px] border-b border-[#ebdcb8]">
          <tr>
            <th class="py-3.5 px-5">Student Info</th>
            <th class="py-3.5 px-4">Phone / WhatsApp</th>
            <th class="py-3.5 px-4">Course</th>
            <th class="py-3.5 px-4">Timing &amp; Country</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-4">Date</th>
            <th class="py-3.5 px-5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          <?php foreach ($students as $s): ?>
            <?php 
              $cleanStudentPhone = preg_replace('/[^0-9]/', '', $s['phone']);
              $studentWhatsAppUrl = "https://wa.me/{$cleanStudentPhone}?text=" . rawurlencode(
                "Assalamu Alaikum " . $s['full_name'] . ",\nThis is AL MAQRA Academy regarding your online Quran learning application. How may we assist you today?"
              );
            ?>
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="py-4 px-5">
                <div class="font-bold text-sm text-[#02110c]"><?= e($s['full_name']) ?></div>
                <div class="text-[11px] text-gray-500 mt-0.5">
                  Age: <?= e($s['age'] ?? 'N/A') ?> yrs • <?= e($s['gender'] ?? 'Male') ?>
                  <?php if (!empty($s['parent_name'])): ?>
                    • Parent: <?= e($s['parent_name']) ?>
                  <?php endif; ?>
                </div>
                <?php if (!empty($s['notes'])): ?>
                  <div class="mt-1 text-[11px] text-amber-900 bg-amber-50 rounded px-1.5 py-0.5 inline-block border border-amber-200/50">
                    📝 <?= e(mb_strimwidth($s['notes'], 0, 45, '...')) ?>
                  </div>
                <?php endif; ?>
              </td>

              <td class="py-4 px-4">
                <div class="font-mono text-xs font-semibold text-[#02110c]"><?= e($s['phone']) ?></div>
                <?php if (!empty($s['email'])): ?>
                  <div class="text-[11px] text-gray-500"><?= e($s['email']) ?></div>
                <?php endif; ?>
              </td>

              <td class="py-4 px-4 font-semibold text-[#1a422f]">
                <?= e($s['course_title'] ?? 'Quran Learning') ?>
              </td>

              <td class="py-4 px-4">
                <div class="text-xs text-gray-800 font-semibold">🌍 <?= e($s['country'] ?? 'Worldwide') ?></div>
                <div class="text-[11px] text-gray-500 mt-0.5">⏰ <?= e($s['preferred_time'] ?? 'Flexible') ?></div>
              </td>

              <td class="py-4 px-4">
                <?= getStatusBadge($s['status']) ?>
              </td>

              <td class="py-4 px-4 text-gray-500 text-[11px]">
                <?= date('d M Y', strtotime($s['created_at'])) ?>
              </td>

              <td class="py-4 px-5 text-right space-x-1.5 whitespace-nowrap">
                <a
                  href="<?= $studentWhatsAppUrl ?>"
                  target="_blank"
                  class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-xs transition-colors shadow-sm"
                  title="Direct WhatsApp"
                >
                  💬 WhatsApp
                </a>
                <a
                  href="<?= baseUrl('/admin/student_form.php?id=' . $s['id']) ?>"
                  class="inline-flex items-center px-2 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-gray-700 font-bold text-xs transition-colors"
                >
                  Edit
                </a>
                <a
                  href="<?= baseUrl('/admin/students.php?action=delete&id=' . $s['id']) ?>"
                  onclick="return confirm('Are you sure you want to delete this student record?');"
                  class="inline-flex items-center px-2 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs transition-colors"
                >
                  Delete
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
