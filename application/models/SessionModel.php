<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Session
 * 
 * Mengelola operasi database terkait sesi pengguna.
 */
class SessionModel extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'ci_sessions';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menghapus sesi yang tidak aktif
     *
     * Menghapus semua sesi yang tidak terkait dengan pengguna aktif (status 'inactive').
     *
     * @return bool Hasil penghapusan sesi
     */
    public function deleteInactiveSessions()
    {
        // Menghapus semua sesi yang statusnya 'inactive'
        $this->db->where('status', 'inactive');
        return $this->db->delete($this->table);
    }

    /**
     * Menghapus sesi dengan user_id null
     *
     * @return bool Hasil penghapusan sesi
     */
    public function deleteSessionsWithNullUserId()
    {
        // Menghapus semua sesi yang user_id-nya NULL
        $this->db->where('user_id', NULL);
        return $this->db->delete($this->table);
    }

    /**
     * Menghapus sesi yang tidak aktif berdasarkan user_id
     *
     * @param int $user_id ID pengguna
     * @return bool Hasil penghapusan sesi
     */
    public function deleteInactiveSessionsByUserId($user_id)
    {
        // Menghapus sesi yang user_id-nya sesuai dan statusnya 'inactive'
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'inactive');
        return $this->db->delete($this->table);
    }

    /**
     * Menghapus sesi lain yang aktif untuk pengguna, kecuali sesi saat ini
     *
     * @param int $user_id ID pengguna
     * @param string $current_session_id ID sesi saat ini
     * @return bool Hasil penghapusan sesi
     */
    public function deleteOtherSessions($user_id, $current_session_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('id !=', $current_session_id);
        return $this->db->delete($this->table);
    }

    /**
     * Menghapus semua sesi untuk user_id tertentu
     *
     * @param int $user_id ID pengguna
     * @return bool Hasil penghapusan sesi
     */
    public function deleteSessionsByUserId($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->delete($this->table);
    }

    /**
     * Mendapatkan sesi berdasarkan ID
     *
     * @param string $session_id ID sesi
     * @return object|null Objek sesi jika ditemukan, null jika tidak
     */
    public function getSessionById($session_id)
    {
        // Mengambil data sesi berdasarkan ID
        $query = $this->db->get_where($this->table, array('id' => $session_id));
        return $query->row();
    }

    /**
     * Memperbarui data sesi
     *
     * @param string $session_id ID sesi yang akan diperbarui
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateSession($session_id, $data)
    {
        // Menambahkan timestamp pembaruan
        $data['timestamp'] = time();

        // Menambahkan kondisi WHERE untuk session_id
        $this->db->where('id', $session_id);
        // Melakukan update data
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus sesi berdasarkan ID
     *
     * @param string $session_id ID sesi yang akan dihapus
     * @return bool Hasil penghapusan sesi
     */
    public function deleteSession($session_id)
    {
        // Menghapus data sesi berdasarkan ID
        return $this->db->delete($this->table, array('id' => $session_id));
    }
}