const translations = {
  id: {
    todayList: "Daftar Hari Ini",
    profile: "Profil",
    language: "Bahasa",
    add: "Tambah",
    category: "Kategori",
    priority: "Prioritas",
    setting: "Pengaturan",
    enterActivity: "Masukkan nama kegiatan",
    importantUrgent: "Penting, Mendesak",
    notImportantNotUrgent: "Tidak Penting, Tidak Mendesak"
  },
  en: {
    todayList: "Today's List",
    profile: "Profile",
    language: "Language",
    add: "Add",
    category: "Category",
    priority: "Priority",
    setting: "Setting",
    enterActivity: "Enter activity name",
    importantUrgent: "Important, Urgent",
    notImportantNotUrgent: "Not Important, Not Urgent"
  }
};

function setLanguage(lang) {
  localStorage.setItem("language", lang);
  const elements = document.querySelectorAll(".translate");
  elements.forEach(el => {
    const key = el.getAttribute("data-key");
    if (translations[lang] && translations[lang][key]) {
      el.textContent = translations[lang][key];
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const lang = localStorage.getItem("language") || "id";
  setLanguage(lang);

  const switcher = document.getElementById("languageSwitcher");
  if (switcher) {
    switcher.value = lang;
    switcher.addEventListener("change", (e) => {
      setLanguage(e.target.value);
    });
  }
});
