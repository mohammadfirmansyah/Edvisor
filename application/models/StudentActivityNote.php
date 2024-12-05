<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model StudentActivityNote
 * 
 * Mengelola operasi database terkait catatan aktivitas siswa.
 */
class StudentActivityNote extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'StudentActivityNotes';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Membuat catatan aktivitas siswa baru
     *
     * @param array $data Data catatan aktivitas siswa
     * @return bool Hasil insert data
     */
    public function createActivityNote($data)
    {
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan semua catatan aktivitas siswa berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return array Array objek catatan aktivitas siswa
     */
    public function getActivityNotesByClass($class_id)
    {
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result(); // Mengembalikan hasil sebagai array objek
    }

    /**
     * Mendapatkan catatan aktivitas siswa berdasarkan ID kelas dan ID observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return object Objek catatan aktivitas siswa
     */
    public function getActivityNote($class_id, $observer_user_id)
    {
        // Mengambil data berdasarkan class_id dan observer_user_id
        return $this->db->get_where($this->table, [
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ])->row();
    }

    /**
     * Memperbarui catatan aktivitas siswa
     *
     * @param int $activity_notes_id ID catatan aktivitas siswa
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateActivityNote($activity_notes_id, $data)
    {
        // Menambahkan kondisi WHERE untuk activity_notes_id
        $this->db->where('activity_notes_id', $activity_notes_id);
        // Melakukan update data
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus Catatan Aktivitas Siswa berdasarkan observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil penghapusan data
     */
    public function deleteActivityNoteByObserver($class_id, $observer_user_id)
    {
        // Menghapus data berdasarkan class_id dan observer_user_id
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }

    /**
     * Menghapus Catatan Aktivitas Siswa berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteActivityNotesByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }
}