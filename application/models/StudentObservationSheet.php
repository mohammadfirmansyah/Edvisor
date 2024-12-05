<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model StudentObservationSheet
 * 
 * Mengelola operasi database terkait lembar pengamatan siswa.
 */
class StudentObservationSheet extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'StudentObservationSheet';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Membuat lembar pengamatan siswa baru
     *
     * @param array $data Data lembar pengamatan siswa
     * @return int ID lembar pengamatan yang dibuat
     */
    public function createObservationSheet($data)
    {
        // Menambahkan timestamp pembuatan
        $data['created_at'] = date('Y-m-d H:i:s');
        // Menyimpan data ke tabel
        $this->db->insert($this->table, $data);
        return $this->db->insert_id(); // Mengembalikan ID terakhir yang dimasukkan
    }

    /**
     * Mendapatkan semua lembar pengamatan siswa berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return array Array objek lembar pengamatan siswa
     */
    public function getObservationSheetsByClass($class_id)
    {
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->result(); // Mengembalikan hasil sebagai array objek
    }

    /**
     * Mendapatkan lembar pengamatan siswa berdasarkan ID kelas dan ID observer
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return object Objek lembar pengamatan siswa
     */
    public function getObservationSheet($class_id, $observer_user_id)
    {
        // Mengambil data berdasarkan class_id dan observer_user_id
        return $this->db->get_where($this->table, [
            'class_id' => $class_id,
            'observer_user_id' => $observer_user_id
        ])->row();
    }

    /**
     * Memperbarui lembar pengamatan siswa
     *
     * @param int $observation_id ID pengamatan
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateObservationSheet($observation_id, $data)
    {
        // Menambahkan timestamp pembaruan
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Menambahkan kondisi WHERE untuk observation_id
        $this->db->where('observation_id', $observation_id);
        // Melakukan update data
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus StudentObservationSheet berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteObservationSheetsByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }
}
