## 📢 Release Notes

### v1.2.0 Stable

**Perbaikan yang Telah Dilakukan:**

1. **Peningkatan Kualitas dan Stabilitas Aplikasi**
   - **Kualitas Hidup & Stabilitas Menyeluruh**  
     Meningkatkan stabilitas pada semua tugas penting dalam aplikasi serta memastikan performa yang lebih andal dan konsisten. Termasuk mencegah penumpukan sesi login untuk menghindari inkonsistensi data dan memperbaiki logika heartbeat untuk menjaga koneksi pengguna dengan server.
   - **Pemeriksaan Versi Aset Setelah Login**  
     Aplikasi kini secara otomatis mengecek dan mengunduh versi aset terbaru setelah pengguna berhasil login, memastikan semua aset yang terdaftar di sistem selalu up-to-date untuk meningkatkan stabilitas selama pemeliharaan.
   - **Trigger pada Struktur Database**  
     Menambahkan trigger pada struktur database untuk meningkatkan stabilitas aplikasi dengan memastikan konsistensi data secara otomatis saat terjadi perubahan.
   - **Pemuatan Font dari Aset Lokal**  
     Mengubah metode pemuatan font dari CDN menjadi dimuat langsung dari aset lokal, mengurangi ketergantungan pada sumber eksternal dan meningkatkan kecepatan pemuatan.

2. **Penambahan Fitur Baru**
   - **Timer pada Halaman Penilaian Formulir**  
     Menambahkan fitur timer yang secara otomatis menyimpan formulir dan menutup akses setelah waktu habis, memastikan keamanan dan keteraturan dalam proses penilaian.

3. **Optimasi Navigasi dan Redirect**
   - **Redirect Konsisten pada Sidebar Guru Model**  
     Memperbaiki alur redirect setelah membuat atau menghapus kelas guru model agar kembali ke sidebar guru model, meningkatkan konsistensi navigasi dalam aplikasi.

4. **Optimasi Metode Pengunduhan dan Penyimpanan Aset**
   - **Metode Download Berkas & Formulir yang Ditingkatkan**  
     Mengubah metode pengunduhan berkas dan formulir kelas Guru Model dengan menggunakan `fetch` dan `blob` untuk meningkatkan stabilitas dan keandalan proses pengunduhan.
   - **Penyimpanan Aset Menggunakan IndexedDB**  
     Beralih dari penyimpanan aset dalam cache ke IndexedDB, memastikan konsistensi dan stabilitas aset di seluruh halaman aplikasi. Selain itu, aplikasi kini secara otomatis memeriksa dan mengunduh versi aset terbaru saat halaman dibuka.
   - **Metode Penyimpanan Aset Menggunakan Hook**  
     Mengubah metode penyimpanan aset menggunakan hook untuk memastikan aset diperbarui dengan lebih konsisten selama proses pemeliharaan.

5. **Peningkatan Animasi dan Tampilan**
   - **Animasi Loading yang Lebih Responsif**  
     Menambahkan animasi loading saat berpindah halaman, mempersiapkan file sebelum diunduh, serta validasi aset dari server dengan teks log untuk pengalaman pengguna yang lebih mulus.
   - **Animasi Progress Bar pada Unggah Foto Profil**  
     Menambahkan animasi progress bar dan efek saat meng-hover tombol unggah foto profil, serta loading saat menyimpan data profil pengguna.
   - **Optimasi Tampilan pada Perangkat Mobile**  
     Memperbaiki tampilan website pada perangkat mobile agar layout lebih konsisten dan stabil, termasuk scroll otomatis ke tengah setelah rotasi dengan delay 0,5 detik dan penempatan popup di tengah layar.
   - **Progres Bar Unggah Berkas yang Lebih Mulus**  
     Memperbaiki progres bar unggah berkas agar tampil dengan lebih mulus dan stabil.

6. **Perbaikan Logika dan Keamanan**
   - **Perbaikan Logika Heartbeat**  
     Memperbaiki logika heartbeat untuk menjaga koneksi pengguna dengan server, mencegah penumpukan sesi login yang dapat menyebabkan inkonsistensi data.
   - **Retry Heartbeat Otomatis**  
     Menambahkan kemampuan untuk mengirimkan heartbeat secara otomatis jika gagal (retry) dengan interval 0,5 detik dan maksimal 10 kali percobaan sebelum menampilkan pesan reconnect, guna meningkatkan stabilitas aplikasi.
   - **Regenerasi Session ID yang Lebih Cepat**  
     Memperpendek waktu regenerasi session ID menjadi setiap 30 detik untuk meningkatkan keamanan aplikasi.
   - **Konsistensi Pengiriman JSON**  
     Menambahkan `$this->output->_display();` dan `exit;` di akhir pengiriman JSON untuk menjaga konsistensi dan kestabilan data.

