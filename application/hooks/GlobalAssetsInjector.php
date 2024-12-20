<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Kelas GlobalAssetsInjector
 *
 * Kelas ini bertanggung jawab untuk menyisipkan aset CSS dan JS secara global
 * serta aset tambahan pada halaman yang memerlukan akses login.
 */
class GlobalAssetsInjector
{
    /**
     * Metode injectAssets
     *
     * Menyisipkan tag <link> untuk CSS dan tag <script> untuk JS ke dalam output HTML.
     * Aset global disisipkan di <head> dan aset tambahan (auth_js) disisipkan di footer (<body>).
     *
     * @param mixed $params Parameter tambahan yang mungkin diperlukan (tidak digunakan dalam metode ini)
     * @return void
     */
    public function injectAssets($params)
    {
        // Mendapatkan instance CodeIgniter
        $CI = &get_instance();

        // Memuat konfigurasi aset
        $CI->load->config('assets');

        // Mendapatkan daftar aset global dari konfigurasi
        $global_css = $CI->config->item('global_css');
        $global_js  = $CI->config->item('global_js');

        // Mendapatkan daftar aset tambahan jika pengguna login
        if ($CI->session->userdata('user_id')) {
            $auth_css = $CI->config->item('auth_css');
            $auth_js  = $CI->config->item('auth_js');
        } else {
            $auth_css = array();
            $auth_js  = array();
        }

        // Mendapatkan output yang dihasilkan oleh controller
        $output = $CI->output->get_output();

        // Membangun tag <link> untuk setiap file CSS global
        $global_css_tags = '';
        foreach ($global_css as $css) {
            $global_css_tags .= $this->buildCssTag($css);
        }

        // Membangun tag <script> untuk setiap file JS global
        $global_js_tags = '';
        foreach ($global_js as $js) {
            $global_js_tags .= $this->buildJsTag($js);
        }

        // Membangun tag <link> untuk setiap file CSS auth (jika ada)
        $auth_css_tags = '';
        if (!empty($auth_css)) {
            foreach ($auth_css as $css) {
                $auth_css_tags .= $this->buildCssTag($css);
            }
        }

        // Membangun tag <script> untuk setiap file JS auth (jika ada)
        $auth_js_tags = '';
        if (!empty($auth_js)) {
            foreach ($auth_js as $js) {
                $auth_js_tags .= $this->buildJsTag($js);
            }
        }

        // Menyiapkan inline script untuk menyembunyikan body segera setelah <head>
        $inline_script = <<<EOD
        <script>
            // Menyembunyikan body segera setelah <head> dimulai
            document.addEventListener('DOMContentLoaded', function() {
                document.body.style.visibility = 'hidden';
            });
        </script>
        EOD;

        // Membangun semua script untuk disisipkan di head
        $head_assets = $inline_script . "\n" . $global_css_tags . $global_js_tags;

        // Menyisipkan aset global di akhir <head>
        if (strpos($output, '</head>') !== false) {
            // Menyisipkan aset sebelum </head>
            $output = str_replace('</head>', $head_assets . '</head>', $output);
        } else if (strpos($output, '<head>') !== false) {
            // Menyisipkan aset segera setelah <head>
            $output = preg_replace('/<head>(.*?)<\/head>/is', '<head>' . $head_assets . '$1</head>', $output);
        }

        // Menyisipkan aset auth_css dan auth_js di akhir <body>
        if (!empty($auth_css_tags) || !empty($auth_js_tags)) {
            $footer_assets = $auth_css_tags . $auth_js_tags;

            if (strpos($output, '</body>') !== false) {
                // Menyisipkan aset sebelum </body>
                $output = str_replace('</body>', $footer_assets . '</body>', $output);
            } else if (strpos($output, '<body>') !== false) {
                // Menyisipkan aset segera setelah <body>
                $output = preg_replace('/<body>(.*?)<\/body>/is', '<body>' . '$1' . $footer_assets . '</body>', $output);
            }
        }

        // Mengatur output yang telah dimodifikasi
        $CI->output->set_output($output);
        $CI->output->_display();
    }

    /**
     * Metode buildCssTag
     *
     * Membangun tag <link> untuk file CSS.
     *
     * @param string $css Path relatif atau URL absolut ke file CSS yang akan disisipkan
     * @return string Tag <link> yang telah dibangun untuk disisipkan ke dalam HTML
     */
    private function buildCssTag($css)
    {
        // Cek apakah $css adalah URL absolut
        if (filter_var($css, FILTER_VALIDATE_URL)) {
            $href = $css;
        } else {
            $href = base_url($css);
        }
        return '<link rel="stylesheet" href="' . $href . '">' . "\n";
    }

    /**
     * Metode buildJsTag
     *
     * Membangun tag <script> untuk file JS.
     *
     * @param string $js Path relatif atau URL absolut ke file JS yang akan disisipkan
     * @return string Tag <script> yang telah dibangun untuk disisipkan ke dalam HTML
     */
    private function buildJsTag($js)
    {
        // Cek apakah $js adalah URL absolut
        if (filter_var($js, FILTER_VALIDATE_URL)) {
            $src = $js;
        } else {
            $src = base_url($js);
        }
        return '<script src="' . $src . '"></script>' . "\n";
    }
}