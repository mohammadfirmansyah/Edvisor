## 📢 Release Notes

### v1.2.1-hotfix.2

**Perbaikan yang Telah Dilakukan:**

1. **Perbaikan Format Pesan Error di SweetAlert2**
   - **Konsistensi dan Bahasa yang Lebih Sopan**  
     Memperbaiki format pesan error saat SweetAlert2 tampil agar menjadi lebih konsisten dan menggunakan bahasa Indonesia yang lebih sopan. Perubahan ini bertujuan untuk memberikan pengalaman pengguna yang lebih baik dan komunikasi yang lebih jelas saat terjadi kesalahan.

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