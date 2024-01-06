const halAwal = halamanDefault;
const enc = new Encryption();
$(document).ready(function () {
	"use strict";
	//remove session storage
	sessionStorage.clear()
	var halaman = 1;
	//sidebar toggle
	$('.ui.sidebar').sidebar({
		context: $('.bottom.segment')
	}).sidebar('attach events', '.menu .item.nabiila');

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

	$(".ui.accordion").accordion(
		{
			exclusive: false
		}
	);



	$(".bottom.attached.segment .ui.sticky").sticky({
		context: ".bottom.segment",
		pushing: true,
	});
	//menu lain


	//logout
	$("body").on('click', "[name='log_out']", function () {
		setTimeout(function () {
			window.location.href = "script/logout";
		}, 400);
	});

	//=====================
	//======DATA TAB=======@audit-ok data tab
	//=====================
	$("body").on('click', 'a[data-tab]', function (e) {
		e.preventDefault();
		let arrayDasboard = {
			tab_home: ["home icon", "DASHBOARD", "seSendok", ''],
			tab_rentra: ["clipboard list icon", "RENSTRA", "Rencana Startegi", ''],
			tab_renja: ["clipboard list icon", "RENJA", "Rencana Kerja dan Anggaran Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD.", ''],
			tab_dpa: ["clipboard list icon", "DPA", "Dokumen Pelaksanaan Anggaran", ''],
			tab_dpa_perubahan: ["clipboard list icon", "DPA", "Dokumen Pelaksanaan Perubahan Anggaran", ''],
			tab_kontrak: ["clipboard list icon", "KONTRAK", "perjanjian", ''],
			tab_input_real: ["clipboard list icon", "Realisasi", "Input Realisasi"],
			tab_spj: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_lap: ["clipboard list icon", "Renja", "Rencana Kerja"],
			bidang_urusan: ["clipboard list icon", "BIDANG URUSAN", "Klasifikasi dan kodefikasi", "Bidang Urusan: Sejumlah tugas atau tanggung jawab khusus pemerintah daerah yang diKlasifikasikan menjadi urusan pemerintahan konkuren terbagi menjadi 32 (tiga puluh dua) bidang urusan, 2 (dua) Urusan Pendukung, 7(tujuh) Urusan Penunjang, 1 (satu) urusan Pengawasan, 3 (Tiga) Urusan Kewilayahan, serta Urusan Kekhususan dan keistimewaan"],
			prog: ["clipboard list icon", "PROGRAM", "Klasifikasi dan kodefikasi", "Klasifikasi dan kodefikasi program disusun berdasarkan pembagian sub urusan dan kegiatan disusun berdasarkan pembagian kewenangan yang diatur dalam Lampiran Undang-Undang Nomor 23 Tahun 2014.Hal ini dilakukan untuk memastikan ruang lingkup penyelenggaraan pemerintahan daerah dilakukan sesuai dengan keenangannya, sehingga mendukung pelaksanaan asas prinsip akuntabilitas, efisiensi, eksternalitas serta kepentingan strategis nasional"],
			keg: ["clipboard list icon", "KEGIATAN", "Klasifikasi dan kodefikasi", "Klasifikasi dan kodefikasi kegiatan"],
			sub_keg: ["clipboard list icon", "SUB KEGIATAN", "Klasifikasi dan kodefikasi", "Klasifikasi dan kodefikasi sub kegiatan disusun berdasarkan aktivitas atau layanan dalam penyelesaian permasalahan daerah sesuai kewenangannya."],
			akun_belanja: ["clipboard list icon", "AKUN", "Klasifikasi dan kodefikasi", "Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek."],
			sumber_dana: ["clipboard list icon", "Sumber Dana", "Klasifikasi dan kodefikasi", "Klasifikasi, Kodefikasi, dan Nomenklatur Sumber Pendanaan ditujukan untuk memberikan informasi atas sumber dana berdasarkan tujuan penggunaan dana dari setiap pelaksanaan urusan pemerintahan daerah yang dijabarkan berdasarkan program, kegiatan dan sub kegiatan dalam rangka pengendalian masing-masing kelompok dana meliputi pengawasan/control, akuntabilitas/accountability dan transparansi/transparency (CAT)."],
			peraturan: ["clipboard list icon", "Peraturan", "Aturan Yang digunakan", "ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan."],
			rekanan: ["clipboard list icon", "REKANAN", "Klasifikasi dan kodefikasi", "Penyedia barang dan/atau jasa"],
			tab_hargasat: ['clipboard list icon', 'SSH', "Standar Harga Satuan", 'PP 12 Tahun 2019<ol class="ui list"><li class="item">Belanja Daerah sebagaimana dimaksud dalam Pasal 49 ayat (5) berpedoman pada standar harga satuan regional, analisis standar belanja, dan/atau standar teknis sesuai dengan ketentuan peraturan perurndang-undangan.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (1) dan ayat (2) ditetapkan dengan Peraturan Presiden.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (3) digunakan sebagai pedoman dalam menyusun standar harga satuan pada masing-masing Daerah.</li></ol>'],

			tab_reset: ["red table icon", "Reset Tabel", "menghapus seluruh data tabel"],
			tab_template: ["download icon", "Template", "Ungguh Contoh Template AHSP"],
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
		let jenis = ini.attr('data-tab');
		$(`#cari_data`).attr('name', jenis);
		let iconDashboard = "home icon";
		let headerDashboard = ini.text();
		let pDashboard = "seSendok";
		let cryptos = false;
		let tbl = ini.attr('tbl');
		if (jenis in arrayDasboard) {
			iconDashboard = arrayDasboard[jenis][0];
			headerDashboard = arrayDasboard[jenis][1];
			pDashboard = arrayDasboard[jenis][2];
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
		$(`div[data-tab=${jenis}]`).attr('tbl', tbl);
		switch (jenis) {
			case 'tab_hargasat':
				dasboardheader.text(tbl.toUpperCase());
				$('div[name="kethargasat"]').html(arrayDasboard[jenis][3]);
				jalankanAjax = true;
				switch (tbl) {
					case 'ssh':
						break;
					case 'hspk':
						break;
					case 'asb':
						break;
					case 'sbu':
						break;
					default:
						break;
				};
				break;
			case 'tab_ref':

				// dasboardheader.text(tbl.toUpperCase());
				$('div[name="ketref"]').html(arrayDasboard[tbl][3]);
				jalankanAjax = true;
				switch (tbl) {
					case 'bidang_urusan':
						break;
					case 'prog':
						break;
					case 'keg':
						break;
					case 'sub_keg':
						break;
					case 'akun_belanja':
						break;
					case 'sumber_dana':
						break;
					case 'peraturan':
						break;
					case 'rekanan':
						break;
					default:
						break;
				};
				break;
			case 'xxxx':
				break;
			default:
				break;
		}
		let data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
		};
		if (jalankanAjax) {
			loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				loaderHide();
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
		}
	})
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
		let dataHtmlku = {};
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
			switch (jenis) {
				//EDIT DATA ROWS
				case 'edit':
					data.id_row = id_row;
					dataHtmlku.icon = "edit icon";
					dataHtmlku.header = "Edit data";
					jalankanAjax = true;
				//TAMBAH ROWS DATA
				case 'add':
					switch (tbl) {
						case 'bidang_urusan':
						case 'prog':
						case 'keg':
						case 'sub_keg':
						case 'akun_belanja':
						case 'sumber_dana':
							dataHtmlku.konten =
								buatElemenHtml("fieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="cek_kode" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Type Dok",
									atribut: 'name="type"',
									kelas: "lainnya selection",
									dataArray: [
										["file", "Peraturan Perundang-undangan Pusat"],
										["peraturan", "Peraturan Kementerian / Lembaga"],
										["tutorial", "Peraturan Perundang-undangan Daerah"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["proyek", "File Kegiatan"],
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Judul",
									atribut: 'name="judul" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nomor",
									atribut: 'name="nomor" placeholder="Nomor..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk",
									atribut: 'name="bentuk" placeholder="Pereaturan Menteri Dalam Negeri..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk SIngkat",
									atribut: 'name="bentuk_singkat" placeholder="permendagri..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Tempat Penetapan",
									atribut: 'name="bentuk_singkat" placeholder="permendagri..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Penetapan",
									atribut:
										'placeholder="Input Tanggal.." name="tgl_penetapan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Pengundangan",
									atribut:
										'placeholder="Input Tanggal.." name="tgl_pengundangan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Type Data",
									atribut: 'name="type"',
									kelas: "lainnya selection",
									dataArray: [
										["file", "File"],
										["peraturan", "Peraturan"],
										["tutorial", "Tutorial"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["proyek", "File Kegiatan"],
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal",
									atribut:
										'placeholder="Input Tanggal.." name="tanggal" readonly',
									kelas: "date",
								}) +

								buatElemenHtml("fieldDropdown", {
									label: "Status Data",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["umum", "Umum"],
										["rahasia", "Rahasia"],
										["proyek", "Dokumen kegiatan"]
									],
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								});
							if (tbl === 'edit') {
								data.id_row = id_row;
								jalankanAjax = true;
								formIni.attr("id_row", id_row);
								dataHtmlku.icon = "edit icon";
								dataHtmlku.header = "Edit Data/Peraturan";
							}
							break;
						case 'peraturan':
							dataHtmlku.konten =
								buatElemenHtml("fieldTextAction", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode (jangan ganda)..."',
									txtLabel: "cek",
									atributLabel: `name="get_data"  jns="cek_kode" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Type Dok",
									atribut: 'name="type"',
									kelas: "lainnya selection",
									dataArray: [
										["file", "Peraturan Perundang-undangan Pusat"],
										["peraturan", "Peraturan Kementerian / Lembaga"],
										["tutorial", "Peraturan Perundang-undangan Daerah"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["proyek", "File Kegiatan"],
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Judul",
									atribut: 'name="judul" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nomor",
									atribut: 'name="nomor" placeholder="Nomor..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk",
									atribut: 'name="bentuk" placeholder="Pereaturan Menteri Dalam Negeri..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Bentuk SIngkat",
									atribut: 'name="bentuk_singkat" placeholder="permendagri..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Tempat Penetapan",
									atribut: 'name="bentuk_singkat" placeholder="permendagri..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Penetapan",
									atribut:
										'placeholder="Input Tanggal.." name="tgl_penetapan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Pengundangan",
									atribut:
										'placeholder="Input Tanggal.." name="tgl_pengundangan" readonly',
									kelas: "date",
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Type Data",
									atribut: 'name="type"',
									kelas: "lainnya selection",
									dataArray: [
										["file", "File"],
										["peraturan", "Peraturan"],
										["tutorial", "Tutorial"],
										["pengumuman", "Pengumuman"],
										["artikel", "Artikel"],
										["lain", "Data Lainnya"],
										["proyek", "File Kegiatan"],
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal",
									atribut:
										'placeholder="Input Tanggal.." name="tanggal" readonly',
									kelas: "date",
								}) +

								buatElemenHtml("fieldDropdown", {
									label: "Status Data",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["umum", "Umum"],
										["rahasia", "Rahasia"],
										["proyek", "Dokumen kegiatan"]
									],
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
								});
							if (tbl === 'edit') {
								data.id_row = id_row;
								jalankanAjax = true;
								formIni.attr("id_row", id_row);
								dataHtmlku.icon = "edit icon";
								dataHtmlku.header = "Edit Data/Peraturan";
							}
							break;
						case 'value1':

							break;
						default:

							break;
					};

					break;
				case 'import':
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
			};
			//atur form
			htmlForm = `${dataHtmlku.konten}<div class="ui icon success message"><i class="check icon"></i><div class="content"><div class="header">Form sudah lengkap</div><p>anda bisa submit form</p></div></div><div class="ui error message"></div>`;
			iconFlyout.attr("class", "").addClass(dataHtmlku.icon);
			headerFlyout.text(dataHtmlku.header);
			formIni.html(htmlForm);
			addRulesForm(formIni);
			let calendarDate = new CalendarConstructor(".ui.calendar.date");
			calendarDate.runCalendar();
			let calendarYear = new CalendarConstructor(".ui.calendar.year");
			calendarYear.Type('year');
			calendarYear.runCalendar();
			$('div[name="jml_header"]').dropdown("set selected", 1);
			$('.ui.accordion').accordion();
			formIni.find('.ui.dropdown.lainnya').dropdown();
			$("[rms]").mathbiila();
		};
		//


		addRulesForm(formIni);
		//JALANKAN AJAX
		if (jalankanAjax) {
			loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {

				}
				var hasKey = result.hasOwnProperty("error");
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
				} else {
					loaderHide();
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
	$('.ui.flyout').flyout({
		closable: false,
		context: $('.bottom.segment.pushable')
	}).flyout('attach events', '[name="flyout"]');
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {//@audit-ok DropdownConstructor
		constructor(element) {
			this.element = $(element);//element;
		}
		satuan(get) {
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
						jenis: "satuan",
						tbl: "get",
						cari: function (value) {
							return get;
							//console.log($('.satuan.ui.dropdown').dropdown('get query'));
						},
						rows: "all",
						halaman: 1,
					}
				},

				filterRemoteData: true,
				saveRemoteData: false
			});
		}
		setSelected(val) {
			this.element.dropdown('set selected', val);
		}
		setValue(val) {
			this.element.dropdown('set value', val);
		}
		restore() {
			this.element.dropdown('restore defaults');
		}
		users(get) {
			get = this.element.dropdown("get query");
			this.element.dropdown({//$(".satuan.ui.dropdown").dropdown({
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
						results: 'results'
					},
					filterRemoteData: true,
				},
				className: {
					//item: "item vertical",
				},
				saveRemoteData: false
			});
		}
		setVal(val) {
			//this.element.dropdown('preventChangeTrigger', true);
			this.element.dropdown('set selected', val);
		}
	}
	//===================================
	//=========== class calendar ========
	//===================================
	class CalendarConstructor {//@audit-ok CalendarConstructor
		constructor(element) {//constructor(element,tglAwal=new Date(),tglAkhir=new Date()) {
			this.element = $(element);
		}
		typeOnChange = ''
		disableDate = []
		enableDate = [];
		type = 'date'
		typeDate = "dddd D MMMM Y";
		disabledDaysOfWeek = []
		tanggal = new Date();
		minDate = null;//new Date(tanggal.getFullYear(), tanggal.getMonth(), tanggal.getDate());
		maxDate = null;//new Date(tglAwal.getFullYear(), tglAwal.getMonth(), tglAwal.getDate());
		startCalendar = null;
		endCalendar = null;
		//panggil methode ini untuk mengganti type dan format
		Type(typeDay) {
			let format = "dddd D MMMM Y"
			switch (typeDay) {
				case 'date':
					format = "dddd D MMMM Y"
					this.type = 'date'
					break;
				case 'datetime':
					format = "dddd D MMMM Y h:mm A"
					this.type = 'datetime'
					break;
				case 'time':
					this.type = 'time'
					break;
				case 'year':
					this.type = 'year'
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
					time: 'H:mm',
					cellTime: 'H:mm',
				},
				text: {
					dayNames: ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",],
					months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember",],
				}, onChange: function (date, text, mode) {
					//let tanggalAwal = new Date(tglAwal.getFullYear(), tglAwal.getMonth(), tglAwal.getDate());
					switch (typeOnChange) {
						case 'tab="lap-harian"':
							var tanggal = new Date(date)
							//console.log(tanggal);
							tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
							//console.log(tanggal);
							$('a[data-tab="lap-harian"][tbl="get_list"]').trigger('click');
							break;
						case 'value2':

							break;
						case 'value3':
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
	function runAjax(url, type, formData, dataType, contentType, processData, callback, cryptos = false) {
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
				Object.keys(formData).forEach(key => {
					// console.log(key)
					// console.log(formData[key])
					const value = formData[key].toString();
					// console.log(value.toString().length)
					if (value.toString().length > 0) {
						formData[key] = enc.encrypt(value, halAwal)
					}
					formData.cry = cryptos;
					//formData.set(key, enc.encrypt(value, halAwal));
				});
			}
			const end = new Date().getTime();
			const diff = end - start;
			const seconds = diff / 1000;//Math.floor(diff / 1000 % 60);
			// console.log(`selisih ecrypt (s) : ${seconds}`);
		}

		var params = new ajaxParams(url, type, formData, dataType, contentType, processData, callback);
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
				elemen =
					`<div class="field"><label>${labelData}</label><div class="ui fluid ${posisi} icon input"><input type="text" ${atributData}><i class="${icon} icon"></i></div></div>`;
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
		return elemen;
	}
	//========================================
	//=================get ip addres==========
	//========================================
	function getIpAddress() {
		return $.getJSON('http://ip-api.com/json', function (ipData) {
			document.write(ipData.query)
		});
	}
	/**
	 * Dark mode
	 *
	 * Adds .inverted to all components.
	 */
	function ready() {
		const darkModePreference = window.matchMedia("(prefers-color-scheme: dark)");
		if (darkModePreference.matches) {
			const usedComponents = ["container", "grid", "button", "calendar", "card", "checkbox", "dimmer", "divider", "dropdown", "form", "header", "icon", "image", "input", "label", "list", "loader", "menu", "message", "modal", "placeholder", "popup", "progress", "segment", "sidebar", "statistics", "step", "tab", "table", "text", "toast", "api", "transition"];

			usedComponents.forEach(usedComponent => {
				let uiComponents = document.querySelectorAll('.ui.' + usedComponent);

				uiComponents.forEach(component => {
					if (component.classList.contains('inverted')) {
						component.classList.remove('inverted');
					} else {
						component.classList.add('inverted');
					}
				});
			});

		}
	}
	// harus darkModePreference = true
	// document.addEventListener("DOMContentLoaded", ready);
	//============================================================
	// . FUNGSI ADD RULE UNTUK SEMUA INPUT DAN TEXT AREA
	//============================================================
	function addRulesForm(formku) {
		var attrName = formku.find($("input[name],textarea[name]"));
		var i = 0;
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
	}
	//
});
