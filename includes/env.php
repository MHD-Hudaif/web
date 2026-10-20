<?php
/**
 * AL MAQRA (المَقرَأ) - Environment Variables Loader
 * Reads secrets and configuration from .env file securely.
 */

if (!function_exists('loadEnv')) {
    /**
     * Parse and load .env file into $_ENV, $_SERVER, and putenv().
     */
    function loadEnv(string $filePath): void {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            // Must contain an '=' separator
            if (strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Strip surrounding quotes (double or single)
            if (strlen($value) >= 2) {
                if (($value[0] === '"' && substr($value, -1) === '"') ||
                    ($value[0] === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Parse boolean/null values
            $lower = strtolower($value);
            if ($lower === 'true') {
                $parsed = true;
            } elseif ($lower === 'false') {
                $parsed = false;
            } elseif ($lower === 'null') {
                $parsed = null;
            } else {
                $parsed = $value;
            }

            $_ENV[$key] = $parsed;
            $_SERVER[$key] = $parsed;
            if (is_scalar($parsed)) {
                putenv("{$key}={$parsed}");
            }
        }
    }
}

if (!function_exists('env')) {
    /**
     * Get an environment variable with optional default value fallback.
     */
    function env(string $key, $default = null) {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }
        $val = getenv($key);
        if ($val !== false) {
            return $val;
        }
        return $default;
    }
}

// Automatically load .env from project root
loadEnv(dirname(__DIR__) . '/.env');
