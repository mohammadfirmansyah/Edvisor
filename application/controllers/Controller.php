<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Autoload Composer dependencies
require_once APPPATH . '../vendor/autoload.php';

// TemplateProcessor digunakan untuk memanipulasi file template Word (docx) 
use PhpOffice\PhpWord\TemplateProcessor;

class Controller extends CI_Controller
{
  protected $user;

  /**
   * Konstruktor Controller
   *
   * Menginisialisasi helper, library, model, dan konfigurasi enkripsi.
   * Mengecek apakah pengguna sudah login dan mengatur data pengguna untuk semua view.
   */
  public function __construct()
  {
    parent::__construct();

    // Memuat helper yang diperlukan
    $this->load->helper([
      'url',
      'form',
      'file',
      'date',
      'download',
      'cookie',
      'security',
      'notification',
      'indonesian_date'
    ]);

    // Memuat library yang diperlukan
    $this->load->library([
      'session',
      'form_validation',
      'email',
      'upload',
      'encryption',
      'zip'
    ]);

    // Memuat model-model yang diperlukan
    $this->load->model([
      'User',
      'ClassModel',
      'ClassObserver',
      'Notification',
      'TeachingActivityAssessment',
      'StudentObservationDetail',
      'StudentObservationSheet',
      'StudentActivityNote',
      'SpecialNote',
      'ClassDocumentationFile',
      'ClassVoiceRecording',
      'ObservedStudent',
      'SessionModel'
    ]);

    // Mendaftarkan handler error kustom
    set_error_handler([$this, 'custom_error_handler']);

    // Mengatur konfigurasi enkripsi
    $this->encryption->initialize([
      'cipher' => 'aes-256',
      'mode' => 'ctr',
      'key' => 'MOHAMMADFIRMANSYAH' // Pastikan untuk menyimpan kunci ini dengan aman
    ]);

    // Mendapatkan nama metode saat ini
    $current_method = $this->router->fetch_method();

    // Daftar metode yang dikecualikan dari pemeriksaan sesi
    $excluded_methods = [
      'sidebarLogout',
      'pageLogin',
      'formLogin',
      'pageSignup',
      'formSignup',
      'pageLupaPassword',
      'formLupaPassword',
      'index'
    ];

    // Memeriksa apakah pengguna sudah login
    $is_logged_in = $this->session->userdata('user_id') ? true : false;

    if ($is_logged_in) {
      if (in_array($current_method, $excluded_methods)) {
        // Menambahkan pengecekan khusus untuk 'sidebarLogout' dan 'index'
        if ($current_method !== 'sidebarLogout') {
          if ($current_method !== 'index') {
            // Pengguna sudah login dan mencoba mengakses halaman yang seharusnya hanya untuk pengguna belum login
            $this->session->set_flashdata('already_logged_in', 'Anda sudah login.');
          }
          // Redirect ke 'sidebarBeranda' tanpa mengatur flashdata
          redirect('sidebarBeranda');
        }
        // Jika metode adalah 'sidebarLogout' dan 'index', izinkan akses tanpa mengatur flashdata dan redirect
      } else {
        // Jika metode saat ini tidak termasuk dalam metode yang dikecualikan
        $this->checkLogin();
      }
    } else {
      // Jika pengguna belum login dan mencoba mengakses halaman yang memerlukan autentikasi
      if (!in_array($current_method, $excluded_methods)) {
        $this->checkLogin();
      }
    }
  }

