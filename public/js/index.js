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
	let handler = {
		activate: function () {
			if (!$(this).hasClass('dropdown browse')) {
				$(this)
					.addClass('active')
					.closest('.ui.menu')
					.find('.item')
					.not($(this))
					.removeClass('active')
					;
			}
		}
	}
	$(".menu .item.inayah").on('click', handler.activate);
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
		tok($(this));
		let url = "script/get_data";
		const dasboard = $(".message.dashboard");
		let ini = $(this);
		let tab = ini.attr("data-tab");
		let jenis_this = ini.attr("jns");
		let jenis = "get_tbl"; //get data
		let tbl = ini.attr("tbl");
		let Text_ssh_sbu = (tbl) ? tbl.toUpperCase() : '';
		let harga_ssh_asb = [
			"clipboard list icon",
			`${Text_ssh_sbu}`,
			"Standar Harga Satuan",
			'PP 12 Tahun 2019<ol class="ui list"><li class="item">Belanja Daerah sebagaimana dimaksud dalam Pasal 49 ayat (5) berpedoman pada standar harga satuan regional, analisis standar belanja, dan/atau standar teknis sesuai dengan ketentuan peraturan perurndang-undangan.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (1) dan ayat (2) ditetapkan dengan Peraturan Presiden.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (3) digunakan sebagai pedoman dalam menyusun standar harga satuan pada masing-masing Daerah.</li></ol>',
		];
		let tab_renstra = ["clipboard list icon", "RENSTRA", "Rencana Startegi", ""];
		let tab_renja = [
			"clipboard list icon",
			"RENJA",
			"Rencana Kerja dan Anggaran SKPD",
			"Rencana Kerja dan Anggaran Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD.",
		]
		if (tab === 'tab_renja') {
			if (ini.attr('anggaran') === 'dpa') {
				tab_renja = [
					"clipboard list icon",
					"DPA",
					"Daftar Pelaksanaan Anggaran (DPA)",
					"Daftar Pelaksanaan Anggaran (DPA) Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat DPA SKPD adalah dokumen yang memuat pendapatan dan belanja setiap SKPD yang digunakan sebagai dasar pelaksanaan oleh pengguna anggaran.",
				]
			}
		}

		let arrayDasboard = {
			tab_home: ["home icon", "DASHBOARD", "seSendok", ""],
			tab_renstra: tab_renstra,
			renstra: tab_renstra,
			tab_renja: tab_renja,
			sub_keg_renja: tab_renja,
			tujuan_sasaran_renstra: [
				"clipboard list icon",
				"Tujuan Sasaran Renstra",
				"Klasifikasi dan kodefikasi",
				"Klasifikasi dan kodefikasi program disusun berdasarkan pembagian sub urusan dan kegiatan disusun berdasarkan pembagian kewenangan yang diatur dalam Lampiran Undang-Undang Nomor 23 Tahun 2014.Hal ini dilakukan untuk memastikan ruang lingkup penyelenggaraan pemerintahan daerah dilakukan sesuai dengan keenangannya, sehingga mendukung pelaksanaan asas prinsip akuntabilitas, efisiensi, eksternalitas serta kepentingan strategis nasional",
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
			ssh: harga_ssh_asb, asb: harga_ssh_asb, sbu: harga_ssh_asb, hspk: harga_ssh_asb,
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
		if (ini.attr('name') === 'page') {
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
			divTab = ini.closest('div[data-tab]');
		}
		$(`#cari_data`).attr("tbl", tbl).attr("dt-tab", tab);
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
		let data = {};
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
			case 'get_pengaturan':
				switch (tbl) {
					case 'pengaturan':
						let formPakai = $(`[name="form_pengaturan"]`);
						let sumdatetimeStartcal = formPakai.find(`.ui.calendar.datetime.startcal`);
						let dynamic = [];
						let jeniku = { awal_renstra: 'akhir_renstra', awal_renja: 'akhir_renja', awal_dpa: 'akhir_dpa', awal_renja_p: 'akhir_renja_p', awal_dppa: 'akhir_dppa' }
						Object.keys(sumdatetimeStartcal).forEach((key) => {
							let element = $(sumdatetimeStartcal[key]);
							let namaAttr = element.attr("name");
							if (namaAttr !== undefined) {
								let bill = jeniku[namaAttr];
								dynamic[namaAttr] = new CalendarConstructor(`div[name="${namaAttr}"],div[name="${bill}"]`);
								dynamic[namaAttr].startCalendar = $(`div[name="${namaAttr}"]`);
								dynamic[namaAttr].endCalendar = $(`div[name="${bill}"]`);
								dynamic[namaAttr].Type("datetime");
								dynamic[namaAttr].runCalendar();
							}
						});
						break;
					default:
						break;
				}
				jenis = "get_pengaturan";
				jalankanAjax = true;
				break;
			case "renstra":
			case "tab_renstra":
				if (tbl) {
					jalankanAjax = true;
					divTab.find('button[jns]').attr('tbl', tbl)
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
			case "organisasi":
			case "wilayah":
			case "sbu":
			case "ssh":
			case "asb":
			case "hspk":
			case "satuan":
			case "rekanan":
			case "tujuan_sasaran_renstra":
			case "tujuan_sasaran":
			case "atur":
				jalankanAjax = true;
				break;
			case "renja":
			case "dpa":
			case "renja_p":
			case "dppa":
			case "sub_keg_renja":
			case "sub_keg_dpa":
			case "tab_renja":
				switch (jenis_this) {
					case 'rincian_pokok':

						break;
					case 'rincian_perubahan':

						break;
					default:

						break;
				};

				let anggaranAttr = ini.attr('anggaran');
				let itemDivDataTab = $(`div[data-tab="tab_renja"] .menu a.item`);
				switch (anggaranAttr) {
					case 'renja':
						itemDivDataTab.eq(0).attr('tbl', 'sub_keg_renja');
						itemDivDataTab.eq(0).text('Sub Kegiatan');
						itemDivDataTab.eq(1).attr('tb', 'renja').attr('id_sub_keg', 'renja');
						itemDivDataTab.eq(1).text('Renja');
						itemDivDataTab.eq(2).attr('tb', 'renja_p');
						itemDivDataTab.eq(2).text('Renja Perubahan');
						break;
					case 'dpa':
						itemDivDataTab.eq(0).attr('tbl', 'sub_keg_dpa');
						itemDivDataTab.eq(0).text('Sub Kegiatan');
						itemDivDataTab.eq(1).attr('tb', 'dpa');
						itemDivDataTab.eq(1).text('D P A');
						itemDivDataTab.eq(2).attr('tb', 'dppa');
						itemDivDataTab.eq(2).text('DPPA');
						break;
				};

				// $(".message.goyang.keterangan").find('p').text(arrayDasboard[tab][3]);
				if (tbl) {
					jalankanAjax = true;
					divTab.find('button[jns]').attr('tbl', tbl)
				}
				switch (tbl) {
					case "sub_keg_renja":
					case "sub_keg_dpa":
					case "dpa":
					case "dppa":
					case "renja":
					case "renja_p":
						let elmk = divTab.find(`.secondary.menu [tb="${tbl}"]`);
						elmk.addClass('active')
							.closest('.ui.menu')
							.find('.item')
							.not($(elmk))
							.removeClass('active');

						switch (tbl) {
							case "sub_keg_renja":
							case "sub_keg_dpa":
							case "dpa":
							case "dppa":
							case "renja":
							case "renja_p":
								break;
							default:
								break;
						}
						if (tbl === 'sub_keg_renja' || tbl === 'sub_keg_dpa') {
							divTab.find('table.sub_keg').attr('hidden', "")
						} else {
							divTab.find('table.sub_keg').removeAttr('hidden')
							data['id_sub_keg'] = ini.closest('tr').attr('id_row');
							// tambhalan atribut id sub kegiatan di button jns
							divTab.find('button[jns="add"]').attr('id_sub_keg', data['id_sub_keg'])
						}
						jalankanAjax = true;
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
				if (result.success === true) {
					let hasKey = result.hasOwnProperty("error");
					if (hasKey) {
						loaderHide();
						switch (jenis) {
							case "get_tbl":
								const elmTable = divTab.find("table.insert");
								const elmtbody = elmTable.find(`tbody`);
								const elmtfoot = elmTable.find(`tfoot`);
								elmtbody.html(result.data.tbody);
								elmtfoot.html(result.data.tfoot);
								if (result?.data?.thead) {
									const elmthead = elmTable.find(`thead`);
									elmthead.html(result.data.thead);
								}
								divTab.find(`.ui.dropdown`).dropdown({});
								switch (tbl) {
									case 'renja':
									case 'dpa':
									case 'renja_p':
									case 'dppa':
										if (tbl !== 'sub_keg_renja') {
											const elmTableSubKeg = divTab.find("table.sub_keg");
											elmTableSubKeg.html(result.data.tr_sub_keg);
										}
										break;
									case 'value1':
										break;
									default:
										break;
								};
								$("[rms]").mathbiila();
								break;
							case "get_data":
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
	//===========button posting data =======@audit-ok add
	//=====================================================
	$("body").on("click", '[name="add"],[name="tambah"]', function (e) {
		e.preventDefault();
	})
	//=====================================================
	//===========button ambil data/get_data/ flyout =======@audit-ok flyout
	//=====================================================
	$("body").on("click", '[name="get_data"],[name="flyout"]', function (e) {
		e.preventDefault();
		let ini = $(this);
		const [node] = $(this);
		const attrs = {}
		$.each(node.attributes, (index, attribute) => {
			attrs[attribute.name] = attribute.value;
			let attrName = attribute.name;
			//membuat variabel
			let myVariable = attrName + 'Attr';
			window[myVariable] = attribute.value;
		});
		let linkTemplate = {
			wilayah: 'template/1. wilayah.xlsx',
			peraturan: 'template/2. template peraturan.xlsx',
			organisasi: 'template/4. Organisasi Perangkat Daerah.xlsx',
			sumber_dana: 'template/5. sumber dana.xlsx',
			akun_belanja: 'template/6. akun.xlsx',
			aset: 'template/7. neraca 1 header.xlsx',
			mapping: 'template/8. Mapping 1 header.xlsx',
			satuan: 'template/9. Satuan 1 Header.xlsx',
			rekanan: 'template/10. rekanan.xlsx',
			sub_keg: 'template/11. Sub keg 900.xlsx',
			ssh: 'template/12. ssh 2024.xlsx',
			asb: 'template/13. asb 2024.xlsx',
			sbu: 'template/17. sbu 2024.xlsx',
			tujuan_sasaran_renstra: 'template/18. tujuan sasaran renstra.xlsx',
			renstra: 'template/19. Renstra Template.xlsx',
			sub_keg_renja: 'template/20. Format Sub Kegiatan Renja:DPA:DPPA.xlsx',
			sub_keg_dpa: 'template/20. Format Sub Kegiatan Renja:DPA:DPPA.xlsx',
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
						case "daftar_paket":
							dataHtmlku.konten =
								buatElemenHtml("fieldTextarea", {
									label: "Nama Paket",
									atribut: 'name="uraian" rows="2" placeholder="Uraian Barang/Jasa..."',
								}) +
								buatElemenHtml("fieldTextAction", {
									label: "Jumlah Uraian Belanja",
									atribut: 'name="count_uraian_belanja" placeholder="Uraian Paket..." readonly',
									txtLabel: `<i class="search icon"></i>`,
									atributLabel: `name="modal_show" jns="uraian_belanja" tbl="${tbl}"`,
								}) +

								buatElemenHtml("fieldText", {
									label: "Volume",
									atribut: 'name="volume" placeholder="volume output..." rms',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan"',
									kelas: "search clearable ajx selection",
									dataArray: [
									],
								}) +
								buatElemenHtml("fieldText", {
									label: "Nilai Pagu",
									atribut:
										'name="pagu" placeholder="Nilai Pagu..." rms readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nilai Kontrak",
									atribut:
										'name="jumlah" placeholder="Nilai Kontrak..." rms',
								}) +
								buatElemenHtml("fieldDropdown", {//@audit now
									label: "Metode Pengadaan",
									atribut: 'name="metode_pengadaan"',
									kelas: "lainnya selection",
									dataArray: [
										["swakelola", "Swakelola"],
										["penyedia", "Penyedia", 'active']
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Metode Pemilihan",
									atribut: 'name="metode_pemilihan"',
									kelas: "lainnya selection",
									dataArray: [
										["e_purchasing", "e-purchasing"],
										["pengadaan_langsung", "pengadaan langsung"],
										["penunjukan", "penunjukan langsung"],
										["tender_cepat", "tender cepat"],
										["tender", "tender"]
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Pengadaan Barang Jasa",
									atribut: 'name="pengadaan_penyedia"',
									kelas: "lainnya selection",
									dataArray: [
										["barang", "Barang"],
										["konstruksi", "Pekerjaan Konstruksi"],
										["konsultansi", "Jasa Konsultansi"],
										["jasa_lainnya", "Jasa Lainnya"]
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Jenis Kontrak",
									atribut: 'name="jns_kontrak"',
									kelas: "lainnya selection",
									dataArray: [
									],
								}) +
								buatElemenHtml("accordionField", {
									label: "Jadwal Pengadaan",
									content: buatElemenHtml("calendar", {
										atribut: `name="tgl_kontrak" placeholder="Tanggal Kontrak" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="no_kontrak" placeholder="Nomor Kontrak" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_persiapan_kont" placeholder="Tanggal Persiapan Kontrak" non_data`, kelas: "datetime"
									}) + buatElemenHtml("text", {
										atribut: `name="no_persiapan_kont" placeholder="Nomor Persiapan Kontrak" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_spmk" placeholder="Tanggal SPMK" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="no_spmk" placeholder="Nomor Persiapan Kontrak" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_undangan" placeholder="Tanggal Undangan/Pengumuman" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="no_undangan" placeholder="Nomor Undangan/Pengumuman" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_penawaran" placeholder="Tanggal Penawaran" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="no_penawaran" placeholder="Nomor Penawaran" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_nego" placeholder="Tanggal Negoisasi" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="no_nego" placeholder="Nomor Negoisasi" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_sppbj" placeholder="Tanggal SPPBJ" non_data`, kelas: "date"
									}) + buatElemenHtml("text", {
										atribut: `name="no_sppbj" placeholder="Nomor SPPBJ" non_data`,
									})
								}) +
								buatElemenHtml("accordionField", {
									label: "Serah Terima Pengadaan",
									content: buatElemenHtml("calendar", {
										atribut: `name="tgl_pho" placeholder="Tanggal PHO" non_data`, kelas: "datetime"
									}) + buatElemenHtml("text", {
										atribut: `name="no_pho" placeholder="Nomor PHO" non_data`,
									}) + buatElemenHtml("calendar", {
										atribut: `name="tgl_fho" placeholder="Tanggal FHO" non_data`, kelas: "datetime"
									}) + buatElemenHtml("text", {
										atribut: `name="no_fho" placeholder="Nomor FHO" non_data`,
									}) +
										buatElemenHtml("fieldFileInput2", {
											label: "Pilih File Dokumen PHO",
											file: "file_pho",
											placeholderData: "Pilih File PHO...",
											accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
										}) +
										buatElemenHtml("fieldFileInput2", {
											label: "Pilih File Dokumen FHO",
											file: "file_fho",
											placeholderData: "Pilih File FHO...",
											accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
										})
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="3"',
								});
							break;
						case 'dppa':
						case 'renja_p':
							var vol_1 = 'vol_1_p';
							var vol_2 = 'vol_2_p';
							var vol_3 = 'vol_3_p';
							var vol_4 = 'vol_4_p';
							var vol_5 = 'vol_5_p';
							var sat_1 = 'sat_1_p';
							var sat_2 = 'sat_2_p';
							var sat_3 = 'sat_3_p';
							var sat_4 = 'sat_4_p';
							var sat_5 = 'sat_5_p';
							var volumeku = 'volume_p';
							var jumlahku = 'jumlah_p';
							var sumber_danaku = 'sumber_dana_p';
						case 'dpa':
						case 'renja':
							if (tbl === 'dpa' || tbl === 'renja') {
								vol_1 = 'vol_1';
								vol_2 = 'vol_2';
								vol_3 = 'vol_3';
								vol_4 = 'vol_4';
								vol_5 = 'vol_5';
								sat_1 = 'sat_1';
								sat_2 = 'sat_2';
								sat_3 = 'sat_3';
								sat_4 = 'sat_4';
								sat_5 = 'sat_5';
								volumeku = 'volume';
								jumlahku = 'jumlah';
								sumber_danaku = 'sumber_dana';
							}
							if (jenis === 'edit') {
								data.id_sub_keg = id_sub_kegAttr;
							}
							formIni.attr("id_sub_keg", ini.attr("id_sub_keg"));
							dataHtmlku.konten = buatElemenHtml("fieldDropdown", {
								label: "Objek Belanja",
								classField: `required`,
								atribut: 'name="objek_belanja" placeholder="pilih objek belanja..."',
								kelas: "search lainnya selection",
								dataArray: [
									["gaji", "Belanja Gaji dan Tunjangan ASN"],
									["barang_jasa_modal", "Belanja Barang Jasa dan Modal", "active"],
									["bunga", "Belanja Bunga"],
									["subsidi", "Belanja Subsidi"],
									["hibah_barang_jasa", "Belanja Hibah (Barang/Jasa)"],
									["hibah_uang", "Belanja Hibah (Uang)"],
									["sosial_barang_jasa", "Belanja Bantuan Sosial (Barang/Jasa)"],
									["sosial_uang", "Belanja Bantuan Sosial (Uang)"],
									["keuangan_umum", "Belania Bantuan Keuangan Umum"],
									["keuangan_khusus", "Belanja Bantuan Keuangan Khusus"],
									["btt", "Belanja Tidak Terduga (BTT)"],
									["bos_pusat", "Dana BOS (BOS Pusat)"],
									["blud", "Belanja Operasional (BLUD)"],
									["lahan", "Pembebasan Tanah/ Lahan"]
								],
							}) +
								buatElemenHtml("fieldDropdown", {
									label: "Rekening / Akun",
									classField: `required`,
									atribut: 'name="kd_akun" placeholder="pilih rekening/akun..."',
									kelas: "search clearable kd_akun ajx selection",
									dataArray: [
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Pengelompokan Belanja",
									classField: `required`,
									atribut: 'name="jenis_kelompok" placeholder="pilih pengelompokan..."',
									kelas: "search clearable lainnya selection",
									dataArray: [
										["paket", "Pemaketan Kerja"],
										["kelompok", "Pengelompokan Belanja"]
									],
								}) +
								buatElemenHtml("fieldDropdownLabel", {
									label: "Uraian Pengelompokan Belanja",
									txtLabel: '<i class="plus icon"></i>',
									classField: `required`,
									atributLabel: `name="modal_show" jns="add_field_json" tbl="sub_keg_${tbl}" klm="kelompok_json"`,
									atribut: 'name="kelompok" placeholder="pilih uraian kelompok..."',
									kelas: "search kelompok ajx selection",
									dataArray: [

									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Sumber Dana",
									classField: `required`,
									atribut: `name="${sumber_danaku}" placeholder="pilih sumber dana..."`,
									kelas: "search clearable multiple sumber_dana ajx selection",
									dataArray: [

									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Jenis Standar Harga",
									classField: `required`,
									atribut: 'name="jenis_standar_harga" placeholder="jenis standar harga..."',
									kelas: "clearable lainnya selection",
									dataArray: [
										["ssh", "SSH"],
										["sbu", "SBU"],
										["hspk", "HSPK"],
										["asb", "ASB"]
									],
								}) +
								buatElemenHtml("fieldDropdownLabel", {
									label: "Komponen",
									txtLabel: '<i class="search icon"></i>',
									classField: `required`,
									atributLabel: `name="modal_show" jns="search_field_json" tbl=""`,
									atribut: 'name="komponen" placeholder="pilih komponen..."',
									kelas: "search clearable komponen ajx selection",
									dataArray: [
										["", ""]
									],
								}) +
								buatElemenHtml("fieldText", {
									label: "TKDN",
									kelas: "disabled",
									atribut: 'name="tkdn" placeholder="tkdn..." rms non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Spesifikasi Komponen",
									kelas: "disabled",
									atribut: 'name="spesifikasi" placeholder="spesifikasi..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Satuan",
									kelas: "disabled",
									atribut:
										'name="satuan" placeholder="satuan komponen..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Harga Satuan",
									kelas: "disabled",
									atribut:
										'name="harga_satuan" placeholder="harga satuan..." rms non_data',
								}) +
								buatElemenHtml("fieldDropdownLabel", {
									label: "Keterangan",
									txtLabel: '<i class="plus icon"></i>',
									classField: `required`,
									atributLabel: `name="modal_show" jns="add_field_json" tbl="sub_keg_${tbl}" klm="keterangan_json"`,
									atribut: 'name="uraian" placeholder="pilih keterangan..."',
									kelas: "search clearable uraian ajx selection",
									dataArray: [
										["", ""]
									],
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "Tambahkan Pajak",
									atribut: 'name="pajak" non_data',
								}) +
								buatElemenHtml("fields", {
									label: "Koefisien Perkalian",
									kelas: "disabled",
									kelas2: ["search sat_1 ajx selection", "search sat_2 ajx selection", "search sat_3 ajx selection", "search sat_4 ajx selection"],
									classField: `required`,
									atribut: [`name="${sat_1}" placeholder="satuan..."`, `name="${sat_2}" placeholder="satuan..." non_data`, `name="${sat_3}" placeholder="satuan..." non_data`, `name="${sat_4}" placeholder="satuan..." non_data`],
									atribut2: [`name="${vol_1}" placeholder="Koefisien..." rms onkeypress="ketikUbah(event);"`, `name="${vol_2}" placeholder="Koefisien..." non_data rms onkeypress="ketikUbah(event);"`, `name="${vol_3}" placeholder="Koefisien..." non_data rms onkeypress="ketikUbah(event);"`, `name="${vol_4}" placeholder="Koefisien..." non_data rms onkeypress="ketikUbah(event);"`]
								}) +
								buatElemenHtml("fieldText", {
									label: "Volume",
									classField: `required`,
									kelas: "disabled",
									atribut: `name="${volumeku}" placeholder="volume..." rms`,
								}) +
								buatElemenHtml("fieldText", {
									label: "Koefisien (Keterangan Jumlah)",
									kelas: "disabled",
									atribut: 'name="koef_ket" placeholder="keterangan jumlah..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Total Belanja",
									kelas: "disabled",
									atribut: `name="${jumlahku}" placeholder="jumlah..." rms non_data`,
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
								});
							break;
						case 'sub_keg_dpa':
						case 'sub_keg_renja':
							dataHtmlku.konten =
								buatElemenHtml("fieldDropdown", {
									label: "Sub Kegiatan",
									atribut: 'name="kd_sub_keg" placeholder="pilih sub kegiatan..."',
									kelas: "search clearable kd_sub_keg ajx selection",
									dataArray: [
										["", ""]
									],
								}) +
								buatElemenHtml("accordionField", {
									label: "Indikator dan Tolok Ukur Kinerja Kegiatan",
									content: buatElemenHtml("fieldTextarea", {
										label: "Tolak Ukur Kinerja Capaian Kegiatan",
										atribut: 'name="tolak_ukur_capaian_keg" rows="2" placeholder="tolak ukur capaian kegiatan..." non_data',
									}) +
										buatElemenHtml("fieldText", {
											label: "Target Kinerja Capaian Kegiatan",
											atribut: 'name="target_kinerja_capaian_keg" placeholder="target kinerja capaian keg..." non_data',
										}) +
										buatElemenHtml("fieldTextarea", {
											label: "Tolak Ukur Kinerja Keluaran",
											atribut: 'name="tolak_ukur_keluaran" rows="2" placeholder="tolak ukur keluaran..." non_data',
										}) +
										buatElemenHtml("fieldText", {
											label: "Target Kinerja Keluaran",
											atribut: 'name="target_kinerja_capaian_keg" placeholder="target kinerja keluaran..." non_data',
										}) +
										buatElemenHtml("fieldTextarea", {
											label: "Tolak Ukur Kinerja Hasil",
											atribut: 'name="tolak_ukur_hasil" rows="2" placeholder="tolak ukur hasil..." non_data',
										}) +
										buatElemenHtml("fieldText", {
											label: "Target Kinerja Hasil",
											atribut: 'name="target_kinerja_hasil" placeholder="target kinerja hasil..." non_data',
										}) +
										buatElemenHtml("fieldTextarea", {
											label: "Keluaran Sub Kegiatan",
											atribut: 'name="keluaran_sub_keg" rows="2" placeholder="keluaran sub keg..." non_data',
										})
								}) +
								buatElemenHtml("accordionField", {
									label: "Indikator dan Tolok Ukur Kinerja Kegiatan Perubahan",
									content: buatElemenHtml("fieldTextarea", {
										label: "Tolak Ukur Kinerja Capaian Kegiatan",
										atribut: 'name="tolak_ukur_capaian_keg_p" rows="2" placeholder="tolak ukur capaian kegiatan..." non_data',
									}) +
										buatElemenHtml("fieldText", {
											label: "Target Kinerja Capaian Kegiatan",
											atribut: 'name="target_kinerja_capaian_keg_p" placeholder="target kinerja capaian keg..." non_data',
										}) +
										buatElemenHtml("fieldTextarea", {
											label: "Tolak Ukur Kinerja Keluaran",
											atribut: 'name="tolak_ukur_keluaran_p" rows="2" placeholder="tolak ukur keluaran..." non_data',
										}) +
										buatElemenHtml("fieldText", {
											label: "Target Kinerja Keluaran",
											atribut: 'name="target_kinerja_capaian_keg_p" placeholder="target kinerja keluaran..." non_data',
										}) +
										buatElemenHtml("fieldTextarea", {
											label: "Tolak Ukur Kinerja Hasil",
											atribut: 'name="tolak_ukur_hasil_p" rows="2" placeholder="tolak ukur hasil..." non_data',
										}) +
										buatElemenHtml("fieldText", {
											label: "Target Kinerja Hasil",
											atribut: 'name="target_kinerja_hasil_p" placeholder="target kinerja hasil..." non_data',
										}) +
										buatElemenHtml("fieldTextarea", {
											label: "Keluaran Sub Kegiatan",
											atribut: 'name="keluaran_sub_keg_p" rows="2" placeholder="keluaran sub keg..." non_data',
										})
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Sumber Dana",
									atribut: 'name="sumber_dana" placeholder="pilih sumber dana..."',
									kelas: "search clearable multiple sumber_dana ajx selection",
									dataArray: [
									],
								}) +
								buatElemenHtml("fieldText", {
									label: "Jumlah Pagu",
									atribut: 'name="jumlah_pagu" placeholder="jumlah (perencanaan)..." rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Jumlah Pagu Perubahan",
									atribut: 'name="jumlah_pagu_p" placeholder="jumlah (perencanaan)..." rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Lokasi",
									atribut: 'name="lokasi" placeholder="lokasi..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case 'tujuan_renstra':
							let kelompok = (tbl = 'tujuan_renstra') ? 'tujuan' : 'sasaran';
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "Kelompok",
									atribut: `name="kelompok" placeholder="Kelompok..." value="${kelompok}" disabled`,
								}) +
								buatElemenHtml("fieldTextarea", {
									label: `Uraian ${kelompok}`,
									atribut: 'name="text" rows="4" placeholder="uraian..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: `Indikator`,
									atribut: 'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case 'sasaran_renstra':
							kelompok = (tbl = 'tujuan_renstra') ? 'tujuan' : 'sasaran';
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "Kelompok",
									atribut: `name="kelompok" placeholder="Kelompok..." value="${kelompok}" disabled`,
								}) +
								buatElemenHtml("fieldTextarea", {
									label: `Tujuan ${kelompok}`,
									atribut: 'name="tujuan" rows="4" placeholder="Tujuan..." disabled',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: `Uraian ${kelompok}`,
									atribut: 'name="text" rows="4" placeholder="uraian..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: `Indikator`,
									atribut: 'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case 'tujuan_sasaran_renstra':
							dataHtmlku.konten =
								buatElemenHtml("fieldDropdown", {
									label: "Kelompok",
									atribut: 'name="kelompok"',
									kelas: "tujuan_sasaran selection",
									dataArray: [
										["tujuan", "Tujuan"],
										["sasaran", "Sasaran"]
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Tujuan",
									atributField: 'hidden',
									atribut: 'name="id_tujuan" non_data',
									kelas: "search clearable tujuan_renstra ajx selection",
									dataArray: [
										["", ""],
										["", ""]
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian",
									atribut: 'name="text" rows="4" placeholder="uraian..." autofocus',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: `Indikator`,
									atribut: 'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case 'renstra':
							dataHtmlku.konten =
								buatElemenHtml("fieldDropdown", {
									label: "Sasaran",
									atribut: 'name="sasaran"',
									kelas: "search clearable sasaran_renstra ajx selection",
									dataArray: [
										["", ""]
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Sub Kegiatan",
									atribut: 'name="kd_sub_keg" placeholder="pilih sub kegiatan..."',
									kelas: "search clearable kode ajx selection",
									dataArray: [
										["", ""]
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Indikator",
									atribut: 'name="indikator" rows="4" placeholder="indikator..."',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan" placeholder="pilih satuan..."',
									kelas: "search clearable satuan ajx selection",
									dataArray: [
										["paket", "Paket"],
										["m", "m"]
									],
								}) +
								buatElemenHtml("fieldText", {
									// typeText: 'number',
									label: "Data Capaian Awal",
									atribut: 'name="data_capaian_awal" placeholder="data capaian awal..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Target tahun 1",
									atribut: 'name="target_thn_1" placeholder="target tahun I..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Dana tahun 1",
									atribut: 'name="dana_thn_1" placeholder="Dana tahun I..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Target tahun 2",
									atribut: 'name="target_thn_2" placeholder="target tahun II..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Dana tahun 2",
									atribut: 'name="dana_thn_2" placeholder="Dana tahun II..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Target tahun 3",
									atribut: 'name="target_thn_3" placeholder="target tahun III..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Dana tahun 3",
									atribut: 'name="dana_thn_3" placeholder="Dana tahun III..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Target tahun 4",
									atribut: 'name="target_thn_4" placeholder="target tahun IV..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Dana tahun 4",
									atribut: 'name="dana_thn_4" placeholder="Dana tahun IV..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Target tahun 5",
									atribut: 'name="target_thn_5" placeholder="target tahun V..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Dana tahun 5",
									atribut: 'name="dana_thn_5" placeholder="Dana tahun V..." non_data rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "Lokasi",
									atribut: 'name="lokasi" placeholder="lokasi..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..." non_data',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
								});
							break;
						case 'rekanan':
							dataHtmlku.konten = buatElemenHtml("fieldTextAction", {
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
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="disable" non_data',
									txtLabel: "Non Aktif",
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
								buatElemenHtml("fieldDropdown", {
									label: "Kode Kelompok Barang/Jasa",
									atribut: 'name="kd_aset"',
									kelas: "search clearable aset ajx selection",
									dataArray: [
										["1.1.12.01.01.0010", "Isi Tabung Gas"]
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian Barang/Jasa",
									atribut: 'name="uraian_barang" rows="2" placeholder="Uraian Barang/Jasa..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Spesifikasi",
									atribut: 'name="spesifikasi" rows="2" placeholder="Spesifikasi..."',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Satuan",
									atribut: 'name="satuan"',
									kelas: "search clearable ajx selection",
									dataArray: [
									],
								}) +
								buatElemenHtml("fieldText", {
									label: "Harga Satuan",
									atribut:
										'name="harga_satuan" placeholder="harga satuan..." rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "TKDN",
									atribut:
										'name="tkdn" placeholder="tkdn..." rms',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Mapping Kode Akun dan Belanja",
									classField: `required`,
									atribut: 'name="kd_akun" placeholder="pilih rekening/akun..."',
									kelas: "search clearable multiple kd_akun ajx selection",
									dataArray: [
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="3"',
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
									label: "Kode Kelompok Barang/Jasa",
									atribut: 'name="kd_aset"',
									kelas: "search clearable aset ajx selection",
									dataArray: [
										["1.1.12.01.01.0010", "Isi Tabung Gas"]
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Rekening / Akun",
									classField: `required`,
									atribut: 'name="kd_akun" placeholder="pilih rekening/akun..."',
									kelas: "search clearable kd_akun ajx selection",
									dataArray: [
										["", ""]
									],
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Jenis Standar Harga",
									classField: `required`,
									atribut: 'name="kelompok" placeholder="jenis standar harga..."',
									kelas: "clearable lainnya selection",
									dataArray: [
										["ssh", "SSH"],
										["sbu", "SBU"],
										["hspk", "HSPK"],
										["asb", "ASB"]
									],
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
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
								buatElemenHtml("fieldCalendar", {
									label: "Renstra",
									kelas: "year",
									atribut: 'name="tahun_renstra" placeholder="tahun renstra..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Alamat",
									atribut:
										'name="alamat" placeholder="Alamat OPD..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Kepala OPD",
									atribut:
										'name="nama_kepala" placeholder="Nama Kepala SKPD..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nip. Kepala OPD",
									atribut:
										'name="nip_kepala" placeholder="Nip. Kepala SKPD..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
									atribut: 'non_data',
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
									label: "Kode Wilayah",
									atribut: 'name="kode" placeholder="Kode Wilayah..."',
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="get_data" tbl="${tbl}"`,
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Nama Wilayah",
									atribut: 'name="uraian" rows="4" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldDropdown", {
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
									atribut: 'name="item" rows="2"',
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
					let templateXlsx = linkTemplate[tbl];
					if (templateXlsx) {
						dataHtmlku.konten = buatElemenHtml("fieldLabel", {
							label: "Download Template",
							icon: "download green",
							value: "Download Template",
							href: BASEURL + templateXlsx,
							atribut: 'target="_blank""'
						});
					} else {
						dataHtmlku.konten = '';
					}
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//file
					dataHtmlku.konten += buatElemenHtml("fieldFileInput2", {
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
					switch (tbl) {
						case 'dpa':
						case 'renja':
						case 'dppa':
						case 'renja_p':
							formIni.attr("id_sub_keg", ini.attr("id_sub_keg"));
							break;
						case 'value1':
							break;
					};

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
			addRulesForm(formIni);
			let calendarDate = new CalendarConstructor(".ui.calendar.date");
			calendarDate.runCalendar();
			let calendarYear = new CalendarConstructor(".ui.calendar.year");
			calendarYear.Type("year");
			calendarYear.runCalendar();
			$('div[name="jml_header"]').dropdown("set selected", 1);
			$(".ui.accordion").accordion();
			formIni.find(".ui.dropdown.lainnya").dropdown();
			switch (jenis) {
				case 'add':
				case 'edit':
					switch (tbl) {
						case 'mapping':
							var dropKdAset = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.ajx[name="kd_aset"]')
							dropKdAset.returnList({ jenis: "get_row_json", tbl: "aset" });
							var dropKdAkun = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.ajx[name="kd_akun"]')
							dropKdAkun.returnList({ jenis: "get_row_json", tbl: "akun_belanja" });
							break;
						case "sbu":
						case "asb":
						case "ssh":
						case "hspk":
							// name="kd_aset"
							var dropdownKdAset = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown[name="kd_aset"]');
							dropdownKdAset.returnList({ jenis: "get_row_json", tbl: 'aset' });
							//satuan
							var dropdown_ajx_satuan = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.ajx[name="satuan"]')
							dropdown_ajx_satuan.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
							var dropdownKdAkun = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.kd_akun.ajx.selection')
							dropdownKdAkun.returnList({ jenis: "get_row_json", tbl: "akun_belanja_val" });
							break;
						case 'tujuan_sasaran_renstra':
							// formIni.find(".ui.dropdown.tujuan_sasaran.selection").dropdown();
							// case 'getJsonRows':
							// switch (allObjek.tbl) {
							// 	case 'tujuan_renstra'
							var allObjek = { jenis: 'getJsonRows', tbl: 'tujuan_renstra' };
							var dropdownTujuanSasaran = new DropdownConstructor('.ui.dropdown.tujuan_sasaran.selection')
							dropdownTujuanSasaran.onChange(allObjek);
							var dropdown_ajx_tujuan = new DropdownConstructor('.ui.dropdown.ajx.tujuan_renstra.selection');
							dropdown_ajx_tujuan.returnList({ jenis: "get_row_json", tbl: "tujuan_renstra", minCharacters: 1 });
							// dropdownTujuanSasaran.returnList("get_row_json", "tujuan_renstra", true)
							break;
						case 'renstra':
							var dropdown_ajx_tujuan = new DropdownConstructor('.ui.dropdown.ajx.sasaran_renstra.selection');
							dropdown_ajx_tujuan.returnList({ jenis: "get_row_json", tbl: "sasaran_renstra", minCharacters: 1 });
							var dropdown_ajx_kode = new DropdownConstructor('.ui.dropdown.kode.ajx.selection')
							dropdown_ajx_kode.returnList({ jenis: "get_row_json", tbl: "sub_keg" });//dropdownConstr.restore();
							dropdown_ajx_satuan = new DropdownConstructor('.ui.dropdown.satuan.ajx.selection')
							dropdown_ajx_satuan.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
							break;
						case 'sub_keg_dpa':
						case 'sub_keg_renja':
							var dropdownSumberDana = new DropdownConstructor('.ui.dropdown.sumber_dana.ajx.selection')
							dropdownSumberDana.returnList({ jenis: "get_row_json", tbl: "sumber_dana" });
							var dropdown_ajx_sub_keg = new DropdownConstructor('.ui.dropdown.kd_sub_keg.ajx.selection')
							dropdown_ajx_sub_keg.returnList({ jenis: "get_row_json", tbl: "sub_keg" });
							break;
						case 'dpa':
						case 'renja':
						case 'dpa':
						case 'dppa'://@audit drop renja
							var tabel_pakai_temporerSubkeg = 'sub_keg_renja';
							switch (tbl) {
								case 'dpa':
								case 'dppa':
									tabel_pakai_temporerSubkeg = 'sub_keg_dpa';
									break;
							};
							var renja_dpa = new DropdownConstructor('.ui.dropdown.kd_akun.ajx.selection')
							renja_dpa.returnList({ jenis: "get_row_json", tbl: "akun_belanja" });
							//jenis_kelompok 
							let dropdownJenisKelompok = new DropdownConstructor('.ui.dropdown[name="jenis_kelompok"]');
							allObjek = { jenis: 'gantiJenisKelompok', tbl: tbl };
							dropdownJenisKelompok.onChange(allObjek);
							//jenis_kelompok 
							let dropdownJenisKomponen = new DropdownConstructor('.ui.dropdown[name="jenis_standar_harga"]');
							allObjek = { jenis: 'gantiJenisKomponen' };
							dropdownJenisKomponen.onChange(allObjek);
							var dropdownSumberDana = new DropdownConstructor('.ui.dropdown.sumber_dana.ajx.selection')
							var allField = { klm: 'sumber_dana', id_sub_keg: $('form[name="form_flyout"]').attr('id_sub_keg'), jns_kel: 'sumber_dana' }
							dropdownSumberDana.returnList({ jenis: "getJsonRows", tbl: tabel_pakai_temporerSubkeg, set: allField });
							var dropdownKeterangan = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown[name="uraian"]')
							allField = { klm: 'keterangan_json', id_sub_keg: $('form[name="form_flyout"]').attr('id_sub_keg'), jns_kel: 'keterangan_json' };
							dropdownKeterangan.returnList({ jenis: "get_field_json", tbl: tabel_pakai_temporerSubkeg, set: allField });
							var dropdownSatuanRenja1 = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.sat_1');
							allField = { minCharacters: 1 };
							dropdownSatuanRenja1.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
							var dropdownSatuanRenja2 = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.sat_2');
							dropdownSatuanRenja2.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
							var dropdownSatuanRenja3 = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.sat_3');
							dropdownSatuanRenja3.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
							var dropdownSatuanRenja4 = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown.sat_4');
							dropdownSatuanRenja4.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
							break;
						case 'val':
							break;
						default:
							break;
					}
					break;
				case 'get_pengaturan':
					switch (tbl) {
						case 'pengaturan':
							let calendarDateTime = new CalendarConstructor(`[name="awal_renja"],[name="akhir_renja"]`);//(`.ui.calendar.datetime.startend
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
			};
			$("[rms]").mathbiila();
		} else if (attrName === "get_data") {
			switch (jenis) {
				case "get_data":
					data.text = ini.closest(".input").find('input[name="kode"]').val();
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
										// set nilai form 
										let elmAttrName = formIni.find('input[name],textarea[name]');
										switch (tbl) {
											case 'xxxxxx':
											default://isi form dengan data
												for (const iterator of elmAttrName) {
													let attrElm = $(iterator).attr('name');
													let postDataField = true;
													let dropDownElmAjx = $(iterator).closest('.ui.dropdown.ajx');
													// console.log(`attrElm : ${attrElm}`);
													if (attrElm === 'file') {
														formIni.form("set value", 'dum_file', result.data?.users[attrElm]);
													} else {
														let strText = null;
														let cariAttrRms = $(iterator).attr('rms');
														// console.log(`cariAttrRms : ${cariAttrRms}`);
														if (typeof cariAttrRms === 'undefined' || cariAttrRms === false) {
															strText = result.data?.users[attrElm];
														} else {
															strText = parseFloat(result.data?.users[attrElm]);
															strText = accounting.formatNumber(result.data?.users[attrElm], strText.countDecimals(), ".", ",");
														}
														// console.log(`strText : ${strText}`);
														// jika ada ajx class drpdown
														if (dropDownElmAjx.length > 0 && result.data.hasOwnProperty("values")) {
															if (result.data?.values[attrElm]) {
																postDataField = false;
																switch (tbl) {
																	case 'mapping':
																		switch (attrElm) {
																			case 'kd_aset':
																				dropKdAset.valuesDropdown(result.data?.values?.kd_aset);
																				dropKdAset.returnList({ jenis: "get_row_json", tbl: "aset" });
																				postDataField = false;
																				break;
																			case 'kd_akun':
																				dropKdAkun.valuesDropdown(result.data?.values?.kd_akun);
																				dropKdAkun.returnList({ jenis: "get_row_json", tbl: 'akun_belanja' });
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
																			case 'satuan':
																				dropdown_ajx_satuan.valuesDropdown(result.data?.values?.satuan);
																				dropdown_ajx_satuan.returnList({ jenis: "get_row_json", tbl: "satuan", set: allField });
																				postDataField = false;
																				break;
																			case 'kd_aset':
																				dropdownKdAset.valuesDropdown(result.data?.values?.kd_aset);
																				dropdownKdAset.returnList({ jenis: "get_row_json", tbl: 'aset' });
																				postDataField = false;
																				break;
																			case 'kd_akun':
																				dropdownKdAkun.valuesDropdown(result.data?.values?.kd_akun);
																				dropdownKdAkun.returnList({ jenis: "get_row_json", tbl: "akun_belanja_val" });
																				break;
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	case 'dpa':
																	case 'renja':
																		tabel_pakai_temporerSubkeg = 'sub_keg_renja';
																		switch (tbl) {
																			case 'dpa':
																			case 'dppa':
																				tabel_pakai_temporerSubkeg = 'sub_keg_dpa';
																				break;
																		}
																		let id_sub_keg = $('form[name="form_flyout"]').attr('id_sub_keg');
																		switch (attrElm) {
																			case 'kd_akun':
																				renja_dpa.valuesDropdown(result.data?.values?.kd_akun);
																				renja_dpa.returnList({ jenis: "get_row_json", tbl: "akun_belanja" });
																				break;
																			// case 'jenis_standar_harga':
																			// 	dropdownJenisKomponen.valuesDropdown(result.data?.values?.jenis_standar_harga);
																			// 	allObjek = { jenis: 'gantiJenisKomponen' };
																			// 	dropdownJenisKomponen.onChange(allObjek);
																			// 	break;
																			case 'kelompok':
																				let dropdownKelompok = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown[name="kelompok"]')
																				dropdownKelompok.valuesDropdown(result.data?.values?.kelompok);
																				allField = { klm: 'kelompok_json', id_sub_keg: id_sub_keg }
																				dropdownKelompok.returnList({ jenis: "get_field_json", tbl: tabel_pakai_temporerSubkeg, set: allField });
																				break;
																			case 'sumber_dana':
																				dropdownSumberDana.valuesDropdown(result.data?.values?.sumber_dana);
																				allField = { klm: 'sumber_dana', id_sub_keg: id_sub_keg, jns_kel: 'sumber_dana' }
																				dropdownSumberDana.returnList({ jenis: "get_field_json", tbl: tabel_pakai_temporerSubkeg, set: allField });
																				break;
																			case 'komponen':
																				let dropdownKomponen = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown[name="komponen"]');
																				dropdownKomponen.valuesDropdown(result.data?.values?.komponen);
																				let jenisKomponen = $('form[name="form_flyout"]').find('.ui.dropdown[name="jenis_standar_harga"]').dropdown('get value');
																				let rekeningAkun = $('form[name="form_flyout"]').find('.ui.dropdown[name="kd_akun"]').dropdown('get value');
																				let allFieldKomponen = { id_sub_keg: id_sub_keg, kd_akun: rekeningAkun };
																				dropdownKomponen.returnList({ jenis: "get_row_json", tbl: jenisKomponen, set: allFieldKomponen });
																				break;
																			case 'uraian':
																				dropdownKeterangan.valuesDropdown(result.data?.values?.uraian);
																				allField = { klm: 'keterangan_json', id_sub_keg: id_sub_keg, jns_kel: 'keterangan_json' }
																				dropdownKeterangan.returnList({ jenis: "get_field_json", tbl: tabel_pakai_temporerSubkeg, set: allField });
																				break;
																			case 'sat_1':
																				dropdownSatuanRenja1.valuesDropdown(result.data?.values?.sat_1);
																				dropdownSatuanRenja1.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
																				break;
																			case 'sat_2':
																				dropdownSatuanRenja2.valuesDropdown(result.data?.values?.sat_2);
																				dropdownSatuanRenja2.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
																				break;
																			case 'sat_3':
																				dropdownSatuanRenja3.valuesDropdown(result.data?.values?.sat_3);
																				dropdownSatuanRenja3.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
																				break;
																			case 'sat_4':
																				dropdownSatuanRenja4.valuesDropdown(result.data?.values?.sat_4);
																				dropdownSatuanRenja4.returnList({ jenis: "get_row_json", tbl: "satuan", minCharacters: 1 });
																			default:
																				break;
																		}
																		break;
																	case 'renstra':
																		switch (attrElm) {
																			case 'sasaran':
																				dropdown_ajx_tujuan.valuesDropdown(result.data?.values?.sasaran);
																				dropdown_ajx_tujuan.returnList({ jenis: "get_row_json", tbl: "sasaran_renstra", set: allField });
																				postDataField = false;
																				break;
																			case 'kd_sub_keg':
																				dropdown_ajx_kode.valuesDropdown(result.data?.values?.kd_sub_keg);
																				dropdown_ajx_kode.returnList({ jenis: "get_row_json", tbl: "sub_keg" });
																				postDataField = false;
																				break;
																			case 'satuan':
																				dropdown_ajx_satuan.valuesDropdown(result.data?.values?.satuan);
																				dropdown_ajx_satuan.returnList({ jenis: "get_row_json", tbl: "satuan", set: allField });
																				postDataField = false;
																				break;
																			default:
																				break;
																		}
																		break;
																	case 'sub_keg_dpa':
																	case 'sub_keg_renja':
																		switch (attrElm) {
																			case 'kd_sub_keg':
																				dropdown_ajx_sub_keg.valuesDropdown(result.data?.values?.kd_sub_keg);
																				dropdown_ajx_sub_keg.returnList({ jenis: "get_row_json", tbl: "sub_keg" });
																				postDataField = false;
																				break;
																			case 'sumber_dana':
																				dropdownSumberDana.valuesDropdown(result.data?.values?.sumber_dana);
																				dropdownSumberDana.returnList({ jenis: "get_row_json", tbl: "sumber_dana" });
																				postDataField = false;
																				break;

																			default:
																				break;
																		}
																		break;
																	case 'tujuan_sasaran_renstra':
																		switch (attrElm) {
																			case 'id_tujuan':
																				dropdown_ajx_tujuan.valuesDropdown(result.data?.values?.id_tujuan);
																				dropdown_ajx_tujuan.returnList({ jenis: "getJsonRows", tbl: "tujuan_renstra", ajax: true })
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
										addRulesForm(formIni);
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
	$(".ui.flyout")
		.flyout({
			closable: false,
			context: $(".bottom.pushable"),
			onShow: function () {
				$('#biilainayah').addClass('disabled');
				// loaderHide();
				// console.log('onShow flyout');
			},
			onHide: function (choice) {
				// 		//console.log(choice);
				$('#biilainayah').removeClass('disabled');
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
	//===========DEL ROW DAN EKSEKUSI ==========
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
		let contentModal = ['<i class="trash alternate icon"></i>anda yakin akan hapus data ini?',
			"menghapus data tidak dapat di batalkan...!",];
		let cryptos = false;
		let data = {};
		switch (jenis) {
			case 'lock':
			case 'setujui':
				jalankanAjax = true;
				break;
			default:
				break;
		};
		switch (jenis) {
			case 'unkunci':
			case 'unsetujui':
			case 'kunci':
			case 'setujui':
				contentModal = [
					`<i class="trash alternate icon"></i>anda yakin akan ${jenis} dokumen ${tbl} ini?`,
					`${jenis} mempengaruhi penginputan...!`,
				];
				data.tahun = $(`form[name="form_pengaturan"]`).form('get value', 'tahun');
				if (tbl === 'renstra') {
					let tanggal = $(`form[name="form_pengaturan"] .ui.calendar.year`).calendar("get date");
					if (tanggal) {
						tanggal = `${tanggal.getFullYear()}`;
						data.tahun = tanggal;
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
				if (id_row && jenis !== 'direct') {
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
		$(".ui.hapus.modal").modal({
			allowMultiple: true,
			//observeChanges: true,
			closable: false,
			onApprove: function () {
				if (jalankanAjax) {
					let classToast = 'warning';
					let iconToast = 'check circle icon';
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
									obj.find('input, textarea').addClass("transparent");
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
						};
						switch (result.error.code) {
							case 3:
								classToast = "success";
								iconToast = "check circle icon";
								break;
							case 'value1':

								break;
							default:
								classToast = "warning";
								iconToast = "check circle icon";
								break;
						};

						showToast(result.error.message, {
							class: classToast,
							icon: iconToast,
						});
					};
					runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
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
		}).modal("show");
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
	//=========================================
	//===========jalankan modal================@audit-ok modal click
	//=========================================
	$("body").on("click", '[name="modal_show"]', function (e) {
		e.preventDefault();
		const ini = $(this);
		const [node] = $(this);
		const attrs = {}
		$.each(node.attributes, (index, attribute) => {
			attrs[attribute.name] = attribute.value;
			let attrName = attribute.name;
			//membuat variabel
			let myVariable = attrName + 'Attr';
			window[myVariable] = attribute.value;
		});
		console.log(attrs);
		let url = 'script/get_data'
		let jalankanAjax = false;
		let mdl = $('.ui.modal[name="mdl_general"]');
		// mdl.addClass("large");
		//ubah kop header modal
		let elmIkonModal = $(mdl.find(".big.icons i")[0]); //ganti class icon
		let elmIkonModal2 = $(mdl.find(".big.icons i")[1]); //ganti class icon
		let elmKontenModal = mdl.find("h5 .content");
		let formIni = $('form[name="form_modal"]');
		let headerModal = 'Catatan';
		let data = {
			cari: cari(jnsAttr),
			rows: countRows(),
			jenis: jnsAttr,
			tbl: tblAttr,
			halaman: halaman
		};
		let elementForm = buatElemenHtml("fieldTextIcon", {
			label: "Uraian Pengelompokan Belanja",
			classField: `required`,
			atribut: 'name="uraian" placeholder="pengelompokan belanja..."',
			icon: "edit blue"
		});
		let kelasToast = "warning";
		let pesanToast = 'Koreksi Data';
		mdl.addClass("tiny");
		switch (jnsAttr) {
			case 'get_field_json':
			case 'add_field_json':
				switch (tblAttr) {
					case 'sub_keg_dpa':
					case 'sub_keg_renja':
						let id_sub_keg = ini.closest('.ui.form').attr('id_sub_keg');
						data.id_sub_keg = id_sub_keg;
						data.klm = klmAttr;
						let jenis_kelompok = klmAttr;
						switch (klmAttr) {
							case 'kelompok_json':
								jenis_kelompok = ini.closest('.ui.form').find('.ui.dropdown[name="jenis_kelompok"').dropdown('get value');
								break;
							default:
								jenis_kelompok = klmAttr;
								break;
						}
						if (klmAttr == 'kelompok' || klmAttr == 'paket') {
						}
						switch (klmAttr) {
							case 'kelompok_json':
								switch (jenis_kelompok) {
									case 'paket':
										headerModal = 'Tambah Uraian Pemaketan Belanja';
										break;
									case 'kelompok':
										headerModal = 'Tambah Uraian Pengelompokan Belanja';
										break;
									default:
										nameAttr = '';
										pesanToast = 'Pilih Pengelompokan Belanja';
										break;
								}
								break;
							case 'keterangan_json':
								headerModal = 'Keterangan Uraian Belanja';
								break;
							default:
								break;
						};
						data.jenis_kelompok = jenis_kelompok;
						formIni.attr('jns', jnsAttr).attr('tbl', tblAttr).attr('klm', klmAttr).attr('id_sub_keg', id_sub_keg).attr('jns_kel', jenis_kelompok)
						break;
					default:
						break;
				}
				break;
			case 'search_field_json':
				let kelasSearch = ini.closest('.ui.form').find('.ui.dropdown[name="jenis_standar_harga"').dropdown('get value')
				elementForm = buatElemenHtml("fieldSearchGrid", {
					label: "Uraian Pengelompokan Belanja",
					kelas: `${kelasSearch}`,
					atribut: 'name="uraian" placeholder="pengelompokan belanja..."',
				});
				break;
			case 'xxx':
				break;
			default:
				break;
		}
		let modalGeneral = new ModalConstructor(mdl);
		modalGeneral.globalModal();
		let InitializeForm = new FormGlobal(formIni);
		InitializeForm.run();
		elementForm += buatElemenHtml("errorForm");
		formIni.html(elementForm);
		document.getElementById("header_mdl").textContent = headerModal;
		addRulesForm(formIni);
		$("[rms]").mathbiila();

		if (jalankanAjax) {
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
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
	});
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		//@audit-ok DropdownConstructor
		jenis = '';
		tbl = '';
		ajax = false;
		result_ajax = {};
		url = "script/get_data";
		constructor(element) {
			this.element = $(element); //element;
			this.methodConstructor = new MethodConstructor();
		}
		returnList(allField = { jenis: "list_dropdown", tbl: "satuan", minCharacters: 3, set: {} }) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			let url = this.url;
			let jenis = allField.jenis;
			let tbl = allField.tbl;
			switch (jenis) {
				case 'get_field_json':
					if (allField?.set?.jns_kel) {
					} else {
						allField.set['jns_kel'] = $(`form[name="form_flyout"]`).find('.ui.dropdown[name="jenis_kelompok"').dropdown('get value');
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
					data: Object.assign({
						jenis: jenis,
						tbl: tbl,
						cari: function (value) {
							return elm.dropdown("get query");
						},
						rows: countRows(), //"all",
						halaman: 1,
					}, allField.set), fields: {
						results: "results",
					}, onSuccess: function (response, element, xhr) {
						// valid response and response.success = true
						this.result_ajax = response.results;
					},
					// filterRemoteData: true,
				},
				// filterRemoteData: true,
				// saveRemoteData: false,
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					let Results_ajax = this.result_ajax;
					let objekArray;
					switch (jenis) {
						case 'get_row_json':
							switch (tbl) {
								case 'sbu':
								case 'asb':
								case 'ssh':
								case 'hspk':
									Results_ajax.some(function (el) {
										// console.log(el);
										objekArray = el;
										return el.value == value;
									});
									let MyForm = $(`form[name="form_flyout"]`);
									MyForm.form('set values', {
										tkdn: objekArray.tkdn,
										satuan: objekArray.satuan,
										spesifikasi: objekArray.spesifikasi,
										harga_satuan: accounting.formatNumber(
											objekArray.harga_satuan,
											parseFloat(objekArray.harga_satuan).countDecimals(),
											".",
											","
										)
									})
									break;
								case 'value1':
									break;
								default:
									break;
							};
							break;
						default:
							break;
					}
					let ajaxSend = false
					if (ajaxSend == true) {
						let data = {
							cari: cari(jenis),
							rows: countRows(),
							jenis: jenis,
							tbl: tbl,
							halaman: halaman,
						};
						let url = 'script/get_data';
						let cryptos = false;
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
					}
				},
			});
		}
		returnListOnChange(allField = { jenis: "list_dropdown", tbl: "satuan", minCharacters: 3 }) {
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
					data: Object.assign({
						jenis: jenis,
						tbl: tbl,
						cari: function (value) {
							return elm.dropdown("get query");
						},
						rows: countRows(), //"all",
						halaman: 1,
					}, allField), fields: {
						results: "results",
					},
					// filterRemoteData: true,
				},
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					switch (jenis) {
						case 'getJsonRows':
							switch (tbl) {
								case 'tujuan_renstra'://tujuan sasaran renstra
									let elmTujuan = $(`form[name="form_flyout"]`).find('[name="id_tujuan"]');
									let fieldElmTujuan = elmTujuan.closest('.field');
									ajaxSend = false;
									if (value === 'tujuan') {
										fieldElmTujuan.attr('hidden');
									} else {
										//tambahkan dropdown
										fieldElmTujuan.removeAttr('hidden');
									}
									break;
								default:
									break;
							}
							break;
						case 'value1':
							break;
						default:
							break;
					};
					if (ajaxSend == true) {
						let data = {
							cari: cari(jenis),
							rows: countRows(),
							jenis: jenis,
							tbl: tbl,
							halaman: halaman,
						};
						let url = 'script/get_data';
						let cryptos = false;
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							if (result.success === true) {
								switch (jenis) {
									case 'getJsonRows':
										switch (tbl) {
											case 'tujuan_renstra'://tujuan sasaran renstra
												MethodConstructor.tujuanRenstra(result);
												break;
											default:
												break;
										}
										break;
									case 'value1':
										break;
									default:
										break;
								};
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
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
		onChange(allObjek = { jenis: "list_dropdown", tbl: "satuan", ajax: false }) {
			let ajaxSend = allObjek.ajax;
			this.element.dropdown({
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					switch (allObjek.jenis) {
						case 'getJsonRows':
							switch (allObjek.tbl) {
								case 'tujuan_renstra'://tujuan sasaran renstra
									let elmTujuan = $(`form[name="form_flyout"]`).find('[name="id_tujuan"]');
									let fieldElmTujuan = elmTujuan.closest('.field');
									ajaxSend = false;
									if (value === 'tujuan') {
										fieldElmTujuan.attr('hidden');
									} else {
										//tambahkan dropdown
										fieldElmTujuan.removeAttr('hidden');
									}
									break;
								default:
									break;
							}
							break;
						case 'gantiJenisKelompok':
							var allData = { jenis: 'gantiJenisKelompok', tbl: allObjek.tbl }
							MethodConstructor.refreshDropdown(allData);
							break;
						case 'gantiJenisKomponen':
							allData = { jenis: 'gantiJenisKomponen', tbl: allObjek.tbl }
							MethodConstructor.refreshDropdown(allData);
							break;
						case 'xxx':
							break;
						default:
							break;
					};
					if (ajaxSend == true) {
						let data = {
							cari: cari(allObjek.jenis),
							rows: countRows(),
							jenis: allObjek.jenis,
							tbl: allObjek.tbl,
							halaman: halaman,
						};
						let url = 'script/get_data';
						let cryptos = false;
						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							if (result.success === true) {
								switch (allObjek.jenis) {
									case 'getJsonRows':
										switch (allObjek.tbl) {
											case 'tujuan_renstra'://tujuan sasaran renstra
												MethodConstructor.tujuanRenstra(result);
												break;
											default:
												break;
										}
										break;
									case 'value1':
										break;
									default:
										break;
								};
							} else {
								kelasToast = "warning"; //'success'
							}
							showToast(result.error.message, {
								class: kelasToast,
								icon: "check circle icon",
							});
							loaderHide();
						};
						runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku", cryptos);
					}
				},
				saveRemoteData: true,
				filterRemoteData: true
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
				values: values
			})
		}
	}
	setTimeout(function () {
	}, 500);
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
			let fieldElmTujuan = elmTujuan.closest('.field');
			fieldElmTujuan.removeClass('hidden');
			if (elmTujuan.length <= 0) {
				// elm.after(dropdownInsert);
				// addRulesForm(formIni);
				// elmTujuan = $(`form[name="form_flyout"]`).find('[name="id_tujuan"]');
				// elmTujuan.dropdown({
				// 	values: result?.results,
				// })
			}
		}
		static refreshDropdown(allData = { jenis: 'gantiJenisKelompok', tbl: 'renja' }) {
			switch (allData.jenis) {
				case 'gantiJenisKelompok':
					let dropdownKelompok = new DropdownConstructor('form[name="form_flyout"] .ui.dropdown[name="kelompok"]')
					let allField = { klm: 'kelompok_json', id_sub_keg: $('form[name="form_flyout"]').attr('id_sub_keg') }
					dropdownKelompok.returnList({ jenis: "get_field_json", tbl: `sub_keg_${allData.tbl}`, set: allField });
					break;
				case 'gantiJenisKomponen':
					var dropdownKomponen = new DropdownConstructor('.ui.dropdown[name="komponen"]')
					let jenisKomponen = $('form[name="form_flyout"]').find('.ui.dropdown[name="jenis_standar_harga"]').dropdown('get value');
					let rekeningAkun = $('form[name="form_flyout"]').find('.ui.dropdown[name="kd_akun"]').dropdown('get value');
					let allFieldKomponen = { id_sub_keg: $('form[name="form_flyout"]').attr('id_sub_keg'), kd_akun: rekeningAkun };
					dropdownKomponen.returnList({ jenis: "get_row_json", tbl: jenisKomponen, set: allFieldKomponen });
					break;
				case 'xxxx':
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
					const [node] = $(MyForm);
					const attrs = {}
					$.each(node.attributes, (index, attribute) => {
						attrs[attribute.name] = attribute.value;
						let attrName = attribute.name;
						//membuat variabel
						let myVariable = attrName + 'Attr';
						window[myVariable] = attribute.value;
					});
					console.log(attrs);
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
					let elmRms = MyForm.find('input[rms]');
					Object.keys(elmRms).forEach((key) => {
						let element = $(elmRms[key]);
						let namaAttr = element.attr("name");
						if (namaAttr !== undefined) {
							formData.set(namaAttr, accounting.unformat(formData.get(namaAttr), ","));
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
					formData.forEach((value, key) => {
						// console.log(key + " " + value)
					});
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
							(formData.has(namaAttr) === false)
								? formData.set(namaAttr, 'off')
								: formData.set(namaAttr, 'on'); // Returns false
						}
					}
					property = ini.find(".ui.calendar.date");
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1
									}-${tanggal.getDate()}`; //local time
								formData.set(nameAttr, tanggal);
							}
						}
					}
					property = ini.find(".ui.calendar.year");
					console.log(property);
					if (property.length > 0) {
						for (const key of property) {
							let nameAttr = $(key).find("[name]").attr("name");
							let tanggal = $(key).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}`; //local time
								console.log(tanggal);

								formData.set(nameAttr, tanggal);
							}
						}
					}
					property = ini.find(".ui.calendar.datetime");
					// console.log(property);
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
								tanggal = new Date(tanggal).toISOString().slice(0, 19).replace('T', ' ');
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
								case 'add_field_json':
									jalankanAjax = true;
									switch (tbl) {
										case 'sub_keg_renja':
										case 'sub_keg_dpa':
											formData.set('klm', klmAttr);
											formData.set('id_sub_keg', id_sub_kegAttr);
											formData.set('jns_kel', jns_kelAttr);
											break;
										case 'value1':
											break;
										default:
											break;
									};
									break;
								case 'value':
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
														tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1
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
														tanggal = new Date().toISOString().slice(0, 19).replace('T', ' ');
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
								case "sub_keg_dpa":
									jalankanAjax = true;
									// 'id_sub_keg'
									break;
								case "renja":
								case "dpa":
								case "renja_p":
								case "dppa":
									formData.append("id_sub_keg", MyForm.attr('id_sub_keg'))
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
							var tdEdit = trTable.eq(indexTr).find("td");
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
					switch (tbl) {
						case 'value':
							break;
						case 'value1':
							break;
						default:
							break;
					};
					if (jalankanAjax) {
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
							// MyForm.form('reset')
							// $(".ui.modal.mdl_general").modal("hide");
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
	let calendarDate = new CalendarConstructor(".ui.calendar.date");
	calendarDate.runCalendar();
	let calendarYear = new CalendarConstructor(".ui.calendar.year");
	calendarYear.Type("year");
	calendarYear.runCalendar();
	let calendarDateTime = new CalendarConstructor(".ui.calendar.datetime");
	calendarDateTime.Type("datetime");
	calendarDateTime.runCalendar();
	//=======================================
	//===============SEACH GLOBAL============@audit-ok SearchConstructor
	//=======================================
	class SearchConstructor {
		constructor(elmSearch) {
			this.elmSearch = $(elmSearch); //element;
		}
		searchGlobal(allField = { minCharacters: 3, searchDelay: 600, jenis: 'get_Search_Json', tbl: 'sbu', data_send: {} }) {
			let MyElmSearch = this.elmSearch;
			MyElmSearch.search({
				minCharacters: allField.minCharacters,
				maxResults: countRows(),
				searchDelay: allField.searchDelay,
				apiSettings: {
					method: "POST",
					url: "script/get_data",
					// passed via POST
					data: Object.assign({
						jenis: allField.jenis,
						tbl: allField.tbl,
						cari: function (value) {
							return MyElmSearch.search('get value');
						},
						rows: countRows(), //"all",
						halaman: 1,
					}, allField.data_send),
				},
				fields: {
					results: [0].uraian,
					title: "title",
					description: "description",
					kode: "kode",
				},
				onSelect(result, response) {
				},
			});
		}
	}
	//=======================================
	//===============MODAL GLOBAL=============
	//=======================================
	class ModalConstructor {//@audit-ok ModalConstructor
		constructor(modal) {
			this.modal = $(modal); //element;
		}
		globalModal() {
			let MyModal = this.modal;
			MyModal.modal({
				allowMultiple: true,
				//observeChanges: true,
				closable: false,
				transition: "vertical flip", //slide down,'slide up','browse right','browse','swing up','vertical flip','fly down','drop','zoom','scale'
				onDeny: function () {
					//return false;//console.log('saya menekan tombol cancel');
				},
				onApprove: function () {
					// jika di tekan yes
					$(this).find("form").trigger("submit");
					return false;
				},
				onShow: function () {
				},
				onHidden: function () {
					$(this).find("form").form("reset");
				}
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
	function buatElemenHtml(namaElemen = "", dataElemen = {}) {//@audit-ok create elm
		let acceptData = "atribut" in dataElemen ? dataElemen.accept : ".pdf";
		let content = "content" in dataElemen ? dataElemen.content : "";
		let atribut = "atribut" in dataElemen ? dataElemen.atribut : "";
		let atribut2 = "atribut2" in dataElemen ? dataElemen.atribut2 : "";
		let aligned = "aligned" in dataElemen ? dataElemen.align : "";
		let header = "header" in dataElemen ? dataElemen.align : "h4";
		let atributField = "atributField" in dataElemen ? dataElemen.atributField : "";
		let atributLabel = "atributLabel" in dataElemen ? dataElemen.atributLabel : "";
		let classField = "classField" in dataElemen ? `${dataElemen.classField} ` : "";
		let kelasData = "kelas" in dataElemen ? dataElemen.kelas : "";
		let kelas2 = "kelas2" in dataElemen ? dataElemen.kelas2 : "";
		let labelData = "label" in dataElemen ? dataElemen.label : "";
		let label = "label" in dataElemen ? dataElemen.label : "";
		let href = "href" in dataElemen ? dataElemen.href : "";//file
		let file = "file" in dataElemen ? dataElemen.file : "file";//file
		let textDrpdown = "textDrpdown" in dataElemen ? dataElemen.textDrpdown : "";
		let placeholderData = "placeholderData" in dataElemen ? dataElemen.placeholderData : "";
		let elemen1Data = "elemen1" in dataElemen ? dataElemen.elemen1 : "";
		let iconData = "icon" in dataElemen ? dataElemen.icon : "download icon";
		let icon = "icon" in dataElemen ? dataElemen.icon : "calendar";
		let icon2 = "icon2" in dataElemen ? dataElemen.icon : "";
		let posisi = "posisi" in dataElemen ? dataElemen.posisi : "left";
		let colorData = "color" in dataElemen ? dataElemen.color : "positive";
		let valueData = "value" in dataElemen ? dataElemen.value : "";
		let iconDataSeach = "icon" in dataElemen ? dataElemen.icon : "search icon";
		let txtLabelData = "txtLabel" in dataElemen ? dataElemen.txtLabel : "";
		let dataArray = "dataArray" in dataElemen ? dataElemen.dataArray : []; //contoh untuk dropdown
		let typeText = "typeText" in dataElemen ? dataElemen.typeText : `type="text"`;
		let dataArray2 = "dataArray2" in dataElemen ? dataElemen.dataArray2 : [[]]; //contoh buat dropdown yang ada deskripsi
		let jenisListDropdown = "jenisListDropdown" in dataElemen ? dataElemen.jenisListDropdown : ""; //jenis dropdown[Selection,Search Selection,Clearable Selection,Multiple Selection,Multiple Search Selection,Description,Image,Actionable ,Columnar Menu]
		// let file
		let accept = "accept" in dataElemen ? dataElemen.accept : ".xlsx";
		switch (namaElemen) {
			case "errorForm":
				elemen = `<div class="ui icon success message"><i class="check icon"></i><div class="content"><div class="header">Form sudah lengkap</div><p>anda bisa submit form</p></div></div><div class="ui error message"></div>`;
				break;
			case "text":
				elemen = `<div class="${classField}field" ${atributField}><input type="text" ${atribut}></div>`;
				break;
			case "accordionField":
				elemen = `<div class="ui accordion${classField} field" ${atribut} ${atributField}><div class="title"><i class="icon dropdown"></i>${labelData} </div><div class="content field">${content} </div></div>`;
				break;
			case "fieldTextAccordion":
				elemen = `<div><label class="visible" style="display: block !important;">${labelData}</label><input ${atribut} type="text" class="visible" style="display: inline-block !important;"></div>`;
				break;
			case "segment":
				elemen = `<div class="ui segments"><div class="ui segment"></div><div class="ui segment"></div></div>`;
				break;
			case "messageLink":
				elemen = `<div class="ui icon message ${colorData}"><i class="${iconData}"></i><div class="content"><div class="header">${labelData} </div><a ${atribut}  target="_blank">${valueData}</a></div></div>`;
				break;
			case "divider":
				elemen = `<${header} class="ui horizontal ${aligned} divider header">${icon2}${label}</${header}>`;
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
			case "fieldLabel":
				elemen =
					`<div class="${classField}field" ${atributField}><label>${labelData}</label><a class="ui fluid label ${kelasData}" href="${href}" ${atribut}>
					<i class="${icon} icon"></i>${valueData}</a></div>`;
				break;
			case "fieldSearchGrid"://untuk modal
				elemen = `<div class="ui aligned grid">
					<div class="right floated right aligned column">
						<div class="ui scrolling search ${kelasData} fluid category">
							<div class="ui icon input"><input class="prompt" type="text" placeholder="Cari..." autocomplete="off" ${atribut}><i class="search icon"></i></div>
							<div class="results"></div>
						</div>
					</div>
				</div>`;
				break;
			case "fieldSearch":
				elemen =
					`<div class="${classField}field" ${atributField}><label>${labelData}</label><div class="ui fluid category scrolling search ${kelasData}"><div class="ui icon fluid input"><input class="prompt" type="text" ${atribut} placeholder="Search..."><i class="search icon"></i></div><div class="results"></div></div></div>`;
				break;
			case "calendar":
				elemen = `<div class="${classField}field" ${atributField}><div class="ui calendar ${kelasData}"><div class="ui fluid input left icon"><i class="calendar icon"></i><input type="text" ${atribut}></div></div></div>`;
				break;
			case "fieldCalendar":
				elemen =
					`<div class="${classField}field" ${atributField}><label>${labelData}</label><div class="ui calendar ${kelasData}"  ${atribut2}><div class="ui input left icon"><i class="calendar icon"></i><input type="text"  ${atribut}"></div></div></div>`;
				break;
			case "fieldAndLabel":
				elemen = `<div class="${classField}field" ${atributField}><label>${labelData}</label> ${elemen1Data}</div>`;
				break;
			case "fieldText":
				elemen = `<div class="${classField}field" ${atributField}><label> ${labelData} </label><div class="ui ${kelasData} input"><input type="${typeText}" ${atribut} ></div></div>`;
				break;
			case "multiFieldTextAction":
				let inputElm = '';
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					inputElm += `<input type="${typeText}" ${rowsData}>`;
				}
				elemen =
					`<div class="${classField}field" ${atributField}><label>
					${labelData}
					</label><div class="ui action fluid input multi">
					${inputElm}
					<button class="ui teal button" ${atributLabel}>${txtLabelData}</button> </div></div>`;
				break;
			case "fieldTextAction":
				elemen =
					`<div class="${classField}field" ${atributField}><label>${labelData}</label><div class="ui action input"><input type="${typeText}" ${atribut}><button class="ui teal button icon"  ${atributLabel}>${txtLabelData}</button></div></div>`;
				break;
			case "fieldTextLabelKanan":
				elemen =
					`<div class="${classField}field" ${atributField}><label>${labelData}</label><div class="ui fluid right labeled input"><input type="${typeText}" ${placeholderData} ${atribut}><div class="ui basic label" ${atributLabel}>" ${txtLabelData}</div></div></div>`;
				break;
			case "fieldTextIcon":
				elemen = `<div class="${classField}field" ${atributField}><label>${labelData}</label><div class="ui fluid ${posisi} icon input"><input type="${typeText}" ${atribut}><i class="${icon} icon"></i></div></div>`;
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
					labelData +
					"</label><input " +
					placeholderData +
					' name="username" type="text" ' +
					atribut +
					"></div>";
				break;
			case "fieldFileInput":
				elemen =
					`<div class="${classField}field" ${atributField}><label>` +
					labelData +
					'</label><div class="ui file input"><input type="file" ' +
					atribut +
					"></div></div>";
				break;
			case "fieldFileInput2":
				//atribut file hanya placeholder
				elemen =
					`<div class="${classField}field" ${atributField}><label>${labelData}</label><div class="ui fluid right action left icon input"><i class="folder open yellow icon"></i><input type="text" placeholder="${placeholderData}" readonly="" name="dum_file" ${atribut}><input hidden type="file" nama="file" name="${file}" accept="${accept}" non_data><button class="ui red icon button" name="del_file"><i class="erase icon"></i></button></div></div>`;
				break;
			case "fieldTextarea":
				elemen =
					`<div class="${classField}field" ${atributField}><label>` +
					labelData +
					"</label><textarea " +
					atribut +
					"></textarea></div>";
				break;
			case "fieldCheckbox":
				elemen = `<div class="inline${classField} field" ${atributField}><div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden" ${atribut}><label>${labelData}</label></div></div>`;
				break;
			case "fielToggleCheckbox":
				elemen =
					`<div class="${classField}field" ${atributField}><label>` +
					labelData +
					'</label><div class="ui toggle checkbox"><input type="checkbox" ' +
					atribut +
					"><label>" +
					txtLabelData +
					"</label></div></div>";
				break;
			case "fieldTxtDropdownLabel":
				elemen = `<div class="${classField}field" ${atributField}>
					<div class="ui right labeled input fluid">
						<input type="text" placeholder="Koefisien" ${atribut2}>
						<div class="ui basic  dropdown label ${kelasData}" placeholder="satuan">
							<input type="hidden" ${atribut}><i class="dropdown icon"></i>
							<div class="default text">${textDrpdown}</div>
							<div class="menu">
							</div>
						</div>
					</div>
				</div>`;
				break;
			case "fields":
				let elm = '';
				if ((typeof atribut) === "object") {
					atribut.forEach(myFunction);
					function myFunction(item, index, arr) {
						elm += buatElemenHtml("fieldTxtDropdownLabel", {
							label: "Koefisien Perkalian",
							kelas: kelas2[index],
							textDrpdown: 'sat.',
							atribut: item,
							atribut2: atribut2[index],
						});
					}
				}
				elemen = `<div class="${classField}field" ${atributField}><label>${labelData}</label>
				${elm}
				</div>`;
				break;
			case "fieldDropdownLabel":
				var elemen11 =
					`<div class="${classField}field" ${atributField}><label> ${labelData}
					</label><div class="ui right labeled input"><div class="ui dropdown fluid 
					${kelasData}" ${atribut}><input type="hidden" ${atribut}><i class="dropdown icon"></i><div class="default text">${textDrpdown}</div><div class="menu">`;
				///Memisahkan array
				var elemen22 = "";
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					let dataValue = rowsData[0];
					// active selected
					let classItem = 'item';
					if (rowsData.length === 1) {
						let txt = dataValue;
						if (rowsData[2]) {
							if (rowsData[2] === 'active' || rowsData[3] === 'active') {
								classItem = 'item active selected';
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
							if (rowsData[2] === 'active' || rowsData[3] === 'active') {
								classItem = 'item active selected';
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
						if (deskripsi !== 'active') {
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
				var elemen33 = `</div></div><button class="ui teal label icon button" ${atributLabel}>${txtLabelData}</button>
				</div></div>`;
				elemen = elemen11 + elemen22 + elemen33;
				break;
			case "fieldDropdown":
				///Memisahkan array
				elemen22 = "";
				var disableatributActive = false
				var txtSelectedActive = '';
				for (let x in dataArray) {
					let rowsData = dataArray[x];
					let dataValue = rowsData[0];
					// active selected
					let classItem = 'item';
					if (rowsData.length === 1) {
						let txt = dataValue;
						if (rowsData[2]) {
							if (rowsData[2] === 'active' || rowsData[3] === 'active') {
								classItem = 'item active selected';
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
						if (rowsData[2] === 'active' || rowsData[3] === 'active') {
							classItem = 'item active selected';
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
						if (deskripsi !== 'active') {
							elemen22 +=
								`<div class="${classItem}" data-value="` +
								dataValue +
								'"><span class="description">' +
								deskripsi +
								'</span><span class="text">' +
								txt +
								"</span></div>";
						} else {
							if (rowsData[2] === 'active' || rowsData[3] === 'active') {
								classItem = 'item active selected';
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
				}
				var atributActive = atribut;
				if (disableatributActive) {
					atributActive += txtSelectedActive;
				}
				elemen11 =
					`<div class="${classField}field" ${atributField}><label>` +
					labelData +
					'</label><div class="ui dropdown ' +
					kelasData +
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
			if (typeof non_data === 'undefined' && non_data === false) {
				formku.form("add rule", atribut, {
					rules: [
						{
							type: "empty",
							prompt: "Lengkapi Data " + lbl,
						},
					],
				});
			} else {
				formku.form("remove field", atribut);
			}
		}
		/*
		for (i = 0; i < attrName.length; i++) {
			let atribut = formku.find(attrName[i]).attr("name");
			let lbl = formku.find(attrName[i]).attr("placeholder");
			if (lbl === undefined) {
				lbl = formku.find(attrName[i]).closest(".field").find("label").text();
				if (lbl === undefined || lbl === "") {
					lbl = formku.find(attrName[i]).closest(".field").find("div.sub.header").text();
				}
				if (lbl === undefined || lbl === "") {
					lbl = atribut.replaceAll(/_/g, " ");
				}
			}
			let non_data = formku.find(attrName[i]).attr("non_data");
			if (typeof non_data === "undefined" || non_data === false) {
				formku.form("add rule", atribut, {
					rules: [
						{
							type: "empty",
							prompt: "Lengkapi Data " + lbl,
						},
					],
				});
			} else {
				formku.form("remove field", atribut);
			}
		}*/
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
					lbl = formku.find(attrName[i]).closest(".field").find("div.sub.header").text();
				}
				if (lbl === undefined || lbl === "") {
					lbl = atribut.replaceAll(/_/g, " ");
				}
			}
			var non_data = formku.find(attrName[i]).attr("non_data");
			if (typeof non_data === "undefined" && non_data === false) {
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
	setTimeout(function () {
	}, 500);
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
});
// onkeypress="return rumus(event);"
function rumus(evt) {
	return /[0-9]|\=|\+|\-|\/|\*|\%|\[|\]|\,/.test(
		String.fromCharCode(evt.which)
	);
}
// onkeypress="return ketikUbah(event);"
function ketikUbah(evt) {
	let MyForm = $(`form[name="form_flyout"]`);
	let hargaSat = accounting.unformat(MyForm.form('get value', 'harga_satuan'), ",");
	let vol_1 = accounting.unformat(MyForm.form('get value', 'vol_1'), ",");
	let vol_2 = accounting.unformat(MyForm.form('get value', 'vol_2'), ",");
	let vol_3 = accounting.unformat(MyForm.form('get value', 'vol_3'), ",");
	let vol_4 = accounting.unformat(MyForm.form('get value', 'vol_4'), ",");
	let vol_1_kali = (vol_1 <= 0) ? 1 : vol_1;
	let vol_2_kali = (vol_2 <= 0) ? 1 : vol_2;
	let vol_3_kali = (vol_3 <= 0) ? 1 : vol_3;
	let vol_4_kali = (vol_4 <= 0) ? 1 : vol_4;
	let perkal = (vol_1_kali * vol_2_kali * vol_3_kali * vol_4_kali) * hargaSat;
	strText = parseFloat(perkal);
	strText = accounting.formatNumber(perkal, perkal.countDecimals(), ".", ",");
	MyForm.form('set value', 'jumlah', strText);
	MyForm.form('set value', 'volume', vol_1_kali * vol_2_kali * vol_3_kali * vol_4_kali);
}