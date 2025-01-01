// Variabel boolean untuk mengaktifkan atau menonaktifkan penyembunyian console
const hideLogs = true;

/**
 * Fungsi untuk menyembunyikan atau menampilkan pesan dari metode console tertentu.
 * 
 * @param {boolean} enable - Parameter boolean yang menentukan apakah metode console akan disembunyikan.
 *                             Jika true, metode console akan dinonaktifkan. Jika false, metode console akan berfungsi normal.
 */
function hideConsole(enable) {
    if (enable) {
        // Mengganti fungsi console dengan fungsi kosong untuk menyembunyikannya
        console.log = function() {};
        console.error = function() {};
        console.warn = function() {}; 
    }
}

// Memanggil fungsi hideConsole dengan nilai variabel hideLogs
hideConsole(hideLogs);