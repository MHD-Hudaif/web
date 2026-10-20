<?php
/**
 * AL MAQRA (المَقرَأ) - Admin Shared Header
 * Theme: Dark Emerald (#02110c), Olive Green (#1a422f), Cream Beige (#f3ebd6)
 */

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

requireAdminLogin();

$admin = currentAdmin();
$currentScript = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($adminPageTitle ?? 'Admin Portal') ?> - AL MAQRA (المَقرَأ)</title>
  <link rel="icon" type="image/png" href="<?= baseUrl('/assets/images/favicon.png') ?>" />

  <!-- Tailwind CSS CDN -->
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
              50: '#faf7f0',
              100: '#f3ebd6',
              200: '#e5d7b5',
              600: '#21523a',
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-[#faf7f0] text-slate-800 min-h-screen flex flex-col">

  <!-- Top Admin Bar (Dark Emerald #02110c) -->
  <header class="bg-[#02110c] text-white shadow-md border-b border-[#1a422f] sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        
        <!-- Brand & Title -->
        <div class="flex items-center gap-3">
          <a href="<?= baseUrl('/admin/index.php') ?>" class="flex items-center gap-2.5 group">
            <img
              src="<?= baseUrl('/assets/images/logo.png') ?>"
              alt="AL MAQRA"
              class="h-9 w-auto object-contain drop-shadow-sm"
            />
            <div>
              <span class="text-base font-extrabold tracking-tight text-white group-hover:text-[#f3ebd6] transition-colors">
                AL MAQRA <span class="font-bold text-[10px] text-[#02110c] bg-[#f3ebd6] px-1.5 py-0.5 rounded ml-1">ADMIN</span>
              </span>
              <p class="text-[9px] text-[#f3ebd6]/75 uppercase tracking-wider font-semibold">Learn. Recite. Reflect.</p>
            </div>
          </a>
          <span class="hidden md:inline-block text-xs bg-[#1a422f] px-2 py-0.5 rounded text-[#f3ebd6] border border-[#ebdcb8]/30 font-arabic">
            المَقرَأ
          </span>
        </div>

        <!-- Desktop Navigation Items -->
        <nav class="hidden md:flex items-center space-x-1 lg:space-x-2 text-sm font-semibold">
          <a
            href="<?= baseUrl('/admin/index.php') ?>"
            class="px-3 py-2 rounded-lg transition-colors <?= $currentScript === 'index.php' ? 'bg-[#1a422f] text-[#f3ebd6] shadow-sm border border-[#f3ebd6]/20' : 'text-[#f3ebd6]/80 hover:bg-[#1a422f]/60 hover:text-[#f3ebd6]' ?>"
          >
            📊 Dashboard
          </a>
          <a
            href="<?= baseUrl('/admin/students.php') ?>"
            class="px-3 py-2 rounded-lg transition-colors <?= in_array($currentScript, ['students.php', 'student_form.php']) ? 'bg-[#1a422f] text-[#f3ebd6] shadow-sm border border-[#f3ebd6]/20' : 'text-[#f3ebd6]/80 hover:bg-[#1a422f]/60 hover:text-[#f3ebd6]' ?>"
          >
            👥 Students &amp; Leads
          </a>
          <a
            href="<?= baseUrl('/admin/teachers.php') ?>"
            class="px-3 py-2 rounded-lg transition-colors <?= in_array($currentScript, ['teachers.php', 'teacher_form.php']) ? 'bg-[#1a422f] text-[#f3ebd6] shadow-sm border border-[#f3ebd6]/20' : 'text-[#f3ebd6]/80 hover:bg-[#1a422f]/60 hover:text-[#f3ebd6]' ?>"
          >
            👨‍🏫 Teachers
          </a>
          <a
            href="<?= baseUrl('/admin/courses.php') ?>"
            class="px-3 py-2 rounded-lg transition-colors <?= $currentScript === 'courses.php' ? 'bg-[#1a422f] text-[#f3ebd6] shadow-sm border border-[#f3ebd6]/20' : 'text-[#f3ebd6]/80 hover:bg-[#1a422f]/60 hover:text-[#f3ebd6]' ?>"
          >
            📖 Courses
          </a>
          <a
            href="<?= baseUrl('/admin/classes.php') ?>"
            class="px-3 py-2 rounded-lg transition-colors <?= in_array($currentScript, ['classes.php', 'class_form.php']) ? 'bg-[#1a422f] text-[#f3ebd6] shadow-sm border border-[#f3ebd6]/20' : 'text-[#f3ebd6]/80 hover:bg-[#1a422f]/60 hover:text-[#f3ebd6]' ?>"
          >
            🏫 Classes
          </a>
        </nav>

        <!-- Right User Actions -->
        <div class="flex items-center gap-3">
          <a
            href="<?= baseUrl('/') ?>"
            target="_blank"
            class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-[#f3ebd6] hover:text-white bg-[#1a422f] hover:bg-[#285f45] px-3 py-1.5 rounded-lg border border-[#f3ebd6]/20 transition-colors"
          >
            <span>🌐 View Site</span>
          </a>
          
          <div class="flex items-center gap-2 pl-2 border-l border-[#1a422f]">
            <span class="text-xs font-semibold text-[#f3ebd6]/90 hidden lg:inline">
              <?= e($admin['name'] ?? 'Admin') ?>
            </span>
            <a
              href="<?= baseUrl('/admin/logout.php') ?>"
              class="text-xs font-bold text-red-200 hover:text-white bg-red-950/60 hover:bg-red-900 px-2.5 py-1.5 rounded-lg border border-red-800/50 transition-colors"
            >
              Logout
            </a>
          </div>
        </div>

      </div>
    </div>
  </header>

  <!-- Flash Messages -->
  <?php $flash = getFlash(); ?>
  <?php if ($flash): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
      <div class="p-4 rounded-xl border text-sm font-semibold flex items-center justify-between <?= $flash['type'] === 'success' ? 'bg-[#f3ebd6] text-[#02110c] border-[#ebdcb8]' : 'bg-red-50 text-red-900 border-red-300' ?>">
        <span><?= e($flash['message']) ?></span>
        <button onclick="this.parentElement.remove()" class="text-xs font-bold opacity-60 hover:opacity-100">✕</button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Content Container -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">