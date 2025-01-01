<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Kelas GlobalAssetsInjector
 *
 * Kelas ini menyisipkan aset global, aset tambahan (jika login),
 * aset khusus halaman, dan favicon berdasarkan controller dan fungsi yang sedang dijalankan.
 * Juga menampilkan overlay loading dengan progress bar.
 * Jika semua aset sudah dimuat atau disimpan di indexedDB, overlay tidak ditampilkan.
 * Juga menampilkan log semua aset yang muncul di network.
 *
 */
class GlobalAssetsInjector
{
    /**
     * Versi Aset
     *
     * Digunakan untuk cache busting dan versi indexedDB.
     *
     * @var string
     */
    private $asset_version = '1.2.0.1'; // Ubah sesuai kebutuhan

    /**
     * Tahun Rilis
     *
     * Digunakan untuk menampilkan tahun hak cipta.
     *
     * @var string
     */
    private $release_year = '2025'; // Tahun rilis

    /**
     * Nama database indexedDB
     *
     * @var string
     */
    private $idb_name = 'asset_storage';

    /**
     * Memformat versi aset menjadi format "vX.X.X (Build Y)"
     *
     * @return string Versi aset yang telah diformat
     */
    private function formatAssetVersion()
    {
        // Pisahkan versi berdasarkan titik
        $parts = explode('.', $this->asset_version);

        // Jika versi tidak memiliki setidaknya dua bagian, kembalikan versi asli
        if (count($parts) < 2) {
            return 'v' . $this->asset_version;
        }

        // Ambil nomor build dari bagian terakhir
        $build_number = array_pop($parts);

        // Gabungkan bagian versi utama
        $main_version = implode('.', $parts);

        // Kembalikan versi yang telah diformat
        return 'v' . $main_version . ' (Build ' . $build_number . ')';
    }

    public function injectAssets()
    {
        $CI = &get_instance();
        $CI->load->config('assets');

        // Mendapatkan nama controller dan metode saat ini
        $current_controller = $CI->router->fetch_class();
        $current_method = $CI->router->fetch_method();
        $current_page_key = $current_method; // Menyesuaikan dengan keys di assets.php

        // Memeriksa keberadaan variabel 'user'
        $user = $CI->session->userdata('user_id');
        $CI->load->vars(['user' => $user]);

        // Jika tidak ada data 'user', anggap sebagai excluded_method
        if (!$user) {
            $is_excluded_method = true;
        } else {
            $is_excluded_method = false;
        }

        // Jika ini adalah permintaan AJAX (misalnya heartbeat), atau metode saat ini adalah 'previewForm', 'updateActivity', atau 'checkLogin',
        // hanya muat aset khusus halaman (tanpa menyisipkan aset global atau auth) dan langsung return tanpa menyisipkan HTML overlay.
        if ($CI->input->is_ajax_request() || in_array($current_method, ['previewForm', 'updateActivity', 'checkLogin'])) {
            // Muat aset khusus halaman
            $page_assets = $CI->config->item('page_assets');
            $page_css_tags = '';
            $page_js_tags = '';
            if (isset($page_assets[$current_page_key])) {
                // CSS Khusus Halaman
                if (isset($page_assets[$current_page_key]['css']) && is_array($page_assets[$current_page_key]['css'])) {
                    foreach ($page_assets[$current_page_key]['css'] as $css) {
                        $asset_name = basename($css);
                        $page_css_tags .= $this->buildCssTag($css, $asset_name, false);
                    }
                }
                // JS Khusus Halaman
                if (isset($page_assets[$current_page_key]['js']) && is_array($page_assets[$current_page_key]['js'])) {
                    foreach ($page_assets[$current_page_key]['js'] as $js) {
                        $asset_name = basename($js);
                        $page_js_tags .= $this->buildJsTag($js, $asset_name, false);
                    }
                }
            }

            // Sisipkan CSS dan JS khusus halaman ke output
            $output = $CI->output->get_output();
            if (strpos($output, '</head>') !== false) {
                $output = str_replace('</head>', $page_css_tags . "\n" . '</head>', $output);
            }
            if (strpos($output, '</body>') !== false) {
                $output = str_replace('</body>', $page_js_tags . "\n" . '</body>', $output);
            }
            $CI->output->set_output($output);
            $CI->output->_display();
            return;
        }

        // Mengambil aset global
        $global_css = $CI->config->item('global_css');
        $global_js  = $CI->config->item('global_js');
        $global_images = $CI->config->item('global_images');

        // Definisikan loading GIFs secara terpisah
        $loading_gifs = [
            'assets/gif/loading.gif',
            'assets/gif/loading_success.gif',
            'assets/gif/loading_failed.gif',
        ];

        // Filter out loading GIFs dari global_images
        $filtered_global_images = array_filter($global_images, function($image) use ($loading_gifs) {
            return !in_array($image, $loading_gifs);
        });

        // Mengambil aset tambahan jika user login dan metode tidak dikecualikan
        if ($user && !$is_excluded_method) {
            $auth_css = $CI->config->item('auth_css');
            $auth_js  = $CI->config->item('auth_js');
        } else {
            $auth_css = array();
            $auth_js  = array();
        }

        // Memeriksa flashdata 'force_assets_refresh'
        // Jika true, maka overlay dan progress bar harus selalu muncul, dan aset dipaksa fetch terbaru.
        $force_refresh_flash = $CI->session->flashdata('force_assets_refresh');

        // Memeriksa cookie 'force_refresh'
        $force_refresh_cookie = $CI->input->cookie('force_refresh', true);
        $force_refresh = ($force_refresh_flash || $force_refresh_cookie) ? true : false;

        // Memeriksa flashdata 'success_login'
        // Jika ada, berarti user baru saja berhasil login via formLogin,
        // maka wajib tampilkan overlay dan progress bar meskipun semua aset tercache.
        $success_login = $CI->session->flashdata('success_login');

        // Mengambil output yang ada
        $output = $CI->output->get_output();

        // Membangun tag CSS global (mendukung fonts.js)
        $global_css_tags = '';
        foreach ($global_css as $css) {
            // Jika ternyata file bernama 'fonts.js', perlakukan sebagai JS (bukan CSS)
            if (basename($css) === 'fonts.js') {
                $asset_name = 'fonts.js';
                $global_css_tags .= $this->buildJsTag($css, $asset_name, $force_refresh);
            } else {
                $asset_name = basename($css);
                $global_css_tags .= $this->buildCssTag($css, $asset_name, $force_refresh);
            }
        }

        // Membangun tag CSS untuk aset tambahan (auth_css)
        $auth_css_tags = '';
        if (!empty($auth_css)) {
            foreach ($auth_css as $css) {
                if (basename($css) === 'fonts.js') {
                    $asset_name = 'fonts.js';
                    $auth_css_tags .= $this->buildJsTag($css, $asset_name, $force_refresh);
                } else {
                    $asset_name = basename($css);
                    $auth_css_tags .= $this->buildCssTag($css, $asset_name, $force_refresh);
                }
            }
        }

        // Membangun tag CSS untuk aset khusus halaman berdasarkan metode (method)
        $page_assets = $CI->config->item('page_assets');
        $page_css_tags = '';
        if (isset($page_assets[$current_page_key])) {
            // CSS Khusus Halaman
            if (isset($page_assets[$current_page_key]['css']) && is_array($page_assets[$current_page_key]['css'])) {
                foreach ($page_assets[$current_page_key]['css'] as $css) {
                    if (basename($css) === 'fonts.js') {
                        $asset_name = 'fonts.js';
                        $page_css_tags .= $this->buildJsTag($css, $asset_name, $force_refresh);
                    } else {
                        $asset_name = basename($css);
                        $page_css_tags .= $this->buildCssTag($css, $asset_name, $force_refresh);
                    }
                }
            }
        }

        // Membangun tag JS global
        $global_js_tags = '';
        foreach ($global_js as $js) {
            $asset_name = basename($js);
            // Semua aset global menggunakan buildJsTag dengan cache busting
            $global_js_tags .= $this->buildJsTag($js, $asset_name, $force_refresh);
        }

        // Membangun tag JS untuk aset tambahan (auth_js)
        $auth_js_tags = '';
        if (!empty($auth_js)) {
            foreach ($auth_js as $js) {
                $asset_name = basename($js);
                $auth_js_tags .= $this->buildJsTag($js, $asset_name, $force_refresh);
            }
        }

        // Membangun tag JS untuk aset khusus halaman berdasarkan metode (method)
        $page_js_tags = '';
        if (isset($page_assets[$current_page_key])) {
            if (isset($page_assets[$current_page_key]['js']) && is_array($page_assets[$current_page_key]['js'])) {
                foreach ($page_assets[$current_page_key]['js'] as $js) {
                    $asset_name = basename($js);
                    $page_js_tags .= $this->buildJsTag($js, $asset_name, $force_refresh);
                }
            }
        }

        // Mengambil konfigurasi favicon
        $favicon = $CI->config->item('favicon');
        $favicon_tag = '';
        if ($favicon) {
            $favicon_tag = $this->buildFaviconTag($favicon, $force_refresh);
        }

        // Definisikan versi aset yang telah diformat
        $formatted_version = $this->formatAssetVersion();

        // Membangun tag <link> preload untuk gambar, tanpa loading GIFs
        $global_images_tags = '';
        foreach ($filtered_global_images as $image) {
            $asset_name = basename($image);
            $global_images_tags .= $this->buildPreloadTag($image, $asset_name, 'image', $force_refresh);
        }

        // CSS overlay
        $loading_css = <<<EOD
        <style>
            /* Body disembunyikan secara default */
            body {
                opacity: 0;
            }

            #loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 90rem;
                height: 64rem;
                background-color: #FFFFFF; /* Tidak transparan */
                z-index: 9999;
                display: none;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                transition: opacity 0.5s ease-out; /* Transisi 0.5s ease-out */
                padding: 20px;
                box-sizing: border-box;
            }