7. **Perbaikan Konsistensi dan Validasi**
   - **Pesan Teks "Belum Ada Data"**  
     Menampilkan pesan ini ketika data Rekaman Suara, Catatan Khusus, dan Dokumentasi kosong, memberikan informasi yang lebih jelas kepada pengguna.
   - **Perbaikan Error pada Sidebar Guru Model**  
     Memperbaiki error terkait seleksi kondisi ketika observer tidak memiliki foto profil, memastikan sidebar guru model berfungsi dengan baik.
   - **Validasi Input yang Ditingkatkan**  
     Memperbaiki validasi input pada halaman signup dan profil pengguna untuk memastikan data yang lebih akurat dan konsisten.
   - **Placeholder Konsisten pada Input Kompetensi Dasar**  
     Memperbaiki placeholder di halaman buat dan edit kelas dengan mengubahnya menjadi textarea, memastikan tampilan yang lebih konsisten dan mudah dipahami pengguna.
   - **Pengaturan Format Penyimpanan Foto Profil**  
     Memperbaiki format penyimpanan foto profil untuk konsistensi dan efisiensi penyimpanan data.
   - **Memperbaiki Input Formulir di Seluruh Halaman**  
     Memperbaiki input formulir di seluruh halaman agar menjadi lebih stabil dan konsisten.
   - **Memperbaiki Tebal Garis Tanda Tangan**  
     Memperbaiki tebal garis tanda tangan agar menjadi lebih konsisten dan stabil.
   - **Menambahkan Image Smoothing pada Tanda Tangan**  
     Menambahkan image smoothing dalam tanda tangan agar tampil lebih mulus dan stabil.

8. **Peningkatan Pengalaman Pengguna**
   - **Petunjuk Login Multi-Browser**  
     Menambahkan teks petunjuk ketika pengguna mencoba login menggunakan lebih dari satu browser dengan akun yang sama, membantu mencegah kebingungan dan konflik sesi.
   - **Fokus pada Preview Formulir dan Dokumentasi**  
     Menambahkan fokus otomatis pada preview formulir penilaian dan dokumentasi saat ditampilkan, meningkatkan navigasi dan penggunaan aplikasi.
   - **Perbaikan Animasi Scroll dan Status Kelas**  
     Memperbaiki animasi scroll pada teks ellipsis dan status kelas guru model di sidebar agar tampil lebih konsisten dan responsif.
   - **Menghapus Efek Fokus pada Input Pencarian Kelas**  
     Menghilangkan efek fokus ke input pencarian kelas ketika halaman beranda, guru model, dan observer dibuka, memberikan tampilan yang lebih bersih.
   - **Perbaikan SweetAlert2**  
     Memperbaiki SweetAlert2 agar tampil konsisten di tengah halaman, meningkatkan kejelasan notifikasi bagi pengguna.
   - **Mengubah Timer Pesan Berhasil Masuk Akun**  
     Mengubah timer pesan berhasil masuk akun menjadi 4 detik untuk memberikan waktu yang cukup bagi pengguna memahami notifikasi.
   - **Menghapus Jeda Pengiriman Pembaruan Jam**  
     Menghapus jeda pengiriman pembaruan jam agar tampil lebih stabil dan responsif.

