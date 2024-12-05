<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model ClassObserver
 * 
 * Mengelola operasi database terkait observer kelas.
 */
class ClassObserver extends CI_Model
{
    // Nama tabel yang digunakan
    protected $table = 'ClassObservers';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
        // Memuat model yang diperlukan untuk memeriksa kelengkapan formulir
        $this->load->model(['TeachingActivityAssessment', 'StudentObservationSheet', 'StudentActivityNote', 'User', 'ObservedStudent']);
    }

    /**
     * Menambahkan observer ke kelas
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil insert data
     */
    public function addObserver($class_id, $observer_user_id)
    {
        // Menyiapkan data untuk disimpan
        $data = array(
            'class_id'          => $class_id,
            'observer_user_id'  => $observer_user_id,
            'created_at'        => date('Y-m-d H:i:s')
        );
        // Menyimpan data ke tabel
        return $this->db->insert($this->table, $data);
    }

    /**
     * Mendapatkan observer berdasarkan kelas
     *
     * @param int $class_id ID kelas
     * @return array Array objek observer
     */
    public function getObserversByClass($class_id)
    {
        // Memilih kolom yang diperlukan dan melakukan join dengan tabel Users
        $this->db->select('Users.full_name, Users.email_address, Users.src_profile_photo, ClassObservers.observer_user_id');
        $this->db->from($this->table);
        $this->db->join('Users', 'ClassObservers.observer_user_id = Users.user_id', 'left');
        $this->db->where('ClassObservers.class_id', $class_id);
        $this->db->order_by('ClassObservers.created_at', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Menghapus data observer berdasarkan ID kelas
     *
     * @param int $class_id ID kelas
     * @return bool Hasil penghapusan data
     */
    public function deleteObserversByClass($class_id)
    {
        // Menghapus data berdasarkan class_id
        return $this->db->delete($this->table, array('class_id' => $class_id));
    }

    /**
     * Menghapus observer dari kelas
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil penghapusan data
     */
    public function removeObserver($class_id, $observer_user_id)
    {
        // Menghapus data berdasarkan class_id dan observer_user_id
        return $this->db->delete($this->table, array(
            'class_id'         => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }

    /**
     * Menghapus observer dan data terkait tanpa menghapus data nomor siswa yang tidak berubah
     *
     * @param int $class_id ID kelas
     * @param int $observer_user_id ID pengguna observer
     * @return bool Hasil penghapusan data
     */
    public function removeObserverWithRelatedData($class_id, $observer_user_id)
    {
        // Menghapus data dari ObservedStudents dan data terkait di StudentObservationSheet dan StudentActivityNotes
        $this->ObservedStudent->deleteAllObservedStudentsWithRelatedData($class_id, $observer_user_id);

        // Menghapus data dari TeachingActivityAssessment
        $this->TeachingActivityAssessment->deleteAssessmentByObserver($class_id, $observer_user_id);

        // Menghapus data dari StudentActivityNotes
        $this->StudentActivityNote->deleteActivityNoteByObserver($class_id, $observer_user_id);

        // Menghapus data dari SpecialNotes
        $this->SpecialNote->deleteNotesByClassAndObserver($class_id, $observer_user_id);

        // Menghapus data dari ClassDocumentationFiles
        $this->ClassDocumentationFile->deleteDocumentationsByClassAndObserver($class_id, $observer_user_id);

        // Menghapus data dari ClassVoiceRecordings
        $this->ClassVoiceRecording->deleteRecordingsByClassAndObserver($class_id, $observer_user_id);

        // Menghapus observer dari ClassObservers
        return $this->db->delete($this->table, array(
            'class_id'         => $class_id,
            'observer_user_id' => $observer_user_id
        ));
    }

    /**
     * Mendapatkan daftar kelas yang diikuti oleh observer tertentu
     *
     * @param int $user_id ID pengguna observer
     * @return array Array objek kelas
     */
    public function getClassesByObserver($user_id)
    {
        // Memilih kolom yang diperlukan dan melakukan join dengan tabel Classes dan Users
        $this->db->select('Classes.*, Users.full_name as guru_model, "observer" as class_type');
        $this->db->from('Classes');
        $this->db->join('ClassObservers', 'Classes.class_id = ClassObservers.class_id');
        $this->db->join('Users', 'Classes.creator_user_id = Users.user_id', 'left'); // Mengambil nama guru model
        $this->db->where('ClassObservers.observer_user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Menghitung jumlah kelas yang diikuti sebagai Observer oleh pengguna tertentu
     *
     * @param int $user_id ID pengguna
     * @return int Jumlah kelas yang diikuti
     */
    public function countClassesByObserver($user_id)
    {
        // Menambahkan kondisi WHERE untuk observer_user_id
        $this->db->where('observer_user_id', $user_id);
        $this->db->from($this->table);
        // Menghitung jumlah hasil
        return $this->db->count_all_results();
    }

    /**
     * Mendapatkan daftar kelas yang diikuti oleh observer tertentu beserta statusnya
     *
     * @param int $observer_user_id ID pengguna observer
     * @return array Array objek kelas dengan status
     */
    public function getClassesWithStatusByObserver($observer_user_id)
    {
        // Mendapatkan semua kelas yang diikuti oleh observer
        $classes = $this->getClassesByObserver($observer_user_id);

        $current_datetime = date('Y-m-d H:i:s');
        $classes_with_status = [];

        foreach ($classes as $class) {
            // Menggabungkan tanggal dan waktu mulai untuk membandingkan dengan waktu saat ini
            $class_start_datetime = $class->date . ' ' . $class->start_time;
            $class_end_datetime = $class->date . ' ' . $class->end_time;

            if (strtotime($current_datetime) < strtotime($class_start_datetime)) {
                // Jika waktu pelaksanaan belum dimulai
                $status = 'Akan Datang';
            } else {
                // Jika waktu pelaksanaan sudah dimulai atau telah lewat, periksa kelengkapan formulir oleh observer ini
                // Cek Penilaian Kegiatan Mengajar
                $assessment = $this->TeachingActivityAssessment->getAssessment($class->class_id, $observer_user_id);
                // Cek Lembar Pengamatan Siswa
                $observation_sheet = $this->StudentObservationSheet->getObservationSheet($class->class_id, $observer_user_id);
                // Cek Catatan Aktivitas Siswa
                $activity_notes = $this->StudentActivityNote->getActivityNote($class->class_id, $observer_user_id);

                // Fungsi untuk memeriksa kelengkapan Penilaian Kegiatan Mengajar
                $isAssessmentComplete = false;
                if ($assessment) {
                    $isAssessmentComplete =
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
                }

                // Fungsi untuk memeriksa kelengkapan Lembar Pengamatan Siswa
                $isObservationComplete = false;
                if ($observation_sheet) {
                    $isObservationComplete =
                        !empty($observation_sheet->notes) &&
                        !empty($observation_sheet->src_signature_file);
                }

                // Fungsi untuk memeriksa kelengkapan Catatan Aktivitas Siswa
                $isActivityNotesComplete = false;
                if ($activity_notes) {
                    $isActivityNotesComplete =
                        !empty($activity_notes->answer_question1) &&
                        !empty($activity_notes->answer_question2) &&
                        !empty($activity_notes->answer_question3) &&
                        !empty($activity_notes->answer_question4) &&
                        !empty($activity_notes->answer_question5) &&
                        !empty($activity_notes->src_signature_file);
                }

                // Menentukan status berdasarkan kelengkapan formulir
                if ($isAssessmentComplete && $isObservationComplete && $isActivityNotesComplete) {
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
     * Mengambil 4 Observer terbaru untuk suatu kelas
     *
     * @param int $class_id ID kelas
     * @param int $limit Jumlah observer yang diambil (default 4)
     * @return array Array objek observer
     */
    public function getLatestObservers($class_id, $limit = 4)
    {
        // Memilih kolom yang diperlukan dan melakukan join dengan tabel Users
        $this->db->select('Users.full_name, Users.email_address, Users.src_profile_photo');
        $this->db->from($this->table);
        $this->db->join('Users', 'ClassObservers.observer_user_id = Users.user_id', 'left');
        $this->db->where('ClassObservers.class_id', $class_id);
        $this->db->order_by('ClassObservers.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    /**
     * Memeriksa apakah seorang pengguna adalah observer dalam kelas tertentu
     *
     * @param int $class_id ID kelas
     * @param int $user_id ID pengguna
     * @return bool True jika pengguna adalah observer, false jika tidak
     */
    public function isUserObserver($class_id, $user_id)
    {
        // Menambahkan kondisi WHERE untuk class_id dan observer_user_id
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $user_id);
        $query = $this->db->get($this->table);
        // Memeriksa jumlah baris hasil
        return $query->num_rows() > 0;
    }

    /**
     * Mendapatkan observer dengan detail lengkap, termasuk daftar nomor siswa yang ditugaskan dan data formulir
     *
     * @param int $class_id ID kelas
     * @return array Array objek observer dengan detail
     */
    public function getObserversWithDetails($class_id)
    {
        // Mendapatkan daftar observer berdasarkan class_id
        $observers = $this->getObserversByClass($class_id);
        foreach ($observers as $observer) {
            // Mendapatkan daftar nomor siswa yang ditugaskan kepada observer
            $observer->assigned_students = $this->ObservedStudent->getStudentNumbersByObserver($class_id, $observer->observer_user_id);

            // Mendapatkan data formulir Penilaian Kegiatan Mengajar
            $observer->assessment = $this->TeachingActivityAssessment->getAssessment($class_id, $observer->observer_user_id);

            // Mendapatkan data formulir Lembar Pengamatan Siswa
            $observer->observation_sheet = $this->StudentObservationSheet->getObservationSheet($class_id, $observer->observer_user_id);

            // Mendapatkan data formulir Catatan Aktivitas Siswa
            $observer->activity_notes = $this->StudentActivityNote->getActivityNote($class_id, $observer->observer_user_id);

            // Mendapatkan catatan khusus
            $observer->special_notes = $this->SpecialNote->getNotesByClassAndObserver($class_id, $observer->observer_user_id);

            // Mendapatkan file dokumentasi
            $observer->documentation_files = $this->ClassDocumentationFile->getDocumentationsByClassAndObserver($class_id, $observer->observer_user_id);

            // Mendapatkan rekaman suara
            $observer->voice_recording = $this->ClassVoiceRecording->getRecordingByClassAndObserver($class_id, $observer->observer_user_id);
        }
        return $observers;
    }

    /**
     * Mendapatkan observer berdasarkan kelas dan ID pengguna
     *
     * @param int $class_id ID Kelas
     * @param int $observer_user_id ID Pengguna Observer
     * @return object|null Objek observer atau null jika tidak ditemukan
     */
    public function getObserver($class_id, $observer_user_id)
    {
        // Menambahkan kondisi WHERE untuk class_id dan observer_user_id
        $this->db->where('class_id', $class_id);
        $this->db->where('observer_user_id', $observer_user_id);
        $query = $this->db->get($this->table);
        return $query->row();
    }
}