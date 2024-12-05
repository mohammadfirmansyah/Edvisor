<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1, width=device-width">
	<title><?php echo $title; ?></title>
	<link rel="icon" href="assets/img/favicon.png">
	<link rel="stylesheet" href="assets/css/bantuan.css" />
</head>

<body>
	<div class="bantuan unselectable-text">
		<!-- Bagian sidebar -->
		<div class="sidebar">
			<a class="logo-ls-1-parent link" href="sidebarBeranda">
				<img oncontextmenu="return false;" class="logo-ls-11" alt="" src="assets/img/logo_lesson_study_sidebar.svg">
				<b class="lesson-study">edvisor</b>
			</a>
			<a class="profile-side-bar link" href="sidebarProfile">
				<div class="profile-side-bar-child" alt="">
					<img oncontextmenu="return false;" class="profile-photo" src="<?php echo !empty($user->src_profile_photo) ? base_url($user->src_profile_photo) : base_url('assets/default/default_profile_picture.jpg'); ?>" alt="">
				</div>
				<div class="nama-pengguna"><?= htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></div>
				<div class="email-pengguna"><?= htmlspecialchars($user->email_address, ENT_QUOTES, 'UTF-8'); ?></div>
			</a>
			<div class="menu-bar">
				<a class="item-side-bar-default link" href="sidebarBeranda">
					<img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_beranda.svg">
					<div class="text-sidebar-default">Beranda</div>
				</a>
				<a class="item-side-bar-default link" href="sidebarGuruModel">
					<img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_guru_model.svg">
					<div class="text-sidebar-default">Guru Model</div>
				</a>
				<a class="item-side-bar-default link" href="sidebarObserver">
					<img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_observer.svg">
					<div class="text-sidebar-default">Observer</div>
				</a>
				<a class="item-side-bar-active link" href="sidebarBantuan">
					<img oncontextmenu="return false;" class="icon-sidebar-active" alt="" src="assets/img/icon_bantuan.svg">
					<div class="text-sidebar-active">Bantuan</div>
				</a>
			</div>
			<a class="item-side-bar-exit link" href="sidebarLogout">
				<img oncontextmenu="return false;" class="icon-sidebar-default" alt="" src="assets/img/icon_keluar.svg">
				<div class="text-sidebar-default">Keluar</div>
			</a>
		</div>
		<!-- Akhir bagian sidebar -->

		<!-- Bagian judul bantuan -->
		<a class="bantuan-group link" href="sidebarBeranda">
			<img oncontextmenu="return false;" class="iconsolidarrow-left" alt="" src="assets/img/icon_arrow_left.svg">
			<div class="bantuan9">Bantuan</div>
		</a>
		<div class="date-container">
			<p id="dateDisplay" class="date"></p>
			<p id="timeDisplay" class="date"></p>
		</div>
		<!-- Akhir bagian judul bantuan -->

		<!-- Bagian daftar bantuan -->
		<div class="butuh-bantuan-parent">
			<img oncontextmenu="return false;" class="logo-ls-1" alt="" src="assets/img/logo_lesson_study_bantuan.svg">
			<div class="butuh-bantuan">Butuh Bantuan?</div>
			<div class="bantuan-parent">
				<!-- Pertanyaan dan jawaban bantuan pertama -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Apa itu aplikasi monitoring Lesson Study berbasis web?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>Aplikasi monitoring lesson study berbasi web yang merupakan pengembangan lanjutan dari aplikasi monitoring lesson study berbasis mobile yang membantu guru dan tim Lesson Study dalam mengorganisir, merekam, dan menganalisis proses Lesson Study. Aplikasi ini memungkinkan guru untuk melakukan kolaborasi, merekam video pelajaran, memberikan umpan balik, dan melacak perkembangan pelajaran secara terperinci.</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan pertama -->

				<!-- Pertanyaan dan jawaban bantuan kedua -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Apa yang dapat saya lakukan setelah login ke aplikasi?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>
							<p class="membuat-dan-mengelola">1. Membuat dan mengelola kelas lesson study.</p>
							<p class="membuat-dan-mengelola">2. Mengunduh berkas untuk persiapan lesson study.</p>
							<p class="membuat-dan-mengelola">3. Berbagi dan berkolaborasi.</p>
							<p class="membuat-dan-mengelola">4. Melakukan observasi dan penilaian terhadap guru model.</p>
							<p class="membuat-dan-mengelola">5. Melihat dan membagikan hasil observasi dan penilaian lesson study.</p>
							<p class="membuat-dan-mengelola">6. Melihat rekaman dan dokumentasi.</p>
						</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan kedua -->

				<!-- Pertanyaan dan jawaban bantuan ketiga -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Bagaimana cara membuat kelas lesson study?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>
							<p class="membuat-dan-mengelola">1. Kunjungi menu "Guru Model"</p>
							<p class="membuat-dan-mengelola">2. Pilih "Buat Kelas"</p>
							<p class="membuat-dan-mengelola">3. Isi detail kelas lesson study.</p>
							<p class="membuat-dan-mengelola">4. Tambahkan berkas-berkas yang dibutuhkan seperti data siswa, modul ajar, dan media pembelajaran.</p>
							<p class="membuat-dan-mengelola">5. Tambah dan pilih observer untuk lesson study Anda.</p>
							<p class="membuat-dan-mengelola">6. Kelas Selesai Dibuat.</p>
						</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan ketiga -->

				<!-- Pertanyaan dan jawaban bantuan keempat -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Bagaimana cara mengundang observer untuk bergabung ke kelas lesson study saya?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>Anda dapat mengundang observer saat membuat kelas atau membagikan kode kelas yang tersedia saat anda selesai membuat kelas.</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan keempat -->

				<!-- Pertanyaan dan jawaban bantuan kelima -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Bagaimana cara saya bergabung menjadi observer pada kelas lesson study salah satu guru model?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>
							<p class="membuat-dan-mengelola">1. Pastikan Anda sudah memiliki kode kelas.</p>
							<p class="membuat-dan-mengelola">2. Masuk menu "Observer" dan</p>
							<p class="membuat-dan-mengelola">3. Pilih menu "Gabung Kelas"</p>
							<p class="membuat-dan-mengelola">4. Masukkan kode kelas yang telah anda miliki.</p>
							<p class="membuat-dan-mengelola">5. Jika kode tersebut valid maka anda berhasil bergabung ke kelas lesson study.</p>
						</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan kelima -->

				<!-- Pertanyaan dan jawaban bantuan keenam -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Bagaimana cara saya memulai observasi dan penilaian lesson study?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>
							<p class="membuat-dan-mengelola">1. Masuk menu "Observer"</p>
							<p class="membuat-dan-mengelola">2. Pilih salah satu kelas yang akan anda observasi.</p>
							<p class="membuat-dan-mengelola">3. Lengkapi setiap form yang ada.</p>
						</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan keenam -->

				<!-- Pertanyaan dan jawaban bantuan ketujuh -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Apakah saya bisa melakukan perekaman dan dokumentasi saat observasi lesson study?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>Tidak bisa. Rekaman dan dokumentasi hanya bisa dilakukan pada aplikasi monitoring lesson study berbasis android. Namun Anda bisa melihat hasil rekaman dan dokumentasi.</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan ketujuh -->

				<!-- Pertanyaan dan jawaban bantuan kedelapan -->
				<div class="bantuan1 unselectable-text">
					<div class="iconsolidchevron-down-parent">
						<img oncontextmenu="return false;" class="iconsolidchevron-down" alt="" src="assets/img/icon_down.svg">
						<div class="apa-itu-aplikasi">Bagaimana cara melihat hasil lesson study yang telah saya lakukan?</div>
					</div>
					<div class="aplikasi-monitoring-lesson-stu-wrapper">
						<div class="aplikasi-monitoring-lesson"><br>
							<p class="membuat-dan-mengelola">1. Masuk menu "Guru Model"</p>
							<p class="membuat-dan-mengelola">2. Pilih salah satu kelas lesson study anda.</p>
							<p class="membuat-dan-mengelola">3. Anda sudah masuk di halaman detail kelas.</p>
							<p class="membuat-dan-mengelola">4. Anda bisa melihat, mengunduh dan membagikan hasil lesson study.</p>
						</div>
					</div>
				</div>
				<!-- Akhir pertanyaan dan jawaban bantuan kedelapan -->
			</div>
		</div>
		<!-- Akhir bagian daftar bantuan -->
	</div>
