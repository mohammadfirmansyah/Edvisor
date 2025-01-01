/**
 * responsive.js
 */

/**
 * Menampilkan kembali body setelah scaling selesai.
 */
function showBody() {
  document.body.style.display = 'block'; // Menampilkan body
}

/**
* Mengecek apakah perangkat adalah perangkat mobile.
* @returns {boolean} True jika perangkat mobile, false jika tidak.
*/
function isMobileDevice() {
  return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

/**
* Menambahkan atau memastikan adanya meta viewport yang sesuai.
*/
function setMetaViewport() {
  let meta = document.querySelector('meta[name="viewport"]');
  if (!meta) {
      meta = document.createElement('meta');
      meta.name = 'viewport';
      document.head.appendChild(meta);
  }
  meta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
}

/**
* Melakukan scaling pada body berdasarkan ukuran layar untuk memastikan tampilan yang responsif tanpa efek zoom.
*/
function scaleBody() {
  const screenWidth = window.innerWidth;
  const screenHeight = window.innerHeight;
  const targetWidth = 1440;
  const targetHeight = 1024;
  const targetAspectRatio = 45 / 32; // Aspek rasio yang diinginkan

  let scaleX = screenWidth / targetWidth;
  let scaleY = screenHeight / targetHeight;
  let scale;

  // Menghitung scale yang dibutuhkan untuk memenuhi aspek rasio
  if (screenWidth / screenHeight > targetAspectRatio) {
      // Layar lebih lebar dari aspek rasio target, scale berdasarkan LEBAR
      scale = scaleX;
  } else {
      // Layar lebih tinggi dari aspek rasio target, scale berdasarkan TINGGI
      scale = scaleY;
  }

  // Scale body
  document.body.style.transform = `scale(${scale})`;
  document.body.style.transformOrigin = 'top left';
  document.body.style.width = `${targetWidth}px`;
  document.body.style.height = `${targetHeight}px`;
  document.body.style.position = 'absolute';

  // Menentukan overflow
  if (screenWidth / screenHeight > targetAspectRatio) {
      // Jika lebar layar lebih besar proporsional, aktifkan overflow-y
      document.body.style.overflowX = 'hidden';
      document.body.style.overflowY = 'auto';
  } else {
      // Jika tinggi layar lebih besar proporsional, aktifkan overflow-x
      document.body.style.overflowX = 'auto';
      document.body.style.overflowY = 'hidden';
  }

  // Mengatur background dan memastikan html mengambil penuh viewport tanpa mengatur ulang width dan height
  document.documentElement.style.background = 'transparent';
  document.documentElement.style.width = '100%';
  document.documentElement.style.height = '100%';

  // Menggunakan requestAnimationFrame untuk memastikan scaling diterapkan sebelum menampilkan body
  requestAnimationFrame(() => {
      showBody();
  });
}

/**
* Secara otomatis menggulir ke tengah halaman secara horizontal dan vertikal pada perangkat mobile.
*/
function autoScrollToMiddle(delay) {
  // Menggunakan timeout untuk memastikan bahwa halaman sudah dirender sepenuhnya sebelum menggulir
  setTimeout(() => {
      // Mendapatkan lebar dan tinggi konten serta viewport
      const scrollWidth = document.documentElement.scrollWidth;
      const windowWidth = window.innerWidth;
      const scrollHeight = document.documentElement.scrollHeight;
      const windowHeight = window.innerHeight;

      // Menghitung posisi scroll horizontal dan vertikal ke tengah
      const scrollToX = (scrollWidth - windowWidth) / 2;
      const scrollToY = (scrollHeight - windowHeight) / 2;

      // Memastikan bahwa nilai scroll tidak negatif
      const finalScrollToX = scrollToX > 0 ? scrollToX : 0;
      const finalScrollToY = scrollToY > 0 ? scrollToY : 0;

      // Menggulir ke posisi tengah secara horizontal dan vertikal
      window.scrollTo({
          left: finalScrollToX,
          top: finalScrollToY,
          behavior: 'smooth' // Menggunakan scroll halus
      });
  }, delay); // Meningkatkan delay untuk memastikan scaling telah diterapkan
}

/**
* Flag untuk menandakan apakah keyboard sedang terlihat.
*/
let isKeyboardVisible = false;

/**
* Flag untuk mengabaikan event resize yang disebabkan oleh penutupan keyboard.
*/
let ignoreResize = false;

/**
* Menyimpan nilai scrollX dan scrollY sebelum keyboard muncul.
*/
let previousScrollX = 0;
let previousScrollY = 0;
let previousInnerWidth = 0;
let previousInnerHeight = 0;

/**
* Memantau focus dan blur pada elemen input untuk mendeteksi status keyboard.
*/
function monitorKeyboard() {
  // Mendapatkan semua elemen input, textarea, dan contenteditable
  const inputs = document.querySelectorAll('input, textarea, [contenteditable="true"]');

  inputs.forEach(input => {
      input.addEventListener('focus', () => {
          isKeyboardVisible = true;
          // Simpan posisi scroll sebelum keyboard muncul
          previousScrollX = window.scrollX;
          previousScrollY = window.scrollY;
          previousInnerWidth = window.innerWidth;
          previousInnerHeight = window.innerHeight;
      });

      input.addEventListener('blur', () => {
          isKeyboardVisible = false;
          // Set ignoreResize untuk mencegah autoScrollToMiddle akibat penutupan keyboard
          ignoreResize = true;
          setTimeout(() => {
              ignoreResize = false;
          }, 0); // Waktu untuk mengabaikan resize setelah keyboard ditutup
      });
  });
}

/**
* Fungsi utama untuk mengatur tampilan responsif.
*/
function initializeResponsiveDesign() {
  setMetaViewport();    // Mengatur meta viewport
  scaleBody();          // Melakukan scaling

  // Menggulir ke tengah halaman secara horizontal jika perangkat mobile dan keyboard tidak terlihat
  if (isMobileDevice() && !isKeyboardVisible) {
      autoScrollToMiddle(500);
  }
}

/**
* Menambahkan event listener untuk menangani perubahan ukuran jendela dan orientasi.
*/
window.addEventListener('resize', function () {
  initializeResponsiveDesign();

  // Hanya panggil autoScrollToMiddle jika keyboard tidak terlihat dan resize bukan karena keyboard
  if (!isKeyboardVisible && !ignoreResize && isMobileDevice()) {
      autoScrollToMiddle(500);
  }
});

/**
* Menambahkan event listener untuk menangani saat halaman selesai dimuat.
*/
window.addEventListener('DOMContentLoaded', function () {
  initializeResponsiveDesign();
  monitorKeyboard(); // Mulai memantau keyboard setelah DOM siap
});

/**
* Menambahkan event listener tambahan untuk menangani saat semua konten telah dimuat.
* Ini memastikan bahwa autoScrollToMiddle dipanggil lagi setelah semua sumber daya (seperti gambar) telah dimuat.
*/
window.addEventListener('load', function () {
  initializeResponsiveDesign();
});

/**
* Memastikan scaling diterapkan secepat mungkin saat skrip dijalankan
*/
initializeResponsiveDesign();