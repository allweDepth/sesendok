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
			tab_home: ["home icon", "DASHBOARD", "seSendok"],
			tab_rentra: ["clipboard list icon", "RENSTRA", "Rencana Startegi"],
			tab_renja: ["clipboard list icon", "RENJA", "Rencana Kerja dan Anggaran Satuan Kerja Perangkat Daerah, yang selanjutnya disingkat RKA SKPD adalah dokumen yang memuat rencana pendapatan dan belanja SKPD atau dokumen yang memuat rencana pendapatan, belanja, dan Pembiayaan SKPD yang melaksanakan fungsi bendahara umum daerah yang digunakan sebagai dasar penyusunan rancangan APBD."],
			tab_dpa: ["clipboard list icon", "DPA", "Dokumen Pelaksanaan Anggaran"],
			tab_dpa_perubahan: ["clipboard list icon", "DPA", "Dokumen Pelaksanaan Perubahan Anggaran"],
			tab_kontrak: ["clipboard list icon", "KONTRAK", "perjanjian"],
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
		var ini = $(this);
		var attrName = ini.attr("name");
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		let formIni = $('form[name="form_flyout"]');
		//removeRulesForm(formIni);
		var url = "script/get_data";
		var jalankanAjax = false;
		var htmlForm = "";
		var dataHtmlku = {};
		var iconFlyout = $('i[name="icon_flyout"]'); //icon flyout
		var headerFlyout = $('div[name="content_flyout"]'); //header flyout
		var data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
		};

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
	$(".ui.flyout").flyout({
		//non aktifkan toggle jika tekan dimmer
		selector: { pusher: '.flyout' },
		className: { pushable: '.bottom.pushable' },
		closable: false,
		onShow: function () {
			loaderHide();
			// console.log('onShow flyout');
		},
		onHide: function (choice) {
			//console.log(choice);
			// let form = $(".ui.flyout form");
			// form.form('clear');
			// removeRulesForm(form);
			// //inisialize kembali agar tidak error di console
			// var reinitForm = new FormGlobal(form);
			// reinitForm.run();
		},
		onApprove: function (elemen) {
			$(elemen).closest('div.flyout').find('form').form('submit');
			return false;
		},
		context: $('.bottom.segment'),
	}).flyout('attach events', '[name="flyout"]');
	// $('.ui.flyout').flyout({
	// 	context: $('.bottom.segment')
	// }).flyout('attach events', '[name="flyout"]');
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
});
