<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model ClassModel
 * 
 * Mengelola operasi database terkait kelas.
 */
class ClassModel extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'Classes';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
        // Memuat model yang diperlukan untuk memeriksa kelengkapan formulir
        $this->load->model('ClassObserver');
        $this->load->model('TeachingActivityAssessment');
        $this->load->model('StudentObservationSheet');
        $this->load->model('StudentActivityNote');
        $this->load->model('User');
    }

    /**
     * Membuat kelas Guru Model baru
     *
     * @param array $data Data kelas yang akan dibuat
     * @return int|bool ID kelas yang dibuat atau false jika gagal
     */
    public function createClass($data)
    {
        // Menambahkan timestamp pembuatan
        $data['created_at'] = date('Y-m-d H:i:s');
        // Menyimpan data ke tabel
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Mendapatkan kelas berdasarkan ID dengan informasi Guru Model
     *
     * @param int $class_id ID kelas
     * @return object Objek kelas
     */
    public function getClassById($class_id)
    {
        // Memilih kolom yang diperlukan dan melakukan join dengan tabel Users
        $this->db->select('Classes.*, Users.full_name AS guru_model_name, Users.src_profile_photo AS guru_model_src_profile_photo');
        $this->db->from('Classes');
        $this->db->join('Users', 'Classes.creator_user_id = Users.user_id', 'left');
        $this->db->where('Classes.class_id', $class_id);
        return $this->db->get()->row();
    }

    /**
     * Fungsi untuk mendapatkan kelas berdasarkan kode kelas
     *
     * @param string $class_code Kode kelas
     * @return object Objek kelas
     */
    public function getClassByCode($class_code)
    {
        // Menambahkan kondisi WHERE untuk class_code
        $this->db->where('class_code', $class_code);
        // Mengambil data dari tabel
        $query = $this->db->get($this->table);
        return $query->row();
    }

    /**
     * Memperbarui data kelas
     *
     * @param int $class_id ID kelas yang akan diperbarui
     * @param array $data Data yang akan diperbarui
     * @return bool Hasil update data
     */
    public function updateClass($class_id, $data)
    {
        // Menambahkan timestamp pembaruan
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Menambahkan kondisi WHERE untuk class_id
        $this->db->where('class_id', $class_id);
        // Melakukan update data
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus kelas
     *
     * @param int $class_id ID kelas yang akan dihapus
     * @return bool Hasil penghapusan data
     */
    public function deleteClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }

    /**
     * Mendapatkan kelas berdasarkan pembuat (Guru Model)
     *
     * @param int $creator_user_id ID pengguna pembuat kelas
     * @return array Array objek kelas
     */
    public function getClassesByCreator($creator_user_id)
    {
        // Mengambil data berdasarkan creator_user_id
        $query = $this->db->get_where($this->table, array('creator_user_id' => $creator_user_id));
        return $query->result();
    }

    /**
     * Menghitung jumlah kelas yang dibuat oleh pengguna tertentu
     *
     * @param int $user_id ID pengguna
     * @return int Jumlah kelas yang dibuat
     */
    public function countClassesByCreator($user_id)
    {
        // Menambahkan kondisi WHERE untuk creator_user_id
        $this->db->where('creator_user_id', $user_id);
        $this->db->from($this->table);
        // Menghitung jumlah hasil
        return $this->db->count_all_results();
    }

    /**
     * Mengambil kelas yang diikuti oleh pengguna sebagai Guru Model atau Observer, diurutkan berdasarkan tanggal terdekat
     * (Kelas yang sudah selesai tidak akan diambil)
     *
     * @param int $user_id ID pengguna
     * @return array Array objek kelas dengan status
     */
    public function getUpcomingClasses($user_id)
    {
        $today = date('Y-m-d'); // Tanggal hari ini
        $current_time = date('H:i:s'); // Waktu saat ini

        // Memilih semua kolom dari tabel Classes dan src_profile_photo dari Users
        $this->db->select('Classes.*, Users.src_profile_photo AS guru_model_src_profile_photo, Users.full_name AS guru_model_name');
        $this->db->from('Classes');

        // Menambahkan kondisi bahwa tanggal kelas harus hari ini atau lebih dan waktu selesai belum lewat
        $this->db->group_start();
        // Kondisi pertama: tanggal kelas lebih dari hari ini
        $this->db->where('Classes.date >', $today);
        // Kondisi kedua: tanggal kelas sama dengan hari ini dan waktu selesai lebih dari sekarang
        $this->db->or_group_start();
        $this->db->where('Classes.date', $today);
        $this->db->where('Classes.end_time >', $current_time);
        $this->db->group_end();
        $this->db->group_end();

        // Mengelompokkan kondisi OR antara Guru Model dan Observer
        $this->db->group_start();
        // Kondisi pertama: kelas dibuat oleh pengguna (Guru Model)
        $this->db->where('Classes.creator_user_id', $user_id);
        // Kondisi kedua: kelas diikuti sebagai Observer
        $subquery = '(SELECT class_id FROM ClassObservers WHERE observer_user_id = ' . $this->db->escape($user_id) . ')';
        $this->db->or_where("Classes.class_id IN $subquery", NULL, FALSE);
        $this->db->group_end();

        // Menambahkan JOIN untuk mengambil src_profile_photo dari Guru Model
        $this->db->join('Users', 'Classes.creator_user_id = Users.user_id', 'left');

        // Mengurutkan berdasarkan tanggal terdekat, waktu mulai, dan waktu pembuatan lebih awal
        $this->db->order_by('Classes.date', 'ASC');
        $this->db->order_by('Classes.start_time', 'ASC');
        $this->db->order_by('Classes.created_at', 'ASC'); // Mengurutkan berdasarkan created_at ASC

        // Mengambil hasil query
        $classes = $this->db->get()->result();

        // Menambahkan atribut 'class_type' pada setiap kelas
        foreach ($classes as $class) {
            if ($class->creator_user_id == $user_id) {
                $class->class_type = 'guru_model';
            } else {
                $class->class_type = 'observer';
            }
        }

        return $classes;
    }

    /**
     * Fungsi utama untuk mendapatkan kelas dengan status berdasarkan pengguna.
     * Fungsi ini memeriksa kelengkapan berbagai formulir yang terkait dengan kelas
     * baik sebagai Guru Model maupun sebagai Observer.
     *
     * @param int $user_id ID pengguna yang ingin diperiksa kelasnya
     * @return array Array objek kelas dengan status yang telah ditentukan
     */
    public function getClassesWithStatus($user_id)
    {
        // Mendapatkan semua kelas yang dibuat oleh pengguna
        $created_classes = $this->getClassesByCreator($user_id);
        foreach ($created_classes as $class) {
            $class->class_type = 'guru_model';
        }

        // Mendapatkan semua kelas yang diikuti oleh pengguna sebagai observer
        $observed_classes = $this->ClassObserver->getClassesByObserver($user_id);
        foreach ($observed_classes as $class) {
            $class->class_type = 'observer';
        }

        // Menggabungkan kedua array kelas
        $all_classes = array_merge($created_classes, $observed_classes);

        // Menghilangkan duplikasi kelas berdasarkan class_id
        $unique_classes = [];
        foreach ($all_classes as $class) {
            // Jika kelas sudah ada, skip
            if (isset($unique_classes[$class->class_id])) {
                continue;
            }
            $unique_classes[$class->class_id] = $class;
        }

        $current_datetime = date('Y-m-d H:i:s'); // Waktu saat ini
        $classes_with_status = [];

        foreach ($unique_classes as $class) {
            // Menggabungkan tanggal dan waktu mulai untuk membandingkan dengan waktu saat ini
            $class_start_datetime = $class->date . ' ' . $class->start_time;

            if (strtotime($current_datetime) < strtotime($class_start_datetime)) {
                // Jika waktu pelaksanaan belum dimulai
                $status = 'Akan Datang';
            } else {
                if ($class->class_type === 'guru_model') {
                    // Sebagai Guru Model, periksa apakah semua observer telah mengisi formulir lengkap
                    $observers = $this->ClassObserver->getObserversByClass($class->class_id);
                    $all_forms_completed = true;

                    foreach ($observers as $observer) {
                        // Cek Penilaian Kegiatan Mengajar
                        $assessment = $this->TeachingActivityAssessment->getAssessment($class->class_id, $observer->observer_user_id);
                        $isAssessmentComplete = $assessment &&
                            !is_null($assessment->score_question1) &&
                            !is_null($assessment->score_question2) &&
                            !is_null($assessment->score_question3) &&
                            !is_null($assessment->score_question4) &&
                            !is_null($assessment->score_question5) &&
                            !is_null($assessment->score_question6) &&
                            !is_null($assessment->score_question7) &&
                            !is_null($assessment->score_question8) &&
                            !is_null($assessment->score_question9) &&
                            !is_null($assessment->score_question10) &&
                            !empty($assessment->notes) &&
                            !empty($assessment->src_signature_file);

                        if (!$isAssessmentComplete) {
                            $all_forms_completed = false;
                            break;
                        }

                        // Cek Lembar Pengamatan Siswa
                        $observation_sheet = $this->StudentObservationSheet->getObservationSheet($class->class_id, $observer->observer_user_id);
                        $isObservationComplete = $observation_sheet &&
                            !empty($observation_sheet->notes) &&
                            !empty($observation_sheet->src_signature_file);

                        if (!$isObservationComplete) {
                            $all_forms_completed = false;
                            break;
                        }

                        // Cek Catatan Aktivitas Siswa
                        $activity_notes = $this->StudentActivityNote->getActivityNote($class->class_id, $observer->observer_user_id);
                        $isActivityNotesComplete = $activity_notes &&
                            !empty($activity_notes->answer_question1) &&
                            !empty($activity_notes->answer_question2) &&
                            !empty($activity_notes->answer_question3) &&
                            !empty($activity_notes->answer_question4) &&
                            !empty($activity_notes->answer_question5) &&
                            !empty($activity_notes->src_signature_file);

                        if (!$isActivityNotesComplete) {
                            $all_forms_completed = false;
                            break;
                        }
                    }

                    if ($all_forms_completed) {
                        $status = 'Selesai';
                    } else {
                        $status = 'Belum Dilengkapi';
                    }
                } elseif ($class->class_type === 'observer') {
                    // Sebagai Observer, periksa apakah pengguna telah mengisi formulir lengkap

                    // Cek Penilaian Kegiatan Mengajar
                    $assessment = $this->TeachingActivityAssessment->getAssessment($class->class_id, $user_id);
                    $isAssessmentComplete = $assessment &&
                        !is_null($assessment->score_question1) &&
                        !is_null($assessment->score_question2) &&
                        !is_null($assessment->score_question3) &&
                        !is_null($assessment->score_question4) &&
                        !is_null($assessment->score_question5) &&
                        !is_null($assessment->score_question6) &&
                        !is_null($assessment->score_question7) &&
                        !is_null($assessment->score_question8) &&
                        !is_null($assessment->score_question9) &&
                        !is_null($assessment->score_question10) &&
                        !empty($assessment->notes) &&
                        !empty($assessment->src_signature_file);

                    // Cek Lembar Pengamatan Siswa
                    $observation_sheet = $this->StudentObservationSheet->getObservationSheet($class->class_id, $user_id);
                    $isObservationComplete = $observation_sheet &&
                        !empty($observation_sheet->notes) &&
                        !empty($observation_sheet->src_signature_file);

                    // Cek Catatan Aktivitas Siswa
                    $activity_notes = $this->StudentActivityNote->getActivityNote($class->class_id, $user_id);
                    $isActivityNotesComplete = $activity_notes &&
                        !empty($activity_notes->answer_question1) &&
                        !empty($activity_notes->answer_question2) &&
                        !empty($activity_notes->answer_question3) &&
                        !empty($activity_notes->answer_question4) &&
                        !empty($activity_notes->answer_question5) &&
                        !empty($activity_notes->src_signature_file);

                    if ($isAssessmentComplete && $isObservationComplete && $isActivityNotesComplete) {
                        $status = 'Selesai';
                    } else {
                        $status = 'Belum Dilengkapi';
                    }
                } else {
                    // Jika role tidak dikenali
                    $status = 'Tidak Diketahui';
                }
            }

            // Menambahkan status ke objek kelas
            $class->status = $status;
            $classes_with_status[] = $class;
        }

        // Mengurutkan kelas berdasarkan created_at descending
        usort($classes_with_status, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        return $classes_with_status;
    }

    /**
     * Mendapatkan kelas dengan status berdasarkan pembuat kelas
     *
     * @param int $creator_user_id ID pengguna pembuat kelas
     * @return array Array objek kelas dengan status
     */
    public function getClassesWithStatusByCreator($creator_user_id)
    {
        // Mendapatkan semua kelas yang dibuat oleh pengguna
        $created_classes = $this->getClassesByCreator($creator_user_id);

        $current_datetime = date('Y-m-d H:i:s');
        $classes_with_status = [];

        foreach ($created_classes as $class) {
            // Menggabungkan tanggal dan waktu selesai untuk membandingkan dengan waktu saat ini
            $class_end_datetime = $class->date . ' ' . $class->end_time;

            if (strtotime($current_datetime) < strtotime($class_end_datetime)) {
                // Jika waktu pelaksanaan belum terjadi
                $status = 'Akan Datang';
            } else {
                // Jika waktu pelaksanaan sudah lewat, periksa kelengkapan formulir
                // Mendapatkan semua observer untuk kelas ini
                $observers = $this->ClassObserver->getObserversByClass($class->class_id);

                $all_forms_completed = true;

                foreach ($observers as $observer) {
                    // Cek Penilaian Kegiatan Mengajar
                    $assessment = $this->TeachingActivityAssessment->getAssessment($class->class_id, $observer->observer_user_id);
                    if (
                        !$assessment ||
                        is_null($assessment->score_question1) || is_null($assessment->score_question2) ||
                        is_null($assessment->score_question3) || is_null($assessment->score_question4) ||
                        is_null($assessment->score_question5) || is_null($assessment->score_question6) ||
                        is_null($assessment->score_question7) || is_null($assessment->score_question8) ||
                        is_null($assessment->score_question9) || is_null($assessment->score_question10) ||
                        empty($assessment->notes) ||
                        empty($assessment->src_signature_file)
                    ) {
                        $all_forms_completed = false;
                        break;
                    }

                    // Cek Lembar Pengamatan Siswa
                    $observation_sheet = $this->StudentObservationSheet->getObservationSheet($class->class_id, $observer->observer_user_id);
                    if (
                        !$observation_sheet ||
                        empty($observation_sheet->notes) ||
                        empty($observation_sheet->src_signature_file)
                    ) {
                        $all_forms_completed = false;
                        break;
                    }

                    // Cek Catatan Aktivitas Siswa
                    $activity_notes = $this->StudentActivityNote->getActivityNote($class->class_id, $observer->observer_user_id); // Pastikan nama model sesuai
                    if (
                        !$activity_notes ||
                        empty($activity_notes->answer_question1) ||
                        empty($activity_notes->answer_question2) ||
                        empty($activity_notes->answer_question3) ||
                        empty($activity_notes->answer_question4) ||
                        empty($activity_notes->answer_question5) ||
                        empty($activity_notes->src_signature_file)
                    ) {
                        $all_forms_completed = false;
                        break;
                    }
                }

                if ($all_forms_completed) {
                    $status = 'Selesai';
                } else {
                    $status = 'Belum Dilengkapi';
                }
            }

            // Menambahkan status ke objek kelas
            $class->status = $status;
            $classes_with_status[] = $class;
        }

        // Mengurutkan kelas berdasarkan created_at descending
        usort($classes_with_status, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        return $classes_with_status;
    }

    /**
     * Memeriksa apakah kode kelas sudah ada
     *
     * @param string $class_code Kode kelas yang akan diperiksa
     * @return bool True jika sudah ada, false jika belum
     */
    public function checkClassCodeExists($class_code)
    {
        // Menambahkan kondisi WHERE untuk class_code
        $this->db->where('class_code', $class_code);
        $query = $this->db->get($this->table);
        // Memeriksa jumlah baris hasil
        return ($query->num_rows() > 0);
    }
}