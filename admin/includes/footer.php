<?php
/**
 * AL MAQRA (المقرأ) - Admin Shared Footer
 */
?>
  </main>

  <footer class="bg-white border-t border-[#ebdcb8] py-6 text-center text-xs text-slate-500 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
      <p>&copy; <?= date('Y') ?> AL MAQRA (المقرأ) - Student &amp; Academic Management System</p>
      <p class="text-slate-400">Database: <?= e($GLOBALS['DB_DRIVER'] ?? 'Connected') ?> | Laragon Ready</p>
    </div>
  </footer>

</body>
</html>
