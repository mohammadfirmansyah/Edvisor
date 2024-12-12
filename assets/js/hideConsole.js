// Variabel boolean untuk mengaktifkan atau menonaktifkan penyembunyian console.log
const hideLogs = true;

/**
 * Fungsi untuk menyembunyikan atau menampilkan pesan console.log
 * 
 * @param {boolean} enable - Parameter boolean yang menentukan apakah console.log akan disembunyikan.
 *                             Jika true, console.log akan dinonaktifkan. Jika false, console.log akan berfungsi normal.
 */
function hideConsoleLog(enable) {
    if (enable) {
        // Mengganti fungsi console.log dengan fungsi kosong untuk menyembunyikannya
        console.log = function() {};
    }
}

// Memanggil fungsi hideConsoleLog dengan nilai variabel hideLogs
hideConsoleLog(hideLogs);