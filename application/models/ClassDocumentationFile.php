<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model ClassDocumentationFile
 * 
 * Mengelola operasi database terkait file dokumentasi kelas.
 */
class ClassDocumentationFile extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'ClassDocumentationFiles';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menambahkan file dokumentasi baru
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @param string $file_src Sumber file dokumentasi
     * @return bool Hasil insert data
     */
    public function createDocumentation($class_id, $observer_user_id, $file_src)
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
     * Mendapatkan file dokumentasi berdasarkan ID Kelas dan ID Observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @return array Array objek dokumentasi
     */
    public function getDocumentationsByClassAndObserver($class_id, $observer_user_id)
    {
        // Menambahkan kondisi WHERE untuk class_id dan observer_user_id
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Mendapatkan semua dokumentasi berdasarkan ID Kelas
     *
     * @param int $class_id ID kelas
     * @return array Array objek dokumentasi
     */
    public function getDocumentationsByClass($class_id)
    {
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Menghapus data file dokumentasi berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteDocumentationsByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }

    /**
     * Menghapus data file dokumentasi berdasarkan ID kelas dan ID observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID observer
     * @return bool Hasil penghapusan data
     */
    public function deleteDocumentationsByClassAndObserver($class_id, $observer_user_id)
    {
        // Menghapus data berdasarkan class_id dan observer_user_id
        return $this->db->delete($this->table, array(
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }
}