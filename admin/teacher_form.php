<?php
/**
 * AL MAQRA (المقرأ) - Add / Edit Teacher Form
 */

require_once __DIR__ . '/includes/header.php';

$teacherId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$teacher = null;
$isEdit = false;

if ($teacherId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->execute([$teacherId]);
    $teacher = $stmt->fetch();
    if ($teacher) {
        $isEdit = true;
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = clean($_POST['full_name'] ?? '');
    $gender = clean($_POST['gender'] ?? 'Male');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $qualification = clean($_POST['qualification'] ?? '');
    $specialization = clean($_POST['specialization'] ?? '');
    $languages = clean($_POST['languages'] ?? 'Malayalam, English, Arabic');
    $status = clean($_POST['status'] ?? 'active');
    $bio = clean($_POST['bio'] ?? '');

    if (empty($fullName)) {
        $error = 'Teacher full name is required.';
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("
                UPDATE teachers SET
                    full_name = :full_name,
                    gender = :gender,
                    email = :email,
                    phone = :phone,
                    qualification = :qualification,
                    specialization = :specialization,
                    languages = :languages,
                    status = :status,
                    bio = :bio
                WHERE id = :id
            ");
            $stmt->execute([
                ':full_name' => $fullName,
                ':gender' => $gender,
                ':email' => $email,
                ':phone' => $phone,
                ':qualification' => $qualification,
                ':specialization' => $specialization,
                ':languages' => $languages,
                ':status' => $status,
                ':bio' => $bio,
                ':id' => $teacherId
            ]);
            setFlash('success', "Teacher profile updated successfully.");
            header('Location: ' . baseUrl('/admin/teachers.php'));
            exit;
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO teachers (
                    full_name, gender, email, phone, qualification,
                    specialization, languages, status, bio
                ) VALUES (
                    :full_name, :gender, :email, :phone, :qualification,
                    :specialization, :languages, :status, :bio
                )
            ");
            $stmt->execute([
                ':full_name' => $fullName,
                ':gender' => $gender,
                ':email' => $email,
                ':phone' => $phone,
                ':qualification' => $qualification,
                ':specialization' => $specialization,
                ':languages' => $languages,
                ':status' => $status,
                ':bio' => $bio
            ]);
            setFlash('success', "Teacher added successfully.");
            header('Location: ' . baseUrl('/admin/teachers.php'));
            exit;
        }
    }
}
?>

<div class="max-w-3xl mx-auto">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-extrabold text-[#02110c] tracking-tight">
        <?= $isEdit ? 'Edit Teacher Profile' : 'Add New Teacher' ?>
      </h1>
      <p class="text-xs sm:text-sm text-gray-500 mt-1">
        Manage teacher credentials, specializations, and contact details.
      </p>
    </div>
    <a href="<?= baseUrl('/admin/teachers.php') ?>" class="text-xs font-bold text-gray-600 hover:text-[#02110c]">
      ← Back to Directory
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
            Teacher Full Name *
          </label>
          <input
            type="text"
            name="full_name"
            required
            value="<?= e($teacher['full_name'] ?? '') ?>"
            placeholder="e.g. Ustadh Hafiz Muhammad Bilal"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Gender *
          </label>
          <select
            name="gender"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          >
            <option value="Male" <?= ($teacher['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male (Ustadh)</option>
            <option value="Female" <?= ($teacher['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female (Ustadha)</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            WhatsApp Phone Number
          </label>
          <input
            type="text"
            name="phone"
            value="<?= e($teacher['phone'] ?? '+91 9497577616') ?>"
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
            value="<?= e($teacher['email'] ?? '') ?>"
            placeholder="teacher@almaqra.com"
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
            <option value="active" <?= ($teacher['status'] ?? '') === 'active' ? 'selected' : '' ?>>● Active</option>
            <option value="inactive" <?= ($teacher['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>○ Inactive</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Specialization
          </label>
          <input
            type="text"
            name="specialization"
            value="<?= e($teacher['specialization'] ?? '') ?>"
            placeholder="e.g. Noorani Qaida, Tajweed Mastery, Hifz"
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
            Teaching Languages
          </label>
          <input
            type="text"
            name="languages"
            value="<?= e($teacher['languages'] ?? 'Malayalam, English, Arabic') ?>"
            placeholder="Malayalam, English, Urdu, Arabic..."
            class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
          />
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
          Qualifications &amp; Sanad / Ijazah
        </label>
        <input
          type="text"
          name="qualification"
          value="<?= e($teacher['qualification'] ?? '') ?>"
          placeholder="e.g. Hafiz-ul-Quran, Alim, Ijazah in Tajweed from Al-Azhar..."
          class="w-full h-11 rounded-xl border border-gray-300 bg-white px-4 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        />
      </div>

      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
          Short Bio / Background
        </label>
        <textarea
          name="bio"
          rows="3"
          placeholder="Teaching experience, teaching methodology, student target..."
          class="w-full rounded-xl border border-gray-300 bg-white p-3 text-xs font-medium text-[#02110c] focus:border-[#1a422f] focus:outline-none"
        ><?= e($teacher['bio'] ?? '') ?></textarea>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <a
          href="<?= baseUrl('/admin/teachers.php') ?>"
          class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-slate-50 transition-colors"
        >
          Cancel
        </a>
        <button
          type="submit"
          class="px-6 py-2.5 rounded-xl bg-[#1a422f] hover:bg-[#02110c] text-[#f3ebd6] border border-[#f3ebd6]/20 text-xs font-bold shadow-md transition-all"
        >
          <?= $isEdit ? 'Save Changes' : 'Add Teacher' ?>
        </button>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
