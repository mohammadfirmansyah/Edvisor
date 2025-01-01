// Membuat elemen <style> baru untuk menambahkan CSS khusus
const styleElement = document.createElement('style');

// Menambahkan aturan CSS untuk .swal2-container
styleElement.textContent = `
.swal2-container {
    z-index: 9998 !important; /* Menetapkan z-index tinggi agar SweetAlert2 muncul di atas elemen lain */
}
`;

// Menambahkan elemen <style> ke dalam <head> dokumen
document.head.appendChild(styleElement);

// Menambahkan event listener untuk menangani saat pengguna meninggalkan halaman
// Contoh tindakan yang dapat memicu event ini: mengklik link atau mengirimkan formulir yang menyebabkan reload
window.addEventListener('beforeunload', function () {
    // Menampilkan popup SweetAlert2 dengan pesan loading
    Swal.fire({
        title: 'Memuat...', // Judul popup
        text: 'Sedang menunggu respons dari server.', // Teks deskriptif dalam popup
        allowOutsideClick: false, // Mencegah pengguna menutup popup dengan mengklik di luar area popup
        allowEscapeKey: false, // Mencegah pengguna menutup popup dengan menekan tombol Escape
        backdrop: true, // Menampilkan backdrop (latar belakang semi-transparan) saat popup aktif
        didOpen: () => {
            Swal.showLoading(); // Menampilkan animasi loading di dalam popup
        }
    });
});