<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOCX Viewer</title>
    <base href="<?php echo base_url(); ?>">
    <link rel="stylesheet" href="assets/css/previewform.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx-preview-lib@0.1.14-fix-3/dist/docx-preview.min.js"></script>
</head>

<body>
    <!-- Kontainer utama tempat dokumen DOCX akan dirender -->
    <div id="container">
        <p>Loading document...</p>
    </div>
</body>
<script>   
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
                throw new Error("Failed to fetch the document");
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
                .then(() => // console.log("docx: finished rendering")) // Log ketika rendering selesai
                .catch(err => console.error("Error during rendering:", err)); // Menangani error selama rendering
        })
        .catch(err => {
            // Menangani error saat pengambilan dokumen
            console.error("Error fetching document:", err);
            // Menampilkan pesan error kepada pengguna
            document.getElementById("container").innerHTML = "<p>Failed to load document.</p>";
        });
</script>

</html>
