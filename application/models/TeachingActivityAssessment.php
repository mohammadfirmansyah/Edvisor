<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model TeachingActivityAssessment
 * 
 * Mengelola operasi database terkait penilaian kegiatan mengajar.
 */
class TeachingActivityAssessment extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'TeachingActivityAssessment';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Membuat penilaian kegiatan mengajar baru
     *
     * @param array $data Data penilaian kegiatan mengajar
     * @return bool Hasil insert data
     */
    public function createAssessment($data)
    {
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan semua penilaian kegiatan mengajar berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return array Array objek penilaian kegiatan mengajar
     */
    public function getAssessmentsByClass($class_id)
    {
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result(); // Mengembalikan hasil sebagai array objek
    }

    /**
     * Mendapatkan penilaian kegiatan mengajar berdasarkan ID kelas dan ID observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return object Objek penilaian kegiatan mengajar
     */
    public function getAssessment($class_id, $observer_user_id)
    {
        // Mengambil data berdasarkan class_id dan observer_user_id
        return $this->db->get_where($this->table, [
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ])->row();
    }

    /**
     * Memperbarui penilaian
     *
     * @param int $assessment_id ID penilaian yang akan diperbarui
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateAssessment($assessment_id, $data)
    {
        // Menambahkan kondisi WHERE untuk assessment_id
        $this->db->where('assessment_id', $assessment_id);
        // Melakukan update data
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus Penilaian Kegiatan Mengajar berdasarkan observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil penghapusan data
     */
    public function deleteAssessmentByObserver($class_id, $observer_user_id)
    {
        // Menghapus data berdasarkan class_id dan observer_user_id
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }

    /**
     * Menghapus Penilaian Kegiatan Mengajar berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteAssessmentsByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }
}