<details>
  <summary><h3 id="instalasi-menggunakan-localhost">Instalasi menggunakan localhost</h3></summary>

  Berikut adalah langkah-langkah untuk menginstalasi Edvisor di localhost:

  1. **Install XAMPP**  
     - Unduh dan instal XAMPP versi PHP 8.2.12 dari <a href="https://www.apachefriends.org/index.html" target="_blank">situs resmi XAMPP</a>.
     - Ikuti petunjuk instalasi untuk sistem operasi Anda.

  2. **Aktifkan Apache dan MySQL**  
     - Buka **XAMPP Control Panel** setelah instalasi selesai.
     - Pada panel kontrol, klik tombol **Start** di sebelah **Apache** dan **MySQL** untuk memulai layanan tersebut.
     - Pastikan status kedua layanan berubah menjadi **Running**.

  3. **Ekstrak File Edvisor**  
     - Ekstrak file `Edvisor.7z` dan tempatkan dalam direktori `xampp/htdocs/`.

  4. **Impor Database**  
     - Buka file `database.sql` dan salin seluruh query.
     - Buka browser dan navigasikan ke [`http://localhost/phpmyadmin/`](http://localhost/phpmyadmin/index.php?route=/server/sql).
     - Klik tab **SQL**, tempelkan query yang telah disalin, dan jalankan dengan menekan tombol **Go**.

  5. **Jalankan Aplikasi**  
     - Buka browser dan akses [`http://localhost/edvisor/`](http://localhost/edvisor/) untuk menguji aplikasi.
</details>

<details>
  <summary><h3 id="instalasi-menggunakan-localtunnel">Instalasi menggunakan localTunnel</h3></summary>

  Untuk memungkinkan akses publik ke aplikasi Edvisor yang berjalan di jaringan pribadi, Anda dapat menggunakan **localTunnel**. Berikut adalah langkah-langkah instalasinya:

  1. **Pastikan Instalasi di Localhost Selesai**  
     - Pastikan Anda telah menyelesaikan semua tahapan instalasi Edvisor di localhost seperti pada langkah [Instalasi menggunakan localhost](#instalasi-menggunakan-localhost).

  2. **Jalankan Apache dalam XAMPP dan Catat Port yang Digunakan untuk HTTP**  
     - Buka XAMPP Control Panel.
     - Jalankan Apache dan MySQL, kemudian catat port yang digunakan untuk HTTP (biasanya port 80).

  3. **Buka Direktori Konfigurasi Aplikasi**  
     - Navigasikan ke direktori `xampp/htdocs/Edvisor/application/config` menggunakan File Explorer.

  4. **Edit File 'config.php'**  
     - Buka file `config.php` menggunakan text editor favorit Anda.

  5. **Definisikan Subdomain yang Ingin Digunakan sebagai Alamat Web Hosting**  
     - Pastikan subdomain yang dipilih unik agar bisa digunakan.
        
       ```php
       $localTunnelSubdomain = '[subdomain].loca.lt';
       ```

     - Contoh:
     
       ```php
       $localTunnelSubdomain = 'edvisorfilkomub.loca.lt';
       ```

  6. **Buka Direktori Konfigurasi Virtual Hosts**  
     - Navigasikan ke direktori `xampp/apache/conf/extra/` menggunakan File Explorer.

  7. **Edit File 'httpd-vhosts.conf'**  
     - Buka file `httpd-vhosts.conf` menggunakan text editor favorit Anda.

  8. **Tambahkan Konfigurasi Virtual Hosts pada Baris Paling Bawah File `httpd-vhosts.conf`**  
     - Tambahkan kode berikut dengan mengganti `[port HTTP]` dan `[subdomain yang sudah didefinisikan]` sesuai dengan konfigurasi Anda:
     <br>

     ```apache
     <VirtualHost *:[port HTTP]>
         ServerName localhost
         DocumentRoot "C:/xampp/htdocs/"
         <Directory "C:/xampp/htdocs/">
             Options Indexes FollowSymLinks Includes ExecCGI
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>

     <VirtualHost *:<port HTTP>>
         ServerName [subdomain yang sudah didefinisikan].loca.lt
         DocumentRoot "C:/xampp/htdocs/edvisor"
         <Directory "C:/xampp/htdocs/edvisor">
             Options Indexes FollowSymLinks Includes ExecCGI
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>
     ```

     **Contoh:**

     ```apache
     <VirtualHost *:80>
         ServerName localhost
         DocumentRoot "C:/xampp/htdocs/"
         <Directory "C:/xampp/htdocs/">
             Options Indexes FollowSymLinks Includes ExecCGI
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>

     <VirtualHost *:80>
         ServerName edvisorfilkomub.loca.lt
         DocumentRoot "C:/xampp/htdocs/edvisor"
         <Directory "C:/xampp/htdocs/edvisor">
             Options Indexes FollowSymLinks Includes ExecCGI
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>
     ```

  9. **Unduh dan Install Node.js Versi LTS dari Situs Resmi Node.js**  
     - Kunjungi <a href="https://nodejs.org/" target="_blank">situs resmi Node.js</a> dan unduh versi LTS terbaru.
     - Ikuti petunjuk instalasi untuk sistem operasi Anda.

  10. **Buka Command Prompt**  
      - Tekan tombol `Windows + R`, ketik `cmd`, dan tekan `Enter` untuk membuka Command Prompt.

  11. **Verifikasi Instalasi Node.js dan npm**  
      ```bash
      node -v
      npm -v
      ```
      Pastikan kedua perintah tersebut mengembalikan versi yang terinstal.

  12. **Install localTunnel Secara Global**  
      - Jalankan perintah berikut di Command Prompt:
      <br>

      ```bash
      npm install -g localtunnel
      ```

  13. **Verifikasi Instalasi localTunnel**  
      ```bash
      lt --version
      ```
      Pastikan **localTunnel** terinstal dengan benar.

  14. **Jalankan localTunnel** 
      - Jalankan perintah berikut dengan mengganti <port HTTP> dan <subdomain> sesuai dengan konfigurasi Anda
      <br>
      
      ```bash
      lt --port [port HTTP] --subdomain [subdomain yang sudah didefinisikan]
      ```
      
      **Contoh:**

      ```bash
      lt --port 80 --subdomain edvisorfilkomub
      ```

      **Catatan:**  
      - `--port 80`: Menentukan port lokal yang akan diekspos.
      - `--subdomain edvisorfilkomub`: Menginginkan subdomain khusus. Namun, **localTunnel** secara gratis tidak menjamin ketersediaan subdomain tertentu. Jika subdomain yang diinginkan sudah digunakan, Anda mungkin perlu memilih subdomain lain.

  15. **Uji Akses Publik melalui Browser dengan Menuliskan Subdomain dalam URL**  
      
      **Contoh URL:**
      ```bash
      https://edvisorfilkomub.loca.lt/
      ```

  16. **Uji dengan Menggunakan Perangkat dan Jaringan yang Berbeda**  
      - Pastikan aplikasi Edvisor dapat diakses dari perangkat dan jaringan yang berbeda untuk memastikan kompatibilitas dan kestabilan akses publik.

  17. **Pastikan Koneksi Internet Stabil**  
      - Pastikan internet pada jaringan pribadi Anda stabil agar tidak muncul error **502 Bad Gateway** saat diakses dari perangkat lain.

  18. **Mengatasi Pesan Password Tunnel**  
      - Apabila muncul pesan `'To access the website, please enter the tunnel password below.'`, isi password tunnel dengan alamat IP jaringan pribadi Anda. Gunakan perintah `ipconfig` di Command Prompt untuk mendapatkan alamat IPv4 Anda, kemudian salin dan masukkan sebagai password.
    
      **Langkah-langkah:**
      1. Buka Command Prompt.
      2. Jalankan perintah: 

         ```bash
         ipconfig
         ```
      3. Cari bagian **IPv4 Address** dan salin alamatnya.
      4. Masukkan alamat IP tersebut sebagai password tunnel saat diminta.
</details>

<details>
  <summary><h3 id="instalasi-menggunakan-localtonet">Instalasi menggunakan LocalToNet</h3></summary>

  Setelah berhasil menggunakan localTunnel, Anda juga dapat menggunakan <a href="https://localtonet.com/" target="_blank">LocalToNet</a> sebagai alternatif untuk membuat aplikasi Edvisor dapat diakses secara publik.

  1. **Pastikan Instalasi di Localhost Selesai**
     - Pastikan langkah [Instalasi menggunakan localhost](#instalasi-menggunakan-localhost) telah selesai.

  2. **Jalankan Apache dalam XAMPP dan Catat Port HTTP**
     - Buka XAMPP Control Panel.
     - Jalankan Apache dan MySQL, catat port HTTP (biasanya port 80).

  3. **Buka Direktori Konfigurasi Aplikasi**
     - Navigasikan ke `xampp/htdocs/Edvisor/application/config`.

  4. **Edit File 'config.php'**
     - Buka `config.php` menggunakan text editor favorit Anda.

  5. **Definisikan Subdomain LocalToNet**
     ```php
     $localToNetSubdomain = '<subdomain>.localto.net';
     ```
     
     **Contoh:**
     ```php
     $localToNetSubdomain = 'edvisorfilkomub.localto.net';
     ```

  6. **Buka Direktori Konfigurasi Virtual Hosts**
     - Navigasikan ke `xampp/apache/conf/extra/`.

  7. **Edit File 'httpd-vhosts.conf'**
     - Buka `httpd-vhosts.conf` menggunakan text editor favorit Anda.

  8. **Tambahkan Konfigurasi Virtual Hosts**
     - Tambahkan kode berikut dengan mengganti `[port HTTP]` dan `[subdomain yang sudah didefinisikan]` sesuai dengan konfigurasi Anda:
     <br>

     ```apache
     <VirtualHost *:[port HTTP]>
         ServerName localhost
         DocumentRoot "C:/xampp/htdocs/"
         <Directory "C:/xampp/htdocs/">
             Options Indexes FollowSymLinks Includes ExecCGI
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>

     <VirtualHost *:[port HTTP]>
      ServerAdmin webmaster@localto.net
      ServerName [subdomain yang sudah didefinisikan].localto.net
      DocumentRoot "C:/xampp/htdocs/Edvisor"
      <Directory "C:/xampp/htdocs/Edvisor">
          Options Indexes FollowSymLinks Includes ExecCGI
          AllowOverride All
          Require all granted
      </Directory>
      ErrorLog "logs/[subdomain yang sudah didefinisikan].localto.net-error.log"
      CustomLog "logs/[subdomain yang sudah didefinisikan].localto.net-access.log" common
     </VirtualHost>
     ```

     **Contoh:**
     ```apache
     <VirtualHost *:80>
         ServerName localhost
         DocumentRoot "C:/xampp/htdocs/"
         <Directory "C:/xampp/htdocs/">
             Options Indexes FollowSymLinks Includes ExecCGI
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>

     <VirtualHost *:80>
      ServerAdmin webmaster@localto.net
      ServerName edvisorfilkomub.localto.net
      DocumentRoot "C:/xampp/htdocs/Edvisor"
      <Directory "C:/xampp/htdocs/Edvisor">
          Options Indexes FollowSymLinks Includes ExecCGI
          AllowOverride All
          Require all granted
      </Directory>
      ErrorLog "logs/edvisorfilkomub.localto.net-error.log"
      CustomLog "logs/edvisorfilkomub.localto.net-access.log" common
     </VirtualHost>
     ```

  9. **Daftarkan akun di LocalToNet**
     - Daftar dan masuk melalui situs resmi <a href="https://localtonet.com/" target="_blank">LocalToNet</a>.

  10. **Tambahkan Balance (Opsional)**
      - Menambah balance untuk meningkatkan bandwidth.

  11. **Buka My Tunnels > HTTP**
      - Buka sidebar LocalToNet, lalu akses halaman Tunnel HTTP.
     
  12. **Isi Formulir Tunnel**
      ```
      Process Type: Custom SubDomain
      Auth Token: Default
      Server: SG-Singapore
      SubDomain: [subdomain yang sudah didefinisikan]
      Domain: localto.net
      IP: 127.0.0.1
      Port: [port HTTP]
      ```

      **Contoh:**
      ```
      Process Type: Custom SubDomain
      Auth Token: Default
      Server: SG-Singapore
      SubDomain: edvisorfilkomub
      Domain: localto.net
      IP: 127.0.0.1
      Port: 80
      ```

      Klik **Create**.

  13. **Install LocalToNet Client**
      - Unduh dan instal aplikasi LocalToNet Client di perangkat Anda melalui situs resmi <a href="https://localtonet.com/download" target="_blank">LocalToNet</a>.

  14. **Ekstrak dan Jalankan localtonet.exe**
      - Ekstrak berkas, kemudian jalankan `localtonet.exe`. Setelah dijalankan, Command Prompt akan muncul.

  15. **Buka Halaman User Token**
      - Akses Halaman <a href="https://localtonet.com/usertoken" target="_blank">User Token</a> menggunakan browser.

  16. **Salin Token Default**
      - Salin token, kemudian tempelkan di Command Prompt dan tekan **Enter**.

  17. **Tunggu Status Tersambung**
      - Pastikan **Session Status** menjadi 'Connected'.

  18. **Buka Halaman Tunnel di LocalToNet**
      - Akses Halaman <a href="https://localtonet.com/tunnel/http" target="_blank">Tunnel</a>, lalu klik tombol **Start** pada Tunnel yang dibuat.

  19. **Tunggu Status Menjadi OK**
      - Pastikan **Status** pada Command Prompt menjadi 'OK'.

  20. **Uji Halaman Web**

      **Contoh URL:**
      ```bash
      https://edvisorfilkomub.localto.net/
      ```

  21. **Uji dengan Perangkat dan Jaringan Berbeda**  
      - Pastikan aplikasi Edvisor dapat diakses dari berbagai perangkat dan jaringan untuk memastikan kompatibilitas serta kestabilan akses publik.

  22. **Pastikan Internet Stabil**
      - Pastikan koneksi internet Anda stabil agar akses pengguna tidak terganggu.

  23. **Matikan Proteksi Akses Web pada Antivirus (Opsional)**
      - Apabila URL diblokir, matikan proteksi akses web pada antivirus pengguna.
</details>