const halAwal = halamanDefault;
const enc = new Encryption();
$(document).ready(function () {
	"use strict";
	//remove session storage
	sessionStorage.clear();
	var halaman = 1;
	const kudarkMode = window.matchMedia("(prefers-color-scheme:dark)").matches;
	console.log(kudarkMode);

	$(document).on("click", ".ui.radio.checkbox", function () {
		$(this).checkbox(); // Inisialisasi checkbox pada elemen yang diklik
	});
	//sidebar toggle
	$(".ui.sidebar")
		.sidebar({
			context: $(".bottom.pushable"),
		})
		.sidebar("attach events", ".menu .item.nabiila")
		.sidebar("setting", "transition", "push");
	let handler = {
		activate: function () {
			if (!$(this).hasClass("dropdown browse")) {
				$(this)
					.addClass("active")
					.closest(".ui.menu")
					.find(".item")
					.not($(this))
					.removeClass("active");
			}
		},
		menuorientasi: function () {
			let ini = $(this);
			ini.addClass("active").siblings().removeClass("active");
			let jenis = ini.attr("jns");
			switch (jenis) {
				case "orientasi":
					$('form[name="form_modal_kedua"]').form(
						"set value",
						"orientasi",
						ini.attr("value")
					);
					break;
				default:
					break;
			}
		},
	};
	$("body").on("click", ".align .button, .font .button", function(e) {
		e.preventDefault();
		if ($(this).parent().hasClass('align')) {
			// Hapus kelas 'active' dari semua tombol .align
			$('.align .button').removeClass('active');
			// Tambahkan kelas 'active' hanya pada tombol yang diklik
			$(this).addClass('active');
		} else if ($(this).parent().hasClass('font')) {
			// Toggle kelas 'active' pada tombol yang diklik
			$(this).toggleClass('active');
		}
	});
	
	$("body").on("click", ".menu .item.aksi", handler.menuorientasi);
	$(".menu .item.inayah").on("click", handler.activate);
	$(".ui.dropdown").dropdown();
	$(".menu .item").tab();
	// fix main menu to page on passing
	// $('.main.menu, .sticky.main').visibility({
	// 	type: 'fixed'
	// });
	// $('.overlay').visibility({
	// 	type: 'fixed',
	// 	offset: 45
	// });
	$(".ui.accordion").accordion({
		exclusive: false,
	});
	$(".bottom.attached.segment .ui.sticky").sticky({
		context: ".bottom.segment",
		pushing: false,
	});
	//menu lain
	//logout
	$("body").on("click", "[name='log_out']", function () {
		setTimeout(function () {
			window.location.href = "script/logout";
		}, 400);
	});
	//theme dark
	let darkmodeEnabled = $(`a[name="change_themes"] i`).hasClass("sun");
	$("body").on("click", "[name='change_themes']", function () {
		// ready();
		darkmodeEnabled = $(`a[name="change_themes"] i`).hasClass("sun");
		if (darkmodeEnabled) {
			toggleLightMode();
			darkmodeEnabled = false;
		} else {
			toggleDarkMode();
			darkmodeEnabled = true;
		}
	});
	//============================
	//=========CARI DATA======
	//============================
	$(document).on("keyup", "#cari_data", function () {
		let ini = $(this);
		let elm_load = ini.closest("div.ui.input");
		let jenis = ini.attr("name");
		let tbl = ini.attr("tbl");
		let txt = ini.val().trim();
		if (jenis !== undefined && jenis.length > 0) {
			elm_load.addClass("loading");
			delay(function () {
				switch (jenis) {
					case "kd_akun":
						$('a[data-tab="get"][tbl="' + jenis + '"]').click();
						break;
					default:
						$('a[tbl="' + tbl + '"]')
							.first()
							.trigger("click");
					// $('a[data-tab="' + jenis + '"]').first().trigger('click');
				}
				elm_load.removeClass("loading");
			}, 1000);
		}
	});
	//=====================
	//======DATA TAB=======@audit-ok data tab
	//=====================
	$("body").on("click", 'a[data-tab], a[name="page"]', function (e) {
		e.preventDefault();
		tok($(this));
		let url = "script/get_data";
		const dasboard = $(".message.dashboard");
		let ini = $(this);
		let tab = ini.attr("data-tab");
		let iconTab = $(this)
			.find(':not([name="page"]) > i.icon:not(:has(*))') // Memilih elemen <i> yang tidak berada dalam <span>
			.closest('a:not([name="page"])') // Temukan elemen <a> terdekat yang tidak memiliki atribut name dengan nilai "page"
			.find("> i.icon") // Temukan kembali elemen <i> di dalam elemen <a>, hanya yang langsung berada di dalamnya
			.attr("class"); // Mengambil kelas atributnya
		let jenis_this = ini.attr("jns");
		let jns = ini.attr("jns");
		let jenis = "get_tbl"; //get data
		let tbl = ini.attr("tbl");
		let tb = ini.attr("tb");
		let Text_ssh_sbu = tbl ? tbl.toUpperCase() : "";
		let harga_ssh_asb = [
			"clipboard list icon",
			`${Text_ssh_sbu}`,
			"Standar Harga Satuan",
			'PP 12 Tahun 2019<ol class="ui list"><li class="item">Belanja Daerah sebagaimana dimaksud dalam Pasal 49 ayat (5) berpedoman pada standar harga satuan regional, analisis standar belanja, dan/atau standar teknis sesuai dengan ketentuan peraturan perurndang-undangan.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (1) dan ayat (2) ditetapkan dengan Peraturan Presiden.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (3) digunakan sebagai pedoman dalam menyusun standar harga satuan pada masing-masing Daerah.</li></ol>',
		];
		let tab_renstra = [
			"clipboard list icon",
			"RENSTRA",
			"Rencana Startegi",
			"",
		];
		let tab_renja = [
			"yellow tags icon",
			"RENJA",
			"Rencana Kerja dan Anggaran SKPD",
			"Rencana Kerja dan Anggaran Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD.",
		];
		let tab_dpa = [
			"violet tag icon icon",
			"DPA",
			"Daftar Pelaksanaan Anggaran (DPA)",
			"Daftar Pelaksanaan Anggaran (DPA) Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat DPA SKPD adalah dokumen yang memuat pendapatan dan belanja setiap SKPD yang digunakan sebagai dasar pelaksanaan oleh pengguna anggaran.",
		];
		let arrayDasboard = {
			create_surat: [
				"newspaper icon",
				"Tata Naskah",
				"membuat naskah dinas",
				"Naskah Dinas adalah informasi tertulis sebagai alat komunikasi kedinasan yang dibuat dan/atau diterima oleh pejabat yang berwenang di lingkungan Lembaga Negara dan Pemerintahan Daerah dalam rangka penyelenggaraan tugas pemerintahan dan pembangunan",
			],
			register_surat: [
				"newspaper icon",
				"Register Surat",
				"data register naskah dinas",
				"Naskah Dinas adalah informasi tertulis sebagai alat komunikasi kedinasan yang dibuat dan/atau diterima oleh pejabat yang berwenang di lingkungan Lembaga Negara dan Pemerintahan Daerah dalam rangka penyelenggaraan tugas pemerintahan dan pembangunan",
			],
			berita: ["newspaper icon", "Berita", "berita", "berita"],
			wallchat: ["comments outline icon", "Pesan dan Wall", "pesan", "pesan"],
			atur_satu: ["erase icon", "PENGATURAN", "seting data", "pengaturan"],
			pengaturan: ["settings icon", "PENGATURAN", "seting data", "pengaturan"],
			reset: ["erase icon", "RESET", "reset tabel data", "mereset tabel data"],
			sk_asn: [
				"home icon",
				"Surat Keputusan",
				"Surat Keputusan",
				"Surat Keputusan",
			],
			tab_home: ["home icon", "DASHBOARD", "seSendok", ""],
			renstra: tab_renstra,
			renstra: tab_renstra,
			renja: tab_renja,
			sub_keg_renja: tab_renja,
			sub_keg_dpa: tab_dpa,
			dpa: tab_dpa,
			dppa: [
				"violet tag icon",
				"DPPA",
				"Daftar PerubahanPelaksanaan Anggaran (DPPA)",
				"Rencana Kerja dan Anggaran Perubahan Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD.",
			],
			renja_p: [
				"yellow tags icon",
				"RENJA PEUBAHAN",
				"Rencana Kerja dan Anggaran Perubahan SKPD",
				"Rencana Kerja dan Anggaran PerubahanSatuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD.",
			],
			asn: [
				"users icon",
				"Aparatur Sipil Negara",
				"Data Kepegawaian",
				"Merupakan data kepegawaian satuan perangkat daerah",
			],
			tujuan_sasaran_renstra: [
				"clipboard list icon",
				"Tujuan Sasaran Renstra",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi dan kodefikasi program disusun berdasarkan pembagian sub urusan dan kegiatan disusun berdasarkan pembagian kewenangan yang diatur dalam Lampiran Undang-Undang Nomor 23 Tahun 2014.Hal ini dilakukan untuk memastikan ruang lingkup penyelenggaraan pemerintahan daerah dilakukan sesuai dengan keenangannya, sehingga mendukung pelaksanaan asas prinsip akuntabilitas, efisiensi, eksternalitas serta kepentingan strategis nasional",
			],
			users: ["users icon", "users", "Pengaturan Akun", "Pengaturan akun"],
			tab_dpa: tab_dpa,
			daftar_paket: [
				"file contract icon",
				"DAFTAR PAKET DAN KONTRAK",
				"daftar paket pekerjaan",
				"Kontrak Pengadaan Barang/Jasa yang selanjutnya disebut Kontrak adalah perjanjian tertulis antara PA/KPA/PPK dengan Penyedia Barang/Jasa atau pelaksana Swakelola",
			],
			dppa: [
				"clipboard list icon",
				"DPPA",
				"Dokumen Pelaksanaan Perubahan Anggaran",
				"",
			],
			tab_input_real: ["clipboard list icon", "Realisasi", "Input Realisasi"],
			tab_spj: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_lap: ["clipboard list icon", "Renja", "Rencana Kerja"],
			bidang_urusan: [
				"clipboard list icon",
				"BIDANG URUSAN",
				"Klasifikasi dan kodefikasi",
				"Bidang Urusan: Sejumlah tugas atau tanggung jawab khusus pemerintah daerah yang diKlasifikasikan menjadi urusan pemerintahan konkuren terbagi menjadi 32 (tiga puluh dua) bidang urusan, 2 (dua) Urusan Pendukung, 7(tujuh) Urusan Penunjang, 1 (satu) urusan Pengawasan, 3 (Tiga) Urusan Kewilayahan, serta Urusan Kekhususan dan keistimewaan",
			],
			prog: [
				"clipboard list icon",
				"PROGRAM",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi dan kodefikasi program disusun berdasarkan pembagian sub urusan dan kegiatan disusun berdasarkan pembagian kewenangan yang diatur dalam Lampiran Undang-Undang Nomor 23 Tahun 2014.Hal ini dilakukan untuk memastikan ruang lingkup penyelenggaraan pemerintahan daerah dilakukan sesuai dengan keenangannya, sehingga mendukung pelaksanaan asas prinsip akuntabilitas, efisiensi, eksternalitas serta kepentingan strategis nasional",
			],
			keg: [
				"clipboard list icon",
				"KEGIATAN",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi dan kodefikasi kegiatan",
			],
			sub_keg: [
				"clipboard list icon",
				"SUB KEGIATAN",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi dan kodefikasi sub kegiatan disusun berdasarkan aktivitas atau layanan dalam penyelesaian permasalahan daerah sesuai kewenangannya.",
			],
			aset: [
				"clipboard list icon",
				"ASET",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			akun_belanja: [
				"clipboard list icon",
				"AKUN",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			realisasi: [
				"money icon",
				"Realisasi",
				"Realisasi Fisik dan Keuangan",
				"merupakan daftar realisasi uraian dan paket",
			],
			mapping: [
				"clipboard list icon",
				"Mapping",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			wilayah: [
				"globe icon",
				"LOKASI/WILAYAH",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			organisasi: [
				"id card icon",
				"SKPD",
				"Organisasi Perangkat Daerah",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening SKPD.",
			],
			sumber_dana: [
				"money check alternate icon",
				"Sumber Dana",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Sumber Pendanaan ditujukan untuk memberikan informasi atas sumber dana berdasarkan tujuan penggunaan dana dari setiap pelaksanaan urusan pemerintahan daerah yang dijabarkan berdasarkan program, kegiatan dan sub kegiatan dalam rangka pengendalian masing-masing kelompok dana meliputi pengawasan/control, akuntabilitas/accountability dan transparansi/transparency (CAT).",
			],
			peraturan: [
				"balance scale icon",
				"Peraturan",
				"Aturan Yang digunakan",
				"ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan.",
			],
			rekanan: [
				"book reader icon",
				"REKANAN",
				"Klasifikasi dan kodefikasi",
				"Penyedia barang dan/atau jasa",
			],
			satuan: [
				"calculator icon",
				"SATUAN",
				"Klasifikasi dan kodefikasi",
				"Penyedia barang dan/atau jasa",
			],
			tab_hargasat: [
				"calculator icon",
				"SSH",
				"Standar Harga Satuan",
				'PP 12 Tahun 2019<ol class="ui list"><li class="item">Belanja Daerah sebagaimana dimaksud dalam Pasal 49 ayat (5) berpedoman pada standar harga satuan regional, analisis standar belanja, dan/atau standar teknis sesuai dengan ketentuan peraturan perurndang-undangan.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (1) dan ayat (2) ditetapkan dengan Peraturan Presiden.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (3) digunakan sebagai pedoman dalam menyusun standar harga satuan pada masing-masing Daerah.</li></ol>',
			],
			ssh: harga_ssh_asb,
			asb: harga_ssh_asb,
			sbu: harga_ssh_asb,
			hspk: harga_ssh_asb,
			tab_reset: [
				"red table icon",
				"Reset Tabel",
				"menghapus seluruh data tabel",
			],
			tab_template: [
				"download icon",
				"Template",
				"Ungguh Contoh Template AHSP",
			],
			tab_user: ["users icon", "Users AHSP", "Akun user ahsp"],
			tab_wallchat: ["comments outline icon", "AHSP chat", "we are chat"],
			tab_wall: ["comments outline icon", "AHSP chat", "Ruang Chat Users"],
			tab_inbox: ["comment outline icon", "AHSP chat", "inbox"],
			"monev[informasi]": ["info circle icon", "Informasi-Monev", "Informasi"],
			"monev[realisasi]": ["chartline icon", "Informasi-Monev", "Realisasi"],
			"monev[laporan]": ["chart pie icon", "Informasi-Monev", "Laporan"],
			tab_outbox: ["comment dots outline icon", "AHSP chat", "outbox"],
		};

		let divTab = $(`div[data-tab="${ini.attr("data-tab")}"]`);
		let iconDashboard = "home icon";
		let headerDashboard = ini.text();
		let pDashboard = "seSendok";
		let cryptos = false;
		if (ini.attr("name") === "page") {
			headerDashboard = ini.text();
			halaman = ini.attr("hal");
			let ret = ini.attr("ret");
			//tab = ini.attr("dt-tab");
			switch (ret) {
				case "prev":
					halaman = halaman - 1;
					break;
				case "next":
					halaman = parseInt(halaman) + 1;
					break;
			}
			tab = tbl;
			divTab = ini.closest("div[data-tab]");
		}
		$(`#cari_data`).attr("tbl", tbl).attr("dt-tab", tab);

		if (tbl in arrayDasboard) {
			if (typeof iconTab !== "undefined") {
				arrayDasboard[tbl][0] = iconTab;
			}
			iconDashboard = arrayDasboard[tbl][0];
			headerDashboard = arrayDasboard[tbl][1];
			pDashboard = arrayDasboard[tbl][2];
		} else if (tab in arrayDasboard) {
			if (typeof iconTab !== "undefined") {
				arrayDasboard[tab][0] = iconTab;
			}
			iconDashboard = arrayDasboard[tab][0];
			headerDashboard = arrayDasboard[tab][1];
			pDashboard = arrayDasboard[tab][2];
		} else if (tb in arrayDasboard) {
			if (typeof iconTab !== "undefined") {
				arrayDasboard[tb][0] = iconTab;
			}
			iconDashboard = arrayDasboard[tb][0];
			headerDashboard = arrayDasboard[tb][1];
			pDashboard = arrayDasboard[tb][2];
		}
		let jalankanAjax = false;
		//node dashboard
		dasboard.find($("i")).attr("class", "").addClass(iconDashboard);
		let dasboardheader = dasboard.find($("div.header"));
		dasboardheader.text(headerDashboard);
		dasboard.find($("div.pDashboard")).html(pDashboard);
		$(`div[data-tab=${tab}]`).attr("tbl", tbl);
		let data = {};
		console.log(tab);
		switch (tab) {
			case "tab_kontrak":
				$('div[name="ketref"]').html(arrayDasboard[tbl][3]);
				jalankanAjax = true;
				break;
			case "tab_hargasat":
				dasboardheader.text(tbl.toUpperCase());
				$('div[name="kethargasat"]').html(arrayDasboard[tab][3]);
				jalankanAjax = true;
				switch (tbl) {
					case "ssh":
					case "hspk":
					case "asb":
					case "sbu":
						divTab.find("[tbl]").attr("tbl", tbl);
						break;
				}
				break;
			case "tab_all":
				let attrButton = "flyout";
				switch (tbl) {
					case "create_surat":
					case "sk_asn":
						attrButton = "modal_show";
						break;
				}
				var newButton = $("<button>", {
					class: "ui button",
					name: attrButton, // Mengubah atribut name
					"data-tooltip": "Tambah Data", // Menambahkan atribut data-tooltip
					"data-position": "bottom center", // Menambahkan atribut data-position
					jns: "add", // Menambahkan atribut jns
					tbl: tbl, // Menambahkan atribut tbl
					html: '<i class="plus icon"></i>', // Isi dari elemen button baru
				});
				// Mengganti elemen pertama dengan elemen baru
				$('[data-tab="tab_all"] .ui.right.floated.basic.buttons button')
					.eq(0)
					.replaceWith(newButton);
				$('div[name="ketref"]').html(arrayDasboard[tbl][3]);
				jalankanAjax = true;
				divTab.find("[tbl]").attr("tbl", tbl);
				break;
			case "tab_ref":
				$('div[name="ketref"]').html(arrayDasboard[tbl][3]);
				jalankanAjax = true;
				divTab.find("[tbl]").attr("tbl", tbl);
				break;
			case "tab_peraturan":
				jalankanAjax = true;
				break;
			case "pengaturan":
			case "get_pengaturan":
				switch (tbl) {
					case "pengaturan":
						let formPakai = $(`[name="form_pengaturan"]`);
						let sumdatetimeStartcal = formPakai.find(
							`.ui.calendar.datetime.startcal`
						);
						let dynamic = [];
						let jeniku = {
							awal_renstra: "akhir_renstra",
							awal_renja: "akhir_renja",
							awal_dpa: "akhir_dpa",
							awal_renja_p: "akhir_renja_p",
							awal_dppa: "akhir_dppa",
						};
						Object.keys(sumdatetimeStartcal).forEach((key) => {
							let element = $(sumdatetimeStartcal[key]);
							let namaAttr = element.attr("name");
							if (namaAttr !== undefined) {
								let bill = jeniku[namaAttr];
								dynamic[namaAttr] = new CalendarConstructor(
									`div[name="${namaAttr}"],div[name="${bill}"]`
								);
								dynamic[namaAttr].startCalendar = $(`div[name="${namaAttr}"]`);
								dynamic[namaAttr].endCalendar = $(`div[name="${bill}"]`);
								dynamic[namaAttr].Type("datetime");
								dynamic[namaAttr].runCalendar();
							}
						});
						break;
				}
				jenis = "get_pengaturan";
				jalankanAjax = true;
				break;
			case "renstra":
			case "tab_renstra":
				if (tbl) {
					jalankanAjax = true;
					divTab.find("button[jns]").attr("tbl", tbl);
				}
				break;
			case "aset":
			case "keg":
			case "bidang_urusan":
			case "prog":
			case "sub_keg":
			case "akun_belanja":
			case "sumber_dana":
			case "organisasi":
			case "wilayah":
			case "mapping":
			case "wilayah":
			case "sbu":
			case "ssh":
			case "asn":
			case "asb":
			case "hspk":
			case "satuan":
			case "rekanan":
			case "tujuan_sasaran_renstra":
			case "tujuan_sasaran":
			case "tab_input_real":
			case "atur":
			case "realisasi":
			case "users":
			case "sk_asn":
				jalankanAjax = true;
				break;
			case "profil":
				let formIni = $(`form[name="profil"]`);
				let calendarYear = new CalendarConstructor(".ui.calendar.year");
				calendarYear.Type("year");
				calendarYear.runCalendar();
				addRulesForm(formIni);
				jenis = "get_row";
				jalankanAjax = true;
				break;
			case "renja":
			case "dpa":
			case "renja_p":
			case "dppa":
			case "sub_keg_renja":
			case "sub_keg_dpa":
			case "tab_renja":
				let itemDivDataTab = $(`div[data-tab="tab_renja"] .menu a.item`);
				switch (jns) {
					case "rincian_perubahan":
					case "rincian_pokok":
						data["id_sub_keg"] = ini.closest("tr").attr("id_row");
						break;
					default:
						switch (tbl) {
							case "dpa":
							case "dppa":
							case "renja":
							case "renja_p":
								data["id_sub_keg"] = ini.attr("id_sub_keg");
								if (ini.attr("name") === "page") {
									data["id_sub_keg"] = $(
										`a.item[tbl="${tbl}"][id_sub_keg]`
									).attr("id_sub_keg");
								}
								break;
						}
						break;
				}
				switch (tbl) {
					case "sub_keg_renja":
						itemDivDataTab.eq(0).attr("tbl", "sub_keg_renja");
						itemDivDataTab.eq(0).text("Sub Kegiatan");
						itemDivDataTab
							.eq(1)
							.attr("tbl", "renja")
							.attr("id_sub_keg", "renja");
						itemDivDataTab.eq(1).text("Renja");
						itemDivDataTab
							.eq(2)
							.attr("tbl", "renja_p")
							.attr("id_sub_keg", "renja");
						itemDivDataTab.eq(2).text("Renja Perubahan");
						break;
					case "sub_keg_dpa":
						itemDivDataTab.eq(0).attr("tbl", "sub_keg_dpa");
						itemDivDataTab.eq(0).text("Sub Kegiatan");
						itemDivDataTab.eq(1).attr("tbl", "dpa").attr("id_sub_keg", "renja");
						itemDivDataTab.eq(1).text("D P A");
						itemDivDataTab
							.eq(2)
							.attr("tbl", "dppa")
							.attr("id_sub_keg", "renja");
						itemDivDataTab.eq(2).text("DPPA");
						break;
					default:
						break;
				}
				// $(".message.goyang.keterangan").find('p').text(arrayDasboard[tab][3]);
				if (tbl) {
					divTab.find("button[jns]").attr("tbl", tbl);
				}
				switch (tbl) {
					case "sub_keg_renja":
					case "sub_keg_dpa":
						divTab.find("table.sub_keg").attr("hidden", "");
						jalankanAjax = true;
					case "dpa":
					case "dppa":
					case "renja":
					case "renja_p":
						let elmk = divTab.find(`.secondary.menu [tbl="${tbl}"]`);
						if (
							tbl !== "sub_keg_renja" &&
							tbl !== "sub_keg_dpa" &&
							data["id_sub_keg"] > 0
						) {
							divTab.find("table.sub_keg").removeAttr("hidden");
							// tambhalan atribut id sub kegiatan di button jns
							if (data["id_sub_keg"] > 0) {
								divTab
									.find('button[jns="add"]')
									.attr("id_sub_keg", data["id_sub_keg"]);
								divTab
									.find(`[tbl="${tbl}"]`)
									.attr("id_sub_keg", data["id_sub_keg"]);
								jalankanAjax = true;
							}
						}
						if (jalankanAjax) {
							elmk
								.addClass("active")
								.closest(".ui.menu")
								.find(".item")
								.not($(elmk))
								.removeClass("active");
						}
						break;
					default:
						break;
				}
				break;
			case "vvvv":
				dasboard.find($("message goyang keterangan")).html(pDashboard);
				break;
			default:
				break;
		}
		data.cari = cari(tab);
		data.rows = countRows();
		data.jenis = jenis;
		data.tbl = tbl;
		data.halaman = halaman;

		if (jalankanAjax) {
			loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				let error_code = result.error.code;
				let kelasToast = "success";
				let iconToast = "check circle icon";
				if (result.success === true) {
					let hasKey = result.hasOwnProperty("error");
					if (hasKey) {
						loaderHide();
						switch (jenis) {
							case "get_tbl":
								const elmTable = divTab.find("table.insert");
								const elmtbody = elmTable.find(`tbody`);
								const elmtfoot = elmTable.find(`tfoot`);
								const elmthead = elmTable.find(`thead`);
								elmtbody.html(result.data.tbody);
								elmtfoot.html(result.data.tfoot);
								if (result?.data?.thead) {
									elmthead.html(result.data.thead);
								}
								if ($(`a[name="change_themes"]`).attr("theme") === "dark") {
									elmTable
										.find(".ui:not(.hidden),.icon")
										.removeClass("inverted")
										.addClass("inverted");
								}
								divTab.find(`.ui.dropdown`).dropdown({});
								let dokumenAnggaran = "";
								switch (tbl) {
									case "tujuan_renstra":
									case "tujuan_sasaran_renstra":
									case "sasaran_renstra":
									case "renstra":
										dokumenAnggaran = "renstra";
										break;
									case "renja":
									case "dpa":
									case "renja_p":
									case "dppa":
										dokumenAnggaran = tbl;
										break;
									case "sub_keg_renja":
										dokumenAnggaran = "renja_p";
										break;
									case "sub_keg_dpa":
										const myArray = tbl.split("_");
										dokumenAnggaran = "dppa";
										break;
								}
								switch (tbl) {
									case "renja":
									case "dpa":
									case "renja_p":
									case "dppa":
										const elmTableSubKeg = divTab.find("table.sub_keg");
										elmTableSubKeg.html(result.data.tr_sub_keg);
									case "sub_keg_renja":
									case "sub_keg_dpa":
									case "tujuan_renstra":
									case "tujuan_sasaran_renstra":
									case "sasaran_renstra":
									case "renstra":
										window["kunci_" + dokumenAnggaran] =
											result.data[`kunci_${dokumenAnggaran}`];
										window["setujui_" + dokumenAnggaran] =
											result.data[`setujui_${dokumenAnggaran}`];
										if (
											window["kunci_" + dokumenAnggaran] ||
											window["setujui_" + dokumenAnggaran]
										) {
											divTab
												.find(`button[jns="add"], button[jns="import"]`)
												.attr("disabled", "");
										} else {
											divTab
												.find(`button[jns="add"], button[jns="import"]`)
												.removeAttr("disabled");
										}
										break;
								}
								$("[rms]").mathbiila();
								break;
							case "get_data":
								break;
							case "get_pengaturan":
								switch (tbl) {
									case "pengaturan":
										// result.data?.tahun;
										var dropdown_ajx_org = new DropdownConstructor(
											'form[name="form_pengaturan"] .ui.dropdown[name="id_opd_tampilkan"]'
										);
										let hasKey = result?.data.hasOwnProperty("values");
										if (hasKey) {
											dropdown_ajx_org.valuesDropdown(
												result.data.values.id_opd_tampilkan
											);
										}
										dropdown_ajx_org.returnList({
											jenis: "get_row_json",
											tbl: "organisasi",
											minCharacters: 1,
										});
										let formPengaturan = $('form[name="form_pengaturan"]');
										$(
											'form[name="form_pengaturan"] .ui.dropdown.aturan'
										).dropdown({
											values: result?.data?.peraturan,
										});
										let attrName = formPengaturan.find(
											"input[name],textarea[name]"
										);
										for (const iterator of attrName) {
											let attrElm = $(iterator).attr("name");
											if (attrElm !== "file") {
												formPengaturan.form(
													"set value",
													attrElm,
													result?.data?.row_tahun[attrElm]
												);
											}
										}
										$(
											`button[jns="kunci"] i,button[jns="setujui"] i`
										).removeClass("red");
										let buttonElm = $(`form[name="form_pengaturan"]`).find(
											`button[jns="kunci"]`
										);
										buttonElm.each(function (index, element) {
											// element == this
											let tabelPakai = $(element).attr("tbl");
											if (result?.data?.row_tahun[`kunci_${tabelPakai}`] > 0) {
												$(
													`button[jns="kunci"][tbl="${tabelPakai}"] i`
												).addClass("red");
											}
											if (
												result?.data?.row_tahun[`setujui_${tabelPakai}`] > 0
											) {
												$(
													`button[jns="setujui"][tbl="${tabelPakai}"] i`
												).addClass("red");
											}
										});
										addRulesForm(formPengaturan);
										break;
									case "xxx":
										break;
									default:
										break;
								}
								break;
							case "get_row":
								switch (tbl) {
									case "user":
										let myForm = $(`form.profil[name="profil"]`);
										let card = $(`[data-tab="profil"] .ui.card`);
										let elm = card.find(`[src]`);
										$(`span[name="nama"]`).html(result.data.users.nama);
										let imgeProfil = result.data.users.photo;
										// console.log(namaku);
										if (imgeProfil.length > 8) {
											elm.attr("src", imgeProfil);
										}
										myForm.attr("id_row", result.data.users.id);
										card
											.find(`button[for="directupload1"]`)
											.attr("id_row", result.data.users.id);
										let resultUser = result.data.users;
										for (let key in resultUser) {
											let value = resultUser[key];
											let elmTxt = myForm.find(
												`input[name="${key}"], textarea[name="${key}"]`
											);
											if (elmTxt.length > 0) {
												myForm.form("set value", key, value);
											}
										}
										break;
									default:
										break;
								}
								break;
							default:
								break;
						}
						hasKey = result.error.hasOwnProperty("message");

						if (hasKey) {
							switch (error_code) {
								case 9:
									kelasToast = "error";
									iconToast = "exclamation triangle yellow icon";
									break;
								default:
									break;
							}
						}
					} else {
						loaderHide();
					}
				} else {
					loaderHide();
					kelasToast = "error";
					iconToast = "exclamation triangle yellow icon";
				}
				showToast(result.error.message, {
					class: kelasToast,
					icon: iconToast,
				});
			};
			runAjax(
				url,
				"POST",
				data,
				"Json",
				undefined,
				undefined,
				"ajaxku",
				cryptos
			);
		}
	});
	//=====================================================
	//===========button ambil data/get_data/ flyout =======@audit-ok flyout
	//=====================================================
	$("body").on("click", '[name="flyout"],[name="get_data"]', function (e) {
		e.preventDefault();
		let ini = $(this);
		const [node] = $(this);
		const attrs = {};
		let deactivate = ini.attr("deactivate");
		$.each(node.attributes, (index, attribute) => {
			attrs[attribute.name] = attribute.value;
			let attrName = attribute.name;
			//membuat variabel
			let myVariable = attrName + "Attr";
			window[myVariable] = attribute.value;
		});
		let linkTemplate = {
			wilayah: "template/1. wilayah.xlsx",
			peraturan: "template/2. template peraturan.xlsx",
			organisasi: "template/4. Organisasi Perangkat Daerah.xlsx",
			sumber_dana: "template/5. sumber dana.xlsx",
			akun_belanja: "template/6. akun.xlsx",
			aset: "template/7. neraca 1 header.xlsx",
			mapping: "template/8. Mapping 1 header.xlsx",
			satuan: "template/9. Satuan 1 Header.xlsx",
			rekanan: "template/10. rekanan.xlsx",
			sub_keg: "template/11. Sub keg 900.xlsx",
			ssh: "template/12. ssh 2024.xlsx",
			asb: "template/13. asb 2024.xlsx",
			sbu: "template/17. sbu 2024.xlsx",
			tujuan_sasaran_renstra: "template/18. tujuan sasaran renstra.xlsx",
			renstra: "template/19. Renstra Template.xlsx",
			sub_keg_renja: "template/20. Format Sub Kegiatan Renja:DPA:DPPA.xlsx",
			sub_keg_dpa: "template/20. Format Sub Kegiatan Renja:DPA:DPPA.xlsx",
			renja: "template/21. Form Renja DPA DPPA.xlsx",
			renja_p: "template/21. Form Renja DPA DPPA.xlsx",
			dpa: "template/21. Form Renja DPA DPPA.xlsx",
			dppa: "template/21. Form Renja DPA DPPA.xlsx",
			asn: "template/26. ASN OPD DPUPR.xlsx",
		};
		let attrName = ini.attr("name");
		let jenis = ini.attr("jns");
		let tbl = ini.attr("tbl");
		if (typeof tbl === "undefined") {
			tbl = ini.closest("tr").attr("tbl");
			if (typeof tbl === "undefined") {
				tbl = ini.closest(`div[data-tab]`).attr("tbl");
				if (typeof tbl === "undefined") {
					tbl = ini.closest("div.item").attr("tbl");
				}
			}
		}
		let formIni = $('form[name="form_flyout"]');
		//removeRulesForm(formIni);
		let url = "script/get_data";
		let jalankanAjax = false;
		let htmlForm = "";
		let dataHtmlku = { konten: "" };
		dataHtmlku.icon = "plus icon";
		let iconFlyout = $('i[name="icon_flyout"]'); //icon flyout
		let headerFlyout = $('div[name="content_flyout"]'); //header flyout
		let data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
		};
		let id_row = ini.attr("id_row");
		if (typeof id_row === "undefined") {
			id_row = ini.closest("tr").attr("id_row");
			if (typeof id_row === "undefined") {
				id_row = ini.closest("div.item").attr("id_row");
			}
		}
		if (attrName === "flyout") {
			formIni.attr("jns", jenis).attr("tbl", tbl);
			switch (jenis) {
				//EDIT DATA ROWS
				case "edit":
					data.id_row = id_row;
					dataHtmlku.icon = "edit icon";
					dataHtmlku.header = "Edit data";
					jalankanAjax = true;
					if (jenis === "edit") {
						data.id_row = id_row;
						jalankanAjax = true;
						formIni.attr("id_row", id_row);
						dataHtmlku.icon = "edit icon";
						dataHtmlku.header = "Edit Data";
					}
					//TAMBAH ROWS DATA
					switch (tbl) {
						case "asn":
							dataHtmlku.konten = createHTML("card3", {
								label: "Aparatur Sipil Negara (ASN)",
								atribut: `for="directupload1" name="direct" type="button" id_row="${id_row}" jns="upload" tbl="asn" dok="file_photo" accept=".jpg,.png,.jpeg,.img"`,
							});
							break;
						default:
							break;
					}
				case "add":
					switch (tbl) {
						case "register_surat":
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Kategori klasifikasi keamanan",
									atribut: 'name="klasifikasi_keamanan"',
									kelas: "lainnya selection",
									dataArray: [
										["sr", "sangat rahasia"],
										["r", "Rahasia"],
										["t", "Terbatas"],
										["b", "biasa/terbuka"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Jenis Naskah Dinas",
									atribut: 'name="jenis_naskah_dinas"',
									kelas: "selection",
									dataArray: [
										["arahan", "Naskah Dinas arahan"],
										["korespondensi", "Naskah Dinas korespondensi"],
										["khusus", "Naskah Dinas khusus"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Sifat Naskah Dinas",
									atribut: 'name="sifat"',
									kelas: "lainnya selection",
									dataArray: [],
								}) +
								createHTML("fieldDropdown", {
									label: "Sub Jenis Naskah Dinas",
									atribut: 'name="sub_sifat"',
									kelas: "lainnya selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									label: "Nomor Surat",
									atribut: 'name="nomor" placeholder="Nomor Surat"',
								}) +
								createHTML("fieldCalendar", {
									label: "Tanggal",
									atribut:
										'placeholder="Input tanggal surat.." name="tanggal" readonly',
									kelas: "date",
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian",
									atribut: 'name="uraian" rows="2"',
								}) +
								createHTML("divider", {
									header: "h4",
									aligned: "left aligned",
									label: `Sumber Surat`,
								}) +
								createHTML("fieldText", {
									label: "Asal Surat",
									atribut: 'name="asal_surat" placeholder="Asal Surat"',
								}) +
								createHTML("fieldText", {
									label: "Nama Lengkap",
									atribut: 'name="nama_lengkap" placeholder="Nama Lengkap"',
								}) +
								createHTML("fieldText", {
									label: "Jabatan",
									atribut: 'name="jabatan" placeholder="Jabatan" non_data',
								}) +
								createHTML("fieldText", {
									label: "Pangkat",
									atribut: 'name="pangkat" placeholder="Pangkat" non_data',
								}) +
								createHTML("fieldText", {
									label: "Nip.",
									atribut: 'name="nip" placeholder="NIP (jika ada)" non_data',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
								}) +
								createHTML("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									atribut: "",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx",
								}) +
								createHTML("fielToggleCheckbox", {
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "berita":
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Kelompok Berita",
									atribut: 'name="kelompok"',
									kelas: "lainnya selection",
									dataArray: [
										["berita", "Berita"],
										["pelayanan", "Pelayanan"],
										["data_teknis", "Data Teknis"],
										["organisasi", "Organisasi"],
										["anggaran", "Anggaran"],
									],
								}) +
								createHTML("fieldText", {
									label: "Nomor Urut",
									atribut:
										'name="urutan" placeholder="Nomor Urutan Berita(101..)" rms',
								}) +
								createHTML("fieldCalendar", {
									label: "Tanggal",
									atribut:
										'placeholder="Input tanggal Berita.." name="tanggal" readonly',
									kelas: "date",
								}) +
								createHTML("fieldTextarea", {
									label: "Judul Berita",
									atribut: 'name="judul" rows="2"',
								}) +
								createHTML("fieldTextarea", {
									labelTambahan: ` <button class="ui circular right aligned blue horizontal label button" name="modal_show" tbl="berita" jns="modal_show" klm="uraian_html"> tampilkan</button>`,
									label: "Markup HTML",
									atribut: 'name="uraian_html" rows="20"',
								}) +
								createHTML("fieldTextarea", {
									labelTambahan: ` <button class="ui circular right aligned blue horizontal label button" name="modal_show" tbl="berita" jns="modal_show" klm="uraian_singkat"> tampilkan</button>`,
									label: "Uraian Singkat",
									atribut: 'name="uraian_singkat" rows="4"',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "users":
							dataHtmlku.konten =
								createHTML("fieldText", {
									label: "Nama Lengkap",
									atribut:
										'name="nama" placeholder="Nama Lengkap (tanpa gelar)"',
								}) +
								createHTML("fieldTextAction", {
									label: "Nomor Induk Pegawai",
									atribut: 'name="nip" placeholder="NIP"',
									txtLabel: `<i class="search icon"></i>`,
									atributLabel: `name="get_data" type="button" jns="get_data" tbl="user" klm="nip"`,
								}) +
								createHTML("fieldTextAction", {
									label: "username",
									atribut: 'name="username" placeholder="username"',
									txtLabel: `<i class="search icon"></i>`,
									atributLabel: `name="get_data" type="button" jns="get_data" tbl="user" klm="username"`,
								}) +
								createHTML("fieldText", {
									label: "email",
									atribut: 'name="email" placeholder="email" readonly',
								}) +
								createHTML("fieldDropdown", {
									label: "Type User",
									atribut: 'name="type_user"',
									kelas: "lainnya selection",
									dataArray: [
										["user", "User"],
										["admin", "Admin"],
										["pa", "Pengguna Anggaran"],
										["super", "Super Administrator"],
									],
								}) +
								createHTML("fieldText", {
									label: "Kontak Person",
									atribut: 'name="kontak_person" placeholder="Kontak Person"',
								}) +
								createHTML("fieldText", {
									label: "Alamat",
									atribut: 'name="alamat" placeholder="Alamat..."',
								}) +
								createHTML("fieldDropdown", {
									label: "Nama Wilayah",
									atribut: 'name="kd_wilayah"',
									kelas: "search ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldDropdown", {
									label: "Organisasi Perangkat Daerah",
									atribut: 'name="kd_organisasi"',
									kelas: "search ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldCalendar", {
									label: "Tahun Anggaran Aktif",
									kelas: "year",
									atribut: 'placeholder="Tanggal.." name="tahun" readonly',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="ket" rows="2" non_data',
								}) +
								createHTML("labelToggleCheckbox", {
									label: "Non Aktifkan User Login",
									atribut: 'name="disable_login" non_data',
									txtLabel: "Disable",
								}) +
								createHTML("labelToggleCheckbox", {
									label: "Non Aktifkan User Input/Edit Anggaran",
									atribut: 'name="disable_anggaran" non_data',
									txtLabel: "Disable",
								}) +
								createHTML("labelToggleCheckbox", {
									label: "Non Aktifkan User Input/Edit Kontrak",
									atribut: 'name="disable_kontrak" non_data',
									txtLabel: "Disable",
								}) +
								createHTML("labelToggleCheckbox", {
									label: "Non Aktifkan User Input/Edit Realisasi",
									atribut: 'name="disable_realisasi" non_data',
									txtLabel: "Disable",
								}) +
								createHTML("labelToggleCheckbox", {
									label: "Non Aktifkan User Untuk Chat",
									atribut: 'name="disable_chat" non_data',
									txtLabel: "Disable",
								});
							break;
						case "asn":
							dataHtmlku.konten +=
								createHTML("fieldText", {
									label: "Nama Lengkap (tanpa gelar)",
									atribut:
										'name="nama" placeholder="Nama Lengkap (tanpa gelar)"',
								}) +
								createHTML("fieldTextAction", {
									label: "Nomor Induk Pegawai",
									atribut: 'name="nip" placeholder="NIP"',
									txtLabel: `<i class="search icon"></i>`,
									atributLabel: `name="get_data" type="button" jns="get_data" tbl="asn" klm="nip"`,
								}) +
								createHTML("fieldText", {
									label: "Gelar",
									atribut:
										'name="gelar" placeholder="Gelar di belakang nama" non_data',
								}) +
								createHTML("fieldText", {
									label: "Gelar Depan Nama",
									atribut:
										'name="gelar_depan" placeholder="Gelar di depan nama" non_data',
								}) +
								createHTML("fieldDropdown", {
									label: "Kelompok Jabatan",
									atribut: 'name="kelompok"',
									kelas: "lainnya selection",
									dataArray: [
										["1", "Kepala OPD"],
										["2", "Sekretaris"],
										["3", "Kepala Bidang"],
										["4", "ASN"],
										["5", "non ASN"],
									],
								}) +
								createHTML("fieldText", {
									label: "Jabatan",
									atribut: 'name="jabatan" placeholder="Jabatan..."',
								}) +
								createHTML("fieldText", {
									label: "Tempat Lahir",
									atribut:
										'name="t4_lahir" placeholder="tempat lahir" non_data',
								}) +
								createHTML("fieldCalendar", {
									label: "Tanggal Lahir",
									atribut:
										'placeholder="Input tanggal lahir.." name="tgl_lahir" readonly',
									kelas: "date",
								}) +
								createHTML("fieldDropdown", {
									label: "Golongan",
									atribut: 'name="golongan"',
									kelas: "lainnya selection",
									dataArray: [
										["1", "I"],
										["2", "II"],
										["3", "III"],
										["4", "IV"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Ruang",
									atribut: 'name="ruang"',
									kelas: "lainnya selection",
									dataArray: [
										["a", "a"],
										["b", "b"],
										["c", "c"],
										["d", "d"],
										["e", "e"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Jenis Kepegawaian",
									atribut: 'name="jenis_kepeg"',
									kelas: "lainnya selection",
									dataArray: [
										["pnsp", "ASN pusat"],
										["pnsd1", "ASN Provinsi"],
										["pnsd2", "ASN Kabupaten/Kota"],
										["pnsp_dpb1", "ASN Pusat diperbantukan Provinsi"],
										["pnsp_dpb2", "ASN Pusat diperbantukan Kab./Kota"],
										["pnsp_dpk1", "ASN Pusat dipekerjakan Provinsi"],
										["pnsp_dpk2", "ASN Pusat dipekerjakan Kab./Kota"],
										["pnsd_dpb_pusat", "ASN Daerah diperbantukan Pusat"],
										["pnsd_dpk_pusat", "ASN Daerah dipekerjakan Pusat"],
										["swasta", "Swasta"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Status Kepegawaian",
									atribut: 'name="status_kepeg"',
									kelas: "lainnya selection",
									dataArray: [
										["capeg", "Calon Pegawai"],
										["peg_tetap", "ASN/Pegawai tetap"],
										["mpp", "Masa Persiapan Pensiun"],
										["pen_uang_tunggu", "Pensiunan"],
										["peg_seorsing", "Pegawai Seorsing"],
										["cuti", "Cuti"],
										["peg_sementara", "Pegawai Sementara"],
										["peg_bulanan", "Pegawai Bulanan"],
									],
								}) +
								createHTML("fieldText", {
									label: "Nomor KTP",
									atribut: 'name="no_ktp" placeholder="Nomor ktp..."',
								}) +
								createHTML("fieldText", {
									label: "NPWP",
									atribut: 'name="npwp" placeholder="NPWP..."',
								}) +
								createHTML("fieldText", {
									label: "Alamat",
									atribut: 'name="alamat" placeholder="Alamat..."',
								}) +
								createHTML("fieldText", {
									label: "Kontak Person",
									atribut:
										'name="kontak_person" placeholder="Kontak Person..."',
								}) +
								createHTML("fieldText", {
									label: "email",
									atribut: 'name="email" placeholder="email..."',
								}) +
								createHTML("fieldDropdown", {
									label: "Agama",
									atribut: 'name="agama"',
									kelas: "lainnya selection",
									dataArray: [
										["islam", "Islam"],
										["kristen", "Kristen"],
										["katolik", "Katolik"],
										["protestan", "Protestan"],
										["hindu", "Hindu"],
										["budha", "Budha"],
										["konghucu", "Konghucu"],
										["yahudi", "Yahudi"],
										["kepercayaan", "Kepercayaan Tuhan YME."],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Kelamin",
									atribut: 'name="kelamin"',
									kelas: "lainnya selection",
									dataArray: [
										["pria", "Pria"],
										["wanita", "Wanita"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Status",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["menikah", "Menikah"],
										["janda-duda", "Duda-Janda"],
										["lajang", "Lajang"],
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "daftar_paket":
							dataHtmlku.konten =
								createHTML("fieldTextAction", {
									label: "Uraian Belanja",
									atribut:
										'name="count_uraian_belanja" placeholder="Nama Paket..." readonly',
									txtLabel: `<i class="search icon"></i>`,
									atributLabel: `name="modal_show" jns="uraian_belanja" tbl="${tbl}"`,
								}) +
								//text ini di hilangkan untuk menampung id yang dipilih di form_modal
								createHTML("text", {
									atributField: 'name="id_uraian" hidden',
									atribut:
										'name="id_uraian" placeholder="Pilih Uraian Belanja"',
								}) +
								createHTML("fieldTextarea", {
									label: "Nama Paket",
									atribut:
										'name="uraian" rows="2" placeholder="Uraian Barang/Jasa..."',
								}) +
								createHTML("fieldText", {
									label: "Volume",
									atribut: 'name="volume" placeholder="volume output..." rms',
								}) +
								createHTML("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan"',
									kelas: "search clearable satuan ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									label: "Nilai Pagu",
									atribut:
										'name="pagu" placeholder="Nilai Pagu..." rms readonly',
								}) +
								createHTML("fieldText", {
									label: "Nilai Kontrak",
									atribut:
										'name="jumlah" placeholder="Nilai Kontrak..." rms readonly',
								}) +
								createHTML("fieldTextarea", {
									label: "Output Rencana",
									atribut:
										'name="renc_output" rows="2" placeholder="Output Rencana"',
								}) +
								createHTML("fieldTextarea", {
									label: "Output",
									atribut:
										'name="output" rows="2" placeholder="output..." non_data',
								}) +
								createHTML("fieldText", {
									label: "Nama PPK",
									atribut:
										'name="nama_ppk" placeholder="Nama Lengkap PPK..." non_data',
								}) +
								createHTML("fieldText", {
									label: "NIP. PPK",
									atribut: 'name="nip_ppk" placeholder="NIP. PPK..." non_data',
								}) +
								createHTML("fieldText", {
									label: "Nama PPTK",
									atribut:
										'name="nama_pptk" placeholder="Nama Lengkap PPTK..." non_data',
								}) +
								createHTML("fieldText", {
									label: "NIP. PPTK",
									atribut:
										'name="nip_pptk" placeholder="NIP. PPTK..." non_data',
								}) +
								createHTML("fieldDropdown", {
									label: "Rekanan",
									atribut: 'name="id_rekanan" non_data',
									kelas: "search clearable rekanan ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldDropdown", {
									label: "Metode Pengadaan",
									atribut: 'name="metode_pengadaan"',
									kelas: "selection",
									dataArray: [
										["swakelola", "Swakelola"],
										["penyedia", "Penyedia"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Metode Pemilihan",
									atribut: 'name="metode_pemilihan"',
									kelas: "lainnya selection",
									dataArray: [
										["e_purchasing", "e-purchasing"],
										["pengadaan_langsung", "pengadaan langsung"],
										["penunjukan", "penunjukan langsung"],
										["tender_cepat", "tender cepat"],
										["tender", "tender"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Pengadaan Barang Jasa",
									atribut: 'name="pengadaan_penyedia"',
									kelas: "lainnya selection",
									dataArray: [
										["barang", "Barang"],
										["konstruksi", "Pekerjaan Konstruksi"],
										["konsultansi", "Jasa Konsultansi"],
										[
											"konsultansi_non_konst",
											"Jasa Konsultansi Non Konstruksi",
										],
										["jasa_lainnya", "Jasa Lainnya"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Jenis Kontrak",
									atribut: 'name="jns_kontrak"',
									kelas: "lainnya selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									label: "Waktu Pelaksanaan",
									atribut:
										'name="waktu_pelaksanaan" placeholder="Waktu Pelaksanaan..." rms non_data',
								}) +
								createHTML("fieldText", {
									label: "Waktu Pemeliharaan (jika dibutuhkan)",
									atribut:
										'name="waktu_pemeliharaan" placeholder="Pemeliharaan jika diperlukan..." rms non_data',
								}) +
								createHTML("accordionField", {
									label: "Jadwal Pengadaan",
									content:
										createHTML("text", {
											atribut: `name="kd_rup" placeholder="Kode RUP" non_data`,
										}) +
										createHTML("text", {
											atribut: `name="kd_paket" placeholder="Kode Paket" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_kontrak" placeholder="Tanggal Kontrak" non_data`,
											kelas: "date",
										}) +
										createHTML("text", {
											atribut: `name="no_kontrak" placeholder="Nomor Kontrak" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_persiapan_kont" placeholder="Tanggal Persiapan Kontrak" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_persiapan_kont" placeholder="Nomor Persiapan Kontrak" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_spmk" placeholder="Tanggal SPMK" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_spmk" placeholder="Nomor Persiapan Kontrak" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_undangan" placeholder="Tanggal Undangan/Pengumuman" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_undangan" placeholder="Nomor Undangan/Pengumuman" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_penawaran" placeholder="Tanggal Penawaran" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_penawaran" placeholder="Nomor Penawaran" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_nego" placeholder="Tanggal Negoisasi" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_nego" placeholder="Nomor Negoisasi" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_sppbj" placeholder="Tanggal SPPBJ" non_data`,
											kelas: "date",
										}) +
										createHTML("text", {
											atribut: `name="no_sppbj" placeholder="Nomor SPPBJ" non_data`,
										}),
								}) +
								createHTML("accordionField", {
									label: "Serah Terima Pengadaan",
									content:
										createHTML("calendar", {
											atribut: `name="tgl_pho" placeholder="Tanggal PHO" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_pho" placeholder="Nomor PHO" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tgl_fho" placeholder="Tanggal FHO" non_data`,
											kelas: "datetime",
										}) +
										createHTML("text", {
											atribut: `name="no_fho" placeholder="Nomor FHO" non_data`,
										}),
								}) +
								createHTML("accordionField", {
									label: "Tambah Kontrak Addendum",
									content: createHTML("button", {
										kelas: `fluid animated fade`,
										atribut: `name="add" jns="direct" tbl="addendum"`,
										value: `<div class="visible content">Tambah</div><div class="hidden content">Addendum Kontrak</div>`,
									}),
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="3" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "dppa":
						case "renja_p":
							var vol_1 = "vol_1_p";
							var vol_2 = "vol_2_p";
							var vol_3 = "vol_3_p";
							var vol_4 = "vol_4_p";
							var vol_5 = "vol_5_p";
							var sat_1 = "sat_1_p";
							var sat_2 = "sat_2_p";
							var sat_3 = "sat_3_p";
							var sat_4 = "sat_4_p";
							var sat_5 = "sat_5_p";
							var volumeku = "volume_p";
							var jumlahku = "jumlah_p";
							var sumber_danaku = "sumber_dana_p";
						case "dpa":
						case "renja":
							if (tbl === "dpa" || tbl === "renja") {
								vol_1 = "vol_1";
								vol_2 = "vol_2";
								vol_3 = "vol_3";
								vol_4 = "vol_4";
								vol_5 = "vol_5";
								sat_1 = "sat_1";
								sat_2 = "sat_2";
								sat_3 = "sat_3";
								sat_4 = "sat_4";
								sat_5 = "sat_5";
								volumeku = "volume";
								jumlahku = "jumlah";
								sumber_danaku = "sumber_dana";
							}
							if (jenis === "edit") {
								data.id_sub_keg = id_sub_kegAttr;
							}
							formIni.attr("id_sub_keg", ini.attr("id_sub_keg"));
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Objek Belanja",
									classField: `required`,
									atribut:
										'name="objek_belanja" placeholder="pilih objek belanja..."',
									kelas: "search lainnya selection",
									dataArray: [
										["gaji", "Belanja Gaji dan Tunjangan ASN"],
										[
											"barang_jasa_modal",
											"Belanja Barang Jasa dan Modal",
											"active",
										],
										["bunga", "Belanja Bunga"],
										["subsidi", "Belanja Subsidi"],
										["hibah_barang_jasa", "Belanja Hibah (Barang/Jasa)"],
										["hibah_uang", "Belanja Hibah (Uang)"],
										[
											"sosial_barang_jasa",
											"Belanja Bantuan Sosial (Barang/Jasa)",
										],
										["sosial_uang", "Belanja Bantuan Sosial (Uang)"],
										["keuangan_umum", "Belania Bantuan Keuangan Umum"],
										["keuangan_khusus", "Belanja Bantuan Keuangan Khusus"],
										["btt", "Belanja Tidak Terduga (BTT)"],
										["bos_pusat", "Dana BOS (BOS Pusat)"],
										["blud", "Belanja Operasional (BLUD)"],
										["lahan", "Pembebasan Tanah/ Lahan"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Rekening / Akun",
									classField: `required`,
									atribut:
										'name="kd_akun" placeholder="pilih rekening/akun..."',
									kelas: "search clearable kd_akun ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldDropdown", {
									label: "Pengelompokan Belanja",
									classField: `required`,
									atribut:
										'name="jenis_kelompok" placeholder="pilih pengelompokan..."',
									kelas: "search clearable lainnya selection",
									dataArray: [
										["paket", "Pemaketan Kerja"],
										["kelompok", "Pengelompokan Belanja"],
									],
								}) +
								createHTML("fieldDropdownLabel", {
									label: "Uraian Pengelompokan Belanja",
									txtLabel: '<i class="plus icon"></i>',
									classField: `required`,
									atributLabel: `name="modal_show" jns="add_field_json" tbl="sub_keg_${tbl}" klm="kelompok_json"`,
									atribut:
										'name="kelompok" placeholder="pilih uraian kelompok..."',
									kelas: "search kelompok ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldDropdown", {
									label: "Sumber Dana",
									classField: `required`,
									atribut: `name="${sumber_danaku}" placeholder="pilih sumber dana..."`,
									kelas: "search clearable multiple sumber_dana ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldDropdown", {
									label: "Jenis Standar Harga",
									classField: `required`,
									atribut:
										'name="jenis_standar_harga" placeholder="jenis standar harga..."',
									kelas: "selection",
									dataArray: [
										["ssh", "SSH"],
										["sbu", "SBU"],
										["hspk", "HSPK"],
										["asb", "ASB"],
									],
								}) +
								createHTML("fieldDropdownLabel", {
									label: "Komponen",
									txtLabel: '<i class="search icon"></i>',
									classField: `required`,
									atributLabel: `name="modal_show" jns="search_field_json" tbl=""`,
									atribut: 'name="komponen" placeholder="pilih komponen..."',
									kelas: "search clearable komponen ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									label: "TKDN",
									kelas: "",
									atribut:
										'name="tkdn" placeholder="tkdn..." rms non_data readonly',
								}) +
								createHTML("fieldText", {
									label: "Spesifikasi Komponen",
									kelas: "",
									atribut:
										'name="spesifikasi" placeholder="spesifikasi..." non_data readonly',
								}) +
								createHTML("fieldText", {
									label: "Satuan Komponen",
									kelas: "",
									atribut:
										'name="satuan" placeholder="satuan komponen..." non_data readonly',
								}) +
								createHTML("fieldText", {
									label: "Harga Satuan Komponen",
									kelas: "",
									atribut:
										'name="harga_satuan" placeholder="harga satuan..." rms non_data readonly',
								}) +
								createHTML("fieldDropdownLabel", {
									label: "Keterangan",
									txtLabel: '<i class="plus icon"></i>',
									classField: `required`,
									atributLabel: `name="modal_show" jns="add_field_json" tbl="sub_keg_${tbl}" klm="keterangan_json"`,
									atribut: 'name="uraian" placeholder="pilih keterangan..."',
									kelas: "search clearable uraian ajx selection",
									dataArray: [["", ""]],
								}) +
								createHTML("fielToggleCheckbox", {
									label: "Tambahkan Pajak",
									atribut: 'name="pajak" non_data',
								}) +
								createHTML("fields2", {
									label: "Koefisien Perkalian",
									kelas: "disabled",
									kelas2: [
										"search sat_1 ajx selection",
										"search sat_2 ajx selection",
										"search sat_3 ajx selection",
										"search sat_4 ajx selection",
									],
									classField: `required`,
									atribut: [
										`name="${sat_1}" placeholder="satuan..."`,
										`name="${sat_2}" placeholder="satuan..." non_data`,
										`name="${sat_3}" placeholder="satuan..." non_data`,
										`name="${sat_4}" placeholder="satuan..." non_data`,
									],
									atribut2: [
										`name="${vol_1}" placeholder="Koefisien..." rms onkeypress="ketikUbah(event);"`,
										`name="${vol_2}" placeholder="Koefisien..." non_data rms onkeypress="ketikUbah(event);"`,
										`name="${vol_3}" placeholder="Koefisien..." non_data rms onkeypress="ketikUbah(event);"`,
										`name="${vol_4}" placeholder="Koefisien..." non_data rms onkeypress="ketikUbah(event);"`,
									],
								}) +
								createHTML("fieldText", {
									label: "Volume",
									classField: `required`,
									kelas: "",
									atribut: `name="${volumeku}" placeholder="volume..." rms readonly`,
								}) +
								createHTML("fieldText", {
									label: "Koefisien (Keterangan Jumlah)",
									kelas: "",
									atribut:
										'name="koef_ket" placeholder="keterangan jumlah..." non_data readonly',
								}) +
								createHTML("fieldText", {
									label: "Total Belanja",
									kelas: "",
									atribut: `name="${jumlahku}" placeholder="jumlah..." rms non_data readonly`,
								}) +
								createHTML("fieldTextarea", {
									label: "Catatan",
									atribut: 'name="keterangan" rows="2" non_data',
								});
							break;
						case "sub_keg_dpa":
						case "sub_keg_renja":
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Sub Kegiatan",
									atribut:
										'name="kd_sub_keg" placeholder="pilih sub kegiatan..."',
									kelas: "search clearable kd_sub_keg ajx selection",
									dataArray: [],
								}) +
								createHTML("accordionField", {
									label: "Indikator dan Tolok Ukur Kinerja Kegiatan",
									content:
										createHTML("fieldTextarea", {
											label: "Tolak Ukur Kinerja Capaian Kegiatan",
											atribut:
												'name="tolak_ukur_capaian_keg" rows="2" placeholder="tolak ukur capaian kegiatan..." non_data',
										}) +
										createHTML("fieldText", {
											label: "Target Kinerja Capaian Kegiatan",
											atribut:
												'name="target_kinerja_capaian_keg" placeholder="target kinerja capaian keg..." non_data',
										}) +
										createHTML("fieldTextarea", {
											label: "Tolak Ukur Kinerja Keluaran",
											atribut:
												'name="tolak_ukur_keluaran" rows="2" placeholder="tolak ukur keluaran..." non_data',
										}) +
										createHTML("fieldText", {
											label: "Target Kinerja Keluaran",
											atribut:
												'name="target_kinerja_capaian_keg" placeholder="target kinerja keluaran..." non_data',
										}) +
										createHTML("fieldTextarea", {
											label: "Tolak Ukur Kinerja Hasil",
											atribut:
												'name="tolak_ukur_hasil" rows="2" placeholder="tolak ukur hasil..." non_data',
										}) +
										createHTML("fieldText", {
											label: "Target Kinerja Hasil",
											atribut:
												'name="target_kinerja_hasil" placeholder="target kinerja hasil..." non_data',
										}) +
										createHTML("fieldTextarea", {
											label: "Keluaran Sub Kegiatan",
											atribut:
												'name="keluaran_sub_keg" rows="2" placeholder="keluaran sub keg..." non_data',
										}),
								}) +
								createHTML("accordionField", {
									label: "Indikator dan Tolok Ukur Kinerja Kegiatan Perubahan",
									content:
										createHTML("fieldTextarea", {
											label: "Tolak Ukur Kinerja Capaian Kegiatan",
											atribut:
												'name="tolak_ukur_capaian_keg_p" rows="2" placeholder="tolak ukur capaian kegiatan..." non_data',
										}) +
										createHTML("fieldText", {
											label: "Target Kinerja Capaian Kegiatan",
											atribut:
												'name="target_kinerja_capaian_keg_p" placeholder="target kinerja capaian keg..." non_data',
										}) +
										createHTML("fieldTextarea", {
											label: "Tolak Ukur Kinerja Keluaran",
											atribut:
												'name="tolak_ukur_keluaran_p" rows="2" placeholder="tolak ukur keluaran..." non_data',
										}) +
										createHTML("fieldText", {
											label: "Target Kinerja Keluaran",
											atribut:
												'name="target_kinerja_capaian_keg_p" placeholder="target kinerja keluaran..." non_data',
										}) +
										createHTML("fieldTextarea", {
											label: "Tolak Ukur Kinerja Hasil",
											atribut:
												'name="tolak_ukur_hasil_p" rows="2" placeholder="tolak ukur hasil..." non_data',
										}) +
										createHTML("fieldText", {
											label: "Target Kinerja Hasil",
											atribut:
												'name="target_kinerja_hasil_p" placeholder="target kinerja hasil..." non_data',
										}) +
										createHTML("fieldTextarea", {
											label: "Keluaran Sub Kegiatan",
											atribut:
												'name="keluaran_sub_keg_p" rows="2" placeholder="keluaran sub keg..." non_data',
										}),
								}) +
								createHTML("fieldDropdown", {
									label: "Sumber Dana",
									atribut:
										'name="sumber_dana" placeholder="pilih sumber dana..."',
									kelas: "search clearable multiple sumber_dana ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									label: "Jumlah Pagu",
									atribut:
										'name="jumlah_pagu" placeholder="jumlah (perencanaan)..." rms',
								}) +
								createHTML("fieldText", {
									label: "Jumlah Pagu Perubahan",
									atribut:
										'name="jumlah_pagu_p" placeholder="jumlah (perencanaan)..." rms',
								}) +
								createHTML("fieldText", {
									label: "Lokasi",
									atribut: 'name="lokasi" placeholder="lokasi..." non_data',
								}) +
								createHTML("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "tujuan_renstra":
							let kelompok = (tbl = "tujuan_renstra") ? "tujuan" : "sasaran";
							dataHtmlku.konten =
								createHTML("fieldText", {
									label: "Kelompok",
									atribut: `name="kelompok" placeholder="Kelompok..." value="${kelompok}" disabled`,
								}) +
								createHTML("fieldTextarea", {
									label: `Uraian ${kelompok}`,
									atribut: 'name="text" rows="4" placeholder="uraian..."',
								}) +
								createHTML("fieldTextarea", {
									label: `Indikator`,
									atribut:
										'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								createHTML("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "sasaran_renstra":
							kelompok = (tbl = "tujuan_renstra") ? "tujuan" : "sasaran";
							dataHtmlku.konten =
								createHTML("fieldText", {
									label: "Kelompok",
									atribut: `name="kelompok" placeholder="Kelompok..." value="${kelompok}" disabled`,
								}) +
								createHTML("fieldTextarea", {
									label: `Tujuan ${kelompok}`,
									atribut:
										'name="tujuan" rows="4" placeholder="Tujuan..." disabled',
								}) +
								createHTML("fieldTextarea", {
									label: `Uraian ${kelompok}`,
									atribut: 'name="text" rows="4" placeholder="uraian..."',
								}) +
								createHTML("fieldTextarea", {
									label: `Indikator`,
									atribut:
										'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								createHTML("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "tujuan_sasaran_renstra":
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Kelompok",
									atribut: 'name="kelompok"',
									kelas: "tujuan_sasaran selection",
									dataArray: [
										["tujuan", "Tujuan"],
										["sasaran", "Sasaran"],
									],
								}) +
								createHTML("fieldDropdown", {
									label: "Tujuan",
									atributField: "hidden",
									atribut: 'name="id_tujuan" non_data',
									kelas: "search clearable tujuan_renstra ajx selection",
									dataArray: [
										["", ""],
										["", ""],
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian",
									atribut:
										'name="text" rows="4" placeholder="uraian..." autofocus',
								}) +
								createHTML("fieldTextarea", {
									label: `Indikator`,
									atribut:
										'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								createHTML("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "renstra":
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Sasaran",
									atribut: 'name="sasaran"',
									kelas: "search clearable sasaran_renstra ajx selection",
									dataArray: [["", ""]],
								}) +
								createHTML("fieldDropdown", {
									label: "Sub Kegiatan",
									atribut:
										'name="kd_sub_keg" placeholder="pilih sub kegiatan..."',
									kelas: "search clearable kode ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldTextarea", {
									label: "Indikator",
									atribut:
										'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								createHTML("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan" placeholder="pilih satuan..."',
									kelas: "search clearable satuan ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									// typeText: 'number',
									label: "Data Capaian Awal",
									atribut:
										'name="data_capaian_awal" placeholder="data capaian awal..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Target tahun 1",
									atribut:
										'name="target_thn_1" placeholder="target tahun I..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Dana tahun 1",
									atribut:
										'name="dana_thn_1" placeholder="Dana tahun I..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Target tahun 2",
									atribut:
										'name="target_thn_2" placeholder="target tahun II..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Dana tahun 2",
									atribut:
										'name="dana_thn_2" placeholder="Dana tahun II..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Target tahun 3",
									atribut:
										'name="target_thn_3" placeholder="target tahun III..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Dana tahun 3",
									atribut:
										'name="dana_thn_3" placeholder="Dana tahun III..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Target tahun 4",
									atribut:
										'name="target_thn_4" placeholder="target tahun IV..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Dana tahun 4",
									atribut:
										'name="dana_thn_4" placeholder="Dana tahun IV..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Target tahun 5",
									atribut:
										'name="target_thn_5" placeholder="target tahun V..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Dana tahun 5",
									atribut:
										'name="dana_thn_5" placeholder="Dana tahun V..." non_data rms',
								}) +
								createHTML("fieldText", {
									label: "Lokasi",
									atribut: 'name="lokasi" placeholder="lokasi..." non_data',
								}) +
								createHTML("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "rekanan":
							dataHtmlku.konten =
								createHTML("fieldTextAction", {
									label: "Nama Perusahaan",
									txtLabel: `<i class="search icon"></i>`,
									atribut:
										'name="nama_perusahaan" placeholder="Nama Perusahaan..."',
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								createHTML("fieldText", {
									label: "Alamat",
									atribut: 'name="alamat" placeholder="Alamat..."',
								}) +
								createHTML("fieldText", {
									label: "NPWP",
									atribut: 'name="npwp" placeholder="NPWP..."',
								}) +
								createHTML("fieldText", {
									label: "Nama Pemilik Penanda Tangan Perjanjian",
									atribut: 'name="direktur" placeholder="Direktur..."',
								}) +
								createHTML("fieldText", {
									label: "Jabatan Penanda Tangan Perjanjian",
									atribut:
										'name="jabatan" placeholder="Jabatan Penanda Tangan Perjanjian..."',
								}) +
								createHTML("fieldText", {
									label: "No. KTP Direktur",
									atribut: 'name="no_ktp" placeholder="KTP Direktur..."',
								}) +
								createHTML("fieldText", {
									label: "Alamat Pemilik",
									atribut: 'name="alamat_dir" placeholder="Alamat Pemilik..."',
								}) +
								createHTML("fieldText", {
									label: "No. Akta Pendirian",
									atribut:
										'name="no_akta_pendirian" placeholder="No. Akta pendirian..."',
								}) +
								createHTML("fieldCalendar", {
									label: "Tanggal Notaris Pendirian",
									kelas: "date",
									atribut:
										'placeholder="Tanggal.." name="tgl_akta_pendirian" readonly',
								}) +
								createHTML("fieldText", {
									label: "Alamat Notaris",
									atribut:
										'name="lokasi_notaris_pendirian" placeholder="Alamat Notaris pendirian..."',
								}) +
								createHTML("fieldText", {
									label: "Notaris",
									atribut:
										'name="nama_notaris_pendirian" placeholder="Nama Notaris pendirian..."',
								}) +
								createHTML("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
									atribut: 'non_data name="file"',
								}) +
								createHTML("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								createHTML("accordionField", {
									label: "Akta Notaris Perubahan",
									content:
										createHTML("text", {
											atribut: `name="nomor[1]" placeholder="Nomor" non_data`,
										}) +
										createHTML("calendar", {
											atribut: `name="tanggal[1]" placeholder="Tanggal" non_data`,
											kelas: "date", //atribut: `data-name='["satu", "tanggal"]' placeholder="Tanggal" non_data`, kelas: "date"
										}) +
										createHTML("text", {
											atribut: `name="alamat_notaris[1]" placeholder="Alamat" non_data`,
										}) +
										createHTML("text", {
											atribut: `name="notaris[1]" placeholder="Notaris" non_data`,
										}),
									atribut: 'name="notaris_perubahan"',
								}) +
								createHTML("accordionField", {
									label: "Pelaksana",
									content:
										createHTML("text", {
											atribut: `name="pelaksana[nama][1]" placeholder="Nama" non_data`, //gunakan object $(elm).data('name') jquery
										}) +
										createHTML("text", {
											atribut: `name="pelaksana[jabatan][1]" placeholder="Jabatan" non_data`,
										}),
									atribut: 'name="data_lain"',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							if (tbl === "input") {
								dataHtmlku.icon = "plus icon";
								dataHtmlku.header = "Tambah data";
							}
							break;
						case "hspk":
						case "ssh":
						case "sbu":
						case "asb":
							dataHtmlku.konten =
								createHTML("fieldDropdown", {
									label: "Kode Kelompok Barang/Jasa",
									atribut: 'name="kd_aset"',
									kelas: "search clearable aset ajx selection",
									dataArray: [["1.1.12.01.01.0010", "Isi Tabung Gas"]],
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian Barang/Jasa",
									atribut:
										'name="uraian_barang" rows="2" placeholder="Uraian Barang/Jasa..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Spesifikasi",
									atribut:
										'name="spesifikasi" rows="2" placeholder="Spesifikasi..."',
								}) +
								createHTML("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan"',
									kelas: "search clearable ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldText", {
									label: "Harga Satuan",
									atribut:
										'name="harga_satuan" placeholder="harga satuan..." rms',
								}) +
								createHTML("fieldText", {
									label: "TKDN",
									atribut: 'name="tkdn" placeholder="tkdn..." rms non_data',
								}) +
								createHTML("fieldDropdown", {
									label: "Mapping Kode Akun dan Belanja",
									classField: `required`,
									atribut:
										'name="kd_akun" placeholder="pilih rekening/akun..."',
									kelas: "search clearable multiple kd_akun ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="3" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "bidang_urusan":
						case "prog":
						case "keg":
						case "sub_keg":
							dataHtmlku.konten =
								createHTML("multiFieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="get_data" tbl="${tbl}"`,
									dataArray: [
										'name="urusan"',
										'name="bidang"',
										'name="prog"',
										'name="keg"',
										'name="sub_keg"',
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Nomenklatur Urusan",
									atribut:
										'name="nomenklatur_urusan" rows="4" placeholder="Uraian..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Kinerja",
									atribut: 'name="kinerja" rows="4" placeholder="kinerja..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Indikator",
									atribut:
										'name="indikator" rows="4" placeholder="indikator..." non_data',
								}) +
								createHTML("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan"',
									kelas: "search clearable satuan ajx selection",
									dataArray: [],
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "aset":
						case "akun_belanja":
							dataHtmlku.konten =
								createHTML("multiFieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="get_data" tbl="${tbl}"`,
									dataArray: [
										'name="akun"',
										'name="kelompok"',
										'name="jenis_akun"',
										'name="objek"',
										'name="rincian_objek"',
										'name="sub_rincian_objek"',
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="3" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "sumber_dana":
							dataHtmlku.konten =
								createHTML("multiFieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="get_data" tbl="${tbl}"`,
									dataArray: [
										'name="sumber_dana"',
										'name="kelompok"',
										'name="jenis_akun"',
										'name="objek"',
										'name="rincian_objek"',
										'name="sub_rincian_objek"',
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							if (tbl === "edit") {
								data.id_row = id_row;
								jalankanAjax = true;
								formIni.attr("id_row", id_row);
								dataHtmlku.icon = "edit icon";
								dataHtmlku.header = "Edit Data/Peraturan";
							}
							break;
						case "peraturan":
							dataHtmlku.konten +=
								createHTML("fieldDropdown", {
									label: "Type Dok",
									atribut: 'name="type_dok"',
									kelas: "lainnya selection",
									dataArray: [
										[
											"peraturan_undang_undang_pusat",
											"Peraturan Perundang-undangan Pusat",
										],
										[
											"peraturan_menteri_lembaga",
											"Peraturan Kementerian / Lembaga",
										],
										["peraturan_daerah", "Peraturan Perundang-undangan Daerah"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["kegiatan", "File Kegiatan"],
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Judul",
									atribut: 'name="judul" rows="3" placeholder="Uraian..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian Singkat Peraturan",
									atribut:
										'name="judul_singkat" rows="3" placeholder="Uraian Singkat..."',
								}) +
								createHTML("fieldTextAction", {
									label: "Nomor",
									atribut: 'name="nomor" placeholder="Nomor Peraturan..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" klm="nomor" tbl="${tbl}"`,
								}) +
								createHTML("fieldText", {
									label: "Bentuk",
									atribut:
										'name="bentuk" placeholder="Bentuk(Peraturan Menteri Dalam Negeri...)"',
								}) +
								createHTML("fieldText", {
									label: "Bentuk Singkat",
									atribut:
										'name="bentuk_singkat" placeholder="bentuk singkat(permendagri)..."',
								}) +
								createHTML("fieldText", {
									label: "Tempat Penetapan",
									atribut:
										'name="t4_penetapan" placeholder="tempat penetapan..."',
								}) +
								createHTML("fieldCalendar", {
									label: "Tanggal Penetapan",
									atribut:
										'placeholder="Input tanggal penetapan.." name="tgl_penetapan" readonly',
									kelas: "date",
								}) +
								createHTML("fieldCalendar", {
									label: "Tanggal Pengundangan",
									atribut:
										'placeholder="Input Tanggal pengundangan.." name="tgl_pengundangan" readonly',
									kelas: "date",
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4" non_data',
								}) +
								createHTML("fieldDropdown", {
									label: "Status Data",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["umum", "Umum"],
										["rahasia", "Rahasia/Pribadi"],
										["kegiatan", "Dokumen kegiatan"],
									],
								}) +
								createHTML("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									atribut: "non_data",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								}) +
								createHTML("fielToggleCheckbox", {
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "mapping":
							dataHtmlku.konten +=
								createHTML("fieldDropdown", {
									label: "Kode Kelompok Barang/Jasa",
									atribut: 'name="kd_aset"',
									kelas: "search clearable aset ajx selection",
									dataArray: [["1.1.12.01.01.0010", "Isi Tabung Gas"]],
								}) +
								createHTML("fieldDropdown", {
									label: "Rekening / Akun",
									classField: `required`,
									atribut:
										'name="kd_akun" placeholder="pilih rekening/akun..."',
									kelas: "search clearable kd_akun ajx selection",
									dataArray: [["", ""]],
								}) +
								createHTML("fieldDropdown", {
									label: "Jenis Standar Harga",
									classField: `required`,
									atribut:
										'name="kelompok" placeholder="jenis standar harga..."',
									kelas: "clearable lainnya selection",
									dataArray: [
										["ssh", "SSH"],
										["sbu", "SBU"],
										["hspk", "HSPK"],
										["asb", "ASB"],
									],
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "organisasi":
							dataHtmlku.konten +=
								createHTML("fieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Nomor Peraturan..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								createHTML("fieldTextarea", {
									label: "Nama SKPD",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								createHTML("fieldText", {
									label: "Alamat",
									atribut: 'name="alamat" placeholder="Alamat OPD..."',
								}) +
								createHTML("fieldText", {
									label: "Kepala OPD",
									atribut:
										'name="nama_kepala" placeholder="Nama Kepala SKPD..."',
								}) +
								createHTML("fieldText", {
									label: "Nip. Kepala OPD",
									atribut:
										'name="nip_kepala" placeholder="Nip. Kepala SKPD..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								createHTML("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
									atribut: "non_data",
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "wilayah":
							dataHtmlku.konten +=
								createHTML("fieldTextAction", {
									label: "Kode Wilayah",
									atribut: 'name="kode" placeholder="Kode Wilayah..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								createHTML("fieldTextarea", {
									label: "Nama Wilayah",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								createHTML("fieldDropdown", {
									label: "Status Wilayah",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["prov", "Provinsi"],
										["kab", "Kabupaten"],
										["kota", "Kota"],
										["desa", "Desa"],
										["kel", "Kelurahan"],
										["dusun", "Dusun"],
										["lain", "Lainnya"],
									],
								}) +
								createHTML("fieldText", {
									label: "Jumlah Kecamatan",
									atribut: 'name="jml_kec" placeholder="Jumlah Kecamatan..."',
								}) +
								createHTML("fieldText", {
									label: "Jumlah Keluarahan",
									atribut: 'name="jml_kel" placeholder="Jumlah Keluarahan..."',
								}) +
								createHTML("fieldText", {
									label: "Jumlah Desa",
									atribut: 'name="jml_desa" placeholder="Jumlah Desa..."',
								}) +
								createHTML("fieldText", {
									label: "Luas Wilayah (km2)",
									atribut: 'name="luas" placeholder="Luas Wilayah (km2)..."',
								}) +
								createHTML("fieldText", {
									label: "Jumlah Penduduk (jiwa)",
									atribut:
										'name="penduduk" placeholder="Jumlah penduduk (jiwa)..."',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								createHTML("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									atribut: "non_data",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							if (jenis === "edit") {
								dataHtmlku.konten += createHTML("card3", {
									label: "Logo Daerah",
									atribut: `for="directupload1" id_row="${id_row}" name="direct" type="button" jns="upload" tbl="wilayah_logo" dok="logo" accept=".jpg,.png,.jpeg,.img"`,
								});
							}
							break;
						case "satuan":
							dataHtmlku.konten +=
								createHTML("fieldTextAction", {
									label: "Kode Pengenal",
									atribut: 'name="value" placeholder="Kode Pengenal..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								createHTML("fieldTextarea", {
									label: "Uraian HTML",
									atribut: 'name="item" rows="2"',
								}) +
								createHTML("fieldTextarea", {
									label: "Sebutan Lain",
									atribut: 'name="sebutan_lain" rows="4"',
								}) +
								createHTML("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4" non_data',
								}) +
								createHTML("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case "value1":
							break;
						default:
							break;
					}
					break;
				case "import":
					let templateXlsx = linkTemplate[tbl];
					if (templateXlsx) {
						dataHtmlku.konten = createHTML("fieldLabel", {
							label: "Download Template",
							icon: "download green",
							value: "Download Template",
							href: BASEURL + templateXlsx,
							atribut: 'target="_blank""',
						});
					} else {
						dataHtmlku.konten = "";
					}
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//file
					dataHtmlku.konten += createHTML("fieldFileInput2", {
						label: "Pilih File Dokumen",
						placeholderData: "Pilih File (*.xlsx)...",
						accept: ".xlsx",
					}); //non_data(artinya tidak di dicek form)
					//dropdown
					dataHtmlku.konten += createHTML("fieldDropdown", {
						label: "Jumlah Header Tabel",
						atribut: 'name="jml_header"',
						kelas: "lainnya selection",
						dataArray: [
							[0, "0 Baris Header"],
							[1, "1 Baris Header"],
							[2, "2 Baris Header"],
							[3, "3 Baris Header"],
							[4, "4 Baris Header"],
							[5, "5 Baris Header"],
						],
					});
					switch (tbl) {
						case "dpa":
						case "dppa":
						case "renja":
						case "renja_p": //@audit now
							// console.log(ini.closest('.ui.tab').find(`.ui.menu a.active[tb="${tbl}"]`));
							formIni.attr(
								"id_sub_keg",
								ini
									.closest(".ui.tab")
									.find(`.ui.menu a.active[tbl="${tbl}"]`)
									.attr("id_sub_keg")
							);
							formIni.attr("dok", ini.attr("dok"));
							break;
						case "value1":
							break;
					}
					break;
				case "upload":
					dataHtmlku.icon = "file icon blue";
					dataHtmlku.header = "Unggah File";
					//file
					let acceptFileExt = ".pdf,.xlsx,.jpg,.jpeg";
					switch (tbl) {
						case "daftar_paket":
							data.id_row = id_row;
							formIni.attr("id_row", ini.closest("tr").attr("id_row"));
							formIni.attr("dok", ini.attr("dok"));
							data.dok = ini.attr("dok");
							jalankanAjax = true;
							break;
						case "logo":
							jalankanAjax = true;
							break;
						default:
							break;
					}
					dataHtmlku.konten +=
						createHTML("fieldFileInput2", {
							label: "Pilih File Dokumen",
							placeholderData: `Pilih File (${acceptFileExt})...`,
							accept: acceptFileExt,
							file: data.dok,
						}) + createHTML("card4", {}); //non_data(artinya tidak di dicek form)
					//dropdown
					switch (tbl) {
						case "daftar_paket":
							break;
						case "value1":
							break;
					}
					break;
				default:
					break;
			}
			//atur form
			removeRulesForm(formIni);
			htmlForm = `${dataHtmlku.konten}<div class="ui icon success message"><i class="check icon"></i><div class="content"><div class="header">Form sudah lengkap</div><p>anda bisa submit form</p></div></div><div class="ui error message"></div>`;
			iconFlyout.attr("class", "").addClass(dataHtmlku.icon);
			headerFlyout.text(dataHtmlku.header);
			formIni.html(htmlForm);
			let calendarDate = new CalendarConstructor(".ui.calendar.date");
			calendarDate.runCalendar();
			let calendarDateTime = new CalendarConstructor(".ui.calendar.datetime");
			calendarDateTime.Type("datetime");
			calendarDateTime.runCalendar();
			let calendarYear = new CalendarConstructor(".ui.calendar.year");
			calendarYear.Type("year");
			calendarYear.runCalendar();
			$('div[name="jml_header"]').dropdown("set selected", 1);
			$(".ui.accordion").accordion();
			formIni.find(".ui.dropdown.lainnya").dropdown();
			if ($(`a[name="change_themes"]`).attr("theme") === "dark") {
				$(".form .icon").removeClass("inverted").addClass("inverted");
			}
			addRulesForm(formIni);
			switch (jenis) {
				case "add":
				case "edit":
					switch (tbl) {
						case "register_surat":
							var dropdownJenisNaskah = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown[name="jenis_naskah_dinas"]'
							);
							dropdownJenisNaskah.onChange({
								jenis: "non",
								tbl: "register_surat",
							});
							break;
						case "users": //name="jenis_naskah_dinas"
							var dropdown_ajx_organisasi = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown[name="kd_organisasi"]'
							);
							dropdown_ajx_organisasi.returnList({
								jenis: "get_row_json",
								tbl: "organisasi",
								minCharacters: 1,
							});
							var dropdown_ajx_wilayah = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown[name="kd_wilayah"]'
							);
							dropdown_ajx_wilayah.returnList({
								jenis: "get_row_json",
								tbl: "wilayah",
								minCharacters: 1,
							});
							break;
						case "bidang_urusan":
						case "prog":
						case "keg":
						case "sub_keg":
							var dropdown_ajx_satuan = new DropdownConstructor(
								".ui.dropdown.satuan.ajx.selection"
							);
							dropdown_ajx_satuan.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							break;
						case "daftar_paket":
							dropdown_ajx_satuan = new DropdownConstructor(
								".ui.dropdown.satuan.ajx.selection"
							);
							dropdown_ajx_satuan.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							var dropdown_ajx_rekanan = new DropdownConstructor(
								".ui.dropdown.rekanan.ajx.selection"
							);
							dropdown_ajx_rekanan.returnList({
								jenis: "get_row_json",
								tbl: "rekanan",
								minCharacters: 3,
							});
							var allObjek = {
								jenis: "non",
								tbl: "metodePengadaan",
							};
							var dropdownMetodePemilihan = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown[name="metode_pengadaan"]'
							);
							dropdownMetodePemilihan.onChange(allObjek);
							break;
						case "mapping":
							var dropKdAset = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.ajx[name="kd_aset"]'
							);
							dropKdAset.returnList({
								jenis: "get_row_json",
								tbl: "aset",
							});
							var dropKdAkun = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.ajx[name="kd_akun"]'
							);
							dropKdAkun.returnList({
								jenis: "get_row_json",
								tbl: "akun_belanja",
							});
							break;
						case "sbu":
						case "asb":
						case "ssh":
						case "hspk":
							// name="kd_aset"
							var dropdownKdAset = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown[name="kd_aset"]'
							);
							dropdownKdAset.returnList({
								jenis: "get_row_json",
								tbl: "aset",
							});
							//satuan
							dropdown_ajx_satuan = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.ajx[name="satuan"]'
							);
							dropdown_ajx_satuan.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							var dropdownKdAkun = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.kd_akun.ajx.selection'
							);
							dropdownKdAkun.returnList({
								jenis: "get_row_json",
								tbl: "akun_belanja_val",
							});
							break;
						case "tujuan_sasaran_renstra":
							// formIni.find(".ui.dropdown.tujuan_sasaran.selection").dropdown();
							// case 'getJsonRows':
							// switch (allObjek.tbl) {
							// 	case 'tujuan_renstra'
							var allObjek = {
								jenis: "getJsonRows",
								tbl: "tujuan_renstra",
							};
							var dropdownTujuanSasaran = new DropdownConstructor(
								".ui.dropdown.tujuan_sasaran.selection"
							);
							dropdownTujuanSasaran.onChange(allObjek);
							var dropdown_ajx_tujuan = new DropdownConstructor(
								".ui.dropdown.ajx.tujuan_renstra.selection"
							);
							dropdown_ajx_tujuan.returnList({
								jenis: "get_row_json",
								tbl: "tujuan_renstra",
								minCharacters: 1,
							});
							// dropdownTujuanSasaran.returnList("get_row_json", "tujuan_renstra", true)
							break;
						case "renstra":
							var dropdown_ajx_tujuan = new DropdownConstructor(
								".ui.dropdown.ajx.sasaran_renstra.selection"
							);
							dropdown_ajx_tujuan.returnList({
								jenis: "get_row_json",
								tbl: "sasaran_renstra",
								minCharacters: 1,
							});
							var dropdown_ajx_kode = new DropdownConstructor(
								".ui.dropdown.kode.ajx.selection"
							);
							dropdown_ajx_kode.returnList({
								jenis: "get_row_json",
								tbl: "sub_keg",
							}); //dropdownConstr.restore();
							dropdown_ajx_satuan = new DropdownConstructor(
								".ui.dropdown.satuan.ajx.selection"
							);
							dropdown_ajx_satuan.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							break;
						case "sub_keg_dpa":
						case "sub_keg_renja":
							var dropdownSumberDana = new DropdownConstructor(
								".ui.dropdown.sumber_dana.ajx.selection"
							);
							dropdownSumberDana.returnList({
								jenis: "get_row_json",
								tbl: "sumber_dana",
							});
							var dropdown_ajx_sub_keg = new DropdownConstructor(
								".ui.dropdown.kd_sub_keg.ajx.selection"
							);
							dropdown_ajx_sub_keg.returnList({
								jenis: "get_row_json",
								tbl: "sub_keg",
							});
							break;
						case "dpa":
						case "renja":
						case "renja_p":
						case "dppa":
							let sumber = "sumber_dana";
							// if (tbl == 'dppa' || tbl == 'renja_p') {
							// 	sumber = 'sumber_dana_p';
							// }
							var tabel_pakai_temporerSubkeg = "sub_keg_renja";
							switch (tbl) {
								case "dpa":
								case "dppa":
									tabel_pakai_temporerSubkeg = "sub_keg_dpa";
									break;
							}
							var renja_dpa = new DropdownConstructor(
								".ui.dropdown.kd_akun.ajx.selection"
							);
							renja_dpa.returnList({
								jenis: "get_row_json",
								tbl: "akun_belanja",
							});
							//jenis_kelompok
							let dropdownJenisKelompok = new DropdownConstructor(
								'.ui.dropdown[name="jenis_kelompok"]'
							);
							allObjek = {
								jenis: "gantiJenisKelompok",
								tbl: tbl,
							};
							dropdownJenisKelompok.onChange(allObjek);
							//jenis_kelompok
							let dropdownJenisKomponen = new DropdownConstructor(
								'.ui.dropdown[name="jenis_standar_harga"]'
							);
							allObjek = { jenis: "gantiJenisKomponen" };
							dropdownJenisKomponen.onChange(allObjek);
							var dropdownSumberDana = new DropdownConstructor(
								".ui.dropdown.sumber_dana.ajx.selection"
							);
							var allField = {
								klm: sumber,
								id_sub_keg: $('form[name="form_flyout"]').attr("id_sub_keg"),
								jns_kel: sumber,
							};
							dropdownSumberDana.returnList({
								jenis: "getJsonRows",
								tbl: tabel_pakai_temporerSubkeg,
								set: allField,
							});
							var dropdownKeterangan = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown[name="uraian"]'
							);
							allField = {
								klm: "keterangan_json",
								id_sub_keg: $('form[name="form_flyout"]').attr("id_sub_keg"),
								jns_kel: "keterangan_json",
							};
							dropdownKeterangan.returnList({
								jenis: "get_field_json",
								tbl: tabel_pakai_temporerSubkeg,
								set: allField,
							});
							var dropdownSatuanRenja1 = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.sat_1'
							);
							allField = { minCharacters: 1 };
							dropdownSatuanRenja1.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							var dropdownSatuanRenja2 = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.sat_2'
							);
							dropdownSatuanRenja2.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							var dropdownSatuanRenja3 = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.sat_3'
							);
							dropdownSatuanRenja3.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							var dropdownSatuanRenja4 = new DropdownConstructor(
								'form[name="form_flyout"] .ui.dropdown.sat_4'
							);
							dropdownSatuanRenja4.returnList({
								jenis: "get_row_json",
								tbl: "satuan",
								minCharacters: 1,
							});
							break;
						case "val":
							break;
						default:
							break;
					}
					break;
				case "get_pengaturan":
					switch (tbl) {
						case "pengaturan":
							let calendarDateTime = new CalendarConstructor(
								`[name="awal_renja"],[name="akhir_renja"]`
							); //(`.ui.calendar.datetime.startend
							calendarDateTime.startCalendar = $(`[name="awal_renja"]`);
							calendarDateTime.endCalendar = $(`[name="akhir_renja"]`);
							calendarDateTime.Type("datetime");
							calendarDateTime.runCalendar();
							break;
						default:
							break;
					}
					break;
				default:
					break;
			}
			$("[rms]").mathbiila();
		} else if (attrName === "get_data") {
			if (typeof ini.attr("klm") !== "undefined") {
				data.klm = ini.attr("klm");
			}
			switch (jenis) {
				case "get_data":
					switch (tbl) {
						case "user":
						case "asn":
							data.text = ini.closest(".input").find("input").val();
							data.klm = ini.attr("klm");
							jalankanAjax = true;
							break;
						case "neraca":
						case "akun_belanja":
							//cek rekening
							let valForm = formIni.form("get values", [
								"akun",
								"kelompok",
								"jenis_akun",
								"objek",
								"rincian_objek",
								"sub_rincian_objek",
							]);
							data = {
								...data,
								...valForm,
							};
							jalankanAjax = true;
							break;
						case "bidang_urusan":
						case "prog":
						case "keg":
						case "sub_keg":
							//cek rekening
							let valForm2 = formIni.form("get values", [
								"urusan",
								"bidang",
								"prog",
								"keg",
								"sub_keg",
							]);
							// merger object
							data = {
								...data,
								...valForm2,
							};
							jalankanAjax = true;
							break;
						case "satuan":
							data.value = ini.closest(".input").find("input").val();
							jalankanAjax = true;
							break;
						default:
							data.text = ini
								.closest(".input")
								.find('input[name="kode"]')
								.val();
							if (typeof data.text === "undefined") {
								data.text = ini.closest(".input").find("input[name]").val();
							}
							if (data.text.length > 1) {
								jalankanAjax = true;
							} else {
								showToast("Input sebelum cek data", {
									class: "warning",
									icon: "check circle icon",
								});
							}
							break;
					}

					break;
				case "value1":
					break;
				default:
					break;
			}
		}
		// addRulesForm(formIni);
		//JALANKAN AJAX
		if (jalankanAjax) {
			// loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					let hasKey = result.hasOwnProperty("error");
					if (hasKey) {
						hasKey = result.error.hasOwnProperty("message");
						let error_code = result.error.code;
						let kelasToast = "success";
						let iconToast = "check circle icon";

						switch (attrName) {
							case "flyout":
								switch (jenis) {
									case "edit":
										// set nilai form
										let elmAttrName = formIni.find(
											"input[name],textarea[name]"
										);
										switch (tbl) {
											case "xcv":
												break;
											default: //isi form dengan data
												for (const iterator of elmAttrName) {
													let attrElm = $(iterator).attr("name");
													let postDataField = true;
													let dropDownElmAjx =
														$(iterator).closest(".ui.dropdown.ajx");
													let text = `${result.data?.users[attrElm]}`;
													let position = text.search("file");
													if (attrElm === "file" || position >= 0) {
														formIni.form(
															"set value",
															"dum_file",
															result.data?.users[attrElm]
														);
													} else {
														let strText = null;
														let cariAttrRms = $(iterator).attr("rms");
														// console.log(`cariAttrRms : ${cariAttrRms}`);
														if (
															typeof cariAttrRms === "undefined" ||
															cariAttrRms === false
														) {
															strText = result.data?.users[attrElm];
														} else {
															strText = parseFloat(result.data?.users[attrElm]);
															strText = accounting.formatNumber(
																result.data?.users[attrElm],
																strText.countDecimals(),
																".",
																","
															);
														}
														// console.log(`strText : ${strText}`);
														// jika ada ajx class drpdown
														if (
															dropDownElmAjx.length > 0 &&
															result.data.hasOwnProperty("values")
														) {
															if (result.data?.values[attrElm]) {
																postDataField = false;
																switch (tbl) {
																	case "users":
																		switch (attrElm) {
																			case "kd_organisasi":
																				var dropdown_ajx_organisasi =
																					new DropdownConstructor(
																						'form[name="form_flyout"] .ui.dropdown[name="kd_organisasi"]'
																					);
																				dropdown_ajx_organisasi.valuesDropdown(
																					result.data?.values?.kd_organisasi
																				);
																				dropdown_ajx_organisasi.returnList({
																					jenis: "get_row_json",
																					tbl: "organisasi",
																					minCharacters: 1,
																				});
																				break;
																			case "kd_wilayah":
																				var dropdown_ajx_wilayah =
																					new DropdownConstructor(
																						'form[name="form_flyout"] .ui.dropdown[name="kd_wilayah"]'
																					);
																				dropdown_ajx_wilayah.valuesDropdown(
																					result.data?.values?.kd_wilayah
																				);
																				dropdown_ajx_wilayah.returnList({
																					jenis: "get_row_json",
																					tbl: "wilayah",
																					minCharacters: 1,
																				});
																				break;
																		}
																		break;
																	case "bidang_urusan":
																	case "prog":
																	case "keg":
																	case "sub_keg":
																		switch (attrElm) {
																			case "satuan":
																				dropdown_ajx_satuan.valuesDropdown(
																					result.data?.values?.satuan
																				);
																				dropdown_ajx_satuan.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					minCharacters: 1,
																				});
																				break;
																		}
																		break;
																	case "daftar_paket":
																		switch (attrElm) {
																			case "satuan":
																				dropdown_ajx_satuan.valuesDropdown(
																					result.data?.values?.satuan
																				);
																				dropdown_ajx_satuan.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					minCharacters: 1,
																				});
																				break;
																			case "id_rekanan":
																				dropdown_ajx_rekanan.valuesDropdown(
																					result.data?.values?.id_rekanan
																				);
																				dropdown_ajx_rekanan.returnList({
																					jenis: "get_row_json",
																					tbl: "rekanan",
																					minCharacters: 3,
																				});
																				break;
																			default:
																				break;
																		}
																		break;
																	case "mapping":
																		switch (attrElm) {
																			case "kd_aset":
																				dropKdAset.valuesDropdown(
																					result.data?.values?.kd_aset
																				);
																				dropKdAset.returnList({
																					jenis: "get_row_json",
																					tbl: "aset",
																				});
																				postDataField = false;
																				break;
																			case "kd_akun":
																				dropKdAkun.valuesDropdown(
																					result.data?.values?.kd_akun
																				);
																				dropKdAkun.returnList({
																					jenis: "get_row_json",
																					tbl: "akun_belanja",
																				});
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	case "sbu":
																	case "asb":
																	case "ssh":
																	case "hspk":
																		// name="kd_aset"
																		switch (attrElm) {
																			case "satuan":
																				dropdown_ajx_satuan.valuesDropdown(
																					result.data?.values?.satuan
																				);
																				dropdown_ajx_satuan.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					set: allField,
																				});
																				postDataField = false;
																				break;
																			case "kd_aset":
																				dropdownKdAset.valuesDropdown(
																					result.data?.values?.kd_aset
																				);
																				dropdownKdAset.returnList({
																					jenis: "get_row_json",
																					tbl: "aset",
																				});
																				postDataField = false;
																				break;
																			case "kd_akun":
																				dropdownKdAkun.valuesDropdown(
																					result.data?.values?.kd_akun
																				);
																				dropdownKdAkun.returnList({
																					jenis: "get_row_json",
																					tbl: "akun_belanja_val",
																				});
																				break;
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	case "dpa":
																	case "renja":
																	case "renja_p":
																	case "dppa":
																		tabel_pakai_temporerSubkeg =
																			"sub_keg_renja";
																		switch (tbl) {
																			case "dpa":
																			case "dppa":
																				tabel_pakai_temporerSubkeg =
																					"sub_keg_dpa";
																				break;
																		}
																		let id_sub_keg = $(
																			'form[name="form_flyout"]'
																		).attr("id_sub_keg");
																		switch (attrElm) {
																			case "kd_akun":
																				renja_dpa.valuesDropdown(
																					result.data?.values?.kd_akun
																				);
																				renja_dpa.returnList({
																					jenis: "get_row_json",
																					tbl: "akun_belanja",
																				});
																				break;
																			// case 'jenis_standar_harga':
																			// 	dropdownJenisKomponen.valuesDropdown(result.data?.values?.jenis_standar_harga);
																			// 	allObjek = { jenis: 'gantiJenisKomponen' };
																			// 	dropdownJenisKomponen.onChange(allObjek);
																			// 	break;
																			case "kelompok":
																				let dropdownKelompok =
																					new DropdownConstructor(
																						'form[name="form_flyout"] .ui.dropdown[name="kelompok"]'
																					);
																				dropdownKelompok.valuesDropdown(
																					result.data?.values?.kelompok
																				);
																				allField = {
																					klm: "kelompok_json",
																					id_sub_keg: id_sub_keg,
																				};
																				dropdownKelompok.returnList({
																					jenis: "get_field_json",
																					tbl: tabel_pakai_temporerSubkeg,
																					set: allField,
																				});
																				break;
																			case "sumber_dana_p":
																			case "sumber_dana":
																				let sumber = "sumber_dana";
																				if (tbl == "dppa" || tbl == "renja_p") {
																					sumber = "sumber_dana_p";
																				}
																				dropdownSumberDana.valuesDropdown(
																					result.data?.values[sumber]
																				);
																				console.log(
																					result.data?.values[sumber]
																				);
																				var allField = {
																					klm: "sumber_dana",
																					id_sub_keg: $(
																						'form[name="form_flyout"]'
																					).attr("id_sub_keg"),
																					jns_kel: "sumber_dana",
																				};
																				dropdownSumberDana.returnList({
																					jenis: "getJsonRows",
																					tbl: tabel_pakai_temporerSubkeg,
																					set: allField,
																				});
																				break;
																			case "komponen":
																				let dropdownKomponen =
																					new DropdownConstructor(
																						'form[name="form_flyout"] .ui.dropdown[name="komponen"]'
																					);
																				dropdownKomponen.valuesDropdown(
																					result.data?.values?.komponen
																				);
																				let jenisKomponen = $(
																					'form[name="form_flyout"]'
																				)
																					.find(
																						'.ui.dropdown[name="jenis_standar_harga"]'
																					)
																					.dropdown("get value");
																				let rekeningAkun = $(
																					'form[name="form_flyout"]'
																				)
																					.find('.ui.dropdown[name="kd_akun"]')
																					.dropdown("get value");
																				let allFieldKomponen = {
																					id_sub_keg: id_sub_keg,
																					kd_akun: rekeningAkun,
																				};
																				dropdownKomponen.returnList({
																					jenis: "get_row_json",
																					tbl: jenisKomponen,
																					set: allFieldKomponen,
																				});

																				break;
																			case "uraian":
																				dropdownKeterangan.valuesDropdown(
																					result.data?.values?.uraian
																				);
																				allField = {
																					klm: "keterangan_json",
																					id_sub_keg: id_sub_keg,
																					jns_kel: "keterangan_json",
																				};
																				dropdownKeterangan.returnList({
																					jenis: "get_field_json",
																					tbl: tabel_pakai_temporerSubkeg,
																					set: allField,
																				});
																				break;
																			case "sat_1_p":
																			case "sat_1":
																				dropdownSatuanRenja1.valuesDropdown(
																					result.data?.values[attrElm]
																				);
																				dropdownSatuanRenja1.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					minCharacters: 1,
																				});
																				break;
																			case "sat_2_p":
																			case "sat_2":
																				dropdownSatuanRenja2.valuesDropdown(
																					result.data?.values[attrElm]
																				);
																				dropdownSatuanRenja2.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					minCharacters: 1,
																				});
																				break;
																			case "sat_3_p":
																			case "sat_3":
																				dropdownSatuanRenja3.valuesDropdown(
																					result.data?.values[attrElm]
																				);
																				dropdownSatuanRenja3.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					minCharacters: 1,
																				});
																				break;
																			case "sat_4_p":
																			case "sat_4":
																				dropdownSatuanRenja4.valuesDropdown(
																					result.data?.values[attrElm]
																				);
																				dropdownSatuanRenja4.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					minCharacters: 1,
																				});
																			default:
																				break;
																		}
																		if (tbl === "renja_p" || tbl === "dppa") {
																			let id_dpa = result.data?.users?.id_dpa;
																			if (id_dpa > 0) {
																				$('form[name="form_flyout"]')
																					.find(
																						'.ui.dropdown[name="komponen"],.ui.dropdown[name="jenis_standar_harga"],.ui.dropdown[name="uraian"],.ui.dropdown[name="kd_akun"],.ui.dropdown[name="objek_belanja"],.ui.dropdown[name="jenis_kelompok"],.ui.dropdown[name="kelompok"]'
																					)
																					.addClass("disabled");
																			}
																		}
																		break;
																	case "renstra":
																		switch (attrElm) {
																			case "sasaran":
																				dropdown_ajx_tujuan.valuesDropdown(
																					result.data?.values?.sasaran
																				);
																				dropdown_ajx_tujuan.returnList({
																					jenis: "get_row_json",
																					tbl: "sasaran_renstra",
																					set: allField,
																				});
																				postDataField = false;
																				break;
																			case "kd_sub_keg":
																				dropdown_ajx_kode.valuesDropdown(
																					result.data?.values?.kd_sub_keg
																				);
																				dropdown_ajx_kode.returnList({
																					jenis: "get_row_json",
																					tbl: "sub_keg",
																				});
																				postDataField = false;
																				break;
																			case "satuan":
																				dropdown_ajx_satuan.valuesDropdown(
																					result.data?.values?.satuan
																				);
																				dropdown_ajx_satuan.returnList({
																					jenis: "get_row_json",
																					tbl: "satuan",
																					set: allField,
																				});
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	case "sub_keg_dpa":
																	case "sub_keg_renja":
																		switch (attrElm) {
																			case "kd_sub_keg":
																				dropdown_ajx_sub_keg.valuesDropdown(
																					result.data?.values?.kd_sub_keg
																				);
																				dropdown_ajx_sub_keg.returnList({
																					jenis: "get_row_json",
																					tbl: "sub_keg",
																				});
																				postDataField = false;
																				break;
																			case "sumber_dana":
																				dropdownSumberDana.valuesDropdown(
																					result.data?.values?.sumber_dana
																				);
																				dropdownSumberDana.returnList({
																					jenis: "get_row_json",
																					tbl: "sumber_dana",
																				});
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	case "tujuan_sasaran_renstra":
																		switch (attrElm) {
																			case "id_tujuan":
																				dropdown_ajx_tujuan.valuesDropdown(
																					result.data?.values?.id_tujuan
																				);
																				dropdown_ajx_tujuan.returnList({
																					jenis: "getJsonRows",
																					tbl: "tujuan_renstra",
																					ajax: true,
																				});
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	default:
																		break;
																}
															}
														}
														if (postDataField) {
															formIni.form("set value", attrElm, strText);
														}
													}
												}

												break;
										}
										switch (tbl) {
											case "wilayah": //upload direct logo wilayah
											case "asn":
												let fileUnggah = result?.data?.users?.file_photo;
												let hasUnggah = "file_photo";
												switch (tbl) {
													case "wilayah":
														fileUnggah = result?.data?.users?.logo;
														hasUnggah = "logo";
														break;
												}

												$(
													'form[name="form_flyout"] .special.card .dimmable.image'
												).dimmer({
													on:
														"ontouchstart" in document.documentElement
															? "click"
															: "hover",
												});
												//
												if (result?.data?.hasOwnProperty("users")) {
													// let card = document.querySelector('.ui.special.fluid.card');
													// let NameElement = card.querySelector('.content');
													// NameElement.textContent = result?.data?.users.nama.toProperCase();
													if (result?.data?.users.hasOwnProperty(hasUnggah)) {
														let angka = fileUnggah;
														if (angka) {
															formIni
																.find(".ui.card img")
																.attr("src", fileUnggah);
														}
													}
												}
												break;
											default:
												break;
										}
										break;
									case "upload":
										switch (tbl) {
											case "daftar_paket":
												let dok = ini.attr("dok");
												formIni
													.find(`[name_bayang="${dok}"]`)
													.val(result.data?.users[dok]);
												// formIni.form("set value", dok, result.data?.users[dok]).trigger("change");
												let namaFileLink = result.data.users[dok];
												if (namaFileLink) {
													let splitku = namaFileLink.split(".");
													let extensionFile = splitku[splitku.length - 1];
													switch (extensionFile) {
														case "jpeg":
														case "png":
														case "jpg":
														case "jpeg":
															formIni.find("img").attr("src", namaFileLink);
															formIni.find("[href]").attr("href", namaFileLink);
															break;
														default:
															formIni.find("[href]").attr("href", namaFileLink);
															break;
													}
												} else {
													formIni.find(".ribbon.label").text("Unggah File");
												}
												break;
											case "logo":
												formIni
													.find("[href]")
													.attr("href", result.data.users.logo);
												break;
											default:
												break;
										}
										break;

									default:
										break;
								}
								addRulesForm(formIni);
								$(".ui.flyout").flyout("toggle");
								break;
							case "get_data":
								let pengenal = {
									rekanan: {
										404: "nama perusahaan dapat digunakan",
										302: "nama rekanan sudah digunakan, update atau gunakan nama rekanan lain",
									},
									asn: {
										404: "nip dapat digunakan",
										302: "nip sudah digunakan, update atau gunakan nip lain",
									},
								};
								switch (jenis) {
									case "get_data":
										switch (tbl) {
											case "neraca":
											case "akun_belanja":
											case "satuan":
											case "bidang_urusan":
											case "prog":
											case "keg":
											case "sub_keg":
											case "wilayah":
												if (result.error.code === 404) {
													result.error.message = "kode dapat digunakan";
												} else {
													kelasToast = "warning";
													result.error.message =
														"kode sudah digunakan, update atau gunakan kode lain";
												}
												break;
											default:
												if (pengenal.hasOwnProperty(tbl)) {
													if (result.error.code === 404) {
														result.error.message = pengenal[tbl]["404"];
													} else {
														kelasToast = "warning";
														result.error.message = pengenal[tbl]["302"];
													}
												} else {
													if (result.error.code === 404) {
														result.error.message = "value dapat digunakan";
													} else {
														kelasToast = "warning";
														result.error.message =
															"value sudah digunakan, update data atau gunakan value lain";
													}
												}

												break;
										}
										break;
									default:
										break;
								}
								break;
							default:
								break;
						}
						if (hasKey) {
							switch (error_code) {
								case 9:
									kelasToast = "error";
									iconToast = "exclamation triangle yellow icon";
									break;
								default:
									break;
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: iconToast,
							});
						}
					} else {
					}
					loaderHide();
				} else {
					loaderHide();
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		} else {
			if (attrName === "flyout" && jalankanAjax === false) {
				$(".ui.flyout").flyout("toggle");
			}
		}

		// disable submit jika deactivate
		formIni
			.closest(".flyout")
			.find(".ui.ok.button, .ui.dropdown")
			.removeClass("disabled");
		if (deactivate) {
			formIni
				.closest(".flyout")
				.find(".ui.ok.button, .ui.dropdown")
				.addClass("disabled");
			formIni.find(".ui.button, .ui.dropdown").addClass("disabled");
			formIni.find("input, textarea").attr("readonly", 1);
		}
	});
	$(".special.card .dimmable.image").dimmer({
		// As hover is not working on mobile, you might use click on those devices as fallback
		on: "ontouchstart" in document.documentElement ? "click" : "hover",
	});
	//====================================
	//=========== flyout =================
	//====================================
	$(".ui.flyout")
		.flyout({
			closable: false,
			context: $(".bottom.pushable"),
			onShow: function () {
				$("#biilainayah").addClass("disabled");
				// loaderHide();
				// console.log('onShow flyout');
			},
			onHide: function (choice) {
				// 		//console.log(choice);
				$("#biilainayah").removeClass("disabled");
				let form = $(".ui.flyout form");
				form.form("clear");
				removeRulesForm(form);
				// 		// //inisialize kembali agar tidak error di console
				var reinitForm = new FormGlobal(form);
				reinitForm.run();
			},
			onApprove: function (elemen) {
				$(elemen).closest("div.flyout").find("form").form("submit");
				return false;
			},
		})
		.flyout("attach events", '[name="flyout"]');
	//========================================
	//===========DEL ROW DAN EKSEKUSI ==========@audit-ok val/delete row
	//========================================
	$("body").on("click", '[name="del_row"], [name="jalankan"]', function (e) {
		e.stopImmediatePropagation();
		e.preventDefault();
		let ini = $(this);
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		var id_row = ini.attr("id_row");
		if (typeof id_row === "undefined") {
			id_row = ini.closest("tr").attr("id_row");
			if (typeof id_row === "undefined") {
				id_row = ini.closest("div.item").attr("id_row");
				if (typeof id_row === "undefined") {
					id_row = ini.closest("div.comment").attr("id_row");
				}
			}
		}
		let url = "script/del_data";
		let jalankanAjax = false;
		let contentModal = [
			'<i class="trash alternate icon"></i>anda yakin akan hapus data ini?',
			"menghapus data tidak dapat di batalkan...!",
		];
		let cryptos = false;
		let data = {};
		switch (jenis) {
			case "lock":
			case "setujui":
				jalankanAjax = true;
				break;
			default:
				break;
		}
		switch (jenis) {
			case "unkunci":
			case "unsetujui":
			case "kunci":
			case "setujui":
				let jns_dok = {
					dpa: "DPA",
					renja: "Renja",
					renja_p: "Renja Perubahan",
					dppa: "DPPA",
				};
				let jenis_eksekusi = {
					unkunci: {
						icon: "unlock alternate",
						header: `unlock dokumen ${jns_dok[tbl]} ini`,
						paragraf: `ini akan membuat user dapat mengedit dokumen...!`,
					},
					unsetujui: {
						icon: "unlock alternate",
						header: `unlock kembali dokumen ${jns_dok[tbl]} ini`,
						paragraf: `ini akan membuat user dapat mengedit dokumen dan menghapus data yang sudah diposting...!`,
					},
					kunci: {
						icon: "lock alternate",
						header: `unlock dokumen ${jns_dok[tbl]} ini`,
						paragraf: `ini akan membuat user dapat mengedit dokumen...!`,
					},
					setujui: {
						icon: "trash alternate",
						header: `posting dokumen ${jns_dok[tbl]} ini`,
						paragraf: `ini akan membuat akan posting ke dokumen selajutnya...!`,
					},
				};
				contentModal = [
					`<i class="${jenis_eksekusi[jenis].icon} icon"></i>anda yakin akan ${jenis} dokumen ${tbl} ini?`,
					`${jenis_eksekusi[jenis].paragraf}`,
				];
				data.tahun = $(`form[name="form_pengaturan"]`)
					.form("get value", "tahun")
					.getFullYear();
				if (tbl === "renstra") {
					//The setDate method works inplace on the object, and the return value of the function is the timestamp of the new date. makanya gunakan new date
					let tanggal = $(
						`form[name="form_pengaturan"] .ui.calendar.year[name="tahun_renstra"]`
					).calendar("get date");
					if (tanggal) {
						console.log(tanggal);
						tanggal = `${tanggal.getFullYear()}`;
						data.tahun_renstra = tanggal;
					}
				}
				url = "script/post_data";
				jalankanAjax = true;
				break;
			case "reset":
				//contentModal = ['<i class="trash alternate icon"></i>anda yakin akan mereset tabel data ini?', 'mereset tabel tidak dapat di batalkan...!']
				var tabelreset = ini.closest(".card").find(".content .header").text();
				contentModal = [
					'<i class="trash alternate icon"></i>anda yakin akan mereset tabel ' +
						tabelreset +
						" ini?",
					"mereset tabel tidak dapat di batalkan...!",
				];
				jalankanAjax = true;
				//data.tbl = "reset";
				break;
			case "del_row":
				if (id_row > 0 && jenis !== "direct") {
					data.id_row = id_row;
					jalankanAjax = true;
				}
				break;
			default:
				break;
		}
		data.cari = cari(jenis);
		data.rows = countRows();
		data.jenis = jenis;
		data.tbl = tbl;
		data.halaman = halaman;
		modal_notif_hapus(contentModal[0], contentModal[1]);
		$(".ui.hapus.modal")
			.modal({
				allowMultiple: true,
				//observeChanges: true,
				closable: false,
				onApprove: function () {
					if (jalankanAjax) {
						let classToast = "warning";
						let iconToast = "check circle icon";
						suksesAjax["ajaxku"] = function (result) {
							switch (jenis) {
								case "reset":
								case "del_row":
									if (result.error.code === 4) {
										var obj = ini.closest("tr");
										if (obj.length <= 0) {
											obj = ini.closest(".item");
											if (obj.length <= 0) {
												obj = ini.closest(".comment");
											}
										}
										//console.log(obj);
										obj.find("input, textarea").addClass("transparent");
										obj.css("background-color", "#EF617C");
										obj.fadeOut(600, function () {
											obj.remove();
										});
										switch (jenis) {
											case "z":
												break;
											default:
												classToast = "success";
												iconToast = "check circle icon";
												break;
										}
									} else {
										classToast = "warning";
										iconToast = "check circle icon";
									}
									break;
								default:
									break;
							}
							switch (result.error.code) {
								case 2:
								case 3:
								case 4:
									classToast = "success";
									iconToast = "check circle icon";
									break;
								default:
									classToast = "warning";
									iconToast = "check circle icon";
									break;
							}
							showToast(result.error.message, {
								class: classToast,
								icon: iconToast,
							});
						};
						runAjax(
							url,
							"POST",
							data,
							"Json",
							undefined,
							undefined,
							"ajaxku",
							cryptos
						);
					} else {
						switch (jenis) {
							case "direct":
								let form = ini.closest("form");
								let direct = ini.attr("direct"); //jika ada atribut direct hapus direct
								let obj = ini.closest("tr");
								if (direct) {
									obj = ini.closest('[direct="del"]');
								}
								obj.find("input, textarea").addClass("transparent");
								obj.css("background-color", "#EF617C");
								obj.fadeOut(500, function () {
									obj.remove();
									switch (tbl) {
										case "remove_uraian":
											onkeypressGlobal(
												{
													jns: "uraian_sub_keg",
													tbl: "renja_p",
												},
												this
											);
											break;
										default:
											break;
									}
									if (typeof form !== "undefined") {
										//console.log(form);
										removeRulesForm(form);
										let reinitForm = new FormGlobal(form);
										reinitForm.run();
										addRulesForm(form);
									}
								});
								break;
							default:
								break;
						}
					}
				},
			})
			.modal("show");
	});
	//==================================
	// download dokumen ke xls/exel
	//==================================
	// download dokumen perencanaan xlsx
	$("body").on("click", '[name="ungguh"]', function (e) {
		e.preventDefault();
		//action="script/eksport_xlsx"
		let dok = $(this).attr("dok");
		let jenis = $(this).attr("jns");
		let tbl = $(this).attr("tbl");
		if (jenis !== "") {
			$('#form_ungguh_dok input[name="jenis"]').val(jenis);
			$('#form_ungguh_dok input[name="tbl"]').val(tbl);
			$('#form_ungguh_dok input[name="dok"]').val(dok);
			$("#form_ungguh_dok").submit();
		} else {
			modal_notif(
				'<i class="info icon"></i>Pilih Dokumen',
				"pilih dokumen perencanaan yang ingin di ungguh"
			);
		}
	});
	//==========================================
	//===========merubah susunan row tabel====
	//==========================================
	$("body").on("click", ".up_row,.down_row", function (e) {
		e.preventDefault();
		var row = $(this).parents("tr:first");
		let srt = "down_row";
		if ($(this).is(".up_row")) {
			row.insertBefore(row.prev());
			srt = "up_row";
		} else {
			row.insertAfter(row.next());
		}
		let jalankanAjax = false;
		let jenis = $(this).attr("jns");
		let id_row = $(this).closest("tr").attr("id_row");
		if (jenis && id_row) {
			switch (jenis) {
				case "rab":
					jalankanAjax = true;
					break;
				default:
					break;
			}
			if (jalankanAjax) {
				var data = {
					jenis: jenis,
					tbl: "sortir",
					srt: srt,
					id_row: id_row,
				};
				var url = "script/post_data";
				suksesAjax["ajaxku"] = function (result) {
					let iconToast = "check circle icon";
					let classToast = "success";
					if (result.success === true) {
						if (result.error.code === 404) {
							classToast = "error";
							iconToast = "exclamation circle icon";
						}
					} else {
						classToast = "error";
						iconToast = "exclamation circle icon";
					}
					showToast(result.error.message, {
						class: classToast,
						icon: iconToast,
					});
				};
				runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
			}
		}
	});
	//=====================================================
	//===========button posting data =======@audit-ok add
	//=====================================================name="direct"
	$("body").on(
		"click",
		'[name="add"],[name="tambah"],[name="direct"]',
		function (e) {
			e.preventDefault();
			let ini = $(this);
			let jenis = ini.attr("jns");
			let tbl = ini.attr("tbl");
			let id_row = ini.attr("id_row");
			let accept = ini.attr("accept");
			const [node] = ini;
			const attrs = {};
			$.each(node.attributes, (index, attribute) => {
				attrs[attribute.name] = attribute.value;
				let attrName = attribute.name;
				//membuat variabel
				let myVariable = attrName;
				window[myVariable] = attribute.value;
			});
			switch (jenis) {
				case "upload":
					switch (tbl) {
						case "wilayah_logo":
						case "asn":
							//upload langsung file
							$("#directupload1").val("");
							id_row = ini.closest("form").attr("id_row");
							$(`#directupload1,form[name="form_upload"]`).attr({
								jns: jenis,
								tbl: tbl,
								id_row: id_row,
								dok: dok,
								accept: accept,
							});
							break;
						case "user":
							//upload langsung file
							$("#directupload1").val("");
							id_row = ini.attr("id_row");
							$(`#directupload1,form[name="form_upload"]`).attr({
								jns: jenis,
								tbl: tbl,
								id_row: id_row,
								dok: dok,
								accept: accept,
							});
							break;
						default:
							break;
					}
					break;
				case "add_row":
					let Tbody = ini.closest("table").find("tbody");
					switch (tbl) {
						case "klm3":
						case "menimbang":
						case "mengingat":
						case "menetapkan":
						case "menetapkan_1":
						case "menetapkan_2":
						case "menetapkan_3":
						case "menetapkan_4":
						case "tembusan":
							let elmTr = createHTML("tr_tabel", {
								bodyTable: [
									[
										{
											lbl: `<div contenteditable=""></div>`,
										},
										{
											lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
										},
										{
											class: "collapsing",
											lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
										},
									],
								],
							});
							Tbody.append(elmTr);
							break;
						case "klm4":
							let elmTr2 = createHTML("tr_tabel", {
								bodyTable: [
									[
										{
											lbl: `<div contenteditable=""></div>`,
										},
										{
											lbl: `<div contenteditable=""></div>`,
										},
										{
											lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
										},
										{
											class: "collapsing",
											lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
										},
									],
								],
							});
							Tbody.append(elmTr2);
							break;
						default:
							break;
					}
					break;
				case "P": //paragraf
				case "L": //list
					let bentuk = jenis === "P" ? "L" : "P";
					let tooltip = jenis === "P" ? "List" : "paragraf";
					ini.attr({ jns: bentuk, "data-tooltip": tooltip }).text(bentuk);
					break;
				default:
					break;
			}
			switch (jenis) {
				case "upload":
					switch (tbl) {
						case "wilayah_logo":
						case "asn":
						case "user":
							$("#directupload1").click();
							break;
						default:
							break;
					}
					break;
				default:
					break;
			}
		}
	);
	$("body").on("change", "#directupload1", function (e) {
		//@audit langsung upload file
		e.preventDefault();
		let ini = $(this);
		let id_row = ini.attr("id_row");
		if (id_row) {
			let myForm = new FormGlobal(`form[name="form_upload"]`);
			myForm.run();
			$(`form[name="form_upload"]`).form("submit");
		}
		// e.stopPropogation();
	});
	///=================================
	///==== IMPOR FILE XLSX=============
	///=================================
	//untik import menjadi baris di tabel modal
	$("body").on("click", "label[for]", function () {
		$("#invisibleupload1")
			.attr("jns", $(this).attr("jns"))
			.attr("tbl", $(this).attr("tbl"));
		$("#invisibleupload1").val("");
	});
	$("body").on("click", 'input[name="dum_file"]', function (e) {
		e.preventDefault();
		let himp = $(this).closest(".action");
		let inputFile = himp.find('input[nama="file"]');
		inputFile.val("");
		inputFile.trigger("click");
	});
	$("body").on("change", 'input[type="file"]', function (e) {
		// e.preventDefault();
		let ini = $(this);
		//Membaca File Excel dengan sheetJS
		let jenis = ini.attr("jns");
		let tbl = ini.attr("tbl");
		let formIni = $('form[name="form_modal"]');
		//console.log('jenis input file :' + jenis);
		if (tbl === "add_row_tabel") {
			let jsonData = [];
			let reader = new FileReader();
			reader.readAsArrayBuffer(e.target.files[0]);
			reader.onload = function (e) {
				let data = new Uint8Array(reader.result);
				let workbook = XLSX.read(data, { type: "array" });
				let first_sheet_name = workbook.SheetNames[0];
				jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[first_sheet_name], {
					header: 1,
				});
				jsonData.forEach((value, key) => {
					let jumlahData = value.length;
					let jumlahKolom = 8;
					switch (jenis) {
						case "analisa_ck":
							jumlahKolom = 7;
							break;
						default:
							break;
					}
					for (var i = 0; i < jumlahKolom; i++) {
						//console.log(value[i])
						if (typeof value[i] === "undefined") {
							value[i] = "";
						}
					}
					let elmForm2 = "";
					let tenporer1 = parseFloat(value[3]);
					let tenporer2 = parseFloat(value[5]);
					switch (true) {
						case jenis == "analisa_ck":
						case jenis == "analisa_sda":
						case jenis == "analisa_bm":
						case jenis == "analisa_alat_custom":
						case jenis == "analisa_quarry":
							let nmrHtml = `<td klm="nomor"><div contenteditable>${value[0]}</div></td>`;
							let ketHtml = `<td klm="keterangan"><div contenteditable>${value[7]}</div></td>`;
							let jumlahHtml = "";
							if (jenis === "analisa_ck") {
								nmrHtml = "";
								ketHtml = "";
								jumlahHtml = '<td klm="jumlah_harga"></td>';
							}
							tenporer1 = parseFloat(value[4]);
							tenporer2 = parseFloat(value[5]);
							elmForm2 = `<tr>${nmrHtml}<td klm="uraian"><div contenteditable>${
								value[1]
							}</div></td><td klm="kode"><div contenteditable>${
								value[2]
							}</div></td><td klm="satuan"><div contenteditable>${
								value[3]
							}</div></td><td klm="koefisien"><div contenteditable rms>${accounting.formatNumber(
								value[4],
								tenporer1.countDecimals(),
								".",
								","
							)}</div></td><td klm="harga_satuan"><div contenteditable rms>${accounting.formatNumber(
								value[5],
								tenporer2.countDecimals(),
								".",
								","
							)}</div></td>${jumlahHtml}<td klm="rumus"><div contenteditable>${
								value[6]
							}</div></td>${ketHtml}<td><div class="ui mini basic icon buttons"><button class="ui button" name="modal_2"><i class="edit teal icon"></i></button><button class="ui button" name="del_row" jns="direct" tbl="del_row"><i class="trash alternate outline icon"></i></button><button class="ui button up_row"><i class="angle up icon"></i></button></div></td></tr>`;
							break;
						default:
							//console.log(`koq ${jenis}`);
							break;
					}
					//console.log(formIni.find('tbody tr:last').length);
					if (formIni.find("tbody tr:last").length > 0) {
						//console.log('opo1');
						//formIni.find('tbody tr:last').after(elmForm2);
						if (jenis === "analisa_ck") {
							//console.log("opo");
							let jumlahRow = formIni.find("tbody tr").length;
							formIni.find("tbody tr:last").before(elmForm2);
						} else {
							formIni.find("tbody tr:last").after(elmForm2);
						}
					} else {
						formIni.find("tbody").html(elmForm2);
					}
				});
				addRulesForm(formIni);
				$("[rms]").mathbiila();
				//var htmlstr = XLSX.write(workbook, { sheet: first_sheet_name, type: 'binary', bookType: 'html' });
				//console.log(htmlstr);
				//$('#wrapper')[0].innerHTML += htmlstr;
			};
		}
		let himp = ini.closest(".action");
		let nama_file = e.target.files[0].name;
		himp.find('input[name="dum_file"]').val(nama_file);
		let file = $(this)[0].files[0].name.toLowerCase(); //this.files[0];
		let arrayAccept = $(this).attr("accept");
		if (arrayAccept !== undefined && arrayAccept.length > 0) {
			arrayAccept = arrayAccept.split(",");
		} else {
			arrayAccept = [".xlsx"];
		}
		//console.log(arrayAccept);
		let fileExp = file.split(".");
		let extFile = "." + fileExp[fileExp.length - 1];
		if (arrayAccept.indexOf(extFile) < 0) {
			//if (!file.endsWith('.xlsx')) {
			modal_notif(
				'<i class="green file excel icon"></i>Pilih File Excel',
				"Pilih file extension " + arrayAccept
			);
			ini.val("");
			return false;
		}
	});
	$("body").on("click", 'button[name="del_file"]', function (e) {
		e.preventDefault();
		var himp = $(this).closest(".action");
		himp.find("input").val("");
	});
	$(".ui.couple.modal").modal({ allowMultiple: true });
	// $('.kedua.modal').modal('attach events', '.mdl_general.modal .button');
	//=========================================
	//===========jalankan modal================@audit-ok modal click
	//=========================================
	$("body").on("click", '[name="modal_show"]', function (e) {
		e.preventDefault();
		const elmIni = this;
		const ini = $(this);
		const [node] = $(this);
		const attrs = {};
		let deactivate = ini.attr("deactivate");
		$.each(node.attributes, (index, attribute) => {
			attrs[attribute.name] = attribute.value;
			let attrName = attribute.name;
			//membuat variabel
			let myVariable = attrName + "Attr";
			window[myVariable] = attribute.value;
		});
		let url = "script/get_data";
		let jalankanAjax = false;
		let mdl = $('.ui.modal[name="mdl_general"]');
		mdl.removeClass(`big tiny`);
		//ubah kop header modal
		let elmIkonModal = $(mdl.find(".big.icons i")[0]); //ganti class icon
		let elmIkonModal2 = $(mdl.find(".big.icons i")[1]); //ganti class icon
		let elmKontenModal = mdl.find("h5 .content");
		let formIni = $('form[name="form_modal"]');
		formIni.removeAttr("id_row");
		let headerModal = "Catatan";
		let data = {
			cari: cari(jnsAttr),
			rows: countRows(),
			jenis: jnsAttr,
			tbl: tblAttr,
			halaman: halaman,
		};
		let elementForm = createHTML("fieldTextIcon", {
			label: "Uraian Pengelompokan Belanja",
			classField: `required`,
			atribut: 'name="uraian" placeholder="pengelompokan belanja..."',
			icon: "edit blue",
		});
		let kelasToast = "warning";
		let pesanToast = "Koreksi Data";
		formIni.attr({ jns: jnsAttr, tbl: tblAttr });
		// console.log(mdl.find('.actions [name="modal_second"]').length);
		if (mdl.find('.actions [name="modal_second"]').length)
			mdl
				.find('.actions [name="modal_second"]')
				.remove(`[name="modal_second"]`);
		switch (jnsAttr) {
			case "edit":
				elementForm = "";
			case "add":
				switch (tblAttr) {
					case "create_surat":
						mdl.addClass("big");
						if (mdl.find('.actions [name="modal_second"]').length <= 0)
							mdl
								.find(".actions")
								.append(
									`<button class="ui primary icon button" name="modal_second" jns="cetak" tbl="sk_asn"><i class="print icon"></i></button>`
								);
						elementForm =
							createHTML("fields", {
								classField: "four",
								content:
									createHTML("fieldDropdown", {
										label: "Jenis Naskah Dinas",
										classField: `required`,
										atribut:
											'name="jenis_naskah_dinas" placeholder="Jenis, Susunan dan bentuk naskah dinas..."',
										kelas: "search clearable selection",
										dataArray: [
											["instruksi", "Instruksi"],
											["surat_edaran", "Surat Edaran"],
											["keputusan", "Keputusan"],
											["surat_perintah", "Surat Perintah atau Surat Tugas"],
											["nota_dinas", "Nota Dinas"],
											["memorandum", "Memorandum"],
											["undangan_internal", "Undangan Internal"],
											["surat_dinas", "Surat Dinas"],
											["perjanjian", "Perjanjian Dalam Negeri"],
											["surat_kuasa", "Surat Kuasa"],
											["berita_acara", "Berita Acara"],
											["surat_keterangan", "Surat Keterangan"],
											["surat_pengantar", "Surat Pengantar"],
											["pengumuman", "Pengumuman"],
											["laporan", "Laporan"],
											["telaah_staf", "Telaah Staf"],
										],
									}) +
									createHTML("fieldText", {
										label: "Nomor Surat",
										classField: `required`,
										atribut: `name="nomor" placeholder="Nomor Surat"`,
									}) +
									createHTML("fieldCalendar", {
										classField: `required`,
										kelas: "date",
										label: "Tanggal Surat",
										atribut: `name="tgl_surat_dibuat" placeholder="tanggal" readonly`,
									}) +
									createHTML("fieldDropdown", {
										label: "Kategori klasifikasi keamanan",
										atribut: 'name="klasifikasi_keamanan"',
										kelas: "lainnya selection",
										dataArray: [
											["sr", "sangat rahasia"],
											["r", "Rahasia"],
											["t", "Terbatas"],
											["b", "biasa/terbuka"],
										],
									}),
							}) +
							createHTML("fieldFileInput2", {
								label: "Pilih File Dokumen",
								placeholderData: "Pilih File...",
								accept: ".jpg,.jpeg,.png,.pdf,.docx",
								atribut: "non_data",
							}) +
							createHTML("fields", {
								classField: "three",
								content:
									createHTML("fieldDropdown", {
										label: "Pejabat yang menetapkan",
										classField: `ten wide required`,
										atribut:
											'name="pemberi_tgs" placeholder="Pemberi Tugas..."',
										kelas: "search clearable pemberi_tgs ajx selection",
										dataArray: [],
									}) +
									createHTML("fieldText", {
										label: "Nama Jabatan",
										classField: `three wide required`,
										atribut: `name="jbt_pemberi_tgs" placeholder="Jabatan Pemberi Tugas"`,
									}) +
									createHTML("fieldText", {
										label: "Pangkat Pemberi Tugas",
										classField: `three wide required`,
										atribut: `name="pangkat_pemberi_tgs" placeholder="Nomor Surat"`,
									}),
							}) +
							createHTML("div", {
								//@audit sekarang elm
								atribut: `name="elm_naskah"`,
							});
						break;
					case "sk_asn":
						mdl.addClass("big");
						if (mdl.find('.actions [name="modal_second"]').length <= 0)
							mdl
								.find(".actions")
								.append(
									`<button class="ui primary icon button" name="modal_second" jns="cetak" tbl="sk_asn"><i class="print icon"></i></button>`
								);
						elementForm =
							createHTML("message", {
								color: "positive",
								icon: `exclamation icon`,
								label: `Perhatian`,
								value: `<ul class="list"><li>jika mengedit data dan merubah "Nomor Surat" maka akan dianggap tambah data baru jika di simpan;</li><li>simpan terlebih dahulu sebelum cetak dokumen.</li></ul>`,
							}) +
							createHTML("fields", {
								classField: "three",
								content:
									createHTML("fieldText", {
										label: "Nomor Surat",
										classField: `required`,
										atribut: `name="nomor" placeholder="Nomor Surat"`,
									}) +
									createHTML("fieldCalendar", {
										classField: `required`,
										kelas: "date",
										label: "Tanggal Surat",
										atribut: `name="tgl_surat_dibuat" placeholder="tanggal" readonly`,
									}) +
									createHTML("fieldFileInput2", {
										label: "Pilih File Dokumen",
										placeholderData: "Pilih File...",
										accept: ".jpg,.jpeg,.png,.pdf,.docx",
										atribut: "non_data",
									}),
							}) +
							createHTML("fieldTextarea", {
								label: "Tentang",
								classField: `required`,
								atribut: `name="tentang" placeholder="tentang" rows="2"`,
							}) +
							createHTML("fields", {
								classField: "three",
								content:
									createHTML("fieldDropdown", {
										label: "Pemberi Tugas",
										classField: `ten wide required`,
										atribut:
											'name="pemberi_tgs" placeholder="Pemberi Tugas..."',
										kelas: "search clearable pemberi_tgs ajx selection",
										dataArray: [],
									}) +
									createHTML("fieldText", {
										label: "Jabatan Pemberi Tugas",
										classField: `three wide required`,
										atribut: `name="jbt_pemberi_tgs" placeholder="Jabatan Pemberi Tugas"`,
									}) +
									createHTML("fieldText", {
										label: "Pangkat Pemberi Tugas",
										classField: `three wide required`,
										atribut: `name="pangkat_pemberi_tgs" placeholder="Nomor Surat"`,
									}),
							}) +
							createHTML("divider", {
								header: "h4",
								icon2: `<i class="feather alternate icon"></i>`,
								label: `MENIMBANG`,
							}) +
							createHTML("tabel2", {
								atribut: `name="menimbang"`,
								kelas: `celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `URAIAN` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="menimbang"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [
									{
										lbl: ``,
									},
									{
										lbl: "",
										attr: ``,
									},
									{
										lbl: "",
										attr: ``,
									},
								],
								bodyTable: [],
							}) +
							createHTML("divider", {
								header: "h4",
								icon2: `<i class="feather alternate icon"></i>`,
								label: `MENGINGAT`,
							}) +
							createHTML("tabel2", {
								atribut: `name="mengingat"`,
								kelas: `celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `URAIAN` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="mengingat"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [
									{
										lbl: ``,
									},
									{
										lbl: "",
										attr: ``,
									},
									{
										lbl: "",
										attr: ``,
									},
								],
								bodyTable: [],
							}) +
							createHTML("divider", {
								header: "h4",
								icon2: `<i class="feather alternate icon"></i>`,
								label: `MENETAPKAN`,
							}) +
							createHTML("tabel2", {
								atribut: `name="menetapkan_1"`,
								kelas: `stackable celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `KESATU` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="menetapkan_1"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [],
								bodyTable: [],
							}) +
							createHTML("tabel2", {
								atribut: `name="menetapkan_2"`,
								kelas: `stackable celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `KEDUA` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="menetapkan_2"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [],
								bodyTable: [],
							}) +
							createHTML("tabel2", {
								atribut: `name="menetapkan_3"`,
								kelas: `stackable celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `KETIGA` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="menetapkan_3"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [],
								bodyTable: [],
							}) +
							createHTML("tabel2", {
								atribut: `name="menetapkan_4"`,
								kelas: `stackable celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `KEEMPAT` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="menetapkan_4"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [],
								bodyTable: [],
							}) +
							createHTML("divider", {
								header: "h4",
								icon2: `<i class="users icon"></i>`,
								label: `ASN YANG DITUGASKAN`, //@audit cetak sk
							}) +
							createHTML("fieldDropdown", {
								label: "Nama ASN ditugaskan",
								atribut:
									'name="asn" placeholder="Nama ASN ditugaskan..." non_data',
								kelas: "search clearable asn ajx selection",
								dataArray: [],
							}) +
							createHTML("tabel2", {
								atribut: `name="nama_ditugaskan"`,
								kelas: `stackable celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `NAMA` },
										{
											attr: "",
											class: "collapsing",
											lbl: `PANGKAT/GOLONGAN`,
										},
										{
											attr: "",
											class: "collapsing",
											lbl: `NIP`,
										},
										{ attr: "", lbl: `JABATAN` },
										{ attr: "", lbl: `JABATAN SK` },
										{
											attr: "",
											lbl: ``,
											class: "collapsing",
										},
									],
								],
								footerTable: [],
								bodyTable: [],
							}) +
							createHTML("fielToggleCheckbox", {
								label: "",
								atribut: 'name="bentuk_lampiran" non_data',
								txtLabel: "Lampiran SK bentuk Tabel",
							}) +
							createHTML("divider", {
								header: "h4",
								icon2: `<i class="feather alternate icon"></i>`,
								label: `TEMBUSAN`,
							}) +
							createHTML("tabel2", {
								atribut: `name="tembusan"`,
								kelas: `celled structured`,
								headerTable: [
									[
										{ attr: "", lbl: `URAIAN` },
										{
											attr: "",
											class: "collapsing",
											lbl: `JENIS`,
										},
										{
											attr: "",
											lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="menetapkan"><i class="plus icon"></i></button>`,
											class: "collapsing",
										},
									],
								],
								footerTable: [],
								bodyTable: [],
							}) +
							createHTML("fieldTextarea", {
								label: "Keterangan",
								atribut:
									'name="keterangan" placeholder="keterangan" rows="2" non_data',
							}) +
							createHTML("fielToggleCheckbox", {
								label: "",
								atribut: 'name="disable" non_data',
								txtLabel: "Non Aktif",
							}) +
							createHTML("dividerHidden", {});
						break;
					case "realisasi":
						mdl.addClass("big");
						if (jnsAttr === "add") {
							elementForm = createHTML("fieldSearchGrid", {
								label: "Uraian Pengelompokan Belanja",
								kelas: `sub_keg_dpa`,
								atribut:
									'name="nama_paket_search" placeholder="Cari Nama Paket..."',
							});
						}
						elementForm +=
							createHTML("fieldTextarea", {
								label: "Nama Paket",
								atribut:
									'name="uraian" placeholder="uraian..." rows="3" readonly',
							}) +
							createHTML("text", {
								atribut:
									'name="jumlah_realisasi" placeholder="jumlah realisasi" rules="decimal" hidden',
							}) +
							createHTML("fields", {
								classField: "three",
								label: "Jadwal Pengadaan",
								content:
									createHTML("fieldText", {
										label: "Volume",
										atribut: `name="volume" placeholder="Volume" non_data readonly`,
									}) +
									createHTML("fieldText", {
										classField: "",
										label: "Satuan",
										atribut: `name="satuan" placeholder="Satuan" non_data readonly`,
									}) +
									createHTML("fieldText", {
										label: "Jumlah Kontrak",
										atribut: `name="jumlah" placeholder="Jumlah Kontrak" non_data readonly`,
									}),
							}) +
							createHTML("divider", {
								header: "h5",
								aligned: "left aligned",
								icon2: `<i class="feather alternate icon"></i>`,
								label: `Rincian Paket`,
							}) +
							createHTML("fields", {
								classField: "two",
								label: "Jadwal Pengadaan",
								content:
									createHTML("fieldCalendar", {
										label: "Tanggal",
										atribut:
											'placeholder="Input Tanggal SPJ..." name="tanggal" readonly',
										kelas: "date",
									}) +
									createHTML("fieldFileInput2", {
										label: "Pilih File Dokumen",
										placeholderData: "Pilih File...",
										accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx",
										atribut: "non_data",
									}),
							}) +
							createHTML("div", {
								kelas: "ui scrolling",
								content: createHTML("tabel2", {
									kelas: `head foot stuck unstackable celled mini structured tblUraian`,
									headerTable: [
										[
											{
												attr: 'rowspan="2"',
												class: "collapsing",
												lbl: `SUB KEGIATAN`,
											},
											{
												attr: 'rowspan="2"',
												lbl: `URAIAN`,
											},
											{
												attr: 'colspan="4"',
												lbl: `KONTRAK`,
												class: "center aligned",
											},
											{
												attr: 'colspan="2"',
												lbl: `SUM REALISASI`,
												class: "center aligned",
											},
											{
												attr: 'colspan="2"',
												lbl: `INPUT REALISASI`,
												class: "center aligned",
											},
											{
												class: "center aligned collapsing",
												attr: 'rowspan="2"',
												lbl: `AKSI`,
											},
										],
										[
											{
												class: "center aligned collapsing",
												lbl: `VOL.`,
											},
											{
												class: "center aligned collapsing",
												lbl: `SAT.`,
											},
											{
												class: "center aligned collapsing",
												lbl: `PAGU`,
											},
											{
												class: "center aligned collapsing",
												lbl: `KONTRAK`,
											},
											{
												class: "center aligned collapsing",
												lbl: `VOL.`,
											},
											{
												class: "center aligned collapsing",
												lbl: `JUMLAH`,
											},
											{ lbl: `VOL.` },
											{ lbl: `JUMLAH` },
										],
									],
									footerTable: [
										{
											lbl: `jumlah`,
											attr: `colspan="2"`,
										},
										{
											lbl: 0,
											attr: `name="vol_kontrak"`,
										},
										{
											lbl: "",
											attr: ``,
										},
										{
											lbl: 0,
											attr: `name="pagu"`,
										},
										{
											lbl: 0,
											attr: `name="jumlah_kontrak"`,
										},
										{
											lbl: 0,
											attr: `name="realisasi_vol"`,
										},
										{
											lbl: 0,
											attr: `name="realisasi_jumlah"`,
										},
										{
											lbl: 0,
											attr: `name="vol"`,
										},
										{
											lbl: 0,
											attr: `name="jumlah"`,
										},
										{
											lbl: "",
											attr: ``,
										},
									],
									bodyTable: [],
								}),
							}) +
							createHTML("fieldTextarea", {
								label: "Keterangan",
								atribut:
									'name="keterangan" placeholder="keterangan spj" rows="2"',
							});
						break;
				}
				break;
			case "get_field_json":
			case "add_field_json":
				switch (tblAttr) {
					case "sub_keg_dpa":
					case "sub_keg_renja":
						mdl.addClass("tiny");
						let id_sub_keg = ini.closest(".ui.form").attr("id_sub_keg");
						data.id_sub_keg = id_sub_keg;
						data.klm = klmAttr;
						let jenis_kelompok = klmAttr;
						switch (klmAttr) {
							case "kelompok_json":
								jenis_kelompok = ini
									.closest(".ui.form")
									.find('.ui.dropdown[name="jenis_kelompok"')
									.dropdown("get value");
								break;
							default:
								jenis_kelompok = klmAttr;
								break;
						}
						if (klmAttr == "kelompok" || klmAttr == "paket") {
						}
						switch (klmAttr) {
							case "kelompok_json":
								switch (jenis_kelompok) {
									case "paket":
										headerModal = "Tambah Uraian Pemaketan Belanja";
										break;
									case "kelompok":
										headerModal = "Tambah Uraian Pengelompokan Belanja";
										break;
									default:
										nameAttr = "";
										pesanToast = "Pilih Pengelompokan Belanja";
										break;
								}
								break;
							case "keterangan_json":
								headerModal = "Keterangan Uraian Belanja";
								break;
							default:
								break;
						}
						data.jenis_kelompok = jenis_kelompok;
						formIni
							.attr("jns", jnsAttr)
							.attr("tbl", tblAttr)
							.attr("klm", klmAttr)
							.attr("id_sub_keg", id_sub_keg)
							.attr("jns_kel", jenis_kelompok);
						break;
					default:
						break;
				}
				break;
			case "search_field_json":
				switch (tblAttr) {
					case "sbu":
					case "ssh":
					case "hspk":
					case "asb":
						var kelasSearch = ini
							.closest(".ui.form")
							.find('.ui.dropdown[name="jenis_standar_harga"')
							.dropdown("get value");
						elementForm =
							createHTML("text", {
								atribut:
									'name="id" placeholder="Pilih komponen" readonly hidden',
							}) +
							createHTML("fieldSearchGrid", {
								kelas2: "",
								kelas: `${kelasSearch}`,
								atribut: 'name="uraian" non_data',
							}) +
							createHTML("fieldText", {
								label: "Kode Komponen",
								atribut:
									'name="kd_aset" placeholder="kode komponen..." non_data readonly',
							}) +
							createHTML("fieldTextarea", {
								label: "Uraian",
								atribut:
									'name="uraian_barang" placeholder="uraian..." rows="2" readonly',
							}) +
							createHTML("fieldTextarea", {
								label: "Spesifikasi",
								atribut:
									'name="spesifikasi" placeholder="spesifikasi..." rows="2" non_data readonly',
							}) +
							createHTML("fieldText", {
								label: "Satuan",
								kelas: "",
								atribut:
									'name="satuan" placeholder="satuan..."non_data readonly',
							}) +
							createHTML("fieldText", {
								label: "Harga Satuan Komponen",
								atribut:
									'name="harga_satuan" placeholder="Harga Satuan Komponen" rms non_data readonly',
							}) +
							createHTML("fieldText", {
								label: "TKDN",
								atribut: 'name="tkdn" placeholder="tkdn..." non_data readonly',
							}) +
							createHTML("fieldTextarea", {
								label: "Mapping Kode Akun",
								atribut:
									'name="kd_akun" placeholder="Mapping Kode Akun..." rows="2" readonly',
							}) +
							createHTML("fieldTextarea", {
								label: "Keterangan",
								atribut:
									'name="keterangan" placeholder="keterangan..." rows="3" non_data readonly',
							}) +
							createHTML("fielToggleCheckbox", {
								label: "",
								atribut: 'name="disable" non_data readonly',
								txtLabel: "Non Aktif",
							});
						break;
					default:
						break;
				}
				break;
			case "uraian_belanja":
				switch (tblAttr) {
					case "daftar_paket":
						mdl.addClass("big");
						let json_id_uraian = $(`form[name="form_flyout"]`).form(
							"get value",
							"id_uraian"
						);
						if (json_id_uraian.length > 2) {
							json_id_uraian = JSON.parse(
								$(`form[name="form_flyout"]`).form("get value", "id_uraian")
							);
							jalankanAjax = true;
							data.jenis = "get_rows";
							data.tbl = "dpa_and_dppa"; //jenis tabel tergantung dari data dok_anggaran tiap baris
							data.send = JSON.stringify(json_id_uraian);
						}
						formIni.attr("jns", "add_uraian").attr("tbl", tblAttr);
						mdl.removeClass("tiny");
						elementForm =
							createHTML("fieldSearchGrid", {
								kelas: `uraian`,
								atribut: 'name="uraian" placeholder="cari..." non_data',
							}) +
							createHTML("fieldDropdown", {
								label: "Sub Kegiatan",
								atribut:
									'name="kd_sub_keg" placeholder="pilih sub kegiatan..." non_data',
								kelas: "search clearable kd_sub_keg ajx selection",
								dataArray: [],
							}) +
							createHTML("text", {
								atribut:
									'name="jumlah" placeholder="jumlah kontrak" rules="decimal" hidden',
							}) +
							createHTML("divider", {
								header: "h5",
								aligned: "left aligned",
								icon2: `<i class="feather alternate icon"></i>`,
								label: `Uraian`,
							}) +
							createHTML("tabel", {
								headerTable: [
									{
										class: "collapsing",
										lbl: `SUB KEGIATAN`,
									},
									{ lbl: `URAIAN` },
									{ lbl: `VOL.` },
									{ lbl: `SAT.` },
									{ lbl: `PAGU` },
									{
										lbl: `KONTRAK`,
									},
									{
										lbl: `AKSI`,
									},
								],
								footerTable: [
									{
										lbl: `jumlah`,
										attr: `colspan="4"`,
									},
									{
										lbl: 0,
										attr: `name="jumlah"`,
									},
									{
										lbl: 0,
										attr: `colspan="2" name="kontrak"`,
									},
								],
								bodyTable: [],
							});
						break;
					default:
						break;
				}
				break;
			case "modal_show":
				switch (tblAttr) {
					case "berita":
						elementForm =
							createHTML("piledSegment", {
								atribut: `name="${klmAttr}" `,
							}) +
							createHTML("fieldTextarea", {
								label: "Markup HTML",
								atribut: `name="${klmAttr}" placeholder="keterangan..." rows="5"`,
							});
						break;
				}
				break;
			case "get_data":
				switch (tblAttr) {
					case "realisasi":
						break;
				}
				break;
			case "xxx":
				break;
			default:
				break;
		}
		elementForm += createHTML("errorForm");
		formIni.html(elementForm);
		$(".ui.sticky").sticky("refresh");
		let modalGeneral = new ModalConstructor(mdl);
		modalGeneral.globalModal();
		let InitializeForm = new FormGlobal(formIni);
		InitializeForm.run();

		document.getElementById("header_mdl").textContent = headerModal;
		let calendarDate = new CalendarConstructor(".ui.calendar.date");
		calendarDate.runCalendar();
		let calendarDateTime = new CalendarConstructor(".ui.calendar.datetime");
		calendarDateTime.Type("datetime");
		calendarDateTime.runCalendar();
		let calendarYear = new CalendarConstructor(".ui.calendar.year");
		calendarYear.Type("year");
		calendarYear.runCalendar();
		if ($(`a[name="change_themes"]`).attr("theme") === "dark") {
			$(".form .icon").removeClass("inverted").addClass("inverted");
		}
		addRulesForm(formIni);
		switch (jnsAttr) {
			case "edit":
				jalankanAjax = true;
				data.id_row = ini.attr("id_row");
			case "add":
				switch (tblAttr) {
					case "create_surat":
						var dropdownASN = new DropdownConstructor(
							'form[name="form_modal"] .ui.dropdown.pemberi_tgs.ajx'
						);
						dropdownASN.returnList({
							jenis: "get_row_json",
							tbl: "asn",
							attrresponserver: {
								golongan: "golongan",
								ruang: "ruang",
							},
						});
						var dropdownJenisNaskah = new DropdownConstructor(
							'form[name="form_modal"] .ui.dropdown[name="jenis_naskah_dinas"]'
						);
						dropdownJenisNaskah.onChange({
							jenis: "non",
							tbl: "create_surat",
						});
						break;
					case "realisasi":
						var searchPaket = new SearchConstructor(
							'form[name="form_modal"] .ui.search'
						);
						let allField = {
							minCharacters: 3,
							searchDelay: 600,
							jenis: "get_Search_Json",
							tbl: "daftar_paket",
							data_send: {},
						};
						searchPaket.searchGlobal(allField);
						break;
					case "sk_asn":
						var dropdownASN = new DropdownConstructor(
							'form[name="form_modal"] .ui.dropdown.pemberi_tgs.ajx'
						);
						dropdownASN.returnList({
							jenis: "get_row_json",
							tbl: "asn",
							attrresponserver: {
								golongan: "golongan",
								ruang: "ruang",
							},
						});
						var dropdownASNTugas = new DropdownConstructor(
							'form[name="form_modal"] .ui.dropdown.asn.ajx'
						);
						dropdownASNTugas.returnList({
							jenis: "get_row_json",
							tbl: "asn",
							attrresponserver: {
								golongan: "golongan",
								ruang: "ruang",
							},
						});
						break;
				}
				break;
			case "modal_show":
				switch (tblAttr) {
					case "berita":
						let komponen = $(`form[name="form_flyout"]`).form(
							"get value",
							klmAttr
						);
						$(`form[name="form_modal"]`).form("set value", klmAttr, komponen);
						let elmTambahan = createHTML("ribbonLabel", {
							label: "Result HTML",
							posisi: "right",
							kelas: "yellow",
							atribut: `name="${klmAttr}" placeholder="keterangan..."`,
						});
						$(`form[name="form_modal"]`)
							.find(".segment")
							.html(elmTambahan + komponen);
						break;
				}
				break;
			case "uraian_belanja":
				switch (tblAttr) {
					case "daftar_paket":
						var dropdown_ajx_sub_keg = new DropdownConstructor(
							'form[name="form_modal"] .ui.dropdown.kd_sub_keg.ajx.selection'
						);
						dropdown_ajx_sub_keg.returnList({
							jenis: "get_row_json",
							tbl: "sub_keg_dpa",
						});
						break;
					default:
						break;
				}
				break;
			case "search_field_json":
				switch (tblAttr) {
					case "sbu":
					case "ssh":
					case "hspk":
					case "asb":
						var searchHargaSat = new SearchConstructor(
							'form[name="form_modal"] .ui.search'
						);
						let kd_akun = $(`form[name="form_flyout"]`).form(
							"get value",
							"kd_akun"
						);
						let allField = {
							minCharacters: 3,
							searchDelay: 600,
							jenis: "get_Search_Json",
							tbl: tblAttr,
							data_send: { kd_akun: kd_akun },
						};
						searchHargaSat.searchGlobal(allField);
						let komponen = $(`form[name="form_flyout"]`).form(
							"get value",
							"komponen"
						);
						if (komponen > 0) {
							jalankanAjax = true;
							data.id_row = komponen;
							data.jenis = "get_row";
						}
						break;
					default:
						break;
				}
				break;
			case "xxx":
				break;
			default:
				break;
		}
		if (jalankanAjax) {
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					loaderHide();
					switch (jnsAttr) {
						case "search_field_json":
							switch (tblAttr) {
								case "sbu":
								case "ssh":
								case "hspk":
								case "asb":
									let strText = parseFloat(result?.data?.users?.harga_satuan);
									strText = accounting.formatNumber(
										result?.data?.users?.harga_satuan,
										strText.countDecimals(),
										".",
										","
									);
									$(`form[name="form_modal"]`).form("set values", {
										id: result?.data?.users?.id,
										kd_aset: result?.data?.users?.kd_aset,
										uraian_barang: result?.data?.users?.uraian_barang,
										spesifikasi: result?.data?.users?.spesifikasi,
										satuan: result?.data?.users?.satuan,
										harga_satuan: strText,
										tkdn: result?.data?.users?.tkdn,
										kd_akun: result?.data?.users?.kd_akun,
										keterangan: result?.data?.users?.keterangan,
										disable: result?.data?.users?.disable,
									});
									break;
								default:
									break;
							}
							break;
						case "uraian_belanja":
							switch (tblAttr) {
								case "daftar_paket":
									formIni.find(`table tbody`).html(result.data.users);
									onkeypressGlobal(
										{
											jns: "uraian_sub_keg",
											tbl: "renja_p",
										},
										formIni
									);
									break;
								default:
									break;
							}
							break;
						case "edit":
							// set nilai form
							let elmAttrName = formIni.find("input[name],textarea[name]");
							switch (tblAttr) {
								case "xcv":
									break;
								default: //isi form dengan data
									for (const iterator of elmAttrName) {
										let attrElm = $(iterator).attr("name");
										let postDataField = true;
										let dropDownElmAjx =
											$(iterator).closest(".ui.dropdown.ajx");
										let text = `${result.data?.users[attrElm]}`;
										let position = text.search("file");
										if (attrElm === "file" || position >= 0) {
											formIni.form(
												"set value",
												"dum_file",
												result.data?.users[attrElm]
											);
										} else {
											let strText = null;
											let cariAttrRms = $(iterator).attr("rms");
											// console.log(`cariAttrRms : ${cariAttrRms}`);
											if (
												typeof cariAttrRms === "undefined" ||
												cariAttrRms === false
											) {
												strText = result.data?.users[attrElm];
											} else {
												strText = parseFloat(result.data?.users[attrElm]);
												strText = accounting.formatNumber(
													result.data?.users[attrElm],
													strText.countDecimals(),
													".",
													","
												);
											}
											if (
												dropDownElmAjx.length > 0 &&
												result.data.hasOwnProperty("values")
											) {
												if (result.data?.values[attrElm]) {
													postDataField = false;
													switch (tblAttr) {
														case "sk_asn":
															switch (attrElm) {
																case "pemberi_tgs":
																	dropdownASN.valuesDropdown(
																		result.data?.values?.pemberi_tgs
																	);
																	dropdownASN.returnList({
																		jenis: "get_row_json",
																		tbl: "asn",
																	});
																	break;
															}
															break;
														default:
															break;
													}
												}
											}
											if (postDataField) {
												formIni.form("set value", attrElm, strText);
											}
										}
									}
									switch (tblAttr) {
										case "sk_asn":
											formIni.attr("id_row", result.data?.users.id);
											//insert semua data di tabel fom
											let allTable = $(`form[name="form_modal"] table[name]`);
											allTable.each(function (index, element) {
												// element == this
												let tbodyElemen = $(this).find("tbody");
												let attrTable = $(this).attr("name");
												// console.log(attrTable);
												if (
													result.data?.users.hasOwnProperty(attrTable) !== false
												) {
													let dataResult = JSON.parse(
														result.data?.users[attrTable]
													);
													Object.keys(dataResult).forEach((key) => {
														// console.log(key, dataResult[key]);
														let p_l = dataResult[key].hasOwnProperty("p_l")
															? dataResult[key]["p_l"]
															: "L";
														let tooltip = p_l === "P" ? "Paragraf" : "List";
														let keyObject = Object.keys(dataResult[key]);
														switch (attrTable) {
															case "nama_ditugaskan":
																let elmTrName = createHTML("tr_tabel", {
																	bodyTable: [
																		[
																			{
																				lbl: `<div contenteditable="">${dataResult[key]["nama"]}</div>`,
																			},
																			{
																				lbl: `<div contenteditable="">${dataResult[key]["pangkat"]}</div></td>`,
																			},
																			{
																				lbl: `<div contenteditable="">${dataResult[key]["nip"]}</div>`,
																			},
																			{
																				lbl: `<div contenteditable="">${dataResult[key]["jabatan"]}</div>`,
																			},
																			{
																				lbl: `<div contenteditable="">${dataResult[key]["jabatan_sk"]}</div>`,
																			},
																			{
																				class: "collapsing",
																				lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																			},
																		],
																	],
																});
																tbodyElemen.append(elmTrName);
																break;
															default:
																let elmTr = createHTML("tr_tabel", {
																	bodyTable: [
																		[
																			{
																				lbl: `<div contenteditable="">${
																					dataResult[key][keyObject[0]]
																				}</div>`,
																			},
																			{
																				lbl: `<button class="ui teal mini button" name="add" jns="${p_l}" data-tooltip="${tooltip}">${p_l}</button>`,
																			},
																			{
																				class: "collapsing",
																				lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																			},
																		],
																	],
																});
																tbodyElemen.append(elmTr);
																break;
														}
													});
												}
											});
											break;
										case "realisasi":
											formIni
												.find(`table.tblUraian tbody`)
												.html(result.data.tbody);
											onkeypressGlobal(
												{
													jns: "realisasi",
													tbl: "vol_realisasi",
												},
												formIni
											);
										case "value1":
											break;
										default:
											break;
									}

									break;
							}

							break;
						case "add":
							switch (tblAttr) {
								case "realisasi":
									formIni.find(`table.tblUraian tbody`).html(result.data.tbody);
									onkeypressGlobal(
										{
											jns: "realisasi",
											tbl: "vol_realisasi",
										},
										formIni
									);
								case "sk_asn":
									switch (tblAttr) {
										case "sk_asn":
											dropdownASN.returnList({
												jenis: "get_row_json",
												tbl: "asn",
											});
											break;
										default:
											break;
									}
									let elmtInputTextarea = formIni.find(
										$("input[name],textarea[name]")
									);
									for (const iterator of elmtInputTextarea) {
										let atribut = $(iterator).attr("name");
										formIni.form(
											"set value",
											atribut,
											result.data.users[atribut]
										);
									}
									//jika edit maka disabled tanggal
									switch (tblAttr) {
										case "realisasi":
											formIni.find(`.ui.calendar.date`).addClass("disabled");
											break;
										default:
											break;
									}
									break;
								default:
									break;
							}
							break;
						default:
							//onkeypressGlobal({ jns: 'realisasi', tbl: 'vol_realisasi' },this);
							break;
					}
					if (nameAttr === "modal_show") {
						mdl.modal("show");
					}
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		} else {
			if (nameAttr === "modal_show") {
				mdl.modal("show");
				kelasToast = "success";
			} else {
				showToast(pesanToast, {
					class: kelasToast,
					icon: "check circle icon",
				});
			}
		}
		switch (tblAttr) {
			case "sbu":
			case "ssh":
			case "hspk":
			case "asb":
				// $('.ui.sticky.melayang').sticky({
				// 	context: '.ui.form[name="form_modal"]',
				// 	pushing: true
				// });
				// $('.ui.sticky').sticky('refresh');
				break;
			default:
				break;
		}
		// disable submit jika deactivate
		formIni
			.closest(".flyout")
			.find(".ui.ok.button, .ui.dropdown")
			.removeClass("disabled");
		if (deactivate) {
			formIni
				.closest(".flyout")
				.find(".ui.ok.button, .ui.dropdown")
				.addClass("disabled");
			formIni.find(".ui.button, .ui.dropdown").addClass("disabled");
			formIni.find("input, textarea").attr("readonly", 1);
		}
		$("[rms]").mathbiila();
	});
	//=========================================
	//===========jalankan modal================@audit-ok modalsecond click
	//=========================================
	$("body").on("click", '[name="modal_second"]', function (e) {
		e.preventDefault();
		const ini = $(this);
		const [node] = $(this);
		const attrs = {};
		let deactivate = ini.attr("deactivate");
		$.each(node.attributes, (index, attribute) => {
			attrs[attribute.name] = attribute.value;
			let attrName = attribute.name;
			//membuat variabel
			let myVariable = attrName + "Attr";
			window[myVariable] = attribute.value;
		});
		let url = "script/get_data";
		let jalankanAjax = false;
		let mdl = $('.ui.kedua.modal[name="mdl_kedua"]');
		// mdl.addClass("large");
		//ubah kop header modal
		let elmIkonModal = $(mdl.find(".big.icons i")[0]); //ganti class icon
		let elmIkonModal2 = $(mdl.find(".big.icons i")[1]); //ganti class icon
		let elmKontenModal = mdl.find("h5 .content");
		let formIni = $('form[name="form_modal_kedua"]');
		let headerModal = "Catatan";
		let data = {
			cari: cari(jnsAttr),
			rows: countRows(),
			jenis: jnsAttr,
			tbl: tblAttr,
			halaman: halaman,
		};
		let elementForm = "";
		let kelasToast = "warning";
		let pesanToast = "Koreksi Data kedua";
		let iconToast = "check circle icon";
		formIni.attr({ jns: jnsAttr, tbl: tblAttr });
		switch (jnsAttr) {
			case "get_data":
				switch (tblAttr) {
					case "realisasi":
						elementForm +=
							createHTML("fieldText", {
								label: "Sub Kegiatan",
								atribut: 'name="uraian" placeholder="uraian..." readonly',
							}) +
							createHTML("fieldText", {
								label: "Akun Belanja",
								atribut: 'name="uraian" placeholder="uraian..." readonly',
							});
						data.id_row = id_rowAttr;
						jalankanAjax = true;
						break;
				}
				break; //'name="modal_second" jns="cetak" tbl="sk_asn',
			case "cetak":
				switch (tblAttr) {
					case "sk_asn": //@audit cetak sk
						var id_row_form_modal = $('form[name="form_modal"]').attr("id_row");
						elementForm =
							createHTML("text", {
								atribut:
									'name="id_row" placeholder="surat keputusan" rules="decimal" hidden',
							}) +
							createHTML("fields", {
								classField: "two",
								content:
									createHTML("fieldDropdown", {
										label: "Dokumen",
										classField: `required`,
										atribut: 'name="dokumen" placeholder="Jenis Dokumen..."',
										kelas: "read-only selection lainnya",
										dataArray: [
											["sk_asn", "Surat Keputusan"],
											["kontrak", "Kontrak"],
											["laporan_realisasi", "Laporan Realisasi"],
										],
									}) +
									createHTML("fieldCalendar", {
										classField: `required`,
										kelas: "date",
										label: "Tanggal Surat",
										atribut: `name="tgl_surat_dibuat" placeholder="tanggal" disabled`,
									}),
							}) +
							createHTML("fields", {
								classField: "two",
								content:
									createHTML("fieldDropdown", {
										label: "Ukuran Kertas",
										classField: `twelve wide required`,
										atribut:
											'name="ukuran_kertas" placeholder="Ukuran Kertas..."',
										kelas: "selection lainnya",
										dataArray: [
											[
												"header",
												'<i class="tags icon"></i>Pilih Ukuran Kertas',
											],
											[
												"letter",
												"Letter ( 216 x 279 ) mm = ( 8.50 x 11.00 ) in",
											],
											["legal", "Legal ( 216 x 356 ) mm = ( 8.50 x 14.00 ) in"],
											["divider"],
											["A4", "A4 ( 210 x 297 ) mm = ( 8,27 x 11,69 ) in"],
											["A3", "A3 ( 297 × 420 ) mm = ( 11,69 x 16,54 ) in"],
											["divider"],
											[
												"F4",
												"F4 ( 215 × 330 ) mm = ( 11,69 x 16,54 ) in",
												"active",
											],
											["custom", "Custom"],
										], //Kertas Seri A merupakan serangkaian kertas yang ukurannya diatur oleh ISO 216. Rasio tinggi dan lebar setiap kertas sama, yaitu 1:1,41.A0: 841 x 1.189 mm atau 33,11 x 46,81 inci.
									}) +
									createHTML("fieldText", {
										classField: `four wide required`,
										kelas: "date",
										label: "Ukuran Huruf (%)",
										atribut: `name="ukuran_huruf" placeholder="Ukuran Huruf (%)" rms value="100"`,
									}),
							}) +
							createHTML("icon_menu", {
								label: "Orientasi",
								atribut: 'name="orientasi" value="portrait"',
								kelas: "",
								dataArray: [
									{
										class: "aksi active",
										lbl: '<i class="id badge icon"></i>Portrait',
										attr: 'value="portrait" jns="orientasi"',
									},
									{
										class: "aksi",
										lbl: '<i class="id card icon"></i>Lanscape',
										attr: 'value="lanscape" jns="orientasi"',
									},
								],
							}) +
							createHTML("dividerClearing") +
							createHTML("header", {
								header: "h3",
								content: "Margin Halaman (mm)",
							}) +
							createHTML("fields", {
								classField: "two",
								content:
									createHTML("fieldText", {
										label: "Atas",
										classField: `required`,
										atribut: `name="margin_top" placeholder="Margin Atas" rms value="20"`,
									}) +
									createHTML("fieldText", {
										label: "Bawah",
										classField: `required`,
										atribut: `name="margin_bottom" placeholder="Margin Bawah" rms value="20"`,
									}),
							}) +
							createHTML("fields", {
								classField: "two",
								content:
									createHTML("fieldText", {
										label: "Kiri",
										classField: `required`,
										atribut: `name="margin_kiri" placeholder="Margin Kiri" rms value="20"`,
									}) +
									createHTML("fieldText", {
										label: "Kanan",
										classField: `required`,
										atribut: `name="margin_kanan" placeholder="Margin Kanan" rms value="20"`,
									}),
							}) +
							createHTML("dividerClearing") +
							createHTML("fields", {
								classField: "two",
								content:
									createHTML("fielToggleCheckbox", {
										label: "",
										atribut: 'name="header" non_data readonly',
										txtLabel: "Aktifkan Header",
									}) +
									createHTML("fielToggleCheckbox", {
										atribut: 'name="footer" non_data readonly',
										txtLabel: "Aktifkan Footer",
									}),
							}) +
							createHTML("fields", {
								classField: "two",
								content:
									createHTML("fieldText", {
										label: "Header",
										classField: `required`,
										atribut: `name="margin_header" placeholder="Margin Header" rms value="10"`,
									}) +
									createHTML("fieldText", {
										label: "Footer",
										classField: `required`,
										atribut: `name="margin_footer" placeholder="Margin Footer" rms value="10"`,
									}),
							}) +
							createHTML("dividerClearing") +
							createHTML("header", {
								header: "h3",
								content: "Kop Surat",
							}) +
							createHTML("fielToggleCheckbox", {
								atribut: 'name="cetak_kop" checked="checked" non_data readonly',
								txtLabel: "Cetak Kop Surat",
							}) +
							createHTML("fields", {
								label: "Kop standar dinas",
								classField: "grouped",
								atribut: 'name="ceklist_kop"',
								content:
									createHTML("label", {
										label: "Jenis Kop Cetak",
									}) +
									createHTML("fieldRadioCheckbox", {
										label: "Kop standar dinas",
										atribut: `name="kop_dns" checked="checked" value="standar"`,
									}) +
									createHTML("fieldRadioCheckbox", {
										label: "Kop gambar (custom)",
										atribut: `name="kop_dns" value="custom"`,
									}),
							});
						break;
				}
				break;
			case "xxx":
				break;
			default:
				break;
		}
		let tampilkan_toast = false;
		elementForm += createHTML("errorForm");
		formIni.html(elementForm);
		$(".ui.sticky").sticky("refresh");
		let modalsecond = new ModalConstructor(mdl);
		modalsecond.globalModal();
		let InitializeForm = new FormGlobal(formIni);
		InitializeForm.run();
		document.getElementById("header_mdl").textContent = headerModal;
		formIni.find(".ui.dropdown.lainnya").dropdown();
		if ($(`a[name="change_themes"]`).attr("theme") === "dark") {
			$(".form .icon").removeClass("inverted").addClass("inverted");
		}
		addRulesForm(formIni);
		switch (jnsAttr) {
			case "cetak":
				switch (tblAttr) {
					case "sk_asn":
						formIni.form("set values", {
							dokumen: tblAttr,
							id_row: id_row_form_modal,
						});
						console.log(id_row_form_modal);
						if (
							id_row_form_modal <= 0 ||
							typeof id_row_form_modal === "undefined"
						) {
							pesanToast =
								"surat keputusan belum di simpan, simpan sebelum cetak";
							kelasToast = "warning";
							iconToast = "exclamation circle icon";
							tampilkan_toast = true;
						}
						break;
				}
				break;
			case "get_data":
				switch (tblAttr) {
					case "realisasi":
						break;
				}
				break;

			default:
				break;
		}
		if (jalankanAjax) {
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					loaderHide();
					switch (jnsAttr) {
						case "get_data":
							switch (tblAttr) {
								case "realisasi":
									break;
							}
							break;
						default:
							break;
					}
					if (nameAttr === "modal_second") {
						mdl.modal("show");
					}
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		} else {
			if (nameAttr === "modal_second" && tampilkan_toast !== true) {
				mdl.modal("show");
				kelasToast = "success";
			} else {
				showToast(pesanToast, {
					class: kelasToast,
					icon: iconToast,
				});
			}
		}
		// disable submit jika deactivate
		formIni
			.closest(".flyout")
			.find(".ui.ok.button, .ui.dropdown")
			.removeClass("disabled");
		if (deactivate) {
			formIni
				.closest(".flyout")
				.find(".ui.ok.button, .ui.dropdown")
				.addClass("disabled");
			formIni.find(".ui.button, .ui.dropdown").addClass("disabled");
			formIni.find("input, textarea").attr("readonly", 1);
		}
		$("[rms]").mathbiila();
		if (tampilkan_toast) {
		}
	});
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		//@audit-ok DropdownConstructor
		jenis = "";
		tbl = "";
		ajax = false;
		result_ajax = {};
		url = "script/get_data";
		constructor(element) {
			this.element = $(element); //element;
			this.methodConstructor = new MethodConstructor();
		}
		returnList(
			allField = {
				jenis: "list_dropdown",
				tbl: "satuan",
				minCharacters: 3,
				set: {},
				attrresponserver: {},
			}
		) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			let url = this.url;
			let jenis = allField.jenis;
			let tbl = allField.tbl;
			switch (jenis) {
				case "get_field_json":
					if (allField?.set?.jns_kel) {
					} else {
						allField.set["jns_kel"] = $(`form[name="form_flyout"]`)
							.find('.ui.dropdown[name="jenis_kelompok"')
							.dropdown("get value");
					}
					break;
				default:
					break;
			}
			this.element.dropdown({
				minCharacters: allField.minCharacters,
				maxResults: countRows(),
				searchDelay: 600,
				throttle: 600,
				cache: false,
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					cache: false,
					method: "POST",
					url: url,
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: Object.assign(
						{
							jenis: jenis,
							tbl: tbl,
							cari: function (value) {
								return elm.dropdown("get query");
							},
							rows: countRows(), //"all",
							halaman: 1,
						},
						allField.set
					),
					fields: Object.assign(
						{ results: "results" },
						allField.attrresponserver
					),
					onSuccess: function (response, element, xhr) {
						// valid response and response.success = true
						this.result_ajax = response.results;
					},
					// filterRemoteData: true,
				},
				// filterRemoteData: true, Object.assign({results: "results"}, allField.responserver)
				// saveRemoteData: false,
				// data: Object.assign({
				// 	jenis: allField.jenis,
				// 	tbl: allField.tbl,
				// 	cari: function (value) {
				// 		return MyElmSearch.search('get value');
				// 	},
				// 	rows: countRows(), //"all",
				// 	halaman: 1,
				// }, allField.data_send),
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find("span.description").text();
					let Results_ajax = this.result_ajax;
					let objekArray;
					let ajaxSend = false;
					if (value.length > 0 && typeof $choice !== "undefined") {
						switch (jenis) {
							case "get_row_json":
								switch (tbl) {
									case "asn":
										//merubah pilihan di sk_asn
										// String yang akan diubah menjadi objek
										if (value.length > 0 && typeof $choice !== "undefined") {
											let dataString = dataChoice;
											// Membagi string menjadi bagian-bagian berdasarkan tanda titik koma dan spasi
											let parts = dataString.split("; ");
											// Objek kosong untuk menampung data
											let dataObj = {};
											// Mengonversi setiap bagian menjadi properti objek
											parts.forEach(function (part) {
												// Memisahkan setiap bagian menjadi kunci dan nilai
												var keyValue = part.split(": ");
												// Menghapus spasi ekstra dari kunci dan nilai
												var key = keyValue[0].trim();
												var value = keyValue[1].trim();
												// Menambahkan properti ke objek
												dataObj[key] = value;
											});
											let nameAttr = elm.attr("name");
											switch (nameAttr) {
												case "asn": //wait
													if (
														typeof elm.closest('form[tbl="sk_asn"]') !==
														"undefined"
													) {
														let elmTabelAsnSK = elm
															.closest("form")
															.find('table[name="nama_ditugaskan"] tbody');
														let trRow = createHTML("tr_tabel", {
															bodyTable: [
																[
																	{
																		lbl: `<div contenteditable>${text}</div>`,
																	},
																	{
																		lbl: `<div contenteditable>${dataObj.Pangkat}</div>`,
																	},
																	{
																		lbl: `<div contenteditable>${value}</div>`,
																	},
																	{
																		lbl: `<div contenteditable="">${dataObj.Jabatan}</div>`,
																	},
																	{
																		lbl: `<div contenteditable></div>`,
																	},
																	{
																		class: "collapsing",
																		lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																	},
																],
															],
														});
														elmTabelAsnSK.append(trRow);
													}
													break;
												case "pemberi_tgs":
													elm.closest("form").form("set values", {
														jbt_pemberi_tgs: dataObj.Jabatan,
														pangkat_pemberi_tgs: dataObj.Pangkat,
													});
													break;
												default:
													break;
											}
										}

										break;
									case "sbu":
									case "asb":
									case "ssh":
									case "hspk":
										Results_ajax.some(function (el) {
											// console.log(el);
											objekArray = el;
											return el.value == value;
										});
										var MyForm = $(`form[name="form_flyout"]`);
										MyForm.form("set values", {
											tkdn: objekArray.tkdn,
											satuan: objekArray.satuan,
											spesifikasi: objekArray.spesifikasi,
											harga_satuan: accounting.formatNumber(
												objekArray.harga_satuan,
												parseFloat(objekArray.harga_satuan).countDecimals(),
												".",
												","
											),
										});
										break;
									case "sub_keg_dpa":
										//buatkan konstruktor untuk search
										MyForm = $(`form[name="form_modal"]`);
										let kd_sub_keg = MyForm.find(
											`.ui.dropdown[name="kd_sub_keg"]`
										).dropdown("get value");
										var searchPrompt = new SearchConstructor(
											'form[name="form_modal"] .ui.search.uraian'
										);
										let allField = {
											minCharacters: 3,
											searchDelay: 600,
											jenis: "get_Search_Json",
											tbl: "dpa_dppa",
											data_send: {
												kd_sub_keg: kd_sub_keg,
											},
										};
										searchPrompt.searchGlobal(allField);
										console.log(kd_sub_keg);
										if (kd_sub_keg.length <= 0) {
											delete window.searchPrompt; //searchPrompt = null;or undefined; or delete window.searchPrompt;
										}
										break;
									case "value1":
										break;
									default:
										break;
								}
								break;
							default:
								break;
						}
					}
					if (ajaxSend == true) {
						let data = {
							cari: cari(jenis),
							rows: countRows(),
							jenis: jenis,
							tbl: tbl,
							halaman: halaman,
						};
						let url = "script/get_data";
						let cryptos = false;
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(
							url,
							"POST",
							data,
							"Json",
							undefined,
							undefined,
							"ajaxku",
							cryptos
						);
					}
				},
			});
		}
		returnListOnChange(
			allField = {
				jenis: "list_dropdown",
				tbl: "satuan",
				minCharacters: 3,
			}
		) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			this.element.dropdown({
				minCharacters: allField.minCharacters,
				maxResults: countRows(),
				searchDelay: 600,
				throttle: 600,
				cache: false,
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					cache: false,
					method: "POST",
					url: "script/get_data",
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: Object.assign(
						{
							jenis: jenis,
							tbl: tbl,
							cari: function (value) {
								return elm.dropdown("get query");
							},
							rows: countRows(), //"all",
							halaman: 1,
						},
						allField
					),
					fields: {
						results: "results",
					},
					// filterRemoteData: true,
				},
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find("span.description").text();
					switch (jenis) {
						case "getJsonRows":
							switch (tbl) {
								case "tujuan_renstra": //tujuan sasaran renstra
									let elmTujuan = $(`form[name="form_flyout"]`).find(
										'[name="id_tujuan"]'
									);
									let fieldElmTujuan = elmTujuan.closest(".field");
									ajaxSend = false;
									if (value === "tujuan") {
										fieldElmTujuan.attr("hidden");
									} else {
										//tambahkan dropdown
										fieldElmTujuan.removeAttr("hidden");
									}
									break;
								default:
									break;
							}
							break;
						case "value1":
							break;
						default:
							break;
					}
					if (ajaxSend == true) {
						let data = {
							cari: cari(jenis),
							rows: countRows(),
							jenis: jenis,
							tbl: tbl,
							halaman: halaman,
						};
						let url = "script/get_data";
						let cryptos = false;
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							if (result.success === true) {
								switch (jenis) {
									case "getJsonRows":
										switch (tbl) {
											case "tujuan_renstra": //tujuan sasaran renstra
												MethodConstructor.tujuanRenstra(result);
												break;
											default:
												break;
										}
										break;
									case "value1":
										break;
									default:
										break;
								}
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(
							url,
							"POST",
							data,
							"Json",
							undefined,
							undefined,
							"ajaxku",
							cryptos
						);
					}
				},
			});
		}
		setSelected(val) {
			this.element.dropdown("set selected", val);
		}
		setValue(val) {
			this.element.dropdown("set value", val);
		}
		restore() {
			this.element.dropdown("restore defaults");
		}
		setVal(val) {
			//this.element.dropdown('preventChangeTrigger', true);
			this.element.dropdown("set selected", val);
		}
		onChange(
			allObjek = { jenis: "list_dropdown", tbl: "satuan", ajax: false }
		) {
			let ajaxSend = allObjek.ajax;
			let jenis = allObjek.jenis;
			let tbl = allObjek.tbl;
			this.element.dropdown({
				onChange: function (value, text, $choice) {
					console.log(value);
					let dataChoice = $($choice).find("span.description").text();
					switch (jenis) {
						case "getJsonRows":
							switch (allObjek.tbl) {
								case "tujuan_renstra": //tujuan sasaran renstra
									let elmTujuan = $(`form[name="form_flyout"]`).find(
										'[name="id_tujuan"]'
									);
									let fieldElmTujuan = elmTujuan.closest(".field");
									ajaxSend = false;
									if (value === "tujuan") {
										fieldElmTujuan.attr("hidden");
									} else {
										//tambahkan dropdown
										fieldElmTujuan.removeAttr("hidden");
									}
									break;
								default:
									break;
							}
							break;
						case "gantiJenisKelompok":
							var allData = {
								jenis: "gantiJenisKelompok",
								tbl: allObjek.tbl,
							};
							MethodConstructor.refreshDropdown(allData);
							break;
						case "gantiJenisKomponen":
							//name="jenis_standar_harga"
							$(
								`form[name="form_flyout"] button[name="modal_show"][jns="search_field_json"]`
							).attr("tbl", value);
							allData = {
								jenis: "gantiJenisKomponen",
								tbl: allObjek.tbl,
							};
							MethodConstructor.refreshDropdown(allData);
							break;
						case "non":
							switch (tbl) {
								case "create_surat": //"divider_tabel_1klm"//@audit sekarang elm
									var myForm = $('form[name="form_modal"]');
									let elemen = "";
									let elmNaskah = {
										instruksi: {
											elemen: [
												{
													tag: "fieldTextarea",
													prop: {
														label: "Tentang",
														classField: `required`,
														atribut: `name="tentang" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Dalam Rangka",
														classField: `required`,
														atribut: `name="text_1" placeholder="dalam rangka" rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Kepada",
														atribut: "kepada",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "KESATU",
														atribut: "kesatu",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "KEDUA",
														atribut: "kedua",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "KETIGA",
														atribut: "ketiga",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "KEEMPAT",
														atribut: "keempat",
													},
												},
											],
										},
										surat_edaran: {
											elemen: [
												{
													tag: "fieldTextarea",
													prop: {
														label: "Tentang",
														classField: `required`,
														atribut: `name="tentang" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Yth.",
														atribut: "yth",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "latar Belakang",
														atribut: "latar_belakang",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Maksud dan Tujuan",
														atribut: "maksud_tujuan",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Ruang Lingkup",
														atribut: "ruang_lingkup",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Dasar",
														atribut: "dasar",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Isi Edaran",
														atribut: "isi_edaran",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Penutup",
														atribut: "penutup",
													},
												},
											],
										},
										keputusan: {
											elemen: [
												{
													tag: "fieldTextarea",
													prop: {
														label: "Tentang",
														classField: `required`,
														atribut: `name="tentang" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "MENIMBANG",
														atribut: "menimbang",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "MENGINGAT",
														atribut: "mengingat",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "MENETAPKAN",
														label: "KESATU",
														atribut: "kesatu",
													},
												},
												{
													tag: "tabel_1klm",
													prop: {
														label: "KEDUA",
														atribut: "kedua",
													},
												},
												{
													tag: "tabel_1klm",
													prop: {
														label: "KETIGA",
														atribut: "ketiga",
													},
												},
												{
													tag: "tabel_1klm",
													prop: {
														label: "KEEMPAT",
														atribut: "keempat",
													},
												},
												{
													tag: "asn_tabel",
													prop: {
														icon: "",
														label: "ASN yang ditugaskan",
														atribut: `name="nama_ditugaskan"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "TEMBUSAN",
														atribut: "tembusan",
													},
												},
											],
										},
										surat_perintah: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "MENIMBANG",
														atribut: "menimbang",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "DASAR",
														atribut: "dasar",
													},
												},
												{
													tag: "divider",
													prop: {
														label: "MEMBERI PERINTAH",
													},
												},
												{
													tag: "asn_tabel",
													prop: {
														label: "ASN yang ditugaskan",
														atribut: `name="nama_ditugaskan"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "UNTUK",
														atribut: "untuk",
													},
												},
											],
										},
										nota_dinas: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "YTH.",
														atribut: "yth",
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Dari",
														classField: `required`,
														atribut: `name="text_1" placeholder="dari" rows="2"`,
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Hal",
														classField: `required`,
														atribut: `name="text_2" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "BATANG TUBUH",
														atribut: "batang_tubuh",
													},
												},
											],
										},
										memorandum: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "YTH.",
														atribut: "yth",
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Hal",
														classField: `required`,
														atribut: `name="text_2" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "BATANG TUBUH",
														atribut: "batang_tubuh",
													},
												},
											],
										},
										undangan_internal: {
											elemen: [
												{
													tag: "fieldTextarea",
													prop: {
														label: "Lampiran",
														classField: `required`,
														atribut: `name="text_1" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Hal",
														classField: `required`,
														atribut: `name="text_2" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Yth.",
														classField: `required`,
														atribut: `name="text_3" placeholder="Yth..." rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Alinea Pembuka",
														atribut: "alinea_pembuka",
													},
												},
												{
													tag: "divider_tabel_2klm",
													prop: {
														icon: "",
														txtLabel: "Jadwal",
														atribut: "jadwal",
														bodyTable:
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable="">pada hari/tanggal</div>`,
																		},
																		{ lbl: `<div contenteditable=""></div>` },
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>waktu</div>`,
																		},
																		{
																			lbl: `<div contenteditable></div>`,
																		},
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>tempat</div>`,
																		},
																		{
																			lbl: `<div contenteditable></div>`,
																		},
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>acara</div>`,
																		},
																		{ lbl: `<div contenteditable></div>` },
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}),
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Alinea Penutup",
														atribut: "alinea_penutup",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "TEMBUSAN",
														atribut: "tembusan",
													},
												},{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "LAMPIRAN",
														label: "DAFTAR YANG DIUNDANG",
														atribut: "lampiran"
													},
												}
											],
										},
										surat_dinas: {
											elemen: [
												{
													tag: "fieldTextarea",
													prop: {
														label: "Lampiran",
														classField: `required`,
														atribut: `name="text_1" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Hal",
														classField: `required`,
														atribut: `name="text_2" placeholder="tentang" rows="2"`,
													},
												},
												{
													tag: "fieldTextarea",
													prop: {
														label: "Yth.",
														classField: `required`,
														atribut: `name="text_3" placeholder="Yth..." rows="2"`,
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "Batang Tubuh/Alinea",
														atribut: "batang_tubuh",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														txtLabel: "TEMBUSAN",
														atribut: "tembusan",
													},
												}
											],
										},
										perjanjian: {
											elemen: [
												{
													tag: "fieldTextarea",
													prop: {
														label: "Tentang",
														classField: `required`,
														atribut: `name="tentang" placeholder="tentang" rows="2"`,
													}
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													}
												}
											],
										},
										surat_kuasa: {
											elemen: [
												{
													tag: "divider_tabel_2klm",
													prop: {
														icon: "",
														txtLabel: "Yang bertanda tangan dibawah ini",
														atribut: "pemberi_kuasa",
														bodyTable:
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable="">Nama</div>`,
																		},
																		{ lbl: `<div contenteditable=""></div>` },
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>Jabatan</div>`,
																		},
																		{
																			lbl: `<div contenteditable></div>`,
																		},
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>Alamat</div>`,
																		},
																		{
																			lbl: `<div contenteditable></div>`,
																		},
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															})
													}
												},
												{
													tag: "divider_tabel_2klm",
													prop: {
														icon: "",
														txtLabel: "Memberi Kuasa Kepada",
														atribut: "diberi_kuasa",
														bodyTable:
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable="">Nama</div>`,
																		},
																		{ lbl: `<div contenteditable=""></div>` },
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>Jabatan</div>`,
																		},
																		{
																			lbl: `<div contenteditable></div>`,
																		},
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															}) +
															createHTML("tr_tabel", {
																bodyTable: [
																	[
																		{
																			lbl: `<div contenteditable>Alamat</div>`,
																		},
																		{
																			lbl: `<div contenteditable></div>`,
																		},
																		{
																			lbl: `<button class="ui teal mini button" name="add" jns="P" data-tooltip="paragraf">P</button>`,
																		},
																		{
																			class: "collapsing",
																			lbl: `<button class="ui icon mini red button" name="del_row" jns="direct"><i class="trash icon"></i></button>`,
																		},
																	],
																],
															})
													}
												},
												{
													tag: "tabel_1klm",
													prop: {
														label: "ALINEA PENUTUP",
														atribut: "penutup",
													}
												},
												{
													tag: "fieldText",
													prop: {
														label: "Lokasi",
														atribut: 'name="text_1" placeholder="lokasi"'
													}
												}
											]
										},
										berita_acara: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														label: "",
														atribut: "1",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													},
												},
											],
										},
										surat_keterangan: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														label: "",
														atribut: "1",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													},
												},
											],
										},
										surat_pengantar: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														label: "",
														atribut: "1",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													},
												},
											],
										},
										pengumuman: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														label: "",
														atribut: "1",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													},
												},
											],
										},
										laporan: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														label: "",
														atribut: "1",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													},
												},
											],
										},
										telaah_staf: {
											elemen: [
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "",
														label: "",
														atribut: "1",
													},
												},
												{
													tag: "divider_tabel_1klm",
													prop: {
														icon: "2",
														label: "3",
														atribut: "4",
													},
												},
											],
										},
									};
									if (value in elmNaskah) {
										for (let { tag, prop } of elmNaskah[value].elemen) {
											elemen += createHTML(tag, prop);
										}
									}
									myForm.find('div[name="elm_naskah"]').html(elemen);
									$(".ui.sticky").sticky("refresh");
									let mdl = $('.ui.kedua.modal[name="mdl_kedua"]');
									let modalsecond = new ModalConstructor(mdl);
									modalsecond.globalModal();
									let InitializeForm = new FormGlobal(myForm);
									InitializeForm.run();
									myForm.find(".ui.dropdown.lainnya").dropdown();
									removeRulesForm(myForm);
									addRulesForm(myForm);
									$("[rms]").mathbiila();
									if (typeof myForm.find(".ui.dropdown.asn.ajx").length) {
										var dropdownASNTugas = new DropdownConstructor(
											'form[name="form_modal"] .ui.dropdown.asn.ajx'
										);
										dropdownASNTugas.returnList({
											jenis: "get_row_json",
											tbl: "asn",
											attrresponserver: {
												golongan: "golongan",
												ruang: "ruang",
											},
										});
									}
									break;
								case "register_surat":
									var myForm = $('form[name="form_flyout"]');
									let arahan = [
										{
											value: "arahan_pengaturan",
											name: "Naskah Dinas pengaturan",
										},
										{
											value: "arahan_penetapan",
											name: "Naskah Dinas penetapan",
										},
										{
											value: "arahan_penugasan",
											name: "Naskah Dinas penugasan",
										},
									];
									let arahan_pengaturan = [
										{
											value: "arahan_pengaturan_undang2",
											name: "peraturan perundang-undangan",
										},
										{
											value: "arahan_pengaturan_instruksi",
											name: "instruksi",
										},
										{
											value: "arahan_pengaturan_edaran",
											name: "surat edaran",
										},
										{
											value: "arahan_pengaturan_sop",
											name: "standar operasional prosedur administrasi pemerintahan",
										},
									];
									let arahan_penetapan = [
										{
											value: "arahan_penetapan_keputusan",
											name: "Keputusan",
										},
									];
									let arahan_penugasan = [
										{
											value: "arahan_penugasan_suratperintah",
											name: "surat perintah",
										},
										{
											value: "arahan_penugasan_surattugas",
											name: "surat tugas",
										},
									];
									let korespondensi = [
										{
											value: "korespondensi_internal",
											name: "Naskah Dinas korespondensi internal;",
										},
										{
											value: "korespondensi_eksternal",
											name: "Naskah Dinas korespondensi eksternal",
										},
									];
									let korespondensi_internal = [
										{
											value: "korespondensi_internal_nota",
											name: "nota dinas",
										},
										{
											value: "korespondensi_internal_memorandum",
											name: "memorandum",
										},
										{
											value: "korespondensi_internal_disposisi",
											name: "disposisi",
										},
										{
											value: "korespondensi_internal_undangan",
											name: "surat undangan internal",
										},
									];
									let korespondensi_eksternal = [
										{
											value: "korespondensi_eksternal_dinas",
											name: "surat dinas",
										},
									];
									let khusus = [
										{
											value: "khusus_perjanjian",
											name: "surat perjanjian",
										},
										{
											value: "khusus_kuasa",
											name: "surat kuasa",
										},
										{
											value: "khusus_ba",
											name: "berita acara",
										},
										{
											value: "khusus_keterangan",
											name: "surat keterangan",
										},
										{
											value: "khusus_pengantar",
											name: "surat pengantar",
										},
										{
											value: "khusus_pengumuman",
											name: "pengumuman",
										},
										{
											value: "khusus_laporan",
											name: "laporan",
										},
										{
											value: "khusus_telaah",
											name: "telaah staf",
										},
									];
									let myVariable = {
										arahan: arahan,
										korespondensi: korespondensi,
										khusus: khusus,
									};
									myForm.find(`.ui.dropdown[name="sifat"]`).dropdown({
										values: myVariable[value],
										onChange: function (value2, text, $choice) {
											let value_sub_sifat = [
												{
													value: value2,
													name: text,
												},
											];
											switch (value2) {
												case "arahan_pengaturan":
													value_sub_sifat = arahan_pengaturan;
													break;
												case "arahan_penetapan":
													value_sub_sifat = arahan_penetapan;
													break;
												case "arahan_penugasan":
													value_sub_sifat = arahan_penugasan;
													break;
												case "korespondensi_internal":
													value_sub_sifat = korespondensi_internal;
													break;
												case "korespondensi_eksternal":
													value_sub_sifat = korespondensi_eksternal;
													break;
											}
											myForm.find(`[name="sub_sifat"]`).dropdown({
												values: value_sub_sifat,
											});
										},
									});
									break;
								case "metodePengadaan":
									let value_pengadaan_penyedia = [
										{
											value: "barang",
											name: "Barang",
										},
										{
											value: "konstruksi",
											name: "Pekerjaan Konstruksi",
										},
										{
											value: "konsultansi",
											name: "Jasa Konsultansi",
										},
										{
											value: "konsultansi_non_konst",
											name: "Jasa Konsultansi Non Konstruksi",
										},
										{
											value: "jasa_lainnya",
											name: "Jasa Lainnya",
										},
									];
									let value_pengadaan_penyediaSW = [
										{
											value: "swakelola",
											name: "swakelola",
											selected: true,
										},
									];
									let value_metode_pemilihan = [
										{
											value: "e_purchasing",
											name: "e-purchasing",
										},
										{
											value: "pengadaan_langsung",
											name: "pengadaan langsung",
										},
										{
											value: "penunjukan",
											name: "penunjukan langsung",
										},
										{
											value: "tender_cepat",
											name: "tender cepat",
										},
										{
											value: "tender",
											name: "tender",
										},
										{
											value: "seleksi",
											name: "seleksi",
										},
									];
									let value_metode_pemilihanSW = [
										{
											value: "sw_type_1",
											name: "Swakelola Type I",
										},
										{
											value: "sw_type_2",
											name: "Swakelola Type II",
										},
										{
											value: "sw_type_3",
											name: "Swakelola Type III",
										},
										{
											value: "sw_type_4",
											name: "Swakelola Type IV",
										},
									];
									var myForm = $('form[name="form_flyout"]');
									if (value === "penyedia") {
										// 'name="metode_pemilihan"'
										myForm.find(`[name="metode_pemilihan"]`).dropdown({
											values: value_metode_pemilihan,
										});
										// 'name="pengadaan_penyedia"'
										myForm.find(`[name="pengadaan_penyedia"]`).dropdown({
											values: value_pengadaan_penyedia,
											onChange: function (value, text, $choice) {
												let jenis_kontrak = [
													{
														value: "lumsum",
														name: "Lumsum",
													},
													{
														value: "harga_satuan",
														name: "Harga Satuan",
													},
													{
														value: "gabungan_lum_sat",
														name: "Gabungan Lumsum dan Harga Satuan",
													},
													{
														value: "terima_jadi",
														name: "Terima Jadi (Turnkey)",
													},
													{
														value: "payung",
														name: "Kontrak Payung",
													},
												];
												switch (value) {
													case "barang":
													case "jasa_lainnya":
														jenis_kontrak = [
															{
																value: "lumsum",
																name: "Lumsum",
															},
															{
																value: "harga_satuan",
																name: "Harga Satuan",
															},
															{
																value: "gabungan_lum_sat",
																name: "Gabungan Lumsum dan Harga Satuan",
															},
															{
																value: "biaya_plus_imbalan",
																name: "Biaya Plus Imbalan",
															},
															{
																value: "payung",
																name: "Kontrak Payung",
															},
														];
														break;
													case "konstruksi":
														jenis_kontrak = [
															{
																value: "lumsum",
																name: "Lumsum",
															},
															{
																value: "harga_satuan",
																name: "Harga Satuan",
															},
															{
																value: "gabungan_lum_sat",
																name: "Gabungan Lumsum dan Harga Satuan",
															},
															{
																value: "putar_kunci",
																name: "Putar Kunci",
															},
															{
																value: "biaya_plus_imbalan",
																name: "Biaya Plus Imbalan",
															},
														];
														break;
													case "konsultansi":
														jenis_kontrak = [
															{
																value: "lumsum",
																name: "Lumsum",
															},
															{
																value: "waktu_penugasan",
																name: "Waktu Penugasan",
															},
														];
														break;
													case "konsultansi_non_konst":
														jenis_kontrak = [
															{
																value: "lumsum",
																name: "Lumsum",
															},
															{
																value: "waktu_penugasan",
																name: "Waktu Penugasan",
															},
															{
																value: "payung",
																name: "Kontrak Payung",
															},
														];
														break;
													default:
														break;
												}
												myForm.find(`[name="jns_kontrak"]`).dropdown({
													values: jenis_kontrak,
												});
											},
										});
									} else {
										// 'name="metode_pemilihan"'
										myForm.find(`[name="metode_pemilihan"]`).dropdown({
											values: value_metode_pemilihanSW,
										});
										// 'name="pengadaan_penyedia"'
										myForm.find(`[name="pengadaan_penyedia"]`).dropdown({
											values: value_pengadaan_penyediaSW,
										});
										// 'name="jns_kontrak"'
										myForm.find(`[name="jns_kontrak"]`).dropdown({
											values: value_pengadaan_penyediaSW,
										});
									}
									break;
								default:
									break;
							}
							break;
						case "direct":
							switch (tbl) {
								case "warna":
									warna_tbl = value;
									let warna = [
										"red",
										"orange",
										"yellow",
										"green",
										"olive",
										"teal",
										"blue",
										"violet",
										"purple",
										"pink",
										"brown",
										"grey",
										"black",
									];
									$("body")
										.find(".table.insert")
										.removeClass(
											"red orange yellow green olive teal blue violet purple pink brown grey black"
										);
									let warnapilih = warna_tbl !== "non" ? warna_tbl : "";
									$("body").find(".table.insert").addClass(warnapilih);
									break;
								default:
									break;
							}
							break;
						case "xxx":
							break;
						default:
							break;
					}
					if (ajaxSend == true) {
						let data = {
							cari: cari(allObjek.jenis),
							rows: countRows(),
							jenis: allObjek.jenis,
							tbl: allObjek.tbl,
							halaman: halaman,
						};
						let url = "script/get_data";
						let cryptos = false;
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							if (result.success === true) {
								switch (allObjek.jenis) {
									case "getJsonRows":
										switch (allObjek.tbl) {
											case "tujuan_renstra": //tujuan sasaran renstra
												MethodConstructor.tujuanRenstra(result);
												break;
											default:
												break;
										}
										break;
									case "value1":
										break;
									default:
										break;
								}
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(
							url,
							"POST",
							data,
							"Json",
							undefined,
							undefined,
							"ajaxku",
							cryptos
						);
					}
				},
				saveRemoteData: true,
				filterRemoteData: true,
			});
		}
		/*
				values: [
					{
						name     : 'Filter by tag',
						type     : 'header'
						// Will be displayed as header
					},
					{
						name     : 'Important',
						value    : 'important',
						type     : 'item'
						// Will be displayed as item
					},
					{
						name     : 'Announcement',
						value    : 'announcement'
						// Will be displayed as item
					}
					{
						name: 'Male',
						value: 'male'
					},
					{
						name: 'Female',
						value: 'female',
						selected: true
					}
				]
				*/
		valuesDropdown(values) {
			this.element.dropdown({
				values: values,
			});
		}
	}
	//===================================
	//=========== class calendar ========
	//===================================
	class MethodConstructor {
		//hjn9ioj MethodConstructor
		constructor(element) {
			//constructor(element,tglAwal=new Date(),tglAkhir=new Date()) {
			this.element = $(element);
		}
		static tujuanRenstra(result = {}) {
			let formIni = $(`form[name="form_flyout"]`);
			let elmTujuan = $(`form[name="form_flyout"]`).find('[name="id_tujuan"]');
			let fieldElmTujuan = elmTujuan.closest(".field");
			fieldElmTujuan.removeClass("hidden");
			if (elmTujuan.length <= 0) {
				// elm.after(dropdownInsert);
				// addRulesForm(formIni);
				// elmTujuan = $(`form[name="form_flyout"]`).find('[name="id_tujuan"]');
				// elmTujuan.dropdown({
				// 	values: result?.results,
				// })
			}
		}
		static refreshDropdown(
			allData = { jenis: "gantiJenisKelompok", tbl: "renja" }
		) {
			var tabel_pakai_temporerSubkeg = "sub_keg_renja";
			switch (allData.tbl) {
				case "dpa":
				case "dppa":
					tabel_pakai_temporerSubkeg = "sub_keg_dpa";
					break;
			}
			switch (allData.jenis) {
				case "gantiJenisKelompok":
					let dropdownKelompok = new DropdownConstructor(
						'form[name="form_flyout"] .ui.dropdown[name="kelompok"]'
					);
					let allField = {
						klm: "kelompok_json",
						id_sub_keg: $('form[name="form_flyout"]').attr("id_sub_keg"),
					};
					dropdownKelompok.returnList({
						jenis: "get_field_json",
						tbl: tabel_pakai_temporerSubkeg,
						set: allField,
					});
					break;
				case "gantiJenisKomponen":
					var dropdownKomponen = new DropdownConstructor(
						'.ui.dropdown[name="komponen"]'
					);
					let jenisKomponen = $('form[name="form_flyout"]')
						.find('.ui.dropdown[name="jenis_standar_harga"]')
						.dropdown("get value");
					let rekeningAkun = $('form[name="form_flyout"]')
						.find('.ui.dropdown[name="kd_akun"]')
						.dropdown("get value");
					let allFieldKomponen = {
						id_sub_keg: $('form[name="form_flyout"]').attr("id_sub_keg"),
						kd_akun: rekeningAkun,
					};
					dropdownKomponen.returnList({
						jenis: "get_row_json",
						tbl: jenisKomponen,
						set: allFieldKomponen,
					});
					break;
				case "xxxx":
					break;
				default:
					break;
			}
		}
	}
	//===================================
	//=========== class calendar ========
	//===================================
	class CalendarConstructor {
		//@audit-ok CalendarConstructor
		constructor(element) {
			//constructor(element,tglAwal=new Date(),tglAkhir=new Date()) {
			this.element = $(element);
		}
		typeOnChange = "";
		disableDate = [];
		enableDate = [];
		type = "date";
		typeDate = "dddd D MMMM Y";
		disabledDaysOfWeek = [];
		tanggal = new Date();
		minDate = null; //new Date(tanggal.getFullYear(), tanggal.getMonth(), tanggal.getDate());
		maxDate = null; //new Date(tglAwal.getFullYear(), tglAwal.getMonth(), tglAwal.getDate());
		startCalendar = null;
		endCalendar = null;
		//panggil methode ini untuk mengganti type dan format
		Type(typeDay) {
			let format = "dddd D MMMM Y";
			switch (typeDay) {
				case "date":
					format = "dddd D MMMM Y";
					this.type = "date";
					break;
				case "datetime":
					format = "dddd D MMMM Y h:mm A";
					this.type = "datetime";
					break;
				case "time":
					format = "Y h:mm A";
					this.type = "time";
					break;
				case "year":
					format = "MMMM";
					this.type = "year";
					break;
				default:
					break;
			}
			this.typeDate = format;
		}
		runCalendar() {
			let typeOnChange = this.typeOnChange;
			let typeCal = this.typeDate;
			this.element.calendar({
				minDate: this.minDate,
				maxDate: this.maxDate,
				disableDate: this.disableDate,
				disabledDaysOfWeek: this.disabledDaysOfWeek,
				type: this.type,
				startCalendar: this.startCalendar,
				endCalendar: this.endCalendar,
				formatter: {
					date: typeCal,
					time: "H:mm",
					cellTime: "H:mm",
				},
				text: {
					dayNames: [
						"Ahad",
						"Senin",
						"Selasa",
						"Rabu",
						"Kamis",
						"Jumat",
						"Sabtu",
					],
					months: [
						"Januari",
						"Februari",
						"Maret",
						"April",
						"Mei",
						"Juni",
						"Juli",
						"Agustus",
						"September",
						"Oktober",
						"November",
						"Desember",
					],
				},
				onChange: function (date, text, mode) {
					//let tanggalAwal = new Date(tglAwal.getFullYear(), tglAwal.getMonth(), tglAwal.getDate());
					switch (typeOnChange) {
						case 'tab="lap-harian"':
							var tanggal = new Date(date);
							tanggal = `${tanggal.getFullYear()}-${
								tanggal.getMonth() + 1
							}-${tanggal.getDate()}`; //local time
							$('a[data-tab="lap-harian"][tbl="get_list"]').trigger("click");
							break;
						case "value2":
							break;
						case "value3":
							break;
						default:
							break;
					}
				},
			});
		}
	}
	//=======================================
	//===============FORM GLOBAL=============
	//=======================================
	class FormGlobal {
		//@audit-ok Form
		constructor(form) {
			this.form = $(form); //element;
		}
		run() {
			let MyForm = this.form;
			MyForm.form({
				autoCheckRequired: true,
				onSuccess: function (e) {
					e.preventDefault();
					loaderShow();
					const [node] = $(MyForm);
					const attrs = {};
					$.each(node.attributes, (index, attribute) => {
						attrs[attribute.name] = attribute.value;
						let attrName = attribute.name;
						//membuat variabel
						let myVariable = attrName + "Attr";
						window[myVariable] = attribute.value;
					});
					let jalankanAjax = false;
					let ini = $(this);
					let tbl = ini.attr("tbl");
					let dataType = "Json";
					let jenis = ini.attr("jns");
					let nama_form = ini.attr("name");
					let cryptos = false;
					//loaderShow();
					let formData = new FormData(this);
					//ubah angka indonesia menjadi standar
					let elmRms = MyForm.find("input[rms]");
					Object.keys(elmRms).forEach((key) => {
						let element = $(elmRms[key]);
						let namaAttr = element.attr("name");
						if (namaAttr !== undefined) {
							formData.set(
								namaAttr,
								accounting.unformat(formData.get(namaAttr), ",")
							);
						}
					});
					formData.set("jenis", jenis);
					formData.set("tbl", tbl);
					let url = "script/post_data";
					formData.set("cari", cari(jenis));
					formData.set("rows", countRows());
					let id_row = ini.attr("id_row");
					if (typeof id_row === "undefined") {
						id_row = ini.closest("tr").attr("id_row");
						if (typeof id_row === "undefined") {
							id_row = ini.closest("div.item").attr("id_row");
						}
					}
					if (typeof id_row !== "undefined") {
						formData.set("id_row", id_row);
					}
					//tampilkan form data
					formData.forEach((value, key) => {});
					switch (jenis) {
						case "import":
							url = "script/impor_xlsx";
							jalankanAjax = true;
							break;
						case "edit":
							url = "script/post_data";
							jalankanAjax = true;
							break;
						default:
							break;
					}
					// global mencari element date dan checkbox
					let property = ini.find(`.ui.toggle.checkbox input[name]`);
					if (property.length > 0) {
						for (const key of property) {
							let namaAttr = $(key).attr("name");
							formData.has(namaAttr) === false
								? formData.set(namaAttr, "off")
								: formData.set(namaAttr, "on"); // Returns false
						}
					}
					property = ini.find(".ui.calendar.date");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}-${
									tanggal.getMonth() + 1
								}-${tanggal.getDate()}`; //local time
								formData.set(nameAttr, tanggal);
							}
						}
					}
					property = ini.find(".ui.calendar.year");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}`; //local time
								formData.set(nameAttr, tanggal);
							}
						}
					}
					property = ini.find(".ui.calendar.datetime");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								//cara satu
								// tanggal = new Date(tanggal);
								// tanggal= tanggal.getUTCFullYear() + '-' +
								// 	('00' + (tanggal.getUTCMonth() + 1)).slice(-2) + '-' +
								// 	('00' + tanggal.getUTCDate()).slice(-2) + ' ' +
								// 	('00' + tanggal.getUTCHours()).slice(-2) + ':' +
								// 	('00' + tanggal.getUTCMinutes()).slice(-2) + ':' +
								// 	('00' + tanggal.getUTCSeconds()).slice(-2);
								//cara singkat
								// tanggal = new Date(tanggal).toISOString().slice(0, 19).replace('T', ' ');
								//caraku
								tanggal = new Date(tanggal)
									.toISOString()
									.slice(0, 19)
									.replace("T", " ");
								formData.set(nameAttr, tanggal);
							}
						}
					}
					switch (nama_form) {
						// =================
						// UNTUK FORM MODAL
						// =================
						case "form_modal":
							switch (jenis) {
								case "modal_show":
									switch (tbl) {
										case "berita":
											$(`form[name="form_flyout"]`).form(
												"set value",
												"uraian_html",
												formData.get("uraian_html")
											);
											break;
									}
									break;
								case "add_uraian":
									switch (tbl) {
										case "daftar_paket":
											let dataUraian = {};
											let sx = 0;
											let namaPaketAwal = "";
											let kd_akunsub_keg = [];
											let sumPagu = 0;
											let sumKontrak = 0;
											$(`[name="form_modal"] table tbody tr`).each(function () {
												sx++;
												let element = $(this);
												if (sx === 1) {
													namaPaketAwal = element
														.find('td[klm="uraian"]')
														.text();
												}
												kd_akunsub_keg.push(
													element.find('td[klm="kd_sub_keg"]').text()
												);
												let idUraian = Number(element.attr("id_row"));
												let dok_anggaran = element.attr("dok_anggaran");
												sumPagu += Number(element.attr("pagu"));
												let kontrak = Number(
													accounting.unformat(
														element.find(`[klm="kontrak"] div`).text(),
														","
													)
												);
												let vol_kontrak = Number(
													accounting.unformat(
														element.find(`[klm="vol_kontrak"] div`).text(),
														","
													)
												);
												let sat_kontrak = element
													.find(`[klm="sat_kontrak"] div`)
													.text();
												sumKontrak += kontrak;
												dataUraian[`AL${sx}`] = {
													id: idUraian,
													val_kontrak: kontrak,
													dok_anggaran: dok_anggaran,
													vol_kontrak: vol_kontrak,
													sat_kontrak: sat_kontrak,
												};
											});
											kd_akunsub_keg.toString();
											if (sx > 1) {
												namaPaketAwal += " cs.";
											}
											let namaPaket = $(`[name="form_flyout"]`).form(
												"get value",
												"uraian"
											);
											if (namaPaket.length < 2) {
												namaPaket = namaPaketAwal;
											}
											sumPagu = parseFloat(sumPagu);
											sumPagu = accounting.formatNumber(
												sumPagu,
												sumPagu.countDecimals(),
												".",
												","
											);
											sumKontrak = parseFloat(sumKontrak);
											sumKontrak = accounting.formatNumber(
												sumKontrak,
												sumKontrak.countDecimals(),
												".",
												","
											);
											$(`[name="form_flyout"]`).form("set values", {
												id_uraian: JSON.stringify(dataUraian),
												uraian: namaPaket,
												pagu: sumPagu,
												jumlah: sumKontrak,
												count_uraian_belanja: `${sx} uraian {${kd_akunsub_keg}}`,
											});
											$(".ui.modal.mdl_general").modal("hide");
											break;
										default:
											break;
									}
									break;
								case "add":
								case "edit":
									let allTable = $(`form[name="form_modal"] table[name]`);
									switch (tbl) {
										case "create_surat":
											allTable.each(function (index, element) {
												// element == this
												let tbodyElemen = $(this).find("tbody");
												let attrTable = $(this).attr("name");
												// console.log(attrTable);
												let $headers = ["isi", "p_l", "button"];
												switch (attrTable) {
													case "nama_ditugaskan":
														$headers = [
															"nama",
															"pangkat",
															"nip",
															"jabatan",
															"jabatan_sk",
															"button"
														];
														break;
													default:
														break;
												}
												let strText = "";
												let myRows = {};
												let $rows = tbodyElemen
													.find("tr")
													.each(function (index, value) {
														let cells = $(this).find("td");
														myRows[index] = {};
														cells.each(function (cellIndex) {
															switch ($headers[cellIndex]) {
																case "angka":
																	strText = $(this).html();
																	strText = accounting.unformat(strText, ",");
																	myRows[index][$headers[cellIndex]] = strText;
																	break;
																case "angka_div":
																	strText = $(this).find("div").html();
																	strText = Number(
																		accounting.unformat(strText, ",")
																	);
																	myRows[index][$headers[cellIndex]] = strText;
																	break;
																case "p_l":
																	strText = $(this).find("button").text();
																	myRows[index][$headers[cellIndex]] = strText
																		.replace(/\s+/g, "")
																		.trim();
																	break;
																case "button":
																	// myRows[index][$headers[cellIndex]] = '';
																	break;
																default:
																	strText = $(this).find("div").html();
																	//Remove Extra Spaces From a String menggunakan .replace(/\s+/g, ' ').trim();
																	myRows[index][$headers[cellIndex]] = strText
																		.replace(/\s+/g, " ")
																		.trim();
																	break;
															}
														});
													});
												formData.append(attrTable, JSON.stringify(myRows));
											});
											jalankanAjax = true;
											break;
										case "sk_asn":
											ini.attr("id_row", id_row);
											//insert semua data di tabel form
											allTable.each(function (index, element) {
												// element == this
												let tbodyElemen = $(this).find("tbody");
												let attrTable = $(this).attr("name");
												// console.log(attrTable);
												let $headers = ["isi", "p_l", "button"];
												switch (attrTable) {
													case "nama_ditugaskan":
														$headers = [
															"nama",
															"pangkat",
															"nip",
															"jabatan",
															"jabatan_sk",
															"button",
														];
														break;

													default:
														break;
												}
												let strText = "";
												let myRows = {};
												let $rows = tbodyElemen
													.find("tr")
													.each(function (index, value) {
														let cells = $(this).find("td");
														myRows[index] = {};
														if (jenis === "edit") {
														}
														cells.each(function (cellIndex) {
															switch ($headers[cellIndex]) {
																case "angka":
																	strText = $(this).html();
																	strText = accounting.unformat(strText, ",");
																	myRows[index][$headers[cellIndex]] = strText;
																	break;
																case "angka_div":
																	strText = $(this).find("div").html();
																	strText = Number(
																		accounting.unformat(strText, ",")
																	);
																	myRows[index][$headers[cellIndex]] = strText;
																	break;
																case "p_l":
																	strText = $(this).find("button").text();
																	myRows[index][$headers[cellIndex]] = strText
																		.replace(/\s+/g, "")
																		.trim();
																	break;
																case "button":
																	// myRows[index][$headers[cellIndex]] = '';
																	break;
																default:
																	strText = $(this).find("div").html();
																	//Remove Extra Spaces From a String menggunakan .replace(/\s+/g, ' ').trim();
																	myRows[index][$headers[cellIndex]] = strText
																		.replace(/\s+/g, " ")
																		.trim();
																	break;
															}
														});
													});
												// var myObj = {};
												// myObj = myRows;
												formData.append(attrTable, JSON.stringify(myRows));
											});
											break;
										case "realisasi":
											let strText = "";
											let myRows = [];
											let $headers = [
												"kd_sub_keg",
												"uraian",
												"vol_kontrak",
												"sat_kontrak",
												"pagu",
												"jumlah_kontrak",
												"realisasi_vol",
												"realisasi_jumlah",
												"vol",
												"jumlah",
												"button",
											];
											let $rows = ini
												.find(".tblUraian tbody tr")
												.each(function (index, value) {
													let cells = $(this).find("td");
													myRows[index] = {};
													console.log(value);
													let id_row_uraian_paket = $(value).attr(
														"id_row_uraian_paket"
													);
													if (jenis === "edit") {
														let id_row = $(value).attr("id_row");
														myRows[index]["id_row"] = parseInt(id_row);
													}
													myRows[index]["id_row_uraian_paket"] =
														parseInt(id_row_uraian_paket);
													cells.each(function (cellIndex) {
														switch ($headers[cellIndex]) {
															case "vol_kontrak":
															case "pagu":
															case "jumlah_kontrak":
															case "realisasi_vol":
															case "realisasi_jumlah":
																strText = $(this).html();
																strText = accounting.unformat(strText, ",");
																myRows[index][$headers[cellIndex]] = strText;
																break;
															case "vol":
															case "jumlah":
																strText = $(this).find("div").html();
																strText = Number(
																	accounting.unformat(strText, ",")
																);
																myRows[index][$headers[cellIndex]] = strText;
																break;
															case "button":
																// myRows[index][$headers[cellIndex]] = '';
																break;
															default:
																myRows[index][$headers[cellIndex]] =
																	$(this).html();
																break;
														}
													});
												});
											var myObj = {};
											myObj.myrows = myRows;

											formData.append("id_row_paket", id_row_paketAttr);
											formData.append("realisasi", JSON.stringify(myObj));
											jalankanAjax = true;
											break;
										case "value1":
											break;
										default:
											break;
									}

									break;
								case "add_field_json":
									jalankanAjax = true;
									switch (tbl) {
										case "sub_keg_renja":
										case "sub_keg_dpa":
											formData.set("klm", klmAttr);
											formData.set("id_sub_keg", id_sub_kegAttr);
											formData.set("jns_kel", jns_kelAttr);
											break;
										case "value1":
											break;
										default:
											break;
									}
									break;
								case "value":
									break;
								default:
									break;
							}
							break;
						// =================
						// UNTUK FORM FLYOUT
						// =================
						case "form_flyout":
							switch (tbl) {
								case "akun_belanja":
								case "neraca":
								case "bidang_urusan":
								case "prog":
								case "keg":
								case "sub_keg":
								case "register_surat":
								case "daftar_paket":
									switch (jenis) {
										case "upload":
											formData.append("dok", dokAttr);
											jalankanAjax = true;
											break;
										case "add":
										case "edit":
											jalankanAjax = true;
											break;
										default:
											break;
									}
									break;
								case "peraturan":
									switch (jenis) {
										case "add":
										case "edit":
										//formData.append("id_row", tbl);
										case "input":
											let property = ini.find(".ui.calendar.date");
											if (property.length > 0) {
												for (const key of property) {
													let nameAttr = $(key).find("[name]").attr("name");
													let tanggal = $(key).calendar("get date");
													if (tanggal) {
														tanggal = `${tanggal.getFullYear()}-${
															tanggal.getMonth() + 1
														}-${tanggal.getDate()}`; //local time
														formData.set(nameAttr, tanggal);
													}
												}
											}
											property = ini.find(".ui.calendar.datetime");
											if (property.length > 0) {
												for (const key of property) {
													let nameAttr = $(key).find("[name]").attr("name");
													let tanggal = $(key).calendar("get date");
													if (tanggal) {
														tanggal = new Date()
															.toISOString()
															.slice(0, 19)
															.replace("T", " ");
														formData.set(nameAttr, tanggal);
													}
												}
											}
											jalankanAjax = true;
											break;
										default:
											break;
									}
									break;
								case "renstra":
								case "tujuan_sasaran_renstra":
								case "sub_keg_renja":
								case "berita":
								case "sub_keg_dpa":
								case "hspk":
								case "ssh":
								case "sbu":
								case "asb":
								case "wilayah":
									jalankanAjax = true;
									// 'id_sub_keg'
									break;
								case "renja":
								case "dpa":
								case "renja_p":
								case "dppa":
									formData.append("id_sub_keg", MyForm.attr("id_sub_keg"));
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						// ====================
						// UNTUK PENGATURAN====
						// ====================
						case "form_pengaturan":
							formData.has("disable") === false
								? formData.append("disable", "off")
								: formData.set("disable", "on"); // Returns false
							jalankanAjax = true;
							break;
						// =================
						// UNTUK MODAL 2====
						// =================
						case "form_modal_kedua":
							switch (jenis) {
								case "cetak":
									switch (tbl) {
										case "sk_asn": //@audit cetak pdf
											// formData.set(id_row, tanggal);
											url = "script/cetak_pdf";
											jalankanAjax = true;
											break;
									}
									break;
								case "edit":
									break;
								default:
									break;
							}

							// var atributFormAwal = ini.attr("nama_modal_awal");
							// var mdlTujuan = $('div[name="' + atributFormAwal + '"]');
							// var formTujuan = mdlTujuan.find("form");
							// var dataForm = ini.form("get values");
							// var tbodyFormAwal = formTujuan.find("table tbody");
							// var indexTr = ini.attr("indextr");
							// var trTable = tbodyFormAwal.children();
							// var tdEdit = trTable.eq(indexTr).find("td");
							// Object.keys(tdEdit).forEach((key) => {
							// 	var element = $(tdEdit[key]);
							// 	var nama_kolom = element.attr("klm");
							// 	if (typeof dataForm[nama_kolom] !== "undefined") {
							// 		var elemenku = element.children();
							// 		if (elemenku.length > 0) {
							// 			element.children().text(dataForm[nama_kolom]); // jika ada div contenteditable
							// 		} else {
							// 			element.text(dataForm[nama_kolom]);
							// 		}
							// 	}
							// });
							break;
						// =================
						// UNTUK PROFIL====
						// =================
						case "profil":
							//[name="ket"]
							formData.set("ket", $('textarea[name="ket"]').val());
							jalankanAjax = true;
							break;
						// =================
						// UNTUK UPLOAD DIRECT FILE====
						// =================
						case "form_upload":
							if (id_row) {
								let uploadedFile =
									document.getElementById("directupload1").files[0];
								formData.append("dok", dok);
								formData.append("jenis", jenis);
								formData.append("tbl", tbl);
								formData.append("id_row", id_row);
								formData.append(dok, uploadedFile);
								jalankanAjax = true;
							}
							break;
						default:
							break;
					}
					if (cryptos) {
						let keyEncryption = halamanDefault;
						let encryption = new Encryption();
						formData.forEach((value, key) => {
							switch (key) {
								case "jenis":
								case "tbl":
									break;
								default:
									formData.set(key, encryption.encrypt(value, keyEncryption));
									break;
							}
						});
						formData.set("cry", true);
					}
					if (jalankanAjax) {
						suksesAjax["ajaxku"] = function (result) {
							let kelasToast = "success";
							if (result.success === true) {
								if (tbl === "import") {
									var pesan = "";
									var hasil = 0;
									if (result.data.hasOwnProperty("note")) {
										if (result.data.note.hasOwnProperty("add row")) {
											hasil = result.data.note["add row"];
											pesan += "<p>" + hasil.length + " row ditambahkan</p> ";
										}
										if (result.data.note.hasOwnProperty("gagal")) {
											hasil = result.data.note["gagal"];
											pesan += "<p>" + hasil.length + " row gagal running</p> ";
										}
										if (result.data.note.hasOwnProperty("row update")) {
											hasil = result.data.note["row update"];
											pesan += "<p>" + hasil.length + " row diupdate</p> ";
										}
									}
								}
								let jenisTrigger = ""; //jenisTrigger = jenis;
								console.log(nama_form);
								switch (nama_form) {
									// ===========================
									// UNTUK FORM form_chat
									// ===========================
									case "form_chat":
										switch (jenis) {
											case "wall":
												switch (tbl) {
													case "add_coment":
														var tabAktif = $('a[data-tab="wall"]')
															.closest(".vertical.pointing.menu")
															.find(".item.active");
														if (tabAktif.length > 0) {
															$(tabAktif).trigger("click");
														}
														break;
													case "reply":
														break;
													default:
														break;
												}
												break;
											default:
												break;
										}
										break;
									// ===========================
									// UNTUK FORM form_range_date
									// ===========================
									case "form_range_date":
										$('table[name="laporan"] tbody').html(result.data.tbody);
										break;
									case "monev[informasi]":
										break;
									// ==================
									// =UNTUK FORM MODAL=
									// ==================
									case "form_modal":
										switch (jenis) {
											case "y":
												break;
											case "z":
												break;
										}
										break;
									// =================
									// UNTUK FORM FLYOUT
									// =================
									case "form_flyout":
										switch (jenis) {
											case "get_data":
												switch (tbl) {
													// cari data double

													default:
														break;
												}
												break;
											case "z":
												break;
										}
										switch (tbl) {
											case "sub_keg_renja":
											case "sub_keg_dpa":
											case "dpa":
											case "renja":
											case "dppa":
											case "renja_p":
											case "renstra":
											case "tujuan_sasaran_renstra":
											case "hspk":
											case "ssh":
											case "sbu":
											case "asb":
											case "rekanan":
											case "satuan":
											case "aset":
											case "asn":
											case "mapping":
											case "sub_keg":
											case "organisasi":
											case "sumber_dana":
											case "akun_belanja":
											case "register_surat":
											case "berita":
											case "peraturan":
											case "daftar_paket":
												switch (jenis) {
													case "get_tbl":
													case "upload":
													case "import":
													case "edit":
													case "add":
														jenisTrigger = tbl;
														break;
													default:
														break;
												}
												break;
											case "profil":
												switch (jenis) {
													case "edit":
														jenisTrigger = "user";
														break;
													case "profil":
														break;
													default:
														break;
												}
												break;
											default:
												break;
										}
										break;
									// =================
									// UNTUK FORM MODAL SECOND====
									// =================
									case "form_modal_kedua":
										console.log(jenis);
										switch (jenis) {
											case "cetak":
												switch (tbl) {
													case "sk_asn":
														function printPreviewBase64(base64String) {
															// Buat URL data untuk PDF
															var pdfDataUri =
																"data:application/pdf;base64," + base64String;
															// Buat sebuah elemen <a> untuk membuka PDF dalam tab baru
															var link = document.createElement("a");
															link.href = pdfDataUri;
															link.target = "_blank";
															// Klik pada link secara otomatis
															link.click();
														}
														printPreviewBase64(result.data.pdf);
														break;
													case "value1":
														break;
													default:
														break;
												}
												break;
											case "value1":
												break;
											default:
												break;
										}
										break;
									// =================
									// UNTUK TAB PROFIL====
									// =================
									case "profil":
										//jika warna tabel != default tambahkan warna di row thead dan row tfoot
										const warna = [
											"red",
											"orange",
											"yellow",
											"olive",
											"green",
											"teal",
											"blue",
											"violet",
											"purple",
											"pink",
											"brown",
											"grey",
											"black",
										];
										let warna_tbl = formData.get("warna_tbl");
										//ambil seluruh row thead dan row tfoot
										const elmRow = $("table tr"); //$('table tfoot tr, table thead tr');
										warna.forEach((value, key) => {
											elmRow.removeClass(value);
											$("table").removeClass("inverted");
										});
										if (warna_tbl !== "non") {
											elmRow.addClass(warna_tbl);
											$("table").addClass("inverted");
										}
										switch (jenis) {
											case "edit":
												switch (tbl) {
													case "profil":
														$("#set_tahun_anggaran").text(
															result.data.users.tahun
														);
														break;
												}
												break;
										}
										break;
								}
								if (jenisTrigger.length > 0) {
									let elmTrigger = $(`a[data-tab][tbl="${jenisTrigger}"]:last`);
									elmTrigger.trigger("click");
								}
								$("[rms]").mathbiila();
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(
							url,
							"POST",
							formData,
							dataType,
							false,
							false,
							"ajaxku",
							cryptos
						);
						//runAjax("script/master_read_xlsx", "POST", formData, false, false, false, 'upload_renja');// untuk type file
						//runAjax("script/load_data", "POST", data, 'text', undefined, undefined, "draft_renstra");// type text
					} else {
						loaderHide();
					}
					switch (nama_form) {
						case "form_modal":
							MyForm.form("reset");
							loaderHide();
							switch (jenis) {
								case "modal_show":
									switch (tbl) {
										case "berita":
											$(".ui.modal.mdl_general").modal("hide");
											break;
									}
									break;
							}
							// $(".ui.modal.mdl_general").modal("hide");
							break;
						case "form_flyout":
							//$('.ui.flyout').flyout('hide');
							break;
						case "form_modal_kedua":
							// $('div[name="mdl_kedua"]').modal("hide");
							break;
						default:
							break;
					}
				},
				onDirty: function (e) {
					//return true
					return false;
				},
				onFailure: function (e) {
					loaderHide();
					return false;
				},
			});
		}
		addRulesForm() {
			let MyForm = this.form;
			MyForm.form("set auto check");
			let elmtInputTextarea = MyForm.find($("input[name],textarea[name]"));
			for (const iterator of elmtInputTextarea) {
				let atribut = $(iterator).attr("name");
				let lbl = $(iterator).attr("placeholder");
				if (lbl === undefined) {
					lbl = $(iterator).closest(".field").find("label").text();
					if (lbl === undefined || lbl === "") {
						lbl = $(iterator).closest(".field").find("div.sub.header").text();
					}
					if (lbl === undefined || lbl === "") {
						lbl = atribut.replaceAll(/_/g, " ");
					}
				}
				let non_data = $(iterator).attr("non_data");
				if (typeof non_data === "undefined" || non_data === false) {
					MyForm.form("add rule", atribut, {
						rules: [
							{
								type: "empty",
								prompt: "Lengkapi Data " + lbl,
							},
						],
					});
				} else {
					MyForm.form("remove field", atribut);
				}
			}
		}
		removeRulesForm(formku) {
			let MyForm = this.form;
			var attrName = MyForm.find($("input[name],textarea[name]"));
			var i = 0;
			for (i = 0; i < attrName.length; i++) {
				var atribut = MyForm.find(attrName[i]).attr("name");
				var lbl = MyForm.find(attrName[i]).attr("placeholder");
				if (lbl === undefined) {
					lbl = MyForm.find(attrName[i]).closest(".field").find("label").text();
					if (lbl === undefined || lbl === "") {
						lbl = MyForm.find(attrName[i])
							.closest(".field")
							.find("div.sub.header")
							.text();
					}
					if (lbl === undefined || lbl === "") {
						lbl = atribut.replaceAll(/_/g, " ");
					}
				}
				var non_data = formku.find(attrName[i]).attr("non_data");
				if (typeof non_data === "undefined" || non_data === false) {
					if (atribut) {
						MyForm.form("remove field", atribut);
					}
				}
			}
			$(formku).form("set auto check");
		}
	}
	let InitializeForm = new FormGlobal(".ui.form");
	InitializeForm.run();
	let calendarDate = new CalendarConstructor(".ui.calendar.date");
	calendarDate.runCalendar();
	let calendarYear = new CalendarConstructor(".ui.calendar.year");
	calendarYear.Type("year");
	calendarYear.runCalendar();
	let calendarDateTime = new CalendarConstructor(".ui.calendar.datetime");
	calendarDateTime.Type("datetime");
	calendarDateTime.runCalendar();

	let dropdownWarna = new DropdownConstructor(
		'form[name="profil"] .ui.dropdown[name="warna_tbl"]'
	);
	dropdownWarna.onChange({ jenis: "direct", tbl: "warna" });
	//=======================================
	//===============SEACH GLOBAL============@audit-ok SearchConstructor
	//=======================================
	class SearchConstructor {
		jenis = "";
		tbl = "";
		ajax = false;
		result_ajax = {};
		url = "script/get_data";
		searchDelay = 600;
		constructor(elmSearch) {
			this.elmSearch = $(elmSearch); //element;
		}
		searchGlobal(
			allField = {
				minCharacters: 3,
				searchDelay: 600,
				jenis: "get_Search_Json",
				tbl: "sbu",
				data_send: {},
			}
		) {
			let MyElmSearch = this.elmSearch;
			console.log(MyElmSearch);
			this.jenis = allField.jenis;
			this.tbl = allField.tbl;
			switch (this.jenis) {
				case "get_Search_Json":
					switch (this.tbl) {
						case "dppa":
						case "renja_p":
							break;
						case "dpa_dppa":
							// this.url = "script/get_data";
							let kd_sub_keg = $(
								`form[name="form_modal"] .ui.dropdown[name="kd_sub_keg"]`
							).dropdown("get value");
							allField.data_send.kd_sub_keg = kd_sub_keg;
							console.log(allField);

							if (
								kd_sub_keg.length <= 0 ||
								!allField.data_send.hasOwnProperty("kd_sub_keg")
							) {
								// this.url = '';
							}
							break;
						case "value":
							break;
						case "value1":
							break;
						default:
							break;
					}
					break;
				case "value1":
					break;
				default:
					break;
			}
			let url = this.url;
			MyElmSearch.search({
				// type: 'category',
				cache: false,
				minCharacters: allField.minCharacters,
				maxResults: countRows(),
				searchDelay: allField.searchDelay,
				apiSettings: {
					method: "POST",
					url: url,
					// passed via POST
					data: Object.assign(
						{
							jenis: allField.jenis,
							tbl: allField.tbl,
							cari: function (value) {
								return MyElmSearch.search("get value");
							},
							rows: countRows(), //"all",
							halaman: 1,
						},
						allField.data_send
					),
				},
				// debug: true,
				ignoreDiacritics: true,
				fullTextSearch: "exact",
				fields: {
					category: "category",
					results: [0].results,
					title: "title",
					description: "description",
				},
				onSelect(result, response) {
					let jenis = allField.jenis;
					let tbl = allField.tbl;
					switch (jenis) {
						case "get_Search_Json":
							switch (tbl) {
								case "sbu":
								case "ssh":
								case "hspk":
								case "asb":
									let strText = parseFloat(result.harga_satuan);
									strText = accounting.formatNumber(
										result.harga_satuan,
										strText.countDecimals(),
										".",
										","
									);
									$(`form[name="form_modal"]`).form("set values", {
										id: result.value,
										kd_aset: result.kd_aset,
										uraian_barang: result.title,
										spesifikasi: result.spesifikasi,
										satuan: result.satuan,
										harga_satuan: strText,
										tkdn: result.tkdn,
										kd_akun: result.kd_akun,
										keterangan: result.keterangan,
										disable: result.disable,
									});
									break;
								case "dpa_dppa":
									let MyForm = $(`[name="form_modal"]`);
									var cellJumlah = MyForm.find(`table tfoot [name="jumlah"]`);
									var cellKontrak = MyForm.find(`table tfoot [name="kontrak"]`);
									// harus di cari dulu klo sdh ada add row tidak berlaku
									var cek_id = MyForm.find(
										`table tbody tr[id_row="${result.value}"]`
									);
									if (cek_id.length <= 0) {
										//vol
										let strvol = parseFloat(result.vol);
										strvol = accounting.formatNumber(
											strvol,
											strvol.countDecimals(),
											".",
											","
										);
										//jumlah
										let strText = result.jumlah;
										strText = parseFloat(strText);
										strText = accounting.formatNumber(
											strText,
											strText.countDecimals(),
											".",
											","
										);
										let trElm = `<tr id_row="${result.value}" pagu="${result.jumlah}" dok_anggaran="${result.dok_anggaran}"><td klm="kd_sub_keg">${result.kd_sub_keg}</td><td klm="uraian">${result.title}</td><td klm="vol_kontrak"><div contenteditable rms>${strvol}</div></td><td klm="sat_kontrak"><div contenteditable>${result.sat}</div></td><td klm="pagu">${strText}</td><td klm="kontrak"><div contenteditable oninput="onkeypressGlobal({ jns: 'uraian_sub_keg', tbl: 'renja_p' },this);" rms></div></td><td><button class="ui red basic icon mini button" name="del_row" jns="direct" tbl="remove_uraian" id_row="${result.value}"><i class="trash alternate outline icon"></i></button></td></tr>`;
										MyForm.find(`table tbody`).append(trElm);
										let pagu = 0;
										let kontrak = 0;
										$(`[name="form_modal"] table tbody tr`).each(function () {
											let element = $(this);
											pagu += Number(
												accounting.unformat(
													element.find(`[klm="pagu"]`).text(),
													","
												)
											);
											kontrak += Number(
												accounting.unformat(
													element.find(`[klm="kontrak"] div`).text(),
													","
												)
											);
										});
										strText = parseFloat(pagu);
										strText = accounting.formatNumber(
											strText,
											strText.countDecimals(),
											".",
											","
										);
										cellJumlah.text(strText);
										strText = parseFloat(kontrak);
										strText = accounting.formatNumber(
											strText,
											strText.countDecimals(),
											".",
											","
										);
										cellKontrak.text(strText);
									}
									$("[rms]").mathbiila();
									break;
								case "daftar_paket":
									let myForm = $(`[name="form_modal"]`);
									cellJumlah = myForm.find(`table tfoot [name="jumlah"]`);
									cellKontrak = myForm.find(`table tfoot [name="kontrak"]`);
									// harus di cari dulu klo sdh ada add row tidak berlaku
									cek_id = myForm.find(
										`table tbody tr[id_row="${result.value}"]`
									);
									let dataRowsAnggaran = result.uraian_id_uraian;
									myForm.attr("id_row_paket", result.value);
									let trElm = "";
									myForm.form("set values", {
										uraian: result.title,
										volume: result.volume,
										satuan: result.satuan,
										jumlah: accounting.formatNumber(result.jumlah, 2, ".", ","),
									});
									dataRowsAnggaran.forEach((value, key) => {
										let vol_kontrak = parseFloat(value.vol_kontrak);
										vol_kontrak = accounting.formatNumber(
											vol_kontrak,
											vol_kontrak.countDecimals(),
											".",
											","
										);
										let jumlah_pagu = parseFloat(value.jumlah_pagu);
										jumlah_pagu = accounting.formatNumber(
											jumlah_pagu,
											jumlah_pagu.countDecimals(),
											".",
											","
										);
										let jumlah_kontrak = parseFloat(value.jumlah_kontrak);
										jumlah_kontrak = accounting.formatNumber(
											jumlah_kontrak,
											jumlah_kontrak.countDecimals(),
											".",
											","
										);
										let realisasi_jumlah = parseFloat(value.realisasi_jumlah);
										realisasi_jumlah = accounting.formatNumber(
											realisasi_jumlah,
											realisasi_jumlah.countDecimals(),
											".",
											","
										);
										let realisasi_vol = parseFloat(value.realisasi_vol);
										realisasi_vol = accounting.formatNumber(
											realisasi_vol,
											realisasi_vol.countDecimals(),
											".",
											","
										);
										trElm += `<tr id_row_uraian_paket="${value.id_uraian_paket}" pagu="${value.jumlah_pagu}" dok_anggaran="${value.dok}">
											<td klm="kd_sub_keg">${value.kd_sub_keg}</td>
											<td klm="uraian">${value.uraian}</td>
											<td klm="vol_kontrak">${vol_kontrak}</td>
											<td klm="sat_kontrak">${value.sat_kontrak}</td>
											<td klm="pagu">${jumlah_pagu}</td>
											<td klm="jumlah_kontrak">${jumlah_kontrak}</td>
											<td klm="realisasi_vol">${realisasi_vol}</td>
											<td klm="realisasi_jumlah">${realisasi_jumlah}</td>
											<td klm="vol" class="positive">
												<div contenteditable oninput="onkeypressGlobal({ jns: 'realisasi', tbl: 'vol_realisasi' },this);" rms></div>
											</td>
											<td klm="jumlah" class="positive">
												<div contenteditable oninput="onkeypressGlobal({ jns: 'realisasi', tbl: 'vol_realisasi' },this);" rms></div>
											</td>
											<td><button class="ui blue basic icon mini button" name="modal_second" jns="direct" tbl="uraian_paket"
													id_row="${value.id_uraian_paket}"><i class="edit alternate outline icon"></i></button></td>
										</tr>`;
									});
									myForm.find(`table.tblUraian tbody`).html(trElm);
									onkeypressGlobal(
										{
											jns: "realisasi",
											tbl: "vol_realisasi",
										},
										this
									);
									$("[rms]").mathbiila();
									break;
								case "value1":
									break;
								default:
									break;
							}
							break;
						case "search_field_json":
							switch (tbl) {
								default:
									break;
							}
							break;
						case "value1":
							break;
						default:
							break;
					}
				},
			});
		}
	}
	//=======================================
	//===============MODAL GLOBAL=============
	//=======================================
	class ModalConstructor {
		//@audit-ok ModalConstructor
		constructor(modal) {
			this.modal = $(modal); //element;
		}
		globalModal() {
			let MyModal = this.modal;
			MyModal.modal({
				allowMultiple: true,
				//observeChanges: true,
				closable: false,
				transition: "zoom", //slide down,'slide up','browse right','browse','swing up','vertical flip','fly down','drop','zoom','scale'
				onDeny: function () {},
				onApprove: function () {
					// jika di tekan yes
					$(this).find("form").trigger("submit");
					return false;
				},
				onShow: function () {},
				onHidden: function () {
					// loaderHide();
					$(this).find("form").form("reset");
				},
			});
		}
	}
	class VisibilityConstructor {
		//@audit-ok VisibilityConstructor
		constructor(visibilityElemen) {
			this.visibility = $(visibilityElemen); //element;
		}
		visibility() {
			let elmVisibility = this.visibility;
			elmVisibility.visibility({
				once: false,
				// update size when new content loads
				observeChanges: true,
				onTopVisible: function (calculations) {
					// top is on screen
					console.log(`onTopVisible = `);
					console.log(calculations);
				},
				onTopPassed: function (calculations) {
					// top of element passed
					console.log(`onTopPassed =`);
					console.log(calculations);
				},
				onUpdate: function (calculations) {
					// do something whenever calculations adjust
					// console.log(`onUpdate =`);
					// console.log(calculations);
				},
				// load content on bottom edge visible
				onBottomVisible: function (calculations) {
					console.log(`onBottomVisible =`);
					console.log(calculations);
				},
			});
		}
	}
	function tok(elm) {
		$(".ui.sidebar").sidebar("hide");
		// elm.closest('.ui.accordion').find('.active').removeClass('active');
		elm.closest(".vertical.sidebar.menu").find(".active").removeClass("active");
	}
	//=============================
	//======  FUNCTION MASTER =====
	//=============================
	function cari(name = "") {
		$("#cari_data").attr("name", name);
		return $("#cari_data").val().trim();
	}
	function countRows() {
		return $("#countRow").dropdown("get value");
	}
	//=============================
	// ini general Ajax parameter.
	//=============================
	function ajaxParams(
		url,
		type,
		data,
		dataType,
		contentType,
		processData,
		callback
	) {
		this.url = url;
		this.type = type;
		this.formData = data;
		this.dataType = dataType;
		this.contentType = contentType;
		this.processData = processData;
		this.callback = callback;
	}
	//The global suksesAjax object, to be used for all scripts.
	var suksesAjax = [];
	// The general Ajax function.
	function generalAjax(params) {
		$.ajax({
			url: params.url,
			type: params.type,
			data: params.formData,
			dataType: params.dataType,
			contentType: params.contentType,
			processData: params.processData,
		})
			.done(function (data) {
				var callback = suksesAjax[params.callback](data);
			})
			.fail(function (jqXHR, textStatus, err) {
				loaderHide();
				try {
					var resultObject = JSON.parse(jqXHR.responseText, reviver);
					modal_notif(
						'<i class="huge info circle icon"></i>' + textStatus,
						jqXHR.responseText.split('"')[1]
					);
				} catch (e) {
					modal_notif('<i class="info icon"></i>' + textStatus, err);
				}
			});
	}
	function runAjax(
		url,
		type,
		formData,
		dataType,
		contentType,
		processData,
		callback,
		cryptos = false
	) {
		/*
					runAjax("script/master_read_xlsx", "POST", formData, false, false, false, 'upload_renja');// untuk type file
					runAjax("script/load_data", "POST", data, 'text', undefined, undefined, "draft_renstra");// type text
					*/
		if (type === undefined) {
			type = "POST";
		}
		if (dataType === undefined) {
			dataType = "Json";
		}
		if (contentType === undefined) {
			contentType = "application/x-www-form-urlencoded; charset=UTF-8";
		}
		if (processData === undefined) {
			processData = true;
		}
		if (dataType === "Json" || dataType === "json") {
			//console.log(formData);
			const start = new Date().getTime();
			if (cryptos) {
				Object.keys(formData).forEach((key) => {
					// console.log(key)
					// console.log(formData[key])
					const value = formData[key].toString();
					// console.log(value.toString().length)
					if (value.toString().length > 0) {
						formData[key] = enc.encrypt(value, halAwal);
					}
					formData.cry = cryptos;
					//formData.set(key, enc.encrypt(value, halAwal));
				});
			}
			const end = new Date().getTime();
			const diff = end - start;
			const seconds = diff / 1000; //Math.floor(diff / 1000 % 60);
			// console.log(`selisih ecrypt (s) : ${seconds}`);
		}
		var params = new ajaxParams(
			url,
			type,
			formData,
			dataType,
			contentType,
			processData,
			callback
		);
		generalAjax(params);
	}
	function modal_notif_hapus(kop, conten) {
		"use strict";
		$("#kop_notif_hapus").html(kop);
		$("#content_notif").html("<p>" + conten + "</p>");
	}
	function modal_notif(kop, conten) {
		"use strict";
		$("#kop_notifikasi").html(kop);
		$("#conten_notifikasi").html("<p>" + conten + "</p>");
		$(".ui.basic.modal.info").modal("show");
	}
	// fungsi notifikasi
	function loaderShow() {
		var tinggi = $(window).height();
		//$('body > .demo.page.dimmer').dimmer('setting', {closable:true, debug:false}).dimmer('show');
		$(".demo.page.dimmer:first")
			.css("height", tinggi)
			.dimmer({
				closable: false,
				debug: false,
				"set opacity": 0,
			})
			.dimmer("show");
		//jika lebih dari 120 detik otomatis hilang sendiri
		// setTimeout(function () {
		// 	$(".demo.page.dimmer:first").dimmer("hide");
		// }, 120000);
	}
	function loaderHide() {
		$(".demo.page.dimmer:first").dimmer("hide");
	}
	//====MEMBUAT ELEMEN HTML================
	//====contohdataElemen={atribut:'name="tambah" jns="harga_satuan" tbl="input"'}
	//================================
	function createHTML(namaElemen = "", dataElemen = {}) {
		//@audit-ok createHTML
		let acceptData = "atribut" in dataElemen ? dataElemen.accept : ".pdf";
		let labelTambahan =
			"labelTambahan" in dataElemen ? dataElemen.labelTambahan : "";
		let content = "content" in dataElemen ? dataElemen.content : "";
		let atribut = "atribut" in dataElemen ? dataElemen.atribut : "";
		let atribut2 = "atribut2" in dataElemen ? dataElemen.atribut2 : "";
		let aligned = "aligned" in dataElemen ? dataElemen.aligned : "";
		let header = "header" in dataElemen ? dataElemen.header : "h4";
		let atributField =
			"atributField" in dataElemen ? dataElemen.atributField : "";
		let atributLabel =
			"atributLabel" in dataElemen ? dataElemen.atributLabel : "";
		let classField =
			"classField" in dataElemen ? `${dataElemen.classField} ` : "";
		let kelas = "kelas" in dataElemen ? dataElemen.kelas : "";
		let kelas2 = "kelas2" in dataElemen ? dataElemen.kelas2 : "";
		let txtLabel = "txtLabel" in dataElemen ? dataElemen.txtLabel : "";
		let label = "label" in dataElemen ? dataElemen.label : "";
		let href = "href" in dataElemen ? dataElemen.href : ""; //file
		let file = "file" in dataElemen ? dataElemen.file : "file"; //file
		let textDrpdown = "textDrpdown" in dataElemen ? dataElemen.textDrpdown : "";
		let placeholderData =
			"placeholderData" in dataElemen ? dataElemen.placeholderData : "";
		let elemen1Data = "elemen1" in dataElemen ? dataElemen.elemen1 : "";
		let iconData = "icon" in dataElemen ? dataElemen.icon : "download icon";
		let icon = "icon" in dataElemen ? dataElemen.icon : "calendar";
		let icon2 = "icon2" in dataElemen ? dataElemen.icon2 : "";
		let posisi = "posisi" in dataElemen ? dataElemen.posisi : "left";
		let colorData = "color" in dataElemen ? dataElemen.color : "positive";
		let valueData = "value" in dataElemen ? dataElemen.value : "";
		let iconDataSeach = "icon" in dataElemen ? dataElemen.icon : "search icon";

		let dataArray = "dataArray" in dataElemen ? dataElemen.dataArray : []; //contoh untuk dropdown
		let typeText = "typeText" in dataElemen ? dataElemen.typeText : `text`;

		let headerTable = "headerTable" in dataElemen ? dataElemen.headerTable : "";
		let footerTable = "footerTable" in dataElemen ? dataElemen.footerTable : "";
		let bodyTable = "bodyTable" in dataElemen ? dataElemen.bodyTable : "";
		// let file
		let accept = "accept" in dataElemen ? dataElemen.accept : ".xlsx";
		let elemen = "";
		switch (namaElemen) {
			case "asn_tabel":
				elemen =
					createHTML("fieldDropdown", {
						label: label,
						atribut: 'name="asn" placeholder="Nama ASN..." non_data',
						kelas: "search clearable asn ajx selection",
						dataArray: [],
					}) +
					createHTML("tabel2", {
						atribut: atribut,
						kelas: `stackable celled structured`,
						headerTable: [
							[
								{ attr: "", lbl: `NAMA` },
								{
									attr: "",
									class: "collapsing",
									lbl: `PANGKAT/GOLONGAN`,
								},
								{
									attr: "",
									class: "collapsing",
									lbl: `NIP`,
								},
								{ attr: "", lbl: `JABATAN` },
								{ attr: "", lbl: `JABATAN SK` },
								{
									attr: "",
									lbl: ``,
									class: "collapsing",
								},
							],
						],
						footerTable: [],
						bodyTable: bodyTable,
					});
				break;
			case "tabel_1klm":
				elemen = createHTML("tabel2", {
					atribut: `name="${atribut}"`,
					kelas: `celled structured`,
					headerTable: [
						[
							{ attr: "", lbl: label },
							{ attr: "", class: "collapsing", lbl: `JENIS` },
							{
								attr: "",
								lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="klm3"><i class="plus icon"></i></button>`,
								class: "collapsing",
							},
						],
					],
					footerTable: [
						{
							lbl: ``,
						},
						{
							lbl: "",
							attr: ``,
						},
						{
							lbl: "",
							attr: ``,
						},
					],
					bodyTable: bodyTable,
				});
				break;
			case "divider_tabel_1klm":
				txtLabel =
					"txtLabel" in dataElemen ? dataElemen.txtLabel : "PENJELASAN";
				label = "label" in dataElemen ? dataElemen.label : "URAIAN";
				icon =
					"icon" in dataElemen
						? dataElemen.icon
						: '<i class="feather alternate icon"></i>';
				elemen =
					createHTML("divider", {
						header: "h4",
						icon: icon,
						label: txtLabel,
					}) +
					createHTML("tabel2", {
						atribut: `name="${atribut}"`,
						kelas: `celled structured`,
						headerTable: [
							[
								{ attr: "", lbl: label },
								{ attr: "", class: "collapsing", lbl: `JENIS` },
								{
									attr: "",
									lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="klm3"><i class="plus icon"></i></button>`,
									class: "collapsing",
								},
							],
						],
						footerTable: [
							{
								lbl: ``,
							},
							{
								lbl: "",
								attr: ``,
							},
							{
								lbl: "",
								attr: ``,
							},
						],
						bodyTable: bodyTable,
					});
				break;
			case "divider_tabel_2klm":
				txtLabel =
					"txtLabel" in dataElemen ? dataElemen.txtLabel : "PENJELASAN";
				label = "label" in dataElemen ? dataElemen.label : "URAIAN";
				icon =
					"icon" in dataElemen
						? dataElemen.icon
						: '<i class="feather alternate icon"></i>';
				elemen =
					createHTML("divider", {
						header: "h4",
						icon: icon,
						label: txtLabel,
					}) + createHTML("tabel2", {
					atribut: `name="${atribut}"`,
					kelas: `celled structured`,
					headerTable: [
						[
							{ attr: "", lbl: label },
							{ attr: "", lbl: `VALUE` },
							{ attr: "", class: "collapsing", lbl: `JENIS` },
							{
								attr: "",
								lbl: `<button class="ui green icon mini button" name="add" jns="add_row" tbl="klm4"><i class="plus icon"></i></button>`,
								class: "collapsing",
							},
						],
					],
					footerTable: [
						{
							lbl: ``,
						},
						{
							lbl: ``,
						},
						{
							lbl: "",
							attr: ``,
						},
						{
							lbl: "",
							attr: ``,
						},
					],
					bodyTable: bodyTable,
				});
				break;
			case "header":
				elemen = `<${header} class="ui header${kelas}">${content}</${header}>`;
				break;
			case "icon_menu":
				let menu = "";
				dataArray.forEach(function (val) {
					// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
					let classRow = val.class !== undefined ? ` ${val.class}` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					menu += `<a class="item${classRow}" ${attrRow}>${lblRow}</a>`;
				});
				// <a class="item aksi active" jns="orientasi_print" sub="P"><i class="id badge icon"></i>Portrait</a>
				//     <a class="item aksi" jns="orientasi_print" sub="L"><i class="id card icon"></i>Lanscape</a>
				elemen = `<div class="${classField}field"${atributField}>
                <label>${label}</label>
                <input type="hidden" ${atribut}>
                <div class="ui compact labeled icon menu">${menu}</div></div>`;
				break;
			case "div":
				elemen = `<div class="${kelas}" ${atribut}>${content}</div>`;
				break;
			case "card":
				elemen = `<div class="ui fluid card">
					<div class="image">
						<img src="../images/avatar2/large/kristy.png">
					</div>
					<div class="content">
						<a class="header">Kristy</a>
						<div class="meta">
							<span class="date">Joined in 2013</span>
						</div>
						<div class="description">
							Kristy is an art director living in New York.
						</div>
					</div>
					<div class="extra content">
						<a>
							<i class="user icon"></i>
							22 Friends
						</a>
					</div>
				</div>`;
				break;
			case "card2":
				elemen = `<div class="ui special fluid card">
					<div class="blurring dimmable image">
						<div class="ui dimmer">
							<div class="content">
								<div class="center">
									<div class="ui inverted button">Add Friend</div>
								</div>
							</div>
						</div>
						<img src="img/avatar/default.jpeg" onerror="imgsrc(this)">
					</div>
					<div class="content">
						<a class="header">Team Fu</a>
						<div class="meta">
							<span class="date">Created in Sep 2014</span>
						</div>
					</div>
					<div class="extra content">
						<a>
							<i class="users icon"></i>
							2 Members
						</a>
					</div>
					</div>`;
				break;
			case "card3":
				elemen = `<div class="ui special fluid card">
						<div class="content">
							<div class="right floated meta">14h</div>
							<img class="ui avatar image" src="img/avatar/default.jpeg" onerror="imgsrc(this)">${label}
						</div>
						<div class="blurring dimmable image">
							<div class="ui dimmer">
								<div class="content">
									<div class="center">
										<button class="ui inverted icon button" ${atribut} >
											<i class="file icon"></i>Upload File
										</button>
									</div>
								</div>
							</div>
							<img src="img/avatar/default.jpeg" onerror="imgsrc(this)">
						</div>
						<div class="content">
							<span class="right floated">
								<i class="heart outline like icon"></i>
								likes
							</span>
							<i class="comment icon"></i>
							comments
						</div>
					</div>`;
				break;
			case "card4":
				elemen = `<div class="ui fluid card" hidden><div class="ui fluid image"><a class="ui teal right ribbon label" href="" target="_blank">Download</a><img jns="img" onerror="imgsrc(this)"></div><div class="content"><div class="header">Dokumentasi</div></div><div class="extra content"><span class="left floated like"><i class="like icon"></i>Like</span><span class="right floated star"><i class="star icon"></i>Favorite</span></div></div>`;
				break;
			case "button":
				elemen = `<button class="ui ${kelas} button" ${atribut}>${valueData}</button>`;
				break;
			case "fieldButton":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><button class="ui ${kelas} button" ${atribut}>${valueData}</button></div>`;
				break;
			case "fieldButtonAnimated":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui animated fade button" tabindex="0">
				<div class="visible content">Sign-up for a Pro account</div>
				<div class="hidden content">${valueData}</div></div></div>`;
				break;
			case "tabel":
				var head = headerTable.length > 0 ? "<thead><tr>" : "";
				var foot = footerTable.length > 0 ? "<tfoot><tr>" : "";
				var body = `<tbody>`;
				//buat header tabel
				headerTable.forEach(function (val) {
					// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
					let classRow = val.class !== undefined ? ` class="${val.class}"` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					head += `<th${classRow} ${attrRow}>${lblRow}</th$>`;
				});
				head += headerTable.length > 0 ? "</thead>" : "";
				//buat body tabel
				bodyTable.forEach(function (val) {
					// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
					let classRow = val.class !== undefined ? ` class="${val.class}"` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					body += `<td${classRow} ${attrRow}>${lblRow}</td>`;
				});
				body += `</tbody>`;
				//buat foot tabel
				footerTable.forEach(function (val) {
					// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
					let classRow = val.class !== undefined ? ` class="${val.class}"` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					foot += `<td${classRow} ${attrRow}>${lblRow}</td>`;
				});
				foot += footerTable.length > 0 ? "</tfoot>" : "";
				elemen = `<table class="ui ${kelas} table" ${atribut}>${head}${body}${foot}</table>`;
				break;
			case "tabel2": //untuk tabel header lebih 1
				var head = headerTable.length > 0 ? "<thead>" : "";
				var foot = footerTable.length > 0 ? "<tfoot><tr>" : "";
				var body = `<tbody>`;
				//buat header tabel
				headerTable.forEach(function (val) {
					let classRow = val.class !== undefined ? ` class="${val.class}"` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					head += `<tr${classRow} ${attrRow}>`;
					val.forEach(function (val2) {
						// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
						classRow = val2.class !== undefined ? ` class="${val2.class}"` : "";
						lblRow = val2.lbl !== undefined ? `${val2.lbl}` : "";
						attrRow = val2.attr !== undefined ? ` ${val2.attr}` : "";
						head += `<th${classRow} ${attrRow}>${lblRow}</th>`;
					});
					head += `</tr>`;
				});
				head += headerTable.length > 0 ? "</thead>" : "";
				//buat body tabel
				if (typeof bodyTable === "object") {
					bodyTable.forEach(function (val) {
						if (typeof val !== "string") {
							// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
							let classRow =
								val.class !== undefined ? ` class="${val.class}"` : "";
							let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
							let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
							body += `<tr${classRow} ${attrRow}>`;
							val.forEach(function (val2) {
								// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
								classRow =
									val2.class !== undefined ? ` class="${val2.class}"` : "";
								lblRow = val2.lbl !== undefined ? `${val2.lbl}` : "";
								attrRow = val2.attr !== undefined ? ` ${val2.attr}` : "";
								head += `<td${classRow}${attrRow}>${lblRow}</td>`;
							});
							body += `</tr>`;
						} else {
							body += val;
						}
					});
				} else {
					body += bodyTable;
				}
				body += `</tbody>`;
				//buat foot tabel
				footerTable.forEach(function (val) {
					// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
					let classRow = val.class !== undefined ? ` class="${val.class}"` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					foot += `<td${classRow} ${attrRow}>${lblRow}</td>`;
				});
				foot += footerTable.length > 0 ? "</tfoot>" : "";
				elemen = `<table class="ui ${kelas} table" ${atribut}>${head}${body}${foot}</table>`;
				break;
			case "tr_tabel":
				body = ``;
				bodyTable.forEach(function (val) {
					let classRow = val.class !== undefined ? ` class="${val.class}"` : "";
					let lblRow = val.lbl !== undefined ? `${val.lbl}` : "";
					let attrRow = val.attr !== undefined ? ` ${val.attr}` : "";
					body += `<tr${classRow} ${attrRow}>`;

					val.forEach(function (val2) {
						// console.log(`Judul: ${val.judul}, Penulis: ${val.penulis}`);
						classRow = val2.class !== undefined ? ` class="${val2.class}"` : "";
						lblRow = val2.lbl !== undefined ? `${val2.lbl}` : "";
						attrRow = val2.attr !== undefined ? ` ${val2.attr}` : "";
						body += `<td${classRow}${attrRow}>${lblRow}</td>`;
					});
					body += `</tr>`;
				});
				elemen = body;
				break;
			case "errorForm":
				elemen = `<div class="ui error message"></div><div class="ui icon success message"><i class="check icon"></i><div class="content"><div class="header">Form sudah lengkap</div><p>anda bisa submit form</p></div></div>`;
				break;
			case "text":
				elemen = `<div class="${classField}field" ${atributField}><input type="text" ${atribut}></div>`;
				break;
			case "accordionField":
				elemen = `<div class="ui accordion${classField} field" ${atribut} ${atributField}><div class="title"><i class="icon dropdown"></i>${label} </div><div class="content field">${content} </div></div>`;
				break;
			case "fieldTextAccordion":
				elemen = `<div><label class="visible" style="display: block !important;">${label}</label><input ${atribut} type="text" class="visible" style="display: inline-block !important;"></div>`;
				break;
			case "ribbonLabel":
				elemen = `<a class="ui ${posisi} ribbon label ${kelas}">${label}</a>`;
				break;
			case "piledSegment":
				elemen = `<div class="ui piled segment ${kelas}"></div>`;
				break;
			case "segment":
				elemen = `<div class="ui segment ${kelas}"></div>`;
				break;
			case "segments":
				elemen = `<div class="ui piled segments ${kelas}">
				<div class="ui segment">
					<p>Top</p>
					</div>
					<div class="ui segment">
						<p>Middle</p>
					</div>
					<div class="ui segment">
						<p>Bottom</p>
					</div>
					</div>`;
				break;
			case "messageLink":
				elemen = `<div class="ui icon message ${colorData}"><i class="${iconData}"></i><div class="content"><div class="header">${label} </div><a ${atribut} target="_blank">${valueData}</a></div></div>`;
				break;
			case "message":
				elemen = `<div class="ui icon message ${colorData}"><i class="${iconData}"></i><div class="content"><div class="header">${label} </div>${valueData}</div></div>`;
				break;
			case "divider": //left aligned
				elemen = `<${header} class="ui horizontal ${aligned} divider header">${icon2}${label}</${header}>`;
				break;
			case "dividerHeader":
				elemen = `<h3 class="ui dividing header">${label}</h3>`;
				break;
			case "dividerClearing":
				elemen = '<div class="ui clearing divider"></div>';
				break;
			case "dividerFitted":
				elemen = '<div class="ui fitted divider"></div>';
				break;
			case "dividerIcon":
				elemen = '<div class="ui horizontal divider"></div>';
				break;
			case "dividerHidden":
				elemen = `<div class="ui hidden divider"></div>`;
				break;
			case "fieldLabel":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><a class="ui fluid label ${kelas}" href="${href}" ${atribut}><i class="${icon} icon"></i>${valueData}</a></div>`;
				break;
			case "fieldSearchGrid": //untuk modal
				elemen = `<div class="ui aligned grid ${kelas2}">
					<div class="right floated right aligned column">
						<div class="ui scrolling search ${kelas} fluid category">
							<div class="ui icon input"><input class="prompt" type="text" autocomplete="off" ${atribut}><i class="search icon"></i></div>
							<div class="results"></div>
						</div>
					</div>
				</div>`;
				break;
			case "fieldSearch":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui fluid scrolling search ${kelas}"><div class="ui icon fluid input"><input class="prompt" type="text" ${atribut} placeholder="Search..."><i class="search icon"></i></div><div class="results"></div></div></div>`;
				break;
			case "calendar":
				elemen = `<div class="${classField}field" ${atributField}><div class="ui calendar ${kelas}"><div class="ui fluid input left icon"><i class="calendar icon"></i><input type="text" ${atribut}></div></div></div>`;
				break;
			case "fieldCalendar":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui calendar ${kelas}"  ${atribut2}><div class="ui input left icon"><i class="calendar icon"></i><input type="text" ${atribut}></div></div></div>`;
				break;
			case "fieldAndLabel":
				elemen = `<div class="${classField}field" ${atributField}><label>${label} ${labelTambahan}</label> ${elemen1Data}</div>`;
				break;
			case "fieldText":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}${labelTambahan}</label><div class="ui ${kelas} input"><input type="${typeText}" ${atribut} ></div></div>`;
				break;
			case "multiFieldTextAction":
				let inputElm = "";
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					inputElm += `<input type="${typeText}" ${rowsData}>`;
				}
				elemen = `<div class="${classField}field" ${atributField}><label>
					${label}${labelTambahan}
					</label><div class="ui action fluid input multi">
					${inputElm}
					<button class="ui teal button" ${atributLabel}>${txtLabel}</button> </div></div>`;
				break;
			case "fieldTextAction":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui action input"><input type="${typeText}" ${atribut}><button class="ui teal button icon"  ${atributLabel}>${txtLabel}</button></div></div>`;
				break;
			case "fieldTextLabelKanan":
				elemen = `<div class="${classField}field" ${atributField}><label>${label} ${labelTambahan}</label><div class="ui fluid right labeled input"><input type="${typeText}" ${placeholderData} ${atribut}><div class="ui basic label" ${atributLabel}>" ${txtLabel}</div></div></div>`;
				break;
			case "fieldTextIcon":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}${labelTambahan}</label><div class="ui fluid ${posisi} icon input"><input type="${typeText}" ${atribut}><i class="${icon} icon"></i></div></div>`;
				break;
			case "textIcon":
				elemen =
					`<div class="ui icon fluid input"><input type="${typeText}" ` +
					placeholderData +
					" " +
					atribut +
					'><i class="' +
					iconDataSeach +
					'"></i></div>';
				break;
			case "fieldText2":
				elemen =
					`<div class="${classField}field" ${atributField}><label>` +
					label +
					labelTambahan +
					"</label><input " +
					placeholderData +
					' name="username" type="text" ' +
					atribut +
					"></div>";
				break;
			case "fieldFileInput":
				elemen =
					`<div class="${classField}field" ${atributField}><label>` +
					label +
					labelTambahan +
					'</label><div class="ui file input"><input type="file" ' +
					atribut +
					"></div></div>";
				break;
			case "fieldFileInput2":
				//atribut file hanya placeholder
				elemen = `<div class="${classField}field" ${atributField}><label>${label}${labelTambahan}</label><div class="ui fluid right action left icon input"><i class="folder open yellow icon"></i><input type="text" placeholder="${placeholderData}" readonly name_bayang="${file}" name="dum_file" ${atribut}><input hidden type="file" nama="file" name="${file}" accept="${accept}" ${atribut2} non_data><button class="ui red icon button" name="del_file" type="button"><i class="erase icon"></i></button></div></div>`;
				break;
			case "segment":
				elemen = `<div class="ui segment ${kelas}" ${atribut}>${label}</div>`;
				break;
			case "fieldTextarea":
				elemen = `<div class="${classField}field" ${atributField}><label>${label}${labelTambahan}</label><textarea 
					${atribut}></textarea></div>`;
				break;
			case "label":
				elemen = `<label>${label}</label>`;
				break;
			case "fieldRadioCheckbox":
				elemen = `<div class="inline${classField} field" ${atributField}><div class="ui radio checkbox"><input type="radio" tabindex="0" class="hidden" ${atribut}><label>${label}</label></div></div>`;
				break;
			case "fieldCheckbox":
				elemen = `<div class="inline${classField} field" ${atributField}><div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden" ${atribut}><label>${label}</label></div></div>`;
				break;
			case "fielToggleCheckbox":
				elemen = `<div class="${classField}field" ${atributField}><div class="ui toggle checkbox"><input type="checkbox" ${atribut}><label>${txtLabel}</label></div></div>`;
				// `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui toggle checkbox"><input type="checkbox" ${atribut}><label>${txtLabel}</label></div></div>`;
				break;
			case "labelToggleCheckbox": //dengan label
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui toggle checkbox"><input type="checkbox" ${atribut}><label>${txtLabel}</label></div></div>`;
				// `<div class="${classField}field" ${atributField}><label>${label}</label><div class="ui toggle checkbox"><input type="checkbox" ${atribut}><label>${txtLabel}</label></div></div>`;
				break;
			case "fieldTxtDropdownLabel":
				elemen = `<div class="${classField}field" ${atributField}>
					<div class="ui right labeled input fluid">
						<input type="text" placeholder="Koefisien" ${atribut2}>
						<div class="ui basic  dropdown label ${kelas}" placeholder="satuan">
							<input type="hidden" ${atribut}><i class="dropdown icon"></i>
							<div class="default text">${textDrpdown}</div>
							<div class="menu">
							</div>
						</div>
					</div>
				</div>`;
				break;
			case "fields2":
				let elm = "";
				if (typeof atribut === "object") {
					atribut.forEach(myFunction);
					function myFunction(item, index, arr) {
						elm += createHTML("fieldTxtDropdownLabel", {
							label: "Koefisien Perkalian",
							kelas: kelas2[index],
							textDrpdown: "sat.",
							atribut: item,
							atribut2: atribut2[index],
						});
					}
				}
				elemen = `<div class="${classField}field" ${atributField}><label>${label}</label>
				${elm}
				</div>`;
				break;
			case "fields":
				elemen = `<div class="${classField}fields" ${atributField}>${content}</div>`;
				break;
			case "fieldDropdownLabel":
				var elemen11 = `<div class="${classField}field" ${atributField}><label> ${label}
					</label><div class="ui right labeled input"><div class="ui dropdown fluid 
					${kelas}" ${atribut}><input type="hidden" ${atribut}><i class="dropdown icon"></i><div class="default text">${textDrpdown}</div><div class="menu">`;
				///Memisahkan array
				var elemen22 = "";
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					let dataValue = rowsData[0];
					// active selected
					let classItem = "item";
					if (rowsData.length === 1) {
						let txt = dataValue;
						if (rowsData[2]) {
							if (rowsData[2] === "active" || rowsData[3] === "active") {
								classItem = "item active selected";
							}
						}
						elemen22 +=
							`<div class="${classItem}" data-value="` +
							dataValue +
							'">' +
							txt +
							"</div>";
					} else if (rowsData.length === 2) {
						let txt = rowsData[1];
						if (rowsData[2]) {
							if (rowsData[2] === "active" || rowsData[3] === "active") {
								classItem = "item active selected";
							}
						}
						elemen22 +=
							`<div class="${classItem}" data-value="` +
							dataValue +
							'">' +
							txt +
							"</div>";
					} else if (rowsData.length >= 3) {
						//mempunyai deskripsi
						let txt = rowsData[1];
						var deskripsi = rowsData[2];
						if (deskripsi !== "active") {
							elemen22 +=
								`<div class="${classItem}" data-value="` +
								dataValue +
								'"><span class="description">' +
								deskripsi +
								'</span><span class="text">' +
								txt +
								"</span></div>";
						} else {
							elemen22 +=
								`<div class="${classItem}" data-value="` +
								dataValue +
								'">' +
								txt +
								"</div>";
						}
					}
				}
				var elemen33 = `</div></div><button class="ui teal label icon button" ${atributLabel}>${txtLabel}</button>
				</div></div>`;
				elemen = elemen11 + elemen22 + elemen33;
				break;
			case "fieldDropdown":
				///Memisahkan array
				elemen22 = "";
				var disableatributActive = false;
				var txtSelectedActive = "";
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					let dataValue = rowsData[0];
					// active selected
					let classItem = "item";
					switch (dataValue) {
						case "divider":
							elemen22 += `<div class="divider"></div>`;
							break;
						case "header":
							elemen22 += `<div class="header">${rowsData[1]}</div>`;
							break;
						default:
							if (rowsData.length === 1) {
								let txt = dataValue;
								if (rowsData[2]) {
									if (rowsData[2] === "active" || rowsData[3] === "active") {
										classItem = "item active selected";
										disableatributActive = true;
										txtSelectedActive = ` value="${dataValue}"`;
									}
								}
								elemen22 +=
									`<div class="${classItem}" data-value="` +
									dataValue +
									'">' +
									txt +
									"</div>";
							} else if (rowsData.length === 2) {
								let txt = rowsData[1];
								if (rowsData[2] === "active" || rowsData[3] === "active") {
									classItem = "item active selected";
									disableatributActive = true;
									txtSelectedActive = ` value="${dataValue}"`;
								}
								elemen22 +=
									`<div class="${classItem}" data-value="` +
									dataValue +
									'">' +
									txt +
									"</div>";
							} else if (rowsData.length >= 3) {
								//mempunyai deskripsi
								let txt = rowsData[1];
								deskripsi = rowsData[2];
								if (deskripsi !== "active") {
									elemen22 +=
										`<div class="${classItem}" data-value="` +
										dataValue +
										'"><span class="description">' +
										deskripsi +
										'</span><span class="text">' +
										txt +
										"</span></div>";
								} else {
									if (rowsData[2] === "active" || rowsData[3] === "active") {
										classItem = "item active selected";
										disableatributActive = true;
										txtSelectedActive = ` value="${dataValue}"`;
									}
									elemen22 +=
										`<div class="${classItem}" data-value="` +
										dataValue +
										'">' +
										txt +
										"</div>";
								}
							}
							break;
					}
				}
				var atributActive = atribut;
				if (disableatributActive) {
					atributActive += txtSelectedActive;
				}
				elemen11 =
					`<div class="${classField}field" ${atributField}><label>` +
					label +
					'</label><div class="ui dropdown ' +
					kelas +
					'" ' +
					atribut +
					'><input type="hidden" ' +
					atributActive +
					'><i class="dropdown icon"></i><div class="default text">' +
					textDrpdown +
					'</div><div class="menu">';
				elemen33 = "</div></div></div>";
				elemen = elemen11 + elemen22 + elemen33;
				break;
			default:
				break;
		}
		if (darkmodeEnabled === true) {
			let $elemen = $(elemen);
			if ($elemen.hasClass("ui")) {
				$elemen.addClass("inverted");
			}
			// $elemen.find(".ui, .icon:not(.ui.button i.icon)").addClass("inverted");
			$elemen.find(".ui, .icon:not(.ui.button > i.icon)").addClass("inverted");
			// $elemen.find(".ui, .icon:not(.buttons .icon), .icon:not(.buttons .button)").addClass("inverted");
			// elemen = $elemen.prop('outerHTML');
			// Ubah semua elemen dalam kumpulan menjadi string HTML
			elemen = $elemen
				.map(function () {
					return this.outerHTML;
				})
				.get()
				.join("");
		}
		return elemen;
	}
	//========================================
	//=================get ip addres==========
	//========================================
	function getIpAddress() {
		return $.getJSON("http://ip-api.com/json", function (ipData) {
			document.write(ipData.query);
		});
	}
	function toggleDarkMode() {
		// add fomantic's inverted class to all ui elements
		$("body").find(".ui:not(.hidden),.icon").addClass("inverted");
		$("body").find(".ui.modal,.ui.form").addClass("inverted");
		// add custom inverted class to body
		$("body").addClass("inverted");
		// simple toggle icon change
		$(`a[name="change_themes"]`).attr("theme", "dark");
		$(`a[name="change_themes"] > i`).removeClass("moon");
		$(`a[name="change_themes"] > i`).addClass("sun");
		return;
	}
	function toggleLightMode() {
		// remove fomantic's inverted from all ui elements
		$("body").find(".ui:not(.hidden),.icon").removeClass("inverted");
		$("body").find(".ui.modal,.ui.form").removeClass("inverted");
		$("body")
			.find(".ui.main.menu,.left.vertical.sidebar.menu")
			.addClass("inverted");
		// remove custom inverted class to body
		$("body").removeClass("inverted");
		// change button icon
		$(`a[name="change_themes"]`).attr("theme", "light");
		$(`a[name="change_themes"] > i`).removeClass("sun");
		$(`a[name="change_themes"] > i`).addClass("moon");
		return;
	}
	/**
	 * Dark mode
	 *
	 * Adds .inverted to all components.
	 */
	function ready() {
		const darkModePreference = window.matchMedia(
			"(prefers-color-scheme: dark)"
		);
		if (darkModePreference.matches) {
			const usedComponents = [
				"container",
				"grid",
				"button",
				"calendar",
				"card",
				"checkbox",
				"dimmer",
				"divider",
				"dropdown",
				"form",
				"flyout",
				"header",
				"icon",
				"items",
				"image",
				"input",
				"label",
				"list",
				"loader",
				"menu",
				"message",
				"modal",
				"placeholder",
				"popup",
				"progress",
				"segment",
				"sidebar",
				"statistics",
				"step",
				"tab",
				"table",
				"text",
				"toast",
				"api",
				"transition",
			];
			usedComponents.forEach((usedComponent) => {
				let uiComponents = document.querySelectorAll(".ui." + usedComponent);
				uiComponents.forEach((component) => {
					if (component.classList.contains("inverted")) {
						component.classList.remove("inverted");
					} else {
						component.classList.add("inverted");
					}
				});
			});
		}
	}
	function darkMode() {
		let uiComponents = document.querySelectorAll(".ui");
		uiComponents.forEach((component) => {
			if (component.classList.contains("inverted")) {
				component.classList.remove("inverted");
			} else {
				component.classList.add("inverted");
			}
		});
	}
	// ready();
	// harus darkModePreference = true
	// document.addEventListener("DOMContentLoaded", darkMode());
	//============================================================
	// . FUNGSI ADD RULE UNTUK SEMUA INPUT DAN TEXT AREA
	//============================================================
	function addRulesForm(formku) {
		$(formku).form("set auto check");
		let elmtInputTextarea = formku.find($("input[name],textarea[name]"));
		for (const iterator of elmtInputTextarea) {
			let atribut = $(iterator).attr("name");
			let lbl = $(iterator).attr("placeholder");
			if (lbl === undefined) {
				lbl = $(iterator).closest(".field").find("label").text();
				if (lbl === undefined || lbl === "") {
					lbl = $(iterator).closest(".field").find("div.sub.header").text();
				}
				if (lbl === undefined || lbl === "") {
					lbl = atribut.replaceAll(/_/g, " ");
				}
			}
			let non_data = $(iterator).attr("non_data");
			if (typeof non_data === "undefined" || non_data === false) {
				// console.log('masuk addRulesForm');
				let attrRules = $(iterator).attr("rules");
				if (typeof attrRules === "undefined" || attrRules === false) {
					//tanpa rule spesifik
					formku.form("add rule", atribut, {
						rules: [
							{
								type: "empty",
								prompt: "Lengkapi Data " + lbl,
							},
						],
					});
				} else {
					//dengan rules spesifik pisahkan rules dengan koma2x ",,", contoh rules="minLength[6],,regExp[/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/i]"
					let rules = attrRules.split(",,");
					let rulesku = {};
					rules.forEach((value, key) => {
						rulesku.type = `${value}`;
					});
					formku.form("add rule", atribut, {
						rules: [rulesku],
					});
				}
			} else {
				formku.form("remove field", atribut);
			}
		}
	}
	//
	function removeRulesForm(formku) {
		var attrName = formku.find($("input[name],textarea[name]"));
		var i = 0;
		//console.log(formku)
		for (i = 0; i < attrName.length; i++) {
			var atribut = formku.find(attrName[i]).attr("name");
			var lbl = formku.find(attrName[i]).attr("placeholder");
			if (lbl === undefined) {
				lbl = formku.find(attrName[i]).closest(".field").find("label").text();
				if (lbl === undefined || lbl === "") {
					lbl = formku
						.find(attrName[i])
						.closest(".field")
						.find("div.sub.header")
						.text();
				}
				if (lbl === undefined || lbl === "") {
					lbl = atribut.replaceAll(/_/g, " ");
				}
			}
			var non_data = formku.find(attrName[i]).attr("non_data");
			if (typeof non_data === "undefined" || non_data === false) {
				if (atribut) {
					//formku.form("remove rule", atribut);
					formku.form("remove field", atribut);
					//console.log('atribut remove rule: ' + atribut)
				}
			}
		}
		$(formku).form("set auto check");
	}
	//
	function showToast(message, settings) {
		settings.title = settings.title === undefined ? "info !" : settings.title;
		settings.position =
			settings.position === undefined ? "top center" : settings.position;
		settings.class = settings.class === undefined ? "info" : settings.class; //warna
		settings.icon = settings.icon === undefined ? false : settings.icon;
		settings.showProgress =
			settings.showProgress === undefined ? "bottom" : settings.showProgress;
		settings.displayTime =
			settings.displayTime === undefined ? 4500 : settings.displayTime; //0 jika ingin tampil terus sebelum diklik
		settings.showMethod =
			settings.showMethod === undefined ? "zoom" : settings.showMethod;
		settings.showDuration =
			settings.showDuration === undefined ? 1000 : settings.showDuration;
		settings.hideMethod =
			settings.hideMethod === undefined ? "fade" : settings.hideMethod;
		settings.hideDuration =
			settings.hideDuration === undefined ? 1000 : settings.hideDuration;
		$("body").toast({
			title: settings.title,
			position: settings.position,
			class: settings.class,
			showIcon: settings.icon,
			message: message,
			showProgress: settings.showProgress,
			displayTime: settings.displayTime,
			transition: {
				showMethod: settings.showMethod,
				showDuration: settings.showDuration,
				hideMethod: settings.hideMethod,
				hideDuration: settings.hideDuration,
			},
		});
	}
	var delay = (function () {
		var timer = 0;
		return function (callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();
	setTimeout(function () {}, 500);
	//menghitung decimal dibelakang koma
	//var x = 23.453453453;
	//console.log(x.countDecimals()); // 9
	Number.prototype.countDecimals = function () {
		if (Math.floor(this.valueOf()) === this.valueOf()) return 0;
		var str = this.toString();
		if (str.indexOf(".") !== -1 && str.indexOf("-") !== -1) {
			return str.split("-")[1] || 0;
		} else if (str.indexOf(".") !== -1) {
			return str.split(".")[1].length || 0;
		}
		return str.split("-")[1] || 0;
	};
	/*
		Number.prototype.countDecimals = function () {
		if (Math.floor(this.valueOf()) === this.valueOf()) return 0;
		var str = this.toString();
		var decimalIndex = str.indexOf(".");
		var decimalPart = decimalIndex !== -1 ? str.slice(decimalIndex + 1) : "";
		if (decimalPart.length > 0 && str[0] === '-') {
			return decimalPart.length;
		} else if (decimalPart.length > 0) {
			return decimalPart.length;
		}
		return 0;
		};
		*/
	//touppercase "pascal".toProperCase();
	String.prototype.toProperCase = function () {
		return this.replace(/\w\S*/g, function (txt) {
			return txt.charAt(0).toUpperCase() + txt.substring(1).toLowerCase();
		});
	};
	//mode dark automatis
	if (kudarkMode === true && theme === "auto") {
		$(`a[name="change_themes"]`).trigger("click");
	}
});
// onkeypress="return rumus(event);"
function onkeypressGlobal(
	params = { jns: "uraian_sub_keg", tbl: "renja_p" },
	evt
) {
	let ini = $(this);
	console.log(evt);

	let form = $(evt).closest(`form`);
	let tr = $(evt).closest(`tbody`).find("tr");
	const keyPressed = String.fromCharCode(evt.which);
	const currentFormula = $(evt.target).val(); // Get the current formula string
	switch (params.jns) {
		case "uraian_sub_keg":
			switch (params.tbl) {
				case "renja_p":
					let strText;
					form = $(`form[name="form_modal"]`);
					let cellJumlah = form.find(`table tfoot [name="jumlah"]`);
					let cellKontrak = form.find(`table tfoot [name="kontrak"]`);
					let pagu = 0;
					let kontrak = 0;
					$(`[name="form_modal"] table tbody tr`).each(function () {
						let element = $(this);
						let paguTemp = Number(
							accounting.unformat(element.find(`[klm="pagu"]`).text(), ",")
						);

						let kontrakTemp = Number(
							accounting.unformat(
								element.find(`[klm="kontrak"] div`).text(),
								","
							)
						);
						if (kontrakTemp > paguTemp) {
							kontrakTemp = paguTemp;
							strText = parseFloat(paguTemp);
							strText = accounting.formatNumber(
								strText,
								strText.countDecimals(),
								".",
								","
							);
							// return false;
							$(evt).text(strText);
						}
						pagu += paguTemp;
						kontrak += kontrakTemp;
					});
					strText = parseFloat(pagu);
					strText = accounting.formatNumber(
						strText,
						strText.countDecimals(),
						".",
						","
					);
					cellJumlah.text(strText);
					strText = parseFloat(kontrak);
					strText = accounting.formatNumber(
						strText,
						strText.countDecimals(),
						".",
						","
					);
					cellKontrak.text(strText);
					if (kontrak > 0) {
						form.form("set value", "jumlah", kontrak);
					} else {
						form.form("set value", "jumlah", "hhh");
					}
					break;
				default:
					break;
			}
			break;
		case "realisasi":
			switch (params.tbl) {
				case "vol_realisasi":
					let vol_kontrak = 0;
					let pagu = 0;
					let jumlah_kontrak = 0;
					let realisasi_vol = 0;
					let realisasi_jumlah = 0;
					let vol = 0;
					let jumlah = 0;
					$(`[name="form_modal"] table tbody tr`).each(function () {
						let element = $(this);
						let vol_kontrakTemp = Number(
							accounting.unformat(
								element.find(`[klm="vol_kontrak"]`).text(),
								","
							)
						);
						vol_kontrak += vol_kontrakTemp;
						pagu += Number(
							accounting.unformat(element.find(`[klm="pagu"]`).text(), ",")
						);
						let jumlah_kontrakTemp = Number(
							accounting.unformat(
								element.find(`[klm="jumlah_kontrak"]`).text(),
								","
							)
						);
						jumlah_kontrak += jumlah_kontrakTemp;
						let realisasi_volTemp = Number(
							accounting.unformat(
								element.find(`[klm="realisasi_vol"]`).text(),
								","
							)
						);
						realisasi_vol += realisasi_volTemp;
						let realisasi_jumlahTemp = Number(
							accounting.unformat(
								element.find(`[klm="realisasi_jumlah"]`).text(),
								","
							)
						);
						realisasi_jumlah += realisasi_jumlahTemp;
						vol_temp = Number(
							accounting.unformat(element.find(`[klm="vol"] div`).text(), ",")
						);
						if (vol_temp + realisasi_volTemp > vol_kontrakTemp) {
							vol_temp = vol_kontrakTemp - realisasi_volTemp;
							let strText = parseFloat(vol_temp);
							strText = accounting.formatNumber(
								strText,
								strText.countDecimals(),
								".",
								","
							);
							$(evt).text(strText);
						}
						vol += vol_temp;
						jumlah_temp = Number(
							accounting.unformat(
								element.find(`[klm="jumlah"] div`).text(),
								","
							)
						);
						if (jumlah_temp + realisasi_jumlahTemp > jumlah_kontrakTemp) {
							jumlah_temp = jumlah_kontrakTemp - realisasi_jumlahTemp;
							strText = parseFloat(jumlah_temp);
							strText = accounting.formatNumber(
								strText,
								strText.countDecimals(),
								".",
								","
							);
							// return false;
							$(evt).text(strText);
						}
						jumlah += jumlah_temp;
					});
					if (jumlah > 0) {
						form.form("set value", "jumlah_realisasi", jumlah);
					} else {
						form.form("set value", "jumlah_realisasi", "hhh");
					}

					let cellIsi = {
						vol_kontrak: vol_kontrak,
						pagu: pagu,
						jumlah_kontrak: jumlah_kontrak,
						realisasi_vol: realisasi_vol,
						realisasi_jumlah: realisasi_jumlah,
						vol: vol,
						jumlah: jumlah,
					};
					Object.entries(cellIsi).forEach((entry) => {
						const [key, value] = entry;
						strText = parseFloat(value);
						strText = accounting.formatNumber(
							strText,
							strText.countDecimals(),
							".",
							","
						);
						form.find(`table tfoot tr td[name="${key}"]`).text(strText);
					});
					break;
			}
		default:
			break;
	}
	//=======================================================================
	//============fungsi tampilkan pdf dari php tcpdf =======================
	//== $data['pdf'] = base64_encode( $pdf->Output( 'file_name', 'S' ) ); ==
	//=======================================================================
	function printPreviewBase64(base64String) {
		// Buat URL data untuk PDF
		var pdfDataUri = "data:application/pdf;base64," + base64String;
		// Buat sebuah elemen <a> untuk membuka PDF dalam tab baru
		var link = document.createElement("a");
		link.href = pdfDataUri;
		link.target = "_blank";
		// Klik pada link secara otomatis
		link.click();
	}
}
function rumus(evt) {
	return /[0-9]|\=|\+|\-|\/|\*|\%|\[|\]|\,/.test(
		String.fromCharCode(evt.which)
	);
}
// onkeypress="return ketikUbah(event);"
function ketikUbah(evt) {
	let MyForm = $(`form[name="form_flyout"]`);
	let hargaSat = accounting.unformat(
		MyForm.form("get value", "harga_satuan"),
		","
	);
	let vol_1 = accounting.unformat(MyForm.form("get value", "vol_1"), ",");
	let vol_2 = accounting.unformat(MyForm.form("get value", "vol_2"), ",");
	let vol_3 = accounting.unformat(MyForm.form("get value", "vol_3"), ",");
	let vol_4 = accounting.unformat(MyForm.form("get value", "vol_4"), ",");
	let vol_1_kali = vol_1 <= 0 ? 1 : vol_1;
	let vol_2_kali = vol_2 <= 0 ? 1 : vol_2;
	let vol_3_kali = vol_3 <= 0 ? 1 : vol_3;
	let vol_4_kali = vol_4 <= 0 ? 1 : vol_4;
	let perkal = vol_1_kali * vol_2_kali * vol_3_kali * vol_4_kali * hargaSat;
	strText = parseFloat(perkal);
	strText = accounting.formatNumber(perkal, perkal.countDecimals(), ".", ",");
	MyForm.form("set value", "jumlah", strText);
	MyForm.form(
		"set value",
		"volume",
		vol_1_kali * vol_2_kali * vol_3_kali * vol_4_kali
	);
}
//========================
//======== ON ERROR ======
//========================
function imgsrc(e) {
	let ini = $(e);
	let jenis = ini.attr("jns");
	const myArray = [
		"img/notfoundwi.jpg",
		"img/notfound.jpg",
		"img/notfoundall.jpg",
	];
	const randomElement = myArray[Math.floor(Math.random() * myArray.length)];
	switch (jenis) {
		case "img":
			ini.attr("src", randomElement);
			break;
		default:
			ini.attr("src", randomElement);
			break;
	}
}
