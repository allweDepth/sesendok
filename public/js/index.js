const halAwal = halamanDefault;
const enc = new Encryption();
$(document).ready(function () {
	"use strict";
	//remove session storage
	sessionStorage.clear()
	var halaman = 1;





	//sidebar toggle
	$(".ui.sidebar").sidebar({
		//context: $('.context.example')
		selector: { pusher: '.context.example.pusher' },
	}).sidebar('setting', 'transition', 'push').sidebar('attach events', "#toggle");

	$(".ui.accordion").accordion(
		// {
		// 	exclusive: false
		// }
	);
	$(".bottom.attached.segment .ui.sticky").sticky({
		context: ".bottom.segment",
		pushing: true,
	});
	//menu lain

	$(".ui.dropdown").dropdown();

	$(".menu .item").tab();

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
			tab_home: ["home icon", "DASHBOARD", "seSendok"],
			tab_rentra: ["clipboard list icon", "RENSTRA", "Rencana Startegi"],
			tab_renja: ["clipboard list icon", "RENJA", "Rencana Kerja dan Anggaran Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD."],
			tab_dpa: ["clipboard list icon", "DPA", "Dokumen Pelaksanaan Anggaran"],
			tab_dpa_perubahan: ["clipboard list icon", "DPA", "Dokumen Pelaksanaan Perubahan Anggaran"],
			tab_kontrak: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_input_real: ["clipboard list icon", "Realisasi", "Input Realisasi"],
			tab_spj: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_lap: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_bidang_urusan: ["clipboard list icon", "BIDANG URUSAN", "Bidang Urusan: Sejumlah tugas atau tanggung jawab khusus pemerintah daerah yang diKlasifikasikan menjadi urusan pemerintahan konkuren terbagi menjadi 32 (tiga puluh dua) bidang urusan, 2 (dua) Urusan Pendukung, 7(tujuh) Urusan Penunjang, 1 (satu) urusan Pengawasan, 3 (Tiga) Urusan Kewilayahan, serta Urusan Kekhususan dan keistimewaan"],
			tab_prog: ["clipboard list icon", "PROGRAM", "Klasifikasi dan kodefikasi program disusun berdasarkan pembagian sub urusan dan kegiatan disusun berdasarkan pembagian kewenangan yang diatur dalam Lampiran Undang-Undang Nomor 23 Tahun 2014.Hal ini dilakukan untuk memastikan ruang lingkup penyelenggaraan pemerintahan daerah dilakukan sesuai dengan keenangannya, sehingga mendukung pelaksanaan asas prinsip akuntabilitas, efisiensi, eksternalitas serta kepentingan strategis nasional"],
			tab_keg: ["clipboard list icon", "KEGIATAN", "Klasifikasi dan kodefikasi kegiatan"],
			tab_sub_keg: ["clipboard list icon", "SUB KEGIATAN", "Klasifikasi dan kodefikasi sub kegiatan disusun berdasarkan aktivitas atau layanan dalam penyelesaian permasalahan daerah sesuai kewenangannya."],
			tab_akunbelanja: ["clipboard list icon", "AKUN", "Klasifikasi, Kodefikasi, dan Nomenklatur Rekening dalam pengelolaan keuangan daerah merupakan alat dalam proses perencanaan anggaran. Rekening Penyusunan Anggaran dan LRA disusun berdasarkan penggolongan, pemberian kode, dan daftar penamaan akun pendapatan daerah, belanja daerah, dan pembiayaan daerah yang ditujukan untuk digunakan dalam penyusunan anggaran dan LRA terdiri atas akun, kelompok, jenis, objek, rincian objek, dan sub rincian objek."],
			tab_sumber_dana: ["clipboard list icon", "Sumber Dana", "Klasifikasi, Kodefikasi, dan Nomenklatur Sumber Pendanaan ditujukan untuk memberikan informasi atas sumber dana berdasarkan tujuan penggunaan dana dari setiap pelaksanaan urusan pemerintahan daerah yang dijabarkan berdasarkan program, kegiatan dan sub kegiatan dalam rangka pengendalian masing-masing kelompok dana meliputi pengawasan/control, akuntabilitas/accountability dan transparansi/transparency (CAT)."],
			tab_peraturan: ["clipboard list icon", "Peraturan", "ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan."],
			tab_rekanan: ["clipboard list icon", "REKANAN", "Penyedia barang dan/atau jasa"],
			tab_ssh: ['clipboard list icon', 'SSH', 'PP 12 Tahun 2019<ol class="ui list"><li class="item">Belanja Daerah sebagaimana dimaksud dalam Pasal 49 ayat (5) berpedoman pada standar harga satuan regional, analisis standar belanja, dan/atau standar teknis sesuai dengan ketentuan peraturan perurndang-undangan.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (1) dan ayat (2) ditetapkan dengan Peraturan Presiden.</li><li class="item">Standar harga satuan regional sebagaimana dimaksud pada ayat (3) digunakan sebagai pedoman dalam menyusun standar harga satuan pada masing-masing Daerah.</li></ol>'],
			tab_hspk: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_asb: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_sbu: ["clipboard list icon", "Renja", "Rencana Kerja"],
			tab_peraturan: ["table icon", "Peraturan", "peraturan yang digunakan"],
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
		const dasboard = $(".message.dashboard");
		let ini = $(this);
		let jenis = ini.attr('data-tab');
		$(`#cari_data`).attr('name', jenis);
		let iconDashboard = "home icon";
		let headerDashboard = ini.text();
		let pDashboard = "AHSP Pekerjaan Konstruksi";
		if (jenis in arrayDasboard) {
			iconDashboard = arrayDasboard[jenis][0];
			headerDashboard = arrayDasboard[jenis][1];
			pDashboard = arrayDasboard[jenis][2];
		}
		dasboard.find($("i")).attr("class", "").addClass(iconDashboard);
		dasboard.find($("div.header")).text(headerDashboard);
		dasboard.find($("div.pDashboard")).html(pDashboard);
	})
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

	// let dropdown = new DropdownConstructor(".satuan.ui.dropdown");
	// dropdown.satuan();
	function tok(elm) {
		$(".ui.sidebar").sidebar("hide");
		// elm.closest('.ui.accordion').find('.active').removeClass('active');
		elm.closest(".vertical.sidebar.menu").find(".active").removeClass("active");
	}
});
