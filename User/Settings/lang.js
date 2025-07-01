const translations = {
  id: {
    todayList: "Daftar Hari Ini",
    profile: "Profil",
    language: "Bahasa",
    add: "Tambah",
    tasks: "tugas",
    change: "Ganti",
    category: "Kategori",
    priority: "Prioritas",
    setting: "Pengaturan",
    enterActivity: "Masukkan nama kegiatan",
    "Penting, Mendesak": "Penting, Mendesak",
    "Penting, Tidak Mendesak": "Penting, Tidak Mendesak",
    "Tidak Penting, Mendesak": "Tidak Penting, Mendesak",
    "Tidak Penting, Tidak Mendesak": "Tidak Penting, Tidak Mendesak",
      selectCategory: "Pilih kategori",
  time: "Waktu",
  submit: "Masukkan",
    whatsNext: "Kegiatan Selanjutnya",
  noTaskToday: "Tidak ada kegiatan hari ini.",
  noUpcomingTasks: "Tidak ada kegiatan mendatang.",
  editTask: "Ubah Kegiatan",
  saveChanges: "Simpan Perubahan",
  selectCategory: "Pilih kategori",
  submit: "Masukkan",
  time: "Waktu",
  "newPassword": "Password Baru",
      "submitChange": "Ganti",
      "choosePhoto": "Pilih Foto",
      "leaveBlank": "Kosongkan jika tidak ingin mengubah",
            "name": "Nama",

  },
  en: {
    todayList: "Today's List",
    profile: "Profile",
    language: "Language",
    add: "Add",
    tasks: "tasks",
    change: "Change",
    category: "Category",
    priority: "Priority",
    setting: "Setting",
    enterActivity: "Enter activity name",
    "Penting, Mendesak": "Important, Urgent",
    "Penting, Tidak Mendesak": "Important, Not Urgent",
    "Tidak Penting, Mendesak": "Not Important, Urgent",
    "Tidak Penting, Tidak Mendesak": "Not Important, Not Urgent",
      selectCategory: "Select category",
  time: "Time",
  submit: "Submit",
    whatsNext: "What's Next",
  noTaskToday: "No tasks today.",
  noUpcomingTasks: "No upcoming tasks.",
  editTask: "Edit Task",
  saveChanges: "Save Changes",
  selectCategory: "Select category",
  submit: "Submit",
  time: "Time",
        "newPassword": "New Password",
      "submitChange": "Submit Changes",
      "choosePhoto": "Choose Photo",
      "leaveBlank": "Leave blank if you don't want to change",
      "name": "Name",
    
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

    // Ubah placeholder jika ada
    if (el.hasAttribute('data-placeholder')) {
      const placeholderKey = el.getAttribute('data-placeholder');
      if (translations[lang][placeholderKey]) {
        el.setAttribute('placeholder', translations[lang][placeholderKey]);
      }
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
