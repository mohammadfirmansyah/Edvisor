<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Notification
 * 
 * Mengelola operasi database terkait notifikasi.
 */
class Notification extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'Notifications';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Membuat atau memperbarui notifikasi
     *
     * @param int $sender_id ID pengirim notifikasi
     * @param int $receiver_id ID penerima notifikasi
     * @param int $class_id ID kelas terkait
     * @param string $notification_text Teks notifikasi
     * @param string $notification_type Jenis notifikasi
     * @return bool Hasil insert atau update data
     */
    public function createNotification($sender_id, $receiver_id, $class_id, $notification_text, $notification_type)
    {
        // Cek apakah notifikasi dengan tipe yang sama sudah ada
        $this->db->from($this->table);
        $this->db->where('sender_id', $sender_id);
        $this->db->where('receiver_id', $receiver_id);
        $this->db->where('class_id', $class_id);
        $this->db->where('notification_type', $notification_type);
        $existing_notification = $this->db->get()->row();

        if ($existing_notification) {
            // Jika notifikasi sudah ada, update updated_at dan teks notifikasi
            $data = array(
                'notification_text' => $notification_text,
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->where('notification_id', $existing_notification->notification_id);
            return $this->db->update($this->table, $data);
        } else {
            // Jika belum ada, buat notifikasi baru
            $data = array(
                'sender_id'         => $sender_id,
                'receiver_id'       => $receiver_id,
                'class_id'          => $class_id,
                'notification_text' => $notification_text,
                'notification_type' => $notification_type,
                'timestamp'         => date('Y-m-d H:i:s')
            );
            return $this->db->insert($this->table, $data);
        }
    }

    /**
     * Mendapatkan notifikasi berdasarkan penerima, diurutkan dari terbaru
     *
     * @param int $receiver_id ID penerima notifikasi
     * @return array Array objek notifikasi
     */
    public function getNotificationsByReceiver($receiver_id)
    {
        // Menambahkan join dengan tabel Users untuk mendapatkan sender_name dan sender_photo
        $this->db->select('Notifications.*, Users.full_name as sender_name, Users.src_profile_photo as sender_photo');
        $this->db->from($this->table);
        $this->db->join('Users', 'Notifications.sender_id = Users.user_id', 'left');
        $this->db->where('receiver_id', $receiver_id);
        $this->db->order_by('timestamp', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Menghapus notifikasi
     *
     * @param int $notification_id ID notifikasi yang akan dihapus
     * @return bool Hasil penghapusan data
     */
    public function deleteNotification($notification_id)
    {
        // Menghapus data berdasarkan notification_id
        return $this->db->delete($this->table, array('notification_id' => $notification_id));
    }

    /**
     * Menghapus notifikasi berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteNotificationsByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }
}