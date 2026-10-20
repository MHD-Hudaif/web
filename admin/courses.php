<?php
/**
 * AL MAQRA (المقرأ) - Courses Management
 */

$adminPageTitle = "Courses Curriculum";
require_once __DIR__ . '/includes/header.php';

// Handle course deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $delId = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$delId]);
    setFlash('success', "Course #{$delId} removed.");
    header('Location: ' . baseUrl('/admin/courses.php'));
    exit;
}

// Handle Add / Edit Course Submission
$editCourse = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$editId]);
    $editCourse = $stmt->fetch();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
    $title = clean($_POST['title'] ?? '');
    $category = clean($_POST['category'] ?? 'Quran');
    $description = clean($_POST['description'] ?? '');
    $duration = clean($_POST['duration'] ?? '3 - 6 Months');
    $level = clean($_POST['level'] ?? 'All Levels');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    if (empty($title)) {
        $error = 'Course title is required.';
    } else {
        if ($courseId > 0) {
            $stmt = $pdo->prepare("
                UPDATE courses SET
                    title = :title,
                    slug = :slug,
                    category = :category,
                    description = :description,
                    duration = :duration,
                    level = :level,
                    sort_order = :sort_order,
                    is_active = :is_active
                WHERE id = :id
            ");
            $stmt->execute([
                ':title' => $title,
                ':slug' => $slug,
                ':category' => $category,
                ':description' => $description,
                ':duration' => $duration,
                ':level' => $level,
                ':sort_order' => $sortOrder,
                ':is_active' => $isActive,
                ':id' => $courseId
            ]);
            setFlash('success', "Course '{$title}' updated successfully.");
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO courses (title, slug, category, description, duration, level, sort_order, is_active)
                VALUES (:title, :slug, :category, :description, :duration, :level, :sort_order, :is_active)
            ");
            $stmt->execute([
                ':title' => $title,
                ':slug' => $slug,
                ':category' => $category,
                ':description' => $description,
                ':duration' => $duration,
                ':level' => $level,
                ':sort_order' => $sortOrder,
                ':is_active' => $isActive
            ]);
            setFlash('success', "Course '{$title}' created successfully.");
        }
        header('Location: ' . baseUrl('/admin/courses.php'));
        exit;
    }
}

// Fetch all courses
$courses = $pdo->query("
    SELECT c.*,
           (SELECT COUNT(*) FROM students s WHERE s.course_id = c.id) as students_count,
           (SELECT COUNT(*) FROM classes cl WHERE cl.course_id = c.id) as classes_count
    FROM courses c
    ORDER BY c.sort_order ASC, c.id ASC
")->fetchAll();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">Courses Curriculum</h1>
    <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage courses shown on the landing page and registration forms.</p>
  </div>
</div>

<?php if (!empty($error)): ?>
  <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
    ⚠️ <?= e($error) ?>
  </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
  
  <!-- Course Form (4 Cols) -->
  <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-[#ebdcb8] shadow-sm">
    <h2 class="text-base font-extrabold text-[#02110c] mb-4 flex items-center justify-between">
      <span><?= $editCourse ? 'Edit Course' : '+ Create New Course' ?></span>
      <?php if ($editCourse): ?>
        <a href="<?= baseUrl('/admin/courses.php') ?>" class="text-xs font-bold text-gray-500 hover:text-gray-800">
          Cancel Edit
        </a>
      <?php endif; ?>
    </h2>

    <form method="POST" action="" class="space-y-4">
      <input type="hidden" name="course_id" value="<?= $editCourse['id'] ?? 0 ?>" />

      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Course Title *
        </label>
        <input
          type="text"
          name="title"
          required
          value="<?= e($editCourse['title'] ?? '') ?>"
          placeholder="e.g. Noorani Qaida for Kids"
          class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        />
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Category
          </label>
          <input
            type="text"
            name="category"
            value="<?= e($editCourse['category'] ?? 'Quran') ?>"
            placeholder="Beginner, Tajweed..."
            class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Duration
          </label>
          <input
            type="text"
            name="duration"
            value="<?= e($editCourse['duration'] ?? '3 - 6 Months') ?>"
            placeholder="3 - 6 Months"
            class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Target Level
          </label>
          <input
            type="text"
            name="level"
            value="<?= e($editCourse['level'] ?? 'Beginner to Advanced') ?>"
            placeholder="Age 4+, All Levels"
            class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
            Sort Order
          </label>
          <input
            type="number"
            name="sort_order"
            value="<?= e($editCourse['sort_order'] ?? 1) ?>"
            class="w-full h-10 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
          Description
        </label>
        <textarea
          name="description"
          rows="3"
          placeholder="Brief summary of the course topics, outcomes, and syllabus..."
          class="w-full rounded-xl border border-gray-300 bg-white p-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        ><?= e($editCourse['description'] ?? '') ?></textarea>
      </div>

      <div class="flex items-center gap-2 pt-1">
        <input
          type="checkbox"
          id="is_active"
          name="is_active"
          value="1"
          class="h-4 w-4 text-[#1a422f] rounded border-gray-300 focus:ring-[#1a422f]"
          <?= !isset($editCourse) || ($editCourse['is_active'] ?? 1) ? 'checked' : '' ?>
        />
        <label for="is_active" class="text-xs font-semibold text-gray-700 cursor-pointer">
          Active (Visible on Landing Page &amp; Forms)
        </label>
      </div>

      <button
        type="submit"
        class="w-full h-11 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-md transition-all mt-2"
      >
        <?= $editCourse ? 'Update Course' : 'Create Course' ?>
      </button>
    </form>
  </div>

  <!-- Courses List (8 Cols) -->
  <div class="lg:col-span-8 bg-white rounded-3xl border border-[#ebdcb8] shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
      <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">
        Active Course Catalog (<?= count($courses) ?>)
      </div>
    </div>

    <div class="divide-y divide-slate-100">
      <?php foreach ($courses as $c): ?>
        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <span class="text-xs font-bold text-[#1a422f] bg-[#f3ebd6] px-2 py-0.5 rounded border border-[#ebdcb8]/50">
                <?= e($c['category']) ?>
              </span>
              <span class="text-[11px] text-gray-500 font-semibold">
                ⏱ <?= e($c['duration']) ?> • Level: <?= e($c['level']) ?>
              </span>
              <?php if (!$c['is_active']): ?>
                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-bold">
                  Inactive
                </span>
              <?php endif; ?>
            </div>
            
            <h3 class="font-bold text-sm text-[#02110c]"><?= e($c['title']) ?></h3>
            <p class="text-xs text-gray-600 mt-1 leading-relaxed max-w-xl">
              <?= e($c['description']) ?>
            </p>

            <div class="mt-2 flex items-center gap-4 text-[11px] text-gray-500 font-semibold">
              <span>👥 <?= (int)$c['students_count'] ?> Students enrolled</span>
              <span>•</span>
              <span>🏫 <?= (int)$c['classes_count'] ?> Active batches</span>
            </div>
          </div>

          <div class="flex items-center gap-2 self-end sm:self-center">
            <a
              href="<?= baseUrl('/admin/courses.php?edit=' . $c['id']) ?>"
              class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-gray-700 font-bold text-xs transition-colors"
            >
              Edit
            </a>
            <a
              href="<?= baseUrl('/admin/courses.php?action=delete&id=' . $c['id']) ?>"
              onclick="return confirm('Are you sure you want to delete this course?');"
              class="px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs transition-colors"
            >
              Delete
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
