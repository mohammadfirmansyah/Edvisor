-- Menghapus database 'edvisor' jika sudah ada
DROP DATABASE IF EXISTS edvisor;

-- Membuat database 'edvisor' jika belum ada
CREATE DATABASE IF NOT EXISTS edvisor;
USE edvisor;

-- Tabel 'Users' untuk menyimpan data pengguna
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik pengguna (Primary Key)
    full_name VARCHAR(255) NOT NULL, -- Nama lengkap pengguna
    email_address VARCHAR(255) NOT NULL UNIQUE, -- Alamat email pengguna yang unik
    password VARCHAR(255) NOT NULL, -- Password pengguna
    last_password_reset DATETIME NULL DEFAULT NULL, -- Waktu terakhir password direset
    remember_token VARCHAR(255) NULL DEFAULT NULL, -- Token untuk fitur 'remember me'
    current_session_id VARCHAR(128) NULL DEFAULT NULL, -- ID sesi saat ini pengguna
    last_activity DATETIME NULL DEFAULT NULL, -- Waktu aktivitas terakhir pengguna
    last_browser VARCHAR(255), -- Browser terakhir yang digunakan pengguna
    last_mode ENUM('private', 'normal') DEFAULT 'normal', -- Mode terakhir pengguna (private/normal)
    src_profile_photo VARCHAR(255), -- Sumber file foto profil pengguna
    registration_number VARCHAR(50), -- Nomor registrasi pengguna
    status ENUM('active', 'wait', 'inactive') DEFAULT 'inactive', -- Status pengguna (aktif/menunggu/inaktif)
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan data pengguna
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP -- Waktu terakhir data pengguna diperbarui
) ENGINE=InnoDB;

-- Menambahkan indeks pada kolom last_activity untuk mempercepat query
CREATE INDEX idx_last_activity ON Users(last_activity);