</body>

<script>
	/**
	 * Fungsi untuk memperbarui tanggal dan waktu yang ditampilkan pada halaman.
	 */
	function updateDateTime() {
		const now = new Date(); // Mendapatkan tanggal dan waktu saat ini
		const optionsDate = {
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric'
		};
		const optionsTime = {
			hour: '2-digit',
			minute: '2-digit',
			second: '2-digit'
		};

		// Format tanggal dan jam dalam bahasa Indonesia
		const dateString = now.toLocaleDateString('id-ID', optionsDate);
		const timeString = now.toLocaleTimeString('id-ID', optionsTime);

		// Mengupdate elemen HTML dengan ID 'dateDisplay' dan 'timeDisplay'
		document.getElementById('dateDisplay').innerText = dateString;
		document.getElementById('timeDisplay').innerText = timeString;
	}

	// Memanggil fungsi updateDateTime setiap 500 milidetik untuk memperbarui waktu secara real-time
	setInterval(updateDateTime, 500);

	// Memastikan waktu saat ini ditampilkan saat memuat halaman
	updateDateTime();

	/**
	 * Event listener yang dijalankan saat seluruh konten halaman telah dimuat.
	 */
	document.addEventListener("DOMContentLoaded", function() {
		const bantuanItems = document.querySelectorAll(".bantuan1"); // Mendapatkan semua elemen dengan kelas 'bantuan1'

		bantuanItems.forEach(item => {
			const currentDescription = item.querySelector(".aplikasi-monitoring-lesson"); // Mendapatkan deskripsi bantuan
			const chevronIcon = item.querySelector(".iconsolidchevron-down"); // Mendapatkan ikon chevron

			// Menyembunyikan deskripsi bantuan secara default
			currentDescription.style.display = "none";
			currentDescription.style.maxHeight = "0";
			currentDescription.style.overflow = "hidden";
			currentDescription.style.transition = "max-height 0.5s ease-in-out, opacity 0.5s ease";

			/**
			 * Event listener untuk menangani klik pada item bantuan.
			 */
			item.addEventListener("click", function() {
				const isActive = currentDescription.style.display === "block"; // Memeriksa apakah deskripsi sedang ditampilkan

				if (isActive) {
					// Jika aktif, sembunyikan deskripsi
					currentDescription.style.maxHeight = "0";
					currentDescription.style.opacity = "0";
					chevronIcon.style.transform = "rotate(0deg)";
					setTimeout(() => {
						currentDescription.style.display = "none";
						item.style.backgroundColor = "";
					}, 500); // Menunggu animasi selesai sebelum menyembunyikan
				} else {
					// Jika tidak aktif, tampilkan deskripsi
					currentDescription.style.display = "block";
					setTimeout(() => {
						currentDescription.style.maxHeight = currentDescription.scrollHeight + "px";
						currentDescription.style.opacity = "1";
					}, 50); // Menambahkan sedikit delay untuk transisi yang mulus
					setTimeout(() => {
						item.style.backgroundColor = "#eff6ff";
					}, 500); // Mengubah warna latar setelah 0.5 detik
					chevronIcon.style.transform = "rotate(-180deg)";
				}
			});

			/**
			 * Event listener untuk menangani saat mouse memasuki area item bantuan.
			 */
			item.addEventListener("mouseenter", function() {
				if (currentDescription.style.display === "block") {
					item.style.backgroundColor = "#dbeafe";
				}
			});

			/**
			 * Event listener untuk menangani saat mouse meninggalkan area item bantuan.
			 */
			item.addEventListener("mouseleave", function() {
				if (currentDescription.style.display === "block") {
					item.style.backgroundColor = "#eff6ff";
				} else {
					item.style.backgroundColor = "";
				}
			});

			// Menambahkan transisi pada ikon chevron
			chevronIcon.style.transition = "transform 0.5s ease-in-out";
		});
	});

	/**
	 * Fungsi yang dijalankan saat seluruh halaman telah dimuat.
	 */
	window.onload = function() {
		// Daftar pasangan elemen target dan induknya (kosong karena tidak ada pasangan yang diberikan)
		const targetPairs = [];

		// Daftar kelas atau atribut data yang akan dianimasikan secara langsung
		const directClassesToAnimate = [
			"nama-pengguna",
			"email-pengguna"
		];

		// Gabungan semua elemen yang perlu dianimasikan
		const elementsToAnimate = [];

		/**
		 * Fungsi untuk memeriksa apakah sebuah elemen mengalami overflow.
		 * @param {HTMLElement} element - Elemen yang akan diperiksa.
		 * @returns {Object} Informasi tentang apakah elemen mengalami overflow dan detail lainnya.
		 */
		function isElementOverflowing(element) {
			const scrollWidth = element.scrollWidth;
			const clientWidth = element.clientWidth;
			const difference = scrollWidth - clientWidth;
			const overflowing = scrollWidth > clientWidth + 1; // Toleransi 1px

			return {
				overflowing,
				scrollWidth,
				clientWidth,
				difference
			};
		}

		/**
		 * Fungsi untuk menyiapkan animasi scroll pada elemen tertentu.
		 * @param {HTMLElement} element - Elemen yang akan dianimasikan.
		 */
		function setupScrollAnimation(element) {
			const overflowInfo = isElementOverflowing(element);

			if (!overflowInfo.overflowing) {
				// console.log(`Tidak perlu animasi untuk elemen:`, element);
				// console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Tidak melebihi toleransi 1px)`);
				return;
			} else {
				// console.log(`Animasi diperlukan untuk elemen:`, element);
				// console.log(`Alasan: scrollWidth (${overflowInfo.scrollWidth}px) - clientWidth (${overflowInfo.clientWidth}px) = ${overflowInfo.difference}px (Melebihi toleransi 1px)`);
			}

			// Menyimpan teks asli dalam atribut data untuk pemulihan nanti
			const originalText = element.textContent.trim();
			element.setAttribute('data-original-text', originalText);

			// Menerapkan gaya default dengan ellipsis
			element.style.overflow = "hidden";
			element.style.textOverflow = "ellipsis";
			element.style.whiteSpace = "nowrap";

			/**
			 * Fungsi untuk memulai animasi scroll.
			 */
			function startScroll() {
				if (element.getAttribute('data-animating') === 'true') return;

				element.setAttribute('data-animating', 'true');

				// Menghilangkan text-overflow: ellipsis saat animasi berjalan
				element.style.textOverflow = "unset";
				element.style.whiteSpace = "nowrap";
				element.style.overflow = "hidden";

				// Mengganti innerHTML dengan scroll-container dan scroll-text
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
				let animationId = null; // ID untuk requestAnimationFrame

				/**
				 * Fungsi animasi menggunakan requestAnimationFrame.
				 * @param {DOMHighResTimeStamp} timestamp - Waktu saat frame animasi.
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

				// Menyimpan ID animasi dalam atribut data untuk referensi nanti
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

				// Mengembalikan innerHTML ke teks asli
				const originalText = element.getAttribute('data-original-text');
				element.innerHTML = originalText;

				// Menerapkan kembali text-overflow: ellipsis
				element.style.textOverflow = "ellipsis";
				element.style.whiteSpace = "nowrap";
				element.style.overflow = "hidden";

				// Menghapus tanda bahwa elemen sedang dalam keadaan animasi
				element.setAttribute('data-animating', 'false');
			}

			// Menyimpan fungsi startScroll dan stopScroll ke elemen untuk akses mudah
			element.startScroll = startScroll;
			element.stopScroll = stopScroll;
		}

		// Mengolah pasangan target dengan induknya (tidak ada pasangan yang diberikan)
		targetPairs.forEach(pair => {
			const {
				targetClass,
				parentClass
			} = pair;
			const parentElements = document.querySelectorAll(`.${parentClass}`);

			parentElements.forEach(parent => {
				const targetElements = parent.querySelectorAll(`.${targetClass}`);
				if (targetElements.length === 0) {
					console.warn(`Tidak menemukan elemen target dengan kelas "${targetClass}" dalam induk:`, parent);
					return;
				}

				targetElements.forEach(target => {
					setupScrollAnimation(target);
					elementsToAnimate.push({
						element: target,
						type: 'parent'
					});
				});

				// Menambahkan event listener pada induk untuk hover
				parent.addEventListener("mouseenter", function() {
					targetElements.forEach(target => {
						if (isElementOverflowing(target).overflowing) {
							target.startScroll();
						}
					});
				});

				parent.addEventListener("mouseleave", function() {
					targetElements.forEach(target => {
						target.stopScroll();
					});
				});
			});
		});

		// Mengolah elemen-elemen yang dianimasikan secara langsung berdasarkan kelas
		directClassesToAnimate.forEach(className => {
			// Jika ini adalah kelas, gunakan selector kelas
			const elements = document.querySelectorAll(`.${className}`);

			elements.forEach(element => {
				setupScrollAnimation(element);
				elementsToAnimate.push({
					element: element,
					type: 'direct'
				});

				const overflowInfo = isElementOverflowing(element);
				if (overflowInfo.overflowing) {
					// Menambahkan event listener untuk memulai dan menghentikan animasi saat hover
					element.addEventListener("mouseenter", function() {
						element.startScroll();
					});

					element.addEventListener("mouseleave", function() {
						element.stopScroll();
					});
				}
			});
		});

		/**
		 * Event listener untuk menangani resize window.
		 * Menyesuaikan animasi pada elemen yang sudah ada.
		 */
		window.addEventListener('resize', function() {
			elementsToAnimate.forEach(item => {
				const {
					element
				} = item;

				// Batalkan animasi jika sedang berjalan
				if (element.getAttribute('data-animating') === 'true') {
					const animationId = element.getAttribute('data-animation-id');
					if (animationId) {
						cancelAnimationFrame(animationId);
					}
					const originalText = element.getAttribute('data-original-text');
					element.innerHTML = originalText;
					element.style.textOverflow = "ellipsis";
					element.style.whiteSpace = "nowrap";
					element.style.overflow = "hidden";
					element.setAttribute('data-animating', 'false');
				}

				// Periksa kembali overflow dan terapkan animasi jika perlu
				setupScrollAnimation(element);

				const overflowInfo = isElementOverflowing(element);
				if (overflowInfo.overflowing) {
					if (item.type === 'parent') {
						// Pastikan event listener pada parent sudah ditetapkan
						const parent = element.parentElement;
						if (parent) {
							parent.addEventListener("mouseenter", function() {
								element.startScroll();
							});

							parent.addEventListener("mouseleave", function() {
								element.stopScroll();
							});
						}
					} else if (item.type === 'direct') {
						// Pastikan event listener pada elemen langsung
						element.addEventListener("mouseenter", function() {
							element.startScroll();
						});

						element.addEventListener("mouseleave", function() {
							element.stopScroll();
						});
					}
				}
			});
		});
	};
</script>

</html>