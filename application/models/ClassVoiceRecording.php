<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model ClassVoiceRecording
 * 
 * Mengelola operasi database terkait rekaman suara kelas.
 */
class ClassVoiceRecording extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'ClassVoiceRecordings';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menyimpan rekaman suara baru
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @param string $file_src Sumber file rekaman suara
     * @return bool Hasil insert data
     */
    public function createRecording($class_id, $observer_user_id, $file_src)
    {
        // Menyiapkan data untuk disimpan
        $data = array(
            'class_id'         => $class_id,
            'observer_user_id' => $observer_user_id,
            'file_src'         => $file_src,
            'created_at'       => date('Y-m-d H:i:s')
        );
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan rekaman suara berdasarkan ID Kelas dan ID Observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @return object Objek rekaman suara
     */
    public function getRecordingByClassAndObserver($class_id, $observer_user_id)
    {
        // Menambahkan kondisi WHERE untuk class_id dan observer_user_id
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->row(); // Mengembalikan objek tunggal
    }

    /**
     * Mendapatkan semua rekaman suara berdasarkan ID Kelas
     *
     * @param int $class_id ID kelas
     * @return object Objek rekaman suara
     */
    public function getRecordingByClass($class_id)
    {
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->row(); // Mengembalikan objek tunggal
    }

    /**
     * Menghapus data rekaman suara berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteRecordingsByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }

    /**
     * Menghapus data rekaman suara berdasarkan ID kelas dan ID observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @return bool Hasil penghapusan data
     */
    public function deleteRecordingsByClassAndObserver($class_id, $observer_user_id)
    {
        // Menghapus data berdasarkan class_id dan observer_user_id
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }
}