            #loading-overlay.active {
                display: flex;
            }

            /* Saat .hidden ditambahkan, overlay memudar dengan ease-out 0.5s */
            #loading-overlay.hidden {
                opacity: 0;
            }

            /* Gaya untuk gambar loading */
            #loading-gif {
                width: 100px !important;
                height: 100px !important; 
                height: auto;
                margin-bottom: 20px; /* Jarak antara gambar dan pesan loading */
            }

            #loading-message {
                margin-bottom: 10px;
                font-size: 1em;
                color: #333;
            }

            #progress-container {
                width: 60%;
                max-width: 400px;
                background-color: #e0e0e0;
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 15px;
                position: relative;
                height: 20px;
            }

            #progress-bar {
                width: 0%;
                height: 100%;
                background-color: #2196F3;
                transition: width 0.6s ease-in-out, background-color 0.6s ease-in-out; /* Transisi lebih halus */
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-weight: bold;
                font-size: 0.8em;
                opacity: 0; /* Sembunyikan teks saat 0% */
            }

            #progress-bar.visible {
                opacity: 1;
            }

            #asset-log {
                width: 60%; /* Disesuaikan dengan progress bar */
                max-width: 400px; /* Disesuaikan dengan progress bar */
                overflow-y: auto;
                overflow-x: auto; /* Untuk memungkinkan scroll horizontal */
                text-align: left;
                font-family: monospace;
                font-size: 0.9em;
                color: #555;
                white-space: nowrap; /* Mencegah pembungkusan teks */
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 10px;
                box-sizing: border-box;
                display: none;
                background-color: #f9f9f9;
                height: 100px; /* Disesuaikan untuk 5 baris log */
                opacity: 0;
                transition: opacity 0.5s ease;
            }

            #asset-log.visible {
                opacity: 1;
            }

            /* Chrome, Safari, Opera */
            #asset-log::-webkit-scrollbar {
                display: none;
            }

            /* Gaya untuk log entry (nama aset) dengan animasi fade-in */
            .log-entry {
                opacity: 0;
                transform: translateY(10px);
                animation: fadeIn 0.5s forwards;
                font-size: 0.5rem;
                line-height: 2;
            }

            @keyframes fadeIn {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Kelas untuk log secured */
            .log-secured {
                color: #A5DC86;
            }

            /* Kelas untuk log ready */
            .log-ready {
                color: #555;
            }

            /* Kelas untuk log action required */
            .log-action-required {
                color: #F27474; /* Warna merah untuk Action Required */
            }

            /* Kelas untuk log completed dengan warna hijau */
            .log-completed {
                color: #A5DC86; /* Warna hijau untuk Completed */
                font-size: 0.5rem;
                line-height: 2;
                transform: translateY(0px) !important;
                font-weight: bold;
            }

            /* Kelas untuk log completed dengan issues (oranye) */
            .log-completed-issues {
                color: #F8BB86; /* Warna oranye untuk Completed with Issues */
                font-size: 0.5rem;
                line-height: 2;
                transform: translateY(0px) !important;
                font-weight: bold;
            }

            body.loading {
                pointer-events: none;
                overflow: hidden !important;
            }

            #notes {
                font-family: monospace;
                font-size: 0.9em;
                line-height: 2;
            }

            /* Pastikan #asset-log menampilkan elemen downloading dengan animasi */
            .log-downloading {
                color: #3FC3EE; /* Warna biru untuk pesan downloading */
                font-size: 0.5rem;
                line-height: 2;
                transform: translateY(0px) !important;
                font-weight: bold;
            }
        </style>
        EOD;

        // HTML overlay
        $loading_html = <<<EOD
        <div id="loading-overlay">
            <img src="" alt="Loading" id="loading-gif">
            <div id="loading-message">Mengunduh aset terbaru dari server.</div>
            <div id="progress-container">
                <div id="progress-bar" tabindex="0"></div>
            </div>
            <div id="asset-log"></div>
            <div id="notes">Tim Edvisor {$formatted_version} &copy; {$this->release_year}. Hak Cipta Dilindungi.</div>
        </div>
        EOD;

        // Inline script placeholder
        $inline_script = <<<EOD
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Placeholder jika diperlukan
            });
        </script>
        EOD;

        // Mengumpulkan preload dan prefetch tags
        $preload_tags = '';
        $prefetch_tags = '';
        if ($force_refresh) {
            // Mengumpulkan semua aset dari global, auth, dan semua halaman
            $all_assets = array_merge($global_css, $global_js);
            if (!empty($auth_css)) {
                $all_assets = array_merge($all_assets, $auth_css);
            }
            if (!empty($auth_js)) {
                $all_assets = array_merge($all_assets, $auth_js);
            }

            // Tambahkan preload untuk aset yang digunakan pada halaman saat ini
            foreach ($all_assets as $asset) {
                $asset_name = basename($asset);
                if (isset($page_assets[$current_page_key]['css']) && in_array($asset, $page_assets[$current_page_key]['css'])) {
                    $preload_tags .= $this->buildPreloadTag($asset, $asset_name, 'style', $force_refresh);
                }
                if (isset($page_assets[$current_page_key]['js']) && in_array($asset, $page_assets[$current_page_key]['js'])) {
                    $preload_tags .= $this->buildPreloadTag($asset, $asset_name, 'script', $force_refresh);
                }
            }

            // Ubah ke fonts.js (contoh) jika diperlukan
            $preload_tags .= $this->buildPreloadTag('assets/js/fonts.js', 'fonts.js', 'script', $force_refresh);

            // Tambahkan preload untuk file font yang digunakan
            $font_files = [
                'assets/fonts/Inter/Inter-VariableFont.ttf',
                'assets/fonts/Inter/Inter-Italic-VariableFont.ttf',
                'assets/fonts/Lato/Lato-Thin.ttf',
                'assets/fonts/Lato/Lato-ThinItalic.ttf',
                'assets/fonts/Lato/Lato-Light.ttf',
                'assets/fonts/Lato/Lato-LightItalic.ttf',
                'assets/fonts/Lato/Lato-Regular.ttf',
                'assets/fonts/Lato/Lato-Italic.ttf',
                'assets/fonts/Lato/Lato-Bold.ttf',
                'assets/fonts/Lato/Lato-BoldItalic.ttf',
                'assets/fonts/Lato/Lato-Black.ttf',
                'assets/fonts/Lato/Lato-BlackItalic.ttf',
                'assets/fonts/Oleo_Script/OleoScript-Regular.ttf',
                'assets/fonts/Oleo_Script/OleoScript-Bold.ttf',
            ];

            foreach ($font_files as $font) {
                $font_name = basename($font);
                $preload_tags .= $this->buildPreloadTag($font, $font_name, 'font', $force_refresh);
            }

            // Prefetch aset dari halaman lain (tidak digunakan pada halaman saat ini)
            foreach ($page_assets as $page_key => $assets) {
                if ($page_key === $current_page_key) {
                    // Skip aset khusus halaman saat ini karena sudah diinclude
                    continue;
                }
                if (isset($assets['css']) && is_array($assets['css'])) {
                    foreach ($assets['css'] as $css) {
                        $asset_name = basename($css);
                        $prefetch_tags .= $this->buildPrefetchTag($css, $asset_name, 'style', $force_refresh);
                    }
                }
                if (isset($assets['js']) && is_array($assets['js'])) {
                    foreach ($assets['js'] as $js) {
                        $asset_name = basename($js);
                        $prefetch_tags .= $this->buildPrefetchTag($js, $asset_name, 'script', $force_refresh);
                    }
                }
            }
        }

        /**
         * PHP Variable untuk JS
         */
        $force_refresh_js = $force_refresh ? 'true' : 'false';
        $success_login_js = $success_login ? 'true' : 'false';
        $assets_version_js = $this->asset_version ?: '0';

        $loading_js = <<<EOD
        <script>
            (function() {
                const idbName = '{$this->idb_name}';
                const idbVersion = parseInt('{$assets_version_js}', 10); // Versi IndexedDB harus sama dengan asset_version

                const forceRefresh = {$force_refresh_js};
                const successLogin = {$success_login_js};

                const loadingOverlay = document.getElementById('loading-overlay');
                const progressBar = document.getElementById('progress-bar');
                const assetLog = document.getElementById('asset-log');
                const loadingMessage = document.getElementById('loading-message');
                const loadingGif = document.getElementById('loading-gif');

                let downloadingLogEntry = null; // Referensi ke log downloading saat ini
                let hasIssues = false; // Flag untuk mengecek apakah ada aset dengan status 'action required'

                // Fungsi untuk mengambil aset dari IndexedDB
                async function getAssetFromDB(assetName) {
                    const db = await openDB();
                    return new Promise((resolve, reject) => {
                        const transaction = db.transaction(['assets'], 'readonly');
                        const store = transaction.objectStore('assets');
                        const request = store.get(assetName);

                        request.onsuccess = () => {
                            const result = request.result;
                            if (result) {
                                const blob = result.data;
                                const url = URL.createObjectURL(blob);
                                resolve(url);
                            } else {
                                resolve(null);
                            }
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                // Fungsi untuk menyimpan aset ke IndexedDB jika belum ada
                async function ensureAssetInDB(assetName, assetURL) {
                    const db = await openDB();
                    const isSaved = await isAssetSaved(db, assetName);
                    if (!isSaved || (isSaved && (await getAssetVersion(db, assetName)) !== '{$this->asset_version}')) {
                        try {
                            const response = await fetch(assetURL, { cache: 'reload' });
                            if (!response.ok) {
                                throw new Error('Failed to fetch asset dengan status: ' + response.status);
                            }

                            const blob = await response.blob();
                            const contentType = response.headers.get('content-type') || '';

                            if (!blob || blob.size === 0 || contentType.includes('text/html')) {
                                console.error(assetName + ' tidak dapat dimuat.');
                                return false;
                            }

                            await storeAsset(db, assetName, blob, assetURL, '{$this->asset_version}');
                            console.log(assetName + ' telah disimpan ke IndexedDB.');
                            return true;
                        } catch (error) {
                            console.error('Error saat menyimpan ' + assetName + ' ke IndexedDB:', error);
                            return false;
                        }
                    }
                    return true;
                }

                // Fungsi utama untuk mengatur gambar loading berdasarkan tipe dari IndexedDB atau server
                async function setDefaultLoadingGif() {
                    const loadingGifUrl = await getAssetFromDB('loading.gif');
                    if (loadingGifUrl) {
                        loadingGif.src = loadingGifUrl;
                    } else {
                        const assetURL = '{$this->encodeAssetUrl(base_url("assets/gif/loading.gif"))}';
                        loadingGif.src = assetURL;
                        await ensureAssetInDB('loading.gif', assetURL);
                    }
                }

                // Fungsi untuk mengatur gambar loading (success atau failed) dari IndexedDB atau server.
                async function setTypedLoadingGif(type) {
                    try {
                        // Tentukan nama file gif berdasarkan tipe
                        const gifName = type === 'success' ? 'loading_success.gif' : 'loading_failed.gif';
                        
                        // Coba ambil URL gif dari IndexedDB
                        const gifUrl = await getAssetFromDB(gifName);
                        
                        if (gifUrl) {
                            // Jika URL ditemukan di IndexedDB, set src gambar
                            loadingGif.src = gifUrl;
                        } else {
                            // Jika tidak ditemukan, ambil dari server
                            const assetURL = '{$this->encodeAssetUrl(base_url("assets/gif/"))}' + gifName;
                            loadingGif.src = assetURL;
                            
                            // Simpan URL gif ke IndexedDB untuk caching di masa mendatang
                            await ensureAssetInDB(gifName, assetURL);
                        }
                    } catch (error) {
                        console.error("Error setting " + type + " gif:", error);
                        // Fallback ke gambar default jika terjadi error
                        loadingGif.src = '{$this->encodeAssetUrl(base_url("assets/gif/default_loading.gif"))}';
                    }
                }

                // Mendapatkan semua elemen link, script, dan style di dalam head
                const headAssets = Array.from(document.head.querySelectorAll('link[data-asset-name], script[data-asset-name], style[data-asset-name]'));

                // Mengumpulkan semua aset dengan atribut data-asset-name
                const dataAssets = Array.from(document.querySelectorAll('link[data-asset-name], script[data-asset-name], style[data-asset-name]'));
                const dataAssetNames = dataAssets.map(a => a.getAttribute('data-asset-name'));

                // Semua aset
                const allAssets = dataAssets;
                const allAssetNames = allAssets.map(a => a.getAttribute('data-asset-name'));

                const totalAssets = allAssets.length; // Jumlah total aset yang relevan
                let loadedAssets = 0;
                let assetsProcessed = 0; // Counter untuk aset yang sudah diproses (baik berhasil maupun gagal)
                let overlayShown = false; // Default: jangan tampilkan overlay
                const loggedAssets = new Set();

                let lastLoadedAssets = 0;
                let lastProgressUpdateTime = Date.now();

                /**
                 * Fungsi delay untuk menambahkan jeda
                 * @param {number} ms - Waktu delay dalam milidetik
                 * @returns {Promise} Promise yang menyelesaikan setelah ms milidetik
                 */
                function delay(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }

                /**
                 * Log aset yang sedang dimuat ke console dan ke dalam elemen #asset-log
                 * @param {string} message - Pesan yang akan dilog
                 * @param {string} status - Status aset ('ready', 'secured', 'action required', 'downloading', 'completed')
                 */
                function logAsset(message, status) {
                    if (status !== 'downloading') {
                        console.log(message); // Log ke console hanya jika status bukan 'downloading'
                    }
                    if (assetLog) {
                        assetLog.style.display = 'block';
                        assetLog.classList.add('visible'); // Tambahkan kelas untuk animasi

                        // Penanganan berdasarkan status
                        if (status === 'downloading') {
                            // Hapus entri 'downloading' sebelumnya jika ada
                            if (downloadingLogEntry) {
                                assetLog.removeChild(downloadingLogEntry);
                            }
                            // Buat elemen baru untuk 'downloading'
                            downloadingLogEntry = document.createElement('div');
                            downloadingLogEntry.classList.add('log-entry', 'log-downloading');
                            downloadingLogEntry.textContent = 'Downloading... ' + message;
                            assetLog.appendChild(downloadingLogEntry);
                        }
                        // Jika status adalah 'completed'
                        else if (status === 'completed') {
                            // Hapus entri 'downloading' jika ada
                            if (downloadingLogEntry) {
                                assetLog.removeChild(downloadingLogEntry);
                                downloadingLogEntry = null;
                            }
                            // Tambahkan pesan 'Completed' atau 'Completed with Issues'
                            const completedEntry = document.createElement('div');
                            if (hasIssues) {
                                completedEntry.classList.add('log-entry', 'log-completed-issues');
                                completedEntry.textContent = 'Completed with Issues';
                            } else {
                                completedEntry.classList.add('log-entry', 'log-completed');
                                completedEntry.textContent = 'Completed';
                            }
                            assetLog.appendChild(completedEntry);
                        }
                        // Untuk status lainnya ('ready', 'secured', 'action required')
                        else {
                            const logEntry = document.createElement('div');
                            logEntry.classList.add('log-entry');
                            if (status === 'action required') {
                                logEntry.classList.add('log-action-required');
                                hasIssues = true; // Set flag jika ada 'action required'
                            } else if (status === 'ready') {
                                logEntry.classList.add('log-ready');
                            } else if (status === 'secured') {
                                logEntry.classList.add('log-secured');
                            }
                            logEntry.textContent = message;
                            assetLog.appendChild(logEntry);
                            assetLog.scrollTo({ top: assetLog.scrollHeight, behavior: 'smooth' });
                        }
                    }
                }

                function updateProgress() {
                    // Pastikan loadedAssets tidak melebihi totalAssets
                    if (loadedAssets > totalAssets) {
                        loadedAssets = totalAssets;
                    }
                    const progressPercentage = Math.round((loadedAssets / totalAssets) * 100);
                    if (progressBar) {
                        if (progressPercentage > 0) {
                            progressBar.innerText = progressPercentage + '%';
                            progressBar.classList.add('visible'); // Tampilkan teks persentase
                        } else {
                            progressBar.innerText = '';
                            progressBar.classList.remove('visible'); // Sembunyikan teks persentase
                        }
                        progressBar.style.width = progressPercentage + '%';
                    }
                    lastProgressUpdateTime = Date.now();
                }

                function checkAllAssetsProcessed() {
                    if (assetsProcessed === totalAssets) {
                        // Jika overlay masih ditampilkan
                        if (overlayShown) {
                            // Ubah pesan dan warna berdasarkan apakah ada issues
                            if (hasIssues) {
                                // Jika ada kendala, set gambar loading_failed.gif
                                setTypedLoadingGif('failed');
                                if (loadingMessage) {
                                    loadingMessage.innerText = 'Aset disimpan dengan kendala.';
                                }
                                // Ubah warna progress bar menjadi oranye
                                if (progressBar) {
                                    progressBar.style.backgroundColor = '#F8BB86';
                                }
                                // Log Completed with Issues
                                logAsset('', 'completed');
                            } else {
                                // Jika tidak ada kendala, set gambar loading_success.gif
                                setTypedLoadingGif('success');
                                if (loadingMessage) {
                                    loadingMessage.innerText = 'Aset berhasil disimpan secara lokal.';
                                }
                                // Ubah warna progress bar menjadi hijau
                                if (progressBar) {
                                    progressBar.style.backgroundColor = '#A5DC86';
                                }
                                // Log Completed
                                logAsset('', 'completed');
                            }
                            // Pastikan progress bar mencapai 100%
                            if (progressBar) {
                                progressBar.style.width = '100%';
                                progressBar.innerText = '100%';
                            }
                            // Tentukan durasi delay berdasarkan apakah ada isu
                            const delayDuration = hasIssues ? 4000 : 2000; // 4 detik jika ada issues, 2 detik jika tidak
                            // Berikan delay sebelum menyembunyikan overlay
                            setTimeout(function() {
                                hideLoadingOverlay();
                            }, delayDuration);
                        } else {
                            // Jika overlay tidak ditampilkan sama sekali, body harus ditampilkan
                            if (document.body) {
                                document.body.style.opacity = '1';
                                document.body.classList.remove('loading');
                            }
                        }
                    }
                }

                /**
                 * Memproses dan melacak pemuatan aset satu per satu dengan jeda
                 */
                async function trackAssetLoading() {
                    await checkAndStoreAssets();
                    checkAllAssetsProcessed();
                }

                async function checkAndStoreAssets() {
                    const db = await openDB();

                    for (const asset of allAssets) {
                        const assetName = asset.getAttribute('data-asset-name');
                        const assetURL = asset.href || asset.src;
                        const assetFileName = getFileNameFromURL(assetURL);
                        let assetStatus = asset.getAttribute('data-status') || 'unknown';

                        // Log downloading sebelum memulai proses
                        logAsset(assetFileName, 'downloading');

                        try {
                            const isSaved = await isAssetSaved(db, assetName);
                            const savedAssetData = await getAssetData(db, assetName);

                            if (isSaved) {
                                // Periksa versi aset di IndexedDB
                                if (savedAssetData && savedAssetData.version === '{$this->asset_version}') {
                                    assetStatus = 'secured';
                                    if (!loggedAssets.has(assetName)) {
                                        logAsset(assetFileName + ' (secured)', 'secured');
                                        loggedAssets.add(assetName);
                                    }
                                    loadedAssets++;
                                    updateProgress();
                                    assetsProcessed++;
                                } else {
                                    // Versi aset berbeda, perlu diperbarui
                                    try {
                                        const response = await fetch(assetURL, { cache: 'reload' });
                                        if (!response.ok) {
                                            throw new Error('Failed to fetch asset dengan status: ' + response.status);
                                        }

                                        const blob = await response.blob();
                                        const contentType = response.headers.get('content-type') || '';

                                        if (!blob || blob.size === 0 || contentType.includes('text/html')) {
                                            assetStatus = 'action required';
                                            if (!loggedAssets.has(assetName)) {
                                                logAsset(assetFileName + ' (action required)', 'action required');
                                                loggedAssets.add(assetName);
                                            }
                                            // Menunda 4 detik setelah menemukan aset dengan status 'action required'
                                            await delay(4000);
                                        } else {
                                            await storeAsset(db, assetName, blob, assetURL, '{$this->asset_version}');
                                            assetStatus = 'ready';
                                            if (!loggedAssets.has(assetName)) {
                                                logAsset(assetFileName + ' (ready)', 'ready');
                                                loggedAssets.add(assetName);
                                            }
                                            loadedAssets++;
                                            updateProgress();
                                        }
                                    } catch (error) {
                                        assetStatus = 'action required';
                                        if (!loggedAssets.has(assetName)) {
                                            logAsset(assetFileName + ' (action required)', 'action required');
                                            loggedAssets.add(assetName);
                                        }
                                        // Menunda 4 detik setelah menemukan aset dengan status 'action required'
                                        await delay(4000);
                                    } finally {
                                        assetsProcessed++;
                                    }
                                }
                            } else {
                                // Aset belum ada di indexedDB, perlu diunduh dan disimpan
                                try {
                                    const response = await fetch(assetURL, { cache: 'reload' });
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch asset dengan status: ' + response.status);
                                    }

                                    const blob = await response.blob();
                                    const contentType = response.headers.get('content-type') || '';

                                    if (!blob || blob.size === 0 || contentType.includes('text/html')) {
                                        assetStatus = 'action required';
                                        if (!loggedAssets.has(assetName)) {
                                            logAsset(assetFileName + ' (action required)', 'action required');
                                            loggedAssets.add(assetName);
                                        }
                                        // Menunda 4 detik setelah menemukan aset dengan status 'action required'
                                        await delay(4000);
                                    } else {
                                        await storeAsset(db, assetName, blob, assetURL, '{$this->asset_version}');
                                        assetStatus = 'ready';
                                        if (!loggedAssets.has(assetName)) {
                                            logAsset(assetFileName + ' (ready)', 'ready');
                                            loggedAssets.add(assetName);
                                        }
                                        loadedAssets++;
                                        updateProgress();
                                    }
                                } catch (error) {
                                    assetStatus = 'action required';
                                    if (!loggedAssets.has(assetName)) {
                                        logAsset(assetFileName + ' (action required)', 'action required');
                                        loggedAssets.add(assetName);
                                    }
                                    // Menunda 4 detik setelah menemukan aset dengan status 'action required'
                                    await delay(4000);
                                } finally {
                                    assetsProcessed++;
                                }
                            }
                        } catch (error) {
                            console.error('Error accessing IndexedDB:', error);
                            assetStatus = 'action required';
                            if (!loggedAssets.has(assetName)) {
                                logAsset(assetFileName + ' (action required)', 'action required');
                                loggedAssets.add(assetName);
                            }
                            assetsProcessed++;
                        } finally {
                            asset.setAttribute('data-status', assetStatus);
                        }
                    }

                    db.close();
                }

                async function openDB() {
                    return new Promise((resolve, reject) => {
                        const request = indexedDB.open(idbName, idbVersion);

                        request.onerror = (event) => {
                            console.error('IndexedDB error:', event.target.errorCode);
                            reject(event.target.error);
                        };

                        request.onsuccess = (event) => {
                            resolve(event.target.result);
                        };

                        request.onupgradeneeded = (event) => {
                            const db = event.target.result;
                            if (!db.objectStoreNames.contains('assets')) {
                                const assetStore = db.createObjectStore('assets', { keyPath: 'name' });
                                // Pastikan Anda membuat index untuk 'version' agar dapat melakukan query berdasarkan versi nanti
                                assetStore.createIndex('version', 'version', { unique: false });
                            }
                        };
                    });
                }

                async function isAssetSaved(db, assetName) {
                    return new Promise((resolve, reject) => {
                        const transaction = db.transaction(['assets'], 'readonly');
                        const store = transaction.objectStore('assets');
                        const request = store.get(assetName);

                        request.onsuccess = () => {
                            resolve(request.result !== undefined);
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                async function getAssetData(db, assetName) {
                    return new Promise((resolve, reject) => {
                        const transaction = db.transaction(['assets'], 'readonly');
                        const store = transaction.objectStore('assets');
                        const request = store.get(assetName);

                        request.onsuccess = () => {
                            resolve(request.result);
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                async function storeAsset(db, assetName, blob, assetURL, version) {
                    return new Promise((resolve, reject) => {
                        const transaction = db.transaction(['assets'], 'readwrite');
                        const store = transaction.objectStore('assets');
                        const assetData = {
                            name: assetName,
                            data: blob,
                            url: assetURL,
                            timestamp: Date.now(),
                            version: version
                        };

                        const request = store.put(assetData);

                        request.onsuccess = () => {
                            resolve();
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                /**
                 * Mengambil nama file dari URL
                 * @param {string} url - URL aset
                 * @returns {string} Nama file
                 */
                function getFileNameFromURL(url) {
                    try {
                        const urlObj = new URL(url, window.location.origin);
                        const pathname = urlObj.pathname;
                        const fileName = pathname.substring(pathname.lastIndexOf('/') + 1);
                        return fileName || urlObj.hostname;
                    } catch (e) {
                        // Jika URL tidak valid, coba ekstrak nama file secara manual
                        const parts = url.split('/');
                        return parts[parts.length - 1] || url;
                    }
                }

                // Fungsi untuk menampilkan overlay
                function showLoadingOverlay() {
                    // Set initial loading GIF dari IndexedDB
                    setDefaultLoadingGif();

                    if (progressBar) {
                        progressBar.style.width = '0%';
                        progressBar.innerText = '';
                        progressBar.style.backgroundColor = '#2196F3'; // Reset warna ke biru
                    }
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'flex';
                    }
                    if(document.body){
                        document.body.classList.add('loading'); // pointer-events: none
                        document.body.style.opacity = '1';
                    }
                    overlayShown = true;
                    lastProgressUpdateTime = Date.now();

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
                            behavior: 'smooth' // Menggunakan scroll smooth
                        });
                    }, 500); // Meningkatkan delay untuk memastikan scaling telah diterapkan
                }

                /**
                 * Fungsi untuk menyembunyikan overlay
                 * @param {boolean} immediate - Jika true, sembunyikan segera tanpa animasi
                 */
                function hideLoadingOverlay(immediate = false) {
                    if (immediate) {
                        if (loadingOverlay) {
                            loadingOverlay.style.display = 'none';
                        }
                        if(document.body){
                            document.body.classList.remove('loading');
                            document.body.style.opacity = '1';
                        }
                        overlayShown = false;
                    } else {
                        if (loadingOverlay) {
                            loadingOverlay.classList.add('hidden');
                            setTimeout(function() {
                                loadingOverlay.style.display = 'none';
                            }, 500); // Durasi transisi di CSS adalah 0.5s
                        }
                        if(document.body){
                            document.body.classList.remove('loading');
                            document.body.style.opacity = '1';
                        }
                        overlayShown = false;
                    }
                    // Setelah overlay benar-benar hilang, dispatch event 'overlayClosed'
                    setTimeout(function() {
                        window.dispatchEvent(new Event('overlayClosed'));
                    }, immediate ? 0 : 600); // 600ms untuk memastikan animasi selesai
                }

                // --- Pengecekan Awal ---
                async function initialize() {
                    const db = await openDB();
                    
                    const assetsToLoad = [];
                    for (const asset of allAssets) {
                        const assetName = asset.getAttribute('data-asset-name');
                        const isSaved = await isAssetSaved(db, assetName);
                        const savedAssetData = await getAssetData(db, assetName);
                        // Periksa apakah aset sudah ada di IndexedDB dan apakah versinya sesuai
                        if (!isSaved || !savedAssetData || savedAssetData.version !== '{$this->asset_version}') {
                            assetsToLoad.push(asset);
                        }
                    }
                    
                    if (db) {
                        db.close();
                    }

                    // Jika successLogin atau forceRefresh atau ada aset yang belum dipersistenkan => overlay
                    // Keluarkan 'page_assets' dari kondisi preload
                    if (successLogin || forceRefresh || assetsToLoad.length > 0) {
                        overlayShown = true;
                        showLoadingOverlay();
                        
                        setTimeout(() => {
                            trackAssetLoading();
                        }, 500);
                    } else {
                        // jika overlay tidak perlu -> perlihatkan body 
                        if(document.body){
                            document.body.style.opacity = '1';
                        }
                        window.dispatchEvent(new Event('overlayClosed'));
                        trackAssetLoading();
                    }
                }

                initialize();
                // --- Akhir Pengecekan Awal ---

                // Interval untuk memeriksa apakah progress bar stuck
                const checkStuckInterval = setInterval(function() {
                    if (!overlayShown || assetsProcessed === totalAssets) return;
                    const now = Date.now();
                    if (loadedAssets === lastLoadedAssets) {
                        // Tidak ada perubahan progress
                        if (now - lastProgressUpdateTime > 60000) {
                            // Stuck lebih dari 1 menit
                            if (loadingMessage) {
                                loadingMessage.innerText = 'Koneksi ke server lambat. Mohon tunggu hingga selesai.';
                            }
                            clearInterval(checkStuckInterval);
                            // Tunggu 5 detik sebelum reload
                            setTimeout(function() {
                                if (!forceRefresh && (totalAssets - loadedAssets) > 0) {
                                    location.reload();
                                }
                            }, 5000);
                        }
                    } else {
                        // Ada perubahan progress
                        lastLoadedAssets = loadedAssets;
                        lastProgressUpdateTime = now;
                    }
                }, 1000);
            })();
        </script>
        EOD;


        // Menyusun preload dan prefetch tags jika force_refresh dipanggil
        $head_assets = $loading_css . "\n" . $inline_script . "\n" . $favicon_tag . "\n" . $preload_tags . "\n" . $prefetch_tags . "\n" . $global_css_tags . $auth_css_tags . $page_css_tags . "\n" . $global_images_tags;

        // Tambahkan preload atau prefetch untuk loading GIFs berdasarkan apakah overlay akan ditampilkan
        $loading_gif_tags = '';
        // Hanya preload jika force_refresh atau success_login diaktifkan
        if ($force_refresh || $success_login) {
            // Overlay akan ditampilkan, preload loading GIFs
            foreach ($loading_gifs as $gif) {
                $asset_name = basename($gif);
                $loading_gif_tags .= $this->buildPreloadTag($gif, $asset_name, 'image', $force_refresh);
            }
        } else {
            // Overlay tidak akan ditampilkan, prefetch loading GIFs
            foreach ($loading_gifs as $gif) {
                $asset_name = basename($gif);
                $loading_gif_tags .= $this->buildPrefetchTag($gif, $asset_name, 'image', $force_refresh);
            }
        }

        // Tambahkan loading_gif_tags ke head_assets
        $head_assets .= "\n" . $loading_gif_tags;

        if ($force_refresh) {
            // Hapus cookie 'force_refresh' agar tidak selalu memaksa refresh
            // Pastikan untuk menghapus cookie dengan atribut SameSite=None dan Secure=true
            if (PHP_VERSION_ID >= 70300) {
                setcookie('force_refresh', '', [
                    'expires' => time() - 3600,
                    'path' => '/',
                    'domain' => '', // Sesuaikan jika diperlukan
                    'secure' => true, // Pastikan untuk menggunakan HTTPS
                    'httponly' => true,
                    'samesite' => 'None'
                ]);
            } else {
                // PHP <7.3, tambahkan SameSite sebagai bagian dari path
                setcookie('force_refresh', '', time() - 3600, '/; samesite=None', '', true, true);
            }
        }

        // Menyisipkan aset ke <head>
        if (strpos($output, '</head>') !== false) {
            $output = str_replace('</head>', $head_assets . "\n" . '</head>', $output);
        } else if (strpos($output, '<head>') !== false) {
            $output = preg_replace('/<head>(.*?)<\/head>/is', '<head>' . "\n" . $head_assets . "\n" . '$1</head>', $output);
        }

        // Menyusun semua aset untuk dimasukkan ke <body>
        $body_assets = '';
        $body_assets .= $loading_html;

        // Menyusun JS tags
        $body_assets .= $global_js_tags . $auth_js_tags . $page_js_tags . $loading_js;

        // Menyisipkan overlay dan JavaScript ke <body>
        if (strpos($output, '<body>') !== false) {
            // Menambahkan overlay HTML segera setelah tag <body>
            $output = preg_replace('/<body>/i', '<body>' . "\n" . $body_assets . "\n", $output);
        } else if (strpos($output, '</body>') !== false) {
            $output = str_replace('</body>', $body_assets . "\n" . '</body>', $output);
        } else {
            $output .= $body_assets;
        }

        // Mengatur output yang sudah diubah
        $CI->output->set_output($output);
        $CI->output->_display();
    }

    /**
     * Membangun tag <link> CSS dengan data-asset-name dan data-status
     *
     * @param string $css URL atau path relatif file CSS
     * @param string $asset_name Nama file aset untuk log
     * @param bool $force_refresh Jika true, tambahkan query param versi untuk cache busting
     * @return string Tag <link> untuk CSS
     */
    private function buildCssTag($css, $asset_name, $force_refresh = false)
    {
        if (filter_var($css, FILTER_VALIDATE_URL)) {
            $href = $css;
            $file_path = $this->getLocalFilePath($css);
        } else {
            $href = $this->encodeAssetUrl(base_url($css));
            $file_path = FCPATH . ltrim(parse_url($href, PHP_URL_PATH), '/');
        }

        if (file_exists($file_path) && filesize($file_path) > 0) {
            $status = 'ready';
        } else {
            $status = 'action required';
        }

        if ($force_refresh) {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        } else {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        }

        return '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
            . '" data-asset-name="' . htmlspecialchars($asset_name, ENT_QUOTES, 'UTF-8')
            . '" data-status="' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    /**
     * Membangun tag <script> JS dengan data-asset-name dan data-status
     *
     * @param string $js URL atau path relatif file JS
     * @param string $asset_name Nama file aset untuk log
     * @param bool $force_refresh Jika true, tambahkan query param versi untuk cache busting
     * @return string Tag <script> untuk JS
     */
    private function buildJsTag($js, $asset_name, $force_refresh = false)
    {
        if (filter_var($js, FILTER_VALIDATE_URL)) {
            $src = $js;
            $file_path = $this->getLocalFilePath($js);
        } else {
            $src = $this->encodeAssetUrl(base_url($js));
            $file_path = FCPATH . ltrim(parse_url($src, PHP_URL_PATH), '/');
        }

        if (file_exists($file_path) && filesize($file_path) > 0) {
            $status = 'ready';
        } else {
            $status = 'action required';
        }

        if ($force_refresh) {
            $src .= (strpos($src, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        } else {
            $src .= (strpos($src, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        }

        return '<script src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8')
            . '" data-asset-name="' . htmlspecialchars($asset_name, ENT_QUOTES, 'UTF-8')
            . '" data-status="' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '"></script>' . "\n";
    }

    /**
     * Membangun tag <link> favicon dengan cache busting dan data-status
     *
     * @param string $favicon URL atau path relatif file favicon
     * @param bool $force_refresh Jika true, tambahkan query param versi untuk cache busting
     * @return string Tag <link> untuk favicon
     */
    private function buildFaviconTag($favicon, $force_refresh = false)
    {
        if (filter_var($favicon, FILTER_VALIDATE_URL)) {
            $href = $favicon;
            $file_path = $this->getLocalFilePath($favicon);
        } else {
            $href = $this->encodeAssetUrl(base_url($favicon));
            $file_path = FCPATH . ltrim(parse_url($href, PHP_URL_PATH), '/');
        }

        if (file_exists($file_path) && filesize($file_path) > 0) {
            $status = 'ready';
        } else {
            $status = 'action required';
        }

        if ($force_refresh) {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        } else {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        }

        return '<link rel="icon" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
            . '" data-asset-name="favicon" data-status="' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    /**
     * Membangun tag <link> preload untuk semua aset di semua halaman
     *
     * @param string $asset URL atau path relatif file aset
     * @param string $asset_name Nama file aset untuk log
     * @param string $as_attr Nilai atribut 'as' (contoh: 'script', 'style', 'font')
     * @param bool $force_refresh Jika true, tambahkan query param versi untuk cache busting
     * @return string Tag <link> preload untuk aset
     */
    private function buildPreloadTag($asset, $asset_name, $as_attr, $force_refresh = false)
    {
        if (filter_var($asset, FILTER_VALIDATE_URL)) {
            $href = $asset;
            $file_path = $this->getLocalFilePath($asset);
        } else {
            $href = $this->encodeAssetUrl(base_url($asset));
            $file_path = FCPATH . ltrim(parse_url($href, PHP_URL_PATH), '/');
        }

        if (file_exists($file_path) && filesize($file_path) > 0) {
            $status = 'ready';
        } else {
            $status = 'action required';
        }

        if ($force_refresh) {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        } else {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        }

        $type = '';
        if ($as_attr === 'font') {
            $extension = pathinfo($href, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'woff2':
                    $type = 'font/woff2';
                    break;
                case 'woff':
                    $type = 'font/woff';
                    break;
                case 'ttf':
                    $type = 'font/ttf';
                    break;
                default:
                    $type = '';
            }
        }

        $type_attr = $type ? ' type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '"' : '';
        $crossorigin = ($as_attr === 'font') ? ' crossorigin="anonymous"' : '';

        return '<link rel="preload" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
            . '" as="' . htmlspecialchars($as_attr, ENT_QUOTES, 'UTF-8') . '"'
            . $type_attr . $crossorigin
            . ' data-asset-name="' . htmlspecialchars($asset_name, ENT_QUOTES, 'UTF-8')
            . '" data-status="' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    /**
     * Membangun tag <link> prefetch untuk aset yang tidak digunakan pada halaman saat ini
     *
     * @param string $asset URL atau path relatif file aset
     * @param string $asset_name Nama file aset untuk log
     * @param string $as_attr Nilai atribut 'as' (contoh: 'script', 'style', 'font')
     * @param bool $force_refresh Jika true, tambahkan query param versi untuk cache busting
     * @return string Tag <link> prefetch untuk aset
     */
    private function buildPrefetchTag($asset, $asset_name, $as_attr, $force_refresh = false)
    {
        if (filter_var($asset, FILTER_VALIDATE_URL)) {
            $href = $asset;
            $file_path = $this->getLocalFilePath($asset);
        } else {
            $href = $this->encodeAssetUrl(base_url($asset));
            $file_path = FCPATH . ltrim(parse_url($href, PHP_URL_PATH), '/');
        }

        if (file_exists($file_path) && filesize($file_path) > 0) {
            $status = 'ready';
        } else {
            $status = 'action required';
        }

        if ($force_refresh) {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        } else {
            $href .= (strpos($href, '?') === false ? '?' : '&') . 'v=' . $this->asset_version;
        }

        $type = '';
        if ($as_attr === 'font') {
            $extension = pathinfo($href, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'woff2':
                    $type = 'font/woff2';
                    break;
                case 'woff':
                    $type = 'font/woff';
                    break;
                case 'ttf':
                    $type = 'font/ttf';
                    break;
                default:
                    $type = '';
            }
        }

        $type_attr = $type ? ' type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '"' : '';
        $crossorigin = ($as_attr === 'font') ? ' crossorigin="anonymous"' : '';

        return '<link rel="prefetch" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
            . '" as="' . htmlspecialchars($as_attr, ENT_QUOTES, 'UTF-8') . '"'
            . $type_attr . $crossorigin
            . ' data-asset-name="' . htmlspecialchars($asset_name, ENT_QUOTES, 'UTF-8')
            . '" data-status="' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    /**
     * Meng-encode URL aset untuk menghindari karakter khusus seperti koma
     *
     * @param string $url URL yang akan di-encode
     * @return string URL yang telah di-encode
     */
    private function encodeAssetUrl($url)
    {
        // Parse URL
        $parsed_url = parse_url($url);

        if (!isset($parsed_url['path'])) {
            return $url;
        }

        // Encode setiap segmen path
        $path_segments = explode('/', $parsed_url['path']);
        $encoded_segments = array_map('rawurlencode', $path_segments);
        $encoded_path = implode('/', $encoded_segments);

        // Reconstruct URL
        $encoded_url = $encoded_path;

        if (isset($parsed_url['query'])) {
            $encoded_url .= '?' . $parsed_url['query'];
        }

        if (isset($parsed_url['fragment'])) {
            $encoded_url .= '#' . $parsed_url['fragment'];
        }

        // Jika ada scheme dan host, tambahkan kembali
        if (isset($parsed_url['scheme']) && isset($parsed_url['host'])) {
            $encoded_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $encoded_url;
        }

        return $encoded_url;
    }

    /**
     * Mendapatkan path lokal dari URL
     *
     * @param string $url URL aset
     * @return string Path lokal dari file aset
     */
    private function getLocalFilePath($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $parsed_url = parse_url($url);
            return FCPATH . ltrim($parsed_url['path'], '/');
        } else {
            return FCPATH . ltrim(parse_url($url, PHP_URL_PATH), '/');
        }
    }
}