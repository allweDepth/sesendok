const halAwal = halamanDefault;
const enc = new Encryption();
$(document).ready(function () {
	"use strict";
	//remove session storage
	sessionStorage.clear();
	var halaman = 1;
	//sidebar toggle
	$(".ui.sidebar")
		.sidebar({
			context: $(".bottom.pushable"),
		})
		.sidebar("attach events", ".menu .item.nabiila")
		.sidebar("setting", "transition", "push");

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
						$('a[tbl="' + tbl + '"]').first().trigger('click');
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
		let arrayDasboard = {
			tab_home: ["home icon", "DASHBOARD", "seSendok", ""],
			tab_rentra: ["clipboard list icon", "RENSTRA", "Rencana Startegi", ""],
			tab_renja: [
				"clipboard list icon",
				"RENJA",
				"Rencana Kerja dan Anggaran Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD.",
				"",
			],
			tab_dpa: [
				"clipboard list icon",
				"DPA",
				"Dokumen Pelaksanaan Anggaran",
				"",
			],
			tab_dpa_perubahan: [
				"clipboard list icon",
				"DPA",
				"Dokumen Pelaksanaan Perubahan Anggaran",
				"",
			],
			tab_kontrak: ["clipboard list icon", "KONTRAK", "perjanjian", ""],
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
			mapping: [
				"clipboard list icon",
				"Mapping",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			wilayah: [
				"clipboard list icon",
				"LOKASI/WILAYAH",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			organisasi: [
				"clipboard list icon",
				"SKPD",
				"Organisasi Perangkat Daerag",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek.",
			],
			sumber_dana: [
				"clipboard list icon",
				"Sumber Dana",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi, Kodefikasi, dan Nomenklatur Sumber Pendanaan ditujukan untuk memberikan informasi atas sumber dana berdasarkan tujuan penggunaan dana dari setiap pelaksanaan urusan pemerintahan daerah yang dijabarkan berdasarkan program, kegiatan dan sub kegiatan dalam rangka pengendalian masing-masing kelompok dana meliputi pengawasan/control, akuntabilitas/accountability dan transparansi/transparency (CAT).",
			],
			peraturan: [
				"clipboard list icon",
				"Peraturan",
				"Aturan Yang digunakan",
				"ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan.",
			],
			rekanan: [
				"clipboard list icon",
				"REKANAN",
				"Klasifikasi dan kodefikasi",
				"Penyedia barang dan/atau jasa",
			],
			satuan: [
				"clipboard list icon",
				"SATUAN",
				"Klasifikasi dan kodefikasi",
				"Penyedia barang dan/atau jasa",
			],
			tab_hargasat: [
				"clipboard list icon",
				"SSH",
				"Standar Harga Satuan",
				'PP 12 Tahun 2019<ol class="ui list"><li class="item">Belanja Daerah sebagaimana dimaksud dalam Pasal 49 ayat (5) berpedoman pada standar harga satuan regional, analisis standar belanja, dan/atau standar teknis sesuai dengan ketentuan peraturan perurndang-undangan.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (1) dan ayat (2) ditetapkan dengan Peraturan Presiden.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (3) digunakan sebagai pedoman dalam menyusun standar harga satuan pada masing-masing Daerah.</li></ol>',
			],

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
		tok($(this));
		let url = "script/get_data";
		const dasboard = $(".message.dashboard");
		let ini = $(this);
		let tab = ini.attr("data-tab");
		let jenis = "get_tbl"; //get data
		let tbl = ini.attr("tbl");
		$(`#cari_data`).attr("tbl", tbl).attr("dt-tab", tab);
		let divTab = $(`div[data-tab="${ini.attr("data-tab")}"]`);
		if (ini.attr('name') === 'page') {
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
			divTab = ini.closest('div[data-tab]');
		}

		let iconDashboard = "home icon";
		let headerDashboard = ini.text();
		let pDashboard = "seSendok";
		let cryptos = false;

		if (tab in arrayDasboard) {
			iconDashboard = arrayDasboard[tab][0];
			headerDashboard = arrayDasboard[tab][1];
			pDashboard = arrayDasboard[tab][2];
		} else if (tbl in arrayDasboard) {
			iconDashboard = arrayDasboard[tbl][0];
			headerDashboard = arrayDasboard[tbl][1];
			pDashboard = arrayDasboard[tbl][2];
		}
		let jalankanAjax = false;
		//node dashboard
		dasboard.find($("i")).attr("class", "").addClass(iconDashboard);
		let dasboardheader = dasboard.find($("div.header"));
		dasboardheader.text(headerDashboard);
		dasboard.find($("div.pDashboard")).html(pDashboard);
		$(`div[data-tab=${tab}]`).attr("tbl", tbl);
		switch (tab) {
			case "tab_hargasat":
				dasboardheader.text(tbl.toUpperCase());
				$('div[name="kethargasat"]').html(arrayDasboard[tab][3]);
				jalankanAjax = true;
				switch (tbl) {
					case "ssh":
					case "hspk":
					case "asb":
					case "sbu":
						divTab.find('[tbl]').attr('tbl', tbl);
						break;
					default:
						break;
				}
				break;
			case "tab_ref":
				// dasboardheader.text(tbl.toUpperCase());
				$('div[name="ketref"]').html(arrayDasboard[tbl][3]);
				jalankanAjax = true;
				divTab.find('[tbl]').attr('tbl', tbl);
				switch (tbl) {
					case "bidang_urusan":
						break;
					case "prog":
						break;
					case "keg":
						break;
					case "sub_keg":
						break;
					case "akun_belanja":
						break;
					case "sumber_dana":
						break;
					case "rekanan":
						break;
					default:
						break;
				}
				break;
			case "tab_peraturan":
				jalankanAjax = true;
				break;
			case "pengaturan":
				jenis = "get_pengaturan";
				jalankanAjax = true;
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
			case "organisasi":
			case "wilayah":
			case "sbu":
			case "ssh":
			case "asb":
			case "hspk":
			case "satuan":
			case "rekanan":
				jalankanAjax = true;
				break;
			case "xxxx":
				break;
			default:
				break;
		}
		let data = {
			cari: cari(tab),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
		};
		if (jalankanAjax) {
			loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					let hasKey = result.hasOwnProperty("error");
					if (hasKey) {
						loaderHide();
						switch (jenis) {
							case "get_tbl":
								console.log(divTab);
								const elmTable = divTab.find("table");
								const elmtbody = elmTable.find(`tbody`);
								const elmtfoot = elmTable.find(`tfoot`);
								elmtbody.html(result.data.tbody);
								elmtfoot.html(result.data.tfoot);
								if (result?.data?.thead) {
									const elmthead = elmTable.find(`thead`);
									elmthead.html(result.data.thead);
								}
								switch (tbl) {
									case "peraturan":
										break;
									case "xxx":
										break;
									default:
										break;
								}
								break;
							case "get_data":
								switch (tbl) {
									case "peraturan":
										break;
									case "xxx":
										break;
									default:
										break;
								}
								break;
							case "get_pengaturan":
								switch (tbl) {
									case "pengaturan":
										// result.data?.tahun;
										let formPengaturan = $('form[name="form_pengaturan"]');
										$('form[name="form_pengaturan"] .ui.dropdown[name!="tahun"]').dropdown({
											values: result?.data?.peraturan,
										})

										let attrName = formPengaturan.find('input[name],textarea[name]');
										for (const iterator of attrName) {
											let attrElm = $(iterator).attr('name');
											if (attrElm !== 'file') {
												formPengaturan.form("set value", attrElm, result?.data?.row_tahun[attrElm]);
											}
										}
										addRulesForm(formPengaturan);
										break;
									case "xxx":
										break;
									default:
										break;
								}
								break;
							default:
								break;
						}
						hasKey = result.error.hasOwnProperty("message");
						let error_code = result.error.code;
						let kelasToast = "success";
						let iconToast = "check circle icon";
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
						loaderHide();
					}
				}
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
	$("body").on("click", '[name="get_data"],[name="flyout"]', function (e) {
		e.preventDefault();
		let ini = $(this);
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
		let dataHtmlku = { konten: '' };
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
			// console.log(`jenis : ${jenis}`);
			// console.log(`tbl : ${tbl}`);

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
				case "add":
					switch (tbl) {
						case 'rekanan':
							dataHtmlku.konten =
								buatElemenHtml("fieldTextAction", {
									label: "Nama Perusahaan",
									atribut: 'name="nama_perusahaan" placeholder="Nama Perusahaan..."',
									atributLabel: `name="get_data" jns="${jenis}" tbl="cek_kode"`,
								}) +
								buatElemenHtml("fieldText", {
									label: "Alamat",
									atribut: 'name="alamat" placeholder="Alamat..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "NPWP",
									atribut: 'name="npwp" placeholder="NPWP..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nama Pemilik/Direktur",
									atribut: 'name="direktur" placeholder="Direktur..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "No. KTP Direktur",
									atribut: 'name="no_ktp" placeholder="KTP Direktur..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Alamat Pemilik",
									atribut: 'name="alamat_dir" placeholder="Alamat Pemilik..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "No. Akta Pendirian",
									atribut: 'name="no_akta_pendirian" placeholder="No. Akta pendirian..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Notaris Pendirian",
									kelas: "date",
									atribut:
										'placeholder="Tanggal.." name="tgl_akta_pendirian" readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Alamat Notaris",
									atribut: 'name="lokasi_notaris_pendirian" placeholder="Alamat Notaris pendirian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Notaris",
									atribut: 'name="nama_notaris_pendirian" placeholder="Nama Notaris pendirian..."',
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
									atribut: 'non_data name="file"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								buatElemenHtml("accordionField", {
									label: "Akta Notaris Perubahan",
									content: buatElemenHtml("text", {
										atribut: `name="nomor[1]" placeholder="Nomor" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tanggal[1]" placeholder="Tanggal" non_data`, kelas: "date"//atribut: `data-name='["satu", "tanggal"]' placeholder="Tanggal" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="alamat_notaris[1]" placeholder="Alamat" non_data`,
									}) + buatElemenHtml("text", {
										atribut: `name="notaris[1]" placeholder="Notaris" non_data`,
									}),
									atribut: 'name="notaris_perubahan"',
								}) +
								buatElemenHtml("accordionField", {
									label: "Pelaksana",
									content: buatElemenHtml("text", {
										atribut: `name="pelaksana[nama][1]" placeholder="Nama" non_data`,//gunakan object $(elm).data('name') jquery
									}) + buatElemenHtml("text", {
										atribut: `name="pelaksana[jabatan][1]" placeholder="Jabatan" non_data`,
									}),
									atribut: 'name="data_lain"',
								});
							if (tbl === 'input') {
								dataHtmlku.icon = "plus icon";
								dataHtmlku.header = "Tambah data";
							}

							break;
						case "hspk":
						case "ssh":
						case "sbu":
						case "asb":
							dataHtmlku.konten =
								buatElemenHtml("fieldDropdown", {//@audit
									label: "Kode Kelompok Barang/Jasa",
									atribut: 'name="kd_aset"',
									kelas: "lainnya selection",
									dataArray: [
										["peraturan_undang_undang_pusat", "Peraturan Perundang-undangan Pusat"],
										["peraturan_menteri_lembaga", "Peraturan Kementerian / Lembaga"],
										["peraturan_daerah", "Peraturan Perundang-undangan Daerah"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["kegiatan", "File Kegiatan"]
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian Kelompok Barang/Jasa",
									atribut: 'name="uraian_kel" rows="4" placeholder="Kelompok Barang/Jasa..." disabled',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian Barang/Jasa",
									atribut: 'name="uraian_barang" rows="4" placeholder="Uraian Barang/Jasa..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Spesifikasi",
									atribut: 'name="spesifikasi" rows="4" placeholder="Spesifikasi..."',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan"',
									kelas: "lainnya selection",
									dataArray: [
										["peraturan_undang_undang_pusat", "Peraturan Perundang-undangan Pusat"],
										["peraturan_menteri_lembaga", "Peraturan Kementerian / Lembaga"],
										["peraturan_daerah", "Peraturan Perundang-undangan Daerah"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["kegiatan", "File Kegiatan"]
									],
								}) +
								buatElemenHtml("fieldText", {
									label: "Harga Satuan",
									atribut:
										'name="harga_satuan" placeholder="harga satuan..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "TKDN",
									atribut:
										'name="tkdn" placeholder="tkdn..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Kode Rekening",
									atribut: 'name="kd_rek_akun_asli" rows="4" placeholder="Kode Rekening..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
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
								buatElemenHtml("multiFieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="get_data" tbl="${tbl}"`,
									dataArray: ['name="urusan"', 'name="bidang"', 'name="prog"', 'name="keg"', 'name="sub_keg"']
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Nomenklatur Urusan",
									atribut: 'name="nomenklatur_urusan" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Kinerja",
									atribut: 'name="kinerja" rows="4" placeholder="kinerja..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Indikator",
									atribut: 'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Satuan",
									atribut:
										'name="satuan" placeholder="satuan..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							
							break;
						case "aset":
						case "akun_belanja":
							dataHtmlku.konten =
								buatElemenHtml("multiFieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="get_data" tbl="${tbl}"`,
									dataArray: ['name="akun"', 'name="kelompok"', 'name="jenis"', 'name="objek"', 'name="rincian_objek"', 'name="sub_rincian_objek"']
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							
							break;
						case "sumber_dana":
							dataHtmlku.konten =
								buatElemenHtml("multiFieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="get_data" tbl="${tbl}"`,
									dataArray: ['name="sumber_dana"', 'name="kelompok"', 'name="jenis"', 'name="objek"', 'name="rincian_objek"', 'name="sub_rincian_objek"']
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
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
								buatElemenHtml("fieldDropdown", {
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
								buatElemenHtml("fieldTextarea", {
									label: "Judul",
									atribut: 'name="judul" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldTextAction", {
									label: "Nomor",
									atribut: 'name="nomor" placeholder="Nomor Peraturan..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk",
									atribut:
										'name="bentuk" placeholder="Bentuk(Peraturan Menteri Dalam Negeri...)"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk Singkat",
									atribut:
										'name="bentuk_singkat" placeholder="bentuk singkat(permendagri)..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Tempat Penetapan",
									atribut: 'name="t4_penetapan" placeholder="tempat penetapan..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Penetapan",
									atribut:
										'placeholder="Input tanggal penetapan.." name="tgl_penetapan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Pengundangan",
									atribut:
										'placeholder="Input Tanggal pengundangan.." name="tgl_pengundangan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Status Data",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["umum", "Umum"],
										["rahasia", "Rahasia/Pribadi"],
										["kegiatan", "Dokumen kegiatan"],
									],
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							
							break;
						case "mapping":
							dataHtmlku.konten +=
								buatElemenHtml("fieldDropdown", {
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
								buatElemenHtml("fieldTextarea", {
									label: "Judul",
									atribut: 'name="judul" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldTextAction", {
									label: "Nomor",
									atribut: 'name="nomor" placeholder="Nomor Peraturan..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk",
									atribut:
										'name="bentuk" placeholder="Bentuk(Peraturan Menteri Dalam Negeri...)"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk Singkat",
									atribut:
										'name="bentuk_singkat" placeholder="bentuk singkat(permendagri)..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Tempat Penetapan",
									atribut: 'name="t4_penetapan" placeholder="tempat penetapan..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Penetapan",
									atribut:
										'placeholder="Input tanggal penetapan.." name="tgl_penetapan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Pengundangan",
									atribut:
										'placeholder="Input Tanggal pengundangan.." name="tgl_pengundangan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Status Data",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["umum", "Umum"],
										["rahasia", "Rahasia/Pribadi"],
										["kegiatan", "Dokumen kegiatan"],
									],
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							
							break;
						case "organisasi":
							dataHtmlku.konten +=
								buatElemenHtml("fieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Nomor Peraturan..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Nama SKPD",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Alamat",
									atribut:
										'name="alamat" placeholder="Kode..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Kepala OPD",
									atribut:
										'name="nama_kepala" placeholder="Kode..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nip. Kepala OPD",
									atribut:
										'name="nip_kepala" placeholder="Kode..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							
							break;
						case "wilayah":
							dataHtmlku.konten +=
								buatElemenHtml("fieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Nomor Peraturan..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Nama Wilayah",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Type Dok",
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
								buatElemenHtml("fieldText", {
									label: "Jumlah Kecamatan",
									atribut:
										'name="jml_kec" placeholder="Jumlah Kecamatan..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Jumlah Keluarahan",
									atribut:
										'name="jml_kel" placeholder="Jumlah Keluarahan..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Jumlah Desa",
									atribut:
										'name="jml_desa" placeholder="Jumlah Desa..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Luas Wilayah (km2)",
									atribut:
										'name="luas" placeholder="Luas Wilayah (km2)..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Jumlah Penduduk (jiwa)",
									atribut:
										'name="penduduk" placeholder="Jumlah penduduk (jiwa)..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							
							break;
						case "satuan":
							dataHtmlku.konten +=
								buatElemenHtml("fieldText", {
									label: "Kode",
									atribut: 'name="value" rows="4" placeholder="Kode..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian HTML",
									atribut: 'name="item" rows="4"',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Sebutan Lain",
									atribut: 'name="sebutan_lain" rows="4"',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
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
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//file
					dataHtmlku.konten = buatElemenHtml("fieldFileInput2", {
						label: "Pilih File Dokumen",
						placeholderData: "Pilih File (*.xlsx)...",
						accept: ".xlsx",
					}); //non_data(artinya tidak di dicek form)
					//dropdown
					dataHtmlku.konten += buatElemenHtml("fieldDropdown", {
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
					break;
				default:
					break;
			}
			//atur form
			htmlForm = `${dataHtmlku.konten}<div class="ui icon success message"><i class="check icon"></i><div class="content"><div class="header">Form sudah lengkap</div><p>anda bisa submit form</p></div></div><div class="ui error message"></div>`;
			iconFlyout.attr("class", "").addClass(dataHtmlku.icon);
			headerFlyout.text(dataHtmlku.header);

			formIni.html(htmlForm);
			addRulesForm(formIni);
			let calendarDate = new CalendarConstructor(".ui.calendar.date");
			calendarDate.runCalendar();
			let calendarYear = new CalendarConstructor(".ui.calendar.year");
			calendarYear.Type("year");
			calendarYear.runCalendar();
			$('div[name="jml_header"]').dropdown("set selected", 1);
			$(".ui.accordion").accordion();
			formIni.find(".ui.dropdown.lainnya").dropdown();
			$("[rms]").mathbiila();
		} else if (attrName === "get_data") {
			switch (jenis) {
				case "get_data":
					data.text = ini.closest(".input").find('input[name="kode"]').val();
					if (typeof data.text === "undefined") {
						data.text = ini.closest(".input").find("input[name]").val();
					}
					console.log(data);

					if (data.text.length > 1) {
						jalankanAjax = true;
					} else {
						showToast("Input sebelum cek data", {
							class: "warning",
							icon: "check circle icon",
						});
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
			loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					let hasKey = result.hasOwnProperty("error");
					if (hasKey) {
						loaderHide();
						hasKey = result.error.hasOwnProperty("message");
						let error_code = result.error.code;
						let kelasToast = "success";
						let iconToast = "check circle icon";
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
						switch (attrName) {
							case 'flyout':
								switch (jenis) {
									case 'edit':
										switch (tbl) {
											case "hspk":
											case "ssh":
											case "sbu":
											case "asb":
												let dropdownAset = result?.data?.aset;
												let dropdownSatuan = result?.data?.satuan;
												if (dropdownAset.length) {

												}
												break;
											default:
												break;
										}
										//isi form dengan data
										let attrName = formIni.find('input[name],textarea[name]');
										for (const iterator of attrName) {
											// console.log(attrName);
											let attrElm = $(iterator).attr('name');
											if (attrElm === 'file') {
												formIni.form("set value", 'dum_file', result.data?.users[attrElm]);
											} else {
												formIni.form("set value", attrElm, result.data?.users[attrElm]);
											}
											// console.log(attrElm);
										}
										addRulesForm(formIni);
										switch (tbl) {
											case 'peraturan':
												break;
											default:
												break;
										}
										break;

									default:
										break;
								}
								$(".ui.flyout").flyout("toggle");
								break;
							case 'value1':

								break;
							default:

								break;
						};

					} else {
						loaderHide();
					}
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		}
		if (attrName === "flyout" && jalankanAjax === false) {
			// $(".ui.flyout").flyout("toggle");
		}
	});

	//====================================
	//=========== flyout =================
	//====================================
	// $(".ui.flyout").flyout({
	// 	//non aktifkan toggle jika tekan dimmer
	// 	// selector: { pusher: '.flyout' },
	// 	// className: { pushable: '.bottom.pushable' },
	// 	closable: false,
	// 	onShow: function () {
	// 		// loaderHide();
	// 		// console.log('onShow flyout');
	// 	},
	// 	onHide: function (choice) {
	// 		//console.log(choice);
	// 		// let form = $(".ui.flyout form");
	// 		// form.form('clear');
	// 		// removeRulesForm(form);
	// 		// //inisialize kembali agar tidak error di console
	// 		// var reinitForm = new FormGlobal(form);
	// 		// reinitForm.run();
	// 	},
	// 	onApprove: function (elemen) {
	// 		$(elemen).closest('div.flyout').find('form').form('submit');
	// 		return false;
	// 	},
	// 	context: $('.bottom.segment'),
	// }).flyout('attach events', '[name="flyout"]');
	$(".ui.flyout")
		.flyout({
			closable: false,
			context: $(".bottom.pushable"),
			onShow: function () {
				// loaderHide();
				// console.log('onShow flyout');
			},
			onHide: function (choice) {
				// 		//console.log(choice);
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
	//============================
	//===========DEL ROW==========
	//============================
	$("body").on("click", '[name="del_row"]', function (e) {
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
		var url = "script/del_data";
		var jalankanAjax = false;
		var contentModal = [
			'<i class="trash alternate icon"></i>anda yakin akan hapus data ini?',
			"menghapus data tidak dapat di batalkan...!",
		];
		var data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
			id_row: id_row,
		};
		switch (jenis) {
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
				if (id_row && jenis !== 'direct') {
					jalankanAjax = true;
				}
				break;
			default:
				break;
		}
		modal_notif_hapus(contentModal[0], contentModal[1]);
		$(".ui.hapus.modal")
			.modal({
				allowMultiple: true,
				//observeChanges: true,
				closable: false,
				onApprove: function () {
					if (jalankanAjax) {
						suksesAjax["ajaxku"] = function (result) {
							if (result.error.code === 4) {
								var obj = ini.closest("tr");
								if (obj.length <= 0) {
									obj = ini.closest(".item");
									if (obj.length <= 0) {
										obj = ini.closest(".comment");
									}
								}
								//console.log(obj);
								obj.find('input, textarea').addClass("transparent");
								obj.css("background-color", "#EF617C");
								obj.fadeOut(600, function () {
									obj.remove();
								});
								switch (jenis) {
									case "z":
										break;
									case "x":
										switch (tbl) {
											case "y":
												break;
											case "z":
												break;
											default:
												break;
										}
										break;
									default:
										showToast(result.error.message, {
											class: "success",
											icon: "check circle icon",
										});
										break;
								}
							} else {
								showToast(result.error.message, {
									class: "warning",
									icon: "check circle icon",
								});
							}
						};
						runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
					} else {
						switch (jenis) {
							case "direct":
								let form = ini.closest("form");
								let direct = ini.attr("direct"); //jika ada atribut direct hapus direct
								let obj = ini.closest("tr");
								if (direct) {
									obj = ini.closest('[direct="del"]');
								}
								obj.find('input, textarea').addClass("transparent");
								obj.css("background-color", "#EF617C");
								obj.fadeOut(500, function () {
									obj.remove();
									if (typeof form !== "undefined") {
										//console.log(form);
										removeRulesForm(form);
										var reinitForm = new FormGlobal(form);
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
		let dok = $(this).attr('dok');
		let jenis = $(this).attr('jns');
		let tbl = $(this).attr('tbl');
		if (jenis !== '') {
			$('#form_ungguh_dok input[name="jenis"]').val(jenis);
			$('#form_ungguh_dok input[name="tbl"]').val(tbl);
			$('#form_ungguh_dok input[name="dok"]').val(dok);
			$('#form_ungguh_dok').submit();
		} else {
			modal_notif('<i class="info icon"></i>Pilih Dokumen', 'pilih dokumen perencanaan yang ingin di ungguh');
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
		//console.log('masuk dum file')
		var himp = $(this).closest(".action");
		//console.log(himp.find('input[type="file"]'))
		var inputFile = himp.find('input[nama="file"]');
		inputFile.val("");
		//inputFile.click();
		inputFile.trigger("click");
		//button.on("click", function () { buttonClick(button, popup); });
	});
	$("body").on("change", 'input[type="file"]', function (e) {
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
				//console.log(jsonData);
				//console.log(jsonData[0]);
				//console.log(JSON.stringify(jsonData));
				jsonData.forEach((value, key) => {
					//console.log(key + " " + value)
					//console.log(value.length)
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
							elmForm2 = `<tr>${nmrHtml}<td klm="uraian"><div contenteditable>${value[1]
								}</div></td><td klm="kode"><div contenteditable>${value[2]
								}</div></td><td klm="satuan"><div contenteditable>${value[3]
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
								)}</div></td>${jumlahHtml}<td klm="rumus"><div contenteditable>${value[6]
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
		//console.log(e)
		var himp = ini.closest(".action");
		var nama_file = e.target.files[0].name;
		himp.find('input[name="dum_file"]').val(nama_file);
		var file = $(this)[0].files[0].name.toLowerCase(); //this.files[0];
		var arrayAccept = $(this).attr("accept");
		if (arrayAccept !== undefined && arrayAccept.length > 0) {
			arrayAccept = arrayAccept.split(",");
		} else {
			arrayAccept = [".xlsx"];
		}
		//console.log(arrayAccept);
		var fileExp = file.split(".");
		//console.log(fileExp);
		var extFile = "." + fileExp[fileExp.length - 1];
		//console.log(extFile);
		//console.log(arrayAccept.indexOf(extFile));
		if (arrayAccept.indexOf(extFile) < 0) {
			//if (!file.endsWith('.xlsx')) {
			modal_notif(
				'<i class="green file excel icon"></i>Pilih File Excel',
				"Pilih file extension " + arrayAccept
			);
			ini.val("");
			return false;
		} else {
		}
	});
	$("body").on("click", 'button[name="del_file"]', function (e) {
		e.preventDefault();
		var himp = $(this).closest(".action");
		himp.find("input").val("");
	});
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		//@audit-ok DropdownConstructor
		constructor(element) {
			this.element = $(element); //element;
		}
		returnList(jenis = "list_dropdown", tbl = "satuan") {
			get = this.element.dropdown("get query");
			this.element.dropdown({
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					method: "POST",
					url: "script/get_data",
					//throttle: 500,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: {
						jenis: jenis,
						tbl: tbl,
						cari: function (value) {
							return get;
							//console.log($('.satuan.ui.dropdown').dropdown('get query'));
						},
						rows: "all",
						halaman: 1,
					}, fields: {
						results: "results",
					},
					filterRemoteData: true,
				},

				filterRemoteData: true,
				saveRemoteData: false,
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
		users(get) {
			get = this.element.dropdown("get query");
			this.element.dropdown({
				//$(".satuan.ui.dropdown").dropdown({
				apiSettings: {
					cache: false,
					// this url just returns a list of tags (with API response expected above)
					method: "POST",
					url: "script/get_data",
					//throttle: 1000,//delay perintah
					// passed via POST
					data: {
						jenis: "user",
						tbl: "get_users_list",
						cari: function (value) {
							return get;
							//console.log($('.satuan.ui.dropdown').dropdown('get query'));
						},
						rows: "all",
						halaman: 1,
					},
					fields: {
						results: "results",
					},
					filterRemoteData: true,
				},
				className: {
					//item: "item vertical",
				},
				saveRemoteData: false,
			});
		}
		setVal(val) {
			//this.element.dropdown('preventChangeTrigger', true);
			this.element.dropdown("set selected", val);
		}
		onChange(val) {
			//this.element.dropdown('preventChangeTrigger', true);
			this.element.dropdown({
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();

				},
				saveRemoteData: true,
				filterRemoteData: true
			});
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
					this.type = "time";
					break;
				case "year":
					this.type = "year";
					break;
				default:
					break;
			}
			this.typeDate = format;
		}
		runCalendar() {
			let typeOnChange = this.typeOnChange;
			this.element.calendar({
				minDate: this.minDate,
				maxDate: this.maxDate,
				disableDate: this.disableDate,
				disabledDaysOfWeek: this.disabledDaysOfWeek,
				type: this.type,
				startCalendar: this.startCalendar,
				endCalendar: this.endCalendar,
				formatter: {
					date: this.typeDate,
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
							//console.log(tanggal);
							tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1
								}-${tanggal.getDate()}`; //local time
							//console.log(tanggal);
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
	// let dropdown = new DropdownConstructor(".satuan.ui.dropdown");
	// dropdown.satuan();

	//=======================================
	//===============FORM GLOBAL=============
	//=======================================
	class FormGlobal {//@audit-ok Form
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
					console.log("masuk form global");
					let jalankanAjax = false;
					let ini = $(this);
					let tbl = ini.attr("tbl");
					let dataType = "Json";
					let jenis = ini.attr("jns");
					let nama_form = ini.attr("name");
					let cryptos = false;
					//loaderShow();
					let formData = new FormData(this);
					formData.append("jenis", jenis);
					formData.append("tbl", tbl);
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
					// formData.forEach((value, key) => {
					// 	console.log(key + " " + value)
					// });
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
					switch (nama_form) {
						// =================
						// UNTUK FORM MODAL
						// =================
						case "form_modal":
							break;
						// =================
						// UNTUK FORM FLYOUT
						// =================
						case "form_flyout":
							switch (tbl) {
								case "peraturan":
									switch (jenis) {
										case "add":
										case "edit":
										//formData.append("id_row", tbl);
										case "input":
											let property = ini.find(".ui.calendar.date");
											for (const key of property) {
												let nameAttr = $(key).find("[name]").attr("name");
												let tanggal = $(key).calendar("get date");
												if (tanggal) {
													tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1
														}-${tanggal.getDate()}`; //local time
													formData.set(nameAttr, tanggal);
												}
											}
											formData.has("disable") === false
												? formData.append("disable", 'off')
												: formData.set("disable", 'on'); // Returns false
											jalankanAjax = true;

											break;
										default:
											break;
									}
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
								? formData.append("disable", 'off')
								: formData.set("disable", 'on'); // Returns false
							jalankanAjax = true;
							break;
						// =================
						// UNTUK MODAL 2====
						// =================
						case "form_modal_kedua":
							var atributFormAwal = ini.attr("nama_modal_awal");
							var mdlTujuan = $('div[name="' + atributFormAwal + '"]');
							var formTujuan = mdlTujuan.find("form");
							var dataForm = ini.form("get values");
							var tbodyFormAwal = formTujuan.find("table tbody");
							var indexTr = ini.attr("indextr");
							var trTable = tbodyFormAwal.children();
							console.log(mdlTujuan);
							var tdEdit = trTable.eq(indexTr).find("td");
							//console.log(dataForm);
							//console.log(dataForm['kode']);
							Object.keys(tdEdit).forEach((key) => {
								var element = $(tdEdit[key]);
								var nama_kolom = element.attr("klm");
								if (typeof dataForm[nama_kolom] !== "undefined") {
									var elemenku = element.children();
									if (elemenku.length > 0) {
										element.children().text(dataForm[nama_kolom]); // jika ada div contenteditable
									} else {
										element.text(dataForm[nama_kolom]);
									}
								}
							});

							break;
						// =================
						// UNTUK PROFIL====
						// =================
						case "profil":
							//[name="ket"]
							formData.set("ket", $('textarea[name="ket"]').val());
							jalankanAjax = true;
							break;
						default:
							break;
					}
					if (jalankanAjax) {
						const start = new Date().getTime();
						if (cryptos) {
							formData.forEach((value, key) => {
								//console.log(key + " " + value)
								formData.set(key, enc.encrypt(value, halAwal));
							});
							formData.set("cry", cryptos);
						}
						const end = new Date().getTime();
						const diff = end - start;
						const seconds = diff / 1000; //Math.floor(diff / 1000 % 60);
						// console.log(`selisih ecrypt form (s) : ${seconds}`);
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
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
								//console.log(nama_form);
								let jenisTrigger = ""; //jenisTrigger = jenis;
								switch (nama_form) {
									// ===========================
									// UNTUK FORM form_chat
									// ===========================
									case "form_chat":
										switch (
										jenis
										) {
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
									// =================
									// UNTUK FORM LOKASI
									// =================
									case "lokasi":
										switch (jenis) {
											case "lokasi":
											case "lokasi-lokasi":
											case "lokasi-marker":
											case "lokasi-polyline":
											case "lokasi-polygon":
												break;
											default:
												break;
										}
										break;
									// ==================
									// =UNTUK FORM MODAL=
									// ==================
									case "form_modal":
										switch (jenis) {
											case "analisa_alat_custom":
												jenisTrigger = "analisa_alat";
											case "analisa_ck":
											case "analisa_bm":
											case "analisa_sda":
											case "analisa_quarry":
											case "analisa_alat":
												jenisTrigger = tbl;
												break;
											case "analisa_alat":
												break;
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
										switch (tbl) {
											case "aset":
											case "mapping":
											case "sub_keg":
											case "organisasi":
											case "sumber_dana":
											case "akun_belanja":
											case "wilayah":
											case "peraturan":
												switch (jenis) {
													case "import":
													case "edit":
													case "add":
														jenisTrigger = tbl;
														break;
													default:
														break;
												}
												break;
											case "rekanan":
												switch (jenis) {
													case "import":
													case "edit":
													case "input":
														jenisTrigger = tbl;
														break;
													default:
														break;
												}
												break;
											case "sbu": // add row rab dari analisa
												switch (tbl) {
													case "ck":
													case "bm":
													case "sda":
														var hasKey = result.data.hasOwnProperty("tbody");
														console.log(hasKey);
														if (hasKey) {
															var rows = result.data.tbody;
															if (tabel.find("tbody tr:last").length > 0) {
																tabel.find("tbody tr:last").after(rows);
															} else {
																tabel.find("tbody").html(rows);
															}
														}
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
										console.log(elmRow);
										warna.forEach((value, key) => {
											elmRow.removeClass(value);
											$("table").removeClass("inverted");
										});
										if (warna_tbl !== "non") {
											elmRow.addClass(warna_tbl);
											$("table").addClass("inverted");
										}
										break;
									default:
										break;
								}
								console.log(jenisTrigger);

								if (jenisTrigger.length > 0) {
									$(`a[data-tab][tbl="${jenisTrigger}"]`).trigger("click");
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
					}
					switch (nama_form) {
						case "form_modal":
							$(".ui.modal.mdl_general").modal("hide");
							break;
						case "form_flyout":
							//$('.ui.flyout').flyout('hide');
							break;
						case "form_modal_kedua":
							$('div[name="mdl_kedua"]').modal("hide");
							break;
						default:
							break;
					}
				},
				onDirty: function (e) {
					//return true
				},
				onFailure: function (e) {
					loaderHide();
					return false;
				},
			});
		}
	}
	let InitializeForm = new FormGlobal(".ui.form");
	InitializeForm.run();
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
				//console.log(suksesAjax);
				//console.log('sukses ajax : '+params.callback);
				var callback = suksesAjax[params.callback](data);
			})
			.fail(function (jqXHR, textStatus, err) {
				loaderHide();
				//console.log(textStatus);
				//console.log(jqXHR.responseText.split(','));
				//console.log(JSON.parse(jqXHR.responseText));
				try {
					var resultObject = JSON.parse(jqXHR.responseText, reviver);
					//console.log(jqXHR.responseText);
					//console.log(jqXHR.responseText.split('"'));
					//var resultObject = JSON.parse(jqXHR.responseText);
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
	}
	function loaderHide() {
		$(".demo.page.dimmer:first").dimmer("hide");
	}
	//====MEMBUAT ELEMEN HTML================
	//====contohdataElemen={atribut:'name="tambah" jns="harga_satuan" tbl="input"'}
	//================================
	function buatElemenHtml(namaElemen = "", dataElemen = {}) {
		let acceptData = "atribut" in dataElemen ? dataElemen.accept : ".pdf";
		let content = "content" in dataElemen ? dataElemen.content : "";
		let atributData = "atribut" in dataElemen ? dataElemen.atribut : "";
		let atributData2 = "atribut2" in dataElemen ? dataElemen.atribut2 : "";
		let atributLabel =
			"atributLabel" in dataElemen ? dataElemen.atributLabel : "";
		let kelasData = "kelas" in dataElemen ? dataElemen.kelas : "";
		let labelData = "label" in dataElemen ? dataElemen.label : "";
		let textDrpdown =
			"textDrpdown" in dataElemen ? dataElemen.textDrpdown : "Pilih...";
		let placeholderData =
			"placeholderData" in dataElemen ? dataElemen.placeholderData : "";
		let elemen1Data = "elemen1" in dataElemen ? dataElemen.elemen1 : "";
		let iconData = "icon" in dataElemen ? dataElemen.icon : "download icon";
		let icon = "icon" in dataElemen ? dataElemen.icon : "calendar";
		let posisi = "posisi" in dataElemen ? dataElemen.posisi : "left";
		let colorData = "color" in dataElemen ? dataElemen.color : "positive";
		let valueData = "value" in dataElemen ? dataElemen.value : "";
		let iconDataSeach = "icon" in dataElemen ? dataElemen.icon : "search icon";
		let txtLabelData = "txtLabel" in dataElemen ? dataElemen.txtLabel : "@";
		let dataArray = "dataArray" in dataElemen ? dataElemen.dataArray : []; //contoh untuk dropdown
		let dataArray2 = "dataArray2" in dataElemen ? dataElemen.dataArray2 : [[]]; //contoh buat dropdown yang ada deskripsi
		let jenisListDropdown =
			"jenisListDropdown" in dataElemen ? dataElemen.jenisListDropdown : "@"; //jenis dropdown[Selection,Search Selection,Clearable Selection,Multiple Selection,Multiple Search Selection,Description,Image,Actionable ,Columnar Menu]
		// let file
		let accept = "accept" in dataElemen ? dataElemen.accept : ".xlsx";
		switch (namaElemen) {
			case "text":
				elemen = `<div class="field"><input type="text" ${atributData}></div>`;
				break;
			case "accordionField":
				elemen = `<div class="ui accordion field" ${atributData}><div class="title"><i class="icon dropdown"></i>${labelData} </div><div class="content field">${content} </div></div>`;
				break;
			case "fieldTextAccordion":
				elemen = `<div><label class="visible" style="display: block !important;">${labelData}</label><input ${atributData} type="text" class="visible" style="display: inline-block !important;"></div>`;
				break;
			case "segment":
				elemen = `<div class="ui segments"><div class="ui segment"></div><div class="ui segment"></div></div>`;
				break;
			case "messageLink":
				elemen = `<div class="ui icon message ${colorData}"><i class="${iconData}"></i><div class="content"><div class="header">${labelData} </div><a ${atributData}  target="_blank">${valueData}</a></div></div>`;
				break;
			case "dividerHeader":
				elemen = `<h3 class="ui dividing header">${labelData}</h3>`;
				break;
			case "dividerClearing":
				var elemen = '<div class="ui clearing divider"></div>';
				break;
			case "dividerFitted":
				var elemen = '<div class="ui fitted divider"></div>';
				break;
			case "dividerIcon":
				var elemen = '<div class="ui horizontal divider"></div>';
				break;
			case "dividerHidden":
				elemen = `<div class="ui hidden divider"></div>`;
				break;
			case "fieldSearch":
				var elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui fluid category scrolling search ' +
					kelasData +
					'"><div class="ui icon fluid input"><input class="prompt" type="text" ' +
					atributData +
					' placeholder="Search..."><i class="search icon"></i></div><div class="results"></div></div></div>';
				break;
			case "calendar":
				elemen = `<div class="field"><div class="ui calendar ${kelasData}"><div class="ui fluid input left icon"><i class="calendar icon"></i><input type="text" ${atributData}></div></div></div>`;
				break;
			case "fieldCalendar":
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui calendar ' +
					kelasData +
					'" ' +
					atributData2 +
					'><div class="ui input left icon"><i class="calendar icon"></i><input type="text" ' +
					atributData +
					"></div></div></div>";
				break;
			case "fieldAndLabel":
				elemen =
					'<div class="field"><label>' +
					labelData +
					"</label> " +
					elemen1Data +
					" </div>";
				break;
			case "fieldText":
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui ' +
					kelasData +
					' input"><input ' +
					placeholderData +
					' type="text" ' +
					atributData +
					"></div></div>";
				break;
			case "multiFieldTextAction":
				let inputElm = '';
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					inputElm += `<input type="text" ${rowsData}>`;
				}
				elemen =
					`<div class="field"><label>
					${labelData}
					</label><div class="ui action fluid input multi">
					${inputElm}
					<button class="ui teal button" ${atributLabel}>${txtLabelData}</button> </div></div>`;
				break;
			case "fieldTextAction":
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui action input"><input type="text" ' +
					atributData +
					'><button class="ui teal button" ' +
					atributLabel +
					">" +
					txtLabelData +
					"</button></div></div>";
				break;
			case "fieldTextLabelKanan":
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui fluid right labeled input"><input type="text" ' +
					placeholderData +
					" " +
					atributData +
					'><div class="ui basic label" ' +
					atributLabel +
					">" +
					txtLabelData +
					"</div></div></div>";
				break;
			case "fieldTextIcon":
				elemen = `<div class="field"><label>${labelData}</label><div class="ui fluid ${posisi} icon input"><input type="text" ${atributData}><i class="${icon} icon"></i></div></div>`;
				break;
			case "textIcon":
				elemen =
					'<div class="ui icon fluid input"><input type="text" ' +
					placeholderData +
					" " +
					atributData +
					'><i class="' +
					iconDataSeach +
					'"></i></div>';
				break;
			case "fieldText2":
				elemen =
					'<div class="field"><label>' +
					labelData +
					"</label><input " +
					placeholderData +
					' name="username" type="text" ' +
					atributData +
					"></div>";
				break;
			case "fieldFileInput":
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui file input"><input type="file" ' +
					atributData +
					"></div></div>";
				break;
			case "fieldFileInput2":
				//atributData file hanya placeholder
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui fluid right action left icon input"><i class="folder open yellow icon"></i><input type="text" placeholder="' +
					placeholderData +
					'" readonly="" name="dum_file" ' +
					atributData +
					'><input hidden="" type="file" nama="file" name="file" accept="' +
					accept +
					'" non_data><button class="ui red icon button" name="del_file"><i class="erase icon"></i></button></div></div>';
				break;
			case "fieldTextarea":
				elemen =
					'<div class="field"><label>' +
					labelData +
					"</label><textarea " +
					atributData +
					"></textarea></div>";
				break;
			case "fieldCheckbox":
				elemen = `<div class="inline field"><div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden" ${atributData}><label>${labelData}</label></div></div>`;
				break;
			case "fielToggleCheckbox":
				elemen =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui toggle checkbox"><input type="checkbox" ' +
					atributData +
					"><label>" +
					txtLabelData +
					"</label></div></div>";
				break;
			case "fieldDropdown":
				var elemen1 =
					'<div class="field"><label>' +
					labelData +
					'</label><div class="ui dropdown ' +
					kelasData +
					'" ' +
					atributData +
					'><input type="hidden" ' +
					atributData +
					'><i class="dropdown icon"></i><div class="default text">' +
					textDrpdown +
					'</div><div class="menu">';
				///Memisahkan array
				let elemen2 = "";
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					let dataValue = rowsData[0];
					if (rowsData.length === 1) {
						let txt = dataValue;
						elemen2 +=
							'<div class="item" data-value="' +
							dataValue +
							'">' +
							txt +
							"</div>";
					} else if (rowsData.length === 2) {
						let txt = rowsData[1];
						elemen2 +=
							'<div class="item" data-value="' +
							dataValue +
							'">' +
							txt +
							"</div>";
					} else if (rowsData.length === 3) {
						//mempunyai deskripsi

						let txt = rowsData[1];
						deskripsi = rowsData[2];
						elemen2 +=
							'<div class="item" data-value="' +
							dataValue +
							'"><span class="description">' +
							deskripsi +
							'</span><span class="text">' +
							txt +
							"</span></div>";
					}
				}
				/*
						for (let x in dataArray) {
							rowsData = dataArray[x]
							dataValue = rowsData.dataValue;
							txt = rowsData.text;
						  //console.log(rowsData.dataValue);
						  //console.log(rowsData.text);
							elemen2 += '<div class="item" data-value="' + dataValue + '">' + txt + '</div>';
						}
						*/
				var elemen3 = "</div></div></div>";
				elemen = elemen1 + elemen2 + elemen3;
				break;
			default:
				break;
		}
		if (darkmodeEnabled === true) {
			elemen = elemen.replace(/class="ui/g, `class="ui inverted`);
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
		$("body").find(".ui").addClass("inverted");
		// add custom inverted class to body
		$("body").addClass("inverted");

		// simple toggle icon change
		$(`a[name="change_themes"] > i`).removeClass("moon");
		$(`a[name="change_themes"] > i`).addClass("sun");
		console.log($(`a[name="change_themes"] > i`));

		return;
	}

	function toggleLightMode() {
		// remove fomantic's inverted from all ui elements
		$("body").find(".ui").removeClass("inverted");
		// remove custom inverted class to body
		$("body").removeClass("inverted");

		// change button icon
		$(`a[name="change_themes"] > i`).removeClass("sun");
		$(`a[name="change_themes"] > i`).addClass("moon");
		console.log($(`a[name="change_themes"] > i`));
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
		var attrName = formku.find($("input[name],textarea[name]"));
		var i = 0;
		$(formku).form("set auto check");
		for (i = 0; i < attrName.length; i++) {
			var atribut = $(attrName[i]).attr("name");
			var lbl = $(attrName[i]).attr("placeholder");
			if (lbl === undefined) {
				lbl = $(attrName[i]).closest(".field").find("label").text();
				if (lbl === undefined || lbl === "") {
					lbl = $(attrName[i]).closest(".field").find("div.sub.header").text();
				}
				if (lbl === undefined || lbl === "") {
					lbl = atribut.replaceAll(/_/g, " ");
				}
			}
			var non_data = $(attrName[i]).attr("non_data");
			if (typeof non_data === "undefined") {
				formku.form("add rule", atribut, {
					rules: [
						{
							type: "empty",
							prompt: "Lengkapi Data " + lbl,
						},
					],
				});
			} else {
				//console.log($(attrName[i]).is)
				//if ($(attrName[i]).is)
				//$(attrName[i]).addClass('enabled').attr('readonly').val(0);
			}
		}
	}
	//
	function removeRulesForm(formku) {
		var attrName = formku.find($("input[name],textarea[name]"));
		var i = 0;
		//console.log(formku)
		for (i = 0; i < attrName.length; i++) {
			var atribut = $(attrName[i]).attr("name");
			var lbl = $(attrName[i]).attr("placeholder");
			if (lbl === undefined) {
				lbl = $(attrName[i]).closest(".field").find("label").text();
				if (lbl === undefined || lbl === "") {
					lbl = $(attrName[i]).closest(".field").find("div.sub.header").text();
				}
				if (lbl === undefined || lbl === "") {
					lbl = atribut.replaceAll(/_/g, " ");
				}
			}
			var non_data = $(attrName[i]).attr("non_data");
			if (typeof non_data === "undefined") {
				if (atribut) {
					//formku.form("remove rule", atribut);
					formku.form("remove field", atribut);
					//console.log('atribut remove rule: ' + atribut)
				}
			} else {
				//console.log($(attrName[i]).is)
				//if ($(attrName[i]).is)
				//$(attrName[i]).addClass('enabled').attr('readonly').val(0);
			}
		}
		$(formku).form("set auto check");
	}
	//
	function showToast(message, settings) {
		//settings = {}
		/*{
			position: 'top center',
			icon:'info circle',
			showProgress: 'bottom'
			classActions: 'left vertical attached',//Vertical actions can also be displayed as button groups using vertical attached
			actions:	[{
			text: 'Yes, really',
			class: 'green',
			click: function() {
				$.toast({message:'You clicked "yes", toast closes by default'});
				}
			},{
				text: 'Maybe later',
				class: 'red',
				click: function() {
				$.toast({message:'You clicked "maybe", toast closes by default'});
				}
			}]
		}*/
		settings.title = settings.title === undefined ? "info !" : settings.title;
		settings.position =
			settings.position === undefined ? "top right" : settings.position;
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
});
