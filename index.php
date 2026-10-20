<?php
/**
 * AL MAQRA (المَقرَأ) - Global Qur'anic Learning Platform
 * High-Converting Free Trial Landing Page
 * Brand Theme: Dark Emerald (#02110c), Olive Green (#1a422f), Cream Beige (#f3ebd6)
 * Tagline: Learn. Recite. Reflect.
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$courses = getActiveCourses($pdo);
$pageTitle = "AL MAQRA (المَقرَأ) - Learn. Recite. Reflect. | Free Trial Online Quran Class";
$pageDesc = "Book your 100% free trial online Quran class at AL MAQRA (المَقرَأ). Learn. Recite. Reflect. Certified male and female Ustadhs, Tajweed, Noorani Qaida, and Hifz.";
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>" />
  <meta property="og:title" content="<?= e($pageTitle) ?>" />
  <meta property="og:description" content="<?= e($pageDesc) ?>" />
  <meta property="og:type" content="website" />
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
              300: '#d1be90',
              500: '#2b6549',
              600: '#21523a',
              700: '#1a422f',
              800: '#0e291d',
              900: '#02110c',
              950: '#010806'
            },
            cream: {
              50: '#faf7f0',
              100: '#f3ebd6',
              200: '#ebdcb8',
              300: '#dfcb99',
              800: '#4d3f22',
              900: '#2c2413'
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
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-arabic { font-family: 'Amiri', serif; }
    @keyframes pulseSlow {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.08); opacity: 0.9; }
    }
    .pulse-slow { animation: pulseSlow 2.5s infinite ease-in-out; }
  </style>
</head>
<body class="bg-[#faf7f0] text-slate-800 antialiased flex flex-col min-h-screen">

  <!-- Top Announcement Bar (Dark Emerald #02110c) -->
  <div class="bg-[#02110c] text-[#f3ebd6] text-xs border-b border-[#1a422f]/60 py-2.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <div class="flex items-center gap-4 text-[#f3ebd6]/90">
        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" rel="noreferrer" class="inline-flex items-center gap-1.5 transition-colors hover:text-white font-medium">
          <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>WhatsApp: <?= WHATSAPP_DISPLAY ?></span>
        </a>
        <span class="hidden md:inline text-[#f3ebd6]/30">|</span>
        <span class="hidden md:inline text-[#f3ebd6]/80 font-arabic text-sm">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</span>
        <span class="hidden lg:inline text-[#f3ebd6]/30">|</span>
        <span class="hidden lg:inline text-[#f3ebd6]/75 italic text-[11px] tracking-wider">Learn. Recite. Reflect.</span>
      </div>
      <div class="flex items-center gap-4 text-[#f3ebd6]/80">
        <a href="<?= INSTAGRAM_URL ?>" target="_blank" class="hover:text-white transition-colors flex items-center gap-1">
          <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          <span class="hidden sm:inline">Instagram Reel</span>
        </a>
        <a href="<?= WHATSAPP_CHANNEL_URL ?>" target="_blank" class="hover:text-white transition-colors flex items-center gap-1">
          <span>📢 Channel</span>
        </a>
        <a href="<?= baseUrl('/admin/login.php') ?>" class="bg-[#1a422f] hover:bg-[#285f45] px-2.5 py-1 rounded-md text-[11px] font-semibold text-[#f3ebd6] border border-[#f3ebd6]/20 transition-colors">
          Admin Portal
        </a>
      </div>
    </div>
  </div>

  <!-- Main Navigation Bar (Clean Cream / Light Linen) -->
  <header class="sticky top-0 z-40 bg-[#faf7f0]/95 backdrop-blur-md border-b border-[#ebdcb8] shadow-sm transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo Brand Lockup -->
        <a href="<?= baseUrl('/') ?>" class="flex items-center gap-3.5 group">
          <img
            src="<?= baseUrl('/assets/images/logo.png') ?>"
            alt="AL MAQRA (المَقرَأ) - Learn. Recite. Reflect."
            class="h-10 sm:h-12 w-auto object-contain group-hover:scale-105 transition-transform drop-shadow-sm"
          />
          <div class="hidden sm:block">
            <div class="flex items-center gap-2">
              <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#02110c] group-hover:text-[#1a422f] transition-colors">
                AL MAQRA
              </span>
              <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#f3ebd6] text-[#1a422f] font-bold border border-[#ebdcb8] font-arabic">
                المَقرَأ
              </span>
            </div>
            <p class="text-[11px] text-[#1a422f] font-semibold tracking-wider uppercase">Learn. Recite. Reflect.</p>
          </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-[#02110c]">
          <a href="#trial-form" class="hover:text-[#1a422f] transition-colors">Free Trial Class</a>
          <a href="#courses" class="hover:text-[#1a422f] transition-colors">Courses</a>
          <a href="#why-us" class="hover:text-[#1a422f] transition-colors">Why AL MAQRA</a>
          <a href="#expectation" class="hover:text-[#1a422f] transition-colors">What to Expect</a>
          <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="hover:text-[#1a422f] transition-colors flex items-center gap-1 text-[#1a422f] font-bold">
            <span>💬 Live Chat</span>
          </a>
        </nav>

        <!-- CTA Buttons -->
        <div class="flex items-center gap-3">
          <a href="#trial-form" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#1a422f] to-[#02110c] px-5 py-2.5 text-sm font-bold text-[#f3ebd6] shadow-md shadow-[#1a422f]/20 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:to-[#1a422f]">
            <span>Book Free Trial</span>
            <svg class="w-4 h-4 text-[#f3ebd6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow">
    <!-- Hero Banner (Dark Emerald #02110c with Olive Green #1a422f accents) -->
    <div class="relative bg-gradient-to-br from-[#02110c] via-[#092217] to-[#1a422f] text-white py-14 sm:py-20 overflow-hidden border-b border-[#1a422f]/50">
      <!-- Ambient Glow and Watermark -->
      <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -right-16 h-96 w-96 rounded-full bg-[#f3ebd6]/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-10 h-80 w-80 rounded-full bg-[#1a422f]/40 blur-3xl"></div>
        <img src="<?= baseUrl('/assets/images/logo-mark.png') ?>" alt="" class="absolute -right-10 top-1/2 -translate-y-1/2 w-96 h-96 object-contain opacity-[0.06] pointer-events-none select-none" />
      </div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Floating Badge in Cream Beige -->
        <span class="inline-flex items-center gap-2 rounded-full bg-[#f3ebd6] px-4 py-1.5 text-xs sm:text-sm font-bold uppercase tracking-[0.2em] text-[#1a422f] shadow-lg mb-5 border border-[#ebdcb8]">
          <span class="h-2 w-2 rounded-full bg-[#1a422f] animate-pulse"></span>
          100% Free Trial Class • 0 Cost • No Obligation
        </span>

        <!-- Main Title -->
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight max-w-4xl mx-auto text-[#f3ebd6] drop-shadow-md">
          Book Your Free Trial Quran Class
        </h1>

        <!-- Subtitle & Slogan -->
        <p class="mt-3 text-lg sm:text-2xl text-[#f3ebd6]/90 font-medium font-arabic">
          المَقرَأ &bull; <span class="font-sans text-sm sm:text-lg text-emerald-200">Learn. Recite. Reflect.</span>
        </p>

        <p class="mt-4 text-base sm:text-xl text-[#f3ebd6]/85 max-w-2xl mx-auto leading-relaxed">
          Experience our live 1-on-1 personalized lessons with certified male &amp; female Quran teachers worldwide before making any decision.
        </p>

        <!-- Feature Badges -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3 sm:gap-6 text-xs sm:text-sm font-semibold text-[#f3ebd6]">
          <span class="flex items-center gap-1.5 bg-[#f3ebd6]/15 px-3.5 py-1.5 rounded-full border border-[#f3ebd6]/25 backdrop-blur-sm">
            <span class="text-[#f3ebd6]">✓</span> No Credit Card Required
          </span>
          <span class="flex items-center gap-1.5 bg-[#f3ebd6]/15 px-3.5 py-1.5 rounded-full border border-[#f3ebd6]/25 backdrop-blur-sm">
            <span class="text-[#f3ebd6]">✓</span> Male &amp; Female Ustadhs
          </span>
          <span class="flex items-center gap-1.5 bg-[#f3ebd6]/15 px-3.5 py-1.5 rounded-full border border-[#f3ebd6]/25 backdrop-blur-sm">
            <span class="text-[#f3ebd6]">✓</span> Flexible 24/7 Global Timings
          </span>
        </div>
      </div>
    </div>

    <!-- Booking Form & Value Section -->
    <section id="trial-form" class="py-12 sm:py-16 bg-[#faf7f0]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
          
          <!-- Registration Form (Left Column, 7 Cols) -->
          <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl shadow-xl border border-[#ebdcb8] p-6 sm:p-10">
              <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-[#1a422f] bg-[#f3ebd6] px-3.5 py-1.5 rounded-full border border-[#ebdcb8]">
                  Step 1 of 1 • Instant WhatsApp Booking
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#02110c] tracking-tight mt-3.5">
                  Student Registration Details
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                  Please provide your contact information to schedule your live 1-on-1 demo session.
                </p>
              </div>

              <!-- Live Submission Feedback Box -->
              <div id="formFeedback" class="hidden"></div>

              <form id="trialForm" class="space-y-5" method="POST" action="<?= baseUrl('/api/book-trial.php') ?>">
                <input type="hidden" name="gender" id="genderInput" value="Male" />

                <!-- Student Full Name -->
                <div>
                  <label for="firstName" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    Student Full Name *
                  </label>
                  <input
                    id="firstName"
                    type="text"
                    required
                    placeholder="e.g. Abdullah Khan / Fatima Noor"
                    class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 placeholder-gray-400 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all"
                    name="firstName"
                  />
                </div>

                <!-- Age and Gender Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="age" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                      Student Age *
                    </label>
                    <input
                      id="age"
                      type="number"
                      min="3"
                      max="100"
                      required
                      placeholder="e.g. 7 (or adult age)"
                      class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 placeholder-gray-400 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all"
                      name="age"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                      Teacher / Student Gender *
                    </label>
                    <div class="grid grid-cols-2 gap-2 h-12">
                      <button
                        type="button"
                        id="genderMaleBtn"
                        class="flex items-center justify-center gap-1.5 rounded-xl border text-sm font-bold transition-all border-[#1a422f] bg-[#f3ebd6] text-[#1a422f] shadow-sm"
                      >
                        <span>👨 Male</span>
                        <span class="text-xs">✓</span>
                      </button>
                      <button
                        type="button"
                        id="genderFemaleBtn"
                        class="flex items-center justify-center gap-1.5 rounded-xl border text-sm font-bold transition-all border-gray-300 bg-white text-gray-700 hover:bg-[#faf7f0]"
                      >
                        <span>👩 Female</span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Select Course -->
                <div>
                  <label for="course_title" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    Select Quran Course *
                  </label>
                  <select
                    id="course_title"
                    name="course_title"
                    class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all cursor-pointer font-medium"
                  >
                    <?php foreach ($courses as $c): ?>
                      <option value="<?= e($c['title']) ?>"><?= e($c['title']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Country of Residence -->
                <div>
                  <label for="country" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    Country of Residence *
                  </label>
                  <select
                    id="country"
                    name="country"
                    class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all cursor-pointer font-medium"
                  >
                    <option value="India" selected>🇮🇳 India (+91)</option>
                    <option value="United Arab Emirates">🇦🇪 United Arab Emirates (+971)</option>
                    <option value="Saudi Arabia">🇸🇦 Saudi Arabia (+966)</option>
                    <option value="Qatar">🇶🇦 Qatar (+974)</option>
                    <option value="Oman">🇴🇲 Oman (+968)</option>
                    <option value="Kuwait">🇰🇼 Kuwait (+965)</option>
                    <option value="Bahrain">🇧🇭 Bahrain (+973)</option>
                    <option value="United Kingdom">🇬🇧 United Kingdom (+44)</option>
                    <option value="United States">🇺🇸 United States (+1)</option>
                    <option value="Canada">🇨🇦 Canada (+1)</option>
                    <option value="Australia">🇦🇺 Australia (+61)</option>
                    <option value="Malaysia">🇲🇾 Malaysia (+60)</option>
                    <option value="Singapore">🇸🇬 Singapore (+65)</option>
                    <option value="Germany">🇩🇪 Germany (+49)</option>
                    <option value="France">🇫🇷 France (+33)</option>
                    <option value="New Zealand">🇳🇿 New Zealand (+64)</option>
                    <option value="Other / Worldwide">🌍 Other / Worldwide (+)</option>
                  </select>
                </div>

                <!-- WhatsApp / Phone with Auto Dial Code -->
                <div>
                  <label for="phone-input" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    WhatsApp Phone (With Country Code) *
                  </label>
                  <div class="flex rounded-xl overflow-hidden shadow-sm transition-all focus-within:ring-2 focus-within:ring-[#1a422f]/20">
                    <div class="relative flex items-center border-y border-l rounded-l-xl bg-[#f3ebd6]/60 border-gray-300 text-gray-800">
                      <div class="flex items-center gap-1.5 pl-3 pr-7 py-3 text-sm font-bold pointer-events-none select-none text-[#1a422f]">
                        <span id="dialDisplayFlag" class="text-base">🇮🇳</span>
                        <span id="dialDisplayText">+91</span>
                      </div>
                      <select id="dialSelect" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer text-sm font-medium" aria-label="Country Dialing Code">
                        <option value="+91" selected>🇮🇳 India (+91)</option>
                        <option value="+971">🇦🇪 UAE (+971)</option>
                        <option value="+966">🇸🇦 Saudi (+966)</option>
                        <option value="+974">🇶🇦 Qatar (+974)</option>
                        <option value="+968">🇴🇲 Oman (+968)</option>
                        <option value="+965">🇰🇼 Kuwait (+965)</option>
                        <option value="+973">🇧🇭 Bahrain (+973)</option>
                        <option value="+44">🇬🇧 UK (+44)</option>
                        <option value="+1">🇺🇸 USA/Canada (+1)</option>
                        <option value="+61">🇦🇺 Australia (+61)</option>
                        <option value="+60">🇲🇾 Malaysia (+60)</option>
                      </select>
                      <div class="pointer-events-none absolute right-2 text-xs opacity-60 text-[#1a422f]">▼</div>
                    </div>
                    <input
                      id="phone-input"
                      type="tel"
                      required
                      placeholder="e.g. 9497577616"
                      class="flex-1 min-w-0 w-full border-y border-r rounded-r-xl px-4 py-3 text-sm font-medium focus:outline-none transition-all bg-white border-gray-300 text-gray-900 placeholder-gray-400 focus:border-[#1a422f]"
                      name="whatsapp"
                    />
                  </div>
                  <div class="mt-1.5 flex items-center justify-between text-[11px] text-gray-500">
                    <span>Full Number: <strong id="fullNumberPreview" class="text-[#1a422f]">+91</strong></span>
                    <span class="text-[#1a422f] font-semibold">🔒 Used for class scheduling only</span>
                  </div>
                </div>

                <!-- Email Address -->
                <div>
                  <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    Parent / Student Email (Optional)
                  </label>
                  <input
                    id="email"
                    type="email"
                    placeholder="parent@example.com"
                    class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 placeholder-gray-400 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all"
                    name="email"
                  />
                </div>

                <!-- Preferred Timing -->
                <div>
                  <label for="preferredTime" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    Preferred Class Timing (Your Time Zone)
                  </label>
                  <select
                    id="preferredTime"
                    name="preferredTime"
                    class="w-full h-12 rounded-xl border border-gray-300 bg-white px-4 text-sm text-gray-900 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all cursor-pointer font-medium"
                  >
                    <option value="Morning (Before School/Work)">Morning (Before School/Work)</option>
                    <option value="Afternoon (12 PM - 4 PM)">Afternoon (12 PM - 4 PM)</option>
                    <option value="Evening (After School/Work)" selected>Evening (After School/Work)</option>
                    <option value="Night (8 PM - 11 PM)">Night (8 PM - 11 PM)</option>
                    <option value="Weekend Only (Saturday/Sunday)">Weekend Only (Saturday/Sunday)</option>
                    <option value="Flexible / Any Time">Flexible / Any Time</option>
                  </select>
                </div>

                <!-- Notes / Special Requirements -->
                <div>
                  <label for="message" class="block text-xs font-bold uppercase tracking-wider text-[#02110c] mb-1.5">
                    Special Requirements or Notes (Optional)
                  </label>
                  <textarea
                    id="message"
                    name="message"
                    rows="2"
                    placeholder="e.g. Beginner child starting from Alif Baa, needs patient female teacher..."
                    class="w-full rounded-xl border border-gray-300 bg-white p-3 text-sm text-gray-900 placeholder-gray-400 focus:border-[#1a422f] focus:ring-2 focus:ring-[#1a422f]/20 focus:outline-none transition-all resize-none"
                  ></textarea>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  id="submitBtn"
                  class="w-full h-14 py-4 px-8 rounded-2xl bg-gradient-to-r from-[#1a422f] to-[#02110c] text-[#f3ebd6] font-extrabold text-base sm:text-lg hover:from-[#21523a] hover:to-[#092217] shadow-xl hover:shadow-2xl hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 disabled:opacity-60 flex items-center justify-center gap-3 mt-4 group border border-[#f3ebd6]/20"
                >
                  <span>BOOK MY FREE TRIAL CLASS</span>
                  <span class="text-xl group-hover:translate-x-1 transition-transform text-[#f3ebd6]">→</span>
                </button>

                <p class="text-center text-xs text-gray-500 font-medium">
                  ⚡ Instant WhatsApp Confirmation • 100% Free Demo Session • No Obligation
                </p>
              </form>
            </div>
          </div>

          <!-- Right Column (Sidebar & Expectations, 5 Cols) -->
          <div class="lg:col-span-5 space-y-6">
            
            <!-- What to Expect Box -->
            <div id="expectation" class="bg-white rounded-3xl p-6 sm:p-8 border border-[#ebdcb8] shadow-md">
              <h3 class="text-xl font-bold text-[#02110c] mb-4 flex items-center gap-2">
                <span>✨</span><span>What to Expect in Free Trial</span>
              </h3>
              <ul class="space-y-4 text-sm text-gray-700">
                <li class="flex items-start gap-3">
                  <span class="h-6 w-6 rounded-full bg-[#f3ebd6] text-[#1a422f] border border-[#ebdcb8] font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">1</span>
                  <div>
                    <strong class="text-[#02110c] block font-bold">1-on-1 Live Assessment</strong>
                    <span>The Ustadh/Ustadha will assess your child's current Quran recitation level gently.</span>
                  </div>
                </li>
                <li class="flex items-start gap-3">
                  <span class="h-6 w-6 rounded-full bg-[#f3ebd6] text-[#1a422f] border border-[#ebdcb8] font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">2</span>
                  <div>
                    <strong class="text-[#02110c] block font-bold">Interactive Learning Demo</strong>
                    <span>Experience our digital Qaida, live screen-sharing, and Tajweed pronunciation practice.</span>
                  </div>
                </li>
                <li class="flex items-start gap-3">
                  <span class="h-6 w-6 rounded-full bg-[#f3ebd6] text-[#1a422f] border border-[#ebdcb8] font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">3</span>
                  <div>
                    <strong class="text-[#02110c] block font-bold">Customized Study Plan</strong>
                    <span>Receive a tailored learning roadmap matching your availability, goals, and pace.</span>
                  </div>
                </li>
              </ul>
            </div>

            <!-- Testimonial Highlight Card (Dark Emerald #02110c) -->
            <div class="rounded-3xl bg-gradient-to-br from-[#02110c] via-[#092217] to-[#1a422f] p-6 sm:p-8 text-[#f3ebd6] shadow-xl border border-[#ebdcb8]/30">
              <div class="flex items-center gap-1 text-amber-400 mb-3 text-sm">
                ★★★★★ <span class="text-[#f3ebd6]/90 font-bold ml-1 text-xs">5.0 / 5.0 Rating</span>
              </div>
              <p class="text-sm sm:text-base text-[#f3ebd6]/95 italic leading-relaxed mb-4">
                &ldquo;AL MAQRA has been a blessing for my children. The Ustadh is exceptionally patient with my 8-year-old son, correcting his Makharij gently. We could see the improvement in his Tajweed within just a few weeks!&rdquo;
              </p>
              <div class="flex items-center justify-between pt-3 border-t border-[#f3ebd6]/20 text-xs text-[#f3ebd6]/80">
                <span class="font-bold text-[#f3ebd6]">Shameer Ahmed (Parent)</span>
                <span class="bg-white/10 px-2.5 py-1 rounded-full border border-white/15">🇦🇪 Dubai, UAE</span>
              </div>
            </div>

            <!-- WhatsApp Direct Card -->
            <div class="rounded-2xl bg-[#f3ebd6]/60 border border-[#ebdcb8] p-5 text-[#02110c] shadow-sm flex items-center justify-between gap-4">
              <div>
                <h4 class="font-bold text-sm text-[#02110c]">Have Questions First?</h4>
                <p class="text-xs text-[#1a422f] mt-0.5">Chat directly with our academic coordinator on WhatsApp.</p>
              </div>
              <a
                href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= rawurlencode('Assalamu Alaikum AL MAQRA Team, I have an enquiry about online Quran classes.') ?>"
                target="_blank"
                rel="noreferrer"
                class="bg-[#25D366] hover:bg-[#20ba59] text-white px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap shadow-md transition-transform hover:scale-105 flex items-center gap-1.5 flex-shrink-0"
              >
                <span>💬 WhatsApp Us</span>
              </a>
            </div>

            <!-- Why Parents Love Us -->
            <div class="bg-white rounded-3xl p-6 border border-[#ebdcb8] shadow-sm space-y-3">
              <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Global Benefits</h4>
              <div class="grid grid-cols-2 gap-3 text-xs font-semibold text-gray-700">
                <div class="flex items-center gap-2 p-2 rounded-lg bg-[#faf7f0]">
                  <span class="text-[#1a422f] font-bold">✓</span> 1-on-1 Classes
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-[#faf7f0]">
                  <span class="text-[#1a422f] font-bold">✓</span> Female Teachers
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-[#faf7f0]">
                  <span class="text-[#1a422f] font-bold">✓</span> Flexible Hours
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-[#faf7f0]">
                  <span class="text-[#1a422f] font-bold">✓</span> Malayalam &amp; English
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="py-16 bg-white border-t border-[#ebdcb8]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
          <span class="text-xs font-bold uppercase tracking-wider text-[#1a422f] bg-[#f3ebd6] px-3.5 py-1.5 rounded-full border border-[#ebdcb8]">
            Curriculum &amp; Courses
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-[#02110c] tracking-tight mt-3.5">
            Our Structured Quran Courses
          </h2>
          <p class="text-gray-600 mt-2 text-sm sm:text-base">
            Designed for beginners, children, sisters, and adults wanting to perfect their recitation and understanding.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php foreach ($courses as $course): ?>
            <div class="rounded-3xl border border-[#ebdcb8] bg-[#faf7f0]/60 p-6 sm:p-8 hover:shadow-xl hover:border-[#1a422f] transition-all duration-300 flex flex-col justify-between group">
              <div>
                <div class="flex items-center justify-between mb-4">
                  <span class="text-xs font-bold text-[#1a422f] bg-[#f3ebd6] px-2.5 py-1 rounded-lg border border-[#ebdcb8]">
                    <?= e($course['category'] ?? 'Quran') ?>
                  </span>
                  <span class="text-xs font-semibold text-gray-500">
                    ⏱ <?= e($course['duration'] ?? 'Flexible') ?>
                  </span>
                </div>
                <h3 class="text-xl font-bold text-[#02110c] group-hover:text-[#1a422f] transition-colors">
                  <?= e($course['title']) ?>
                </h3>
                <p class="text-xs font-semibold text-amber-700 mt-1">
                  Level: <?= e($course['level'] ?? 'All Levels') ?>
                </p>
                <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                  <?= e($course['description']) ?>
                </p>
              </div>

              <div class="mt-6 pt-4 border-t border-[#ebdcb8] flex items-center justify-between">
                <span class="text-xs font-bold text-[#1a422f]">Free Trial Included</span>
                <a
                  href="#trial-form"
                  class="inline-flex items-center gap-1 text-sm font-bold text-[#1a422f] hover:text-[#02110c] transition-colors"
                >
                  <span>Book Demo</span>
                  <span>→</span>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Why Choose AL MAQRA Section -->
    <section id="why-us" class="py-16 bg-[#faf7f0] border-t border-[#ebdcb8]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14">
          <span class="text-xs font-bold uppercase tracking-wider text-[#1a422f] bg-[#f3ebd6] px-3.5 py-1.5 rounded-full border border-[#ebdcb8]">
            Why Choose Us
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-[#02110c] tracking-tight mt-3.5">
            Why AL MAQRA is the Right Choice for Your Family
          </h2>
          <p class="text-gray-600 mt-2 text-sm sm:text-base">
            We provide structured Islamic education with compassionate teachers, personal tracking, and complete convenience from home.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white rounded-2xl p-6 border border-[#ebdcb8] shadow-sm text-center hover:shadow-md transition-shadow">
            <div class="w-12 h-12 mx-auto rounded-xl bg-[#f3ebd6] text-[#1a422f] flex items-center justify-center text-2xl mb-4 border border-[#ebdcb8]">
              👨‍🏫
            </div>
            <h4 class="font-bold text-[#02110c] text-base mb-1">Qualified Ustadhs</h4>
            <p class="text-xs text-gray-600 leading-relaxed">Certified Huffaz and scholars with Sanad and deep expertise in Tajweed &amp; Makharij.</p>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-[#ebdcb8] shadow-sm text-center hover:shadow-md transition-shadow">
            <div class="w-12 h-12 mx-auto rounded-xl bg-[#f3ebd6] text-[#1a422f] flex items-center justify-center text-2xl mb-4 border border-[#ebdcb8]">
              🌍
            </div>
            <h4 class="font-bold text-[#02110c] text-base mb-1">Global Timings</h4>
            <p class="text-xs text-gray-600 leading-relaxed">Convenient class schedules tailored to your time zone across UAE, Saudi, UK, and worldwide.</p>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-[#ebdcb8] shadow-sm text-center hover:shadow-md transition-shadow">
            <div class="w-12 h-12 mx-auto rounded-xl bg-[#f3ebd6] text-[#1a422f] flex items-center justify-center text-2xl mb-4 border border-[#ebdcb8]">
              🎯
            </div>
            <h4 class="font-bold text-[#02110c] text-base mb-1">1-on-1 Focus</h4>
            <p class="text-xs text-gray-600 leading-relaxed">Private sessions allowing each student to learn at their own pace without hesitation.</p>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-[#ebdcb8] shadow-sm text-center hover:shadow-md transition-shadow">
            <div class="w-12 h-12 mx-auto rounded-xl bg-[#f3ebd6] text-[#1a422f] flex items-center justify-center text-2xl mb-4 border border-[#ebdcb8]">
              📱
            </div>
            <h4 class="font-bold text-[#02110c] text-base mb-1">Direct WhatsApp Support</h4>
            <p class="text-xs text-gray-600 leading-relaxed">Instant communication with teachers and coordinator for reminders, schedule changes &amp; feedback.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Worldwide Students Section (Dark Emerald #02110c) -->
    <div class="bg-[#02110c] py-10 text-white text-center border-t border-[#1a422f]/60">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-[#f3ebd6]">
          Trusted by Students Worldwide &bull; Learn. Recite. Reflect.
        </h3>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-3 sm:gap-4 text-xs sm:text-sm font-medium">
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇮🇳</span> <span>India</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇦🇪</span> <span>UAE</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇸🇦</span> <span>Saudi Arabia</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇶🇦</span> <span>Qatar</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇰🇼</span> <span>Kuwait</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇴🇲</span> <span>Oman</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇬🇧</span> <span>United Kingdom</span>
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-[#f3ebd6]/20 bg-[#f3ebd6]/10 px-4 py-2 text-[#f3ebd6]">
            <span>🇺🇸</span> <span>United States</span>
          </span>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer (Dark Emerald #02110c with Cream Beige #f3ebd6) -->
  <footer class="bg-[#02110c] text-[#f3ebd6] border-t border-[#1a422f] py-12 text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div class="md:col-span-2">
          <div class="flex items-center gap-3 mb-3">
            <img src="<?= baseUrl('/assets/images/logo.png') ?>" alt="AL MAQRA (المَقرَأ)" class="h-10 w-auto object-contain drop-shadow-sm" />
            <div>
              <span class="text-xl font-bold tracking-tight text-[#f3ebd6]">AL MAQRA <span class="font-arabic text-sm ml-1 text-emerald-300">المَقرَأ</span></span>
              <p class="text-[11px] text-[#f3ebd6]/75 uppercase tracking-wider font-semibold">Learn. Recite. Reflect.</p>
            </div>
          </div>
          <p class="text-[#f3ebd6]/75 text-xs sm:text-sm max-w-sm leading-relaxed">
            A global Qur’anic learning platform and online Madrasa helping students worldwide recite the Holy Qur’an correctly with Tajweed.
          </p>
          <div class="mt-4 flex items-center gap-3">
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="text-emerald-400 hover:text-emerald-300 text-xs font-bold flex items-center gap-1.5">
              <span>💬 <?= WHATSAPP_DISPLAY ?></span>
            </a>
          </div>
        </div>

        <div>
          <h4 class="font-bold text-xs uppercase tracking-wider text-[#f3ebd6]/90 mb-3">Courses</h4>
          <ul class="space-y-2 text-xs text-[#f3ebd6]/70">
            <li>Noorani Qaida for Beginners</li>
            <li>Quran Reading with Tajweed</li>
            <li>Tajweed Mastery &amp; Makharij</li>
            <li>Online Quran Hifz Program</li>
            <li>Islamic Studies &amp; Deeniyath</li>
          </ul>
        </div>

        <div>
          <h4 class="font-bold text-xs uppercase tracking-wider text-[#f3ebd6]/90 mb-3">Quick Links</h4>
          <ul class="space-y-2 text-xs text-[#f3ebd6]/70">
            <li><a href="#trial-form" class="hover:text-white transition-colors">Book Free Trial</a></li>
            <li><a href="<?= WHATSAPP_CHANNEL_URL ?>" target="_blank" class="hover:text-white transition-colors">WhatsApp Channel</a></li>
            <li><a href="<?= INSTAGRAM_URL ?>" target="_blank" class="hover:text-white transition-colors">Instagram Video</a></li>
            <li><a href="<?= baseUrl('/admin/login.php') ?>" class="hover:text-white transition-colors">Admin Login</a></li>
          </ul>
        </div>
      </div>

      <div class="pt-6 border-t border-[#1a422f] flex flex-col sm:flex-row items-center justify-between text-xs text-[#f3ebd6]/60">
        <p>&copy; <?= date('Y') ?> AL MAQRA (المَقرَأ). All rights reserved. &bull; Learn. Recite. Reflect.</p>
        <p class="mt-2 sm:mt-0">Contact: <?= WHATSAPP_DISPLAY ?></p>
      </div>
    </div>
  </footer>

  <!-- Floating Sticky WhatsApp CTA Widget -->
  <a
    href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= rawurlencode('Assalamu Alaikum AL MAQRA Team, I am interested in joining a free trial Quran class.') ?>"
    target="_blank"
    rel="noreferrer"
    class="fixed right-5 bottom-6 z-50 flex items-center gap-2 group transition-transform hover:-translate-y-1"
    aria-label="Chat on WhatsApp"
  >
    <span class="hidden sm:inline-block bg-[#02110c] text-[#f3ebd6] text-xs font-bold py-1.5 px-3.5 rounded-full shadow-xl border border-[#1a422f] group-hover:bg-[#1a422f] transition-colors">
      Need Help? Chat on WhatsApp
    </span>
    <div class="h-14 w-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-2xl ring-4 ring-[#02110c] pulse-slow">
      <svg class="h-7 w-7 fill-current" viewBox="0 0 24 24">
        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
      </svg>
    </div>
  </a>

  <!-- Country & Form Logic Script -->
  <script src="<?= baseUrl('/assets/js/countries.js') ?>"></script>
</body>
</html>