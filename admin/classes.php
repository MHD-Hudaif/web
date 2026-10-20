<?php
/**
 * AL MAQRA (المقرأ) - Classes & Batches Management
 */

$adminPageTitle = "Classes & Batches";
require_once __DIR__ . '/includes/header.php';

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $delId = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->execute([$delId]);
    $stmtE = $pdo->prepare("DELETE FROM class_students WHERE class_id = ?");
    $stmtE->execute([$delId]);
    setFlash('success', "Class #{$delId} removed.");
    header('Location: ' . baseUrl('/admin/classes.php'));
    exit;
}

// Fetch classes with teacher and course info, and enrolled students
$classes = $pdo->query("
    SELECT c.*, 
           t.full_name as teacher_name, t.phone as teacher_phone,
           crs.title as course_name
    FROM classes c
    LEFT JOIN teachers t ON c.teacher_id = t.id
    LEFT JOIN courses crs ON c.course_id = crs.id
    ORDER BY c.status ASC, c.id DESC
")->fetchAll();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">Classes &amp; Batches</h1>
    <p class="text-xs sm:text-sm text-gray-500 mt-1">
      Schedule 1-on-1 sessions and group batches, assign teachers, and enroll students.
    </p>
  </div>
  <a
    href="<?= baseUrl('/admin/class_form.php') ?>"
    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-sm transition-all"
  >
    <span>+ Schedule New Class</span>
  </a>
</div>

<!-- Classes Grid / Cards -->
<?php if (empty($classes)): ?>
  <div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm p-12 text-center text-gray-500">
    <div class="text-4xl mb-3">🏫</div>
    <h3 class="text-lg font-bold text-gray-800">No classes scheduled yet.</h3>
    <p class="text-xs text-gray-500 mt-1">Create your first class to pair a teacher with students.</p>
    <div class="mt-4">
      <a href="<?= baseUrl('/admin/class_form.php') ?>" class="px-4 py-2 bg-[#1a422f] text-[#f3ebd6] rounded-xl text-xs font-bold inline-block">
        + Schedule First Class
      </a>
    </div>
  </div>
<?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach ($classes as $cls): ?>
      <?php
        // Fetch enrolled students for this class
        $stmtS = $pdo->prepare("
            SELECT s.id, s.full_name, s.phone, s.country 
            FROM class_students cs
            JOIN students s ON cs.student_id = s.id
            WHERE cs.class_id = ?
        ");
        $stmtS->execute([$cls['id']]);
        $enrolledStudents = $stmtS->fetchAll();
      ?>
      <div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm p-6 hover:shadow-md transition-shadow flex flex-col justify-between">
        <div>
          <!-- Header -->
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $cls['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' ?>">
              ● <?= ucfirst(e($cls['status'])) ?>
            </span>
            <span class="text-xs font-semibold text-gray-500">
              Capacity: <?= count($enrolledStudents) ?> / <?= e($cls['max_students']) ?>
            </span>
          </div>

          <h3 class="text-lg font-extrabold text-[#02110c] mb-1">
            <?= e($cls['title']) ?>
          </h3>

          <div class="space-y-1.5 text-xs text-gray-600 mt-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Course:</span>
              <strong class="text-brand-900"><?= e($cls['course_name'] ?? 'General') ?></strong>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Teacher:</span>
              <strong class="text-gray-800"><?= e($cls['teacher_name'] ?? 'Unassigned') ?></strong>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Schedule:</span>
              <span class="font-semibold text-gray-800">🗓 <?= e($cls['schedule_days']) ?></span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Time:</span>
              <span class="font-semibold text-gray-800">⏰ <?= e($cls['time_slot']) ?></span>
            </div>
            <?php if (!empty($cls['meeting_link'])): ?>
              <div class="flex items-center justify-between pt-1 border-t border-[#ebdcb8]/60">
                <span class="text-gray-500">Link:</span>
                <a href="<?= e($cls['meeting_link']) ?>" target="_blank" class="text-[#1a422f] font-bold hover:underline">
                  Open Meeting ↗
                </a>
              </div>
            <?php endif; ?>
          </div>

          <!-- Enrolled Students List -->
          <div class="mt-4">
            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-2">
              Enrolled Students (<?= count($enrolledStudents) ?>)
            </div>
            <?php if (empty($enrolledStudents)): ?>
              <p class="text-xs text-gray-400 italic">No students enrolled yet in this class.</p>
            <?php else: ?>
              <div class="flex flex-wrap gap-1.5">
                <?php foreach ($enrolledStudents as $std): ?>
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#f3ebd6] text-brand-900 border border-[#ebdcb8]/60 text-xs font-semibold">
                    <span>👤</span>
                    <span><?= e($std['full_name']) ?></span>
                    <span class="text-[10px] text-gray-500">(<?= e($std['country']) ?>)</span>
                  </span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
          <a
            href="<?= baseUrl('/admin/class_form.php?id=' . $cls['id']) ?>"
            class="text-xs font-bold text-[#1a422f] hover:text-[#02110c]"
          >
            Edit Class &amp; Students →
          </a>
          <a
            href="<?= baseUrl('/admin/classes.php?action=delete&id=' . $cls['id']) ?>"
            onclick="return confirm('Are you sure you want to delete this class?');"
            class="text-xs font-bold text-red-600 hover:text-red-800"
          >
            Delete
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
