<?php
/**
 * AL MAQRA (المقرأ) - Admin Logout
 */

require_once __DIR__ . '/../includes/auth.php';

logoutAdmin();
header('Location: ' . baseUrl('/admin/login.php'));
exit;
