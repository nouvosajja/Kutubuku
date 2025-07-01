const daysTag = document.querySelector(".days"),
      currentDate = document.querySelector(".current-date"),
      prevNextIcon = document.querySelectorAll(".icons span");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ["January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"];

const priorityColors = {
  "Penting, Mendesak": "#a52a2a",
  "Penting, Tidak Mendesak": "#FF9100",
  "Tidak Penting, Mendesak": "#e3e369",
  "Tidak Penting, Tidak Mendesak": "#6CA64C"
};

function renderCalendar() {
  const firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();

  let liTag = "";

  // Hari dari bulan sebelumnya
  for (let i = firstDayofMonth; i > 0; i--) {
    liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
  }

  // Hari bulan ini
  for (let i = 1; i <= lastDateofMonth; i++) {
    let isToday = i === date.getDate() && currMonth === new Date().getMonth() &&
                  currYear === new Date().getFullYear() ? "active" : "";

    const fullDate = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

    let bgStyle = "";
    if (tasksByDate[fullDate] && tasksByDate[fullDate].length > 0) {
      const taskCategory = tasksByDate[fullDate][0].kategori;
      const color = priorityColors[taskCategory] || "#6CA64C";
      bgStyle = `style="background-color: ${color}; color: white; border-radius: 50%;"`;
    }

liTag += `<li class="${isToday}" data-full-date="${fullDate}" ${bgStyle}>${i}</li>`;
  }

  // Hari dari bulan berikutnya
  for (let i = lastDayofMonth; i < 6; i++) {
    liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
  }

  currentDate.innerText = `${months[currMonth]} ${currYear}`;
  daysTag.innerHTML = liTag;
}

// Fungsi untuk menampilkan tugas berikutnya
function showNextUpcomingTask() {
  const priorityBox = document.querySelector(".rounded-box-priority");
  if (!priorityBox) return;

  const today = new Date().toISOString().split("T")[0];
  const upcomingDates = Object.keys(tasksByDate)
    .filter(date => date >= today && tasksByDate[date].length > 0)
    .sort();

  if (upcomingDates.length === 0) {
    priorityBox.innerHTML = "<h5 class='fw-bold'>Tidak ada tugas berikutnya</h5>";
    return;
  }

  const nextDate = upcomingDates[0];
  const tasks = tasksByDate[nextDate];



}

// Tombol next/prev bulan
prevNextIcon.forEach(icon => {
  icon.addEventListener("click", () => {
    currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

    if (currMonth < 0) {
      currMonth = 11;
      currYear -= 1;
    } else if (currMonth > 11) {
      currMonth = 0;
      currYear += 1;
    }

    renderCalendar();
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("toggleCalendar");
  const calendarWrapper = document.getElementById("calendarWrapper");

  toggle.addEventListener("click", () => {
    toggle.classList.toggle("rotate");
    if (calendarWrapper.style.display === "none") {
      calendarWrapper.style.display = "block";
      calendarWrapper.style.animation = "fadeIn 0.3s ease";
    } else {
      calendarWrapper.style.display = "none";
    }
  });

  renderCalendar();
  showNextUpcomingTask(); // Langsung tampilkan tugas terdekat
});
