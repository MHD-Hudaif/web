<?php
/**
 * AL MAQRA (المقرأ) - Helper Functions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate a dynamic base URL that works on subdirectories (/al-maqra/web, /al-maqra) and root vhosts.
 */
function baseUrl(string $path = ''): string {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($script, '/al-maqra/web') !== false) {
        $prefix = '/al-maqra/web';
    } elseif (strpos($script, '/al-maqra') !== false) {
        $prefix = '/al-maqra';
    } else {
        $prefix = '';
    }
    return $prefix . '/' . ltrim($path, '/');
}

/**
 * Sanitize strings for safe HTML rendering.
 */
function e(?string $string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize generic user inputs.
 */
function clean(?string $input): string {
    return trim((string)$input);
}

/**
 * Generate or retrieve CSRF token.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token.
 */
function verify_csrf_token(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Format a WhatsApp link with pre-filled message.
 */
function formatWhatsAppUrl(string $phone, string $message): string {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message);
}

/**
 * Build pre-filled admission WhatsApp message for lead.
 */
function buildAdmissionWhatsAppMessage(array $data): string {
    $name = $data['full_name'] ?? 'Student';
    $age = $data['age'] ?? 'N/A';
    $gender = $data['gender'] ?? 'Not specified';
    $course = $data['course_title'] ?? 'Quran Learning';
    $country = $data['country'] ?? 'Worldwide';
    $timing = $data['preferred_time'] ?? 'Flexible';
    $notes = !empty($data['notes']) ? "\n*Notes:* " . $data['notes'] : '';

    return "Assalamu Alaikum *AL MAQRA (المَقرَأ)* Academy,\n"
        . "_Learn. Recite. Reflect._\n\n"
        . "I have booked a *Free 1-on-1 Trial Class* on your platform:\n\n"
        . "👤 *Student Name:* {$name}\n"
        . "🎂 *Age:* {$age} yrs\n"
        . "🚻 *Teacher/Student:* {$gender}\n"
        . "📖 *Course:* {$course}\n"
        . "🌍 *Country:* {$country}\n"
        . "⏰ *Preferred Timing:* {$timing}"
        . $notes . "\n\n"
        . "Please confirm my free trial schedule. Jazakallahu Khair!";
}

/**
 * Get all active courses.
 */
function getActiveCourses(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM courses WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
    return $stmt->fetchAll();
}

/**
 * Get status badge markup with Tailwind CSS.
 */
function getStatusBadge(string $status): string {
    $map = [
        'trial_requested' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-300', 'label' => 'Trial Requested', 'icon' => '⏳'],
        'trial_scheduled' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-300', 'label' => 'Trial Scheduled', 'icon' => '📅'],
        'trial_completed' => ['bg' => 'bg-purple-100 text-purple-800 border-purple-300', 'label' => 'Trial Completed', 'icon' => '✓'],
        'enrolled' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'label' => 'Enrolled', 'icon' => '🎓'],
        'active' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300', 'label' => 'Active', 'icon' => '●'],
        'inactive' => ['bg' => 'bg-gray-100 text-gray-700 border-gray-300', 'label' => 'Inactive', 'icon' => '○'],
    ];

    $cfg = $map[$status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-300', 'label' => ucfirst(str_replace('_', ' ', $status)), 'icon' => '•'];

    return sprintf(
        '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border %s"><span>%s</span> %s</span>',
        $cfg['bg'],
        $cfg['icon'],
        e($cfg['label'])
    );
}

/**
 * Flash message helper.
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
