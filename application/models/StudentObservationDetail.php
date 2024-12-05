<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model StudentObservationDetail
 * 
 * Mengelola operasi database terkait detail pengamatan siswa.
 */
class StudentObservationDetail extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'StudentObservationDetails';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menambahkan detail pengamatan siswa
     *
     * @param array $data Data detail pengamatan siswa dalam bentuk batch
     * @return bool Hasil insert data
     */
    public function addObservationDetail($data)
    {
        // Menambahkan data secara batch ke tabel
        return $this->db->insert_batch($this->table, $data);
    }

    /**
     * Mendapatkan detail pengamatan berdasarkan ID pengamatan
     *
     * @param int $observation_id ID pengamatan
     * @return array Array objek detail pengamatan
     */
    public function getObservationDetails($observation_id)
    {
        // Mengambil data dari tabel berdasarkan observation_id
        $query = $this->db->get_where($this->table, array('observation_id' => $observation_id));
        return $query->result();
    }

    /**
     * Menghapus detail pengamatan siswa berdasarkan observation_id
     *
     * @param int $observation_id ID pengamatan
     * @return bool Hasil penghapusan data
     */
    public function deleteObservationDetails($observation_id)
    {
        // Menghapus data dari tabel berdasarkan observation_id
        return $this->db->delete($this->table, array('observation_id' => $observation_id));
    }

    /**
     * Menghapus data detail pengamatan siswa berdasarkan student_number
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @param string $student_number Nomor siswa
     * @return bool Hasil penghapusan data
     */
    public function deleteObservationDetailsByStudentNumber($class_id, $observer_user_id, $student_number)
    {
        // Mendapatkan observation_id dari StudentObservationSheet
        $this->db->select('observation_id');
        $this->db->from('StudentObservationSheet');
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        $query = $this->db->get();
        $observation_row = $query->row();

        if (!$observation_row) {
            // Jika tidak ditemukan, tidak perlu menghapus
            return true;
        }

        $observation_id = $observation_row->observation_id;

        // Menghapus data StudentObservationDetails yang sesuai
        return $this->db->delete($this->table, array(
            'observation_id' => $observation_id,
            'student_number' => $student_number
        ));
    }

    /**
     * Menghapus semua data detail pengamatan siswa berdasarkan observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil penghapusan data
     */
    public function deleteAllObservationDetailsByObserver($class_id, $observer_user_id)
    {
        // Mendapatkan observation_id dari StudentObservationSheet
        $this->db->select('observation_id');
        $this->db->from('StudentObservationSheet');
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        $query = $this->db->get();
        $observation_row = $query->row();

        if (!$observation_row) {
            // Jika tidak ditemukan, tidak perlu menghapus
            return true;
        }

        $observation_id = $observation_row->observation_id;

        // Menghapus data StudentObservationDetails yang sesuai
        return $this->db->delete($this->table, array(
            'observation_id' => $observation_id
        ));
    }

    /**
     * Menghapus data detail pengamatan siswa berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteObservationDetailsByClass($class_id)
    {
        // Mendapatkan semua observation_id yang terkait dengan class_id
        $this->db->select('observation_id');
        $this->db->from('StudentObservationSheet');
        $this->db->where('class_id', $class_id);
        $query = $this->db->get();
        $observation_ids = array_column($query->result_array(), 'observation_id');

        if (!empty($observation_ids)) {
            // Menghapus data berdasarkan observation_id
            $this->db->where_in('observation_id', $observation_ids);
            return $this->db->delete($this->table);
        }
        return true;
    }
}