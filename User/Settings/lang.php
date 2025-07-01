<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Bahasa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="lang.css">
</head>
<body>

  
    
    <div class="top-left-title">
  <i class="fas fa-language fa-lg"></i>
  <span class="fw-bold fs-5 translate" data-key="language">Bahasa</span>
</div>

<a href="javascript:history.back()" class="btn-close top-right-close" aria-label="Close"></a>
<div class="lang-container">

    <div class="lang-option active" data-lang="id">
      <span class="fw-bold">Indonesia</span>
      <i class="fas fa-check checkmark" id="check-id"></i>
    </div>

    <div class="lang-option" data-lang="en">
      <span class="fw-bold">English</span>
      <i class="fas fa-check checkmark hidden" id="check-en"></i>
    </div>

    <button class="btn-ganti translate" data-key="change" onclick="saveLanguage()">Ganti</button>
  </div>

  <script>
    const options = document.querySelectorAll('.lang-option');
    let selectedLang = localStorage.getItem('language') || 'id';

    const updateUI = () => {
      options.forEach(opt => {
        const lang = opt.dataset.lang;
        const check = document.getElementById(`check-${lang}`);
        if (lang === selectedLang) {
          opt.classList.add('active');
          check.classList.remove('hidden');
        } else {
          opt.classList.remove('active');
          check.classList.add('hidden');
        }
      });
    };

    options.forEach(opt => {
      opt.addEventListener('click', () => {
        selectedLang = opt.dataset.lang;
        updateUI();
      });
    });

    const saveLanguage = () => {
      localStorage.setItem('language', selectedLang);
      alert("Bahasa telah diganti ke: " + (selectedLang === 'id' ? "Indonesia" : "English"));
      window.location.href = "setting.php";
    };

    updateUI();
  </script>

<script src="lang.js"></script>
</body>
</html>
