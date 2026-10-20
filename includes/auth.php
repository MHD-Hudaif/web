<?php
/**
 * AL MAQRA (المقرأ) - Admin Authentication & Session Security
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the admin is authenticated.
 */
function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_user']) && !empty($_SESSION['admin_user']['id']);
}

/**
 * Get the currently logged-in admin.
 */
function currentAdmin(): ?array {
    return $_SESSION['admin_user'] ?? null;
}

/**
 * Require admin authentication; redirect to login if unauthorized.
 */
function requireAdminLogin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: ' . baseUrl('/admin/login.php'));
        exit;
    }
}

/**
 * Attempt admin login.
 */
function attemptAdminLogin(PDO $pdo, string $email, string $password): bool {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
    $stmt->execute([trim($email)]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);
        $_SESSION['admin_user'] = [
            'id' => $admin['id'],
            'name' => $admin['name'],
            'email' => $admin['email'],
            'role' => $admin['role'] ?? 'admin'
        ];
        return true;
    }

    return false;
}

/**
 * Log out admin.
 */
function logoutAdmin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['admin_user']);
    session_destroy();
}
