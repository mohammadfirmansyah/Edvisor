/**
 * Menyembunyikan scrollbar untuk mencegah tampilan yang tidak diinginkan.
 */
function hideScrollbar() {
    document.documentElement.style.overflow = 'hidden'; // Menyembunyikan scrollbar
}

/**
 * Menampilkan kembali body setelah scaling selesai.
 */
function showBody() {
    document.body.style.visibility = 'visible'; // Menampilkan body
}

/**
 * Melakukan scaling pada body berdasarkan ukuran layar untuk memastikan tampilan yang responsif tanpa efek zoom.
 */
function scaleBody() {
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;
    const baseWidth = 1440;
    const baseHeight = 1024;
    const aspectRatio = screenWidth / screenHeight;

    let scale;

    // Menentukan apakah orientasi adalah portrait atau landscape
    if (aspectRatio <= baseWidth / baseHeight || screenHeight > screenWidth) {
        // Orientasi portrait (misalnya, ponsel)
        scale = screenHeight / baseHeight;
        document.body.style.transform = `scale(${scale})`;
        document.body.style.transformOrigin = 'top left';
        document.body.style.width = `${screenWidth / scale}px`;
        document.body.style.height = `${baseHeight}px`;
        document.documentElement.style.width = `${screenWidth / scale}px`;
        document.body.style.overflowY = 'hidden';
        document.body.style.overflowX = 'scroll';
    } else {
        // Orientasi landscape (misalnya, laptop)
        scale = screenWidth / baseWidth;
        document.body.style.transform = `scale(${scale})`;
        document.body.style.transformOrigin = 'top left';
        document.body.style.width = `${baseWidth}px`;
        document.body.style.height = `${screenHeight / scale}px`;
        document.documentElement.style.height = `${screenHeight / scale}px`;
        document.body.style.overflowY = 'scroll';
        document.body.style.overflowX = 'hidden';
    }

    // Mengatur background dan ukuran html untuk memastikan tampilan yang mulus
    document.documentElement.style.background = 'transparent';
    document.documentElement.style.width = '100%';
    document.documentElement.style.height = '100%';

    // Menampilkan kembali body setelah scaling
    showBody();
}

// Menambahkan event listener untuk menangani perubahan ukuran jendela
window.addEventListener('resize', scaleBody);

// Menambahkan event listener untuk menangani saat halaman selesai dimuat
window.addEventListener('DOMContentLoaded', function() {
    hideScrollbar(); // Menyembunyikan scrollbar
    scaleBody();     // Melakukan scaling
});
