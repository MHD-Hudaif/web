/**
 * AL MAQRA (المقرأ) - Country Dial Codes & Flag Data
 */
const COUNTRIES_DATA = [
  { name: "India", code: "IN", dial: "+91", flag: "🇮🇳" },
  { name: "United Arab Emirates", code: "AE", dial: "+971", flag: "🇦🇪" },
  { name: "Saudi Arabia", code: "SA", dial: "+966", flag: "🇸🇦" },
  { name: "Qatar", code: "QA", dial: "+974", flag: "🇶🇦" },
  { name: "Oman", code: "OM", dial: "+968", flag: "🇴🇲" },
  { name: "Kuwait", code: "KW", dial: "+965", flag: "🇰🇼" },
  { name: "Bahrain", code: "BH", dial: "+973", flag: "🇧🇭" },
  { name: "United Kingdom", code: "GB", dial: "+44", flag: "🇬🇧" },
  { name: "United States", code: "US", dial: "+1", flag: "🇺🇸" },
  { name: "Canada", code: "CA", dial: "+1", flag: "🇨🇦" },
  { name: "Australia", code: "AU", dial: "+61", flag: "🇦🇺" },
  { name: "Malaysia", code: "MY", dial: "+60", flag: "🇲🇾" },
  { name: "Singapore", code: "SG", dial: "+65", flag: "🇸🇬" },
  { name: "Germany", code: "DE", dial: "+49", flag: "🇩🇪" },
  { name: "France", code: "FR", dial: "+33", flag: "🇫🇷" },
  { name: "New Zealand", code: "NZ", dial: "+64", flag: "🇳🇿" },
  { name: "Norway", code: "NO", dial: "+47", flag: "🇳🇴" },
  { name: "Sweden", code: "SE", dial: "+46", flag: "🇸🇪" },
  { name: "Netherlands", code: "NL", dial: "+31", flag: "🇳🇱" },
  { name: "Ireland", code: "IE", dial: "+353", flag: "🇮🇪" },
  { name: "Italy", code: "IT", dial: "+39", flag: "🇮🇹" },
  { name: "Spain", code: "ES", dial: "+34", flag: "🇪🇸" },
  { name: "Switzerland", code: "CH", dial: "+41", flag: "🇨🇭" },
  { name: "South Africa", code: "ZA", dial: "+27", flag: "🇿🇦" },
  { name: "Pakistan", code: "PK", dial: "+92", flag: "🇵🇰" },
  { name: "Bangladesh", code: "BD", dial: "+880", flag: "🇧🇩" },
  { name: "Egypt", code: "EG", dial: "+20", flag: "🇪🇬" },
  { name: "Turkey", code: "TR", dial: "+90", flag: "🇹🇷" },
  { name: "Other / Worldwide", code: "XX", dial: "+", flag: "🌍" }
];

