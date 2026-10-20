<?php
/**
 * AL MAQRA (المَقرَأ) - Admin Login
 * Brand Theme: Dark Emerald (#02110c), Olive Green (#1a422f), Cream Beige (#f3ebd6)
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (isAdminLoggedIn()) {
    header('Location: ' . baseUrl('/admin/index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = clean($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email address and password.';
    } else {
        if (attemptAdminLogin($pdo, $email, $password)) {
            header('Location: ' . baseUrl('/admin/index.php'));
            exit;
        } else {
            $error = 'Invalid email address or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - AL MAQRA (المَقرَأ)</title>
  <link rel="icon" type="image/png" href="<?= baseUrl('/assets/images/favicon.png') ?>" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            darkEmerald: '#02110c',
            oliveGreen: '#1a422f',
            creamBeige: '#f3ebd6',
            brand: {
              700: '#1a422f',
              800: '#0e291d',
              900: '#02110c',
              950: '#010806'
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
            arabic: ['"Amiri"', 'serif']
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-[#02110c] via-[#092217] to-[#1a422f] min-h-screen flex items-center justify-center p-4">

  <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#ebdcb8]">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#02110c] to-[#1a422f] p-8 text-center text-white relative border-b border-[#ebdcb8]/30">
      <img
        src="<?= baseUrl('/assets/images/logo.png') ?>"
        alt="AL MAQRA (المَقرَأ)"
        class="h-16 mx-auto mb-3 object-contain drop-shadow-xl"
      />
      <h1 class="text-2xl font-extrabold tracking-tight text-[#f3ebd6]">AL MAQRA <span class="font-arabic text-lg text-emerald-300">المَقرَأ</span></h1>
      <p class="text-xs text-[#f3ebd6]/80 mt-1 uppercase tracking-widest font-semibold">Learn. Recite. Reflect. &bull; Portal Login</p>
    </div>

    <!-- Login Form -->
    <div class="p-8">
      <?php if (!empty($error)): ?>
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center gap-2">
          <span>⚠️</span> <span><?= e($error) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="" class="space-y-5">
        <div>
          <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
            Admin Email Address
          </label>
          <input
            type="email"
            id="email"
            name="email"
            required
            value="<?= e($_POST['email'] ?? 'admin@almaqra.com') ?>"
            placeholder="admin@almaqra.com"
            class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-[#02110c] focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all"
          />
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#02110c]">
              Password
            </label>
          </div>
          <input
            type="password"
            id="password"
            name="password"
            required
            placeholder="••••••••"
            value="admin123"
            class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-[#02110c] focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all"
          />
        </div>

        <button
          type="submit"
          class="w-full h-12 rounded-xl bg-gradient-to-r from-[#1a422f] to-[#02110c] text-[#f3ebd6] font-bold text-sm hover:from-[#285f45] hover:to-[#0e291d] shadow-lg hover:shadow-xl transition-all duration-200 border border-[#f3ebd6]/20"
        >
          Sign In to Portal
        </button>
      </form>

      <!-- Default Credential Helper Note -->
      <div class="mt-6 p-3.5 rounded-xl bg-[#f3ebd6] border border-[#ebdcb8] text-[#02110c] text-xs">
        <p class="font-bold mb-1 text-[#1a422f]">🔑 Default Credentials:</p>
        <p>Email: <code class="bg-white px-1.5 py-0.5 rounded border border-[#ebdcb8] font-mono text-[11px] text-[#02110c]"><?= e(env('DEFAULT_ADMIN_EMAIL', 'admin@almaqra.com')) ?></code></p>
        <p class="mt-0.5">Password: <code class="bg-white px-1.5 py-0.5 rounded border border-[#ebdcb8] font-mono text-[11px] text-[#02110c]"><?= e(env('DEFAULT_ADMIN_PASSWORD', 'admin123')) ?></code></p>
      </div>

      <div class="mt-6 text-center">
        <a href="<?= baseUrl('/') ?>" class="text-xs font-semibold text-gray-500 hover:text-[#1a422f] transition-colors">
          ← Back to Public Website
        </a>
      </div>
    </div>
  </div>

</body>
</html>