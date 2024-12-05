<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model ObservedStudent
 * 
 * Mengelola operasi database terkait siswa yang diamati.
 */
class ObservedStudent extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'observedstudents'; // Sesuaikan dengan nama tabel di database

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menambahkan siswa yang diamati
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @param string $student_number Nomor siswa
     * @return bool Hasil insert data
     */
    public function addObservedStudent($class_id, $observer_user_id, $student_number)
    {
        // Menyiapkan data untuk disimpan, termasuk trimming spasi pada student_number
        $data = array(
            'class_id'          => $class_id,
            'observer_user_id'  => $observer_user_id,
            'student_number'    => trim($student_number),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        );
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan siswa yang diamati berdasarkan observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return array Array objek siswa yang diamati
     */
    public function getObservedStudents($class_id, $observer_user_id)
    {
        // Mengambil data siswa yang diamati berdasarkan class_id dan observer_user_id
        $query = $this->db->get_where($this->table, array(
            'class_id'          => $class_id,
            'observer_user_id'  => $observer_user_id
        ));
        return $query->result();
    }

    /**
     * Menghapus data siswa yang diamati berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteObservedStudentsByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }

    /**
     * Menghapus data siswa yang diamati dan data terkait
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @param string $student_number Nomor siswa
     * @return bool Hasil penghapusan data
     */
    public function deleteObservedStudentWithRelatedData($class_id, $observer_user_id, $student_number)
    {
        // Menghapus data dari StudentObservationDetails yang terkait dengan student_number ini
        $this->StudentObservationDetail->deleteObservationDetailsByStudentNumber($class_id, $observer_user_id, $student_number);

        // Menghapus ObservedStudent
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id,
            'student_number' => $student_number
        ));
    }

    /**
     * Menghapus semua data siswa yang diamati dan data terkait
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil penghapusan data
     */
    public function deleteAllObservedStudentsWithRelatedData($class_id, $observer_user_id)
    {
        // Menghapus semua data StudentObservationDetails yang terkait dengan observer ini
        $this->StudentObservationDetail->deleteAllObservationDetailsByObserver($class_id, $observer_user_id);

        // Menghapus semua ObservedStudents
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }

    /**
     * Mendapatkan daftar nomor siswa yang diamati oleh observer tertentu dalam kelas tertentu
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return array Array nomor siswa
     */
    public function getStudentNumbersByObserver($class_id, $observer_user_id)
    {
        // Memilih kolom student_number
        $this->db->select('student_number');
        $this->db->from($this->table);
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        $query = $this->db->get();

        // Mengambil hasil query sebagai array
        $result = $query->result_array();

        // Membersihkan spasi pada student_number
        $student_numbers = array();
        foreach ($result as $row) {
            $student_numbers[] = trim($row['student_number']);
        }

        // Mengembalikan array nomor siswa
        return $student_numbers;
    }

    /**
     * Mendapatkan daftar nomor siswa unik yang diamati dalam kelas
     *
     * @param int $class_id ID kelas
     * @return array Array nomor siswa unik
     */
    public function getUniqueStudentNumbersByClass($class_id)
    {
        // Memilih nomor siswa unik dalam kelas
        $this->db->distinct();
        $this->db->select('student_number');
        $this->db->from($this->table);
        $this->db->where('class_id', $class_id);
        $query = $this->db->get();

        // Mengambil hasil query sebagai array
        $result = $query->result_array();

        // Membersihkan spasi pada student_number
        $student_numbers = array();
        foreach ($result as $row) {
            $student_numbers[] = trim($row['student_number']);
        }

        // Mengembalikan array nomor siswa unik
        return $student_numbers;
    }

    /**
     * Mendapatkan daftar nomor siswa yang diamati oleh semua observer dalam kelas tertentu
     *
     * @param int $class_id ID kelas
     * @return array Array nomor siswa
     */
    public function getStudentNumbersByClass($class_id)
    {
        // Memilih kolom student_number
        $this->db->select('student_number');
        $this->db->from($this->table);
        $this->db->where('class_id', $class_id);
        $query = $this->db->get();

        $student_numbers = array();
        foreach ($query->result() as $row) {
            $student_numbers[] = trim($row->student_number);
        }

        return $student_numbers;
    }
}