-- Tabel 'ci_sessions' untuk menyimpan data sesi pengguna
CREATE TABLE IF NOT EXISTS ci_sessions (
    id VARCHAR(128) NOT NULL, -- ID sesi (Primary Key)
    user_id INT NULL, -- ID pengguna yang terkait dengan sesi (Foreign Key ke Users)
    ip_address VARCHAR(45) NOT NULL, -- Alamat IP pengguna
    browser VARCHAR(255), -- Browser yang digunakan selama sesi
    mode_private BOOLEAN DEFAULT FALSE, -- Mode private sesi (true/false)
    timestamp INT(10) UNSIGNED DEFAULT 0 NOT NULL, -- Timestamp sesi
    data BLOB NOT NULL, -- Data sesi dalam bentuk BLOB
    status ENUM('active', 'wait', 'inactive') DEFAULT 'inactive', -- Status sesi (aktif/inaktif)
    PRIMARY KEY (id), -- Menetapkan 'id' sebagai Primary Key
    KEY `ci_sessions_timestamp` (`timestamp`), -- Indeks pada kolom 'timestamp' untuk mempercepat query
    KEY `ci_sessions_user_id` (`user_id`), -- Indeks pada kolom 'user_id' untuk mempercepat query
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'Classes' untuk menyimpan data kelas Guru Model
CREATE TABLE IF NOT EXISTS Classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik kelas (Primary Key)
    class_name VARCHAR(255) NOT NULL, -- Nama kelas
    school_name VARCHAR(255) NOT NULL, -- Nama sekolah
    subject VARCHAR(255) NOT NULL, -- Mata pelajaran
    basic_competency VARCHAR(255) NOT NULL, -- Kompetensi dasar
    date DATE NOT NULL, -- Tanggal pelaksanaan kelas
    start_time TIME NOT NULL, -- Waktu mulai kelas
    end_time TIME NOT NULL, -- Waktu selesai kelas
    class_code VARCHAR(255) NOT NULL UNIQUE, -- Kode unik kelas
    src_student_data_file VARCHAR(255), -- Sumber file data siswa
    src_teaching_module_file VARCHAR(255), -- Sumber file modul pengajaran
    src_learning_media_file VARCHAR(255), -- Sumber file media pembelajaran
    creator_user_id INT NOT NULL, -- ID pengguna yang membuat kelas (Foreign Key ke Users)
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan kelas
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir kelas diperbarui
    FOREIGN KEY (creator_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'ClassVoiceRecordings' untuk menyimpan sumber file rekaman suara per observer dalam kelas
CREATE TABLE IF NOT EXISTS ClassVoiceRecordings (
    recording_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik rekaman (Primary Key)
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    file_src VARCHAR(255) NOT NULL, -- Sumber file rekaman suara
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan rekaman
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir rekaman diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'SpecialNotes' untuk menyimpan catatan khusus per observer dalam kelas
CREATE TABLE IF NOT EXISTS SpecialNotes (
    note_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik catatan (Primary Key)
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    activity_type VARCHAR(255) NOT NULL, -- Jenis aktivitas yang dicatat
    note_details TEXT NOT NULL, -- Detail catatan
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan catatan
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir catatan diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'ClassDocumentationFiles' untuk menyimpan sumber file dokumentasi per observer dalam kelas
CREATE TABLE IF NOT EXISTS ClassDocumentationFiles (
    documentation_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik dokumentasi (Primary Key)
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    file_src VARCHAR(255) NOT NULL, -- Sumber file dokumentasi
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan dokumentasi
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir dokumentasi diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'ClassObservers' untuk menyimpan informasi observer dalam kelas
CREATE TABLE IF NOT EXISTS ClassObservers (
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    PRIMARY KEY (class_id, observer_user_id), -- Kombinasi Primary Key dari class_id dan observer_user_id
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu penambahan observer
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir data observer diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'ObservedStudents' untuk menyimpan daftar siswa yang diamati oleh setiap observer
CREATE TABLE IF NOT EXISTS ObservedStudents (
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke ClassObservers)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke ClassObservers)
    student_number VARCHAR(10) NOT NULL, -- Nomor siswa yang diamati
    PRIMARY KEY (class_id, observer_user_id, student_number), -- Kombinasi Primary Key dari class_id, observer_user_id, dan student_number
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu penambahan siswa yang diamati
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir data siswa yang diamati diperbarui
    FOREIGN KEY (class_id, observer_user_id) REFERENCES ClassObservers(class_id, observer_user_id) -- Hubungan Foreign Key ke tabel ClassObservers
) ENGINE=InnoDB;

-- Tabel 'Notifications' untuk menyimpan notifikasi pengguna
CREATE TABLE IF NOT EXISTS Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik notifikasi (Primary Key)
    sender_id INT NOT NULL, -- ID pengguna yang mengirim notifikasi (Foreign Key ke Users)
    receiver_id INT NOT NULL, -- ID pengguna yang menerima notifikasi (Foreign Key ke Users)
    class_id INT NOT NULL, -- ID kelas terkait notifikasi (Foreign Key ke Classes)
    notification_text TEXT NOT NULL, -- Isi teks notifikasi
    notification_type VARCHAR(255) NOT NULL, -- Jenis notifikasi
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pengiriman notifikasi
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir notifikasi diperbarui
    FOREIGN KEY (sender_id) REFERENCES Users(user_id), -- Hubungan Foreign Key ke tabel Users (pengirim)
    FOREIGN KEY (receiver_id) REFERENCES Users(user_id), -- Hubungan Foreign Key ke tabel Users (penerima)
    FOREIGN KEY (class_id) REFERENCES Classes(class_id) -- Hubungan Foreign Key ke tabel Classes
) ENGINE=InnoDB;

-- Tabel 'TeachingActivityAssessment' untuk menyimpan Penilaian Kegiatan Mengajar
CREATE TABLE IF NOT EXISTS TeachingActivityAssessment (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik penilaian (Primary Key)
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    score_question1 INT, -- Skor untuk pertanyaan 1
    score_question2 INT, -- Skor untuk pertanyaan 2
    score_question3 INT, -- Skor untuk pertanyaan 3
    score_question4 INT, -- Skor untuk pertanyaan 4
    score_question5 INT, -- Skor untuk pertanyaan 5
    score_question6 INT, -- Skor untuk pertanyaan 6
    score_question7 INT, -- Skor untuk pertanyaan 7
    score_question8 INT, -- Skor untuk pertanyaan 8
    score_question9 INT, -- Skor untuk pertanyaan 9
    score_question10 INT, -- Skor untuk pertanyaan 10
    total_score INT, -- Total skor penilaian
    converted_value FLOAT, -- Nilai yang dikonversi dari total skor
    notes TEXT, -- Catatan tambahan
    src_signature_file VARCHAR(255), -- Sumber file tanda tangan
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan penilaian
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir penilaian diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'StudentObservationSheet' untuk menyimpan lembar pengamatan siswa
CREATE TABLE IF NOT EXISTS StudentObservationSheet (
    observation_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik pengamatan (Primary Key)
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    notes TEXT, -- Catatan pengamatan
    src_signature_file VARCHAR(255), -- Sumber file tanda tangan
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan lembar pengamatan
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir lembar pengamatan diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Tabel 'StudentObservationDetails' untuk menyimpan detail pengamatan siswa
CREATE TABLE IF NOT EXISTS StudentObservationDetails (
    observation_id INT NOT NULL, -- ID pengamatan (Foreign Key ke StudentObservationSheet)
    student_number VARCHAR(10) NOT NULL, -- Nomor siswa yang diamati
    indicator_number INT NOT NULL, -- Nomor indikator pengamatan
    value BOOLEAN, -- Nilai indikator (true/false)
    PRIMARY KEY (observation_id, student_number, indicator_number), -- Kombinasi Primary Key dari observation_id, student_number, dan indicator_number
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan detail pengamatan
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir detail pengamatan diperbarui
    FOREIGN KEY (observation_id) REFERENCES StudentObservationSheet(observation_id) -- Hubungan Foreign Key ke tabel StudentObservationSheet
) ENGINE=InnoDB;

-- Tabel 'StudentActivityNotes' untuk menyimpan Catatan Aktivitas Siswa
CREATE TABLE IF NOT EXISTS StudentActivityNotes (
    activity_notes_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik catatan aktivitas (Primary Key)
    class_id INT NOT NULL, -- ID kelas terkait (Foreign Key ke Classes)
    observer_user_id INT NOT NULL, -- ID pengguna pengamat (Foreign Key ke Users)
    answer_question1 TEXT, -- Jawaban untuk pertanyaan 1
    answer_question2 TEXT, -- Jawaban untuk pertanyaan 2
    answer_question3 TEXT, -- Jawaban untuk pertanyaan 3
    answer_question4 TEXT, -- Jawaban untuk pertanyaan 4
    answer_question5 TEXT, -- Jawaban untuk pertanyaan 5
    src_signature_file VARCHAR(255), -- Sumber file tanda tangan
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan catatan aktivitas
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Waktu terakhir catatan aktivitas diperbarui
    FOREIGN KEY (class_id) REFERENCES Classes(class_id), -- Hubungan Foreign Key ke tabel Classes
    FOREIGN KEY (observer_user_id) REFERENCES Users(user_id) -- Hubungan Foreign Key ke tabel Users
) ENGINE=InnoDB;

-- Mengaktifkan event scheduler
SET GLOBAL event_scheduler = ON;

-- Mengubah delimiter untuk pembuatan event
DELIMITER //

-- Menghapus event 'check_wait_users' jika sudah ada dan membuat event baru
DROP EVENT IF EXISTS `check_wait_users` //

CREATE EVENT `check_wait_users`
ON SCHEDULE EVERY 10 SECOND -- Mengatur jadwal setiap 10 detik
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Mengubah status pengguna dari 'wait' menjadi 'inactive' jika tidak ada aktivitas selama lebih dari 10 detik
    UPDATE Users u
    INNER JOIN ci_sessions s ON u.current_session_id = s.id
    SET 
        u.status = 'inactive', 
        u.current_session_id = NULL,
        s.status = 'inactive', 
        s.user_id = NULL
    WHERE 
        u.status = 'wait'
        AND u.last_activity IS NOT NULL
        AND TIMESTAMPDIFF(SECOND, u.last_activity, NOW()) > 10;

    -- Mengubah status pengguna dari 'active' menjadi 'inactive' jika tidak ada pembaruan aktivitas selama lebih dari 1 menit
    UPDATE Users u
    INNER JOIN ci_sessions s ON u.current_session_id = s.id
    SET 
        u.status = 'inactive', 
        u.current_session_id = NULL,
        s.status = 'inactive', 
        s.user_id = NULL
    WHERE 
        u.status = 'active'
        AND u.last_activity IS NOT NULL
        AND TIMESTAMPDIFF(SECOND, u.last_activity, NOW()) > 60;
END //

-- Menghapus event 'cleanup_ci_sessions' jika sudah ada dan membuat event baru
DROP EVENT IF EXISTS `cleanup_ci_sessions` //

CREATE EVENT `cleanup_ci_sessions`
ON SCHEDULE EVERY 10 SECOND -- Mengatur jadwal setiap 10 detik
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Menghapus data di ci_sessions yang memiliki status 'inactive'
    DELETE FROM ci_sessions
    WHERE status = 'inactive';
END //

-- Mengembalikan delimiter ke default
DELIMITER ;