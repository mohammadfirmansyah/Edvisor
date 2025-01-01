// fonts.js
// Kode berikut menyisipkan definisi font-face ke dalam dokumen melalui elemen <style>.
// Pastikan Anda memanggil file ini di <head> atau setelah <body> agar style dapat diaplikasikan.

(function() {
    // Konten CSS asli kita simpan sebagai string di dalam variable cssContent
    var cssContent = `
/* Inter Variable Font */
@font-face {
    font-family: 'Inter';
    src: url('assets/fonts/Inter/Inter-VariableFont.ttf') format('truetype');
    font-weight: 100 900;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Inter';
    src: url('assets/fonts/Inter/Inter-Italic-VariableFont.ttf') format('truetype');
    font-weight: 100 900;
    font-style: italic;
    font-display: swap;
}

/* Lato Font */
@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-Thin.ttf') format('truetype');
    font-weight: 100;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-ThinItalic.ttf') format('truetype');
    font-weight: 100;
    font-style: italic;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-Light.ttf') format('truetype');
    font-weight: 300;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-LightItalic.ttf') format('truetype');
    font-weight: 300;
    font-style: italic;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-Italic.ttf') format('truetype');
    font-weight: 400;
    font-style: italic;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-Bold.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-BoldItalic.ttf') format('truetype');
    font-weight: 700;
    font-style: italic;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-Black.ttf') format('truetype');
    font-weight: 900;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Lato';
    src: url('assets/fonts/Lato/Lato-BlackItalic.ttf') format('truetype');
    font-weight: 900;
    font-style: italic;
    font-display: swap;
}

/* Oleo Script Font */
@font-face {
    font-family: 'Oleo Script';
    src: url('assets/fonts/Oleo_Script/OleoScript-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Oleo Script';
    src: url('assets/fonts/Oleo_Script/OleoScript-Bold.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}
    `;

    // Membuat elemen <style> baru
    var styleElement = document.createElement('style');
    // Menentukan tipe konten
    styleElement.setAttribute('type', 'text/css');
    // Memasukkan string CSS ke dalam elemen style
    styleElement.textContent = cssContent;

    // Sisipkan elemen style ke dalam <head>
    document.head.appendChild(styleElement);
})();