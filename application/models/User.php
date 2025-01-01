<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model User
 * 
 * Mengelola operasi database terkait pengguna.
 */
class User extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'Users';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Membuat pengguna baru
     *
     * @param string $full_name Nama lengkap pengguna
     * @param string $email_address Alamat email pengguna
     * @param string $password Kata sandi pengguna
     * @return bool Hasil insert data
     */
    public function createUser($full_name, $email_address, $password)
    {
        // Menyiapkan data pengguna baru
        $data = array(
            'full_name'         => $full_name,
            'email_address'     => $email_address,
            'password'          => password_hash($password, PASSWORD_BCRYPT),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        );
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan pengguna berdasarkan ID
     *
     * @param int $user_id ID pengguna
     * @return object Objek pengguna
     */
    public function getUserById($user_id)
    {
        // Mengambil data berdasarkan user_id
        $query = $this->db->get_where($this->table, array('user_id' => $user_id));
        return $query->row();
    }

    /**
     * Mendapatkan semua pengguna kecuali user_id tertentu
     *
     * @param int $user_id ID pengguna yang dikecualikan
     * @return array Array objek pengguna
     */
    public function getAllUsersExcept($user_id)
    {
        // Menambahkan kondisi WHERE untuk mengecualikan user_id tertentu
        $this->db->where('user_id !=', $user_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Mendapatkan pengguna berdasarkan email (untuk login)
     *
     * @param string $email_address Alamat email pengguna
     * @return object Objek pengguna
     */
    public function getUserByEmail($email_address)
    {
        // Mengambil data berdasarkan email_address
        $query = $this->db->get_where($this->table, array('email_address' => $email_address));
        return $query->row();
    }

    /**
     * Menemukan pengguna berdasarkan token "Ingat Saya"
     *
     * @param string $token Token "Ingat Saya"
     * @return object|null Objek pengguna atau null jika tidak ditemukan
     */
    public function getUserByRememberToken($token)
    {
        // Mengambil data berdasarkan remember_token
        return $this->db->where('remember_token', $token)->get('users')->row();
    }

    /**
     * Memperbarui data pengguna
     *
     * @param int $user_id ID pengguna yang akan diperbarui
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateUser($user_id, $data)
    {
        // Menambahkan timestamp pembaruan
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Menambahkan kondisi WHERE untuk user_id
        $this->db->where('user_id', $user_id);
        // Melakukan update data
        return $this->db->update($this->table, $data);
    }

   /**
     * Memperbarui aktivitas pengguna
     *
     * @param int $user_id ID pengguna
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateActivity($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        $result = $this->db->update($this->table, $data);

        if ($result) {
            log_message('info', "User: User_id: {$user_id} aktivitas diperbarui dengan data: " . json_encode($data));
        } else {
            log_message('error', "User: Gagal memperbarui aktivitas untuk User_id: {$user_id}.");
        }

        return $result;
    }

    /**
     * Memeriksa dan menonaktifkan pengguna yang tidak aktif
     *
     * Mengubah status pengguna menjadi 'inactive' dan menghapus session_id serta last_activity jika tidak ada heartbeat selama 30 menit.
     *
     * @return void
     */
    public function checkInactiveUsers()
    {
        // Mendapatkan waktu saat ini
        $current_time = new DateTime();

        // Mendapatkan semua pengguna yang statusnya 'active'
        $this->db->where('status', 'active');
        $users = $this->db->get($this->table)->result();

        foreach ($users as $user) {
            if ($user->last_activity) {
                $last_activity = new DateTime($user->last_activity);
                $interval = $current_time->getTimestamp() - $last_activity->getTimestamp();

                // Batas waktu inaktivitas 1800 detik (30 menit)
                if ($interval > 1800) {
                    // Menambahkan log info saat pengguna dinonaktifkan karena timeout
                    log_message('info', "Model: User_id: {$user->user_id} dinonaktifkan karena melebihi batas waktu inaktivitas. Last Activity: {$user->last_activity}, Interval: {$interval} seconds.");

                    // Update status menjadi 'inactive' dan hapus session_id
                    $update_data = [
                        'status' => 'inactive',
                        'current_session_id' => NULL,
                        'last_activity' => date('Y-m-d H:i:s')
                    ];
                    $this->updateActivity($user->user_id, $update_data);

                    // Juga, perbarui tabel ci_sessions jika diperlukan
                    $this->load->model('SessionModel');
                    $this->SessionModel->updateSession($user->current_session_id, ['user_id' => NULL, 'status' => 'inactive']);
                }
            }
        }
    }
}