  /**
   * Memeriksa apakah pengguna sudah login
   *
   * @return bool True jika pengguna sudah login, akan melakukan redirect jika belum login.
   */
  private function checkLogin()
  {
    if (!$this->session->userdata('user_id')) {
      // Cek apakah ada cookie "remember_me"
      $remember_token = get_cookie('remember_me');
      if ($remember_token) {
        // Mendapatkan pengguna berdasarkan token "remember_me"
        $user = $this->User->getUserByRememberToken($remember_token);
        if ($user) {
          // Cek apakah sudah ada sesi aktif di browser lain atau status 'wait'
          if ($user->current_session_id) {
            $existing_session = $this->SessionModel->getSessionById($user->current_session_id);
            if ($existing_session && ($existing_session->status === 'active' || $existing_session->status === 'wait')) {
              // Sesi aktif di browser lain atau status 'wait', tidak dapat login
              $this->session->set_flashdata('login_error', 'Akun Anda sudah digunakan di browser lain.');
              redirect('pageLogin');
            }
          }

          // Mulai transaksi untuk memastikan konsistensi data
          $this->db->trans_start();

          // Regenerasi session ID dan menghapus sesi lama
          $this->session->sess_regenerate(TRUE);

          // Mengatur session pengguna
          $this->session->set_userdata('user_id', $user->user_id);
          $this->session->set_userdata('email_address', $user->email_address);

          // Update session_id, last_activity, dan status di database
          $update_data = [
            'current_session_id' => session_id(),
            'last_activity' => date('Y-m-d H:i:s'),
            'status' => 'active' // Mengatur status sesi menjadi aktif
          ];
          $this->User->updateActivity($user->user_id, $update_data);

          // Memperbarui user_id dan status pada tabel ci_sessions menggunakan SessionModel
          $this->SessionModel->updateSession(session_id(), ['user_id' => $user->user_id, 'status' => 'active']);

          // Menghapus sesi dengan user_id null di tabel ci_sessions
          $this->SessionModel->deleteSessionsWithNullUserId();

          // Menghapus sesi lama yang tidak aktif untuk user ini
          $this->SessionModel->deleteInactiveSessionsByUserId($user->user_id);

          // Menghapus sesi lain yang aktif untuk user ini kecuali sesi saat ini
          $this->SessionModel->deleteOtherSessions($user->user_id, session_id());

          // Commit transaksi
          $this->db->trans_complete();

          if ($this->db->trans_status() === FALSE) {
            // Jika transaksi gagal, set flashdata dan redirect ke login
            $this->session->set_flashdata('login_error', 'Terjadi kesalahan saat memproses login. Silakan coba lagi.');
            redirect('pageLogin');
          }

          // Menyediakan data pengguna untuk semua view
          $this->user = $user;
          $this->load->vars(['user' => $user]);

          return true;
        }
      }
      // Set flashdata untuk pesan SweetAlert2
      $this->session->set_flashdata('login_required', 'Anda harus login sebelum mengakses halaman ini.');

      // Jika tidak ada session dan token, redirect ke login
      redirect('pageLogin');
    } else {
      // Jika pengguna sudah login, periksa sesi dan aktivitas
      $user_id = $this->session->userdata('user_id');
      $user = $this->User->getUserById($user_id);
      if ($user) {
        // Cek apakah sesi saat ini sesuai dengan sesi yang disimpan di database
        if ($user->current_session_id !== session_id()) {
          // Cek status sesi yang disimpan di database
          $existing_session = $this->SessionModel->getSessionById($user->current_session_id);
          if ($existing_session && ($existing_session->status === 'active' || $existing_session->status === 'wait')) {
            // Sesi aktif di browser lain atau status 'wait', tidak dapat login
            $this->session->set_flashdata('session_invalid', 'Sesi Anda sudah aktif di browser lain.');
            redirect('pageLogin'); // Redirect ke login tanpa logout
          } else {
            // Sesi sebelumnya tidak aktif atau status tidak 'active'/'wait', izinkan login dan perbarui sesi baru
            $this->db->trans_start();

            // Memperbarui aktivitas pengguna
            $this->User->updateActivity($user_id, ['current_session_id' => session_id(), 'status' => 'active']);
            $this->SessionModel->updateSession(session_id(), ['user_id' => $user_id, 'status' => 'active']);

            // Commit transaksi
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
              // Jika transaksi gagal, set flashdata dan redirect ke login
              $this->session->set_flashdata('session_invalid', 'Terjadi kesalahan saat memproses sesi. Silakan login kembali.');
              redirect('pageLogin');
            }
          }
        }

        // Cek aktivitas terakhir
        $current_time = new DateTime();
        if ($user->last_activity) {
          $last_activity = new DateTime($user->last_activity);
          $interval = $current_time->getTimestamp() - $last_activity->getTimestamp();

          // Batas waktu inaktivitas (30 menit = 1.800 detik)
          $timeout = 1800;

          if ($interval > $timeout) {
            // Inaktivitas melebihi batas, logout pengguna
            $this->session->set_flashdata('session_timeout', 'Sesi Anda telah habis karena inaktivitas.');
            redirect('sidebarLogout');
          } else {
            // Update waktu aktivitas terakhir dan status sesi menjadi aktif
            $this->User->updateActivity($user_id, ['last_activity' => date('Y-m-d H:i:s'), 'status' => 'active']);
          }
        } else {
          // Jika last_activity tidak ada, set default dan status aktif
          $this->User->updateActivity($user_id, ['last_activity' => date('Y-m-d H:i:s'), 'status' => 'active']);
        }

        // Memperbarui user_id dan status pada tabel ci_sessions menggunakan SessionModel
        $this->SessionModel->updateSession(session_id(), ['user_id' => $user_id, 'status' => 'active']);

        // Menyediakan variabel 'user' untuk semua view
        $this->user = $user;
        $this->load->vars(['user' => $user]);
      } else {
        // Pengguna tidak ditemukan, logout
        redirect('sidebarLogout');
      }
    }
    return true;
  }

  /**
   * Memperbarui Aktivitas Pengguna secara Real-time
   *
   * Fungsi ini dipanggil melalui AJAX untuk memperbarui waktu aktivitas terakhir pengguna dan status sesi.
   */
  public function updateActivity()
  {
    // Mendapatkan data POST
    $status = $this->input->post('status');

    if ($this->session->userdata('user_id')) {
      $user_id = $this->session->userdata('user_id');

      // Memperbarui waktu aktivitas terakhir dan status sesi
      $update_data = [
        'last_activity' => date('Y-m-d H:i:s'),
        'status' => $status === 'active' ? 'active' : ($status === 'wait' ? 'wait' : 'inactive')
        // Mengatur status berdasarkan input 'active', 'wait', atau lainnya menjadi 'inactive'
      ];
      $this->User->updateActivity($user_id, $update_data);

      // Memperbarui user_id dan status pada tabel ci_sessions menggunakan SessionModel
      $session_update_data = [
        'user_id' => $user_id,
        'status' => $status === 'active' ? 'active' : ($status === 'wait' ? 'wait' : 'inactive')
      ];
      $this->SessionModel->updateSession(session_id(), $session_update_data);

      // Mengembalikan respon sukses
      echo json_encode(['status' => 'success']);
    } else {
      // Jika sesi tidak valid, kembalikan pesan error
      echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    }
  }

  /**
   * Fungsi Index
   *
   * Mengarahkan pengguna ke beranda jika sudah login, atau ke halaman login jika belum.
   */
  public function index()
  {
    if ($this->session->userdata('user_id')) {
      // Pengguna sudah login, arahkan ke beranda
      redirect('sidebarBeranda');
    } else {
      // Pengguna belum login, arahkan ke halaman login
      redirect('pageLogin');
    }
  }

  /**
   * Menampilkan Profil Pengguna
   *
   * Menampilkan halaman profil pengguna yang sedang login.
   */
  public function sidebarProfile()
  {
    $this->checkLogin();

    $user_id = $this->session->userdata('user_id');

    // Mendapatkan data pengguna terbaru
    $user = $this->User->getUserById($user_id);

    // Memperbarui data pengguna di session dan variabel global
    if ($user) {
      $this->user = $user;
      $this->load->vars(['user' => $user]);
    }

    $data["title"] = "Profil";
    $data["user"] = $user; // Memastikan data terbaru dikirim ke view
    $this->load->view('Profil', $data);
  }

  /**
   * Memperbarui Data Pengguna
   *
   * Memproses form pembaruan data pengguna, termasuk pengunggahan foto profil.
   */
  public function formPerbaruiDataPengguna()
  {
    $this->checkLogin();

    $user_id = $this->session->userdata('user_id');

    // Mendapatkan input dari formulir
    $full_name = $this->input->post('full_name');
    $email_address = $this->input->post('email_address');
    $registration_number = $this->input->post('registration_number');

    // Validasi input (contoh sederhana)
    if (empty($full_name) || empty($email_address)) {
      $this->session->set_flashdata('error', 'Nama lengkap dan email tidak boleh kosong.');
      redirect('sidebarProfile');
    }

    // Mendapatkan waktu saat ini untuk 'updated_at'
    $current_datetime = date('Y-m-d H-i-s');
    $data = array(
      'full_name'           => $full_name,
      'email_address'       => $email_address,
      'registration_number' => $registration_number,
      'updated_at'          => $current_datetime
    );

    // Format tanggal untuk penamaan file (ganti ':' dengan '-')
    $formatted_datetime = str_replace(':', '-', $current_datetime);

    // Tentukan direktori upload baru
    $upload_dir = 'assets/media/fotoProfil/';

    // Cek apakah ada 'croppedImage' yang dikirim
    $cropped_image = $this->input->post('croppedImage');

    if (!empty($cropped_image)) {
      // Decode base64 dan simpan sebagai file gambar
      $data_uri = $cropped_image;
      // Pisahkan header data URI
      list($type, $data_encoded) = explode(';', $data_uri);
      list(, $data_encoded)      = explode(',', $data_encoded);
      // Decode data
      $data_decoded = base64_decode($data_encoded);

      // Tentukan nama file sesuai format baru
      $file_name = $user_id . '_FotoProfil_' . $formatted_datetime . '.png';
      $file_path = $upload_dir . $file_name;

      // Pastikan direktori tujuan ada
      if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      // Simpan file ke direktori yang diinginkan
      if (file_put_contents($file_path, $data_decoded)) {
        // Jika berhasil, tambahkan path ke data yang akan diperbarui
        $data_update = array(
          'src_profile_photo' => $file_path
        );

        // Gabungkan dengan data utama
        $data = array_merge($data, $data_update);
      } else {
        $this->session->set_flashdata('error', 'Gagal menyimpan foto profil.');
        redirect('sidebarProfile');
      }
    } elseif (!empty($_FILES['profile_photo']['name'])) {
      // Jika tidak ada 'croppedImage', cek apakah ada file yang diupload
      $config['upload_path']   = './' . $upload_dir;
      $config['allowed_types'] = 'gif|jpg|png';
      $config['file_name']     = $user_id . '_fotoProfil_' . $formatted_datetime;
      $config['overwrite']     = true;
      $config['max_size']      = 1024; // 1MB

      $this->load->library('upload', $config);

      if ($this->upload->do_upload('profile_photo')) {
        $upload_data = $this->upload->data();
        $data['src_profile_photo'] = $upload_dir . $upload_data['file_name'];
      } else {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect('sidebarProfile');
      }
    }

    // Memperbarui data pengguna
    if ($this->User->updateUser($user_id, $data)) {
      $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');

      // Setelah data berhasil diperbarui, hapus file lama
      if (isset($data['src_profile_photo'])) {
        $current_photo = $data['src_profile_photo'];

        // Cari semua file yang cocok dengan pola email_fotoProfil_*
        $pattern = $upload_dir . $user_id . '_FotoProfil_*.*';
        $files = glob($pattern);

        if ($files !== false) {
          foreach ($files as $file) {
            // Jika file bukan yang baru diupload, hapus
            if ($file !== $current_photo) {
              if (is_file($file)) {
                unlink($file);
              }
            }
          }
        }

        // Memperbarui data pengguna di session dan variabel global
        $user = $this->User->getUserById($user_id);
        if ($user) {
          $this->user = $user;
          $this->load->vars(['user' => $user]);
        }
      }
    } else {
      $this->session->set_flashdata('error', 'Gagal memperbarui data pengguna.');
    }

    redirect('sidebarProfile');
  }

  /**
   * Memperbarui Password Pengguna
   *
   * Memproses form pembaruan password pengguna, termasuk validasi dan penyimpanan password baru.
   */
  public function formPerbaruiPasswordPengguna()
  {
    $this->checkLogin();

    $user_id = $this->session->userdata('user_id');

    // Mendapatkan input dari formulir
    $current_password = $this->input->post('current_password');
    $new_password     = $this->input->post('new_password');
    $confirm_password = $this->input->post('confirm_password');

    // Validasi input dasar
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
      $this->session->set_flashdata('error', 'Semua bidang harus diisi.');
      redirect('sidebarProfile');
    }

    if ($new_password !== $confirm_password) {
      $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
      redirect('sidebarProfile');
    }

    // Mendapatkan data pengguna
    $user = $this->User->getUserById($user_id);

    if (!$user) {
      $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
      redirect('sidebarProfile');
    }

    if (password_verify($current_password, $user->password)) {
      // Memperbarui password
      $data = array(
        'password'   => password_hash($new_password, PASSWORD_BCRYPT),
        'updated_at' => date('Y-m-d H:i:s')
      );

      if ($this->User->updateUser($user_id, $data)) {
        $this->session->set_flashdata('success', 'Password berhasil diperbarui.');

        // Memperbarui data pengguna di session dan variabel global
        $user = $this->User->getUserById($user_id);
        if ($user) {
          $this->user = $user;
          $this->load->vars(['user' => $user]);
        }
      } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui password.');
      }
    } else {
      $this->session->set_flashdata('error', 'Password saat ini salah.');
    }

    redirect('sidebarProfile');
  }

  /**
   * Menampilkan Beranda
   *
   * Menampilkan halaman beranda dengan berbagai informasi terkait kelas, notifikasi, dan pengguna.
   */
  public function sidebarBeranda()
  {
    // Memeriksa apakah pengguna sudah login
    $this->checkLogin();

    // Mengambil ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Mendapatkan jumlah kelas Guru Model yang dibuat oleh pengguna
    $num_classes_created = $this->ClassModel->countClassesByCreator($user_id);

    // Mendapatkan jumlah kelas Observer yang diikuti oleh pengguna
    $num_classes_observed = $this->ClassObserver->countClassesByObserver($user_id);

    // Menghitung jumlah Lesson Study sebagai kombinasi dari Guru Model dan Observer
    $num_lesson_study = $num_classes_created + $num_classes_observed;

    // Mendapatkan jadwal terdekat (kelas yang diikuti sebagai Guru Model atau Observer)
    $upcoming_classes = $this->ClassModel->getUpcomingClasses($user_id);
    $nearest_class = !empty($upcoming_classes) ? $upcoming_classes[0] : null;

    // Jika ada kelas terdekat, ambil data Observer terbaru (maksimal 4) dan foto Guru Model
    if ($nearest_class) {
      // Mendapatkan 4 Observer terbaru
      $latest_observers = $this->ClassObserver->getLatestObservers($nearest_class->class_id, 4);
      $nearest_class->latest_observers = $latest_observers;

      // Mendapatkan foto profil Guru Model
      $guru_model = $this->User->getUserById($nearest_class->creator_user_id);
      $nearest_class->guru_model_photo = !empty($guru_model->src_profile_photo) ? $guru_model->src_profile_photo : 'assets/default/default_profile_picture.jpg';

      // Enkripsi ID Kelas menggunakan bin2hex dan encryption library
      $encrypted_class_id = $this->encryption->encrypt($nearest_class->class_id);
      $nearest_class->encrypted_class_id = bin2hex($encrypted_class_id);
    }

    // Mendapatkan semua notifikasi yang diterima oleh pengguna
    $notifications_all = $this->Notification->getNotificationsByReceiver($user_id);

    // Mengurutkan notifikasi berdasarkan 'updated_at' secara descending
    usort($notifications_all, function ($a, $b) {
      $timeA = strtotime($a->updated_at);
      $timeB = strtotime($b->updated_at);
      if ($timeA == $timeB) {
        return 0;
      }
      return ($timeA > $timeB) ? -1 : 1;
    });

    // Mengambil 4 notifikasi terbaru untuk tampilan utama
    $notifications_latest = array_slice($notifications_all, 0, 4);

    // Mendapatkan jumlah total notifikasi
    $total_notifications = count($notifications_all);

    // Mendapatkan daftar kelas dengan status
    $classes = $this->ClassModel->getClassesWithStatus($user_id);

    // Menambahkan latest_observers, foto Guru Model, dan encrypted_class_id ke setiap kelas
    foreach ($classes as &$class) {
      $class->latest_observers = $this->ClassObserver->getLatestObservers($class->class_id, 4);

      // Mendapatkan foto profil Guru Model untuk setiap kelas
      $guru_model = $this->User->getUserById($class->creator_user_id);
      $class->guru_model_photo = !empty($guru_model->src_profile_photo) ? $guru_model->src_profile_photo : 'assets/default/default_profile_picture.jpg';

      // Enkripsi ID Kelas menggunakan bin2hex dan encryption library
      $encrypted_class_id = $this->encryption->encrypt($class->class_id);
      $class->encrypted_class_id = bin2hex($encrypted_class_id);
    }
    unset($class); // Menghindari reference pada variabel terakhir

    // Mendapatkan flashdata
    $success_login = $this->session->flashdata('success_login');
    $already_logged_in = $this->session->flashdata('already_logged_in');
    $success_message = $this->session->flashdata('success');
    $error_message = $this->session->flashdata('error');
    $warning_message = $this->session->flashdata('warning');
    $notice_message = $this->session->flashdata('notice');
    $deprecated_message = $this->session->flashdata('deprecated');
    $class_not_started = $this->session->flashdata('class_not_started');
    $error_long = $this->session->flashdata('error_long');

    // Menyiapkan data untuk dikirim ke view
    $data = array(
      "title"                 => "Beranda",
      "num_lesson_study"      => $num_lesson_study,
      "num_classes_created"   => $num_classes_created,
      "num_classes_observed"  => $num_classes_observed,
      "nearest_class"         => $nearest_class,
      "notifications_latest"  => $notifications_latest, // Notifikasi 4 terbaru
      "notifications_all"     => $notifications_all,    // Semua notifikasi (terurut)
      "total_notifications"   => $total_notifications,  // Jumlah total notifikasi
      "classes"               => $classes,
      "success_message"       => $success_message,
      "error_message"         => $error_message,
      "warning_message"       => $warning_message,
      "notice_message"        => $notice_message,
      "deprecated_message"    => $deprecated_message,
      "class_not_started"     => $class_not_started,
      "error_long"            => $error_long,
      "success_login"         => $success_login,
      "already_logged_in"     => $already_logged_in
    );

    // Memuat view 'Beranda' dengan data yang telah disiapkan
    $this->load->view('Beranda', $data);
  }

  /**
   * Menampilkan Halaman Guru Model
   *
   * Menampilkan daftar kelas yang dibuat oleh pengguna sebagai Guru Model.
   */
  public function sidebarGuruModel()
  {
    $this->checkLogin(); // Memastikan pengguna sudah login

    $user_id = $this->session->userdata('user_id'); // Mendapatkan ID pengguna

    // Mendapatkan daftar kelas Guru Model beserta statusnya
    $classes = $this->ClassModel->getClassesWithStatusByCreator($user_id);

    // Untuk setiap kelas, ambil 4 observer terbaru dan enkripsi ID kelas
    foreach ($classes as $class) {
      // Mengambil 4 observer terbaru
      $latest_observers = $this->ClassObserver->getLatestObservers($class->class_id, 4);
      $class->latest_observers = $latest_observers;

      // Enkripsi ID Kelas menggunakan bin2hex dan encryption library
      $encrypted_class_id = $this->encryption->encrypt($class->class_id);
      $class->encrypted_class_id = bin2hex($encrypted_class_id);
    }

    // Menyiapkan data untuk dikirim ke view
    $data = array(
      "title"   => "Guru Model",
      "classes" => $classes
    );

    // Memuat view 'guruModel' dengan data yang telah disiapkan
    $this->load->view('guruModel', $data);
  }

  /**
   * Mengarahkan ke Halaman Detail Kelas Saat Membuat Kelas Baru
   *
   * Mengarahkan pengguna ke halaman detail kelas untuk proses pembuatan kelas baru.
   */
  public function pageBuatKelas()
  {
    redirect('pageBuatKelas_detailKelas');
  }

  /**
   * Menampilkan Halaman Detail Kelas Saat Membuat Kelas Baru
   *
   * Menampilkan form untuk memasukkan detail kelas saat proses pembuatan kelas baru.
   */
  public function pageBuatKelas_detailKelas()
  {
    $this->checkLogin();

    // Mengambil data dari session jika ada
    $session_data = $this->session->userdata('class_data');

    $data["title"] = "Detail Kelas";
    $data["session_data"] = $session_data;

    // Memuat view dengan data
    $this->load->view('buatKelas1', $data);
  }

  /**
   * Memproses Detail Kelas yang Diinputkan
   *
   * Menangani penyimpanan data detail kelas ke session setelah validasi input.
   */
  public function formDetailKelas()
  {
    $this->checkLogin();

    // Mengambil data POST dari form
    $class_name = $this->input->post('class_name');
    $school_name = $this->input->post('school_name');
    $subject = $this->input->post('subject');
    $basic_competency = $this->input->post('basic_competency');
    $date_input = $this->input->post('date');
    $start_time_input = $this->input->post('start_time');
    $end_time_input = $this->input->post('end_time');

    // Validasi data (contoh sederhana)
    if (empty($class_name) || empty($school_name) || empty($subject) || empty($basic_competency) || empty($date_input) || empty($start_time_input) || empty($end_time_input)) {
      // Jika ada data yang kosong, redirect kembali ke form dengan pesan error
      $this->session->set_flashdata('error', 'Semua field wajib diisi.');
      redirect('pageBuatKelas_detailKelas');
    }

    // Mengonversi tanggal dari format DD/MM/YYYY ke YYYY-MM-DD untuk penyimpanan di database
    $date_parts = explode('/', $date_input);
    if (count($date_parts) == 3) {
      $formatted_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    } else {
      // Jika format tanggal salah, set ke NULL atau handle sesuai kebutuhan
      $formatted_date = NULL;
    }

    // Menyiapkan data untuk disimpan di session
    $class_data = array(
      'class_name'        => $class_name,
      'school_name'       => $school_name,
      'subject'           => $subject,
      'basic_competency'  => $basic_competency,
      'date'              => $formatted_date,
      'start_time'        => $start_time_input,
      'end_time'          => $end_time_input
    );

    // Menyimpan data ke session
    $this->session->set_userdata('class_data', $class_data);

    // Redirect ke halaman berikutnya (misalnya Unggah Berkas)
    redirect('pageBuatKelas_unggahBerkas');
  }

  /**
   * Menampilkan Halaman Unggah Berkas Saat Membuat Kelas Baru
   *
   * Menampilkan form untuk mengunggah berkas yang diperlukan saat pembuatan kelas baru.
   */
  public function pageBuatKelas_unggahBerkas()
  {
    $this->checkLogin();

    // Mengambil data class_data dari session
    $class_data = $this->session->userdata('class_data');

    // Pengecekan session untuk detail kelas
    if (
      empty($class_data['class_name']) ||
      empty($class_data['school_name']) ||
      empty($class_data['subject']) ||
      empty($class_data['basic_competency']) ||
      empty($class_data['date']) ||
      empty($class_data['start_time']) ||
      empty($class_data['end_time'])
    ) {
      // Menetapkan flashdata error
      $this->session->set_flashdata('error', 'Anda harus mengisi formulir detail kelas sebelum dapat mengakses formulir ini.');
      // Redirect ke halaman detail kelas
      redirect('pageBuatKelas_detailKelas');
    }

    $data["title"] = "Unggah Berkas";
    // Mengambil data class_files dari session
    $data["class_files"] = $this->session->userdata('class_files') ?: array();
    $data["user"] = $this->user;
    $this->load->view('buatKelas2', $data);
  }

  /**
   * Memproses Unggahan Berkas Saat Membuat Kelas Baru
   *
   * Menangani proses pengunggahan berkas dan penyimpanan path berkas ke session.
   *
   * @return void Mengembalikan respon JSON mengenai status pengunggahan.
   */
  public function formUnggahBerkas()
  {
    $this->checkLogin();

    // Mendapatkan user_id pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Memeriksa apakah ada berkas yang diupload
    if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
      $type = $this->input->post('type');
      $upload_path = '';
      $allowed_types = '';

      // Menentukan path upload dan tipe file yang diizinkan berdasarkan jenis berkas
      switch ($type) {
        case 'DataSiswa':
          $upload_path = FCPATH . 'assets/media/temporary/';
          $allowed_types = 'xls|xlsx|csv';
          break;
        case 'ModulAjar':
          $upload_path = FCPATH . 'assets/media/temporary/';
          $allowed_types = 'pdf';
          break;
        case 'MediaPembelajaran':
          $upload_path = FCPATH . 'assets/media/temporary/';
          $allowed_types = 'pdf';
          break;
        default:
          echo json_encode(['status' => 'error', 'message' => 'Jenis berkas tidak valid.']);
          exit; // Menghentikan eksekusi
      }

      // Membuat direktori jika belum ada
      if (!is_dir($upload_path)) {
        if (!mkdir($upload_path, 0777, TRUE)) {
          // Jika gagal membuat direktori, kembalikan error
          echo json_encode(['status' => 'error', 'message' => 'Gagal membuat direktori upload.']);
          exit; // Menghentikan eksekusi
        }
      }

      // Format tanggal sesuai timezone GMT+7 dengan spasi
      $date = gmdate('Y-m-d H-i-s', time() + 7 * 3600);

      // Mendapatkan ekstensi file
      $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      $original_file_name = $_FILES['file']['name']; // Menyimpan nama asli file

      // Menyusun nama file sesuai format: userID_jenisFile_tanggal_timestamp.extension
      $file_name = "{$user_id}_{$type}_{$date}.{$file_extension}";

      // Mengatur konfigurasi upload
      $config['upload_path']   = $upload_path;
      $config['allowed_types'] = $allowed_types;
      $config['file_name']     = $file_name;
      $config['max_size']      = '10240'; // Maksimal 10MB
      $config['overwrite']     = FALSE; // Menghindari penimpaan file dengan nama yang sama
      $config['remove_spaces'] = FALSE; // Menjaga spasi dalam nama file

      // Menginisialisasi konfigurasi upload
      $this->upload->initialize($config);

      // Menghapus file lama jika ada
      $existing_files = glob($upload_path . "{$user_id}_{$type}_*.*");

      if ($existing_files !== false) {
        foreach ($existing_files as $existing_file) {
          if (is_file($existing_file)) {
            unlink($existing_file); // Menghapus file lama
          }
        }
      }

      // Melakukan upload berkas
      if ($this->upload->do_upload('file')) {
        // Mendapatkan data upload
        $upload_data = $this->upload->data();

        // Menyimpan path file dan nama asli ke session
        $class_files = $this->session->userdata('class_files') ?: array();
        $class_files[$type] = 'assets/media/temporary/' . $file_name;
        $class_files["original_{$type}"] = $original_file_name; // Menyimpan nama asli
        $this->session->set_userdata('class_files', $class_files);

        // Mengembalikan respon sukses dengan nama asli file
        echo json_encode([
          'status' => 'success',
          'file_name' => $file_name,
          'original_name' => $original_file_name
        ]);
        exit; // Menghentikan eksekusi
      } else {
        // Mengembalikan pesan error jika upload gagal
        echo json_encode([
          'status' => 'error',
          'message' => $this->upload->display_errors('', '')
        ]);
        exit; // Menghentikan eksekusi
      }
    } else {
      // Mengembalikan pesan error jika tidak ada berkas yang diupload
      echo json_encode([
        'status' => 'error',
        'message' => 'Tidak ada berkas yang diunggah.'
      ]);
      exit; // Menghentikan eksekusi
    }
  }

  /**
   * Menampilkan Halaman Detail Observer Saat Membuat Kelas Baru
   *
   * Menampilkan form untuk memilih observer dan menghasilkan kode kelas unik.
   */
  public function pageBuatKelas_detailObserver()
  {
    // Memeriksa apakah pengguna sudah login
    $this->checkLogin();

    // Mengambil data dari sesi
    $session_data = $this->session->userdata('class_data');
    $class_files = $this->session->userdata('class_files');
    $saved_observers = $this->session->userdata('observers') ?: [];

    // Pengecekan session untuk Data Siswa, Modul Ajar, dan Media Pembelajaran
    if (
      empty($class_files['DataSiswa']) ||
      empty($class_files['ModulAjar']) ||
      empty($class_files['MediaPembelajaran'])
    ) {
      // Menetapkan flashdata error
      $this->session->set_flashdata('error', 'Anda harus mengisi formulir unggah berkas sebelum dapat mengakses formulir ini.');
      // Redirect ke halaman unggah berkas
      redirect('pageBuatKelas_unggahBerkas');
    }

    // Mengambil semua pengguna kecuali pengguna saat ini
    $users = $this->User->getAllUsersExcept($this->user->user_id);

    // Membuat array asosiatif pengguna dengan key user_id untuk akses cepat
    $users_assoc = [];
    foreach ($users as $user) {
      $users_assoc[$user->user_id] = $user;
    }

    // Memperkaya data observer yang disimpan dengan src_profile_photo dari database
    foreach ($saved_observers as &$observer) {
      if (isset($users_assoc[$observer['observerId']])) {
        $observer['src_profile_photo'] = $users_assoc[$observer['observerId']]->src_profile_photo;
      } else {
        // Jika tidak ada, gunakan gambar default
        $observer['src_profile_photo'] = 'assets/default/default_profile_picture.jpg';
      }
    }

    // Menghasilkan kode kelas
    $class_code = $this->generateClassCode(
      $session_data['class_name'],
      $session_data['school_name'],
      $session_data['subject'],
      $session_data['basic_competency']
    );

    // Menyiapkan data untuk dikirim ke view
    $data["title"] = "Detail Observer";
    $data["session_data"] = $session_data;
    $data["class_files"] = $class_files;
    $data["users"] = $users;
    $data["class_code"] = $class_code;
    $data["user"] = $this->user; // Menambahkan data pengguna saat ini
    $data["saved_observers"] = $saved_observers; // Menambahkan data observer yang disimpan

    // Memuat view dengan data yang telah disiapkan
    $this->load->view('buatKelas3', $data);
  }

  /**
   * Memproses Detail Observer Saat Membuat Kelas Baru
   *
   * Menyimpan data observer yang dipilih ke session.
   */
  public function formDetailObserver()
  {
    // Memeriksa apakah pengguna sudah login
    $this->checkLogin();

    // Mendapatkan data JSON dari request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['observers'])) {
      // Menyimpan data observer ke sesi
      $this->session->set_userdata('observers', $data['observers']);
      echo json_encode(['status' => 'success']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    }
  }

  /**
   * Memproses Pengiriman Data Kelas ke Database
   *
   * Menyimpan data kelas, observer, catatan khusus, dokumentasi, dan rekaman suara ke database.
   *
   * @return void
   */
  public function formBuatKelasSender()
  {
    // Memeriksa apakah pengguna sudah login
    $this->checkLogin();

    $user_id = $this->session->userdata('user_id');

    // Mengambil data dari sesi
    $class_data = $this->session->userdata('class_data');
    $class_files = $this->session->userdata('class_files');

    // Mengambil data observer dan kode kelas dari form
    $observers_json = $this->input->post('observers_data');
    $class_code = $this->input->post('class_code');

    // Mengubah JSON observer menjadi array
    $observers = json_decode($observers_json, true);

    // Menambahkan data dummy untuk catatan khusus
    $special_notes = array(
      array(
        "activity_type" => "Keaktifan siswa",
        "note_details" => "Siswa secara aktif terlibat dalam diskusi kelas dengan mengajukan pertanyaan yang relevan kepada guru."
      ),
      array(
        "activity_type" => "Siswa menjawab",
        "note_details" => "Beberapa siswa menunjukkan inisiatif dengan menjawab pertanyaan yang diajukan oleh guru. Tanggapan mereka mencerminkan pemahaman yang baik dan kemampuan untuk menerapkan konsep yang telah dipelajari."
      ),
      array(
        "activity_type" => "Presentasi",
        "note_details" => "Dua siswa mengambil peran aktif dalam presentasi proyek kelas. Mereka menyiapkan materi dengan baik, menyampaikan informasi secara jelas, dan mampu menjawab pertanyaan dari teman-teman sekelas dengan percaya diri."
      ),
      array(
        "activity_type" => "Siswa membuat kegaduhan",
        "note_details" => "Siswa nomor 4 dan 5 terlihat mengobrol dengan suara keras selama pelajaran berlangsung. Kegiatan ini mengganggu konsentrasi siswa lain dan menghambat proses belajar mengajar di kelas."
      ),
      array(
        "activity_type" => "Kehadiran siswa",
        "note_details" => "Sebagian besar siswa hadir tepat waktu setiap hari. Kehadiran yang konsisten ini menunjukkan komitmen mereka terhadap pembelajaran dan tanggung jawab terhadap pendidikan mereka."
      ),
      array(
        "activity_type" => "Kerja kelompok",
        "note_details" => "Siswa bekerja sama secara efektif dalam kelompok untuk menyelesaikan tugas proyek. Mereka mendistribusikan tugas dengan adil, berkomunikasi secara terbuka, dan menyelesaikan pekerjaan tepat waktu."
      ),
      array(
        "activity_type" => "Penggunaan perangkat",
        "note_details" => "Siswa memanfaatkan perangkat teknologi seperti laptop dan tablet dengan bijak untuk mendukung proses belajar. Mereka menggunakan aplikasi edukatif dan sumber daya online untuk memperdalam pemahaman materi."
      ),
      array(
        "activity_type" => "Disiplin kelas",
        "note_details" => "Siswa secara konsisten menunjukkan perilaku disiplin dengan mengikuti aturan kelas, menghormati guru dan teman-teman sekelas, serta menjaga lingkungan belajar yang kondusif dan teratur."
      )
    );

    // Menambahkan data dummy untuk file dokumentasi
    $documentation_files = array(
      'assets/default/default_documentation_1.jpg',
      'assets/default/default_documentation_2.jpg',
      'assets/default/default_documentation_3.jpg',
      'assets/default/default_documentation_4.jpg',
      'assets/default/default_documentation_5.jpg'
    );

    // Data dummy untuk rekaman suara
    $voice_recording = 'assets/media/audio/sample_audio.mp3';

    // Memindahkan file dari direktori sementara ke direktori tujuan
    $this->moveFilesFromTemporary($user_id, $class_files);

    // Menyimpan kembali class_files ke session setelah pemindahan
    $this->session->set_userdata('class_files', $class_files);

    // Menyiapkan data untuk disimpan ke database
    $class_data['src_student_data_file'] = isset($class_files['DataSiswa']) ? $class_files['DataSiswa'] : null;
    $class_data['src_teaching_module_file'] = isset($class_files['ModulAjar']) ? $class_files['ModulAjar'] : null;
    $class_data['src_learning_media_file'] = isset($class_files['MediaPembelajaran']) ? $class_files['MediaPembelajaran'] : null;
    $class_data['class_code'] = $class_code;
    $class_data['creator_user_id'] = $user_id;

    // Menyimpan kelas ke database
    $class_id = $this->ClassModel->createClass($class_data);

    if ($class_id) {
      // Menambahkan observer ke kelas
      foreach ($observers as $observer) {
        $observer_user_id = $observer['observerId'];
        $student_numbers = explode(',', $observer['nomorSiswa']); // Array nomor siswa

        // Mengurutkan nomor siswa dari terkecil hingga terbesar
        $student_numbers = array_map('trim', $student_numbers);
        sort($student_numbers, SORT_NUMERIC);

        $this->ClassObserver->addObserver($class_id, $observer_user_id);

        // Menambahkan siswa yang diamati
        foreach ($student_numbers as $student_number) {
          $this->ObservedStudent->addObservedStudent($class_id, $observer_user_id, $student_number);
        }

        // Menambahkan catatan khusus per observer
        foreach ($special_notes as $note) {
          $note['class_id'] = $class_id;
          $note['observer_user_id'] = $observer_user_id;
          $this->SpecialNote->createNote($note);
        }

        // Menambahkan file dokumentasi per observer
        foreach ($documentation_files as $file_src) {
          $this->ClassDocumentationFile->createDocumentation($class_id, $observer_user_id, $file_src);
        }

        // Menambahkan rekaman suara per observer
        $this->ClassVoiceRecording->createRecording($class_id, $observer_user_id, $voice_recording);

        // Mengirim notifikasi ke observer
        $this->Notification->createNotification($user_id, $observer_user_id, $class_id, 'Anda telah ditambahkan sebagai observer.', 'Observer Ditambahkan');
      }

      // Menghapus data dari sesi
      $this->session->unset_userdata('class_data');
      $this->session->unset_userdata('class_files');
      $this->session->unset_userdata('observers'); // Menghapus data observer dari sesi

      // Menetapkan flashdata untuk SweetAlert2 di sidebarBeranda
      $this->session->set_flashdata('success', 'SELAMAT! Kelas Anda Berhasil Dibuat.');

      // Redirect ke halaman Beranda
      redirect('sidebarBeranda');
    } else {
      // Jika gagal membuat kelas, tetapkan flashdata error dan redirect kembali
      $this->session->set_flashdata('error', 'Gagal membuat kelas.');
      redirect('pageBuatKelas_detailObserver');
    }
  }

  /**
   * Memindahkan File dari Direktori Sementara ke Direktori Sesungguhnya
   *
   * @param int $user_id ID Pengguna
   * @param array $class_files Array Path File
   */
  private function moveFilesFromTemporary($user_id, &$class_files)
  {
    // Daftar jenis berkas dan direktori tujuan
    $file_types = [
      'DataSiswa' => 'assets/media/datasiswa/',
      'ModulAjar' => 'assets/media/modulajar/',
      'MediaPembelajaran' => 'assets/media/mediapembelajaran/'
    ];

    foreach ($file_types as $type => $destination_dir) {
      if (isset($class_files[$type])) {
        $temp_path = FCPATH . $class_files[$type];
        $file_name = basename($temp_path);
        $new_path = FCPATH . $destination_dir . $file_name;

        // Membuat direktori tujuan jika belum ada
        if (!is_dir(FCPATH . $destination_dir)) {
          mkdir(FCPATH . $destination_dir, 0777, TRUE);
        }

        // Memindahkan file
        if (file_exists($temp_path)) {
          if (rename($temp_path, $new_path)) {
            // Memperbarui path file dalam array class_files
            $class_files[$type] = $destination_dir . $file_name;
          }
        }
      }
    }
  }

  /**
   * Menghasilkan Kode Kelas Unik
   *
   * Membuat kode kelas yang unik berdasarkan nama kelas, nama sekolah, subjek, dan kompetensi dasar.
   *
   * @param string $class_name Nama kelas
   * @param string $school_name Nama sekolah
   * @param string $subject Subjek
   * @param string $basic_competency Kompetensi dasar
   * @return string Kode kelas unik
   * @throws Exception Jika gagal menghasilkan kode unik setelah maksimal percobaan
   */
  private function generateClassCode($class_name, $school_name, $subject, $basic_competency)
  {
    // Fungsi untuk mengolah setiap bagian
    function generatePart($input)
    {
      // Menghapus karakter non-alfanumerik dan mengubah ke huruf besar
      $cleaned = strtoupper(preg_replace('/[^A-Z0-9]/', '', $input));

      // Menghasilkan hash SHA1 dari input yang telah dibersihkan dengan tambahan keacakan
      $hash = sha1($cleaned . uniqid('', true));

      // Mengonversi hash hex ke integer dan kemudian ke Base36
      $int = hexdec(substr($hash, 0, 13)); // Mengambil sebagian hash untuk konversi
      $base36 = strtoupper(base_convert($int, 10, 36));

      // Mengambil 5 karakter pertama dari Base36
      return substr($base36, 0, 5);
    }

    $max_attempts = 5; // Maksimal percobaan untuk menghasilkan kode unik
    $attempt = 0;

    do {
      // Menghasilkan setiap bagian dari kode kelas
      $class_name_part = generatePart($class_name);
      $school_name_part = generatePart($school_name);
      $subject_part = generatePart($subject);
      $basic_competency_part = generatePart($basic_competency);

      // Menggabungkan semua bagian untuk membentuk kode kelas akhir
      $class_code = $class_name_part . $school_name_part . $subject_part . $basic_competency_part;

      // Memeriksa apakah kode kelas sudah ada di database
      $exists = $this->ClassModel->checkClassCodeExists($class_code);

      if (!$exists) {
        // Jika kode tidak ada, break loop
        break;
      }

      $attempt++;
    } while ($attempt < $max_attempts);

    if ($attempt == $max_attempts && $exists) {
      // Jika gagal menghasilkan kode unik setelah maksimal percobaan
      throw new Exception('Gagal menghasilkan kode kelas yang unik. Silakan coba lagi.');
    }

    return strtoupper($class_code);
  }

  /**
   * Menampilkan Halaman Kelas Guru Model
   *
   * Menampilkan detail kelas Guru Model termasuk notifikasi, dokumentasi, dan penilaian.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function pageKelasGuruModel($encrypted_idKelas)
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Validasi input: pastikan encrypted_idKelas memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      // Tampilkan pesan error dan redirect ke beranda
      $this->session->set_flashdata('error', 'Kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas); // Dekode dari heksadesimal ke biner
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    // Jika ID Kelas tidak valid, tampilkan pesan error dan redirect
    if (!$idKelas) {
      $this->session->set_flashdata('error', 'Kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      // Tampilkan halaman 404 jika kelas tidak ditemukan
      show_404();
    }

    // Memeriksa apakah pengguna adalah guru model yang membuat kelas ini
    if ($class->creator_user_id != $user_id) {
      // Jika bukan, tampilkan pesan error dan redirect ke beranda
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan data guru model
    $guruModel = $this->User->getUserById($class->creator_user_id);
    $class->guru_model_name = !empty($guruModel->full_name) ? $guruModel->full_name : ' ';
    $class->guru_model_registration_number = !empty($guruModel->registration_number) ? $guruModel->registration_number : ' ';

    // Menghitung jumlah siswa unik dalam kelas
    $unique_students = $this->ObservedStudent->getUniqueStudentNumbersByClass($idKelas);
    $number_of_students = count($unique_students);
    // Menambahkan jumlah siswa ke data kelas
    $class->number_of_students = $number_of_students;

    // Menambahkan leading zeros pada nomor siswa dan mengurutkannya
    $unique_students_padded = array_map(function ($num) {
      return sprintf('%02d', $num);
    }, $unique_students);
    sort($unique_students_padded, SORT_NUMERIC); // Mengurutkan dari yang terkecil

    // Mendapatkan data observer dengan detail lengkap
    $observers = $this->ClassObserver->getObserversWithDetails($idKelas);

    // Mendapatkan siswa yang diamati oleh setiap observer
    foreach ($observers as &$observer) {
      $observed_students = $this->ObservedStudent->getObservedStudents($idKelas, $observer->observer_user_id);
      $observer->observed_students = array_column($observed_students, 'student_number');
      sort($observer->observed_students, SORT_NUMERIC); // Mengurutkan nomor siswa

      // Mendapatkan data tambahan observer
      $observerData = $this->User->getUserById($observer->observer_user_id);
      $observer->full_name = !empty($observerData->full_name) ? $observerData->full_name : ' ';
      $observer->email_address = !empty($observerData->email_address) ? $observerData->email_address : ' ';
      $observer->registration_number = !empty($observerData->registration_number) ? $observerData->registration_number : ' ';
      $observer->src_profile_photo = !empty($observerData->src_profile_photo) ? base_url($observerData->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg');

      // Mendapatkan data Penilaian Kegiatan Mengajar
      $assessment = $this->TeachingActivityAssessment->getAssessment($idKelas, $observer->observer_user_id);
      $this->generateDocxFiles($idKelas, $observer, 'PenilaianKegiatanMengajar', $assessment, $class);
      if ($assessment) {
        $observer->penilaian_updated_at = $assessment->updated_at;
        $observer->penilaian_share_link = site_url('downloadFile/' . $this->safe_encrypt($observer->penilaian_file_path) . '/PenilaianKegiatanMengajar.docx');
        $observer->penilaian_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/PenilaianKegiatanMengajar');
      } else {
        $observer->penilaian_updated_at = '';
        $observer->penilaian_share_link = site_url('downloadFile/' . $this->safe_encrypt($observer->penilaian_file_path) . '/PenilaianKegiatanMengajar.docx');
        $observer->penilaian_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/PenilaianKegiatanMengajar');
      }

      // Mendapatkan data Lembar Pengamatan Siswa
      $observation = $this->StudentObservationSheet->getObservationSheet($idKelas, $observer->observer_user_id);

      if ($observation) {
        // Mendapatkan detail pengamatan siswa
        $observation->observationDetails = $this->StudentObservationDetail->getObservationDetails($observation->observation_id);
      }

      $this->generateDocxFiles($idKelas, $observer, 'LembarPengamatanSiswa', $observation, $class);

      if ($observation) {
        $observer->pengamatan_updated_at = $observation->updated_at;
        $observer->pengamatan_share_link = site_url('downloadFile/' . $this->safe_encrypt($observer->pengamatan_file_path) . '/LembarPengamatanSiswa.docx');
        $observer->pengamatan_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/LembarPengamatanSiswa');
      } else {
        $observer->pengamatan_updated_at = '';
        $observer->pengamatan_share_link = site_url('downloadFile/' . $this->safe_encrypt($observer->pengamatan_file_path) . '/LembarPengamatanSiswa.docx');
        $observer->pengamatan_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/LembarPengamatanSiswa');
      }

      // Mendapatkan data Catatan Aktivitas Siswa
      $activityNote = $this->StudentActivityNote->getActivityNote($idKelas, $observer->observer_user_id);
      $this->generateDocxFiles($idKelas, $observer, 'CatatanAktivitasSiswa', $activityNote, $class);
      if ($activityNote) {
        $observer->catatan_updated_at = $activityNote->updated_at;
        $observer->catatan_share_link = site_url('downloadFile/' . $this->safe_encrypt($observer->catatan_file_path) . '/CatatanAktivitasSiswa.docx');
        $observer->catatan_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/CatatanAktivitasSiswa');
      } else {
        $observer->catatan_updated_at = '';
        $observer->catatan_share_link = site_url('downloadFile/' . $this->safe_encrypt($observer->catatan_file_path) . '/CatatanAktivitasSiswa.docx');
        $observer->catatan_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/CatatanAktivitasSiswa');
      }

      // Mendapatkan data rekaman suara untuk setiap observer
      $audio_recording = $this->ClassVoiceRecording->getRecordingByClassAndObserver($idKelas, $observer->observer_user_id);
      if ($audio_recording) {
        $observer->audio_src = base_url($audio_recording->file_src);
        $observer->encrypted_audio_path = $this->safe_encrypt($audio_recording->file_src);
        $observer->audio_share_link = site_url('downloadFile/' . $observer->encrypted_audio_path . '/RekamanSuara.' . pathinfo($audio_recording->file_src, PATHINFO_EXTENSION));
      } else {
        // Jika tidak ada rekaman, buat tautan untuk formulir kosong
        $observer->audio_src = '';
        $observer->encrypted_audio_path = $this->safe_encrypt(''); // Bisa diatur sesuai kebutuhan
        $observer->audio_share_link = '#'; // Atau link ke formulir kosong jika ada
      }

      // Mendapatkan data Catatan Khusus per observer
      $special_notes = $this->SpecialNote->getNotesByClassAndObserver($idKelas, $observer->observer_user_id);
      // Format catatan khusus menjadi array
      $formatted_special_notes = array();
      foreach ($special_notes as $note) {
        $formatted_special_notes[] = array(
          'note_id' => isset($note->note_id) ? $note->note_id : ' ',
          'activity_type' => isset($note->activity_type) ? $note->activity_type : ' ',
          'note_details' => isset($note->note_details) ? $note->note_details : ' ',
          'updated_at' => isset($note->updated_at) ? $note->updated_at : ' '
        );
      }

      // Menambahkan entri default jika tidak ada data
      if (empty($formatted_special_notes)) {
        $formatted_special_notes[] = array(
          'note_id' => ' ',
          'activity_type' => ' ',
          'note_details' => ' ',
          'updated_at' => ' '
        );
      }
      $observer->special_notes = $formatted_special_notes; // Menambahkan catatan khusus ke observer

      // Mendapatkan data dokumentasi per observer
      $documentation_files = $this->ClassDocumentationFile->getDocumentationsByClassAndObserver($idKelas, $observer->observer_user_id);
      // Format dokumentasi menjadi array
      $formatted_documentation_files = array();
      foreach ($documentation_files as $doc) {
        $formatted_documentation_files[] = array(
          'file_src' => (!empty($doc->file_src)) ? base_url($doc->file_src) : ' ',
          'file_name' => (!empty($doc->file_src)) ? basename($doc->file_src) : ' '
        );
      }

      // Menambahkan entri default jika tidak ada data
      if (empty($formatted_documentation_files)) {
        $formatted_documentation_files[] = array(
          'file_src' => ' ',
          'file_name' => ' '
        );
      }
      $observer->documentation_files = $formatted_documentation_files; // Menambahkan dokumentasi ke observer
    }
    unset($observer); // Hapus referensi

    // Mengambil path file Data Siswa, Modul Ajar, dan Media Pembelajaran
    $data_siswa_path = (!empty($class->src_student_data_file)) ? $class->src_student_data_file : '';
    $modul_ajar_path = (!empty($class->src_teaching_module_file)) ? $class->src_teaching_module_file : '';
    $media_pembelajaran_path = (!empty($class->src_learning_media_file)) ? $class->src_learning_media_file : '';

    // Menghitung ukuran file dalam KB atau MB
    $data_siswa_size = $this->formatFileSize(FCPATH . $data_siswa_path);
    $modul_ajar_size = $this->formatFileSize(FCPATH . $modul_ajar_path);
    $media_pembelajaran_size = $this->formatFileSize(FCPATH . $media_pembelajaran_path);

    // Enkripsi path file menggunakan metode yang sama
    $encrypted_data_siswa_path = (!empty($data_siswa_path)) ? $this->safe_encrypt($data_siswa_path) : '';
    $encrypted_modul_ajar_path = (!empty($modul_ajar_path)) ? $this->safe_encrypt($modul_ajar_path) : '';
    $encrypted_media_pembelajaran_path = (!empty($media_pembelajaran_path)) ? $this->safe_encrypt($media_pembelajaran_path) : '';

    // Membuat link share menggunakan URL yang terenkripsi
    $data_siswa_share_link = (!empty($encrypted_data_siswa_path)) ? site_url('downloadFile/' . $encrypted_data_siswa_path . '/DataSiswa.' . pathinfo($data_siswa_path, PATHINFO_EXTENSION)) : '#';
    $modul_ajar_share_link = (!empty($encrypted_modul_ajar_path)) ? site_url('downloadFile/' . $encrypted_modul_ajar_path . '/ModulAjar.' . pathinfo($modul_ajar_path, PATHINFO_EXTENSION)) : '#';
    $media_pembelajaran_share_link = (!empty($encrypted_media_pembelajaran_path)) ? site_url('downloadFile/' . $encrypted_media_pembelajaran_path . '/MediaPembelajaran.' . pathinfo($media_pembelajaran_path, PATHINFO_EXTENSION)) : '#';

    // Menyiapkan data untuk dikirim ke view
    $data = array(
      'title' => 'Kelas Guru Model',
      'class' => $class,
      'user' => $this->User->getUserById($user_id),
      'encrypted_idKelas' => $encrypted_idKelas,
      'data_siswa_size' => $data_siswa_size,
      'data_siswa_path' => $data_siswa_path,
      'encrypted_data_siswa_path' => $encrypted_data_siswa_path,
      'data_siswa_share_link' => $data_siswa_share_link,
      'modul_ajar_size' => $modul_ajar_size,
      'modul_ajar_path' => $modul_ajar_path,
      'encrypted_modul_ajar_path' => $encrypted_modul_ajar_path,
      'modul_ajar_share_link' => $modul_ajar_share_link,
      'media_pembelajaran_size' => $media_pembelajaran_size,
      'media_pembelajaran_path' => $media_pembelajaran_path,
      'encrypted_media_pembelajaran_path' => $encrypted_media_pembelajaran_path,
      'media_pembelajaran_share_link' => $media_pembelajaran_share_link,
      'nomor_siswa' => $unique_students_padded, // Menyediakan data nomor siswa dengan leading zeros untuk JavaScript
      'observers' => $observers // Menyediakan data observers untuk JavaScript
    );

    // Memuat view 'kelasGuruModel' dengan data yang telah disiapkan
    $this->load->view('kelasGuruModel', $data);
  }

  /**
   * Fungsi untuk mengenkripsi data dengan pemeriksaan keamanan
   *
   * @param string $data Data yang akan dienkripsi
   * @return string Data terenkripsi dalam bentuk heksadesimal
   */
  private function safe_encrypt($data)
  {
    if (is_string($data) && strlen($data) > 0) {
      $encrypted = $this->encryption->encrypt($data);
      if ($encrypted !== false && $encrypted !== null) {
        return bin2hex($encrypted);
      } else {
        log_message('error', 'Encryption failed for data: ' . $data);
      }
    } else {
      log_message('error', 'Invalid data passed to encryption: ' . var_export($data, true));
    }
    return '';
  }

  /**
   * Mengenerate File .docx untuk Formulir
   *
   * Mengenerate file .docx untuk jenis formulir tertentu dan menyimpannya di direktori sesuai jenis file.
   *
   * @param int $idKelas ID Kelas
   * @param object $observer Data Observer
   * @param string $jenisFile Jenis File (PenilaianKegiatanMengajar, LembarPengamatanSiswa, CatatanAktivitasSiswa)
   * @param object|null $formData Data Formulir (Assessment, Observation, ActivityNote)
   * @param object $classData Data Kelas
   */
  private function generateDocxFiles($idKelas, $observer, $jenisFile, $formData, $classData)
  {
    // Tentukan template file dan direktori penyimpanan berdasarkan jenisFile
    switch ($jenisFile) {
      case 'PenilaianKegiatanMengajar':
        $templatePath = 'assets/media/template/FormatPenilaianKegiatanMengajar.docx';
        $saveDir = 'assets/media/penilaianKegiatanMengajar/';
        $filename = $idKelas . '_' . $observer->observer_user_id . '_PenilaianKegiatanMengajar_' . date('Y-m-d_H-i-s') . '.docx';
        $filePattern = $idKelas . '_' . $observer->observer_user_id . '_PenilaianKegiatanMengajar_*.docx';
        break;
      case 'LembarPengamatanSiswa':
        $templatePath = 'assets/media/template/FormatLembarPengamatanSiswa.docx';
        $saveDir = 'assets/media/lembarPengamatanSiswa/';
        $filename = $idKelas . '_' . $observer->observer_user_id . '_LembarPengamatanSiswa_' . date('Y-m-d_H-i-s') . '.docx';
        $filePattern = $idKelas . '_' . $observer->observer_user_id . '_LembarPengamatanSiswa_*.docx';
        break;
      case 'CatatanAktivitasSiswa':
        $templatePath = 'assets/media/template/FormatCatatanAktivitasSiswa.docx';
        $saveDir = 'assets/media/catatanAktivitasSiswa/';
        $filename = $idKelas . '_' . $observer->observer_user_id . '_CatatanAktivitasSiswa_' . date('Y-m-d_H-i-s') . '.docx';
        $filePattern = $idKelas . '_' . $observer->observer_user_id . '_CatatanAktivitasSiswa_*.docx';
        break;
      default:
        // Jenis file tidak dikenal
        exit;
    }

    // Pastikan direktori penyimpanan ada, jika tidak buat direktori
    if (!is_dir($saveDir)) {
      mkdir($saveDir, 0755, true);
    }

    // Hapus file lama berdasarkan idKelas, idObserver, dan jenisFile
    $existingFiles = glob($saveDir . $filePattern);
    if ($existingFiles) {
      foreach ($existingFiles as $file) {
        unlink($file);
      }
    }

    $filepath = $saveDir . $filename;

    // Load template menggunakan TemplateProcessor
    $templateProcessor = new TemplateProcessor($templatePath);

    // Data umum
    $namaSekolah = !empty($classData->school_name) ? $classData->school_name : ' ';
    $namaGuruModel = !empty($classData->guru_model_name) ? $classData->guru_model_name : ' ';
    $nomorIndukGuruModel = !empty($classData->guru_model_registration_number) ? $classData->guru_model_registration_number : ' ';
    $tanggal = !empty($classData->date) ? indonesian_date($classData->date) : ' ';
    $namaObserver = !empty($observer->full_name) ? $observer->full_name : ' ';
    $nomorIndukObserver = !empty($observer->registration_number) ? $observer->registration_number : ' ';

    // Mengambil tanda tangan dari data formulir
    $tandaTangan = '';
    if (!empty($formData->src_signature_file)) {
      $tandaTangan = FCPATH . $formData->src_signature_file; // Path absolut
    }

    // Berdasarkan jenis file, lakukan penggantian placeholder
    switch ($jenisFile) {
      case 'PenilaianKegiatanMengajar':
        // Gantikan placeholder sesuai dengan template
        $templateProcessor->setValue('${namaSekolah}', $namaSekolah);
        $templateProcessor->setValue('${namaGuruModel}', $namaGuruModel);
        $templateProcessor->setValue('${nomorIndukGuruModel}', $nomorIndukGuruModel);
        $templateProcessor->setValue('${tanggal}', $tanggal);

        // Skor Indikator
        // 4 Baris 10 Kolom
        // Menentukan skor untuk setiap indikator dan nilai
        for ($i = 1; $i <= 10; $i++) {
          for ($j = 1; $j <= 4; $j++) {
            $score = (!empty($formData->{'score_question' . $i}) && $formData->{'score_question' . $i} == $j) ? '✓' : ' ';
            $placeholder = '${' . $i . '_' . $j . '}';
            $templateProcessor->setValue($placeholder, $score);
          }
        }

        $totalSkor = !empty($formData->total_score) ? $formData->total_score : ' ';
        $konversiNilai = !empty($formData->converted_value) ? $formData->converted_value : ' ';
        $catatan = !empty($formData->notes) ? $formData->notes : ' ';

        $templateProcessor->setValue('${totalSkor}', $totalSkor);
        $templateProcessor->setValue('${konversiNilai}', $konversiNilai);
        $templateProcessor->setValue('${catatan}', $catatan);
        $templateProcessor->setValue('${tanggal}', $tanggal);
        $templateProcessor->setValue('${namaObserver}', $namaObserver);
        $templateProcessor->setValue('${nomorIndukObserver}', $nomorIndukObserver);

        // Sisipkan tanda tangan
        if (!empty($tandaTangan) && file_exists($tandaTangan)) {
          // Mengkonversi ukuran dari cm ke pixel
          $cm_to_px = 37.7952755906; // Faktor konversi dari cm ke pixel
          $width_cm = 3.5;
          $height_cm = 3.5;
          $width_px = $width_cm * $cm_to_px;
          $height_px = $height_cm * $cm_to_px;

          // Mengganti placeholder ${tandaTangan} dengan gambar tanda tangan
          $templateProcessor->setImageValue('tandaTangan', array('path' => $tandaTangan, 'width' => $width_px, 'height' => $height_px, 'ratio' => false));
        } else {
          // Jika tidak ada tanda tangan, kosongkan placeholder
          $templateProcessor->setValue('${tandaTangan}', ' ');
        }

        break;

      case 'LembarPengamatanSiswa':
        // Gantikan placeholder sesuai dengan template
        $templateProcessor->setValue('${namaObserver}', $namaObserver);
        $templateProcessor->setValue('${namaGuruModel}', $namaGuruModel);
        $hariDanTanggal = !empty($classData->date) ? indonesian_date($classData->date, true) : ' ';
        $templateProcessor->setValue('${hariDanTanggal}', $hariDanTanggal);
        $templateProcessor->setValue('${mataPelajaran}', !empty($classData->subject) ? $classData->subject : ' ');
        $templateProcessor->setValue('${kompetensiDasar}', !empty($classData->basic_competency) ? $classData->basic_competency : ' ');

        // Jumlah siswa
        $jumlahSiswa = !empty($classData->number_of_students) ? $classData->number_of_students : ' ';
        $templateProcessor->setValue('${jumlahSiswa}', $jumlahSiswa);

        // Nomor siswa yang diamati
        $nomorSiswaDiamati = !empty($observer->observed_students) ? count($observer->observed_students) : ' ';
        $templateProcessor->setValue('${nomorSiswa}', $nomorSiswaDiamati);

        // Nomor siswa diamati, dibatasi sampai 12 siswa
        if (!empty($observer->observed_students)) {
          $observedStudents = $observer->observed_students;
          sort($observedStudents);
          for ($i = 1; $i <= 12; $i++) {
            $studentNumber = isset($observedStudents[$i - 1]) ? $observedStudents[$i - 1] : ' ';
            $templateProcessor->setValue('${siswa' . $i . '}', $studentNumber);
          }
        } else {
          // Jika tidak ada siswa diamati
          for ($i = 1; $i <= 12; $i++) {
            $templateProcessor->setValue('${siswa' . $i . '}', ' ');
          }
        }

        // Indikator
        // 12 Baris 18 Kolom
        // Mendapatkan data pengamatan siswa dari $formData

        for ($indikator = 1; $indikator <= 18; $indikator++) {
          for ($siswaIndex = 1; $siswaIndex <= 12; $siswaIndex++) {
            $studentNumber = isset($observedStudents[$siswaIndex - 1]) ? $observedStudents[$siswaIndex - 1] : null;
            if ($studentNumber !== null) {
              // Cari nilai pengamatan untuk indikator dan siswa ini
              $value = ' ';
              if (!empty($formData->observationDetails)) {
                foreach ($formData->observationDetails as $detail) {
                  if ($detail->student_number == $studentNumber && $detail->indicator_number == $indikator) {
                    $value = $detail->value ? '✓' : ' ';
                    break;
                  }
                }
              }
              $templateProcessor->setValue('${' . $indikator . '_' . $siswaIndex . '}', $value);
            } else {
              $templateProcessor->setValue('${' . $indikator . '_' . $siswaIndex . '}', ' ');
            }
          }
        }

        // Catatan
        $catatan = !empty($formData->notes) ? $formData->notes : ' ';
        $templateProcessor->setValue('${catatan}', $catatan);
        $templateProcessor->setValue('${tanggal}', $tanggal);
        $templateProcessor->setValue('${namaObserver}', $namaObserver);
        $templateProcessor->setValue('${nomorIndukObserver}', $nomorIndukObserver);

        // Sisipkan tanda tangan
        if (!empty($tandaTangan) && file_exists($tandaTangan)) {
          // Mengkonversi ukuran dari cm ke pixel
          $cm_to_px = 37.7952755906; // Faktor konversi dari cm ke pixel
          $width_cm = 3.5;
          $height_cm = 3.5;
          $width_px = $width_cm * $cm_to_px;
          $height_px = $height_cm * $cm_to_px;

          // Mengganti placeholder ${tandaTangan} dengan gambar tanda tangan
          $templateProcessor->setImageValue('tandaTangan', array('path' => $tandaTangan, 'width' => $width_px, 'height' => $height_px, 'ratio' => false));
        } else {
          // Jika tidak ada tanda tangan, kosongkan placeholder
          $templateProcessor->setValue('${tandaTangan}', ' ');
        }

        break;

      case 'CatatanAktivitasSiswa':
        // Gantikan placeholder sesuai dengan template
        $templateProcessor->setValue('${namaObserver}', $namaObserver);
        $templateProcessor->setValue('${namaGuruModel}', $namaGuruModel);
        $hariDanTanggal = !empty($classData->date) ? indonesian_date($classData->date, true) : ' ';
        $templateProcessor->setValue('${hariDanTanggal}', $hariDanTanggal);
        $templateProcessor->setValue('${mataPelajaran}', !empty($classData->subject) ? $classData->subject : ' ');
        $templateProcessor->setValue('${kompetensiDasar}', !empty($classData->basic_competency) ? $classData->basic_competency : ' ');

        // Jumlah siswa
        $jumlahSiswa = !empty($classData->number_of_students) ? $classData->number_of_students : ' ';
        $templateProcessor->setValue('${jumlahSiswa}', $jumlahSiswa);

        // Nomor siswa yang diamati
        $nomorSiswaDiamati = !empty($observer->observed_students) ? count($observer->observed_students) : ' ';
        $templateProcessor->setValue('${nomorSiswa}', $nomorSiswaDiamati);

        // Jawaban esai pertanyaan 1-5
        for ($i = 1; $i <= 5; $i++) {
          $jawaban = !empty($formData->{'answer_question' . $i}) ? $formData->{'answer_question' . $i} : ' ';
          $templateProcessor->setValue('${pertanyaan' . $i . '}', $jawaban);
        }

        $templateProcessor->setValue('${tanggal}', $tanggal);
        $templateProcessor->setValue('${namaObserver}', $namaObserver);
        $templateProcessor->setValue('${nomorIndukObserver}', $nomorIndukObserver);

        // Sisipkan tanda tangan
        if (!empty($tandaTangan) && file_exists($tandaTangan)) {
          // Mengkonversi ukuran dari cm ke pixel
          $cm_to_px = 37.7952755906; // Faktor konversi dari cm ke pixel
          $width_cm = 3.5;
          $height_cm = 3.5;
          $width_px = $width_cm * $cm_to_px;
          $height_px = $height_cm * $cm_to_px;

          // Mengganti placeholder ${tandaTangan} dengan gambar tanda tangan
          $templateProcessor->setImageValue('tandaTangan', array('path' => $tandaTangan, 'width' => $width_px, 'height' => $height_px, 'ratio' => false));
        } else {
          // Jika tidak ada tanda tangan, kosongkan placeholder
          $templateProcessor->setValue('${tandaTangan}', ' ');
        }

        break;

      default:
        // Jenis File Tidak Diketahui
        exit;
    }

    // Simpan file .docx
    $templateProcessor->saveAs($filepath);

    // Update path file ke observer data untuk digunakan dalam link download
    switch ($jenisFile) {
      case 'PenilaianKegiatanMengajar':
        $observer->penilaian_file_path = $filepath;
        $observer->penilaian_download_link = site_url('downloadFile/' . $this->safe_encrypt($filepath) . '/PenilaianKegiatanMengajar.docx');
        $observer->penilaian_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/PenilaianKegiatanMengajar');
        break;
      case 'LembarPengamatanSiswa':
        $observer->pengamatan_file_path = $filepath;
        $observer->pengamatan_download_link = site_url('downloadFile/' . $this->safe_encrypt($filepath) . '/LembarPengamatanSiswa.docx');
        $observer->pengamatan_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/LembarPengamatanSiswa');
        break;
      case 'CatatanAktivitasSiswa':
        $observer->catatan_file_path = $filepath;
        $observer->catatan_download_link = site_url('downloadFile/' . $this->safe_encrypt($filepath) . '/CatatanAktivitasSiswa.docx');
        $observer->catatan_preview_link = site_url('previewForm/' . bin2hex($this->encryption->encrypt($idKelas)) . '/' . $observer->observer_user_id . '/CatatanAktivitasSiswa');
        break;
    }
  }

  /**
   * Memformat Ukuran File
   *
   * Mengubah ukuran file dari bytes ke format KB atau MB.
   *
   * @param string $file_path Path lengkap file
   * @return string Ukuran file dalam format KB atau MB atau ' ' jika tidak tersedia
   */
  private function formatFileSize($file_path)
  {
    if (file_exists($file_path)) {
      $size_in_kb = filesize($file_path) / 1024;
      if ($size_in_kb > 1024) {
        $size_in_mb = round($size_in_kb / 1024, 2) . ' MB';
        return $size_in_mb;
      } else {
        return round($size_in_kb, 2) . ' KB';
      }
    } else {
      return ' ';
    }
  }

  /**
   * Mengunduh File Secara Individual
   *
   * Mengunduh file berdasarkan path yang terenkripsi dan nama file yang diberikan.
   *
   * @param string $encrypted_path Path file yang terenkripsi
   * @param string $filename Nama file yang diunduh
   */
  public function downloadFile($encrypted_path, $filename)
  {
    // Dekripsi path yang dienkripsi (hex ke binari kemudian dekripsi)
    $encrypted_path_bin = hex2bin($encrypted_path);
    $file_path = $this->encryption->decrypt($encrypted_path_bin);

    // Memeriksa apakah path valid dan file ada di server
    if (!$file_path || !file_exists(FCPATH . $file_path)) {
      // Jika tidak valid atau file tidak ada, tampilkan halaman 404
      show_404();
    }

    // Mengatur nama file yang akan diunduh sesuai permintaan pengguna
    $new_filename = $filename;

    // Mengatur header untuk mengunduh file dengan benar
    $this->load->helper('download');
    force_download($new_filename, file_get_contents(FCPATH . $file_path));
  }

  /**
   * Mengunduh Semua Formulir sebagai Zip
   *
   * Mengambil semua formulir dari observer tertentu dan mengunduhnya dalam format zip.
   *
   * @param string $encrypted_classId ID Kelas yang terenkripsi
   * @param string $observerIds String ID Observer dipisahkan koma
   */
  public function downloadAllFormsZip($encrypted_classId, $observerIds)
  {
    // Dekripsi ID Kelas (hex ke binari kemudian dekripsi)
    $encrypted_classId_bin = hex2bin($encrypted_classId);
    $classId = $this->encryption->decrypt($encrypted_classId_bin);

    // Validasi input: pastikan classId dan observerIds tidak kosong
    if (empty($classId) || empty($observerIds)) {
      // Jika validasi gagal, tampilkan halaman 404
      show_404();
    }

    // Mengkonversi string observerIds menjadi array ID observer
    $observerIdsArray = explode(',', $observerIds);

    // Mengambil data kelas
    $class = $this->ClassModel->getClassById($classId);

    // Mengambil data observers
    $observers = $this->ClassObserver->getObserversWithDetails($classId);

    // Menyimpan file yang akan di-zip
    $files_to_zip = array();

    foreach ($observerIdsArray as $observerId) {
      // Mencari observer dalam data observers
      $observer = null;
      foreach ($observers as $obs) {
        if ($obs->observer_user_id == $observerId) {
          $observer = $obs;
          break;
        }
      }
      if (!$observer) {
        continue; // Jika observer tidak ditemukan, lewati
      }

      // Mendapatkan file Penilaian Kegiatan Mengajar dari direktori
      $penilaianDir = 'assets/media/penilaianKegiatanMengajar/';
      $penilaianPattern = $classId . '_' . $observerId . '_PenilaianKegiatanMengajar_*.docx';
      $penilaianFiles = glob($penilaianDir . $penilaianPattern);
      if ($penilaianFiles) {
        // Ambil file terbaru berdasarkan waktu modifikasi
        usort($penilaianFiles, function ($a, $b) {
          return filemtime($b) - filemtime($a);
        });
        $penilaianFile = $penilaianFiles[0];
        if (file_exists($penilaianFile)) {
          $files_to_zip[] = array(
            'path' => $penilaianFile,
            'name' => 'PenilaianKegiatanMengajar' . '.docx'
          );
        }
      }

      // Mendapatkan file Lembar Pengamatan Siswa dari direktori
      $pengamatanDir = 'assets/media/lembarPengamatanSiswa/';
      $pengamatanPattern = $classId . '_' . $observerId . '_LembarPengamatanSiswa_*.docx';
      $pengamatanFiles = glob($pengamatanDir . $pengamatanPattern);
      if ($pengamatanFiles) {
        // Ambil file terbaru berdasarkan waktu modifikasi
        usort($pengamatanFiles, function ($a, $b) {
          return filemtime($b) - filemtime($a);
        });
        $pengamatanFile = $pengamatanFiles[0];
        if (file_exists($pengamatanFile)) {
          $files_to_zip[] = array(
            'path' => $pengamatanFile,
            'name' => 'LembarPengamatanSiswa' . '.docx'
          );
        }
      }

      // Mendapatkan file Catatan Aktivitas Siswa dari direktori
      $catatanDir = 'assets/media/catatanAktivitasSiswa/';
      $catatanPattern = $classId . '_' . $observerId . '_CatatanAktivitasSiswa_*.docx';
      $catatanFiles = glob($catatanDir . $catatanPattern);
      if ($catatanFiles) {
        // Ambil file terbaru berdasarkan waktu modifikasi
        usort($catatanFiles, function ($a, $b) {
          return filemtime($b) - filemtime($a);
        });
        $catatanFile = $catatanFiles[0];
        if (file_exists($catatanFile)) {
          $files_to_zip[] = array(
            'path' => $catatanFile,
            'name' => 'CatatanAktivitasSiswa' . '.docx'
          );
        }
      }
    }

    // Memeriksa apakah ada file yang akan di-zip
    if (empty($files_to_zip)) {
      // Jika tidak ada file, tampilkan pesan error atau halaman 404
      $this->session->set_flashdata('error', 'Tidak ada file untuk diunduh.');
      redirect('pageKelasGuruModel/' . $encrypted_classId);
    }

    // Membuat file zip dengan nama yang diinginkan
    $zipName = 'BerkasObservasiPenilaian.zip';

    // Memuat library Zip dari CodeIgniter
    $this->load->library('zip');

    // Membersihkan data zip sebelumnya
    $this->zip->clear_data();

    // Menambahkan setiap file ke dalam zip dengan nama yang sesuai
    foreach ($files_to_zip as $file) {
      $this->zip->read_file($file['path'], $file['name']);
    }

    // Mengunduh file zip ke pengguna
    $this->zip->download($zipName);
  }

  /**
   * Preview Formulir Dokumen
   *
   * Menampilkan preview dokumen .docx berdasarkan ID Kelas terenkripsi, ID Observer, dan jenis formulir.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   * @param int $idObserver ID Observer
   * @param string $jenisForm Jenis Formulir (CatatanAktivitasSiswa, LembarPengamatanSiswa, PenilaianKegiatanMengajar)
   */
  public function previewForm($encrypted_idKelas, $idObserver, $jenisForm)
  {
    // Debugging: Log parameter yang diterima
    log_message('debug', "previewForm dipanggil dengan encrypted_idKelas: $encrypted_idKelas, idObserver: $idObserver, jenisForm: $jenisForm");

    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendekripsi ID Kelas
    $idKelas = $this->encryption->decrypt(hex2bin($encrypted_idKelas));

    // Debugging: Log hasil dekripsi
    log_message('debug', "ID Kelas setelah dekripsi: $idKelas");

    // Validasi ID Kelas
    if (!$idKelas) {
      log_message('error', "encrypted_idKelas tidak valid: $encrypted_idKelas");
      show_404();
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);
    if (!$class) {
      log_message('error', "Kelas tidak ditemukan untuk idKelas: $idKelas");
      show_404();
    }

    // Mendapatkan data observer berdasarkan ID Observer
    $observer = $this->ClassObserver->getObserver($idKelas, $idObserver);
    if (!$observer) {
      log_message('error', "Observer tidak ditemukan untuk idKelas: $idKelas, idObserver: $idObserver");
      show_404();
    }

    // Menentukan direktori berdasarkan jenis formulir
    $directories = [
      'CatatanAktivitasSiswa' => 'assets/media/catatanAktivitasSiswa/',
      'LembarPengamatanSiswa' => 'assets/media/lembarPengamatanSiswa/',
      'PenilaianKegiatanMengajar' => 'assets/media/penilaianKegiatanMengajar/'
    ];

    // Validasi jenis formulir
    if (!array_key_exists($jenisForm, $directories)) {
      log_message('error', "jenisForm tidak valid: $jenisForm");
      show_404();
    }

    $saveDir = $directories[$jenisForm];
    $filePattern = $idKelas . '_' . $idObserver . '_' . $jenisForm . '_*.docx';

    // Mencari file yang sesuai
    $files = glob($saveDir . $filePattern);

    // Debugging: Log jumlah file yang ditemukan
    log_message('debug', "Jumlah file yang ditemukan: " . count($files));

    if (empty($files)) {
      log_message('error', "Tidak ada file yang cocok dengan pola: $filePattern");
      show_404();
    }

    // Mengambil file terbaru berdasarkan waktu modifikasi
    usort($files, function ($a, $b) {
      return filemtime($b) - filemtime($a);
    });
    $latestFile = $files[0];

    // Memastikan file ada dan dapat diakses
    if (!file_exists($latestFile)) {
      log_message('error', "File tidak ada: $latestFile");
      show_404();
    }

    // Pisahkan path dan nama file
    $pathParts = pathinfo($latestFile);
    $encodedFilename = rawurlencode($pathParts['basename']);
    $encodedFilePath = $pathParts['dirname'] . '/' . $encodedFilename;

    // Membuat URL file dengan benar
    $file_url = base_url($encodedFilePath);

    // Debugging: Log URL file
    log_message('debug', "URL file: $file_url");

    // Mengirim data ke view
    $data = array(
      'file_url' => $file_url,
      'formType' => $jenisForm
    );

    // Memuat view 'previewForm' dengan data yang telah disiapkan
    $this->load->view('previewForm', $data);
  }

  /**
   * Menghapus Kelas Guru Model
   *
   * Menghapus kelas Guru Model berdasarkan $encrypted_idKelas, termasuk semua data terkait dalam database.
   * Juga menghapus file dalam direktori berdasarkan src file, termasuk file .docx dan tanda tangan, kecuali data rekaman suara dan file dokumentasi.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function hapusKelasGuruModel($encrypted_idKelas)
  {
    $this->checkLogin(); // Memastikan pengguna sudah login

    // Mendapatkan ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Validasi input: pastikan encrypted_idKelas memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      // Tampilkan pesan error dan redirect ke beranda
      $this->session->set_flashdata('error', 'Kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas); // Dekode dari heksadesimal ke biner
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    // Jika ID Kelas tidak valid, tampilkan pesan error dan redirect
    if (!$idKelas) {
      $this->session->set_flashdata('error', 'Kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      // Tampilkan halaman 404 jika kelas tidak ditemukan
      show_404();
    }

    // Memeriksa apakah pengguna adalah guru model yang membuat kelas ini
    if ($class->creator_user_id != $user_id) {
      // Jika bukan, tampilkan pesan error dan redirect ke beranda
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus kelas ini.');
      redirect('sidebarBeranda');
    }

    // Mengambil data assessments, observations, dan activityNotes
    $assessments = $this->TeachingActivityAssessment->getAssessmentsByClass($idKelas);
    $observations = $this->StudentObservationSheet->getObservationSheetsByClass($idKelas);
    $activityNotes = $this->StudentActivityNote->getActivityNotesByClass($idKelas);

    // Memulai transaksi database
    $this->db->trans_begin();

    try {
      // Menghapus data dari tabel ObservedStudents
      $this->ObservedStudent->deleteObservedStudentsByClass($idKelas);

      // Menghapus data dari tabel ClassObservers
      $this->ClassObserver->deleteObserversByClass($idKelas);

      // Menghapus data dari tabel TeachingActivityAssessment
      $this->TeachingActivityAssessment->deleteAssessmentsByClass($idKelas);

      // Menghapus data dari tabel StudentObservationDetail
      $this->StudentObservationDetail->deleteObservationDetailsByClass($idKelas);

      // Menghapus data dari tabel StudentObservationSheet
      $this->StudentObservationSheet->deleteObservationSheetsByClass($idKelas);

      // Menghapus data dari tabel StudentActivityNotes
      $this->StudentActivityNote->deleteActivityNotesByClass($idKelas);

      // Menghapus data dari tabel Notifications
      $this->Notification->deleteNotificationsByClass($idKelas);

      // Menghapus data dari tabel SpecialNotes
      $this->SpecialNote->deleteNotesByClass($idKelas);

      // Menghapus data dari tabel ClassDocumentationFiles
      $this->ClassDocumentationFile->deleteDocumentationsByClass($idKelas);

      // Menghapus data dari tabel ClassVoiceRecordings
      $this->ClassVoiceRecording->deleteRecordingsByClass($idKelas);

      // Menghapus data dari tabel Classes
      $this->ClassModel->deleteClass($idKelas);

      // Cek apakah ada error selama transaksi
      if ($this->db->trans_status() === FALSE) {
        // Jika ada error, rollback transaksi
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus kelas.');
        redirect('pageKelasGuruModel/' . $encrypted_idKelas);
      } else {
        // Jika berhasil, commit transaksi
        $this->db->trans_commit();

        // Menghapus file dalam direktori berdasarkan src file
        // Kecuali data rekaman suara dan file dokumentasi

        // Menghapus file src_student_data_file
        if (!empty($class->src_student_data_file) && file_exists(FCPATH . $class->src_student_data_file)) {
          unlink(FCPATH . $class->src_student_data_file);
        }

        // Menghapus file src_teaching_module_file
        if (!empty($class->src_teaching_module_file) && file_exists(FCPATH . $class->src_teaching_module_file)) {
          unlink(FCPATH . $class->src_teaching_module_file);
        }

        // Menghapus file src_learning_media_file
        if (!empty($class->src_learning_media_file) && file_exists(FCPATH . $class->src_learning_media_file)) {
          unlink(FCPATH . $class->src_learning_media_file);
        }

        // Menghapus file .docx untuk Catatan Aktivitas Siswa
        $dirCatatan = 'assets/media/catatanAktivitasSiswa/';
        $patternCatatan = $dirCatatan . $idKelas . '_*_CatatanAktivitasSiswa_*.docx';
        $filesCatatan = glob($patternCatatan);
        if ($filesCatatan) {
          foreach ($filesCatatan as $file) {
            if (file_exists($file)) {
              unlink($file);
            }
          }
        }

        // Menghapus file .docx untuk Lembar Pengamatan Siswa
        $dirPengamatan = 'assets/media/lembarPengamatanSiswa/';
        $patternPengamatan = $dirPengamatan . $idKelas . '_*_LembarPengamatanSiswa_*.docx';
        $filesPengamatan = glob($patternPengamatan);
        if ($filesPengamatan) {
          foreach ($filesPengamatan as $file) {
            if (file_exists($file)) {
              unlink($file);
            }
          }
        }

        // Menghapus file .docx untuk Penilaian Kegiatan Mengajar
        $dirPenilaian = 'assets/media/penilaianKegiatanMengajar/';
        $patternPenilaian = $dirPenilaian . $idKelas . '_*_PenilaianKegiatanMengajar_*.docx';
        $filesPenilaian = glob($patternPenilaian);
        if ($filesPenilaian) {
          foreach ($filesPenilaian as $file) {
            if (file_exists($file)) {
              unlink($file);
            }
          }
        }

        // Menghapus file tanda tangan untuk setiap formulir

        // Menghapus file tanda tangan dari assessments
        if ($assessments) {
          foreach ($assessments as $assessment) {
            if (!empty($assessment->src_signature_file) && file_exists(FCPATH . $assessment->src_signature_file)) {
              unlink(FCPATH . $assessment->src_signature_file);
            }
          }
        }

        // Menghapus file tanda tangan dari observations
        if ($observations) {
          foreach ($observations as $observation) {
            if (!empty($observation->src_signature_file) && file_exists(FCPATH . $observation->src_signature_file)) {
              unlink(FCPATH . $observation->src_signature_file);
            }
          }
        }

        // Menghapus file tanda tangan dari activityNotes
        if ($activityNotes) {
          foreach ($activityNotes as $activityNote) {
            if (!empty($activityNote->src_signature_file) && file_exists(FCPATH . $activityNote->src_signature_file)) {
              unlink(FCPATH . $activityNote->src_signature_file);
            }
          }
        }

        // Menetapkan flashdata sukses
        $this->session->set_flashdata('success', 'Kelas berhasil dihapus.');
        redirect('sidebarBeranda');
      }
    } catch (Exception $e) {
      // Jika terjadi exception, rollback transaksi
      $this->db->trans_rollback();
      $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus kelas.');
      redirect('pageKelasGuruModel/' . $encrypted_idKelas);
    }
  }

  /**
   * Mengarahkan ke Halaman Detail Kelas Saat Mengedit Kelas
   *
   * Mengarahkan pengguna ke halaman detail kelas untuk proses pengeditan kelas yang telah ada.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi.
   */
  public function pageEditKelas($encrypted_idKelas)
  {
    // Mengarahkan ke fungsi detail kelas dengan menyertakan parameter ID kelas yang terenkripsi
    redirect('pageEditKelas_detailKelas/' . $encrypted_idKelas);
  }

  /**
   * Menampilkan Halaman Edit Detail Kelas
   *
   * Membuka halaman editKelas1 dan mengambil data kelas dari database berdasarkan ID kelas yang terenkripsi.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi.
   */
  public function pageEditKelas_detailKelas($encrypted_idKelas)
  {
    $this->checkLogin();

    // Validasi input: pastikan encrypted_idKelas memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      // Tampilkan pesan error dan redirect ke beranda
      $this->session->set_flashdata('error', 'Kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas); // Dekode dari heksadesimal ke biner
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    // Jika ID Kelas tidak valid, tampilkan pesan error dan redirect
    if (!$idKelas) {
      $this->session->set_flashdata('error', 'Kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      // Tampilkan halaman 404 jika kelas tidak ditemukan
      show_404();
    }

    // Memeriksa apakah pengguna adalah guru model yang membuat kelas ini
    if ($class->creator_user_id != $this->user->user_id) {
      // Jika bukan, tampilkan pesan error dan redirect ke beranda
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarBeranda');
    }

    // Menyiapkan data untuk dikirim ke view editKelas1
    $data["title"] = "Edit Detail Kelas";
    $data["class"] = $class;
    $data["encrypted_idKelas"] = $encrypted_idKelas;

    // Memuat view dengan data
    $this->load->view('editKelas1', $data);
  }

  /**
   * Memproses Update Detail Kelas
   *
   * Mengirimkan update detail kelas ke database tanpa menyimpan ke session, kemudian redirect ke halaman edit kelas detail.
   */
  public function formUpdateDetailKelas()
  {
    $this->checkLogin();

    // Mengambil data POST dari form
    $encrypted_idKelas = $this->input->post('encrypted_idKelas');
    $class_name = $this->input->post('class_name');
    $school_name = $this->input->post('school_name');
    $subject = $this->input->post('subject');
    $basic_competency = $this->input->post('basic_competency');
    $date_input = $this->input->post('date');
    $start_time_input = $this->input->post('start_time');
    $end_time_input = $this->input->post('end_time');

    // Validasi data
    if (empty($encrypted_idKelas) || empty($class_name) || empty($school_name) || empty($subject) || empty($basic_competency) || empty($date_input) || empty($start_time_input) || empty($end_time_input)) {
      // Jika ada data yang kosong, redirect kembali ke form dengan pesan error
      $this->session->set_flashdata('error', 'Semua field wajib diisi.');
      redirect('pageEditKelas_detailKelas/' . $encrypted_idKelas);
    }

    // Validasi encrypted_idKelas
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Dekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas);
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    if (!$idKelas) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Konversi tanggal dari format DD/MM/YYYY ke YYYY-MM-DD
    $date_parts = explode('/', $date_input);
    if (count($date_parts) == 3) {
      $formatted_date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    } else {
      $formatted_date = NULL;
    }

    // Menyiapkan data untuk diupdate
    $update_data = array(
      'class_name'        => $class_name,
      'school_name'       => $school_name,
      'subject'           => $subject,
      'basic_competency'  => $basic_competency,
      'date'              => $formatted_date,
      'start_time'        => $start_time_input,
      'end_time'          => $end_time_input,
      'updated_at'        => date('Y-m-d H:i:s') // Memperbarui waktu update
    );

    // Melakukan update di database
    $update_success = $this->ClassModel->updateClass($idKelas, $update_data);

    if ($update_success) {
      // Mendapatkan daftar observer untuk kelas ini
      $observers = $this->ClassObserver->getObserversByClass($idKelas);
      $sender_id = $this->user->user_id; // Asumsi user saat ini adalah pengirim notifikasi

      // Mendefinisikan jenis notifikasi dan teks notifikasi
      $notification_type = 'Detail Observasi Diperbarui';
      $notification_text = 'Detail kelas telah diperbarui oleh Guru Model.';

      foreach ($observers as $observer) {
        $receiver_id = $observer->observer_user_id;
        $this->Notification->createNotification($sender_id, $receiver_id, $idKelas, $notification_text, $notification_type);
      }

      // Set flashdata sukses
      $this->session->set_flashdata('success', 'Selamat data anda sudah di simpan dan notifikasi telah dikirimkan.');
      // Redirect ke halaman detail kelas dengan ID kelas yang terenkripsi
      redirect('pageEditKelas_detailKelas/' . $encrypted_idKelas);
    } else {
      // Jika gagal update, set flashdata dan redirect kembali
      $this->session->set_flashdata('error', 'Gagal memperbarui detail kelas.');
      redirect('pageEditKelas_detailKelas/' . $encrypted_idKelas);
    }
  }

  /**
   * Menampilkan Halaman Unggah Berkas Edit Kelas
   *
   * Membuka halaman editKelas2 dan mengambil data src Data Siswa, Modul Ajar, dan Media pembelajaran dari database.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function pageEditKelas_unggahBerkas($encrypted_idKelas)
  {
    $this->checkLogin();

    // Validasi input
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Dekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas);
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    if (!$idKelas) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan data kelas dari database
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      show_404();
    }

    // Memeriksa apakah pengguna adalah pembuat kelas
    if ($class->creator_user_id != $this->session->userdata('user_id')) {
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarBeranda');
    }

    // Menyiapkan data untuk dikirim ke view editKelas2
    $data["title"] = "Edit Unggah Berkas";
    $data["class"] = $class;
    $data["encrypted_idKelas"] = $encrypted_idKelas;

    // Menyiapkan data 'class_files' untuk JavaScript
    $class_files = [
      'DataSiswa' => !empty($class->src_student_data_file),
      'ModulAjar' => !empty($class->src_teaching_module_file),
      'MediaPembelajaran' => !empty($class->src_learning_media_file)
    ];

    // Menyimpan 'class_files' dalam flashdata untuk JavaScript
    $this->session->set_flashdata('class_files', json_encode($class_files));

    // Memuat view editKelas2 dengan data
    $this->load->view('editKelas2', $data);
  }

  /**
   * Memproses Update Unggah Berkas Kelas
   *
   * Mengirimkan update berkas ke database tanpa menyimpan ke session, dan melakukan overwrite file.
   * Jika berhasil, mengirimkan notifikasi kepada observer.
   */
  public function formUpdateUnggahBerkas()
  {
    $this->checkLogin();

    // Mengambil data POST dari form
    $encrypted_idKelas = $this->input->post('encrypted_idKelas');
    $user_id = $this->session->userdata('user_id');

    // Validasi input
    if (empty($encrypted_idKelas)) {
      $response = ['status' => 'error', 'message' => 'ID kelas tidak valid.'];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
      exit;
    }

    // Dekripsi ID Kelas
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      $response = ['status' => 'error', 'message' => 'ID kelas tidak valid.'];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
      exit;
    }

    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas);
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    if (!$idKelas) {
      $response = ['status' => 'error', 'message' => 'ID kelas tidak valid.'];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
      exit;
    }

    // Mendapatkan data kelas dari database
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      $response = ['status' => 'error', 'message' => 'Kelas tidak ditemukan.'];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
      exit;
    }

    // Memeriksa apakah pengguna adalah pembuat kelas
    if ($class->creator_user_id != $user_id) {
      $response = ['status' => 'error', 'message' => 'Anda tidak memiliki akses ke kelas ini.'];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
      exit;
    }

    // Memeriksa apakah ada berkas yang diupload
    if (!empty($_FILES) && isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
      $type = $this->input->post('type');
      $upload_path = '';
      $allowed_types = '';

      // Menentukan path upload dan tipe file yang diizinkan berdasarkan jenis berkas
      switch ($type) {
        case 'DataSiswa':
          $upload_path = FCPATH . 'assets/media/dataSiswa/';
          $allowed_types = 'xls|xlsx|csv';
          break;
        case 'ModulAjar':
          $upload_path = FCPATH . 'assets/media/modulAjar/';
          $allowed_types = 'pdf';
          break;
        case 'MediaPembelajaran':
          $upload_path = FCPATH . 'assets/media/mediaPembelajaran/';
          $allowed_types = 'pdf';
          break;
        default:
          $response = ['status' => 'error', 'message' => 'Jenis berkas tidak valid.'];
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
          exit;
      }

      // Membuat direktori jika belum ada
      if (!is_dir($upload_path)) {
        if (!mkdir($upload_path, 0777, TRUE)) {
          $response = ['status' => 'error', 'message' => 'Gagal membuat direktori upload.'];
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
          exit;
        }
      }

      // Format tanggal sesuai timezone GMT+7 dengan spasi
      $date = gmdate('Y-m-d H-i-s', time() + 7 * 3600);

      // Mendapatkan ekstensi file
      $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      $original_file_name = $_FILES['file']['name']; // Menyimpan nama asli file

      // Menyusun nama file sesuai format: userID_jenisFile_tanggal_timestamp.extension
      $file_name = "{$user_id}_{$type}_{$date}.{$file_extension}";

      // Mengatur konfigurasi upload
      $config['upload_path']   = $upload_path;
      $config['allowed_types'] = $allowed_types;
      $config['file_name']     = $file_name;
      $config['max_size']      = '10240'; // Maksimal 10MB
      $config['overwrite']     = TRUE; // Overwrite file dengan nama yang sama
      $config['remove_spaces'] = FALSE; // Menjaga spasi dalam nama file

      // Menginisialisasi konfigurasi upload
      $this->upload->initialize($config);

      // Mendapatkan nama file yang saat ini disimpan dalam database untuk jenis berkas ini
      $current_file_field = '';
      switch ($type) {
        case 'DataSiswa':
          $current_file_field = 'src_student_data_file';
          break;
        case 'ModulAjar':
          $current_file_field = 'src_teaching_module_file';
          break;
        case 'MediaPembelajaran':
          $current_file_field = 'src_learning_media_file';
          break;
      }

      // Mendapatkan path file saat ini
      $current_file_path = $class->{$current_file_field};

      // Jika ada file saat ini, hapus file tersebut untuk overwrite
      if (!empty($current_file_path) && file_exists(FCPATH . $current_file_path)) {
        unlink(FCPATH . $current_file_path);
      }

      // Melakukan upload berkas
      if ($this->upload->do_upload('file')) {
        // Mendapatkan data upload
        $upload_data = $this->upload->data();

        // Menyiapkan data untuk diupdate di database
        $update_data = array(
          $current_file_field => 'assets/media/' . strtolower($type) . '/' . $file_name,
          'updated_at'        => date('Y-m-d H:i:s') // Memperbarui waktu update
        );

        // Melakukan update di database
        $update_success = $this->ClassModel->updateClass($idKelas, $update_data);

        if ($update_success) {
          // Mengirim notifikasi kepada observer
          // Mendapatkan daftar observer untuk kelas ini
          $observers = $this->ClassObserver->getObserversByClass($idKelas);

          // Membuat teks notifikasi berdasarkan jenis berkas
          switch ($type) {
            case 'DataSiswa':
              $notification_text = "Guru Model telah mengunggah Data Siswa baru.";
              break;
            case 'ModulAjar':
              $notification_text = "Guru Model telah mengunggah Modul Ajar baru.";
              break;
            case 'MediaPembelajaran':
              $notification_text = "Guru Model telah mengunggah Media Pembelajaran baru.";
              break;
            default:
              $notification_text = "Guru Model telah mengunggah berkas baru.";
              break;
          }

          $notification_type = "Berkas Diperbarui";

          // Mengirim notifikasi kepada setiap observer
          foreach ($observers as $observer) {
            $this->Notification->createNotification($user_id, $observer->observer_user_id, $idKelas, $notification_text, $notification_type);
          }

          // Mengembalikan respon JSON sukses
          $response = ['status' => 'success', 'message' => 'Berkas berhasil diupload.'];
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
          return;
        } else {
          // Jika gagal memperbarui database
          $response = ['status' => 'error', 'message' => 'Gagal memperbarui berkas kelas.'];
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
          return;
        }
      } else {
        // Jika upload gagal, kirim respon JSON error
        $error = $this->upload->display_errors('', '');
        $response = ['status' => 'error', 'message' => $error];
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
        return;
      }
    } else {
      // Jika tidak ada berkas yang diupload, kirim respon JSON error
      $response = ['status' => 'error', 'message' => 'Tidak ada berkas yang diunggah.'];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
      exit;
    }
  }

  /**
   * Menampilkan Halaman Detail Observer Edit Kelas
   *
   * Membuka halaman editKelas3 dan mengambil data daftar Observer beserta nomor siswa yang dipilih dari database.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function pageEditKelas_detailObserver($encrypted_idKelas)
  {
    $this->checkLogin();

    // Validasi input
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Dekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas);
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    if (!$idKelas) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan data kelas dari database
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      show_404();
    }

    // Memeriksa apakah pengguna adalah pembuat kelas
    if ($class->creator_user_id != $this->user->user_id) {
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarBeranda');
    }

    // Mendapatkan daftar observer dan nomor siswa yang dipilih
    $observers = $this->ClassObserver->getObserversWithDetails($idKelas);

    // Mengambil semua pengguna kecuali pengguna saat ini
    $users = $this->User->getAllUsersExcept($this->user->user_id);

    // Membuat array asosiatif pengguna dengan key user_id untuk akses cepat
    $users_assoc = [];
    foreach ($users as $user) {
      $users_assoc[$user->user_id] = $user;
    }

    // Memperkaya data observer dengan src_profile_photo dari database
    foreach ($observers as &$observer) {
      if (isset($users_assoc[$observer->observer_user_id])) {
        $observer->src_profile_photo = $users_assoc[$observer->observer_user_id]->src_profile_photo;
      } else {
        // Jika tidak ada, gunakan gambar default
        $observer->src_profile_photo = 'assets/default/default_profile_picture.jpg';
      }
    }
    unset($observer); // Hapus referensi

    // Mengonversi objek ke array
    $observers = json_decode(json_encode($observers), true);

    // Memetakan ke format yang diinginkan
    foreach ($observers as &$observer) {
      $observer['observerId'] = $observer['observer_user_id'];
      $observer['observerName'] = $observer['full_name'];
      $observer['nomorSiswa'] = implode(',', $observer['assigned_students']);
    }
    unset($observer);

    // Menyiapkan data untuk dikirim ke view editKelas3
    $data["title"] = "Edit Detail Observer";
    $data["class"] = $class;
    $data["saved_observers"] = $observers;
    $data["users"] = $users;
    $data["class_code"] = $class->class_code; // untuk classCode di JS
    $data["encrypted_idKelas"] = $encrypted_idKelas;
    $data["user"] = $this->user; // Menambahkan data pengguna saat ini

    // Memuat view dengan data yang telah disiapkan
    $this->load->view('editKelas3', $data);
  }

  /**
   * Memproses Update Detail Observer
   *
   * Mengirimkan update observer ke database dan melakukan redirect dengan menampilkan pesan sukses atau error.
   */
  public function formUpdateDetailObserver()
  {
    $this->checkLogin();

    // Mendapatkan data dari POST
    $encrypted_idKelas = $this->input->post('class_id');
    $observersDataJson = $this->input->post('observersData');

    if (!$observersDataJson) {
      $this->session->set_flashdata('error', 'Data tidak valid.');
      redirect('pageEditKelas_detailObserver/' . $encrypted_idKelas);
      exit;
    }

    $observers = json_decode($observersDataJson, true);

    if (!$observers) {
      $this->session->set_flashdata('error', 'Data tidak valid.');
      redirect('pageEditKelas_detailObserver/' . $encrypted_idKelas);
      exit;
    }

    // Dekripsi ID Kelas
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
      exit;
    }

    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas);
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    if (!$idKelas) {
      $this->session->set_flashdata('error', 'ID kelas tidak valid.');
      redirect('sidebarBeranda');
      exit;
    }

    // Mendapatkan data kelas dari database
    $class = $this->ClassModel->getClassById($idKelas);

    if (!$class) {
      $this->session->set_flashdata('error', 'Kelas tidak ditemukan.');
      redirect('sidebarBeranda');
      exit;
    }

    // Memeriksa apakah pengguna adalah pembuat kelas
    if ($class->creator_user_id != $this->user->user_id) {
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarBeranda');
      exit;
    }

    $user_id = $this->session->userdata('user_id');

    // Mendapatkan daftar observer yang ada dalam kelas ini
    $existing_observers = $this->ClassObserver->getObserversByClass($idKelas);
    $existing_observer_ids = array_column($existing_observers, 'observer_user_id');

    // Membuat array untuk observer baru dan yang dihapus
    $new_observer_ids = array_column($observers, 'observerId');
    $observers_to_remove = array_diff($existing_observer_ids, $new_observer_ids);
    $observers_to_add_or_update = $observers; // Semua observer dalam data baru

    // Menambahkan data dummy untuk catatan khusus
    $special_notes = array(
      array(
        "activity_type" => "Keaktifan siswa",
        "note_details" => "Siswa secara aktif terlibat dalam diskusi kelas dengan mengajukan pertanyaan yang relevan kepada guru."
      ),
      array(
        "activity_type" => "Siswa menjawab",
        "note_details" => "Beberapa siswa menunjukkan inisiatif dengan menjawab pertanyaan yang diajukan oleh guru. Tanggapan mereka mencerminkan pemahaman yang baik dan kemampuan untuk menerapkan konsep yang telah dipelajari."
      ),
      array(
        "activity_type" => "Presentasi",
        "note_details" => "Dua siswa mengambil peran aktif dalam presentasi proyek kelas. Mereka menyiapkan materi dengan baik, menyampaikan informasi secara jelas, dan mampu menjawab pertanyaan dari teman-teman sekelas dengan percaya diri."
      ),
      array(
        "activity_type" => "Siswa membuat kegaduhan",
        "note_details" => "Siswa nomor 4 dan 5 terlihat mengobrol dengan suara keras selama pelajaran berlangsung. Kegiatan ini mengganggu konsentrasi siswa lain dan menghambat proses belajar mengajar di kelas."
      ),
      array(
        "activity_type" => "Kehadiran siswa",
        "note_details" => "Sebagian besar siswa hadir tepat waktu setiap hari. Kehadiran yang konsisten ini menunjukkan komitmen mereka terhadap pembelajaran dan tanggung jawab terhadap pendidikan mereka."
      ),
      array(
        "activity_type" => "Kerja kelompok",
        "note_details" => "Siswa bekerja sama secara efektif dalam kelompok untuk menyelesaikan tugas proyek. Mereka mendistribusikan tugas dengan adil, berkomunikasi secara terbuka, dan menyelesaikan pekerjaan tepat waktu."
      ),
      array(
        "activity_type" => "Penggunaan perangkat",
        "note_details" => "Siswa memanfaatkan perangkat teknologi seperti laptop dan tablet dengan bijak untuk mendukung proses belajar. Mereka menggunakan aplikasi edukatif dan sumber daya online untuk memperdalam pemahaman materi."
      ),
      array(
        "activity_type" => "Disiplin kelas",
        "note_details" => "Siswa secara konsisten menunjukkan perilaku disiplin dengan mengikuti aturan kelas, menghormati guru dan teman-teman sekelas, serta menjaga lingkungan belajar yang kondusif dan teratur."
      )
    );

    // Menambahkan data dummy untuk file dokumentasi
    $documentation_files = array(
      'assets/default/default_documentation_1.jpg',
      'assets/default/default_documentation_2.jpg',
      'assets/default/default_documentation_3.jpg',
      'assets/default/default_documentation_4.jpg',
      'assets/default/default_documentation_5.jpg'
    );

    // Data dummy untuk rekaman suara
    $voice_recording = 'assets/media/audio/sample_audio.mp3';

    // Menghapus observer yang tidak ada dalam data baru
    foreach ($observers_to_remove as $observer_user_id) {
      // Hapus observer dan data terkait
      $this->ClassObserver->removeObserverWithRelatedData($idKelas, $observer_user_id);
      // Mengirim notifikasi ke observer bahwa mereka telah dihapus
      $this->Notification->createNotification($user_id, $observer_user_id, $idKelas, 'Anda telah dihapus dari observer kelas.', 'Observer Dihapus');
    }

    // Memproses observer yang ditambahkan atau diupdate
    foreach ($observers_to_add_or_update as $observer) {
      $observer_user_id = $observer['observerId'];
      $student_numbers  = explode(',', $observer['nomorSiswa']); // Array nomor siswa

      // Cek apakah observer sudah ada di kelas
      $existing_observer = $this->ClassObserver->getObserver($idKelas, $observer_user_id);
      if (!$existing_observer) {
        // Jika belum ada, tambahkan observer
        $this->ClassObserver->addObserver($idKelas, $observer_user_id);
        // Mengirim notifikasi ke observer bahwa mereka telah ditambahkan
        $this->Notification->createNotification($user_id, $observer_user_id, $idKelas, 'Anda telah ditambahkan sebagai observer.', 'Observer Ditambahkan');

        // Menambahkan catatan khusus per observer
        foreach ($special_notes as $note) {
          $note['class_id'] = $idKelas;
          $note['observer_user_id'] = $observer_user_id;
          $this->SpecialNote->createNote($note);
        }

        // Menambahkan file dokumentasi per observer
        foreach ($documentation_files as $file_src) {
          $this->ClassDocumentationFile->createDocumentation($idKelas, $observer_user_id, $file_src);
        }

        // Menambahkan rekaman suara per observer
        $this->ClassVoiceRecording->createRecording($idKelas, $observer_user_id, $voice_recording);
      }

      // Mendapatkan student_numbers yang sebelumnya ditugaskan ke observer ini
      $existing_student_numbers = $this->ObservedStudent->getStudentNumbersByObserver($idKelas, $observer_user_id);

      // Cari nomor siswa yang perlu dihapus dan ditambahkan
      $student_numbers = array_map('trim', $student_numbers);
      $student_numbers_to_remove = array_diff($existing_student_numbers, $student_numbers);
      $student_numbers_to_add = array_diff($student_numbers, $existing_student_numbers);

      // Menghapus ObservedStudents yang tidak ada dalam data baru
      foreach ($student_numbers_to_remove as $student_number) {
        $this->ObservedStudent->deleteObservedStudentWithRelatedData($idKelas, $observer_user_id, $student_number);
        // Mengirim notifikasi ke observer bahwa nomor siswa telah berubah
        $this->Notification->createNotification($user_id, $observer_user_id, $idKelas, 'Nomor siswa yang Anda amati telah berubah.', 'Nomor Siswa Berubah');
      }

      // Menambahkan ObservedStudents yang baru
      foreach ($student_numbers_to_add as $student_number) {
        $this->ObservedStudent->addObservedStudent($idKelas, $observer_user_id, $student_number);
        // Mengirim notifikasi ke observer bahwa nomor siswa telah berubah
        $this->Notification->createNotification($user_id, $observer_user_id, $idKelas, 'Nomor siswa yang Anda amati telah berubah.', 'Nomor Siswa Berubah');
      }
    }

    // Memperbarui 'updated_at' pada kelas
    $update_data = array(
      'updated_at' => date('Y-m-d H:i:s')
    );
    $this->ClassModel->updateClass($idKelas, $update_data);

    // Mengembalikan respon sukses dan redirect
    $this->session->set_flashdata('success', 'Detail observer berhasil diperbarui.');
    redirect('pageEditKelas_detailObserver/' . $encrypted_idKelas);
  }

  /**
   * Menampilkan Halaman Observer
   *
   * Menampilkan daftar kelas yang diikuti oleh pengguna sebagai Observer.
   */
  public function sidebarObserver()
  {
    // Mendapatkan ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Mendapatkan daftar kelas Observer dengan status
    $classes = $this->ClassObserver->getClassesWithStatusByObserver($user_id);

    // Untuk setiap kelas, ambil 4 observer terbaru dan enkripsi ID kelas
    foreach ($classes as $class) {
      $class->latest_observers = $this->ClassObserver->getLatestObservers($class->class_id, 4);

      // Enkripsi ID Kelas menggunakan bin2hex untuk aman di URL
      $encrypted_class_id = $this->encryption->encrypt($class->class_id);
      $encrypted_class_id = bin2hex($encrypted_class_id);

      // Simpan encrypted_class_id ke dalam objek kelas
      $class->encrypted_class_id = $encrypted_class_id;

      // Ambil nama guru model
      $guru_model = $this->User->getUserById($class->creator_user_id);
      $class->guru_model_name = $guru_model ? $guru_model->full_name : '';
    }

    // Menyiapkan data untuk view
    $data = array(
      "title"   => "Observer",
      "classes" => $classes,
      "user"    => $this->User->getUserById($user_id) // Pastikan data pengguna tersedia di view
    );

    // Memuat view 'Observer' dengan data yang sudah disiapkan
    $this->load->view('Observer', $data);
  }

  /**
   * Memproses Pencarian Kelas oleh Observer
   *
   * Mengembalikan informasi kelas berdasarkan kode kelas yang dimasukkan.
   */
  public function formGabungKelasObserver()
  {
    // Mendapatkan kode kelas dari POST
    $class_code = trim($this->input->post('class_code'));
    $user_id = $this->session->userdata('user_id');

    // Menyiapkan respons sebagai JSON
    header('Content-Type: application/json');

    // Validasi input: cek apakah kode kelas kosong
    if (empty($class_code)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Anda harus mengisi kode'
      ]);
      exit;
    }

    // Mendapatkan kelas berdasarkan kode kelas
    $class = $this->ClassModel->getClassByCode($class_code);

    if ($class) {
      // Cek apakah kelas dibuat oleh pengguna sendiri
      if ($class->creator_user_id == $user_id) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Anda tidak bisa menjadi observer pada kelas guru model Anda'
        ]);
        exit;
      }

      // Cek apakah pengguna sudah menjadi observer pada kelas ini
      $is_already_observer = $this->ClassObserver->isUserObserver($class->class_id, $user_id);
      if ($is_already_observer) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Anda sudah bergabung sebagai observer pada kelas ini'
        ]);
        exit;
      }

      // Mendapatkan nama guru model
      $guru_model = $this->User->getUserById($class->creator_user_id);
      $guru_model_name = $guru_model ? $guru_model->full_name : '';

      // Mendapatkan daftar nomor siswa yang sudah dipilih oleh observer lain
      $assigned_students = $this->ObservedStudent->getStudentNumbersByClass($class->class_id);

      echo json_encode([
        'status' => 'success',
        'guru_model' => $guru_model_name,
        'assigned_students' => $assigned_students
      ]);
      exit;
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Anda harus mengisi kode dengan benar'
      ]);
      exit;
    }
  }

  /**
   * Menyimpan Observer Baru dan Nomor Siswa yang Dipilih
   *
   * Menambahkan observer baru ke kelas guru model, termasuk data catatan khusus, file dokumentasi,
   * dan rekaman suara.
   *
   * @return void
   */
  public function simpanDataObserver()
  {
    // Mendapatkan data dari POST
    $class_code = trim($this->input->post('class_code'));
    $nomorSiswaId = $this->input->post('nomorSiswaId'); // Daftar nomor siswa yang dipilih
    $user_id = $this->session->userdata('user_id');

    // Validasi input: cek apakah class_code dan nomor siswa ada
    if (empty($class_code)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Kode kelas tidak ditemukan. Silakan coba lagi.'
      ]);
      exit;
    }

    // Mendapatkan kelas berdasarkan kode kelas
    $class = $this->ClassModel->getClassByCode($class_code);

    if ($class) {
      // Cek apakah kelas dibuat oleh pengguna sendiri
      if ($class->creator_user_id == $user_id) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Anda tidak bisa menjadi observer pada kelas guru model Anda.'
        ]);
        exit;
      }

      // Cek apakah pengguna sudah menjadi observer pada kelas ini
      $is_already_observer = $this->ClassObserver->isUserObserver($class->class_id, $user_id);
      if ($is_already_observer) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Anda sudah bergabung sebagai observer pada kelas ini.'
        ]);
        exit;
      }

      // Menambahkan data dummy untuk catatan khusus
      $special_notes = array(
        array(
          "activity_type" => "Keaktifan siswa",
          "note_details" => "Siswa secara aktif terlibat dalam diskusi kelas dengan mengajukan pertanyaan yang relevan kepada guru."
        ),
        array(
          "activity_type" => "Siswa menjawab",
          "note_details" => "Beberapa siswa menunjukkan inisiatif dengan menjawab pertanyaan yang diajukan oleh guru. Tanggapan mereka mencerminkan pemahaman yang baik dan kemampuan untuk menerapkan konsep yang telah dipelajari."
        ),
        array(
          "activity_type" => "Presentasi",
          "note_details" => "Dua siswa mengambil peran aktif dalam presentasi proyek kelas. Mereka menyiapkan materi dengan baik, menyampaikan informasi secara jelas, dan mampu menjawab pertanyaan dari teman-teman sekelas dengan percaya diri."
        ),
        array(
          "activity_type" => "Siswa membuat kegaduhan",
          "note_details" => "Siswa nomor 4 dan 5 terlihat mengobrol dengan suara keras selama pelajaran berlangsung. Kegiatan ini mengganggu konsentrasi siswa lain dan menghambat proses belajar mengajar di kelas."
        ),
        array(
          "activity_type" => "Kehadiran siswa",
          "note_details" => "Sebagian besar siswa hadir tepat waktu setiap hari. Kehadiran yang konsisten ini menunjukkan komitmen mereka terhadap pembelajaran dan tanggung jawab terhadap pendidikan mereka."
        ),
        array(
          "activity_type" => "Kerja kelompok",
          "note_details" => "Siswa bekerja sama secara efektif dalam kelompok untuk menyelesaikan tugas proyek. Mereka mendistribusikan tugas dengan adil, berkomunikasi secara terbuka, dan menyelesaikan pekerjaan tepat waktu."
        ),
        array(
          "activity_type" => "Penggunaan perangkat",
          "note_details" => "Siswa memanfaatkan perangkat teknologi seperti laptop dan tablet dengan bijak untuk mendukung proses belajar. Mereka menggunakan aplikasi edukatif dan sumber daya online untuk memperdalam pemahaman materi."
        ),
        array(
          "activity_type" => "Disiplin kelas",
          "note_details" => "Siswa secara konsisten menunjukkan perilaku disiplin dengan mengikuti aturan kelas, menghormati guru dan teman-teman sekelas, serta menjaga lingkungan belajar yang kondusif dan teratur."
        )
      );

      // Menambahkan data dummy untuk file dokumentasi
      $documentation_files = array(
        'assets/default/default_documentation_1.jpg',
        'assets/default/default_documentation_2.jpg',
        'assets/default/default_documentation_3.jpg',
        'assets/default/default_documentation_4.jpg',
        'assets/default/default_documentation_5.jpg'
      );

      // Data dummy untuk rekaman suara
      $voice_recording = 'assets/media/audio/sample_audio.mp3';

      // Menambahkan pengguna sebagai observer
      $this->ClassObserver->addObserver($class->class_id, $user_id);

      // Menambahkan catatan khusus
      foreach ($special_notes as $note) {
        $note['class_id'] = $class->class_id;
        $note['observer_user_id'] = $user_id;
        $this->SpecialNote->createNote($note);
      }

      // Menambahkan file dokumentasi
      foreach ($documentation_files as $file_src) {
        $this->ClassDocumentationFile->createDocumentation($class->class_id, $user_id, $file_src);
      }

      // Menambahkan rekaman suara
      $this->ClassVoiceRecording->createRecording($class->class_id, $user_id, $voice_recording);

      // Simpan nomor siswa yang dipilih
      if (!empty($nomorSiswaId)) {
        $nomorSiswaArray = explode(',', $nomorSiswaId);
        foreach ($nomorSiswaArray as $nomor) {
          // Validasi nomor siswa
          $nomor = trim($nomor);
          if (is_numeric($nomor) && $nomor >= 1 && $nomor <= 100) {
            $this->ObservedStudent->addObservedStudent($class->class_id, $user_id, $nomor);
          }
        }
      }

      // Mengirim notifikasi ke Guru Model
      $this->Notification->createNotification(
        $user_id,
        $class->creator_user_id,
        $class->class_id,
        'Seorang observer telah bergabung.',
        'Observer Bergabung'
      );

      // Mengirim respons sukses dengan pesan tambahan
      echo json_encode([
        'status' => 'success',
        'message' => 'Anda berhasil bergabung sebagai observer pada kelas ini.',
        'redirect_url' => site_url('sidebarObserver')
      ]);
      exit;
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Kode kelas tidak valid.'
      ]);
      exit;
    }
  }

  /**
   * Menampilkan Halaman Penilaian Kegiatan Mengajar oleh Observer
   *
   * Menampilkan form penilaian kegiatan mengajar yang dapat diisi oleh observer.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function pageKelasObserver1($encrypted_idKelas)
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Mendapatkan data pengguna berdasarkan ID
    $user = $this->User->getUserById($user_id);

    // Jika pengguna tidak ditemukan, tampilkan error 404
    if (!$user) {
      show_error('Pengguna tidak ditemukan.', 404);
    }

    // Validasi input: pastikan encrypted_idKelas memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      show_error('Kelas tidak valid.', 404);
    }

    // Mendekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas); // Dekode dari heksadesimal ke biner
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    // Jika ID Kelas tidak valid, tampilkan error
    if (!$idKelas) {
      show_error('Kelas tidak valid.', 404);
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);

    // Jika kelas tidak ditemukan, tampilkan halaman 404
    if (!$class) {
      show_404();
    }

    // Mendapatkan waktu mulai kelas
    $class_start_datetime = new DateTime($class->date . ' ' . $class->start_time);
    $current_datetime = new DateTime();

    // Jika kelas belum dimulai, tampilkan pesan error dan redirect
    if ($current_datetime < $class_start_datetime) {
      $this->session->set_flashdata('class_not_started', 'Kelas belum dimulai.');
      redirect('sidebarBeranda'); // Redirect ke halaman beranda atau halaman sebelumnya
      exit;
    }

    // Mengecek apakah pengguna adalah observer terdaftar dalam kelas ini
    $is_observer = $this->ClassObserver->isUserObserver($idKelas, $user_id);

    if (!$is_observer) {
      // Jika bukan observer, tampilkan pesan error dan arahkan ke halaman sidebarObserver()
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarObserver');
      exit;
    }

    // Menghitung jumlah siswa unik dalam kelas
    $unique_students = $this->ObservedStudent->getUniqueStudentNumbersByClass($idKelas);
    $number_of_students = count($unique_students);

    // Menambahkan jumlah siswa ke data kelas
    $class->number_of_students = $number_of_students;

    // Mendapatkan ekstensi asli file modul ajar dan media pembelajaran
    if (!empty($class->src_student_data_file)) {
      $class->student_file_ext = strtolower(pathinfo($class->src_student_data_file, PATHINFO_EXTENSION));
    } else {
      $class->student_file_ext = 'csv';
    }

    if (!empty($class->src_teaching_module_file)) {
      $class->modul_file_ext = strtolower(pathinfo($class->src_teaching_module_file, PATHINFO_EXTENSION));
    } else {
      $class->modul_file_ext = 'pdf';
    }

    if (!empty($class->src_learning_media_file)) {
      $class->media_file_ext = strtolower(pathinfo($class->src_learning_media_file, PATHINFO_EXTENSION));
    } else {
      $class->media_file_ext = 'pdf';
    }

    // Mendapatkan penilaian yang sudah ada untuk kelas ini dan pengguna
    $assessment = $this->TeachingActivityAssessment->getAssessment($idKelas, $user_id);

    // Menyiapkan data untuk view
    $data = array(
      'title'              => 'Penilaian Kegiatan Mengajar',
      'class'              => $class,
      'user'               => $user,        // Mengirim data pengguna ke view
      'assessment'         => $assessment,
      'encrypted_class_id' => bin2hex($this->encryption->encrypt($idKelas))   // Mengirim ID kelas terenkripsi ke view
    );

    // Memuat view 'kelasObserver1' dengan data yang telah disiapkan
    $this->load->view('kelasObserver1', $data);
  }

  /**
   * Memproses Penilaian Kegiatan Mengajar oleh Observer
   *
   * Menyimpan atau memperbarui penilaian kegiatan mengajar yang diisi oleh observer.
   */
  public function formPenilaianKegiatanMengajar()
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan data dari form dengan sanitasi input
    $encrypted_class_id = $this->input->post('class_id', true);
    $observer_user_id = $this->session->userdata('user_id');

    // Validasi input: pastikan encrypted_class_id memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_class_id) % 2 !== 0 || !ctype_xdigit($encrypted_class_id)) {
      show_error('Data tidak lengkap atau tidak valid.', 400);
    }

    // Mendekripsi ID Kelas
    $encrypted_class_id_bin = hex2bin($encrypted_class_id); // Dekode dari heksadesimal ke biner
    $class_id = $this->encryption->decrypt($encrypted_class_id_bin);

    // Validasi input: pastikan class_id dan observer_user_id tidak kosong
    if (empty($class_id) || empty($observer_user_id)) {
      show_error('Data tidak lengkap.', 400);
    }

    // Mengecek apakah pengguna adalah observer terdaftar dalam kelas ini
    $is_observer = $this->ClassObserver->isUserObserver($class_id, $observer_user_id);

    if (!$is_observer) {
      // Jika bukan observer, tampilkan pesan error dan arahkan ke halaman sidebarObserver()
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengisi formulir ini.');
      redirect('sidebarObserver');
      exit;
    }

    // Mendapatkan dan memvalidasi skor untuk setiap indikator
    $scores = array();
    for ($i = 1; $i <= 10; $i++) {
      $score = $this->input->post("score_question$i", true);
      if ($score !== '' && !in_array($score, array('1', '2', '3', '4'))) {
        show_error('Skor tidak valid.', 400);
      }
      // Jika skor kosong, set sebagai null
      $scores["score_question$i"] = ($score !== '') ? (int)$score : null;
    }

    // Menghitung total skor (hanya skor yang diisi)
    $total_score = 0;
    foreach ($scores as $score) {
      if ($score !== null) {
        $total_score += $score;
      }
    }

    // Menghitung konversi nilai (TOTAL SKOR / 40 * 100)
    $converted_value = ($total_score / 40) * 100;

    // Menangani file tanda tangan yang diunggah
    $signature_data = $this->input->post('src_signature_file');
    $signature_changed = $this->input->post('signatureChanged'); // Mendapatkan nilai signatureChanged

    // Mendapatkan penilaian yang sudah ada
    $existing_assessment = $this->TeachingActivityAssessment->getAssessment($class_id, $observer_user_id);

    if ($signature_changed == '1') {
      // Tanda tangan telah diubah
      if (!empty($signature_data)) {
        // Decode base64
        $signature = base64_decode(explode(',', $signature_data)[1]);

        // Membuat nama file sesuai format
        $tanggal_waktu = date('Y-m-d H-i-s'); // Mengganti ':' dengan '-' untuk kompatibilitas sistem file
        $filename = "{$class_id}_{$observer_user_id}_Formulir1_{$tanggal_waktu}.png";

        // Menentukan path untuk menyimpan tanda tangan
        $filepath = FCPATH . "assets/media/tandaTanganFormulir/" . $filename;

        // Simpan file tanda tangan ke server
        file_put_contents($filepath, $signature);

        // Cek dan hapus file duplikasi jika ada (menyisakan hanya file terbaru)
        $existing_files = glob(FCPATH . "assets/media/tandaTanganFormulir/{$class_id}_{$observer_user_id}_Formulir1_*.png");
        foreach ($existing_files as $file) {
          if ($file !== $filepath) {
            unlink($file);
          }
        }

        // Menyimpan path relatif ke file tanda tangan
        $src_signature_file = "assets/media/tandaTanganFormulir/" . $filename;
      } else {
        // Tanda tangan telah dihapus oleh pengguna
        $src_signature_file = null;

        // Hapus file tanda tangan lama jika ada
        if ($existing_assessment && !empty($existing_assessment->src_signature_file)) {
          $old_signature_path = FCPATH . $existing_assessment->src_signature_file;
          if (file_exists($old_signature_path)) {
            unlink($old_signature_path);
          }
        }
      }
    } else {
      // Tanda tangan tidak diubah, gunakan yang lama
      if ($existing_assessment && !empty($existing_assessment->src_signature_file)) {
        $src_signature_file = $existing_assessment->src_signature_file;
      } else {
        $src_signature_file = null;
      }
    }

    // Menggabungkan data skor dan data lainnya
    $data = array_merge($scores, array(
      'class_id'           => $class_id,
      'observer_user_id'   => $observer_user_id,
      'total_score'        => $total_score,
      'converted_value'    => $converted_value,
      'notes'              => $this->input->post('catatan', true),
      'src_signature_file' => $src_signature_file,
    ));

    if ($existing_assessment) {
      // Jika penilaian sudah ada, perbarui data yang ada tanpa mengubah 'created_at'
      $data['updated_at'] = date('Y-m-d H:i:s');
      $this->TeachingActivityAssessment->updateAssessment($existing_assessment->assessment_id, $data);
    } else {
      // Jika penilaian belum ada, buat penilaian baru dengan 'created_at'
      $data['created_at'] = date('Y-m-d H:i:s');
      $this->TeachingActivityAssessment->createAssessment($data);
    }

    // Mengecek apakah semua formulir telah diisi untuk kelas ini
    $all_forms_completed = $this->checkAllFormsCompleted($class_id, $observer_user_id);

    if ($all_forms_completed) {
      // Mengirim notifikasi ke Guru Model jika semua formulir telah diisi
      $class = $this->ClassModel->getClassById($class_id);
      $this->Notification->createNotification(
        $observer_user_id,
        $class->creator_user_id,
        $class_id,
        'Semua formulir telah diisi oleh observer.',
        'Formulir Diisi'
      );
    }

    // Menetapkan flashdata sukses untuk ditampilkan di halaman berikutnya
    $this->session->set_flashdata('success', 'Formulir berhasil disimpan.');

    // Redirect kembali ke halaman penilaian
    redirect('pageKelasObserver1/' . bin2hex($this->encryption->encrypt($class_id)));
  }

  /**
   * Menampilkan Halaman Catatan Aktivitas Siswa oleh Observer
   *
   * Menampilkan form catatan aktivitas siswa yang dapat diisi oleh observer.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function pageKelasObserver2($encrypted_idKelas)
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Mendapatkan data pengguna berdasarkan ID
    $user = $this->User->getUserById($user_id);

    // Jika pengguna tidak ditemukan, tampilkan error 404
    if (!$user) {
      show_error('Pengguna tidak ditemukan.', 404);
    }

    // Validasi input: pastikan encrypted_idKelas memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      show_error('Kelas tidak valid.', 404);
    }

    // Mendekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas); // Dekode dari heksadesimal ke biner
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    // Jika ID Kelas tidak valid, tampilkan error
    if (!$idKelas) {
      show_error('Kelas tidak valid.', 404);
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);

    // Jika kelas tidak ditemukan, tampilkan halaman 404
    if (!$class) {
      show_404();
    }

    // Mendapatkan waktu mulai kelas
    $class_start_datetime = new DateTime($class->date . ' ' . $class->start_time);
    $current_datetime = new DateTime();

    // Jika kelas belum dimulai, tampilkan pesan error dan redirect
    if ($current_datetime < $class_start_datetime) {
      $this->session->set_flashdata('class_not_started', 'Kelas belum dimulai.');
      redirect('sidebarBeranda'); // Redirect ke halaman beranda atau halaman sebelumnya
      exit;
    }

    // Mengecek apakah pengguna adalah observer terdaftar dalam kelas ini
    $is_observer = $this->ClassObserver->isUserObserver($idKelas, $user_id);

    if (!$is_observer) {
      // Jika bukan observer, tampilkan pesan error dan arahkan ke halaman sidebarObserver()
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarObserver');
      exit;
    }

    // Menghitung jumlah siswa unik dalam kelas
    $unique_students = $this->ObservedStudent->getUniqueStudentNumbersByClass($idKelas);
    $number_of_students = count($unique_students);

    // Menambahkan jumlah siswa ke data kelas
    $class->number_of_students = $number_of_students;

    // Mendapatkan ekstensi asli file modul ajar dan media pembelajaran
    if (!empty($class->src_student_data_file)) {
      $class->student_file_ext = strtolower(pathinfo($class->src_student_data_file, PATHINFO_EXTENSION));
    } else {
      $class->student_file_ext = 'csv';
    }

    if (!empty($class->src_teaching_module_file)) {
      $class->modul_file_ext = strtolower(pathinfo($class->src_teaching_module_file, PATHINFO_EXTENSION));
    } else {
      $class->modul_file_ext = 'pdf';
    }

    if (!empty($class->src_learning_media_file)) {
      $class->media_file_ext = strtolower(pathinfo($class->src_learning_media_file, PATHINFO_EXTENSION));
    } else {
      $class->media_file_ext = 'pdf';
    }

    // Mendapatkan lembar pengamatan yang sudah ada untuk kelas ini dan pengguna
    $observation_sheet = $this->StudentObservationSheet->getObservationSheet($idKelas, $user_id);

    // Mendapatkan detail pengamatan jika ada
    $observation_details = array();
    if ($observation_sheet) {
      $details = $this->StudentObservationDetail->getObservationDetails($observation_sheet->observation_id);
      foreach ($details as $detail) {
        $observation_details[$detail->indicator_number][$detail->student_number] = $detail->value;
      }
    }

    // Mengubah data detail pengamatan ke format JSON untuk digunakan di JavaScript
    $observation_details_json = json_encode($observation_details);

    // Mendapatkan daftar nomor siswa yang diamati oleh observer
    $student_numbers = $this->ObservedStudent->getStudentNumbersByObserver($class->class_id, $user_id);

    // Jika $student_numbers kosong, tampilkan pesan error
    if (empty($student_numbers)) {
      // Tampilkan pesan error menggunakan flashdata
      $this->session->set_flashdata('error', 'Anda belum memilih siswa yang akan diamati.');
      // Redirect ke halaman sebelumnya atau halaman yang sesuai
      redirect('sidebarObserver');
      exit;
    }

    // Menyiapkan data untuk view
    $data = array(
      'title'                    => 'Lembar Pengamatan Siswa',
      'class'                    => $class,
      'user'                     => $user,
      'student_numbers'          => $student_numbers,
      'observation_sheet'        => $observation_sheet,
      'observation_details'      => $observation_details,
      'observation_details_json' => $observation_details_json,
      'encrypted_class_id'       => bin2hex($this->encryption->encrypt($idKelas))
    );

    // Memuat view 'kelasObserver2' dengan data yang telah disiapkan
    $this->load->view('kelasObserver2', $data);
  }

  /**
   * Memproses Lembar Pengamatan Siswa oleh Observer
   *
   * Menyimpan atau memperbarui lembar pengamatan siswa yang diisi oleh observer.
   */
  public function formLembarPengamatanSiswa()
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan data dari form dengan sanitasi input
    $encrypted_class_id = $this->input->post('class_id', true);
    $observer_user_id = $this->session->userdata('user_id');

    // Validasi input: pastikan encrypted_class_id memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_class_id) % 2 !== 0 || !ctype_xdigit($encrypted_class_id)) {
      show_error('Data tidak lengkap atau tidak valid.', 400);
    }

    // Mendekripsi ID Kelas
    $encrypted_class_id_bin = hex2bin($encrypted_class_id); // Dekode dari heksadesimal ke biner
    $class_id = $this->encryption->decrypt($encrypted_class_id_bin);

    // Validasi input: pastikan class_id dan observer_user_id tidak kosong
    if (empty($class_id) || empty($observer_user_id)) {
      show_error('Data tidak lengkap.', 400);
    }

    // Mengecek apakah pengguna adalah observer terdaftar dalam kelas ini
    $is_observer = $this->ClassObserver->isUserObserver($class_id, $observer_user_id);

    if (!$is_observer) {
      // Jika bukan observer, tampilkan pesan error dan arahkan ke halaman sidebarObserver()
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengisi formulir ini.');
      redirect('sidebarObserver');
      exit;
    }

    // Mendapatkan data catatan dan tanda tangan
    $notes = $this->input->post('notes', true);
    $signature_data = $this->input->post('src_signature_file');
    $signature_changed = $this->input->post('signatureChanged'); // Mendapatkan nilai signatureChanged

    // Mendapatkan lembar pengamatan yang sudah ada
    $existing_observation = $this->StudentObservationSheet->getObservationSheet($class_id, $observer_user_id);

    if ($signature_changed == '1') {
      // Tanda tangan telah diubah
      if (!empty($signature_data)) {
        // Decode base64
        $signature = base64_decode(explode(',', $signature_data)[1]);

        // Membuat nama file sesuai format
        $tanggal_waktu = date('Y-m-d H-i-s'); // Mengganti ':' dengan '-' untuk kompatibilitas sistem file
        $filename = "{$class_id}_{$observer_user_id}_Formulir2_{$tanggal_waktu}.png";

        // Menentukan path untuk menyimpan tanda tangan
        $filepath = FCPATH . "assets/media/tandaTanganFormulir/" . $filename;

        // Simpan file tanda tangan ke server
        file_put_contents($filepath, $signature);

        // Cek dan hapus file duplikasi jika ada (menyisakan hanya file terbaru)
        $existing_files = glob(FCPATH . "assets/media/tandaTanganFormulir/{$class_id}_{$observer_user_id}_Formulir2_*.png");
        foreach ($existing_files as $file) {
          if ($file !== $filepath) {
            unlink($file);
          }
        }

        // Menyimpan path relatif ke file tanda tangan
        $src_signature_file = "assets/media/tandaTanganFormulir/" . $filename;
      } else {
        // Tanda tangan telah dihapus oleh pengguna
        $src_signature_file = null;

        // Hapus file tanda tangan lama jika ada
        if ($existing_observation && !empty($existing_observation->src_signature_file)) {
          $old_signature_path = FCPATH . $existing_observation->src_signature_file;
          if (file_exists($old_signature_path)) {
            unlink($old_signature_path);
          }
        }
      }
    } else {
      // Tanda tangan tidak diubah, gunakan yang lama
      if ($existing_observation && !empty($existing_observation->src_signature_file)) {
        $src_signature_file = $existing_observation->src_signature_file;
      } else {
        $src_signature_file = null;
      }
    }

    // Mendapatkan detail pengamatan dari form (indicator_number, student_number)
    $observation_details_input_json = $this->input->post('observation_details'); // Data JSON dari JavaScript

    // Decode JSON menjadi array PHP
    $observation_details_input = json_decode($observation_details_input_json, true);

    // Mendapatkan daftar nomor siswa yang diamati oleh observer
    $student_numbers = $this->ObservedStudent->getStudentNumbersByObserver($class_id, $observer_user_id);

    // Mendapatkan daftar semua indikator (1-18)
    $all_indicators = range(1, 18);

    // Siapkan data untuk lembar pengamatan
    $data = array(
      'class_id'           => $class_id,
      'observer_user_id'   => $observer_user_id,
      'notes'              => $notes,
      'src_signature_file' => $src_signature_file
    );

    if ($existing_observation) {
      // Jika lembar pengamatan sudah ada, perbarui data yang ada tanpa mengubah 'created_at'
      $data['updated_at'] = date('Y-m-d H:i:s');
      $this->StudentObservationSheet->updateObservationSheet($existing_observation->observation_id, $data);
      $observation_id = $existing_observation->observation_id;
    } else {
      // Jika lembar pengamatan belum ada, buat lembar pengamatan baru dengan 'created_at'
      $data['created_at'] = date('Y-m-d H:i:s');
      $observation_id = $this->StudentObservationSheet->createObservationSheet($data);
    }

    // Menghapus detail pengamatan sebelumnya jika ada
    $this->StudentObservationDetail->deleteObservationDetails($observation_id);

    // Menyimpan detail pengamatan
    $details_data = array();
    foreach ($all_indicators as $indicator_number) {
      foreach ($student_numbers as $student_number) {
        // Cek apakah ada nilai yang disubmit, jika tidak set 0
        $value = 0;
        if (isset($observation_details_input[$indicator_number]) && isset($observation_details_input[$indicator_number][$student_number])) {
          $value = $observation_details_input[$indicator_number][$student_number] ? 1 : 0;
        }

        $details_data[] = array(
          'observation_id'    => $observation_id,
          'student_number'    => $student_number,
          'indicator_number'  => $indicator_number,
          'value'             => $value, // Mengatur nilai 1 atau 0
          'created_at'        => date('Y-m-d H:i:s'),
          'updated_at'        => date('Y-m-d H:i:s')
        );
      }
    }

    // Menyimpan detail pengamatan
    if (!empty($details_data)) {
      $this->StudentObservationDetail->addObservationDetail($details_data);
    }

    // Mengecek apakah semua formulir telah diisi untuk kelas ini
    $all_forms_completed = $this->checkAllFormsCompleted($class_id, $observer_user_id);

    if ($all_forms_completed) {
      // Mengirim notifikasi ke Guru Model jika semua formulir telah diisi
      $class = $this->ClassModel->getClassById($class_id);
      $this->Notification->createNotification(
        $observer_user_id,
        $class->creator_user_id,
        $class_id,
        'Semua formulir telah diisi oleh observer.',
        'Formulir Diisi'
      );
    }

    // Menetapkan flashdata sukses untuk ditampilkan di halaman berikutnya
    $this->session->set_flashdata('success', 'Formulir berhasil disimpan.');

    // Redirect kembali ke halaman pengamatan siswa
    redirect('pageKelasObserver2/' . bin2hex($this->encryption->encrypt($class_id)));
  }

  /**
   * Menampilkan Halaman Catatan Aktivitas Siswa oleh Observer
   *
   * Menampilkan form catatan aktivitas siswa yang dapat diisi oleh observer.
   *
   * @param string $encrypted_idKelas ID kelas yang terenkripsi
   */
  public function pageKelasObserver3($encrypted_idKelas)
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan ID pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Mendapatkan data pengguna berdasarkan ID
    $user = $this->User->getUserById($user_id);

    // Jika pengguna tidak ditemukan, tampilkan error 404
    if (!$user) {
      show_error('Pengguna tidak ditemukan.', 404);
    }

    // Validasi input: pastikan encrypted_idKelas memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_idKelas) % 2 !== 0 || !ctype_xdigit($encrypted_idKelas)) {
      show_error('Kelas tidak valid.', 404);
    }

    // Mendekripsi ID Kelas
    $encrypted_idKelas_bin = hex2bin($encrypted_idKelas); // Dekode dari heksadesimal ke biner
    $idKelas = $this->encryption->decrypt($encrypted_idKelas_bin);

    // Jika ID Kelas tidak valid, tampilkan error
    if (!$idKelas) {
      show_error('Kelas tidak valid.', 404);
    }

    // Mendapatkan data kelas berdasarkan ID Kelas
    $class = $this->ClassModel->getClassById($idKelas);

    // Jika kelas tidak ditemukan, tampilkan halaman 404
    if (!$class) {
      show_404();
    }

    // Mendapatkan waktu mulai kelas
    $class_start_datetime = new DateTime($class->date . ' ' . $class->start_time);
    $current_datetime = new DateTime();

    // Jika kelas belum dimulai, tampilkan pesan error dan redirect
    if ($current_datetime < $class_start_datetime) {
      $this->session->set_flashdata('class_not_started', 'Kelas belum dimulai.');
      redirect('sidebarBeranda'); // Redirect ke halaman beranda atau halaman sebelumnya
      exit;
    }

    // Mengecek apakah pengguna adalah observer terdaftar dalam kelas ini
    $is_observer = $this->ClassObserver->isUserObserver($idKelas, $user_id);

    if (!$is_observer) {
      // Jika bukan observer, tampilkan pesan error dan arahkan ke halaman sidebarObserver()
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kelas ini.');
      redirect('sidebarObserver');
      exit;
    }

    // Menghitung jumlah siswa unik dalam kelas
    $unique_students = $this->ObservedStudent->getUniqueStudentNumbersByClass($idKelas);
    $number_of_students = count($unique_students);

    // Menambahkan jumlah siswa ke data kelas
    $class->number_of_students = $number_of_students;

    // Mendapatkan ekstensi asli file modul ajar dan media pembelajaran
    if (!empty($class->src_student_data_file)) {
      $class->student_file_ext = strtolower(pathinfo($class->src_student_data_file, PATHINFO_EXTENSION));
    } else {
      $class->student_file_ext = 'csv';
    }

    if (!empty($class->src_teaching_module_file)) {
      $class->modul_file_ext = strtolower(pathinfo($class->src_teaching_module_file, PATHINFO_EXTENSION));
    } else {
      $class->modul_file_ext = 'pdf';
    }

    if (!empty($class->src_learning_media_file)) {
      $class->media_file_ext = strtolower(pathinfo($class->src_learning_media_file, PATHINFO_EXTENSION));
    } else {
      $class->media_file_ext = 'pdf';
    }

    // Mendapatkan catatan aktivitas siswa yang sudah ada untuk kelas ini dan pengguna
    $activity_note = $this->StudentActivityNote->getActivityNote($idKelas, $user_id);

    // Menyiapkan data untuk view
    $data = array(
      'title'              => 'Catatan Aktivitas Siswa',
      'class'              => $class,
      'user'               => $user,          // Mengirim data pengguna ke view
      'activity_note'      => $activity_note,
      'encrypted_class_id' => bin2hex($this->encryption->encrypt($idKelas)) // Mengirim ID kelas terenkripsi ke view
    );

    // Memuat view 'kelasObserver3' dengan data yang telah disiapkan
    $this->load->view('kelasObserver3', $data);
  }

  /**
   * Memproses Catatan Aktivitas Siswa oleh Observer
   *
   * Menyimpan atau memperbarui catatan aktivitas siswa yang diisi oleh observer.
   */
  public function formCatatanAktivitasSiswa()
  {
    // Memastikan pengguna sudah login
    $this->checkLogin();

    // Mendapatkan data dari form dengan sanitasi input
    $encrypted_class_id = $this->input->post('class_id', true);
    $observer_user_id = $this->session->userdata('user_id');

    // Validasi input: pastikan encrypted_class_id memiliki panjang genap dan merupakan string heksadesimal
    if (strlen($encrypted_class_id) % 2 !== 0 || !ctype_xdigit($encrypted_class_id)) {
      show_error('Data tidak lengkap atau tidak valid.', 400);
    }

    // Mendekripsi ID Kelas
    $encrypted_class_id_bin = hex2bin($encrypted_class_id); // Dekode dari heksadesimal ke biner
    $class_id = $this->encryption->decrypt($encrypted_class_id_bin);

    // Validasi input: pastikan class_id dan observer_user_id tidak kosong
    if (empty($class_id) || empty($observer_user_id)) {
      show_error('Data tidak lengkap.', 400);
    }

    // Mengecek apakah pengguna adalah observer terdaftar dalam kelas ini
    $is_observer = $this->ClassObserver->isUserObserver($class_id, $observer_user_id);

    if (!$is_observer) {
      // Jika bukan observer, tampilkan pesan error dan arahkan ke halaman sidebarObserver()
      $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengisi formulir ini.');
      redirect('sidebarObserver');
      exit;
    }

    // Mendapatkan dan memvalidasi jawaban untuk setiap pertanyaan
    $answers = array();
    for ($i = 1; $i <= 5; $i++) {
      $answer = $this->input->post("answer_question$i", true);
      // Jika jawaban kosong, set sebagai null
      $answers["answer_question$i"] = !empty($answer) ? $answer : null;
    }

    // Menangani file tanda tangan yang diunggah
    $signature_data = $this->input->post('src_signature_file');
    $signature_changed = $this->input->post('signatureChanged'); // Mendapatkan nilai signatureChanged

    // Mendapatkan catatan aktivitas yang sudah ada
    $existing_activity_note = $this->StudentActivityNote->getActivityNote($class_id, $observer_user_id);

    if ($signature_changed == '1') {
      // Tanda tangan telah diubah
      if (!empty($signature_data)) {
        // Decode base64
        $signature = base64_decode(explode(',', $signature_data)[1]);

        // Membuat nama file sesuai format
        $tanggal_waktu = date('Y-m-d H-i-s'); // Mengganti ':' dengan '-' untuk kompatibilitas sistem file
        $filename = "{$class_id}_{$observer_user_id}_Formulir3_{$tanggal_waktu}.png";

        // Menentukan path untuk menyimpan tanda tangan
        $filepath = FCPATH . "assets/media/tandaTanganFormulir/" . $filename;

        // Simpan file tanda tangan ke server
        file_put_contents($filepath, $signature);

        // Cek dan hapus file duplikasi jika ada (menyisakan hanya file terbaru)
        $existing_files = glob(FCPATH . "assets/media/tandaTanganFormulir/{$class_id}_{$observer_user_id}_Formulir3_*.png");
        foreach ($existing_files as $file) {
          if ($file !== $filepath) {
            unlink($file);
          }
        }

        // Menyimpan path relatif ke file tanda tangan
        $src_signature_file = "assets/media/tandaTanganFormulir/" . $filename;
      } else {
        // Tanda tangan telah dihapus oleh pengguna
        $src_signature_file = null;

        // Hapus file tanda tangan lama jika ada
        if ($existing_activity_note && !empty($existing_activity_note->src_signature_file)) {
          $old_signature_path = FCPATH . $existing_activity_note->src_signature_file;
          if (file_exists($old_signature_path)) {
            unlink($old_signature_path);
          }
        }
      }
    } else {
      // Tanda tangan tidak diubah, gunakan yang lama
      if ($existing_activity_note && !empty($existing_activity_note->src_signature_file)) {
        $src_signature_file = $existing_activity_note->src_signature_file;
      } else {
        $src_signature_file = null;
      }
    }

    // Menggabungkan data jawaban dan data lainnya
    $data = array_merge($answers, array(
      'class_id'           => $class_id,
      'observer_user_id'   => $observer_user_id,
      'src_signature_file' => $src_signature_file,
    ));

    if ($existing_activity_note) {
      // Jika catatan aktivitas sudah ada, perbarui data yang ada tanpa mengubah 'created_at'
      $data['updated_at'] = date('Y-m-d H:i:s');
      $this->StudentActivityNote->updateActivityNote($existing_activity_note->activity_notes_id, $data);
    } else {
      // Jika catatan aktivitas belum ada, buat catatan baru dengan 'created_at'
      $data['created_at'] = date('Y-m-d H:i:s');
      $this->StudentActivityNote->createActivityNote($data);
    }

    // Mengecek apakah semua formulir telah diisi untuk kelas ini
    $all_forms_completed = $this->checkAllFormsCompleted($class_id, $observer_user_id);

    if ($all_forms_completed) {
      // Mengirim notifikasi ke Guru Model jika semua formulir telah diisi
      $class = $this->ClassModel->getClassById($class_id);
      $this->Notification->createNotification(
        $observer_user_id,
        $class->creator_user_id,
        $class_id,
        'Semua formulir telah diisi oleh observer.',
        'Formulir Diisi'
      );
    }

    // Menetapkan flashdata sukses untuk ditampilkan di halaman berikutnya
    $this->session->set_flashdata('success', 'Formulir berhasil disimpan.');

    // Redirect kembali ke halaman catatan aktivitas siswa
    redirect('pageKelasObserver3/' . bin2hex($this->encryption->encrypt($class_id)));
  }

  /**
   * Mengecek apakah semua formulir telah diisi untuk kelas ini
   *
   * @param int $class_id ID kelas
   * @param int $observer_user_id ID pengguna observer
   * @return bool True jika semua formulir telah diisi, false jika tidak
   */
  public function checkAllFormsCompleted($class_id, $observer_user_id)
  {
    // Cek Penilaian Kegiatan Mengajar
    $assessment = $this->TeachingActivityAssessment->getAssessment($class_id, $observer_user_id);
    if (!$assessment) {
      // Penilaian Kegiatan Mengajar tidak ditemukan
      return false;
    }

    // Pastikan semua skor pertanyaan telah diisi
    for ($i = 1; $i <= 10; $i++) {
      $score_field = 'score_question' . $i;
      if (!isset($assessment->$score_field) || is_null($assessment->$score_field)) {
        // Skor pertanyaan ke-$i tidak diisi
        return false;
      }
    }

    // Pastikan catatan dan tanda tangan telah diisi
    if (empty($assessment->notes) || empty($assessment->src_signature_file)) {
      // Catatan atau tanda tangan Penilaian Kegiatan Mengajar tidak diisi
      return false;
    }

    // Cek Lembar Pengamatan Siswa
    $observation_sheet = $this->StudentObservationSheet->getObservationSheet($class_id, $observer_user_id);
    if (!$observation_sheet) {
      // Lembar Pengamatan Siswa tidak ditemukan
      return false;
    }

    // Pastikan catatan dan tanda tangan telah diisi
    if (empty($observation_sheet->notes) || empty($observation_sheet->src_signature_file)) {
      // Catatan atau tanda tangan Lembar Pengamatan Siswa tidak diisi
      return false;
    }

    // Cek Catatan Aktivitas Siswa
    $activity_notes = $this->StudentActivityNote->getActivityNote($class_id, $observer_user_id);
    if (!$activity_notes) {
      // Catatan Aktivitas Siswa tidak ditemukan
      return false;
    }

    // Pastikan tanda tangan telah diisi
    if (empty($activity_notes->src_signature_file)) {
      // Tanda tangan Catatan Aktivitas Siswa tidak diisi
      return false;
    }

    // Pastikan semua pertanyaan esai telah diisi
    for ($j = 1; $j <= 5; $j++) {
      $answer_field = 'answer_question' . $j;
      if (!isset($activity_notes->$answer_field) || empty(trim($activity_notes->$answer_field))) {
        // Jawaban pertanyaan esai ke-$j tidak diisi
        return false;
      }
    }

    // Semua formulir telah diisi untuk observer ini dalam kelas
    return true;
  }

  /**
   * Menampilkan Halaman Bantuan
   *
   * Menampilkan halaman bantuan untuk pengguna.
   */
  public function sidebarBantuan()
  {
    $data["title"] = "Bantuan";
    $this->load->view('Bantuan', $data);
  }

  /**
   * Logout Pengguna
   *
   * Menghapus semua data session dan cookie "remember_me", menghapus file temporary milik pengguna, kemudian mengarahkan ke halaman login.
   */
  public function sidebarLogout()
  {
    // Mendapatkan parameter 'reason' dari URL
    $reason = $this->input->get('reason');

    // Mendapatkan user_id pengguna dari session
    $user_id = $this->session->userdata('user_id');

    // Set notifikasi berdasarkan 'reason'
    if ($reason === 'inactivity') {
      $notification = [
        'title' => 'Inaktivitas Terdeteksi',
        'text' => 'Anda telah tidak aktif selama 30 menit. Anda otomatis logout.',
        'icon' => 'warning'
      ];
    } elseif ($reason === 'disconnect') {
      $notification = [
        'title' => 'Koneksi Terputus',
        'text' => 'Anda telah logout karena gagal terhubung ke server.',
        'icon' => 'error'
      ];
    } elseif ($reason === 'logout') {
      $notification = [
        'title' => 'Logout',
        'text' => 'Anda telah berhasil logout.',
        'icon' => 'success'
      ];
    } else {
      // Pesan default jika tidak ada alasan yang diberikan
      $notification = [
        'title' => 'Logout',
        'text' => 'Anda telah berhasil logout.',
        'icon' => 'success'
      ];
    }

    // Encode notification ke JSON
    $notification_json = json_encode($notification);

    // Load cookie helper
    $this->load->helper('cookie');

    // Set cookie 'notification' dengan data JSON
    // Cookie akan berakhir dalam 5 detik (cukup untuk permintaan berikutnya)
    $cookie = array(
      'name'   => 'notification',
      'value'  => $notification_json,
      'expire' => 5, // detik
      'path'   => '/'
    );
    set_cookie($cookie);

    // Memastikan user_id ada
    if ($user_id) {
      // Mulai transaksi untuk memastikan konsistensi data saat logout
      $this->db->trans_start();

      // Memperbarui database untuk menghapus current_session_id, remember_token, last_activity, dan status
      $update_data = [
        'current_session_id' => NULL,
        'remember_token' => NULL,
        'last_activity' => date('Y-m-d H:i:s'),
        'status' => 'inactive' // Mengatur status sesi menjadi inactive
      ];
      $this->User->updateActivity($user_id, $update_data);

      // Memperbarui user_id dan status pada tabel ci_sessions menggunakan SessionModel
      $this->SessionModel->updateSession(session_id(), ['user_id' => NULL, 'status' => 'inactive']);

      // Hapus semua sesi lain untuk user ini
      $this->SessionModel->deleteSessionsByUserId($user_id);

      // Hapus sesi dengan user_id null di tabel ci_sessions
      $this->SessionModel->deleteSessionsWithNullUserId();

      // Path direktori temporary
      $temporary_dir = FCPATH . 'assets/media/temporary/';

      // Mencari file yang mengandung user_id
      $user_files = glob($temporary_dir . "{$user_id}_*");

      // Menghapus file-file tersebut
      if ($user_files !== false) {
        foreach ($user_files as $file) {
          if (is_file($file)) {
            unlink($file);
          }
        }
      }

      // Commit transaksi
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        // Jika transaksi gagal, set flashdata dan redirect ke login
        $this->session->set_flashdata('logout_error', 'Terjadi kesalahan saat proses logout. Silakan coba lagi.');
        redirect('pageLogin');
      }
    }

    // Menghapus semua data session
    $this->session->sess_destroy();

    // Menghapus sesi yang tidak aktif saat logout
    $this->SessionModel->deleteInactiveSessions();

    // Hapus cookie "remember_me"
    delete_cookie('remember_me');

    // Menambahkan header untuk mencegah caching aset autentikasi
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
    $this->output->set_header('Pragma: no-cache');

    // Redirect ke halaman login
    redirect('pageLogin');
  }

  /**
   * Menampilkan Halaman Login
   *
   * Menampilkan halaman login jika pengguna belum login. Jika ada cookie "remember_me", mencoba untuk login otomatis.
   */
  public function pageLogin()
  {
    // Cek apakah pengguna sudah login melalui session
    if ($this->session->userdata('user_id')) {
      redirect('sidebarBeranda');
    }

    // Cek apakah ada cookie "remember_me"
    $remember_token = get_cookie('remember_me');
    if ($remember_token) {
      // Mendapatkan pengguna berdasarkan token "remember_me"
      $user = $this->User->getUserByRememberToken($remember_token);
      if ($user) {
        // Cek apakah sudah ada sesi aktif di browser lain atau status 'wait'
        if ($user->current_session_id) {
          $existing_session = $this->SessionModel->getSessionById($user->current_session_id);
          if ($existing_session && ($existing_session->status === 'active' || $existing_session->status === 'wait')) {
            // Sesi aktif di browser lain atau status 'wait', tidak dapat login
            $this->session->set_flashdata('login_error', 'Akun Anda sudah digunakan di browser lain.');
            redirect('pageLogin');
          }
        }

        // Mulai transaksi untuk memastikan konsistensi data
        $this->db->trans_start();

        // Regenerasi session ID dan menghapus sesi lama
        $this->session->sess_regenerate(TRUE);

        // Mengatur session pengguna
        $this->session->set_userdata('user_id', $user->user_id);
        $this->session->set_userdata('email_address', $user->email_address);

        // Update session_id, last_activity, dan status di database
        $update_data = [
          'current_session_id' => session_id(),
          'last_activity' => date('Y-m-d H:i:s'),
          'status' => 'active' // Mengatur status sesi menjadi aktif
        ];
        $this->User->updateActivity($user->user_id, $update_data);

        // Memperbarui user_id dan status pada tabel ci_sessions menggunakan SessionModel
        $this->SessionModel->updateSession(session_id(), ['user_id' => $user->user_id, 'status' => 'active']);

        // Menghapus sesi lama yang tidak aktif untuk user ini
        $this->SessionModel->deleteInactiveSessionsByUserId($user->user_id);

        // Menghapus sesi lain yang aktif untuk user ini kecuali sesi saat ini
        $this->SessionModel->deleteOtherSessions($user->user_id, session_id());

        // Commit transaksi
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
          // Jika transaksi gagal, set flashdata dan redirect ke login
          $this->session->set_flashdata('login_error', 'Terjadi kesalahan saat memproses login otomatis. Silakan login kembali.');
          redirect('pageLogin');
        }

        // Menyediakan data pengguna untuk semua view
        $this->user = $user;
        $this->load->vars(['user' => $user]);

        // Redirect ke beranda
        redirect('sidebarBeranda');
      }
    }

    // Memeriksa semua pengguna dan menonaktifkan yang tidak aktif
    $this->User->checkInactiveUsers();

    // Menyiapkan data untuk view
    $data["title"] = "Login";

    // Mendapatkan flashdata untuk pesan SweetAlert2
    $data['success_message'] = $this->session->flashdata('success');
    $data['error_message'] = $this->session->flashdata('error');
    $data['login_error'] = $this->session->flashdata('login_error');
    $data['login_required'] = $this->session->flashdata('login_required');
    $data['session_invalid'] = $this->session->flashdata('session_invalid');
    $data['session_timeout'] = $this->session->flashdata('session_timeout');

    // Load cookie helper untuk mendapatkan cookie 'notification'
    $this->load->helper('cookie');

    // Mendapatkan 'notification' cookie
    $notification_cookie = get_cookie('notification');

    if ($notification_cookie) {
      // Decode JSON notifikasi
      $notification = json_decode($notification_cookie, true);

      // Pass notification data ke view
      $data['notification'] = $notification;

      // Hapus cookie 'notification' setelah dibaca
      delete_cookie('notification');
    } else {
      $data['notification'] = null;
    }

    // Menambahkan script untuk menghapus 'login_timestamp' dari localStorage saat halaman login dimuat
    $data['clear_login_timestamp_script'] = "
            <script>
                // Hapus loginTimestamp dari localStorage saat halaman login dimuat
                localStorage.removeItem('login_timestamp');
            </script>
        ";

    // Menghapus sesi yang tidak aktif saat mengakses halaman login
    $this->SessionModel->deleteInactiveSessions();

    // Memuat view Login dengan data
    $this->load->view('Login', $data);
  }

  /**
   * Memproses Login Pengguna
   *
   * Mengautentikasi pengguna berdasarkan email dan password, serta menangani opsi "Ingat Saya".
   * 
   * @param string $email_address Alamat email pengguna yang dikirim dari form.
   * @param string $password Password pengguna yang dikirim dari form.
   * @param string $remember_me Nilai checkbox "Ingat Saya" dari form.
   * @param string $browser Informasi nama browser yang dikirim dari form (diisi oleh JavaScript).
   * @param string $mode Informasi mode browser (private/normal) yang dikirim dari form (diisi oleh JavaScript).
   */
  public function formLogin()
  {
    // Mengambil data dari form menggunakan name attribute
    $email_address = $this->input->post('email_address');
    $password      = $this->input->post('password');
    $remember_me   = $this->input->post('remember_me'); // Mengambil nilai checkbox "Ingat Saya"

    // Mengambil data browser dan mode dari input hidden
    $browser = $this->input->post('browser', TRUE);
    $mode    = $this->input->post('mode', TRUE);

    // Validasi input dasar
    if (empty($email_address) || empty($password)) {
      $this->session->set_flashdata('error', 'Silakan isi semua bidang.');
      redirect('pageLogin');
    }

    // Mendapatkan pengguna berdasarkan email
    $user = $this->User->getUserByEmail($email_address);

    if ($user && password_verify($password, $user->password)) {
      // Cek apakah sudah ada sesi aktif di browser lain atau status 'wait'
      if ($user->current_session_id) {
        $existing_session = $this->SessionModel->getSessionById($user->current_session_id);
        if ($existing_session && ($existing_session->status === 'active' || $existing_session->status === 'wait')) {
          // Jika ada sesi aktif di browser lain atau status 'wait', blokir login
          $this->session->set_flashdata('login_error', 'Akun Anda sudah digunakan di browser lain.');
          redirect('pageLogin');
        }
      }

      // Mulai transaksi untuk memastikan konsistensi data saat login
      $this->db->trans_start();

      // Hapus sesi dengan user_id null di tabel ci_sessions
      $this->SessionModel->deleteSessionsWithNullUserId();

      // Regenerasi session ID setelah login sukses
      $this->session->sess_regenerate(TRUE);

      // Menyimpan data pengguna ke session
      $this->session->set_userdata('user_id', $user->user_id);
      $this->session->set_userdata('email_address', $user->email_address);

      // Menyiapkan data untuk diperbarui di database
      $update_data = [
        'current_session_id' => session_id(),
        'last_activity' => date('Y-m-d H:i:s'),
        'status' => 'active',
        'last_browser' => $browser, // Menyimpan nama browser terakhir yang digunakan
        'last_mode' => $mode       // Menyimpan mode browser terakhir (private/normal)
      ];

      // Jika "Ingat Saya" dicentang
      if ($remember_me) {
        // Generate token unik
        $token = bin2hex(random_bytes(16));

        // Simpan token ke database
        $update_data['remember_token'] = $token;

        // Set cookie "remember_me" dengan token, berlaku 30 hari
        $cookie = array(
          'name'   => 'remember_me',
          'value'  => $token,
          'expire' => 60 * 60 * 24 * 30, // 30 hari
          'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
          'httponly' => TRUE,
          'path'   => '/',
          'samesite' => 'Lax'
        );
        set_cookie($cookie);
      } else {
        // Hapus token jika sebelumnya ada
        $update_data['remember_token'] = NULL;

        // Hapus cookie "remember_me" jika ada
        delete_cookie('remember_me');
      }

      // Memperbarui data pengguna di database
      $this->User->updateActivity($user->user_id, $update_data);

      // Memperbarui user_id, status, browser, dan mode_private pada tabel ci_sessions menggunakan SessionModel
      // mode_private bernilai TRUE/FALSE sesuai dengan $mode
      $this->SessionModel->updateSession(session_id(), [
        'user_id' => $user->user_id,
        'status' => 'active',
        'browser' => $browser,
        'mode_private' => ($mode === 'private' ? 1 : 0)
      ]);

      // Hapus sesi lama yang tidak aktif untuk user ini
      $this->SessionModel->deleteInactiveSessionsByUserId($user->user_id);

      // Hapus sesi lain yang aktif untuk user ini kecuali sesi saat ini
      $this->SessionModel->deleteOtherSessions($user->user_id, session_id());

      // Commit transaksi
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        // Jika transaksi gagal, set flashdata dan redirect ke login
        $this->session->set_flashdata('error', 'Terjadi kesalahan saat proses login. Silakan coba lagi.');
        redirect('pageLogin');
      }

      // Menyediakan data pengguna untuk semua view
      $this->user = $user;
      $this->load->vars(['user' => $user]);

      // Set Flashdata `success_login` sebelum redirect
      $this->session->set_flashdata('success_login', 'Anda berhasil login.');

      // Redirect ke beranda
      redirect('sidebarBeranda');
    } else {
      // Menampilkan pesan error jika login gagal
      $this->session->set_flashdata('error', 'Email atau password salah.');
      redirect('pageLogin');
    }
  }

  /**
   * Menampilkan Halaman Signup
   *
   * Menampilkan halaman signup untuk pengguna baru.
   */
  public function pageSignup()
  {
    $data["title"] = "Signup";
    $this->load->view('Signup', $data);
  }

  /**
   * Memproses Signup Pengguna
   *
   * Menangani pembuatan akun baru setelah validasi input.
   */
  public function formSignup()
  {
    // Menetapkan aturan validasi untuk setiap field
    $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim|max_length[255]', [
      'required'   => 'Nama lengkap wajib diisi.',
      'max_length' => 'Nama lengkap tidak boleh lebih dari 255 karakter.'
    ]);
    $this->form_validation->set_rules('email_address', 'Alamat E-mail', 'required|trim|valid_email|is_unique[Users.email_address]', [
      'required'   => 'Alamat email wajib diisi.',
      'valid_email' => 'Alamat email tidak valid.',
      'is_unique'  => 'Alamat email sudah terdaftar.'
    ]);
    $this->form_validation->set_rules('password', 'Kata Sandi', 'required|trim|min_length[8]|max_length[255]|regex_match[/^(?=.*[A-Z])(?=.*\W).+$/]', [
      'required'    => 'Kata sandi wajib diisi.',
      'min_length'  => 'Kata sandi harus memiliki panjang minimal 8 karakter.',
      'max_length'  => 'Kata sandi tidak boleh lebih dari 255 karakter.',
      'regex_match' => 'Kata sandi harus mengandung setidaknya 1 huruf kapital dan 1 karakter khusus.'
    ]);
    $this->form_validation->set_rules('password_confirm', 'Konfirmasi Kata Sandi', 'required|trim|matches[password]', [
      'required' => 'Konfirmasi kata sandi wajib diisi.',
      'matches'  => 'Konfirmasi kata sandi tidak cocok.'
    ]);

    // Menjalankan validasi form
    if ($this->form_validation->run() == FALSE) {
      // Ambil semua pesan error
      $errors = $this->form_validation->error_array();

      // Set flashdata per error (opsional, untuk penanganan granular)
      foreach ($errors as $key => $error) {
        $this->session->set_flashdata('error_' . $key, $error);
      }

      // Atau gabungkan semua error dalam satu flashdata
      $this->session->set_flashdata('error', implode(' ', $errors));
      redirect('pageSignup');
    } else {
      // Mendapatkan input dari formulir
      $full_name     = $this->input->post('full_name');
      $email_address = $this->input->post('email_address');
      $password      = $this->input->post('password');

      // Membuat pengguna baru
      $result = $this->User->createUser($full_name, $email_address, $password);

      if ($result) {
        // Jika berhasil, set pesan sukses dan arahkan ke halaman login
        $this->session->set_flashdata('signup_success', true);
        $this->session->set_flashdata('success', 'Akun Anda berhasil dibuat. Silakan login.');
        redirect('pageLogin');
      } else {
        // Jika gagal, set pesan error dan arahkan kembali ke halaman signup
        $this->session->set_flashdata('error', 'Gagal membuat akun. Silakan coba lagi.');
        redirect('pageSignup');
      }
    }
  }

  /**
   * Menampilkan Halaman Lupa Password
   *
   * Menampilkan form untuk pengguna yang lupa password.
   */
  public function pageLupaPassword()
  {
    $data["title"] = "Lupa Password";
    $this->load->view('lupaPassword', $data);
  }

  /**
   * Memproses Permintaan Lupa Password
   *
   * Mengirimkan password baru ke email pengguna dan memperbarui password di database.
   */
  public function formLupaPassword()
  {
    // Ambil data email dengan trim untuk menghapus spasi di awal dan akhir
    $email_address = trim($this->input->post('email_address'));

    // Validasi input email
    if (empty($email_address) || !filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
      $this->session->set_flashdata('error', 'Silakan masukkan alamat email yang valid.');
      redirect('pageLupaPassword');
    }

    // Mendapatkan pengguna berdasarkan email
    $user = $this->User->getUserByEmail($email_address);

    if ($user) {
      // Cek apakah pengguna telah meminta reset password dalam 5 menit terakhir
      $current_time = new DateTime();
      if (!empty($user->last_password_reset)) {
        $last_reset = new DateTime($user->last_password_reset);
        $interval = $current_time->getTimestamp() - $last_reset->getTimestamp();

        if ($interval < 300) { // 300 detik = 5 menit
          $remaining = 300 - $interval;
          $minutes = floor($remaining / 60);
          $seconds = $remaining % 60;
          $this->session->set_flashdata('error', 'Silakan tunggu ' . $minutes . ' menit ' . $seconds . ' detik sebelum meminta reset password lagi.');
          redirect('pageLupaPassword');
        }
      }

      // Generate password baru
      $new_password = $this->generateRandomPassword();

      // Siapkan isi email
      $subject = 'Permintaan Reset Password';
      $message = "
                <!DOCTYPE html>
                <html lang='id'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Reset Password Anda</title>
                </head>
                <body style='margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
                    <table width='100%' cellpadding='0' cellspacing='0' border='0'>
                        <tr>
                            <td align='center' style='padding: 20px 0;'>
                                <table width='600' cellpadding='0' cellspacing='0' border='0' style='background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
                                    <!-- Header -->
                                    <tr>
                                        <td align='center' style='padding: 20px; background-color: #4CAF50; color: #ffffff;'>
                                            <h2 style='margin: 0; font-size: 24px;'>Reset Password Anda</h2>
                                        </td>
                                    </tr>
                                    <!-- Body -->
                                    <tr>
                                        <td style='padding: 30px; color: #333333;'>
                                            <p style='font-size: 16px; line-height: 1.5;'>Halo " . htmlspecialchars($user->full_name) . ",</p>
                                            <p style='font-size: 16px; line-height: 1.5;'>Kami menerima permintaan untuk mereset password Anda. Berikut adalah password baru Anda:</p>
                                            <p style='font-size: 16px; line-height: 1.5;'><strong>Password Baru:</strong> " . htmlspecialchars($new_password) . "</p>
                                            <p style='font-size: 16px; line-height: 1.5;'>Silakan login menggunakan password baru ini dan segera ganti password Anda untuk keamanan akun.</p>
                                            <p style='font-size: 16px; line-height: 1.5;'>Jika Anda tidak mengajukan permintaan ini, silakan abaikan email ini atau hubungi edvisorfilkomub@gmail.com untuk bantuan lebih lanjut.</p>
                                            <p style='font-size: 16px; line-height: 1.5;'>Terima kasih telah menggunakan layanan kami.</p>
                                        </td>
                                    </tr>
                                    <!-- Footer -->
                                    <tr>
                                        <td style='padding: 20px; background-color: #f4f4f4; text-align: center; color: #777777; font-size: 14px;'>
                                            <p style='margin: 0;'>Salam,<br>Tim edvisor</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>
            ";

      // Kirim email menggunakan SendinBlue/Brevo
      $email_sent = $this->sendEmail($email_address, $subject, $message);

      if ($email_sent) {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password dan waktu reset di database
        $data = array(
          'password' => $hashed_password,
          'last_password_reset' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
        );
        $update = $this->User->updateUser($user->user_id, $data);

        if ($update) {
          $this->session->set_flashdata('success', 'Password baru telah dikirim ke email Anda.');
          redirect('pageLogin');
        } else {
          // Jika update database gagal, set flashdata error
          $this->session->set_flashdata('error', 'Gagal memperbarui password. Silakan coba lagi.');
          redirect('pageLupaPassword');
        }
      } else {
        // Jika pengiriman email gagal, jangan update 'last_password_reset'
        $this->session->set_flashdata('error', 'Gagal mengirim email. Silakan coba lagi.');
        redirect('pageLupaPassword');
      }
    } else {
      $this->session->set_flashdata('error', 'Email tidak ditemukan.');
      redirect('pageLupaPassword');
    }
  }

  /**
   * Menghasilkan Password Acak
   *
   * Membuat password acak dengan panjang yang ditentukan.
   *
   * @param int $length Panjang password
   * @return string Password acak
   */
  private function generateRandomPassword($length = 8)
  {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
  }

  /**
   * Mengirim Email
   *
   * Mengirim email menggunakan konfigurasi yang telah disetel.
   *
   * @param string $to Alamat email penerima
   * @param string $subject Subjek email
   * @param string $message Isi email dalam format HTML
   * @return bool True jika email berhasil dikirim, false jika gagal
   */
  private function sendEmail($to, $subject, $message)
  {
    // Konfigurasi email sudah dilakukan di application/config/email.php
    $this->email->clear(); // Bersihkan email sebelumnya

    $this->email->from('edvisorfilkomub@gmail.com', 'edvisor Filkom UB');
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);

    // Kirim email dan cek status pengiriman
    if ($this->email->send()) {
      return true;
    } else {
      // Untuk debugging
      log_message('error', 'Email gagal dikirim: ' . $this->email->print_debugger());
      return false;
    }
  }

  /**
   * Menampilkan halaman 404 error.
   *
   * Fungsi ini akan menyetel pesan error ke flashdata dan melakukan redirect
   * pengguna ke halaman 'sidebarBeranda'.
   */
  public function show_404()
  {
    // Set pesan error ke flashdata
    $this->session->set_flashdata('error', 'Halaman yang Anda cari tidak ditemukan.');

    // Redirect ke halaman 'sidebarBeranda'
    redirect('sidebarBeranda');
  }

  /**
   * Handler error kustom untuk menangani error PHP.
   *
   * @param int    $severity Tingkat keparahan error yang dihasilkan.
   * @param string $message  Pesan error yang dihasilkan.
   * @param string $filepath Jalur file di mana error terjadi.
   * @param int    $line     Nomor baris tempat error terjadi.
   *
   * Fungsi ini memetakan tingkat keparahan error ke tipe pesan yang sesuai,
   * menyetel pesan tersebut ke flashdata, dan mengarahkan pengguna ke 
   * halaman 'sidebarBeranda'. Jika tingkat keparahan tidak dikenali, handler
   * error default akan menangani error tersebut.
   */
  public function custom_error_handler($severity, $message, $filepath, $line)
  {
    // Daftar pemetaan tingkat keparahan error ke tipe pesan
    $error_types = [
      E_ERROR             => 'Error',
      E_WARNING           => 'Warning',
      E_PARSE             => 'Parse Error',
      E_NOTICE            => 'Notice',
      E_CORE_ERROR        => 'Core Error',
      E_CORE_WARNING      => 'Core Warning',
      E_COMPILE_ERROR     => 'Compile Error',
      E_COMPILE_WARNING   => 'Compile Warning',
      E_USER_ERROR        => 'User Error',
      E_USER_WARNING      => 'User Warning',
      E_USER_NOTICE       => 'User Notice',
      E_STRICT            => 'Strict Notice',
      E_RECOVERABLE_ERROR => 'Recoverable Error',
      E_DEPRECATED        => 'Deprecated',
      E_USER_DEPRECATED   => 'User Deprecated',
      // PHP 8 and above
      E_ALL               => 'All Errors',
    ];

    // Periksa apakah tingkat keparahan ada dalam daftar
    if (isset($error_types[$severity])) {
      // Dapatkan tipe error
      $error_type = $error_types[$severity];

      // Buat pesan error yang diformat
      $formatted_message = "$error_type: $message di $filepath pada baris $line.";

      // Set flashdata berdasarkan tipe error
      switch ($severity) {
        case E_ERROR:
        case E_PARSE:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
          // Error kritis
          $this->session->set_flashdata('error_long', $formatted_message);
          break;

        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
          // Peringatan
          $this->session->set_flashdata('warning', $formatted_message);
          break;

        case E_NOTICE:
        case E_USER_NOTICE:
        case E_STRICT:
          // Pemberitahuan
          $this->session->set_flashdata('notice', $formatted_message);
          break;

        case E_DEPRECATED:
        case E_USER_DEPRECATED:
          // Pemberitahuan Deprecation
          $this->session->set_flashdata('deprecated', $formatted_message);
          break;

        case E_ALL:
          // Semua jenis error, bisa ditangani secara umum atau diabaikan
          $this->session->set_flashdata('error', $formatted_message);
          break;

        default:
          // Tipe error lainnya
          $this->session->set_flashdata('error', $formatted_message);
          break;
      }

      // Redirect ke halaman 'sidebarBeranda'
      redirect('sidebarBeranda');
    }

    // Jika tingkat keparahan tidak dikenali, biarkan handler error default menangani
    return false;
  }
}
