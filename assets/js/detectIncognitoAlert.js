/**
 * detectIncognitoAlert.js
 *
 * Skrip ini mendeteksi apakah browser sedang dalam mode incognito/private browsing.
 * Jika terdeteksi, skrip ini akan menampilkan peringatan menggunakan SweetAlert2
 * yang tidak dapat ditutup oleh pengguna dan menghentikan eksekusi skrip lain.
 */

(function () {
    // Fungsi untuk menampilkan peringatan tidak dapat ditutup menggunakan SweetAlert2
    function showIncognitoWarning() {
        // Tambahkan custom CSS untuk SweetAlert2
        const style = document.createElement('style');
        style.type = 'text/css';
        style.innerHTML = `
            /* Custom overlay */
            .swal2-container.custom-incognito-overlay {
                position: fixed;
                backdrop-filter: blur(5px);
                background: rgba(0, 0, 0, 0.5);
                max-width: 90rem !important;
                max-height; 64rem !important;
                overflow: scroll !important;
            }
            /* Custom popup */
            .swal2-popup.custom-incognito-popup {
                position: relative !important;
                background: #ffffff;
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                max-width: 70%;
                max-height: 70%
                text-align: center;
            }
            /* Custom title */
            .swal2-title.custom-incognito-title {
                font-size: 1.8em;
                font-weight: 700;
                margin-top: 20px;
                margin-bottom: 20px;
                color: #333333;
            }
            /* Custom content */
            .swal2-html-container.custom-incognito-content {
                font-size: 1.1em;
                color: #555555;
                margin-bottom: 30px;
                line-height: 1.5em;
            }
            /* Custom image */
            .swal2-image.custom-incognito-image {
                object-fit: contain;
                margin-bottom: 0px;
            }
            /* Disable default confirm button */
            .swal2-confirm {
                display: none;
            }
        `;
        document.head.appendChild(style);

        // Tampilkan SweetAlert2 dengan kustomisasi
        Swal.fire({
            title: 'Mode Private Browsing Terdeteksi!',
            html: 'Fitur ini sedang tidak didukung karena dapat menyebabkan beberapa fungsi tidak berjalan dengan semestinya.',
            imageUrl: 'assets/gif/incognito.gif',
            imageAlt: 'Incognito Mode',
            imageWidth: 200,
            customClass: {
                popup: 'custom-incognito-popup',
                title: 'custom-incognito-title',
                htmlContainer: 'custom-incognito-content',
                image: 'custom-incognito-image',
                container: 'custom-incognito-overlay'
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                // Menghilangkan kemampuan untuk menutup modal dengan klik di luar atau tombol escape
                Swal.getPopup().addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }
        });
    }

    // Fungsi utama untuk mendeteksi mode incognito dan menampilkan peringatan
    function detectAndWarn() {
        detectIncognito().then((result) => {
            // console.log(`Browser: ${result.browserName}, Mode Privat: ${result.isPrivate}`);
            if (result.isPrivate) {
                showIncognitoWarning();
                // Menghentikan eksekusi skrip selanjutnya
                stopFurtherExecution();
            }
        }).catch((error) => {
            console.error('Error saat mendeteksi mode incognito:', error);
        });
    }

    // Fungsi untuk menghentikan eksekusi skrip lainnya setelah peringatan
    function stopFurtherExecution() {
        // Menggunakan blok try-catch untuk menghentikan eksekusi skrip
        try {
            throw new Error('Mode incognito/private browsing tidak didukung. Skrip dihentikan.');
        } catch (e) {
            console.error(e.message);
        }
    }

    // Jalankan deteksi saat halaman dimuat
    window.addEventListener('DOMContentLoaded', detectAndWarn);
})();
