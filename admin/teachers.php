<?php
/**
 * AL MAQRA (المقرأ) - Teachers Management
 */

$adminPageTitle = "Teachers Directory";
require_once __DIR__ . '/includes/header.php';

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $delId = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->execute([$delId]);
    setFlash('success', "Teacher record #{$delId} deleted.");
    header('Location: ' . baseUrl('/admin/teachers.php'));
    exit;
}

// Fetch all teachers with count of their classes
$teachers = $pdo->query("
    SELECT t.*, 
           (SELECT COUNT(*) FROM classes c WHERE c.teacher_id = t.id) as classes_count
    FROM teachers t
    ORDER BY t.status ASC, t.id DESC
")->fetchAll();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">Teachers &amp; Ustadhs</h1>
    <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage certified Quran teachers, Huffaz, and instructors.</p>
  </div>
  <a
    href="<?= baseUrl('/admin/teacher_form.php') ?>"
    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-sm transition-all"
  >
    <span>+ Add New Teacher</span>
  </a>
</div>

<!-- Teachers Table -->
<div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm overflow-hidden">
  <div class="p-5 border-b border-slate-100 flex items-center justify-between">
    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">
      Total <?= count($teachers) ?> Teachers
    </div>
  </div>

  <?php if (empty($teachers)): ?>
    <div class="p-12 text-center text-gray-500">
      <div class="text-4xl mb-2">👨‍🏫</div>
      <p class="font-bold text-gray-800">No teachers found.</p>
      <div class="mt-3">
        <a href="<?= baseUrl('/admin/teacher_form.php') ?>" class="px-4 py-2 bg-[#1a422f] text-[#f3ebd6] rounded-xl text-xs font-bold inline-block">
          + Add First Teacher
        </a>
      </div>
    </div>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 text-gray-500 font-bold uppercase tracking-wider text-[10px] border-b border-[#ebdcb8]">
          <tr>
            <th class="py-3.5 px-5">Teacher Name</th>
            <th class="py-3.5 px-4">Gender &amp; Contact</th>
            <th class="py-3.5 px-4">Specialization &amp; Qualification</th>
            <th class="py-3.5 px-4">Languages</th>
            <th class="py-3.5 px-4">Classes</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-5 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-medium">
          <?php foreach ($teachers as $t): ?>
            <?php 
              $cleanTeacherPhone = preg_replace('/[^0-9]/', '', $t['phone'] ?? '');
              $teacherWaUrl = "https://wa.me/{$cleanTeacherPhone}?text=" . rawurlencode("Assalamu Alaikum Ustadh/Ustadha " . $t['full_name'] . ", message from AL MAQRA administration.");
            ?>
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="py-4 px-5">
                <div class="font-bold text-sm text-[#02110c]"><?= e($t['full_name']) ?></div>
                <?php if (!empty($t['bio'])): ?>
                  <div class="text-[11px] text-gray-500 mt-0.5 line-clamp-1 max-w-xs">
                    <?= e($t['bio']) ?>
                  </div>
                <?php endif; ?>
              </td>

              <td class="py-4 px-4">
                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold <?= $t['gender'] === 'Female' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800' ?>">
                  <?= e($t['gender'] ?? 'Male') ?>
                </span>
                <div class="font-mono text-[11px] text-gray-800 mt-1"><?= e($t['phone'] ?? 'N/A') ?></div>
                <?php if (!empty($t['email'])): ?>
                  <div class="text-[10px] text-gray-400"><?= e($t['email']) ?></div>
                <?php endif; ?>
              </td>

              <td class="py-4 px-4">
                <div class="font-semibold text-brand-900"><?= e($t['specialization'] ?? 'Quran Recitation') ?></div>
                <div class="text-[11px] text-gray-500 mt-0.5"><?= e($t['qualification'] ?? '') ?></div>
              </td>

              <td class="py-4 px-4 text-gray-600 text-xs">
                <?= e($t['languages'] ?? 'Malayalam, English') ?>
              </td>

              <td class="py-4 px-4">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                  🏫 <?= (int)$t['classes_count'] ?> Classes
                </span>
              </td>

              <td class="py-4 px-4">
                <?= getStatusBadge($t['status']) ?>
              </td>

              <td class="py-4 px-5 text-right space-x-2 whitespace-nowrap">
                <?php if (!empty($cleanTeacherPhone)): ?>
                  <a
                    href="<?= $teacherWaUrl ?>"
                    target="_blank"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-xs shadow-sm transition-colors"
                  >
                    💬 WhatsApp
                  </a>
                <?php endif; ?>
                <a
                  href="<?= baseUrl('/admin/teacher_form.php?id=' . $t['id']) ?>"
                  class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-gray-700 font-bold text-xs transition-colors"
                >
                  Edit
                </a>
                <a
                  href="<?= baseUrl('/admin/teachers.php?action=delete&id=' . $t['id']) ?>"
                  onclick="return confirm('Are you sure you want to remove this teacher?');"
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
