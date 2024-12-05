<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model SpecialNote
 * 
 * Mengelola operasi database terkait catatan khusus.
 */
class SpecialNote extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'SpecialNotes';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Membuat catatan khusus baru
     *
     * @param array $data Data catatan khusus (harus berisi 'class_id', 'observer_user_id', 'activity_type', 'note_details')
     * @return bool Hasil insert data
     */
    public function createNote($data)
    {
        // Menambahkan timestamp pembuatan
        $data['created_at'] = date('Y-m-d H:i:s');
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan catatan khusus berdasarkan ID Kelas dan ID Observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @return array Array objek catatan
     */
    public function getNotesByClassAndObserver($class_id, $observer_user_id)
    {
        // Menambahkan kondisi WHERE untuk class_id dan observer_user_id
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Mendapatkan semua catatan khusus berdasarkan ID Kelas
     *
     * @param int $class_id ID kelas
     * @return array Array objek catatan
     */
    public function getNotesByClass($class_id)
    {
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Menghapus catatan khusus berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteNotesByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }

    /**
     * Menghapus catatan khusus berdasarkan ID kelas dan ID observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @return bool Hasil penghapusan data
     */
    public function deleteNotesByClassAndObserver($class_id, $observer_user_id)
    {
        // Menghapus data berdasarkan class_id dan observer_user_id
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }
}