document.addEventListener("DOMContentLoaded", function () {
  const countrySelect = document.getElementById("country");
  const dialSelect = document.getElementById("dialSelect");
  const dialDisplayFlag = document.getElementById("dialDisplayFlag");
  const dialDisplayText = document.getElementById("dialDisplayText");
  const fullNumberPreview = document.getElementById("fullNumberPreview");
  const phoneInput = document.getElementById("phone-input");

  if (!countrySelect) return;

  function updateDialCode(dialCode, countryName) {
    if (dialDisplayText) dialDisplayText.textContent = dialCode;
    const country = COUNTRIES_DATA.find(c => c.dial === dialCode || c.name === countryName);
    if (country && dialDisplayFlag) {
      dialDisplayFlag.textContent = country.flag;
    }
    updateFullNumber();
  }

  function updateFullNumber() {
    if (!fullNumberPreview || !phoneInput) return;
    const dial = dialDisplayText ? dialDisplayText.textContent.trim() : "+91";
    const phone = phoneInput.value.replace(/[^0-9]/g, "");
    fullNumberPreview.textContent = dial + (phone ? " " + phone : "");
  }

  countrySelect.addEventListener("change", function () {
    const selected = COUNTRIES_DATA.find(c => c.name === this.value);
    if (selected) {
      if (dialSelect) dialSelect.value = selected.dial;
      updateDialCode(selected.dial, selected.name);
    }
  });

  if (dialSelect) {
    dialSelect.addEventListener("change", function () {
      const selected = COUNTRIES_DATA.find(c => c.dial === this.value);
      updateDialCode(this.value, selected ? selected.name : "");
      if (selected && countrySelect) {
        countrySelect.value = selected.name;
      }
    });
  }

  if (phoneInput) {
    phoneInput.addEventListener("input", updateFullNumber);
  }

  // Gender selection toggle buttons
  const genderMaleBtn = document.getElementById("genderMaleBtn");
  const genderFemaleBtn = document.getElementById("genderFemaleBtn");
  const genderInput = document.getElementById("genderInput");

  if (genderMaleBtn && genderFemaleBtn && genderInput) {
    genderMaleBtn.addEventListener("click", function () {
      genderInput.value = "Male";
      genderMaleBtn.className = "flex items-center justify-center gap-1.5 rounded-xl border text-sm font-bold transition-all border-[#0F766E] bg-teal-50/90 text-[#0F766E] shadow-sm";
      genderFemaleBtn.className = "flex items-center justify-center gap-1.5 rounded-xl border text-sm font-bold transition-all border-gray-300 bg-white text-gray-700 hover:bg-gray-50";
    });

    genderFemaleBtn.addEventListener("click", function () {
      genderInput.value = "Female";
      genderFemaleBtn.className = "flex items-center justify-center gap-1.5 rounded-xl border text-sm font-bold transition-all border-[#0F766E] bg-teal-50/90 text-[#0F766E] shadow-sm";
      genderMaleBtn.className = "flex items-center justify-center gap-1.5 rounded-xl border text-sm font-bold transition-all border-gray-300 bg-white text-gray-700 hover:bg-gray-50";
    });
  }

  // Form submission handler (AJAX with WhatsApp Redirect)
  const trialForm = document.getElementById("trialForm");
  const formFeedback = document.getElementById("formFeedback");
  const submitBtn = document.getElementById("submitBtn");

  if (trialForm) {
    trialForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      if (!phoneInput.value.trim()) {
        alert("Please enter your WhatsApp phone number.");
        phoneInput.focus();
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
          Processing & Opening WhatsApp...
        `;
      }

      const formData = new FormData(trialForm);
      // Ensure dial code is explicitly passed
      if (dialDisplayText) {
        formData.append("dialCode", dialDisplayText.textContent.trim());
      }

      try {
        const apiUrl = trialForm.getAttribute('action') || (window.location.pathname.startsWith('/al-maqra/web') ? '/al-maqra/web/api/book-trial.php' : (window.location.pathname.startsWith('/al-maqra') ? '/al-maqra/api/book-trial.php' : '/api/book-trial.php'));
        const response = await fetch(apiUrl, {
          method: "POST",
          body: formData
        });
        const result = await response.json();

        if (result.success) {
          if (formFeedback) {
            formFeedback.className = "p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-sm font-medium mb-4 block";
            formFeedback.innerHTML = `
              <div class="flex items-center gap-2 font-bold text-base text-emerald-950 mb-1">
                <span>✓</span> Registration Successful!
              </div>
              <p>Your details have been saved. Opening WhatsApp to connect directly with our academic team...</p>
            `;
          }
          // Redirect to WhatsApp deep link
          setTimeout(() => {
            window.location.href = result.whatsapp_url;
          }, 800);
        } else {
          throw new Error(result.error || "An error occurred during submission.");
        }
      } catch (err) {
        if (formFeedback) {
          formFeedback.className = "p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 text-sm font-medium mb-4 block";
          formFeedback.innerHTML = `<strong>Error:</strong> ${err.message}`;
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = `<span>BOOK MY FREE TRIAL CLASS</span><span class="text-xl">→</span>`;
        }
      }
    });
  }
});
