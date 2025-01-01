<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1, width=device-width">
	<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
	<base href="<?= base_url(); ?>">
</head>

<body>
	<!-- Menampilkan pesan flashdata (sukses atau error) -->
	<?php
	$success_message = $this->session->flashdata('success');
	$error_message = $this->session->flashdata('error');
	?>
	<div id="flashdata"
		data-success="<?= isset($success_message) ? htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') : ''; ?>"
		data-error="<?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : ''; ?>">
	</div>

	<!-- Konten utama -->
	<div class="detail-kelas unselectable-text">
		<!-- Sidebar -->
		<div class="sidebar">
			<!-- Logo dan nama aplikasi -->
			<a class="logo-ls-1-parent link" href="sidebarBeranda">
				<img oncontextmenu="return false;" class="logo-ls-1" alt=""
					src="assets/img/logo_lesson_study_sidebar.svg">
				<b class="lesson-study">edvisor</b>
			</a>
			<!-- Profil pengguna -->
			<a class="profile-side-bar link" href="sidebarProfile">
				<div class="profile-side-bar-child" alt="">
					<img oncontextmenu="return false;" class="profile-photo"
						src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>"
						alt="">
				</div>
				<div class="nama-pengguna"><?= htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></div>
				<div class="email-pengguna"><?= htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8'); ?></div>
			</a>
			<!-- Menu navigasi -->
			<div class="menu-bar">
				<a class="item-side-bar-default link" href="sidebarBeranda">
					<img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
						src="assets/img/icon_beranda.svg">
					<div class="text-sidebar-default">Beranda</div>
				</a>
				<a class="item-side-bar-active link" href="sidebarGuruModel">
					<img oncontextmenu="return false;" class="icon-sidebar-active" alt=""
						src="assets/img/icon_guru_model.svg">
					<div class="text-sidebar-active">Guru Model</div>
				</a>
				<a class="item-side-bar-default link" href="sidebarObserver">
					<img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
						src="assets/img/icon_observer.svg">
					<div class="text-sidebar-default">Observer</div>
				</a>
				<a class="item-side-bar-default link" href="sidebarBantuan">
					<img oncontextmenu="return false;" class="icon-sidebar-default" alt=""
						src="assets/img/icon_bantuan.svg">
					<div class="text-sidebar-default">Bantuan</div>
				</a>
			</div>
			<!-- Tombol keluar -->
			<a class="item-side-bar-exit link" href="sidebarLogout">
				<img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_keluar.svg">
				<div class="text-sidebar-default">Keluar</div>
			</a>
		</div>

		<!-- Header Detail Kelas -->
		<a class="detail-kelas-group link" href="sidebarGuruModel">
			<img oncontextmenu="return false;" class="iconsolidarrow-left" alt="" src="assets/img/icon_arrow_left.svg">
			<div class="detail-kelas1">Detail Kelas</div>
		</a>

		<!-- Tanggal dan Waktu -->
		<div class="date-container">
			<p id="dateDisplay" class="date"></p>
			<p id="timeDisplay" class="date"></p>
		</div>

		<!-- Informasi Kelas -->
		<div class="xi-rekayasa-perangkat-lunak-b-parent">
			<div class="xi-rekayasa-perangkat"><?= htmlspecialchars($class->class_name, ENT_QUOTES, 'UTF-8'); ?></div>
			<div class="dasar-pemrograman">
				<span class="dasar-pemrograman1"><?= htmlspecialchars($class->subject, ENT_QUOTES, 'UTF-8'); ?></span>
				<span class="dasar-pemrograman2"> | <?= $class->number_of_students; ?> Siswa</span>
			</div>
			<div class="kompetensi-dasar-menerapkan-container">
				<span>Kompetensi Dasar: </span>
				<span class="kompetensi-dasar"><?= htmlspecialchars($class->basic_competency, ENT_QUOTES, 'UTF-8'); ?></span>
			</div>
			<div class="group-parent">
				<div class="frame-wrapper">
					<div class="date-range-parent">
						<img oncontextmenu="return false;" class="date-range-icon" alt=""
							src="assets/img/icon_calendar.svg">
						<div
							class="kompetensi-dasar-menerapkan-container1"><?= htmlspecialchars(indonesian_date($class->date), ENT_QUOTES, 'UTF-8'); ?>
						</div>
					</div>
				</div>
				<div class="alarm-parent">
					<img oncontextmenu="return false;" class="date-range-icon" alt="" src="assets/img/icon_alarm.svg">
					<div class="kompetensi-dasar-menerapkan-container1"><?= date('H:i', strtotime($class->start_time)) . ' - ' . date('H:i', strtotime($class->end_time)); ?>
					</div>
				</div>
			</div>
			<div class="kode-kelas-f6y8iotrqwmuge7wob-parent">
				<div class="kode-kelas-f6y8iotrqwmuge7wob-container">
					<span class="kode-kelas">Kode Kelas: </span>
					<span class="f6y8iotrqwmuge7wobny"><?= htmlspecialchars($class->class_code, ENT_QUOTES, 'UTF-8'); ?></span>
					<span class="tooltip-wrapper">
						<img oncontextmenu="return false;" class="iconoutlineduplicate" alt="" id="iconCopy"
							src="assets/img/icon_copy_white.svg">
						<div class="kode-kelas-tersalin-wrapper">
							<div class="kode-kelas-tersalin" id="copySuccess">Kode Kelas Tersalin</div>
							<div class="kode-kelas-tersalin" id="copyHover">Salin</div>
						</div>
					</span>
				</div>
			</div>
			<div class="tooltip-wrapper1">
				<img oncontextmenu="return false;" class="iconoutlineduplicate" alt="" id="iconEdit"
					data-idkelas="<?= $encrypted_idKelas; ?>" src="assets/img/icon_edit.svg">
				<div class="kode-kelas-tersalin-wrapper1">
					<div class="kode-kelas-tersalin1" id="iconHover1">Edit Data Kelas</div>
				</div>
			</div>
			<div class="tooltip-wrapper2">
				<img oncontextmenu="return false;" class="iconoutlineduplicate" alt="" id="iconHapus"
					data-idkelas="<?= $encrypted_idKelas; ?>" src="assets/img/icon_delete_class.svg">
				<div class="kode-kelas-tersalin-wrapper2">
					<div class="kode-kelas-tersalin2" id="iconHover2">Hapus Kelas</div>
				</div>
			</div>
		</div>

		<!-- Berkas yang Dibutuhkan -->
		<div class="berkas-yang-dibutuhkan-parent">
			<div class="berkas-yang-dibutuhkan">Berkas yang dibutuhkan</div>
			<div class="frame-child"></div>

			<!-- Data Siswa -->
			<img oncontextmenu="return false;" class="iconsoliddocument-text" alt="" src="assets/img/icon_excel.svg">
			<div class="text-button">
				<div class="ini-adalah-text">Data Siswa</div>
			</div>
			<div class="kb"><?= $data_siswa_size; ?></div>
			<div class="download-button" data-filepath="<?= $data_siswa_share_link; ?>"
				data-filename="DataSiswa.<?= pathinfo($data_siswa_path, PATHINFO_EXTENSION); ?>">
				<!-- Tombol Download Data Siswa -->
				<div class="download-button-child"></div>
				<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
					src="assets/img/icon_download.svg">
				<div class="frame-parent1">
					<div class="unduh-wrapper">
						<div class="unduh">Unduh</div>
					</div>
					<img oncontextmenu="return false;" class="group-child" alt="Polygon"
						src="assets/img/polygon.svg">
				</div>
			</div>
			<div class="share-button-icon" data-link="<?= $data_siswa_share_link; ?>">
				<!-- Tombol Share Data Siswa -->
				<div class="share-button-icon-child"></div>
				<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
					src="assets/img/icon_share.svg">
				<div class="frame-group1">
					<div class="bagikan-wrapper">
						<div class="bagikan copyHover">Bagikan</div>
						<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
					</div>
					<img oncontextmenu="return false;" class="group-item" alt="Polygon"
						src="assets/img/polygon.svg">
				</div>
			</div>

			<!-- Modul Ajar -->
			<img oncontextmenu="return false;" class="iconsoliddocument-text1" alt="" src="assets/img/icon_pdf.svg">
			<div class="text-button1">
				<div class="ini-adalah-text">Modul Ajar</div>
			</div>
			<div class="kb1"><?= $modul_ajar_size; ?></div>
			<div class="download-button1" data-filepath="<?= $modul_ajar_share_link; ?>"
				data-filename="ModulAjar.<?= pathinfo($modul_ajar_path, PATHINFO_EXTENSION); ?>">
				<!-- Tombol Download Modul Ajar -->
				<div class="download-button-child"></div>
				<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
					src="assets/img/icon_download.svg">
				<div class="frame-parent1">
					<div class="unduh-wrapper">
						<div class="unduh">Unduh</div>
					</div>
					<img oncontextmenu="return false;" class="group-child" alt="Polygon"
						src="assets/img/polygon.svg">
				</div>
			</div>
			<div class="share-button-icon1" data-link="<?= $modul_ajar_share_link; ?>">
				<!-- Tombol Share Modul Ajar -->
				<div class="share-button-icon-child"></div>
				<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
					src="assets/img/icon_share.svg">
				<div class="frame-group1">
					<div class="bagikan-wrapper">
						<div class="bagikan copyHover">Bagikan</div>
						<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
					</div>
					<img oncontextmenu="return false;" class="group-item" alt="Polygon"
						src="assets/img/polygon.svg">
				</div>
			</div>

			<!-- Media Pembelajaran -->
			<img oncontextmenu="return false;" class="iconsoliddocument-text2" alt="" src="assets/img/icon_pdf.svg">
			<div class="text-button2">
				<div class="ini-adalah-text">Media Pembelajaran</div>
			</div>
			<div class="kb2"><?= $media_pembelajaran_size; ?></div>
			<div class="download-button2" data-filepath="<?= $media_pembelajaran_share_link; ?>"
				data-filename="MediaPembelajaran.<?= pathinfo($media_pembelajaran_path, PATHINFO_EXTENSION); ?>">
				<!-- Tombol Download Media Pembelajaran -->
				<div class="download-button-child"></div>
				<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
					src="assets/img/icon_download.svg">
				<div class="frame-parent1">
					<div class="unduh-wrapper">
						<div class="unduh">Unduh</div>
					</div>
					<img oncontextmenu="return false;" class="group-child" alt="Polygon"
						src="assets/img/polygon.svg">
				</div>
			</div>
			<div class="share-button-icon2" data-link="<?= $media_pembelajaran_share_link; ?>">
				<!-- Tombol Share Media Pembelajaran -->
				<div class="share-button-icon-child"></div>
				<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
					src="assets/img/icon_share.svg">
				<div class="frame-group1">
					<div class="bagikan-wrapper">
						<div class="bagikan copyHover">Bagikan</div>
						<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
					</div>
					<img oncontextmenu="return false;" class="group-item" alt="Polygon"
						src="assets/img/polygon.svg">
				</div>
			</div>
		</div>

		<!-- Observer Section -->
		<div class="observer">
			<div class="observer1">Observer</div>
			<div class="observer-child"></div>
			<div class="bagas-nugroho"></div> <!-- Nama Observer Akan Diisi oleh JavaScript -->
			<div class="list-observer">
				<!-- Observer akan diisi oleh JavaScript -->
			</div>
			<div class="frame-parent">
				<div class="siswa-yang-diamati-wrapper">
					<div class="siswa-yang-diamati">Siswa yang diamati</div>
				</div>
				<div class="nomor-siswa-parent">
					<!-- Nomor siswa akan diisi oleh JavaScript -->
				</div>
			</div>

			<!-- Dokumen Observasi -->
			<div class="dokumen-observasi">
				<div class="dokumen-observasi-inner">
					<div class="observasi-dan-penilaian-parent">
						<div class="siswa-yang-diamati">Observasi dan Penilaian</div>
						<div class="unduh-semua">Unduh Semua</div>
					</div>
				</div>
				<div class="text-button-parent">
					<!-- Dokumen 1: Penilaian Kegiatan Mengajar -->
					<img oncontextmenu="return false;" class="iconsoliddocument-text3" alt="Icon Dokumen"
						src="assets/img/icon_doc.svg">
					<div class="text-button4 showIFrameButton showIFrame">
						<div class="keaktifan-siswa">Penilaian Kegiatan Mengajar</div>
					</div>
					<div class="diperbarui-1002-27-container">
						<span class="kode-kelas">Diperbarui </span>
						<span class="mei-20231">[Belum Ada Data]</span>
					</div>
					<div class="download-button4" data-filepath="#" data-filename="PenilaianKegiatanMengajar.docx">
						<!-- Tombol Download -->
						<div class="download-button-child"></div>
						<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
							src="assets/img/icon_download.svg">
						<div class="frame-parent1">
							<div class="unduh-wrapper">
								<div class="unduh">Unduh</div>
							</div>
							<img oncontextmenu="return false;" class="group-child" alt="Polygon"
								src="assets/img/polygon.svg">
						</div>
					</div>
					<div class="share-button-icon4" data-link="#">
						<!-- Tombol Share -->
						<div class="share-button-icon-child"></div>
						<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
							src="assets/img/icon_share.svg">
						<div class="frame-group1">
							<div class="bagikan-wrapper">
								<div class="bagikan copyHover">Bagikan</div>
								<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
							</div>
							<img oncontextmenu="return false;" class="group-item" alt="Polygon"
								src="assets/img/polygon.svg">
						</div>
					</div>
				</div>

				<!-- Dokumen 2: Lembar Pengamatan Siswa -->
				<div class="iconsoliddocument-text-parent">
					<img oncontextmenu="return false;" class="iconsoliddocument-text3" alt="Icon Dokumen"
						src="assets/img/icon_doc.svg">
					<div class="text-button4 showIFrameButton showIFrame">
						<div class="keaktifan-siswa">Lembar Pengamatan Siswa</div>
					</div>
					<div class="diperbarui-1040-27-container">
						<span class="kode-kelas">Diperbarui </span>
						<span class="mei-20231">[Belum Ada Data]</span>
					</div>
					<div class="download-button4" data-filepath="#" data-filename="LembarPengamatanSiswa.docx">
						<!-- Tombol Download -->
						<div class="download-button-child"></div>
						<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
							src="assets/img/icon_download.svg">
						<div class="frame-parent1">
							<div class="unduh-wrapper">
								<div class="unduh">Unduh</div>
							</div>
							<img oncontextmenu="return false;" class="group-child" alt="Polygon"
								src="assets/img/polygon.svg">
						</div>
					</div>
					<div class="share-button-icon4" data-link="#">
						<!-- Tombol Share -->
						<div class="share-button-icon-child"></div>
						<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
							src="assets/img/icon_share.svg">
						<div class="frame-group1">
							<div class="bagikan-wrapper">
								<div class="bagikan copyHover">Bagikan</div>
								<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
							</div>
							<img oncontextmenu="return false;" class="group-item" alt="Polygon"
								src="assets/img/polygon.svg">
						</div>
					</div>
				</div>

				<!-- Dokumen 3: Catatan Aktivitas Siswa -->
				<div class="iconsoliddocument-text-parent">
					<img oncontextmenu="return false;" class="iconsoliddocument-text3" alt="Icon Dokumen"
						src="assets/img/icon_doc.svg">
					<div class="text-button4 showIFrameButton showIFrame">
						<div class="keaktifan-siswa">Catatan Aktivitas Siswa</div>
					</div>
					<div class="diperbarui-1040-27-container">
						<span class="kode-kelas">Diperbarui </span>
						<span class="mei-20231">[Belum Ada Data]</span>
					</div>
					<div class="download-button4" data-filepath="#" data-filename="CatatanAktivitasSiswa.docx">
						<!-- Tombol Download -->
						<div class="download-button-child"></div>
						<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
							src="assets/img/icon_download.svg">
						<div class="frame-parent1">
							<div class="unduh-wrapper">
								<div class="unduh">Unduh</div>
							</div>
							<img oncontextmenu="return false;" class="group-child" alt="Polygon"
								src="assets/img/polygon.svg">
						</div>
					</div>
					<div class="share-button-icon4" data-link="#">
						<!-- Tombol Share -->
						<div class="share-button-icon-child"></div>
						<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
							src="assets/img/icon_share.svg">
						<div class="frame-group1">
							<div class="bagikan-wrapper">
								<div class="bagikan copyHover">Bagikan</div>
								<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
							</div>
							<img oncontextmenu="return false;" class="group-item" alt="Polygon"
								src="assets/img/polygon.svg">
						</div>
					</div>
				</div>
			</div>

			<!-- Daftar Catatan -->
			<div class="daftar-catatan">
				<div class="daftar-catatan-parent">
					<div class="daftar-catatan1">Daftar Catatan</div>
					<!-- Daftar catatan akan diisi oleh JavaScript -->
				</div>
				<div class="tipe-kegiatan-parent">
					<div class="tipe-kegiatan">Tipe Kegiatan</div>
					<div class="kegiatan-siswa">
						<div class="siswa-izin-bertanya siswa-izin-bertanya1"></div>
					</div>
				</div>
				<div class="detail-catatan-parent">
					<div class="detail-catatan">Detail Catatan</div>
					<div class="detail-catatan1">
						<div class="siswa-izin-bertanya siswa-izin-bertanya1"></div>
					</div>
				</div>
			</div>

			<!-- Rekaman Suara -->
			<div class="rekaman">
				<div class="rekaman-icon-parent">
					<img oncontextmenu="return false;" class="rekaman-icon" alt="" src="assets/img/icon_record.svg">
					<div class="rekaman-suara">Rekaman Suara</div>
				</div>
				<div class="record">
					<!-- Wave audio -->
					<div class="record-child audioWave"></div>
					<!-- Progress bar yang dapat dikontrol -->
					<input type="range" class="record-item audioPosition" min="0" max="100" value="0" step="0.01">
					<!-- Tombol play/pause -->
					<img oncontextmenu="return false;" class="iconsolidplay playAudio" alt="Play"
						src="assets/img/icon_play.svg">
					<!-- Pewaktu audio -->
					<div class="div6 audioTime">
						<span class="currentTime"></span>
						<span class="span"></span>
						<span class="span duration"></span>
					</div>
				</div>
				<!-- Tombol download audio -->
				<div class="download-button3" data-filepath="#" data-filename="RekamanSuara.mp3">
					<div class="download-button-child"></div>
					<img oncontextmenu="return false;" class="iconsoliddownload" alt="Download"
						src="assets/img/icon_download.svg">
					<div class="frame-parent1">
						<div class="unduh-wrapper">
							<div class="unduh">Unduh</div>
						</div>
						<img oncontextmenu="return false;" class="group-child" alt="Polygon"
							src="assets/img/polygon.svg">
					</div>
				</div>
				<!-- Tombol share audio -->
				<div class="share-button-icon3" data-link="#">
					<div class="share-button-icon-child"></div>
					<img oncontextmenu="return false;" class="iconsolidshare" alt="Share"
						src="assets/img/icon_share.svg">
					<div class="frame-group1">
						<div class="bagikan-wrapper">
							<div class="bagikan copyHover">Bagikan</div>
							<div class="bagikan copySuccess">Tautan Berhasil Disalin</div>
						</div>
						<img oncontextmenu="return false;" class="group-item" alt="Polygon"
							src="assets/img/polygon.svg">
					</div>
				</div>
			</div>

			<!-- Dokumentasi -->
			<div class="dokumentasi">
				<div class="rekaman-suara">Dokumentasi</div>
				<div class="images">
					<!-- Gambar dokumentasi akan diisi oleh JavaScript -->
				</div>
			</div>
		</div>
	</div>

	<div class="text-button6"></div>

	<!-- Modal untuk Zoomed Image -->
	<div class="modal-overlay" id="modalOverlay"></div>
	<div class="modal-content" id="modalContent">
		<button class="close-button" id="closeButton">×</button>
		<img oncontextmenu="return false;" src="" alt="Zoomed Image" id="modalImage" tabindex="0">
	</div>

	<!-- Tambahkan overlay dan iframe untuk preview -->
	<div id="iframeOverlay" class="iframe-overlay" style="display: none;"></div>
	<div class="iframe-container" style="display: none;" tabindex="0">
		<button class="close-iframe" id="closeIFrame">×</button>
		<iframe id="iframePreview" src="" frameborder="0"></iframe>
	</div>
