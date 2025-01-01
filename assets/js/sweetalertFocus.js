(function() {
    // Memeriksa apakah SweetAlert2 sudah dimuat
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 tidak ditemukan. Pastikan SweetAlert2 sudah dimuat sebelum sweetalertFocus.js.');
        return;
    }

    // Menyimpan referensi asli dari Swal.fire
    const originalSwalFire = Swal.fire;

    // Mengoverride Swal.fire untuk menambahkan fungsionalitas fokus, scroll, dan pengaturan overflow body
    Swal.fire = function(...args) {
        let swalOptions = {};
        let originalDidOpen = null;
        let originalDidClose = null;
        let originalOverflow = '';

        // Jika argumen pertama adalah objek, gunakan sebagai opsi Swal
        if (typeof args[0] === 'object') {
            swalOptions = { ...args[0] };
        }

        // Menyimpan callback didOpen asli jika ada
        if (typeof swalOptions.didOpen === 'function') {
            originalDidOpen = swalOptions.didOpen;
        }

        // Menyimpan callback didClose asli jika ada
        if (typeof swalOptions.didClose === 'function') {
            originalDidClose = swalOptions.didClose;
        }

        // Menambahkan callback didOpen baru
        swalOptions.didOpen = (popup) => {
            // Memanggil callback didOpen asli jika ada
            if (originalDidOpen) {
                originalDidOpen(popup);
            }

            // Menunda eksekusi untuk menunggu animasi CSS selesai
            setTimeout(function() {
                // Mencari elemen input, textarea, atau select di dalam popup untuk difokuskan
                const inputElement = popup.querySelector('input, textarea, select');

                if (inputElement) {
                    inputElement.focus();
                } else {
                    // Jika tidak ada elemen input, fokuskan popup itu sendiri
                    popup.focus();
                }

                // Menggeser popup ke tengah layar dengan animasi smooth
                popup.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'center'
                });

                // Mengatur overflow body menjadi hidden untuk mencegah scroll
                document.body.style.overflow = 'hidden';
            }, 500); // Sesuaikan delay sesuai dengan durasi animasi CSS Anda
        };

        // Menambahkan callback didClose baru
        swalOptions.didClose = () => {
            // Mengembalikan overflow body ke nilai asli
            document.body.style.overflow = 'auto';

            // Memanggil callback didClose asli jika ada
            if (originalDidClose) {
                originalDidClose();
            }
        };

        // Memanggil Swal.fire asli dengan opsi yang telah dimodifikasi
        return originalSwalFire.call(Swal, swalOptions);
    };
})();