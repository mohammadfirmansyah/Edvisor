<div align="center">
  <h1><b>Edvisor Berbasis Website</b></h1>

  ![Edvisor Logo](assets/img/logo.png)

  <!-- <a href="https://github.com/mohammadfirmansyah/Edvisor/releases" target="_blank" style="text-decoration: none !important;"><img src="https://img.shields.io/github/v/release/mohammadfirmansyah/Edvisor?include_prereleases&label=Pre-release&style=for-the-badge&color=lightgray" alt="Pre-release"></a> -->
  <a href="https://github.com/mohammadfirmansyah/Edvisor/releases/latest" target="_blank" style="text-decoration: none !important;"><img src="https://img.shields.io/github/v/release/mohammadfirmansyah/Edvisor?style=for-the-badge" alt="Release"></a>
</div>

<h2 id="daftar-isi">📋 Daftar Isi</h2>

- [Deskripsi](#deskripsi)
  - [Format Penomoran Versi](#format-penomoran-versi)
- [Fitur](#fitur)
  - [Fitur yang Telah Diimplementasikan](#fitur-yang-telah-diimplementasikan)
  - [Fitur yang Belum Diimplementasikan](#fitur-yang-belum-diimplementasikan)
- [Instalasi](#instalasi)
  - [Instalasi menggunakan localhost](#instalasi-menggunakan-localhost)
  - [Instalasi menggunakan localTunnel](#instalasi-menggunakan-localtunnel)
  - [Instalasi menggunakan localToNet](#instalasi-menggunakan-localtonet)
- [Kontribusi](#kontribusi)
- [Dokumentasi](#dokumentasi)
  - [User Flow dan Class Diagram](#user-flow-dan-class-diagram)
  - [Screenshot Aplikasi](#screenshot-aplikasi)
- [Kredit](#kredit)
- [Lisensi](#lisensi)

<h2 id="deskripsi">📖 Deskripsi</h2>

Edvisor adalah aplikasi berbasis web yang dirancang untuk memfasilitasi pelaksanaan *lesson study* bagi mahasiswa dan supervisor. Aplikasi ini memungkinkan pengguna untuk membuat kelas guru model, mengundang anggota lain sebagai *observer*, serta bergabung ke kelas guru model menggunakan kode kelas. Fitur utama Edvisor meliputi pengisian formulir penilaian kegiatan mengajar, lembar pengamatan siswa, serta catatan aktivitas siswa yang dapat disimpan dan diunduh oleh guru model.

### Format Penomoran Versi

Untuk mengelola pengembangan dan pembaruan Edvisor secara sistematis, kami menerapkan <a href="https://semver.org/" target="_blank">Semantic Versioning</a>. Metode ini memudahkan dalam melacak perubahan, kompatibilitas, dan rilis fitur baru. Berikut adalah rincian format penomoran versi yang kami gunakan:

| Komponen       | Keterangan                                                                                                                                                                                                                                                                                                                                                                              |
|----------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Digit 1**  | **Versi Major**: Menandakan perubahan besar yang mungkin tidak kompatibel dengan versi sebelumnya (breaking changes). Contohnya termasuk perubahan arsitektur atau penghapusan fitur lama.                                                                                                                                                                                                   |
| **Digit 2**  | **Versi Minor**: Menunjukkan penambahan fitur baru yang kompatibel dengan versi sebelumnya. Perubahan ini meningkatkan fungsionalitas tanpa mengganggu kestabilan sistem.                                                                                                                                                                                                                  |
| **Digit 3**  | **Versi Patch**: Menandakan perbaikan bug kecil atau peningkatan keamanan yang tidak mempengaruhi fungsionalitas utama aplikasi.                                                                                                                                                                                                                                                               |
| **Label Prerelease** | Menandakan status pengembangan versi yang belum stabil. Terdapat empat kategori utama:<br>- **Alpha**: Versi awal untuk pengujian internal.<br>- **Beta**: Versi setelah alpha yang siap diuji oleh kelompok pengguna terbatas.<br>- **Release Candidate (RC)**: Versi yang hampir stabil, menunggu pengujian akhir.<br>- **Release**: Versi stabil yang siap digunakan secara luas. |
| **Label Khusus** | Menandakan rilis khusus dalam konteks pengembangan seperti:<br>- **Hotfix**: Perbaikan cepat pada masalah kritis di versi produksi tanpa menunggu rilis berikutnya.<br>- **Security Patch**: Tambalan keamanan untuk mengatasi kerentanan.<br>- **Maintenance Release**: Rilis perawatan rutin untuk meningkatkan stabilitas dan kinerja.<br>- **Rollback**: Rilis yang mengembalikan perubahan karena adanya masalah serius. |
| **Nomor** | Menandakan urutan iterasi dari setiap rilis (prerelease atau label khusus). Contoh: `.1`, `.2`, `.3`, dll. |

**Contoh Penomoran Versi:**

- `1.0.0-alpha.1`: Versi awal dengan perubahan besar yang masih dalam tahap pengembangan pertama.
- `1.0.0-alpha.2`: Pembaruan pada versi alpha pertama.
- `1.0.0-beta.1`: Versi beta pertama setelah alpha, siap untuk pengujian lebih luas.
- `1.1.0-beta.2`: Penambahan fitur baru pada versi minor dengan revisi beta kedua.
- `1.1.0-rc.1`: Release Candidate pertama sebelum rilis final.
- `1.1.0`: Rilis final yang stabil dan siap digunakan secara luas.
- `1.1.0-hotfix.1`: Rilis khusus hotfix untuk memperbaiki masalah kritis pada versi 1.1.0.
- `2.0.0`: Versi major kedua dengan perubahan signifikan dan kemungkinan tidak kompatibel dengan versi sebelumnya.

**Panduan Penerapan Penomoran Versi:**

1. **Penambahan Fitur Baru:**
   - Tingkatkan **Minor** versi.
   - Contoh: Dari `1.0.0` menjadi `1.1.0`.

2. **Perbaikan Bug atau Peningkatan Kecil:**
   - Tingkatkan **Patch** versi.
   - Contoh: Dari `1.1.0` menjadi `1.1.1`.

3. **Perubahan Besar atau Tidak Kompatibel:**
   - Tingkatkan **Major** versi.
   - Contoh: Dari `1.1.0` menjadi `2.0.0`.

4. **Rilis Prerelease (Alpha, Beta, RC):**
   - Tambahkan label prerelease dengan nomor urut.
   - Contoh: `1.1.0-beta.1`, `1.1.0-beta.2`, hingga `1.1.0-rc.1`.

5. **Rilis Label Khusus (Hotfix, Security Patch, dsb.):**
   - Tambahkan label khusus dengan nomor urut.
   - Contoh: `1.1.0-hotfix.1`, `1.1.0-securitypatch.1`.

6. **Rilis Final:**
   - Hilangkan label prerelease atau label khusus setelah mencapai stabilitas.
   - Contoh: Dari `1.1.0-rc.1` menjadi `1.1.0`.

Dengan mengikuti format penomoran versi ini, kami memastikan bahwa setiap rilis Edvisor dapat dilacak dengan jelas, memudahkan pengguna dan pengembang dalam memahami tingkat perubahan dan kompatibilitas antar versi.

<h2 id="fitur">🚀 Fitur</h2>

<h3 id="fitur-yang-telah-diimplementasikan">✅ Fitur yang Telah Diimplementasikan</h3>

1. **Pembuatan Kelas Lesson Study**  
   Memungkinkan pengguna untuk membuat kelas khusus untuk *lesson study*.
   
2. **Penentuan Jadwal Lesson Study**  
   Menetapkan jadwal pelaksanaan *lesson study* saat pembuatan kelas.
   
3. **Penambahan Observer**  
   Menambahkan anggota *observer* saat membuat kelas.
   
4. **Penentuan Siswa yang Diamati**  
   Memilih siswa yang akan diamati oleh *observer*.
   
5. **Daftar Kelas Guru Model**  
   Menampilkan daftar kelas yang dibuat oleh guru model.
   
6. **Pengunggahan Berkas Kegiatan**  
   Mengunggah dokumen yang diperlukan untuk kegiatan *lesson study*.
   
7. **Penayangan Hasil Rekaman**  
   Melihat hasil rekaman kegiatan mengajar.
   
8. **Pembagian Kode Kelas**  
   Membagikan kode kelas untuk bergabung ke kelas *lesson study*.
   
9. **Daftar Kelas Observer**  
   Menampilkan daftar kelas yang diikuti oleh *observer*.
   
10. **Bergabung dengan Kelas Menggunakan Kode**  
    Memungkinkan pengguna untuk bergabung ke kelas dengan memasukkan kode kelas.
    
11. **Pelaksanaan Observasi dan Penilaian**  
    Mengisi formulir observasi dan penilaian kegiatan mengajar.
    
12. **Hasil Penilaian**  
    Melihat hasil penilaian yang telah diisi oleh *observer*.
    
13. **Pengunduhan Berkas Kegiatan**  
    Mengunduh dokumen yang diperlukan untuk kegiatan *lesson study*.
    
14. **Catatan dan Dokumentasi**  
    Melihat hasil catatan serta dokumentasi kegiatan.
    
15. **Akses Bantuan Penggunaan Aplikasi**  
    Menyediakan panduan penggunaan aplikasi.
    
16. **Profil Pengguna**  
    Melihat dan mengubah profil pengguna.
    
17. **Kompatibilitas Akses Publik**  
    Memungkinkan akses publik ke aplikasi Edvisor yang berjalan di jaringan pribadi pengembang menggunakan <a href="https://localtunnel.github.io/www/" target="_blank">localTunnel</a> dan <a href="https://localtonet.com/" target="_blank">LocalToNet</a>.

<h3 id="fitur-yang-belum-diimplementasikan">🕒 Fitur yang Belum Diimplementasikan</h3>

1. **Desain Antarmuka Responsif**  
   Meningkatkan tampilan agar responsif di berbagai perangkat.
   
2. **Opsi Pendaftaran untuk Supervisor**  
   Menambahkan fitur pendaftaran khusus untuk supervisor.
   
3. **Seleksi Login Berdasarkan Peran**  
   Menyediakan opsi login yang berbeda untuk akun supervisor dan mahasiswa.
   
4. **Halaman Penilaian Khusus Supervisor**  
   Membuat halaman penilaian yang eksklusif untuk supervisor.
   
5. **Formulir Penilaian untuk Supervisor**  
   Menambahkan formulir khusus bagi supervisor untuk melakukan penilaian.
   
6. **Penyesuaian Formulir Penilaian**  
   Mengadaptasi formulir penilaian dengan format terkini untuk supervisor dan mahasiswa.
   
7. **Penghapusan Input Dummy**  
   Menghapus input dummy seperti rekaman suara, file dokumentasi, dan catatan khusus dari controller.
   
8. **Integrasi API dengan Aplikasi Mobile**  
   Menghubungkan API Edvisor berbasis web dengan aplikasi mobile.
   
9. **Pengamanan CSRF**  
    Menambahkan mekanisme CSRF untuk meningkatkan keamanan sistem.

10. **Konfigurasi Pesan Logging**  
    Melakukan konfigurasi pesan logging di dalam controller dan model untuk memudahkan troubleshooting dan audit.

<h2 id="instalasi">🛠 Instalasi</h2>

### Instalasi menggunakan localhost

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

### Instalasi menggunakan localTunnel

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

### Instalasi menggunakan LocalToNet

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
    SubDomain: edvisorfilkomub.localto.net
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

<h2 id="kontribusi">🤝 Kontribusi</h2>

Kontribusi untuk pengembangan Edvisor sangat kami hargai. Berikut adalah panduan untuk berkontribusi:

1. **Akses Controller**  
   Buka direktori `Edvisor/application/controllers/` untuk mengakses dan mengedit controller.

2. **Akses Model**  
   Buka direktori `Edvisor/application/models/` untuk mengakses dan mengedit model.

3. **Akses View**  
   Buka direktori `Edvisor/application/view/` untuk mengakses dan mengedit tampilan.

4. **Akses Assets**  
   Buka direktori `Edvisor/assets/` untuk mengakses dan mengelola aset seperti gambar, CSS, dan JavaScript.

5. **Menampilkan console.log**  
   - Buka direktori `Edvisor/assets/js/hideConsole.js`
   - Ubah nilai variabel:

     ```javascript
     const hideLogs = true;
     ```

     Menjadi:

     ```javascript
     const hideLogs = false;
     ```
   
   Dengan perubahan ini, pesan `console.log` akan muncul pada console browser untuk memudahkan debugging.

<h2 id="dokumentasi">📚 Dokumentasi</h2>

### User Flow dan Class Diagram

Kami telah menyediakan dokumentasi visual untuk memudahkan pemahaman alur pengguna dan struktur kelas dalam aplikasi:

#### User Flow

![User Flow](screenshot/User%20Flow.png)

**Penjelasan User Flow:**

User Flow pada Edvisor menggambarkan alur interaksi pengguna dalam produk ini dengan dua peran utama: **Observer** dan **Guru Model**. Berikut adalah langkah-langkah interaksi pengguna:

1. **Memulai Interaksi:**
   - Pengguna membuka aplikasi Edvisor.
   - Pengguna diberikan pilihan untuk **Mendaftar Akun** atau **Login**.

2. **Proses Pendaftaran atau Login:**
   - **Mendaftar Akun:**
     - Jika memilih **Mendaftar Akun**, pengguna akan melalui proses pendaftaran terlebih dahulu.
     - Setelah pendaftaran berhasil, pengguna diarahkan untuk melakukan **Login**.
   - **Login:**
     - Jika pengguna sudah memiliki akun, mereka dapat langsung memilih opsi **Login**.
     - Setelah berhasil login, pengguna diarahkan ke **Halaman Akses Beranda**.

3. **Akses Beranda:**
   - Di **Halaman Beranda**, pengguna memiliki beberapa opsi:
     - Memilih peran sebagai **Guru Model**.
     - Memilih peran sebagai **Observer**.
     - Memilih opsi **Bantuan** untuk mendapatkan panduan lebih lanjut.
   - **Opsi Bantuan:**
     - Jika memilih **Bantuan**, pengguna akan menerima panduan yang diperlukan.
     - Setelah menerima panduan, proses berakhir.

4. **Pemilihan Peran:**
   - Pengguna memilih untuk menjadi **Guru Model** atau **Observer**.
   
   - **Sebagai Guru Model:**
     1. **Membuat Kelas Guru Model:**
        - Mengisi detail kelas.
        - Mengunggah berkas yang relevan.
        - Memilih **Observer** yang akan berpartisipasi.
        - Menentukan nomor siswa untuk kelas tersebut.
        - Menyimpan formulir yang telah diisi.
        - Setelah menyimpan, pengguna kembali ke **Halaman Beranda**.
     2. **Lihat Hasil Observasi:**
        - Memilih kelas yang diinginkan.
        - Melihat hasil observasi yang dilakukan oleh **Observer**.
        - Mengunduh formulir observasi jika diperlukan.
        - Proses ini berakhir setelah hasil observasi dilihat.
   
   - **Sebagai Observer:**
     1. **Gabung Kelas Guru Model:**
        - Memasukkan **kode kelas** yang diberikan oleh **Guru Model**.
        - Memilih nomor siswa yang sesuai.
        - Menyimpan formulir tersebut.
        - Setelah menyimpan, pengguna kembali ke **Halaman Beranda**.
     2. **Observasi Kelas:**
        - Memilih kelas yang ingin diobservasi.
        - Mengisi formulir pengamatan yang relevan.
        - Menyimpan formulir pengamatan.
        - Proses ini berakhir setelah formulir disimpan.

Dengan alur ini, Edvisor memastikan bahwa kedua peran utama dapat berinteraksi secara efektif dalam mendukung pelaksanaan *lesson study*.

#### Class Diagram

![Class Diagram](screenshot/Class%20Diagram.png)

**Penjelasan Class Diagram:**

Class Diagram pada Edvisor menggambarkan struktur data dan hubungan antar berbagai entitas dalam sistem. Berikut adalah penjelasan detail mengenai hubungan antar kelas:

1. **Users dan ci_sessions**
   - **Hubungan:** Users "1" --> "0..*" ci_sessions : has sessions
   - **Alasan:** Setiap pengguna (**Users**) dapat memiliki banyak sesi (**ci_sessions**). Ini memungkinkan sistem untuk melacak dan mengelola sesi pengguna secara individual, seperti login dan aktivitas pengguna.

2. **Users dan Classes**
   - **Hubungan:** Users "1" --> "0..*" Classes : creates classes
   - **Alasan:** Seorang pengguna (**Users**) yang berperan sebagai Guru Model dapat membuat banyak kelas (**Classes**). Kolom `creator_user_id` dalam tabel **Classes** merupakan foreign key yang merujuk ke `user_id` di tabel **Users**, yang menunjukkan siapa yang membuat kelas tersebut.

3. **Classes dan ClassObservers**
   - **Hubungan:** Classes "1" --> "0..*" ClassObservers : has observers
   - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak pengamat (**ClassObservers**). Ini memungkinkan kelas tersebut untuk diikuti atau diamati oleh berbagai pengguna yang berperan sebagai pengamat.

4. **Users dan ClassObservers**
   - **Hubungan:** Users "1" --> "0..*" ClassObservers : becomes observer
   - **Alasan:** Seorang pengguna (**Users**) dapat menjadi pengamat (**ClassObservers**) untuk banyak kelas (**Classes**). Ini memungkinkan satu pengguna untuk mengamati berbagai kelas yang berbeda.

5. **Classes dan Notifications**
   - **Hubungan:** Classes "1" --> "0..*" Notifications : triggers notifications
   - **Alasan:** Setiap kelas (**Classes**) dapat memicu banyak notifikasi (**Notifications**). Notifikasi ini dapat berkaitan dengan berbagai aktivitas atau perubahan dalam kelas, seperti penambahan pengamat baru atau pembaruan materi pengajaran.

6. **Users dan Notifications**
   - **Hubungan:** Users "1" --> "0..*" Notifications : sends & receives notifications
   - **Alasan:** Seorang pengguna (**Users**) dapat mengirim dan menerima banyak notifikasi (**Notifications**). Kolom `sender_id` dan `receiver_id` dalam tabel **Notifications** masing-masing merujuk ke `user_id` di tabel **Users**, menunjukkan siapa yang mengirim dan menerima notifikasi tersebut.

7. **ClassObservers dan ObservedStudents**
   - **Hubungan:** ClassObservers "1" --> "0..*" ObservedStudents : observes students
   - **Alasan:** Setiap entri dalam **ClassObservers** (kombinasi antara kelas dan pengamat) dapat memiliki banyak siswa yang diamati (**ObservedStudents**). Ini menghubungkan pengamat dengan siswa yang mereka amati dalam kelas tertentu, memungkinkan pencatatan pengamatan secara terperinci.

8. **Classes dan TeachingActivityAssessment**
   - **Hubungan:** Classes "1" --> "0..*" TeachingActivityAssessment : has
   - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak penilaian kegiatan mengajar (**TeachingActivityAssessment**). Ini memungkinkan pengamatan dan penilaian berbagai aspek pengajaran dalam satu kelas.

9. **ClassObservers dan TeachingActivityAssessment**
   - **Hubungan:** ClassObservers "1" --> "1" TeachingActivityAssessment : assesses
   - **Alasan:** Setiap pengamat dalam **ClassObservers** dapat melakukan satu penilaian kegiatan mengajar (**TeachingActivityAssessment**) untuk kelas yang diamatinya.

10. **Classes dan StudentObservationSheet**
    - **Hubungan:** Classes "1" --> "0..*" StudentObservationSheet : has
    - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak lembar pengamatan siswa (**StudentObservationSheet**). Ini memungkinkan dokumentasi pengamatan siswa oleh pengamat secara sistematis.

11. **ClassObservers dan StudentObservationSheet**
    - **Hubungan:** ClassObservers "1" --> "1" StudentObservationSheet : observes
    - **Alasan:** Setiap pengamat dalam **ClassObservers** dapat mengisi satu lembar pengamatan siswa (**StudentObservationSheet**) untuk kelas yang diamatinya.

12. **StudentObservationSheet dan StudentObservationDetails**
    - **Hubungan:** StudentObservationSheet "1" --> "0..*" StudentObservationDetails : has
    - **Alasan:** Setiap lembar pengamatan siswa (**StudentObservationSheet**) dapat memiliki banyak detail pengamatan siswa (**StudentObservationDetails**). Ini memungkinkan pengamatan rinci terhadap berbagai indikator untuk setiap siswa.

13. **Classes dan StudentActivityNotes**
    - **Hubungan:** Classes "1" --> "0..*" StudentActivityNotes : has
    - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak catatan aktivitas siswa (**StudentActivityNotes**). Catatan ini dapat mencakup berbagai aktivitas atau observasi yang dilakukan selama kelas berlangsung.

14. **ClassObservers dan StudentActivityNotes**
    - **Hubungan:** ClassObservers "1" --> "1" StudentActivityNotes : notes
    - **Alasan:** Setiap pengamat dalam **ClassObservers** dapat membuat satu catatan aktivitas siswa (**StudentActivityNotes**) untuk kelas yang diamatinya.

15. **Classes dan ClassVoiceRecordings**
    - **Hubungan:** Classes "1" --> "0..*" ClassVoiceRecordings : has
    - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak rekaman suara (**ClassVoiceRecordings**). Rekaman ini dapat digunakan untuk mendokumentasikan aktivitas atau diskusi selama kelas.

16. **ClassObservers dan ClassVoiceRecordings**
    - **Hubungan:** ClassObservers "1" --> "1" ClassVoiceRecordings : notes
    - **Alasan:** Setiap pengamat dalam **ClassObservers** dapat membuat satu rekaman suara (**ClassVoiceRecordings**) untuk kelas yang diamatinya.

17. **Classes dan SpecialNotes**
    - **Hubungan:** Classes "1" --> "0..*" SpecialNotes : has
    - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak catatan khusus (**SpecialNotes**). Catatan ini dapat mencakup hal-hal spesifik atau kejadian unik yang terjadi selama kelas berlangsung.

18. **ClassObservers dan SpecialNotes**
    - **Hubungan:** ClassObservers "1" --> "0..*" SpecialNotes : notes
    - **Alasan:** Setiap pengamat dalam **ClassObservers** dapat membuat banyak catatan khusus (**SpecialNotes**) untuk kelas yang diamatinya. Ini memungkinkan pengamat untuk mencatat hal-hal spesifik yang mereka amati dalam kelas.

19. **Classes dan ClassDocumentationFiles**
    - **Hubungan:** Classes "1" --> "0..*" ClassDocumentationFiles : documents
    - **Alasan:** Setiap kelas (**Classes**) dapat memiliki banyak file dokumentasi (**ClassDocumentationFiles**). File-file ini dapat berisi materi pengajaran, dokumentasi kegiatan, atau materi lainnya yang relevan dengan kelas.

20. **ClassObservers dan ClassDocumentationFiles**
    - **Hubungan:** ClassObservers "1" --> "0..*" ClassDocumentationFiles : documents
    - **Alasan:** Setiap pengamat dalam **ClassObservers** dapat mengunggah banyak file dokumentasi (**ClassDocumentationFiles**) untuk kelas yang diamatinya. Ini memungkinkan dokumentasi aktivitas pengamat dalam kelas secara terorganisir.

Dengan pemahaman struktur dan hubungan antar kelas ini, pengembang dapat lebih mudah dalam melakukan pengembangan dan pemeliharaan aplikasi Edvisor.

<h3 id="screenshot-aplikasi">📸 Screenshot Aplikasi</h2>

Berikut adalah beberapa screenshot yang menggambarkan antarmuka dan fitur Edvisor:

1. **Login**  
   ![Login](screenshot/Login.jpeg)

2. **Beranda**  
   ![Beranda](screenshot/Beranda.jpeg)

3. **Kelas Guru Model**  
   ![Kelas Guru Model](screenshot/Kelas%20Guru%20Model.jpeg)

4. **Dokumentasi**  
   ![Dokumentasi](screenshot/Dokumentasi.jpeg)

5. **Preview Formulir**  
   ![Preview Formulir](screenshot/Preview%20Formulir.jpeg)

6. **Formulir**  
   ![Formulir](screenshot/Formulir.jpeg)

<h2 id="kredit">🎓 Kredit</h2>

### Pelopor Inovasi dalam Pengembangan Proyek

- **Nama:** Ir. Retno Indah Rokhmawati, S.Pd., M.Pd.

### Developer

- **Nama:** Mohammad Firman Syah  
- **Program Studi:** Pendidikan Teknologi Informasi  
- **NIM:** 205150600111011  
- **Dirilis:** 2024

### Desain Antarmuka

- **Nama:** Moch. Rizal Effendi  
- **Program Studi:** Pendidikan Teknologi Informasi  
- **NIM:** 156150601111003  
- **Dirilis:** 2023

<h2 id="lisensi">📄 Lisensi</h2>

Edvisor adalah perangkat lunak tertutup (*Closed Source Software*). Semua hak cipta dan hak kekayaan intelektual lainnya dilindungi.

---

Dibuat dengan ❤️ menggunakan <a href="https://codeigniter.com/" target="_blank">CodeIgniter V3.13</a>