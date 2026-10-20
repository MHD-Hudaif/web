<?php
/**
 * AL MAQRA (المَقرَأ) - Database Configuration
 * Loads connection credentials and sensitive parameters strictly from .env
 */

require_once __DIR__ . '/../includes/env.php';

// Database Credentials loaded exclusively from .env
define('DB_DRIVER', (string)env('DB_DRIVER', 'mysql'));
define('DB_HOST', (string)env('DB_HOST', '127.0.0.1'));
define('DB_PORT', (string)env('DB_PORT', '3306'));
define('DB_NAME', (string)env('DB_NAME', 'al_maqra'));
define('DB_USER', (string)env('DB_USER', 'root'));
define('DB_PASS', (string)env('DB_PASS', ''));

// Organization & Contact Configuration loaded from .env
define('APP_NAME', (string)env('APP_NAME', 'AL MAQRA'));
define('APP_NAME_AR', (string)env('APP_NAME_AR', 'المَقرَأ'));
define('APP_TAGLINE', (string)env('APP_TAGLINE', 'Learn. Recite. Reflect.'));
define('WHATSAPP_NUMBER', (string)env('WHATSAPP_NUMBER', '919497577616'));
define('WHATSAPP_DISPLAY', (string)env('WHATSAPP_DISPLAY', '+91 9497577616'));
define('OFFICIAL_EMAIL', (string)env('OFFICIAL_EMAIL', 'info@almaqra.com'));
define('INSTAGRAM_URL', (string)env('INSTAGRAM_URL', 'https://www.instagram.com/reel/DecGHKqBme5/'));
define('WHATSAPP_CHANNEL_URL', (string)env('WHATSAPP_CHANNEL_URL', 'https://whatsapp.com/channel/0029VbCfp7UHgZWk09TcOT2l'));

/**
 * Returns a PDO database connection instance.
 * Connects to MySQL using .env variables, with fallback to SQLite if needed.
 */
function getDBConnection(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    if (DB_DRIVER === 'mysql') {
        try {
            $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', DB_HOST, DB_PORT);
            $tempPdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3,
            ]);

            // Ensure database exists
            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // Connect to specific database
            $pdo = new PDO(
                sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME),
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            $GLOBALS['DB_DRIVER'] = 'mysql';
            return $pdo;
        } catch (Exception $e) {
            // MySQL error; fall back to SQLite if in development
        }
    }

    // Fallback to SQLite
    $dbDir = __DIR__ . '/../database';
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0777, true);
    }
    $sqliteFile = $dbDir . '/al_maqra.sqlite';
    $pdo = new PDO('sqlite:' . $sqliteFile, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $GLOBALS['DB_DRIVER'] = 'sqlite';
    return $pdo;
}