</body>

<script>
	// Menampilkan pesan flashdata (sukses atau error) menggunakan SweetAlert2
	document.addEventListener("DOMContentLoaded", function() {
		var flashdata = document.getElementById('flashdata');
		var success = flashdata.getAttribute('data-success');
		var error = flashdata.getAttribute('data-error');

		if (success) {
			Swal.fire({
				icon: 'success',
				title: 'Berhasil',
				text: success,
				confirmButtonText: 'OK',
				confirmButtonColor: '#2563EB'
			});
		}

		if (error) {
			Swal.fire({
				icon: 'error',
				title: 'Terjadi Kesalahan',
				text: error,
				confirmButtonText: 'OK',
				confirmButtonColor: '#2563EB'
			});
		}
	});

	// Mengirim data nomor siswa, observer, dan catatan dari PHP ke JavaScript
	const nomorSiswaData = <?= json_encode($nomor_siswa); ?>;
	const observersData = <?= json_encode($observers); ?>;

	document.addEventListener('DOMContentLoaded', function() {
		// Render List Observer
		const listObserver = document.querySelector('.list-observer');
		listObserver.innerHTML = ''; // Bersihkan konten sebelumnya
		let firstActiveObserver = null;

		observersData.forEach((observer, index) => {
			// Mengabaikan observer yang merupakan guru model atau pemilik kelas
			if (observer.observer_user_id == <?= $user->user_id; ?>) {
				return; // Lewati observer yang merupakan guru model
			}

			const observerDiv = document.createElement('div');
			observerDiv.classList.add('item-observer');
			observerDiv.setAttribute('data-user-id', observer.observer_user_id); // Tambahkan atribut data-user-id
			if (!firstActiveObserver) {
				firstActiveObserver = observer;
				observerDiv.classList.add('active'); // Observer pertama aktif saat load
			} else {
				observerDiv.classList.add('inactive');
			}
			observerDiv.innerHTML = `
                <div class="nama-observer">${observer.full_name}</div>
                <div class="observeremail">${observer.email_address}</div>
                <img oncontextmenu="return false;" class="profile-01-icon" alt="${observer.full_name}" src="${observer.src_profile_photo}">
            `;
			listObserver.appendChild(observerDiv);
		});

		// Jika tidak ada observer lain, sembunyikan section observer
		if (!firstActiveObserver) {
			document.querySelector('.observer').style.display = 'none';
			return;
		}

		// Menampilkan nama observer aktif saat load
		document.querySelector('.bagas-nugroho').textContent = firstActiveObserver.full_name;

		// Update data untuk observer pertama
		updateObserverData(firstActiveObserver);

		// Event Listener untuk Observer
		listObserver.addEventListener('click', function(e) {
			const item = e.target.closest('.item-observer');
			if (item && item.classList.contains('inactive')) {
				// Menghapus kelas aktif dari semua observer
				document.querySelectorAll('.item-observer').forEach(obs => {
					obs.classList.remove('active');
					obs.classList.add('inactive');
				});
				// Menambahkan kelas aktif pada yang diklik
				item.classList.remove('inactive');
				item.classList.add('active');

				// Tampilkan data observer yang aktif
				const observerId = item.getAttribute('data-user-id');
				const selectedObserver = observersData.find(obs => obs.observer_user_id == observerId);

				// Update Nama Observer
				document.querySelector('.bagas-nugroho').textContent = selectedObserver.full_name;

				// Update data untuk observer yang dipilih
				updateObserverData(selectedObserver);
			}
		});

		/**
		 * Fungsi untuk mengupdate data berdasarkan observer yang dipilih.
		 * @param {Object} selectedObserver - Objek observer yang dipilih.
		 */
		function updateObserverData(selectedObserver) {
			// Update Nomor Siswa yang Diamati
			const nomorSiswaParent = document.querySelector('.nomor-siswa-parent');
			nomorSiswaParent.innerHTML = '';
			if (selectedObserver.observed_students && Array.isArray(selectedObserver.observed_students) && selectedObserver.observed_students.length > 0) {
				selectedObserver.observed_students.forEach(nomor => {
					const nomorDiv = document.createElement('div');
					nomorDiv.classList.add('nomor-siswa-item');
					nomorDiv.textContent = nomor.toString().padStart(2, '0'); // Pastikan format 2 digit
					nomorSiswaParent.appendChild(nomorDiv);
				});
			} else {
				const nomorDiv = document.createElement('div');
				nomorDiv.classList.add('nomor-siswa-item');
				nomorDiv.textContent = 'Tidak ada data siswa';
				nomorSiswaParent.appendChild(nomorDiv);
			}

			// Update Dokumen Observasi dan Penilaian
			updateObservationDocuments(selectedObserver);

			// Update Daftar Catatan
			renderDaftarCatatan(selectedObserver.special_notes);

			// Update Rekaman Suara
			updateAudioRecording(selectedObserver);

			// Update Dokumentasi
			renderDocumentation(selectedObserver.documentation_files);
		}

		/**
		 * Fungsi untuk mengupdate dokumen observasi dan penilaian.
		 * @param {Object} observer - Objek observer.
		 */
		function updateObservationDocuments(observer) {
			const documentTypes = [{
					formType: 'Penilaian Kegiatan Mengajar',
					filename: 'PenilaianKegiatanMengajar.docx',
					shareLink: observer.penilaian_share_link,
					previewLink: observer.penilaian_preview_link,
					updatedAt: observer.penilaian_updated_at
				},
				{
					formType: 'Lembar Pengamatan Siswa',
					filename: 'LembarPengamatanSiswa.docx',
					shareLink: observer.pengamatan_share_link,
					previewLink: observer.pengamatan_preview_link,
					updatedAt: observer.pengamatan_updated_at
				},
				{
					formType: 'Catatan Aktivitas Siswa',
					filename: 'CatatanAktivitasSiswa.docx',
					shareLink: observer.catatan_share_link,
					previewLink: observer.catatan_preview_link,
					updatedAt: observer.catatan_updated_at
				}
			];

			const dokumenObservasi = document.querySelector('.dokumen-observasi');
			const textButtonParent = dokumenObservasi.querySelector('.text-button-parent');
			const iconsoliddocumentTextParents = dokumenObservasi.querySelectorAll('.iconsoliddocument-text-parent');

			// Update Dokumen 1
			const doc1 = textButtonParent;
			updateDocumentElement(doc1, documentTypes[0]);

			// Update Dokumen 2
			const doc2 = iconsoliddocumentTextParents[0];
			updateDocumentElement(doc2, documentTypes[1]);

			// Update Dokumen 3
			const doc3 = iconsoliddocumentTextParents[1];
			updateDocumentElement(doc3, documentTypes[2]);
		}

		/**
		 * Fungsi untuk mengupdate elemen dokumen.
		 * @param {HTMLElement} element - Elemen dokumen.
		 * @param {Object} data - Data dokumen.
		 */
		function updateDocumentElement(element, data) {
			const previewButton = element.querySelector('.showIFrameButton');
			const updatedAtElement = element.querySelector('.mei-20231');
			const downloadButton = element.querySelector('.download-button4');
			const shareButton = element.querySelector('.share-button-icon4');

			// Update preview link jika tersedia
			if (data.previewLink && data.previewLink !== '#') {
				previewButton.setAttribute('data-preview-link', data.previewLink);
				previewButton.style.cursor = 'pointer';
			} else {
				previewButton.setAttribute('data-preview-link', '#');
				previewButton.style.cursor = 'not-allowed';
			}

			// Update teks tanggal pembaruan
			updatedAtElement.textContent = data.updatedAt ? formatDateTime(data.updatedAt) : '[Belum Ada Data]';

			// Update tombol download
			if (data.shareLink && data.shareLink !== '#') {
				downloadButton.setAttribute('data-filepath', data.shareLink);
				downloadButton.setAttribute('data-filename', data.filename);
				downloadButton.style.display = 'block';
			} else {
				downloadButton.style.display = 'none';
			}

			// Update tombol share
			if (data.shareLink && data.shareLink !== '#') {
				shareButton.setAttribute('data-link', data.shareLink);
				shareButton.style.display = 'block';
			} else {
				shareButton.style.display = 'none';
			}
		}

		/**
		 * Fungsi untuk memformat tanggal dan waktu.
		 * @param {string} dateTime - Tanggal dan waktu dalam format ISO.
		 * @returns {string} - Tanggal dan waktu terformat.
		 */
		function formatDateTime(dateTime) {
			const date = new Date(dateTime);
			const options = {
				day: '2-digit',
				month: 'short',
				year: 'numeric',
				hour: '2-digit',
				minute: '2-digit',
				hour12: false
			};
			return date.toLocaleString('id-ID', options).replace(/\./g, ':');
		}

		/**
		 * Fungsi untuk merender daftar catatan.
		 * @param {Array} catatanData - Array catatan khusus.
		 */
		function renderDaftarCatatan(catatanData) {
			const daftarParent = document.querySelector('.daftar-catatan-parent');
			const kegiatanSiswa = document.querySelector('.kegiatan-siswa .siswa-izin-bertanya');
			const detailCatatan = document.querySelector('.detail-catatan1 .siswa-izin-bertanya');

			daftarParent.innerHTML = ''; // Membersihkan konten sebelumnya

			if (!Array.isArray(catatanData) || catatanData.length === 0) {
				// Jika special_notes kosong atau bukan array, tambahkan data dummy
				const listItem = document.createElement('div');
				listItem.className = 'list-item-catatan-01'; // Aktif secara default
				listItem.innerHTML = `
                    <div class="keaktifan-siswa">
                        <ol class="siswa-bertanya">
                            <li>1. [Belum Ada Catatan]</li>
                        </ol>
                    </div>
                `;
				listItem.dataset.id = 'dummy';
				daftarParent.appendChild(listItem);

				// Update tipe kegiatan dan detail catatan dengan data dummy
				kegiatanSiswa.textContent = '[Belum Ada Catatan]';
				detailCatatan.textContent = '[Belum Ada Catatan]';
				return;
			}

			catatanData.forEach((catatan, index) => {
				const isActive = index === 0; // Item pertama aktif secara default
				const listItem = document.createElement('div');
				listItem.className = isActive ? 'list-item-catatan-01' : 'list-item-catatan-011';
				listItem.innerHTML = `
                    <div class="${isActive ? 'keaktifan-siswa' : 'siswa-izin-bertanya'}">
                        <ol class="siswa-bertanya">
                            <li>${(index + 1).toString()}. ${catatan.activity_type || '[Belum Ada Catatan]'}</li>
                        </ol>
                    </div>
                `;
				listItem.dataset.id = catatan.note_id || 'dummy';
				daftarParent.appendChild(listItem);

				// Event listener untuk setiap item
				listItem.addEventListener('click', () => {
					// Update kelas aktif/tidak aktif
					daftarParent.querySelectorAll('.list-item-catatan-01, .list-item-catatan-011').forEach(item => {
						item.className = 'list-item-catatan-011';
						item.querySelector('.siswa-izin-bertanya, .keaktifan-siswa').className = 'siswa-izin-bertanya';
					});
					listItem.className = 'list-item-catatan-01';
					listItem.querySelector('.siswa-izin-bertanya, .keaktifan-siswa').className = 'keaktifan-siswa';

					// Update tipe kegiatan dan detail catatan
					kegiatanSiswa.textContent = catatan.activity_type || '[Belum Ada Catatan]';
					detailCatatan.textContent = catatan.note_details || '[Belum Ada Catatan]';
				});
			});

			// Set item pertama sebagai aktif
			if (catatanData.length > 0) {
				const firstCatatan = catatanData[0];
				kegiatanSiswa.textContent = firstCatatan.activity_type || '[Belum Ada Catatan]';
				detailCatatan.textContent = firstCatatan.note_details || '[Belum Ada Catatan]';
			} else {
				kegiatanSiswa.textContent = 'Tidak ada catatan.';
				detailCatatan.textContent = 'Tidak ada detail catatan.';
			}
		}

		/**
		 * Fungsi untuk merender dokumentasi.
		 * @param {Array} documentationFiles - Array dokumentasi.
		 */
		function renderDocumentation(documentationFiles) {
			const imagesContainer = document.querySelector('.dokumentasi .images');
			imagesContainer.innerHTML = ''; // Bersihkan konten sebelumnya

			if (Array.isArray(documentationFiles) && documentationFiles.length > 0) {
				documentationFiles.forEach(doc => {
					if (doc.file_src && doc.file_src.trim() !== '') { // Pastikan ada sumber file
						const imageDiv = document.createElement('div');
						imageDiv.classList.add('image-container');
						imageDiv.innerHTML = `
                            <img oncontextmenu="return false;" class="thumbnail" src="${doc.file_src}" alt="${doc.file_name || 'Dokumentasi'}">
                        `;
						imagesContainer.appendChild(imageDiv);
					}
				});

				// Jika setelah filtering tidak ada gambar, tampilkan pesan
				if (imagesContainer.children.length === 0) {
					imagesContainer.innerHTML = '<div class="no-data">[Belum Ada Dokumentasi]</div>';
				}
			} else {
				imagesContainer.innerHTML = '<div class="no-data">[Belum Ada Dokumentasi]</div>';
			}
		}

		/**
		 * Fungsi untuk mengupdate rekaman suara.
		 * @param {Object} observer - Objek observer.
		 */
		function updateAudioRecording(observer) {
			// Mengupdate elemen audio dengan kelas unik
			const rekamanDiv = document.querySelector('.rekaman');
			const audioWave = rekamanDiv.querySelector('.audioWave');
			const playButtons = rekamanDiv.querySelectorAll('.playAudio');
			const audioPositions = rekamanDiv.querySelectorAll('.audioPosition');
			const currentTimeEls = rekamanDiv.querySelectorAll('.currentTime');
			const durationEls = rekamanDiv.querySelectorAll('.duration');
			const downloadButton = rekamanDiv.querySelector('.download-button3');
			const shareButton = rekamanDiv.querySelector('.share-button-icon3');
			const spanEl = rekamanDiv.querySelector('.span');

			// Cek apakah observer memiliki data audio
			if (!observer.audio_src || observer.audio_src.trim() === '') {
				// Jika ada WaveSurfer instance, stop playback dan destroy
				if (rekamanDiv.waveSurferInstance) {
					rekamanDiv.waveSurferInstance.stop(); // Hentikan playback
					rekamanDiv.waveSurferInstance.destroy(); // Hancurkan instance
					rekamanDiv.waveSurferInstance = null;
				}

				// Observer tidak memiliki data audio
				currentTimeEls.forEach(el => {
					el.textContent = '[Belum Ada Rekaman]';
					el.style.color = '#94a3b8';
				});
				spanEl.textContent = ' ';
				durationEls.forEach(el => el.textContent = ' ');

				// Sembunyikan elemen audio lainnya
				rekamanDiv.querySelector('.audioPosition').style.display = 'none';
				rekamanDiv.querySelector('.audioWave').style.display = 'none';
				rekamanDiv.querySelector('.playAudio').style.display = 'none';
				rekamanDiv.querySelector('.download-button3').style.display = 'none';
				rekamanDiv.querySelector('.share-button-icon3').style.display = 'none';
				return;
			} else {
				// Observer memiliki data audio, tampilkan elemen audio
				currentTimeEls.forEach(el => el.style.color = '#0f172a');
				spanEl.textContent = ' / ';
				rekamanDiv.querySelector('.audioPosition').style.display = 'block';
				rekamanDiv.querySelector('.audioWave').style.display = 'block';
				rekamanDiv.querySelector('.playAudio').style.display = 'block';
				rekamanDiv.querySelector('.download-button3').style.display = 'block';
				rekamanDiv.querySelector('.share-button-icon3').style.display = 'block';
			}

			// Pastikan hanya satu audio player di dalam rekaman div
			if (playButtons.length === 0 || audioPositions.length === 0 || currentTimeEls.length === 0 || durationEls.length === 0) {
				console.error('Elemen audio tidak ditemukan.');
				return;
			}

			// Menghapus instance WaveSurfer sebelumnya jika ada
			if (rekamanDiv.waveSurferInstance) {
				rekamanDiv.waveSurferInstance.stop(); // Hentikan playback
				rekamanDiv.waveSurferInstance.destroy(); // Hancurkan instance
				rekamanDiv.waveSurferInstance = null;
			}

			// Reset nilai slider dan waktu
			audioPositions.forEach(slider => {
				slider.value = 0;
				slider.max = 100; // Reset max sementara
				slider.style.background = 'linear-gradient(to right, #3b82f6 0%, #d9d9d9 0%)';
			});
			currentTimeEls.forEach(el => el.textContent = '00:00:00');
			durationEls.forEach(el => el.textContent = '00:00:00');

			// Reset tombol play/pause ke ikon "play"
			playButtons.forEach(button => {
				button.src = 'assets/img/icon_play.svg';
			});

			// Inisialisasi WaveSurfer baru untuk rekaman ini
			const wavesurfer = WaveSurfer.create({
				container: audioWave,
				waveColor: '#CBD5E1',
				progressColor: '#3b82f6',
				cursorColor: 'transparent',
				barWidth: 6,
				barGap: 6,
				barRadius: 10,
				responsive: true,
				height: 66,
				interact: false,
				normalize: true,
				scrollParent: true,
				minPxPerSec: 60,
				autoCenter: true,
				hideScrollbar: true,
				partialRender: true,
				barMinHeight: 10, // Tinggi minimal bar
				barMaxHeight: 66, // Tinggi maksimal bar
			});

			// Menyimpan instance WaveSurfer ke dalam rekamanDiv
			rekamanDiv.waveSurferInstance = wavesurfer;

			// Memuat file audio
			wavesurfer.load(observer.audio_src);

			// Variabel untuk menyimpan startTime
			let startTime = null;

			/**
			 * Fungsi untuk memformat objek Date ke HH:MM:SS.
			 * @param {Date} date - Objek Date yang akan diformat.
			 * @returns {string} - Waktu dalam format HH:MM:SS.
			 */
			function formatDateTime(date) {
				const hours = date.getHours().toString().padStart(2, '0');
				const minutes = date.getMinutes().toString().padStart(2, '0');
				const seconds = date.getSeconds().toString().padStart(2, '0');
				return `${hours}:${minutes}:${seconds}`;
			}

			/**
			 * Fungsi untuk memformat waktu ke HH:MM:SS.
			 * @param {number} totalSeconds - Total detik yang akan diformat.
			 * @returns {string} - Waktu dalam format HH:MM:SS.
			 */
			function formatTime(totalSeconds) {
				const sec_num = parseInt(totalSeconds, 10);
				let hours = Math.floor(sec_num / 3600);
				let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
				let seconds = sec_num - (hours * 3600) - (minutes * 60);

				if (hours < 10) {
					hours = "0" + hours;
				}
				if (minutes < 10) {
					minutes = "0" + minutes;
				}
				if (seconds < 10) {
					seconds = "0" + seconds;
				}
				return hours + ':' + minutes + ':' + seconds;
			}

			/**
			 * Fungsi untuk mengambil Last-Modified dari header HTTP.
			 * @param {string} url - URL yang akan diambil header-nya.
			 * @returns {Promise<string|null>} - Tanggal Last-Modified atau null jika gagal.
			 */
			async function getLastModified(url) {
				try {
					const response = await fetch(url, {
						method: 'HEAD'
					});
					if (response.ok) {
						const lastModified = response.headers.get('Last-Modified');
						return lastModified;
					} else {
						throw new Error('Tidak dapat mengambil header Last-Modified.');
					}
				} catch (error) {
					console.error('Error:', error);
					return null;
				}
			}

			// Mengupdate tanggal dan waktu last modified
			wavesurfer.on('ready', async function() {
				const duration = wavesurfer.getDuration(); // Durasi audio dalam detik

				// Mendapatkan Last-Modified dari header HTTP
				const lastModifiedHeader = await getLastModified(observer.audio_src);
				if (lastModifiedHeader) {
					const lastModifiedDate = new Date(lastModifiedHeader);
					if (isNaN(lastModifiedDate)) {
						console.error('Tanggal Last-Modified tidak valid.');
						durationEls.forEach(el => el.textContent = '[Belum Ada Rekaman]');
						currentTimeEls.forEach(el => el.textContent = '[Belum Ada Rekaman]');
						return;
					}

					// Menghitung estimasi waktu audio dimulai
					const startTimeDate = new Date(lastModifiedDate.getTime() - duration * 1000);
					startTime = startTimeDate;

					// Memformat waktu ke HH:MM:SS
					const formattedStartTime = formatDateTime(startTimeDate);
					const formattedLastModifiedTime = formatDateTime(lastModifiedDate);

					// Mengatur teks ke elemen HTML
					currentTimeEls.forEach(el => el.textContent = formattedStartTime);
					durationEls.forEach(el => el.textContent = formattedLastModifiedTime);

					// Mengatur max value dari slider
					audioPositions.forEach(slider => {
						slider.max = duration;
						slider.value = 0; // Reset nilai slider saat audio siap
						slider.style.background = 'linear-gradient(to right, #3b82f6 0%, #d9d9d9 0%)';
					});
				} else {
					durationEls.forEach(el => el.textContent = '[Belum Ada Rekaman]');
					currentTimeEls.forEach(el => el.textContent = '[Belum Ada Rekaman]');
				}
			});

			// Event saat tombol play/pause ditekan
			playButtons.forEach((button) => {
				button.addEventListener('click', function() {
					wavesurfer.playPause();
				});
			});

			// Event saat audio mulai diputar
			wavesurfer.on('play', function() {
				playButtons.forEach(button => {
					button.src = 'assets/img/icon_pause.svg';
				});
			});

			// Event saat audio dijeda
			wavesurfer.on('pause', function() {
				playButtons.forEach(button => {
					button.src = 'assets/img/icon_play.svg';
				});
			});

			/**
			 * Fungsi untuk mengupdate background input range.
			 * @param {number} value - Nilai saat ini dari slider.
			 */
			function updateRangeBackground(value) {
				audioPositions.forEach(slider => {
					const percentage = (value / slider.max) * 100;
					slider.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${percentage}%, #d9d9d9 ${percentage}%, #d9d9d9 100%)`;
				});
			}

			// Update waktu dan progress secara realtime
			wavesurfer.on('audioprocess', function() {
				if (!startTime) return; // Pastikan startTime sudah ditetapkan

				const currentPlaybackTime = wavesurfer.getCurrentTime(); // Waktu playback dalam detik
				const currentTimeDisplayDate = new Date(startTime.getTime() + currentPlaybackTime * 1000);

				// Memformat waktu ke HH:MM:SS
				const formattedCurrentTime = formatDateTime(currentTimeDisplayDate);

				// Mengatur teks ke elemen HTML
				currentTimeEls.forEach(el => el.textContent = formattedCurrentTime);

				// Mengatur nilai slider
				audioPositions.forEach(slider => {
					slider.value = currentPlaybackTime;
				});

				// Mengupdate background slider
				updateRangeBackground(currentPlaybackTime);
			});

			// Tambahan: Update currentTime saat seek selesai
			wavesurfer.on('seek', function() {
				if (!startTime) return; // Pastikan startTime sudah ditetapkan

				const currentPlaybackTime = wavesurfer.getCurrentTime(); // Waktu playback dalam detik
				const currentTimeDisplayDate = new Date(startTime.getTime() + currentPlaybackTime * 1000);

				// Memformat waktu ke HH:MM:SS
				const formattedCurrentTime = formatDateTime(currentTimeDisplayDate);

				// Mengatur teks ke elemen HTML
				currentTimeEls.forEach(el => el.textContent = formattedCurrentTime);

				// Mengatur nilai slider
				audioPositions.forEach(slider => {
					slider.value = currentPlaybackTime;
				});

				// Mengupdate background slider
				updateRangeBackground(currentPlaybackTime);
			});

			// Reset ikon play saat audio selesai
			wavesurfer.on('finish', function() {
				playButtons.forEach(button => {
					button.src = 'assets/img/icon_play.svg';
				});
				audioPositions.forEach(slider => {
					slider.value = 0;
				});
				updateRangeBackground(0);

				if (startTime) {
					// Mengatur currentTimeEl kembali ke startTime
					const formattedStartTime = formatDateTime(startTime);
					currentTimeEls.forEach(el => el.textContent = formattedStartTime);
				}
			});

			// Mengubah posisi audio saat progress bar digeser
			audioPositions.forEach((slider) => {
				slider.addEventListener('input', function() {
					const value = parseFloat(slider.value);
					wavesurfer.seekTo(value / wavesurfer.getDuration());

					if (startTime) {
						const currentTimeDisplayDate = new Date(startTime.getTime() + value * 1000);
						const formattedCurrentTime = formatDateTime(currentTimeDisplayDate);
						currentTimeEls.forEach(el => el.textContent = formattedCurrentTime);
					}

					// Mengupdate background slider
					updateRangeBackground(value);
				});
			});

			// Event untuk tombol download audio
			if (downloadButton && observer.audio_share_link && observer.audio_share_link !== '#') {
				downloadButton.setAttribute('data-filepath', observer.audio_share_link);
				downloadButton.setAttribute('data-filename', 'RekamanSuara.' + observer.audio_src.split('.').pop());
				downloadButton.style.display = 'block'; // Pastikan tombol download terlihat

				// Hapus event listener sebelumnya untuk mencegah duplikasi
				const newDownloadButton = downloadButton.cloneNode(true);
				downloadButton.parentNode.replaceChild(newDownloadButton, downloadButton);

				// Menambahkan event listener baru
				newDownloadButton.addEventListener('click', function(event) {
					event.stopPropagation(); // Mencegah event bubbling

					// Menampilkan SweetAlert2 loading modal
					Swal.fire({
						title: 'Memuat...',
						text: 'Sedang memproses pengunduhan.',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});

					var filepath = newDownloadButton.getAttribute('data-filepath');
					var filename = newDownloadButton.getAttribute('data-filename');

					if (filepath && filepath !== '#') {
						// Mengunduh file menggunakan fetch dan blob
						fetch(filepath)
							.then(response => {
								if (!response.ok) {
									throw new Error('Network response was not ok');
								}
								return response.blob();
							})
							.then(blob => {
								Swal.close(); // Menutup loading modal
								var url = window.URL.createObjectURL(blob);
								var a = document.createElement('a');
								a.href = url;
								a.download = filename;
								document.body.appendChild(a);
								a.click();
								a.remove();
								window.URL.revokeObjectURL(url);
							})
							.catch(error => {
								Swal.close(); // Menutup loading modal
								console.error('There was a problem with the fetch operation:', error);
								Swal.fire({
									icon: 'error',
									title: 'Terjadi Kesalahan',
									text: 'File tidak tersedia untuk diunduh.',
									footer: 'Silakan coba lagi.',
									confirmButtonText: 'OK',
									confirmButtonColor: '#2563EB'
								});
							});
					} else {
						Swal.close(); // Menutup loading modal jika filepath tidak valid
						Swal.fire({
							icon: 'error',
							title: 'Terjadi Kesalahan',
							text: 'File tidak tersedia untuk diunduh.',
							footer: 'Silakan coba lagi.',
							confirmButtonText: 'OK',
							confirmButtonColor: '#2563EB'
						});
					}
				});
			} else {
				downloadButton.style.display = 'none'; // Sembunyikan jika tidak ada
			}

			// Event untuk tombol share audio
			if (shareButton && observer.audio_share_link && observer.audio_share_link !== '#') {
				shareButton.setAttribute('data-link', observer.audio_share_link);
				shareButton.style.display = 'block'; // Pastikan tombol share terlihat
				// Hapus event listener sebelumnya untuk mencegah duplikasi
				const newShareButton = shareButton.cloneNode(true);
				shareButton.parentNode.replaceChild(newShareButton, shareButton);

				newShareButton.addEventListener('click', function(event) {
					event.stopPropagation(); // Mencegah event bubbling
					const link = newShareButton.getAttribute('data-link');
					copyToClipboard(link);
					// Menampilkan pesan 'Tautan Berhasil Disalin'
					const copyHover = newShareButton.querySelector('.copyHover');
					const copySuccess = newShareButton.querySelector('.copySuccess');
					if (copyHover && copySuccess) {
						copyHover.style.display = 'none';
						copySuccess.style.display = 'block';
						// Mengembalikan tampilan setelah 2 detik
						setTimeout(function() {
							copySuccess.style.display = 'none';
							copyHover.style.display = 'block';
						}, 2000);
					}
				});
			} else {
				shareButton.style.display = 'none'; // Sembunyikan jika tidak ada
			}

			/**
			 * Fungsi untuk menyalin teks ke clipboard.
			 * @param {string} text - Teks yang akan disalin.
			 */
			function copyToClipboard(text) {
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(text).catch(function(err) {
						console.error('Tidak dapat menyalin teks: ', err);
					});
				} else {
					// Fallback untuk browser yang tidak mendukung
					// Simpan posisi scroll saat ini
					const scrollY = window.scrollY || window.pageYOffset;
					const scrollX = window.scrollX || window.pageXOffset;

					var textarea = document.createElement('textarea');
					textarea.value = text;
					textarea.style.position = 'fixed';
					textarea.style.top = '0';
					textarea.style.left = '0';
					textarea.style.width = '1px';
					textarea.style.height = '1px';
					textarea.style.padding = '0';
					textarea.style.border = 'none';
					textarea.style.outline = 'none';
					textarea.style.boxShadow = 'none';
					textarea.style.background = 'transparent';
					document.body.appendChild(textarea);
					textarea.focus({
						preventScroll: true
					});
					textarea.select();
					try {
						document.execCommand('copy');
					} catch (err) {
						console.error('Fallback: Tidak dapat menyalin', err);
					}
					document.body.removeChild(textarea);

					// Kembalikan posisi scroll
					window.scrollTo(scrollX, scrollY);
				}
			}
		}

		// Menambahkan event listener untuk tombol preview iframe
		document.querySelectorAll('.showIFrameButton').forEach(function(element) {
			element.addEventListener('click', function() {
				var previewLink = element.getAttribute('data-preview-link');
				if (previewLink && previewLink !== '#') {
					var iframeOverlay = document.getElementById('iframeOverlay');
					var iframePreview = document.getElementById('iframePreview');
					var iframeContainer = document.querySelector('.iframe-container');

					iframePreview.src = previewLink;

					// Tampilkan overlay dan iframe dengan animasi
					iframeOverlay.style.display = 'flex';
					iframeOverlay.style.opacity = '1'; // Untuk transisi overlay
					iframeContainer.classList.add('show'); // Tambahkan kelas animasi masuk
					iframeContainer.classList.remove('hide'); // Pastikan kelas keluar dihapus

					// Menyimpan nilai overflow asli sebelum diubah
					originalOverflow = document.body.style.overflow;

					document.body.style.overflow = 'hidden';

					// Atur fokus ke iframeContainer setelah overlay ditampilkan
					setTimeout(function() {
						iframeContainer.focus({
							preventScroll: true
						});
						iframeContainer.scrollIntoView({
							behavior: 'smooth',
							block: 'center',
							inline: 'center'
						});
					}, 500); // Sesuaikan delay sesuai animasi CSS

				} else {
					Swal.fire({
						icon: 'error',
						title: 'Terjadi Kesalahan',
						text: 'File tidak tersedia.',
						confirmButtonText: 'OK',
						confirmButtonColor: '#2563EB'
					});
				}
			});
		});

		// Fungsi untuk menutup iframe
		function closeIframe() {
			var iframeOverlay = document.getElementById('iframeOverlay');
			var iframePreview = document.getElementById('iframePreview');
			var iframeContainer = document.querySelector('.iframe-container');

			// Animasi keluar
			iframeContainer.classList.remove('show');
			iframeContainer.classList.add('hide'); // Tambahkan kelas animasi keluar
			iframeOverlay.style.opacity = '0'; // Untuk transisi overlay


			// Tunggu sampai animasi selesai sebelum benar-benar menyembunyikan
			setTimeout(function() {
				iframeOverlay.style.display = 'none';
				iframePreview.src = ''; // Reset src iframe
				document.body.style.overflow = originalOverflow || '';
			}, 500); // Durasi sama dengan animasi CSS
		}

		// Event listener untuk tombol close iframe
		document.getElementById('closeIFrame').addEventListener('click', function() {
			closeIframe();
		});

		// Event listener untuk klik di luar konten iframe
		document.getElementById('iframeOverlay').addEventListener('click', function(e) {
			if (e.target === this) {
				closeIframe();
			}
		});
	});

	// Fungsi untuk memperbarui tanggal dan waktu secara realtime
	function updateDateTime() {
		const now = new Date();
		const optionsDate = {
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric'
		};
		const optionsTime = {
			hour: '2-digit',
			minute: '2-digit',
			second: '2-digit',
			hour12: false // Menggunakan format 24 jam
		};

		// Format tanggal dan jam dalam bahasa Indonesia
		const dateString = now.toLocaleDateString('id-ID', optionsDate);
		const timeString = now.toLocaleTimeString('id-ID', optionsTime).replace(/\./g, ':'); // Mengganti titik dengan titik dua

		// Mengupdate elemen HTML
		document.getElementById('dateDisplay').innerText = dateString;
		document.getElementById('timeDisplay').innerText = timeString;
	}

	// Memanggil fungsi updateDateTime secara terus-menerus tanpa jeda
	setInterval(updateDateTime, 0);

	// Memastikan waktu saat ini ditampilkan saat memuat halaman
	updateDateTime();

	// Event Listener untuk tombol copy kode kelas
	document.addEventListener('DOMContentLoaded', function() {
		var iconCopy = document.getElementById('iconCopy');
		var copyHover = document.getElementById('copyHover');
		var copySuccess = document.getElementById('copySuccess');
		var kodeKelasTersalinWrapper = document.querySelector('.kode-kelas-tersalin-wrapper');

		var hideTimeout;
		var hideWrapperTimeout;

		// Mendefinisikan kodeKelas di sini
		var kodeKelas = document.querySelector('.f6y8iotrqwmuge7wobny');

		// Pastikan kodeKelas ditemukan
		if (!kodeKelas) {
			console.error('Elemen dengan kelas "f6y8iotrqwmuge7wobny" tidak ditemukan.');
			return;
		}

		// Event saat mouse hover masuk ke ikon copy
		iconCopy.addEventListener('mouseover', function() {
			// Hentikan timeout yang sedang berjalan
			clearTimeout(hideTimeout);
			clearTimeout(hideWrapperTimeout);

			// Mengubah src gambar menjadi hover
			iconCopy.src = 'assets/img/icon_copy_hover_white.svg';

			// Menampilkan wrapper dan mengatur opacity
			kodeKelasTersalinWrapper.style.display = 'block';
			kodeKelasTersalinWrapper.style.opacity = '1';

			// Menampilkan elemen yang sesuai
			if (copySuccess.style.display === 'block') {
				// Jika copySuccess sedang ditampilkan, biarkan tetap tampil
				copyHover.style.display = 'none';
			} else {
				// Tampilkan copyHover
				copyHover.style.display = 'block';
				copySuccess.style.display = 'none';
			}
		});

		// Event saat mouse hover keluar dari ikon copy
		iconCopy.addEventListener('mouseout', function() {
			// Mengembalikan src gambar ke default
			iconCopy.src = 'assets/img/icon_copy_white.svg';

			// Mulai timeout untuk menyembunyikan copyHover/copySuccess dan wrapper setelah 1 detik
			hideTimeout = setTimeout(function() {
				// Mengatur opacity untuk transisi keluar
				kodeKelasTersalinWrapper.style.opacity = '0';

				// Setelah transisi selesai, sembunyikan elemen copyHover, copySuccess, dan wrapper
				hideWrapperTimeout = setTimeout(function() {
					copyHover.style.display = 'none';
					copySuccess.style.display = 'none';
					kodeKelasTersalinWrapper.style.display = 'none';
				}, 250); // Sesuai dengan waktu transisi CSS (0.25s)
			}, 1000); // Delay 1 detik
		});

		// Event saat ikon copy diklik
		iconCopy.addEventListener('click', function() {
			// Menyalin teks ke clipboard
			var textToCopy = kodeKelas.textContent.trim();
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(textToCopy).then(function() {
					// Berhasil menyalin
				}, function(err) {
					console.error('Tidak dapat menyalin teks: ', err);
				});
			} else {
				// Fallback untuk browser yang tidak mendukung
				// Simpan posisi scroll saat ini
				const scrollY = window.scrollY || window.pageYOffset;
				const scrollX = window.scrollX || window.pageXOffset;

				var textarea = document.createElement('textarea');
				textarea.value = textToCopy;
				textarea.style.position = 'fixed';
				textarea.style.top = '0';
				textarea.style.left = '0';
				textarea.style.width = '1px';
				textarea.style.height = '1px';
				textarea.style.padding = '0';
				textarea.style.border = 'none';
				textarea.style.outline = 'none';
				textarea.style.boxShadow = 'none';
				textarea.style.background = 'transparent';
				document.body.appendChild(textarea);
				textarea.focus({
					preventScroll: true
				});
				textarea.select();
				try {
					document.execCommand('copy');
				} catch (err) {
					console.error('Fallback: Tidak dapat menyalin', err);
				}
				document.body.removeChild(textarea);

				// Kembalikan posisi scroll
				window.scrollTo(scrollX, scrollY);
			}

			// Mengembalikan src gambar ke default
			iconCopy.src = 'assets/img/icon_copy_white.svg';

			// Menyembunyikan elemen copyHover
			copyHover.style.display = 'none';

			// Menampilkan elemen copySuccess
			copySuccess.style.display = 'block';

			// Pastikan wrapper terlihat
			kodeKelasTersalinWrapper.style.display = 'block';
			kodeKelasTersalinWrapper.style.opacity = '1';

			// Hentikan timeout yang mungkin sedang berjalan
			clearTimeout(hideTimeout);
			clearTimeout(hideWrapperTimeout);
		});
	});

	document.addEventListener('DOMContentLoaded', function() {
		// Mendapatkan elemen untuk ikon edit
		var iconEdit = document.getElementById('iconEdit');
		var kodeKelasTersalinWrapper1 = document.querySelector('.kode-kelas-tersalin-wrapper1');
		var iconHover1 = document.getElementById('iconHover1');

		// Mendapatkan elemen untuk ikon hapus
		var iconHapus = document.getElementById('iconHapus');
		var kodeKelasTersalinWrapper2 = document.querySelector('.kode-kelas-tersalin-wrapper2');
		var iconHover2 = document.getElementById('iconHover2');

		// Variabel timeout untuk ikon edit
		var hideTimeoutEdit;
		var hideWrapperTimeoutEdit;

		// Variabel timeout untuk ikon hapus
		var hideTimeoutHapus;
		var hideWrapperTimeoutHapus;

		/**
		 * Fungsi untuk menyembunyikan hover ikon hapus.
		 */
		function hideHapusHover() {
			clearTimeout(hideTimeoutHapus);
			clearTimeout(hideWrapperTimeoutHapus);
			iconHapus.src = 'assets/img/icon_delete_class.svg';
			kodeKelasTersalinWrapper2.style.opacity = '0';
			hideWrapperTimeoutHapus = setTimeout(function() {
				kodeKelasTersalinWrapper2.style.display = 'none';
				iconHover2.style.display = 'none';
			}, 250);
		}

		/**
		 * Fungsi untuk menyembunyikan hover ikon edit.
		 */
		function hideEditHover() {
			clearTimeout(hideTimeoutEdit);
			clearTimeout(hideWrapperTimeoutEdit);
			iconEdit.src = 'assets/img/icon_edit.svg';
			kodeKelasTersalinWrapper1.style.opacity = '0';
			hideWrapperTimeoutEdit = setTimeout(function() {
				kodeKelasTersalinWrapper1.style.display = 'none';
				iconHover1.style.display = 'none';
			}, 250);
		}

		// Event saat mouse hover masuk ke ikon edit
		iconEdit.addEventListener('mouseover', function() {
			// Sembunyikan hover ikon hapus jika sedang ditampilkan
			hideHapusHover();

			// Hentikan timeout yang mungkin masih berjalan untuk ikon edit
			clearTimeout(hideTimeoutEdit);
			clearTimeout(hideWrapperTimeoutEdit);

			// Ubah sumber gambar menjadi gambar hover
			iconEdit.src = 'assets/img/icon_edit_hover.svg';

			// Tampilkan wrapper dan atur opacity
			kodeKelasTersalinWrapper1.style.display = 'block';
			kodeKelasTersalinWrapper1.style.opacity = '1';

			// Tampilkan teks hover
			iconHover1.style.display = 'block';
		});

		// Event saat mouse hover keluar dari ikon edit
		iconEdit.addEventListener('mouseout', function() {
			// Kembalikan sumber gambar ke gambar default
			iconEdit.src = 'assets/img/icon_edit.svg';

			// Mulai timeout untuk menyembunyikan wrapper dan teks setelah 1 detik
			hideTimeoutEdit = setTimeout(function() {
				kodeKelasTersalinWrapper1.style.opacity = '0';

				// Setelah transisi opacity selesai, sembunyikan wrapper dan teks
				hideWrapperTimeoutEdit = setTimeout(function() {
					kodeKelasTersalinWrapper1.style.display = 'none';
					iconHover1.style.display = 'none';
				}, 250); // Sesuaikan dengan durasi transisi CSS
			}, 1000);
		});

		// Event saat ikon edit diklik
		iconEdit.addEventListener('click', function() {
			var idKelas = iconEdit.getAttribute('data-idkelas');
			if (idKelas) {
				window.location.href = "pageEditKelas/" + idKelas;
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Terjadi Kesalahan',
					text: 'ID Kelas tidak ditemukan.',
					confirmButtonText: 'OK',
					confirmButtonColor: '#2563EB'
				});
			}
		});

		// Event saat mouse hover masuk ke ikon hapus
		iconHapus.addEventListener('mouseover', function() {
			// Sembunyikan hover ikon edit jika sedang ditampilkan
			hideEditHover();

			// Hentikan timeout yang mungkin masih berjalan untuk ikon hapus
			clearTimeout(hideTimeoutHapus);
			clearTimeout(hideWrapperTimeoutHapus);

			// Ubah sumber gambar menjadi gambar hover
			iconHapus.src = 'assets/img/icon_delete_class_hover.svg';

			// Tampilkan wrapper dan atur opacity
			kodeKelasTersalinWrapper2.style.display = 'block';
			kodeKelasTersalinWrapper2.style.opacity = '1';

			// Tampilkan teks hover
			iconHover2.style.display = 'block';
		});

		// Event saat mouse hover keluar dari ikon hapus
		iconHapus.addEventListener('mouseout', function() {
			// Kembalikan sumber gambar ke gambar default
			iconHapus.src = 'assets/img/icon_delete_class.svg';

			// Mulai timeout untuk menyembunyikan wrapper dan teks setelah 1 detik
			hideTimeoutHapus = setTimeout(function() {
				kodeKelasTersalinWrapper2.style.opacity = '0';

				// Setelah transisi opacity selesai, sembunyikan wrapper dan teks
				hideWrapperTimeoutHapus = setTimeout(function() {
					kodeKelasTersalinWrapper2.style.display = 'none';
					iconHover2.style.display = 'none';
				}, 250); // Sesuaikan dengan durasi transisi CSS
			}, 1000);
		});

		// Event saat ikon hapus diklik
		iconHapus.addEventListener('click', function() {
			var idKelas = iconHapus.getAttribute('data-idkelas');
			if (idKelas) {
				Swal.fire({
					title: 'Apakah Anda yakin?',
					text: "Kelas yang dihapus tidak dapat dikembalikan!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#2563EB',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, hapus!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						window.location.href = "hapusKelasGuruModel/" + idKelas;
					}
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Terjadi Kesalahan',
					text: 'ID Kelas tidak ditemukan.',
					confirmButtonText: 'OK',
					confirmButtonColor: '#2563EB'
				});
			}
		});
	});

	// Event Listener untuk tombol download dan share
	document.addEventListener('DOMContentLoaded', function() {
		// Menangani klik tombol download
		var downloadButtons = document.querySelectorAll('[class^="download-button"]:not(.download-button3)');
		downloadButtons.forEach(function(button) {
			button.addEventListener('click', function(event) {
				event.preventDefault(); // Mencegah perilaku default

				// Menampilkan SweetAlert2 loading modal
				Swal.fire({
					title: 'Memuat...',
					text: 'Sedang memproses pengunduhan.',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				var filepath = button.getAttribute('data-filepath');
				var filename = button.getAttribute('data-filename');

				if (filepath && filepath !== '#') {
					// Mengunduh file menggunakan fetch dan blob
					fetch(filepath)
						.then(response => {
							if (!response.ok) {
								throw new Error('Network response was not ok');
							}
							return response.blob();
						})
						.then(blob => {
							Swal.close(); // Menutup loading modal
							var url = window.URL.createObjectURL(blob);
							var a = document.createElement('a');
							a.href = url;
							a.download = filename;
							document.body.appendChild(a);
							a.click();
							a.remove();
							window.URL.revokeObjectURL(url);
						})
						.catch(error => {
							Swal.close(); // Menutup loading modal
							console.error('There was a problem with the fetch operation:', error);
							Swal.fire({
								icon: 'error',
								title: 'Terjadi Kesalahan',
								text: 'File tidak tersedia untuk diunduh.',
								footer: 'Silakan coba lagi.',
								confirmButtonText: 'OK',
								confirmButtonColor: '#2563EB'
							});
						});
				} else {
					Swal.close(); // Menutup loading modal jika filepath tidak valid
					Swal.fire({
						icon: 'error',
						title: 'Terjadi Kesalahan',
						text: 'File tidak tersedia untuk diunduh.',
						footer: 'Silakan coba lagi.',
						confirmButtonText: 'OK',
						confirmButtonColor: '#2563EB'
					});
				}
			});
		});

		// Menangani klik tombol share
		var shareButtons = document.querySelectorAll('[class^="share-button-icon"]');
		shareButtons.forEach(function(button) {
			button.addEventListener('click', function(event) {
				var link = button.getAttribute('data-link');
				if (link && link !== '#') {
					copyToClipboard(link);
					// Menampilkan pesan 'Tautan Berhasil Disalin'
					var copyHover = button.querySelector('.copyHover');
					var copySuccess = button.querySelector('.copySuccess');
					if (copyHover && copySuccess) {
						copyHover.style.display = 'none';
						copySuccess.style.display = 'block';
						// Mengembalikan tampilan setelah 2 detik
						setTimeout(function() {
							copySuccess.style.display = 'none';
							copyHover.style.display = 'block';
						}, 2000);
					}
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Terjadi Kesalahan',
						text: 'Tautan tidak tersedia untuk dibagikan.',
						footer: 'Silakan coba lagi.',
						confirmButtonText: 'OK',
						confirmButtonColor: '#2563EB'
					});
				}
			});
		});

		// Menangani klik tombol unduh semua
		var unduhSemuaButton = document.querySelector('.unduh-semua');
		if (unduhSemuaButton) {
			unduhSemuaButton.addEventListener('click', function() {
				// Menampilkan SweetAlert2 loading modal
				Swal.fire({
					title: 'Memuat...',
					text: 'Sedang memproses pengunduhan.',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				// Mendapatkan observer IDs yang aktif
				var activeObserver = document.querySelector('.item-observer.active');
				if (!activeObserver) {
					Swal.close(); // Menutup loading modal
					Swal.fire({
						icon: 'error',
						title: 'Terjadi Kesalahan',
						text: 'Tidak ada observer aktif.',
						confirmButtonText: 'OK',
						confirmButtonColor: '#2563EB'
					});
					return;
				}

				// Mendapatkan ID observer
				var observerId = activeObserver.getAttribute('data-user-id');

				// Mendapatkan ID kelas
				var classId = '<?= $encrypted_idKelas; ?>';
				var downloadUrl = 'downloadAllFormsZip/' + classId + '/' + observerId;

				// Mengunduh file ZIP menggunakan fetch dan blob
				fetch(downloadUrl)
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.blob();
					})
					.then(blob => {
						Swal.close(); // Menutup loading modal
						var url = window.URL.createObjectURL(blob);
						var a = document.createElement('a');
						a.href = url;
						a.download = 'AllForms.zip'; // Ganti nama file sesuai kebutuhan
						document.body.appendChild(a);
						a.click();
						a.remove();
						window.URL.revokeObjectURL(url);
					})
					.catch(error => {
						Swal.close(); // Menutup loading modal
						console.error('There was a problem with the fetch operation:', error);
						Swal.fire({
							icon: 'error',
							title: 'Terjadi Kesalahan',
							text: 'File tidak tersedia untuk diunduh.',
							footer: 'Silakan coba lagi.',
							confirmButtonText: 'OK',
							confirmButtonColor: '#2563EB'
						});
					});
			});
		}

		/**
		 * Fungsi untuk menyalin teks ke clipboard.
		 * @param {string} text - Teks yang akan disalin.
		 */
		function copyToClipboard(text) {
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).catch(function(err) {
					console.error('Tidak dapat menyalin teks: ', err);
				});
			} else {
				// Fallback untuk browser yang tidak mendukung
				// Simpan posisi scroll saat ini
				const scrollY = window.scrollY || window.pageYOffset;
				const scrollX = window.scrollX || window.pageXOffset;

				var textarea = document.createElement('textarea');
				textarea.value = text;
				textarea.style.position = 'fixed';
				textarea.style.top = '0';
				textarea.style.left = '0';
				textarea.style.width = '1px';
				textarea.style.height = '1px';
				textarea.style.padding = '0';
				textarea.style.border = 'none';
				textarea.style.outline = 'none';
				textarea.style.boxShadow = 'none';
				textarea.style.background = 'transparent';
				document.body.appendChild(textarea);
				textarea.focus({
					preventScroll: true
				});
				textarea.select();
				try {
					document.execCommand('copy');
				} catch (err) {
					console.error('Fallback: Tidak dapat menyalin', err);
				}
				document.body.removeChild(textarea);

				// Kembalikan posisi scroll
				window.scrollTo(scrollX, scrollY);
			}
		}
	});

	// Event Listener untuk gambar thumbnail dokumentasi
	document.addEventListener('DOMContentLoaded', function() {
		// Seleksi elemen gambar thumbnail
		const thumbnails = document.querySelector('.dokumentasi .images');

		thumbnails.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('thumbnail')) {
				const imgSrc = e.target.src;
				const modalOverlay = document.getElementById('modalOverlay');
				const modalImage = document.getElementById('modalImage');
				const modalContent = document.getElementById('modalContent');

				modalImage.src = imgSrc;

				modalOverlay.classList.add('active');

				// Reset animasi jika diperlukan
				modalContent.style.animation = 'none';
				void modalContent.offsetWidth; // Trigger reflow
				modalContent.style.animation = 'zoomIn 0.5s forwards';
				modalContent.style.display = "block";

				// Menyimpan nilai overflow asli sebelum diubah
				originalOverflow = document.body.style.overflow;

				document.body.style.overflow = 'hidden';

				// Atur fokus ke modalImage setelah modal ditampilkan
				setTimeout(function() {
					modalImage.focus({
						preventScroll: true
					});
					modalImage.scrollIntoView({
						behavior: 'smooth',
						block: 'center',
						inline: 'center'
					});
				}, 500); // Sesuaikan delay sesuai animasi CSS
			}
		});

		// Fungsi untuk menutup modal
		function closeModal() {
			const modalOverlay = document.getElementById('modalOverlay');
			const modalImage = document.getElementById('modalImage');
			const modalContent = document.getElementById('modalContent');

			modalOverlay.classList.remove('active');
			modalImage.src = '';
			modalContent.style.display = "none";
			document.body.style.overflow = originalOverflow || '';
		}

		// Event listener untuk tombol close
		document.getElementById('closeButton').addEventListener('click', function(e) {
			e.stopPropagation(); // Mencegah event bubbling
			closeModal();
		});

		// Event listener untuk klik di luar konten modal
		document.getElementById('modalOverlay').addEventListener('click', function(e) {
			if (e.target === this) {
				closeModal();
			}
		});
	});

	// Animasi teks berjalan untuk elemen-elemen tertentu
	window.onload = function() {
		// Daftar kelas atau atribut data yang akan dianimasikan secara langsung
		const directClassesToAnimate = [
			"nama-pengguna",
			"email-pengguna",
			"xi-rekayasa-perangkat",
			"dasar-pemrograman1",
			"kompetensi-dasar",
			"nama-observer",
			"observeremail",
			"f6y8iotrqwmuge7wobny",
			"bagas-nugroho"
		];

		// Mengolah elemen-elemen yang dianimasikan secara langsung berdasarkan kelas
		directClassesToAnimate.forEach(className => {
			// Jika ini adalah kelas, gunakan selector kelas
			const elements = document.querySelectorAll(`.${className}`);

			elements.forEach(element => {
				setupScrollAnimation(element);
			});
		});

		/**
		 * Fungsi untuk memeriksa apakah elemen benar-benar overflow.
		 * @param {HTMLElement} element - Elemen yang akan diperiksa.
		 * @returns {boolean} - True jika elemen overflow.
		 */
		function isElementOverflowing(element) {
			return element.scrollWidth > element.clientWidth + 1; // Toleransi 1px
		}

		/**
		 * Fungsi untuk menangani animasi scroll pada elemen tertentu.
		 * @param {HTMLElement} element - Elemen yang akan dianimasikan.
		 */
		function setupScrollAnimation(element) {
			if (!isElementOverflowing(element)) return;

			// Simpan teks asli dalam data attribute untuk pemulihan nanti
			const originalText = element.textContent.trim();
			element.setAttribute('data-original-text', originalText);

			// Terapkan gaya default dengan ellipsis
			element.style.overflow = "hidden";
			element.style.textOverflow = "ellipsis";
			element.style.whiteSpace = "nowrap";

			/**
			 * Fungsi untuk memulai animasi scroll.
			 */
			function startScroll() {
				if (element.getAttribute('data-animating') === 'true') return;

				element.setAttribute('data-animating', 'true');

				// Hilangkan text-overflow: ellipsis saat animasi berjalan
				element.style.textOverflow = "unset";
				element.style.whiteSpace = "nowrap";
				element.style.overflow = "hidden";

				// Ganti innerHTML dengan scroll-container dan scroll-text
				element.innerHTML = `
                    <div class="scroll-container">
                        <span class="scroll-text">${originalText}&nbsp;&nbsp;&nbsp;</span>
                        <span class="scroll-text">${originalText}&nbsp;&nbsp;&nbsp;</span>
                    </div>
                `;

				const scrollContainer = element.querySelector(".scroll-container");
				const scrollTexts = element.querySelectorAll(".scroll-text");

				// Menghitung lebar total teks
				const textWidth = scrollTexts[0].offsetWidth;
				const totalWidth = textWidth * 2;

				// Mengatur lebar scroll-container agar cukup untuk menampung teks
				scrollContainer.style.width = `${totalWidth}px`;
				scrollContainer.style.display = "flex";

				// Variabel animasi
				let position = 0; // Posisi awal scroll
				const speed = 30; // Kecepatan animasi dalam piksel per detik

				let lastTimestamp = null;
				let animationId = null;

				/**
				 * Fungsi animasi menggunakan requestAnimationFrame.
				 * @param {number} timestamp - Waktu saat frame dimulai.
				 */
				function animate(timestamp) {
					if (!lastTimestamp) lastTimestamp = timestamp;
					const delta = timestamp - lastTimestamp;
					lastTimestamp = timestamp;

					// Menghitung pergeseran berdasarkan kecepatan
					position -= (speed * delta) / 1000; // Konversi delta ke detik

					if (Math.abs(position) >= textWidth) {
						// Reset posisi untuk loop infinite
						position += textWidth;
					}

					scrollContainer.style.transform = `translateX(${position}px)`;

					// Melanjutkan animasi frame berikutnya
					animationId = requestAnimationFrame(animate);
				}

				// Mulai animasi scroll
				animationId = requestAnimationFrame(animate);

				// Simpan ID animasi dalam data attribute untuk referensi nanti
				element.setAttribute('data-animation-id', animationId);
			}

			/**
			 * Fungsi untuk menghentikan animasi scroll dan mengembalikan posisi awal.
			 */
			function stopScroll() {
				if (element.getAttribute('data-animating') !== 'true') return;

				const animationId = element.getAttribute('data-animation-id');
				if (animationId) {
					cancelAnimationFrame(animationId);
				}

				// Kembalikan innerHTML ke teks asli
				const originalText = element.getAttribute('data-original-text');
				element.innerHTML = originalText;

				// Terapkan kembali text-overflow: ellipsis
				element.style.textOverflow = "ellipsis";
				element.style.whiteSpace = "nowrap";
				element.style.overflow = "hidden";

				// Hapus tanda bahwa elemen sedang dalam keadaan animasi
				element.setAttribute('data-animating', 'false');
			}

			// Menambahkan event listener untuk animasi saat mouse masuk dan keluar
			element.addEventListener("mouseenter", function() {
				startScroll();
			});

			element.addEventListener("mouseleave", function() {
				stopScroll();
			});
		}
	};
</script>

</html>