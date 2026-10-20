<?php
/**
 * AL MAQRA (المقرأ) - Add / Edit Student Form
 */

require_once __DIR__ . '/includes/header.php';

$studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$isEdit = false;

if ($studentId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();
    if ($student) {
        $isEdit = true;
    }
}

$allCourses = getActiveCourses($pdo);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = clean($_POST['full_name'] ?? '');
    $age = !empty($_POST['age']) ? (int)$_POST['age'] : null;
    $gender = clean($_POST['gender'] ?? 'Male');
    $parentName = clean($_POST['parent_name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $country = clean($_POST['country'] ?? 'India');
    $courseId = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;
    $preferredTime = clean($_POST['preferred_time'] ?? '');
    $status = clean($_POST['status'] ?? 'trial_requested');
    $notes = clean($_POST['notes'] ?? '');

    // Resolve course title
    $courseTitle = '';
    if ($courseId) {
        foreach ($allCourses as $c) {
            if ($c['id'] == $courseId) {
                $courseTitle = $c['title'];
                break;
            }
        }
    }

    if (empty($fullName)) {
        $error = 'Student Full Name is required.';
    } elseif (empty($phone)) {
        $error = 'Phone / WhatsApp number is required.';
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("
                UPDATE students SET
                    full_name = :full_name,
                    age = :age,
                    gender = :gender,
                    parent_name = :parent_name,
                    email = :email,
                    phone = :phone,
                    country = :country,
                    course_id = :course_id,
                    course_title = :course_title,
                    preferred_time = :preferred_time,
                    status = :status,
                    notes = :notes
                WHERE id = :id
            ");
            $stmt->execute([
                ':full_name' => $fullName,
                ':age' => $age,
                ':gender' => $gender,
                ':parent_name' => $parentName,
                ':email' => $email,
                ':phone' => $phone,
                ':country' => $country,
                ':course_id' => $courseId,
                ':course_title' => $courseTitle,
                ':preferred_time' => $preferredTime,
                ':status' => $status,
                ':notes' => $notes,
                ':id' => $studentId
            ]);
            setFlash('success', "Student record updated successfully.");
            header('Location: ' . baseUrl('/admin/students.php'));
            exit;
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO students (
                    full_name, age, gender, parent_name, email, phone, country,
                    course_id, course_title, preferred_time, status, notes
                ) VALUES (
                    :full_name, :age, :gender, :parent_name, :email, :phone, :country,
                    :course_id, :course_title, :preferred_time, :status, :notes
                )
            ");
            $stmt->execute([
                ':full_name' => $fullName,
                ':age' => $age,
                ':gender' => $gender,
                ':parent_name' => $parentName,
                ':email' => $email,
                ':phone' => $phone,
                ':country' => $country,
                ':course_id' => $courseId,
                ':course_title' => $courseTitle,
                ':preferred_time' => $preferredTime,
                ':status' => $status,
                ':notes' => $notes
            ]);
            setFlash('success', "New student registered successfully.");
            header('Location: ' . baseUrl('/admin/students.php'));
            exit;
        }
    }
}
?>

<div class="max-w-3xl mx-auto">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">
        <?= $isEdit ? 'Edit Student Details' : 'Register New Student' ?>
      </h1>
      <p class="text-xs sm:text-sm text-gray-500 mt-1">
        Enter academic and contact information for the student.
      </p>
    </div>
    <a href="<?= baseUrl('/admin/students.php') ?>" class="text-xs font-bold text-gray-600 hover:text-[#02110c]">
      ← Back to List
    </a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
      ⚠️ <?= e($error) ?>
    </div>
  <?php endif; ?>

  <div class="bg-white rounded-3xl border border-[#ebdcb8] shadow-sm p-6 sm:p-8">
    <form method="POST" action="" class="space-y-6">
      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Student Full Name *
          </label>
          <input
            type="text"
            name="full_name"
            required
            value="<?= e($student['full_name'] ?? '') ?>"
            placeholder="e.g. Abdullah Khan"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Parent / Guardian Name
          </label>
          <input
            type="text"
            name="parent_name"
            value="<?= e($student['parent_name'] ?? '') ?>"
            placeholder="e.g. Shameer Ahmed"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Age
          </label>
          <input
            type="number"
            name="age"
            min="3"
            max="100"
            value="<?= e($student['age'] ?? '') ?>"
            placeholder="e.g. 8"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Gender
          </label>
          <select
            name="gender"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          >
            <option value="Male" <?= ($student['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= ($student['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Status *
          </label>
          <select
            name="status"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none font-bold"
          >
            <option value="trial_requested" <?= ($student['status'] ?? '') === 'trial_requested' ? 'selected' : '' ?>>⏳ Trial Requested</option>
            <option value="trial_scheduled" <?= ($student['status'] ?? '') === 'trial_scheduled' ? 'selected' : '' ?>>📅 Trial Scheduled</option>
            <option value="trial_completed" <?= ($student['status'] ?? '') === 'trial_completed' ? 'selected' : '' ?>>✓ Trial Completed</option>
            <option value="enrolled" <?= ($student['status'] ?? '') === 'enrolled' ? 'selected' : '' ?>>🎓 Enrolled</option>
            <option value="inactive" <?= ($student['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>○ Inactive</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            WhatsApp / Phone Number *
          </label>
          <input
            type="text"
            name="phone"
            required
            value="<?= e($student['phone'] ?? '') ?>"
            placeholder="+91 9497577616"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none font-mono"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Email Address
          </label>
          <input
            type="email"
            name="email"
            value="<?= e($student['email'] ?? '') ?>"
            placeholder="student@example.com"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Course Program
          </label>
          <select
            name="course_id"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none cursor-pointer"
          >
            <option value="">-- Select Course --</option>
            <?php foreach ($allCourses as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($student['course_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>>
                <?= e($c['title']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Country of Residence
          </label>
          <input
            type="text"
            name="country"
            value="<?= e($student['country'] ?? 'India') ?>"
            placeholder="India, UAE, Saudi Arabia, UK..."
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
          Preferred Class Timing
        </label>
        <input
          type="text"
          name="preferred_time"
          value="<?= e($student['preferred_time'] ?? 'Evening (After School/Work)') ?>"
          placeholder="e.g. 7:00 PM - 8:00 PM Gulf Standard Time"
          class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        />
      </div>

      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
          Internal Notes &amp; Special Requirements
        </label>
        <textarea
          name="notes"
          rows="3"
          placeholder="Student proficiency level, learning pace, teacher requests..."
          class="w-full rounded-xl border border-gray-300 bg-white p-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        ><?= e($student['notes'] ?? '') ?></textarea>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <a
          href="<?= baseUrl('/admin/students.php') ?>"
          class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-slate-50 transition-colors"
        >
          Cancel
        </a>
        <button
          type="submit"
          class="px-6 py-2.5 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-md transition-all"
        >
          <?= $isEdit ? 'Save Changes' : 'Register Student' ?>
        </button>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
