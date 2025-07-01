const fileInput = document.getElementById('file-upload');
  const preview = document.getElementById('preview');

  fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.setAttribute('src', e.target.result);
        preview.style.display = 'block';
      }

      reader.readAsDataURL(file);
    }
  });