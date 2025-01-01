<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <base href="<?php echo base_url(); ?>">
</head>

<body>
    <!-- Kontainer utama tempat dokumen DOCX akan dirender -->
    <div id="container"></div>
</body>
<script>
    // Menampilkan animasi loading SweetAlert2 dengan teks bahasa Indonesia
    Swal.fire({
        title: 'Memuat dokumen...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // URL file .docx yang akan ditampilkan
    // Ganti dengan URL file DOCX Anda atau gunakan variabel server-side untuk dinamis
    const fileUrl = "<?= $file_url; ?>";

    /**
     * Mengambil file DOCX dari URL yang diberikan menggunakan Fetch API
     */
    fetch(fileUrl)
        .then(response => {
            // Memeriksa apakah respons berhasil (status HTTP 200-299)
            if (!response.ok) {
                // Jika tidak berhasil, melemparkan error
                throw new Error("Gagal mengambil dokumen");
            }
            // Mengonversi respons menjadi Blob untuk diproses lebih lanjut
            return response.blob();
        })
        .then(blob => {
            /**
             * Merender file DOCX ke dalam elemen HTML dengan id "container"
             * @param {Blob} blob - File DOCX dalam bentuk Blob
             * @param {HTMLElement} document.getElementById("container") - Elemen tempat dokumen akan dirender
             */
            docx.renderAsync(blob, document.getElementById("container"))
                .then(() => {
                    console.log("docx: selesai merender");
                    // Menutup animasi loading setelah rendering selesai
                    Swal.close();
                })
                .catch(err => {
                    console.error("Error selama rendering:", err);
                    // Menutup animasi loading dan menampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Merender Dokumen',
                        text: 'Terjadi kesalahan saat merender dokumen.'
                    });
                });
        })
        .catch(err => {
            // Menangani error saat pengambilan dokumen
            console.error("Error saat mengambil dokumen:", err);
            // Menutup animasi loading dan menampilkan pesan error
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Dokumen',
                text: 'Tidak dapat memuat dokumen.'
            });
            // Menampilkan pesan error kepada pengguna di kontainer
            document.getElementById("container").innerHTML = "<p>Gagal memuat dokumen.</p>";
        });
</script>

</html>