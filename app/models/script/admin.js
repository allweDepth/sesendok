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

	$(".ui.accordion").accordion();
	$(".ui.accordion.menu_utama").accordion({
		exclusive: false,
	});
	//Sticking to Own Context
	$(".bottom.attached.segment .ui.sticky").sticky({
		context: ".bottom.segment",
		pushing: true,
	});
	//menu lain
	// tanpa clerable
	$(".ui.dropdown.lain").dropdown();
	$(".ui.dropdown.lainnya").dropdown({
		fullTextSearch: true,
		clearable: true,
	});
	$(".ui.dropdown").dropdown();
	$(".ui.dropdown.custom").dropdown({
		transition: "drop",
	});
	$(".menu .item").tab();
	$("body").on("click", ".item.menuku", function () {
		$(this)
			.addClass("active")
			.closest(".ui.menu")
			.find(".item")
			.not($(this))
			.removeClass("active");
	});
	$('.dimmable.image')
		.dimmer({
			on: 'hover'
		})
		;
	//logout
	$("body").on("click", "[name='log_out']", function () {
		//setCookie('sesendok', '', 0);
		//eraseCookie('sesendok');
		setTimeout(function () {
			window.location.href = "script/logout";
		}, 400);
	});
	function tok(elm) {
		$(".ui.sidebar").sidebar("hide");
		// elm.closest('.ui.accordion').find('.active').removeClass('active');
		elm.closest(".vertical.sidebar.menu").find(".active").removeClass("active");
	}

	//=============================================
	//===========modal_full_screen monev ==========
	//=============================================
	var chart;
	//$('.pulsat').transition('pulsating');
	$('[name="modal_fullscreen"]').modal({
		allowMultiple: true,
		closable: false,
		transition: 'fly down', //slide down,'slide up','browse right',"horizontal flip",'browse','swing up','vertical flip','fly down','drop','zoom','scale'
		onHidden: function (e) {
			//reset chart jika modal
			if (chart)
				chart.destroy();
		}
	});
	$("body").on("click", '[name="modal_full_screen"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		$('[name="modal_fullscreen"]').modal('show');
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		let content = $('div[name="content_laporan"]');

		content.html('');
		// viewport width without vertical scrollbar
		var viewport_width = document.documentElement.clientWidth;
		// viewport height without horizontal scrollbar
		var viewport_height = document.documentElement.clientHeight;
		switch (tbl) {
			case 'ghant_harian':
				var options = {
					series: [
						{
							name: 'Rencana',
							data: [
								{
									x: 'Design',
									y: [
										new Date('2019-03-05').getTime(),
										new Date('2019-03-08').getTime()
									]
								},
								{
									x: 'Code',
									y: [
										new Date('2019-03-02').getTime(),
										new Date('2019-03-05').getTime()
									]
								},
								{
									x: 'Code',
									y: [
										new Date('2019-03-05').getTime(),
										new Date('2019-03-07').getTime()
									]
								},
								{
									x: 'Test',
									y: [
										new Date('2019-03-03').getTime(),
										new Date('2019-03-09').getTime()
									]
								},
								{
									x: 'Test',
									y: [
										new Date('2019-03-08').getTime(),
										new Date('2019-03-11').getTime()
									]
								},
								{
									x: 'Validation',
									y: [
										new Date('2019-03-11').getTime(),
										new Date('2019-03-16').getTime()
									]
								},
								{
									x: 'Design',
									y: [
										new Date('2019-03-01').getTime(),
										new Date('2019-03-03').getTime()
									],
								}
							]
						},
						{
							name: 'Realisasi',
							data: [
								{
									x: 'Design',
									y: [
										new Date('2019-03-02').getTime(),
										new Date('2019-03-05').getTime()
									]
								},
								{
									x: 'Test',
									y: [
										new Date('2019-03-06').getTime(),
										new Date('2019-03-16').getTime()
									],
									goals: [
										{
											name: 'Break',
											value: new Date('2019-03-10').getTime(),
											strokeColor: '#CD2F2A'
										}
									]
								},
								{
									x: 'Code',
									y: [
										new Date('2019-03-03').getTime(),
										new Date('2019-03-07').getTime()
									]
								},
								{
									x: 'Deployment',
									y: [
										new Date('2019-03-20').getTime(),
										new Date('2019-03-22').getTime()
									]
								},
								{
									x: 'Design',
									y: [
										new Date('2019-03-10').getTime(),
										new Date('2019-03-16').getTime()
									]
								}
							]
						},
						{
							name: 'Dan',
							data: [
								{
									x: 'Code',
									y: [
										new Date('2019-03-10').getTime(),
										new Date('2019-03-17').getTime()
									]
								},
								{
									x: 'Validation',
									y: [
										new Date('2019-03-05').getTime(),
										new Date('2019-03-09').getTime()
									],
									goals: [
										{
											name: 'Break',
											value: new Date('2019-03-07').getTime(),
											strokeColor: '#CD2F2A'
										}
									]
								},
							]
						}
					],
					chart: {
						height: viewport_height - 70 - 65 - 50,
						type: 'rangeBar'
					},
					plotOptions: {
						bar: {
							horizontal: true,
							barHeight: '80%'
						}
					},
					xaxis: {
						type: 'datetime'
					},
					stroke: {
						width: 1
					},
					fill: {
						type: 'solid',
						opacity: 0.6
					},
					legend: {
						position: 'top',
						horizontalAlign: 'left'
					}
				};
				chart = new ApexCharts(document.querySelector('div[name="content_laporan"]'), options);
				chart.render();
				break;
			case 'ghant_mingguan':

				break;
			case 'ghant_bulanan':
				var options = {
					series: [{
						name: 'Rencana',
						type: 'column',
						data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
					}, {
						name: 'Realisasi',
						type: 'column',
						data: [1.1, 3, 3.1, 4, 4.1, 4.9, 5, 3]
					}, {
						name: 'Tertimbang Rencana',
						type: 'line',
						data: [20, 29, 37, 36, 44, 45, 50, 58]
					}, {
						name: 'Tertimbang Realisasi',
						type: 'line',
						data: [25, 29, 37, 36, 55, 56, 70, 80]
					}],
					chart: {
						height: viewport_height - 70 - 65 - 50,
						type: 'line',
						stacked: false
					},
					dataLabels: {
						enabled: false
					},
					stroke: {
						width: [1, 1, 4]
					},
					title: {
						text: 'Time Schedule Bar Chart',
						align: 'center',
						offsetX: 110
					},
					xaxis: {
						categories: ["januari", "februari", "maret", "april", "mei", "juni", "juli", "agustus"],
					},
					yaxis: [
						{
							axisTicks: {
								show: true,
							},
							axisBorder: {
								show: true,
								color: '#008FFB'
							},
							labels: {
								style: {
									colors: '#008FFB',
								}
							},
							title: {
								text: "Income (thousand crores)",
								style: {
									color: '#008FFB',
								}
							},
							tooltip: {
								enabled: true
							}
						},
						{
							seriesName: 'Income',
							opposite: true,
							axisTicks: {
								show: true,
							},
							axisBorder: {
								show: true,
								color: '#00E396'
							},
							labels: {
								style: {
									colors: '#00E396',
								}
							},
							title: {
								text: "Operating Cashflow (thousand crores)",
								style: {
									color: '#00E396',
								}
							},
						},
						{
							seriesName: 'Revenue',
							opposite: true,
							axisTicks: {
								show: true,
							},
							axisBorder: {
								show: true,
								color: '#FEB019'
							},
							labels: {
								style: {
									colors: '#FEB019',
								},
							},
							title: {
								text: "Revenue (thousand crores)",
								style: {
									color: '#FEB019',
								}
							}
						},
					],
					tooltip: {
						fixed: {
							enabled: true,
							position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
							offsetY: 30,
							offsetX: 60
						},
					},
					legend: {
						horizontalAlign: 'left',
						offsetX: 40
					}
				};
				chart = new ApexCharts(
					document.querySelector('div[name="content_laporan"]'),
					options
				);
				chart.render();
				break;
			case 'radar':
				var options = {
					series: [{
						name: 'Rencana Fisik',
						data: [80, 50, 30, 40, 100, 20, 20],
					}, {
						name: 'Realisasi Fisik',
						data: [20, 30, 40, 80, 20, 80, 80],
					}, {
						name: 'Rencana Keuangan',
						data: [44, 76, 78, 13, 43, 10, 10],
					}, {
						name: 'Realisasi Keuangan',
						data: [15, 20, 40, 60, 30, 80, 80],
					}],
					chart: {
						height: viewport_height - 70 - 65 - 50,
						type: 'radar',
						dropShadow: {
							enabled: true,
							blur: 1,
							left: 1,
							top: 1
						}
					},
					title: {
						text: 'Radar Chart - Multi Series'
					},
					stroke: {
						width: 2
					},
					fill: {
						opacity: 0.1
					},
					markers: {
						size: 0
					},
					xaxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Mei', 'April', 'Juni', 'Juli']
					},
					dataLabels: {
						enabled: true
					}
				};

				chart = new ApexCharts(document.querySelector('div[name="content_laporan"]'), options);
				chart.render();
				break;
			case 'bobot_rencana':
				options = {
					series: [44, 55, 13, 43, 22],
					chart: {
						height: viewport_height - 70 - 65 - 50,
						type: 'pie',
					},
					labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
					responsive: [{
						breakpoint: 480,
						options: {
							chart: {
								width: 200
							},
							legend: {
								position: 'bottom'
							}
						}
					}]
				};
				chart = new ApexCharts(document.querySelector('div[name="content_laporan"]'), options);
				chart.render();
				break;
			case 'kesimpulan':
				break;
			default:
				break;
		}
	});

	//============================
	//========= cari analisa =====
	//============================
	$("body").on("click", '[name="cari_analisa"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		ini.val("");
		var tbl = ini.attr("tbl");
		let jenis = ini.attr("jns");
		var kode = "";
		var jalankanAjax = true;
		var tfootTabel = $('table[name="tabel_rab"] tfoot');
		$(".ui.cari.modal")
			.modal({
				centered: true,
				allowMultiple: true,
				//observeChanges: true,
				closable: false,
				onApprove: function () {
					if (jalankanAjax && kode.length > 2) {
						var tabel = $('table[name="tabel_rab"]');
						var url = "script/post_data";
						var dataAjax = {
							cari: "",
							rows: countRows(),
							jenis: "rab",
							tbl: "add_row",
							halaman: halaman,
							id_row: kode,
							uraian: $(".ui.search.analisa").search("get value"),
						};
						//console.log($('.ui.search.analisa').search('get selected'));
						suksesAjax["ajaxku"] = function (result) {
							if (result.success === true) {
								var hasKey = result.data.hasOwnProperty("tbody");
								if (hasKey) {
									var rows = result.data.tbody;
									if (tabel.find("tbody tr:last").length > 0) {
										tabel.find("tbody tr:last").after(rows);
									} else {
										tabel.find("tbody").html(rows);
									}
									showToast(result.error.message, {
										class: "success",
										icon: "check circle icon",
									});
								} else {
									showToast(result.error.message, {
										class: "warning",
										icon: "exclamation circle icon",
									});
								}
							} else {
								showToast(result.error.message, {
									class: "warning",
									icon: "exclamation circle icon",
								});
							}
						};
						runAjax(
							url,
							"POST",
							dataAjax,
							"Json",
							undefined,
							undefined,
							"ajaxku"
						);
					} else {
						showToast("pilih daftar Harga Satuan Pekerjaan (HSP)", {
							class: "warning",
							icon: "exclamation circle icon",
						});
					}
					return false;
				},
			})
			.modal("show");
		$(".ui.search.analisa").search({
			minCharacters: 3,
			maxResults: countRows(),
			searchDelay: 600,
			apiSettings: {
				method: "POST",
				url: "script/get_data",
				// passed via POST
				data: {
					jenis: jenis,
					tbl: tbl,
					cari: function () {
						//console.log($(this));
						return $(".ui.search.analisa").search("get value");
					},
					rows: countRows(),
					halaman: 1,
				},
			},
			fields: {
				results: [0].uraian,
				title: "uraian",
				description: "gabung",
				kode: "kode",
			},
			onSelect(result, response) {
				//console.log(result);
				//console.log(response);
				kode = result.kode;
			},
		});
	});
	//============================
	//===========DEL ROW==========
	//============================
	$("body").on("click", '[name="del_row"]', function (e) {
		e.stopImmediatePropagation();
		e.preventDefault();
		var ini = $(this);
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		var id_row = ini.attr("id_row");
		if (typeof id_row === "undefined") {
			id_row = ini.closest("tr").attr("id_row");
			if (typeof id_row === "undefined") {
				id_row = ini.closest("div.item").attr("id_row");
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
			tbl: "del_row",
			halaman: halaman,
			id_row: id_row,
		};
		switch (tbl) {
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
				data.tbl = "reset";
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
								}
								//console.log(obj);
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
								var obj = ini.closest("tr");
								if (direct) {
									obj = ini.closest('[direct="del"]');
								}
								obj.css("background-color", "#EF617C");
								obj.fadeOut(500, function () {
									obj.remove();
									if (typeof form !== "undefined") {
										//console.log(form);
										removeRulesForm(form);
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
	//==============================================
	//===========jalankan add element tabel html====
	//==============================================
	$("body").on("click", '[name="add_elm"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		var attrName = ini.attr("name");
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		let formIni = ini.closest("form"); //formIni = $('form[name="form_modal"]');
		var table = formIni.find("table");
		var elmForm2 = "";
		switch (jenis) {
			case "monev":
				switch (tbl) {
					case "addendum":
						formIni = $('form[name="monev[informasi]"]');
						let accordion = formIni.find('div[name="data_addendum"]');
						let letChild = accordion.children();
						let lengthLetChild = 1;
						if (letChild.length > 0) {
							lengthLetChild = letChild.length + 1;
						}
						let element = `<div class="field" direct="del"><label>Addendum [${lengthLetChild}]</label><div class="two fields"><div class="twelve wide field"><div class="ui action input"><input placeholder="Nomor Addendum" type="text" name="nomor[${lengthLetChild}]"><button class="ui basic icon button" name="del_row" jns="direct" tbl="del_row" direct="1"><i class="trash alternate outline red icon"></i></button></div></div><div class="four wide field"><div class="ui calendar date"><div class="ui input left icon"><i class="calendar icon"></i><input type="text" placeholder="tanggal Addendum" name="tgl[${lengthLetChild}]"></div></div></div></div></div>`;
						if (letChild.length > 0) {
							letChild.last().after(element);
						} else {
							accordion.html(element);
						}
						let calendarDate = new CalendarConstructor(".ui.calendar.date");
						calendarDate.runCalendar();
						break;
					default:
						break;
				}
			case "analisa_ck":
				break;
			default:
				break;
		}

		addRulesForm(formIni);
		$("[rms]").mathbiila();
	});
	//==========================================
	//===========jalankan add row tabel html====
	//==========================================
	$("body").on("click", '[name="add_row"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		var attrName = ini.attr("name");
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		var mdl = $('.ui.modal[name="mdl_general"]');
		mdl.addClass("large");
		let formIni = ini.closest("form"); //formIni = $('form[name="form_modal"]');
		var table = formIni.find("table");
		var elmForm2 = "";
		switch (jenis) {
			case "analisa_alat_custom":
			case "analisa_quarry":
			case "analisa_sda":
			case "analisa_bm":
			case "analisa_ck":
				let nmrHtml = '<td klm="nomor"><div contenteditable></div></td>';
				let ketHtml = '<td klm="keterangan"><div contenteditable></div></td>';
				let jumlahHtml = "";
				if (jenis === "analisa_ck") {
					nmrHtml = "";
					ketHtml = "";
					jumlahHtml = '<td klm="jumlah_harga"></td>';
				}
				elmForm2 = `<tr>${nmrHtml}<td klm="uraian"><div contenteditable></div></td><td klm="kode"><div contenteditable></div></td><td klm="satuan"><div contenteditable></div></td><td klm="koefisien"><div contenteditable rms></div></td><td klm="harga_satuan"><div contenteditable rms></div></td>${jumlahHtml}<td klm="rumus"><div contenteditable></div></td>${ketHtml}<td><div class="ui mini basic icon buttons"><button class="ui button" name="modal_2"><i class="edit icon"></i></button><button class="ui button" name="del_row" jns="direct" tbl="del_row"><i class="trash alternate outline icon"></i></button><button class="ui button up_row"><i class="angle up icon"></i></button></div></td></tr>`;
				break;
			default:
				break;
		}
		//console.log(elmForm2);
		if (formIni.find("tbody tr:last").length > 0) {
			if (jenis === "analisa_ck") {
				formIni.find("tbody tr:last").before(elmForm2);
			} else {
				formIni.find("tbody tr:last").after(elmForm2);
			}
		} else {
			formIni.find("tbody").html(elmForm2);
		}
		addRulesForm(formIni);
		$("[rms]").mathbiila();
	});
	//==========================================
	//===========jalankan modal2=================
	//==========================================
	$("body").on("click", '[name="modal_2"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		var modal_awal = ini.closest(".ui.modal");
		var attrName = ini.attr("name");
		var table = ini.closest("table");
		var indexTr = table.find("tbody tr").index($(this).closest("tr"));
		//console.log('indexTr= ' + indexTr)
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		if (typeof jenis === "undefined") {
			jenis = ini.closest("form").attr("jns");
		}
		if (typeof tbl === "undefined") {
			tbl = ini.closest("form").attr("tbl");
		}
		var mdl = $('.ui.modal[name="mdl_kedua"]');
		var td_table = ini.closest("tr").children();
		var th_table = ini.closest("table").find("thead tr").children();
		//tambahkan atribut indekxtr dan modal_awal pada modal kedua
		//console.log(JSON.stringify(jsonData));
		let formIni = $('form[name="form_modal_kedua"]');
		//mdl.attr('indextr', indexTr).attr('nama_modal_awal', modal_awal.attr('name'))
		formIni
			.attr("jns", jenis)
			.attr("tbl", tbl)
			.attr("indextr", indexTr)
			.attr("nama_modal_awal", modal_awal.attr("name"));
		//console.log(td_table);
		//console.log(th_table);
		var id_row = ini.attr("id_row");
		var url = "script/get_data";
		var jalankanAjax = false;
		var data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
			id_row: id_row,
		};
		var elementForm =
			'<div class="ui aligned grid"><div class="right floated right aligned column"><div class="ui scrolling search sbu fluid category"><div class="ui icon input"><input class="prompt" type="text" placeholder="Search..."><i class="search icon"></i></div><div class="results"></div></div></div></div>';
		let autoFokus = ""; //ini untuk mencegah search input selalu focus
		for (let index = 0; index < th_table.length - 1; index++) {
			const element_td = $(td_table[index]);
			const element_th = $(th_table[index]);
			var atributTd = element_td.attr("klm");
			var rumusTd = "";
			if (
				atributTd === "harga_satuan" ||
				atributTd === "jumlah_harga" ||
				atributTd === "koefisien"
			) {
				rumusTd = "rms";
			}
			if (index === 0) {
				autoFokus = "autofocus";
			} else {
				autoFokus = "";
			}
			elementForm += `<div class="field"><label>${element_th.text()}</label><input type="text" name='${atributTd}' placeholder='${element_th.text()}' value='${element_td.text()}' ${rumusTd} ${autoFokus}></div>`;
		}
		elementForm += '<div class="ui error message"></div>';
		//console.log(td_table);
		formIni.html(elementForm);
		//addRulesForm(formIni);tidak diperlukan rules karena data bisa kosong
		$("[rms]").mathbiila();
		$(".ui.search.sbu").search({
			minCharacters: 3,
			maxResults: countRows(),
			searchDelay: 600,
			apiSettings: {
				method: "POST",
				url: "script/get_data",
				// passed via POST
				data: {
					jenis: "sbu",
					tbl: "get",
					cari: function (value) {
						//console.log($(this));
						return $(".ui.search.sbu").search("get value");
					},
					rows: countRows(),
					halaman: 1,
				},
			},
			fields: {
				results: [0].uraian,
				title: "uraian",
				description: "gabung",
			},
			onSelect(result, response) {
				var formInayah = $(this).closest("form");
				//console.log(result);// yang diklik
				//console.log(response);//semua hasil
				Object.keys(result).forEach((key) => {
					const fieldCari = formInayah.find('input[name="' + key + '"]');
					//console.log(key); //semua hasil
					//console.log(result[key]); //semua hasil
					if (fieldCari.length > 0) {
						var hasilAttr = result[key];
						if (key === "harga_satuan" || key === "jumlah_harga") {
							//accounting.unformat(value[5], ",")
							var tenporer1 = parseFloat(hasilAttr);
							hasilAttr = accounting.formatNumber(
								result[key],
								tenporer1.countDecimals(),
								".",
								","
							);
						}
						formInayah.form("set value", key, hasilAttr);
						/*
							formInayah.form('set values', {
								[key]: hasilAttr//JavaScript set object key by variable dengan [key]
							});
							*/
					}
				});
			},
		});
		mdl.modal("show");
	});
	//=========================================
	//===========jalankan modal================
	//=========================================
	$("body").on("click", '[name="modal_show"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		var attrName = ini.attr("name");
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		var mdl = $('.ui.modal[name="mdl_general"]');
		mdl.addClass("large");
		//ubah kop header modal
		var elmIkonModal = $(mdl.find(".big.icons i")[0]); //ganti class icon
		var elmIkonModal2 = $(mdl.find(".big.icons i")[1]); //ganti class icon
		var elmKontenModal = mdl.find("h5 .content");
		let formIni = $('form[name="form_modal"]');
		var id_row = ini.attr("id_row");
		formIni.attr("id_row", id_row);
		var url = "script/get_data";
		var jalankanAjax = false;
		var data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
			id_row: id_row,
		};
		if (attrName === "modal_show") {
			formIni.attr("tbl", tbl).attr("jns", jenis);
			jalankanAjax = true;
		}
		if (id_row > 0) {
			data.id_row = id_row;
			formIni.attr("id_row", id_row);
		}
		let elmIkon = "puzzle icon";
		let elmKonten = 'AHSP <div class="sub header">Data</div>';
		var bidang = "binamarga";
		var elmIkon2 = "bottom right teal corner add icon";
		switch (jenis) {
			case "analisa_alat_custom":
				elmIkon = "snowplow icon";
				bidang = "peralatan custom";
				break;
			case "analisa_quarry":
				bidang = "Analisa Quarry";
				elmIkon = "truck monster icon";
				break;
			case "analisa_bm":
				bidang = "Binamarga";
				elmIkon = "road icon";
				break;
			case "analisa_sda":
				bidang = "Sumber Daya Air";
				elmIkon = "water icon";
				break;
			case "analisa_ck":
				bidang = "Cipta Karya";
				elmIkon = "city icon";
				break;
			case "analisa_alat":
				bidang = "Peralatan";
				elmIkon = "snowplow icon";
				break;
			default:
				break;
		}
		var topKontent = "";
		var kontent = "";
		switch (jenis) {
			case "proyek":
				switch (tbl) {
					case "cek_kode":
						break;
					case "edit":
						break;
					default:
						break;
				}
				break;
			case "analisa_alat_custom":
			case "analisa_quarry":
			case "analisa_bm":
			case "analisa_sda":
			case "analisa_ck":
				let nmrHtml = "<th>NO.</th>";
				let ketHtml = "<th>KET</th>";
				let headerRumus = "RUMUS KOEF.";
				let jumlahHtml = "";
				if (jenis === "analisa_ck") {
					ketHtml = "";
					nmrHtml = "";
					headerRumus = "RUMUS HARGA SAT.";
					jumlahHtml = "<th>JUMLAH</th>";
				}
				switch (tbl) {
					case "edit":
						elmIkon2 = "bottom right teal corner edit icon";
						elmKonten = `${bidang} <div class="sub header">Edit</div>`;
						//mengambil text dan membagi/split dari text menjadi array
						var sell = ini.closest(".item").find(".header").text().split(":");
						kontent =
							'<div class="field"><div class="fields"><div class="four wide field"><label>Kode</label><input type="text" readonly name="kd_analisa" value="' +
							sell[0] +
							'" autofocus></div><div class="twelve wide field"><label>Uraian</label><input type="text" name="jenis_pek" value="' +
							sell[1] +
							'"></div></div></div>';
						if (jenis === "analisa_quarry") {
							kontent = `<div class="fields"><div class="three wide field"><label>Kode</label><input type="text" name="kd_analisa" value="${sell[0]}" readonly></div><div class="thirteen wide field"><label>Uraian Bahan Dasar</label><input type="text" name="jenis_pek" value="${sell[1]}" readonly></div></div><div class="two fields"><div class="field"><label>Lokasi</label><input type="text" name="lokasi"></div><div class="field"><label>Tujuan</label><input type="text" name="tujuan"></div></div>`;
						}
						break;
					case "input":
						elmKonten = `${bidang} <div class="sub header">Input</div>`;
						elmIkon2 = "bottom right teal corner add icon";
						//mengambil text dan membagi/split dari text menjadi array
						var sell = ini.closest(".item").find(".header").text().split(":");
						switch (jenis) {
							case "analisa_alat_custom":
								topKontent = `<div class="fields"><div class="three wide field"><label>Kode Peralatan</label><input type="text" name="kd_analisa" autofocus></div><div class="thirteen wide field"><label>Nama Peralatan</label><input type="text" name="jenis_pek"></div></div>`;
								break;
							case "analisa_quarry":
								topKontent = `<div class="fields"><div class="three wide field"><label>Kode</label><input type="text" name="kd_analisa"></div><div class="thirteen wide field"><label>Uraian Bahan Dasar</label><input type="text" name="jenis_pek"></div></div><div class="two fields"><div class="field"><label>Lokasi</label><input type="text" name="lokasi"></div><div class="field"><label>Tujuan</label><input type="text" name="tujuan"></div></div>`;
								break;
							case "analisa_bm":
							case "analisa_sda":
							case "analisa_ck":
								topKontent = `<div class="field"><div class="fields"><div class="three wide field"><label>Item Pembayaran No.</label><input type="text" name="kd_analisa"></div><div class="ten wide field"><label>Jenis Pekerjaan</label><input type="text" name="jenis_pek"></div><div class="three wide field"><label>Satuan pembayaran</label><input type="text" name="satuan_pembayaran"></div></div></div>`;
								break;
							default:
								break;
						}
						kontent = `${topKontent}<div class="ui scrolling container"><table class="ui first head foot stuck unstackable mini compact celled table" tbl="input" jns="${jenis}"><thead><tr>${nmrHtml}<th class="five wide">URAIAN</th><th>KODE</th><th>SATUAN</th><th>KOEF.</th><th>HARGA SATUAN</th>${jumlahHtml}<th>${headerRumus}</th>${ketHtml}<th class="center aligned collapsing"><div class="ui mini basic icon buttons"><button class="ui button" name="add_row" jns="${jenis}" tbl="add_row_tabel"><i class="plus icon"></i></button><label jns="${jenis}" tbl="add_row_tabel" for="invisibleupload1" class="ui button"><i class="excel green file outline icon"></i></label></div></th></tr></thead><tbody></tbody><tfoot><tr><th class="right aligned" colspan="9"></th></tr></tfoot></table></div><div class="ui error message">`;
						formIni.html(kontent);
						jalankanAjax = false;
						mdl.modal("show");
						break;
					default:
						break;
				}
				break;
			case "analisa_alat":
				switch (tbl) {
					case "input":
						elmKonten = `${bidang} <div class="sub header">Input</div>`;
						elmIkon2 = "bottom right teal corner add icon";
						break;
					case "edit":
						elmKonten = `${bidang} <div class="sub header">Edit</div>`;
						elmIkon2 = "bottom right teal corner edit icon";
						break;
					default:
						break;
				}
				break;
			case "x":
				switch (tbl) {
					case "y":
						break;
					default:
						break;
				}
				break;
			default:
				break;
		}
		elmIkonModal.attr("class", elmIkon);
		elmIkonModal2.attr("class", elmIkon2);
		elmKontenModal.html(elmKonten);
		if (jalankanAjax) {
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					switch (jenis) {
						case "proyek":
							switch (tbl) {
								case "cek_kode":
									showToast(result.error.message, {
										class: "success",
										icon: "check circle icon",
									});
									break;
								case "tambah_proyek":
									break;
								case "edit":
									showToast(result.error.message, {
										class: "success",
										icon: "check circle icon",
									});
									formIni.form("set values", {
										kode: result.data.kd_proyek,
										uraian: result.data.nama_proyek,
										tahun: result.data.tahun_anggaran,
										keterangan: result.data.keterangan,
									});
									addRulesForm(formIni);
									break;
								default:
									break;
							}
							break;
						case "analisa_alat":
							switch (tbl) {
								case "edit":
									formIni.html(result.data.tbody + "</tbody>");
									formIni.find("tbody").after(result.data.tfoot);
									break;
								case "z":
									break;
								default:
									break;
							}
							break;
						case "analisa_alat_custom":
						case "analisa_quarry":
						case "analisa_bm":
						case "analisa_ck":
						case "analisa_sda":
							switch (tbl) {
								case "edit":
									formIni.html(
										kontent +
										result.data.tbody +
										"</tbody><tfoot>" +
										result.data.tfoot +
										"</tfoot>"
									);
									if (jenis === "analisa_quarry") {
										let row_analisa = result.data.row_analisa;
										//console.log(row_analisa);
										var hasKey = result.data.row_analisa.hasOwnProperty("keterangan");
										var keteranganRow_analisa = '';
										var lokasi = '';
										var tujuan = '';
										if (hasKey) {
											keteranganRow_analisa = JSON.parse(
												result.data.row_analisa.keterangan
											);
											lokasi = keteranganRow_analisa.lokasi;
											tujuan = keteranganRow_analisa.tujuan;
										}


										formIni.form("set values", {
											kd_analisa: row_analisa.kd_analisa,
											jenis_pek: row_analisa.uraian,
											lokasi: lokasi,
											tujuan: tujuan,
										});
									}
									break;
								case "z":
									break;
								default:
									break;
							}
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
							break;
					}
					$("[rms]").mathbiila();
					if (attrName === "modal_show") {
						mdl.modal("show");
					}
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		}
		//mdl.modal('show');
	});
	//MODALmodal mdl_general
	$(".ui.modal.mdl_general").modal({
		allowMultiple: true,
		//observeChanges: true,
		closable: false,
		transition: "vertical flip", //slide down,'slide up','browse right','browse','swing up','vertical flip','fly down','drop','zoom','scale'
		onDeny: function () {
			//return false;//console.log('saya menekan tombol cancel');
		},
		onApprove: function () {
			// jika di tekan yes//console.log('saya menekan tombol YES');
			$(this).find("form").trigger("submit");
			return false;
		},
		onShow: function () {
			/*$(this).find('table tbody').sortable({
				revert: true
			});*/
		},
	});
	//MODALmodal mdl_general
	$(".ui.modal.kedua").modal({
		allowMultiple: true,
		//observeChanges: true,
		closable: false,
		transition: "slide down", //slide down,'slide up','browse right','browse','swing up','vertical flip','fly down','drop','zoom','scale'
		onDeny: function () {
			//return false;//console.log('saya menekan tombol cancel');
		},
		onApprove: function () {
			// jika di tekan yes//console.log('saya menekan tombol YES');
			$(this).find("form").trigger("submit");
			return false;
		},
	});

	//=====================================================
	//===========efek transisi klik message=================
	//=====================================================
	$("body").on("click", ".message.goyang", function (e) {
		e.preventDefault();
		$(this).transition({
			animation: "jiggle",
			duration: 500,
			interval: 200,
		});
	});
	//=====================================================
	//===========ungguh xlsx=================
	//=====================================================
	$("body").on("click", '[name="ungguh"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		var tbl = ini.attr("tbl");
		var jns = ini.attr("jns");
		var url = "script/writer_xlsx";
		var data = {
			jenis: jns,
			tbl: tbl,
		};
		var jalankanAjax = true;
		switch (jns) {
			case 'monev':
				switch (tbl) {
					case 'lap_periodik':
						var calendarUi = $('form[name="form_range_date"] .ui.calendar[id]');
						calendarUi.map(function (key, property) {
							let nameAttr = $(property).find("[name]").attr("name");
							let tanggal = $(property).calendar("get date");
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
								data[nameAttr] = tanggal;
							} else {
								let placeholderAttr = $(property).find('input[name]').attr("placeholder");
								if (placeholderAttr) {
									showToast(`${placeholderAttr} tidak boleh kosong`, {
										class: "warning",
										icon: "exclamation circle icon",
									});
								}

								jalankanAjax = false;
							}
						});
						break;
					default:
						break;
				}
				break;
			default:
				break;
		}
		if (jalankanAjax) {
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					showToast(result.error.message, {
						class: "success",
						icon: "check circle icon",
					});
					$('[name="tempat_download"]').attr("href", result.data.filename);
					$('[name="tempat_download"]')[0].click(); //mengklik link gunakan [0]
				} else {
					showToast(result.error.message, {
						class: "error",
						icon: "check circle icon",
					});
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		}
	});
	//
	/*
	function satuanDropdown() {
		$(".satuan.ui.dropdown").dropdown({
			apiSettings: {
				// this url just returns a list of tags (with API response expected above)
				method: "POST",
				url: "script/get_data",
				//throttle: 1000,//delay perintah
				// passed via POST
				data: {
					jenis: "satuan",
					tbl: "get",
					cari: function (value) {
						return $(".satuan.ui.dropdown").dropdown("get query");
						//console.log($('.satuan.ui.dropdown').dropdown('get query'));
					},
					rows: "all",
					halaman: 1,
				},
				fields: {
					//results: results,
					value: "value",
					text: "name",
					description: "value",
				},
				filterRemoteData: true,
			},
			filterRemoteData: true,
			saveRemoteData: false
		}); //.dropdown('set selected', 'jam');
	}*/
	//===================================
	//=========== class calendar ========
	//===================================
	class CalendarConstructor {
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
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		constructor(element) {
			this.element = $(element);//element;
		}
		satuan(get) {
			get = this.element.dropdown("get query");
			this.element.dropdown({//$(".satuan.ui.dropdown").dropdown({
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					method: "POST",
					url: "script/get_data",
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
					},
					fields: {
						//results: results,
						value: "value",
						text: "name",
						description: "value"

					},
					filterRemoteData: true,
				},
				filterRemoteData: true,
				saveRemoteData: false
			});
		}
		setVal(val) {
			//this.element.dropdown('preventChangeTrigger', true);
			this.element.dropdown('set selected', val);
		}
	}
	//
	$("body").on("click", '[name="ungguh2"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		var tbl = ini.attr("tbl");
		var jns = ini.attr("jns");
		$('form[name="download_file_xls"]').form("set values", {
			jenis: jns,
			tbl: tbl,
		});
		$('form[name="download_file_xls"]').trigger("submit");
	});

	//=======================================
	//===============FORM GLOBAL=============
	//=======================================
	$(".ui.form").form({
		onSuccess: function (e) {
			e.preventDefault();
			console.log("masuk form global");
			var jalankanAjax = false;
			var ini = $(this);
			var tbl = ini.attr("tbl");
			var dataType = "Json";
			let jenis = ini.attr("jns");
			var nama_form = ini.attr("name");
			//loaderShow();
			var formData = new FormData(this);
			formData.append("jenis", jenis);
			formData.append("tbl", tbl);
			var url = "script/post_data";
			formData.set('cari', cari(jenis));
			formData.set('rows', countRows());
			var id_row = ini.attr("id_row");
			if (typeof id_row === "undefined") {
				id_row = ini.closest("tr").attr("id_row");
				if (typeof id_row === "undefined") {
					id_row = ini.closest("div.item").attr("id_row");
				}
			}
			if (typeof id_row !== "undefined") {
				formData.set('id_row', id_row);
			}
			//tampilkan form data
			formData.forEach((value, key) => {
				console.log(key + " " + value)
			});
			switch (nama_form) {
				// ===========================
				// UNTUK FORM form_range_date
				// ===========================
				case "form_range_date":
					var calendarUi = ini.find(".ui.calendar");
					calendarUi.map(function (key, property) {
						let nameAttr = $(property).find("[name]").attr("name");
						let tanggal = $(property).calendar("get date");
						//console.log(tanggal);
						if (tanggal) {
							tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
							formData.set(nameAttr, tanggal);
						}
					});

					url = "script/get_data";
					jalankanAjax = true;
					//console.log(formData)
					break;
				// ===========================
				// UNTUK FORM monev[informasi]
				// ===========================
				case "monev[informasi]":
					formData.set("jenis", "monev[informasi]");
					formData.set("tbl", "edit");
					formData.set(
						"nilai_kontrak",
						accounting.unformat(formData.get("nilai_kontrak"), ",")
					);
					let addendum = ini.find('div[name="data_addendum"]').children();
					calendarUi = ini.find(".ui.calendar");
					//console.log(calendarUi);
					if (typeof addendum !== "undefined") {
						let baris = {};
						calendarUi.map(function (key, property) {
							let nameAttr = $(property).find("[name]").attr("name");
							let tanggal = $(property).calendar("get date");
							//console.log(tanggal);
							if (tanggal) {
								tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
								formData.set(nameAttr, tanggal);
							}
						});
						addendum.map(function (key, property) {
							//console.log(key, property);
							baris[key] = {};
							baris[key].nomor = $(this).find(`input[name^="nomor"]`).val();
							let tanggal = $(this)
								.find(`.ui.calendar`)
								.calendar("get date")[0];
							tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
							//tanggal = `${tanggal.getUTCFullYear()}-${tanggal.getUTCMonth() + 1}-${tanggal.getUTCDate()}`;
							baris[key].tgl = tanggal;
						});
						formData.append("dataArray", JSON.stringify(baris));
					}
					jalankanAjax = true;
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
							jalankanAjax = true;
							break;
						default:
							break;
					}
					break;
				// =================
				// UNTUK FORM MODAL
				// =================
				case "form_modal":
					var tbody_tbl = ini.find("tbody");
					//console.log(tbody_tbl);
					var tr = tbody_tbl.children();
					//console.log(tr);
					var data_row = new Object();
					switch (jenis) {
						case "analisa_alat_custom":
						case "analisa_quarry":
						case "analisa_sda":
						case "analisa_bm":
						case "analisa_ck":
							formData.append("id_row", id_row);
							var rowCount = 0;
							var no_id = "";
							let nmrRandom = 250909 + Math.floor(Math.random() * 1001);
							//console.log('element tr = ');
							//console.log(tr);
							tr.each(function (ii, elm) {
								switch (tbl) {
									case "edit":
										break;
									case "input":
										break;
									default:
										break;
								}
								no_id = $(this).attr("id_row");
								if (typeof no_id === "undefined") {
									// jika id row undefined
									no_id = nmrRandom + rowCount + "n";
								}
								var elementTD = elm.children;
								//var no_idKu = 'a'+no_id//sortir kembali di php menurut no_sortir karena index int tdk bisa di sortir
								data_row[no_id] = {};
								console.log('elementTD = ' + no_id);
								//console.log(elementTD);
								Object.keys(elementTD).forEach((key) => {
									var element = $(elementTD[key]);
									var nama_kolom = element.attr("klm");
									if (typeof nama_kolom !== "undefined") {
										var txt = element.text().trim();
										let tryNumbers = txt.replaceAll(".", "");
										tryNumbers = tryNumbers.replaceAll(",", "");
										//console.log(tryNumbers);
										if (
											containsOnlyNumbers(tryNumbers) ||
											nama_kolom === "harga_satuan" ||
											nama_kolom === "koefisien"
										) {
											data_row[no_id][nama_kolom] = accounting.unformat(
												txt,
												","
											)
												? accounting.unformat(txt, ",")
												: 0;
											//console.log(`${nama_kolom} : ${containsOnlyNumbers(tryNumbers)} hasil =  ${data_row[no_id][nama_kolom]}`);
										} else {
											data_row[no_id][nama_kolom] = txt;
										}
									}
								});
								rowCount++;
								data_row[no_id]["no_sortir"] = rowCount;
								//console.log(data_row);
							});
							//var data_row= data_row.sort( compare );
							//console.log(data_row);
							break;
						case "analisa_alat":
							tr.children("td").each(function () {
								var nama_kolom = $(this).attr("klm");
								if (typeof nama_kolom !== "undefined") {
									//console.log(nama_kolom);
									//console.log($(this).text().trim());
									data_row[nama_kolom] = accounting.unformat(
										$(this).text().trim(),
										","
									);
								}
							});
							//console.log(data_row);
							//tambahkan id pada data
							formData.append("id", ini.attr("id_row"));
							break;
						case "y":
							break;
						case "z":
							break;
					}
					formData.append("dataArray", JSON.stringify(data_row));
					jalankanAjax = true;
					break;
				// =================
				// UNTUK FORM FLYOUT
				// =================
				case "form_flyout":
					switch (jenis) {
						case 'peraturan':
							switch (tbl) {
								case 'edit':
								case 'input':
									let property = ini.find(".ui.calendar.date");
									let nameAttr = $(property).find("[name]").attr("name");
									let tanggal = $(property).first().calendar("get date");
									console.log(tanggal);
									if (tanggal) {
										tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
										formData.set(nameAttr, tanggal);
									}
									jalankanAjax = true;
									
									break;
								default:
									break;
							}
							break;
						case 'rekanan':
							switch (tbl) {
								case 'edit':
								case 'input':
									let property = ini.find(".ui.calendar.date");
									let nameAttr = $(property).find("[name]").attr("name");
									let tanggal = $(property).first().calendar("get date");
									console.log(tanggal);
									if (tanggal) {
										tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
										formData.set(nameAttr, tanggal);
									}
									jalankanAjax = true;
									let obj = ini.find('[name="notaris_perubahan"]').extractObject('name');
									formData.set('notaris_perubahan', JSON.stringify(obj));
									obj = ini.find('[name="data_lain"]').extractObject('name');
									formData.set('data_lain', JSON.stringify(obj));
									console.log(obj);
									break;
								default:
									break;
							}
							break;
						case "copy":
							switch (tbl) {
								case "analisa_alat":
								case "analisa_bm":
								case "analisa_ck":
								case "analisa_sda":
								case "analisa_quarry":
								case "analisa_alat_custom":
									jalankanAjax = true;
									break;
								case 'copy_lap_harian':
									let tanggal = $(ini.find('[name="tanggal_copy"]').closest('.ui.calendar.laporan')).calendar("get date");
									tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;
									formData.set('tanggal_copy', tanggal);
									tanggal = $(ini.find('[name="tanggal"]').closest('.ui.calendar.laporan')).calendar("get date");
									tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
									formData.set('tanggal', tanggal);
									jalankanAjax = true;
									break;
								case 'proyek':
									//jika toogle tidak on maka tambahahkan data  di form data sebagai toggle off
									formData.has("aktifkan_proyek") === false
										? formData.append("aktifkan_proyek", "off")
										: console.log("del"); // Returns false
									formData.has("copy_realisasi_proyek") === false
										? formData.append("copy_realisasi_proyek", "off")
										: console.log("del"); // Returns false
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "monev": //input realisasi harian
							switch (tbl) {
								case 'import':
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								case 'lap-harian_edit':
								case 'lap-harian':// input baru laporan harian
									let typeLapHarian = formData.get('type');
									switch (typeLapHarian) {
										case 'upah':
											formData.set('value-jumlah', accounting.unformat(parseFloat(formData.get('value-jumlah')), ","));
											break;
										case 'bahan':
											formData.set('value-diterima', accounting.unformat(parseFloat(formData.get('value-diterima')), ","));
											formData.set('value-ditolak', accounting.unformat(parseFloat(formData.get('value-ditolak')), ","));
											break;
										case 'peralatan':
											formData.set('value-diterima', accounting.unformat(parseFloat(formData.get('value-diterima')), ","));
											formData.set('value-ditolak', accounting.unformat(parseFloat(formData.get('value-ditolak')), ","));
											break;
										default:
											break;
									}
									break;
								default:
									let calendarUi = ini.find(".ui.calendar");
									calendarUi.map(function (key, property) {
										let nameAttr = $(property).find("[name]").attr("name");
										let tanggal = $(property).calendar("get date");
										//console.log(tanggal);
										if (tanggal) {
											tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
											formData.set(nameAttr, tanggal);
										}
									});
									//ubah accounting realisasi fisik dan keuangan
									formData.set(
										"realisasi_fisik",
										accounting.unformat(
											ini.form("get value", "realisasi_fisik"),
											","
										)
									);
									formData.set(
										"realisasi_keu",
										accounting.unformat(ini.form("get value", "realisasi_keu"), ",")
									);
									break;
							}

							jalankanAjax = true;
							break;
						case "rab":
							switch (tbl) {
								case "add_row":
									formData.set(
										"volume",
										Number.parseFloat(
											accounting.unformat(formData.get("volume"), ",")
										)
									);
									jalankanAjax = true;
									break;
								case "edit":
									var tabel = $('table[name="tabel_rab"]');
									//var id_row = ini.attr('id_row');
									formData.set(
										"volume",
										Number.parseFloat(
											accounting.unformat(formData.get("volume"), ",")
										)
									);
									formData.append("id_row", id_row);
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "schedule":
							switch (tbl) {
								case "edit":
									var tabel = $('table[name="tabel_schedule"]');
									//var id_row = ini.attr('id_row');
									formData.append("id_row", id_row);
									jalankanAjax = true;
									formData.set(
										"mulai",
										Number.parseFloat(
											accounting.unformat(formData.get("mulai"), ",")
										)
									);
									formData.set(
										"bobot_selesai",
										Number.parseFloat(
											accounting.unformat(formData.get("bobot_selesai"), ",")
										)
									);
									formData.set(
										"durasi",
										Number.parseFloat(
											accounting.unformat(formData.get("durasi"), ",")
										)
									);
									break;
								default:
									break;
							}
							break;
						case "sbu": //add row rab
							switch (tbl) {
								case "ck":
								case "bm":
								case "sda":
									formData.set(
										"volume",
										Number.parseFloat(
											accounting.unformat(formData.get("volume"), ",")
										)
									);
									var tabel = $('table[name="tabel_rab"]');
									formData.set("id_row", ini.form("get value", "kode"));
									formData.set("tbl", "add_row");
									formData.set("jenis", "rab");
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "proyek":
							switch (tbl) {
								case "edit":
								case "tambah_proyek":
									jalankanAjax = true;
									//jika toogle tidak on maka tambahahkan data  di form data sebagai toggle off
									formData.has("aktifkan_proyek") === false
										? formData.append("aktifkan_proyek", "off")
										: console.log("del"); // Returns false
									break;
								case "c":
									break;
								default:
									break;
							}
							break;
						case "harga_satuan":
							switch (tbl) {
								case "input":
								//formData.set('harga_satuan', accounting.formatNumber(formData.get('harga_satuan'), 6, '.', ','));//update formdata
								//Number.parseFloat(accounting.unformat(formData.get('harga_satuan'), ","))
								case "edit":
									formData.set(
										"harga_satuan",
										Number.parseFloat(
											accounting.unformat(formData.get("harga_satuan"), ",")
										)
									); //update formdata
									jalankanAjax = true;
									formData.append("id_row", id_row);
									break;
								case "import_basic_price":
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "divisi":
							switch (tbl) {
								case "input":
								//formData.set('harga_satuan', accounting.formatNumber(formData.get('harga_satuan'), 6, '.', ','));//update formdata
								//Number.parseFloat(accounting.unformat(formData.get('harga_satuan'), ","))
								case "edit":
									break;
								case "import":
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "satuan":
							switch (tbl) {
								case "import_satuan":
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								case "edit":
									formData.append("id_row", id_row);
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "analisa_alat":
							switch (tbl) {
								case "import_alat":
									url = "script/impor_xlsx";
									break;
								case "edit":
									url = "script/post_data";
									break;
								default:
									break;
							}
							jalankanAjax = true;
							break;
						case "analisa_alat_custom":
							switch (tbl) {
								case "import_alat":
									url = "script/impor_xlsx";
									break;
								case "edit":
									url = "script/post_data";
									break;
								default:
									break;
							}
							jalankanAjax = true;
							break;
						case "analisa_quarry":
							switch (tbl) {
								case "import_quarry":
									url = "script/impor_xlsx";
									break;
								case "edit":
									url = "script/post_data";
									break;
								default:
									break;
							}
							jalankanAjax = true;
							break;
						case "analisa_sda":
							switch (tbl) {
								case "import_sda":
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "analisa_bm":
							switch (tbl) {
								case "import_bm":
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "analisa_ck":
							switch (tbl) {
								case "import_ck":
									url = "script/impor_xlsx";
									jalankanAjax = true;
									break;
								default:
									break;
							}
							break;
						case "profil":
							jalankanAjax = true;
							break;
						case "informasi_umum":
							switch (tbl) {
								case "edit":
								case "input":
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
				// =================
				// UNTUK MODAL 2====
				// =================
				case "form_modal_kedua":
					var atributFormAwal = ini.attr("nama_modal_awal");
					var mdlTujuan = $('div[name="' + atributFormAwal + '"');
					var formTujuan = mdlTujuan.find("form");
					var dataForm = ini.form("get values");
					var tbodyFormAwal = formTujuan.find("table tbody");
					var indexTr = ini.attr("indextr");
					var trTable = tbodyFormAwal.children();
					//console.log(trTable.eq(indexTr));
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
					switch (jenis) {
						case "analisa_bm":
							break;
						default:
							break;
					}
					break;
				// =================
				// UNTUK PROFIL====
				// =================
				case "profil":
					//[name="ket"]
					formData.set('ket', $('textarea[name="ket"]').val());
					jalankanAjax = true;
					break;
				default:
					break;
			}
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
						let jenisTrigger = '';//jenisTrigger = jenis;
						switch (nama_form) {
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
										jenisTrigger = jenis;
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
								switch (jenis) {
									case 'peraturan':
										switch (tbl) {
											case 'edit':
											case 'input':
												jenisTrigger = jenis;
												break;
											default:
												break;
										}
										break;
									case 'rekanan':
										switch (tbl) {
											case 'edit':
											case 'input':
												jenisTrigger = jenis;
												break;
											default:
												break;
										}
										break;
									case "copy":
										switch (tbl) {
											case "analisa_alat":
											case "analisa_bm":
											case "analisa_ck":
											case "analisa_sda":
											case "analisa_quarry":
											case "analisa_alat_custom":
												jenisTrigger = tbl;
												break;
											case 'copy_lap_harian':
												break;
											case 'proyek':
												break;
											default:
												break;
										}
										break;
									case "monev":
										switch (tbl) {
											case 'lap-harian_edit':
												jenisTrigger = "lap-harian";
												break;
											default:
												break;
										}
										break;
									case "informasi_umum":
										switch (tbl) {
											case "edit":
											case "input":
												if (result.error.code === 2) {
													jenisTrigger = jenis;
												};
												break;
											default:
												break;
										}
										break;
									case "rab":
										switch (tbl) {
											case "add_row":
												break;
											case "edit":
												if (result.error.code === 3) {
													tabel = $('table[name="tabel_rab"]');
													let tdTable = tabel.find(`tr[id_row="${id_row}"] td`);
													tdTable.each(function () {
														let atributTd = $(this).attr("klm");
														if (typeof atributTd !== "undefined") {
															let nilaitTd = result.data.rows[atributTd];
															switch (atributTd) {
																case "volume":
																case "jumlah_harga":
																case "harga_satuan":
																	let jumlahDesimal = nilaitTd.countDecimals();
																	nilaitTd = accounting.formatNumber(
																		nilaitTd,
																		jumlahDesimal,
																		".",
																		","
																	);
																	break;
																default:
																	break;
															}
															if ($(this).children().length > 0) {
																$(this).children().text(nilaitTd);
															} else {
																$(this).text(nilaitTd);
															}
														}
													});
													//tambahkan jumlah analisa do tfoot table
													var hasKey = result.data.hasOwnProperty("sum");
													if (hasKey) {
														tabel
															.find("tfoot")
															.html(
																`<tr><td colspan="5"><td>${accounting.formatNumber(
																	result.data.sum,
																	2,
																	".",
																	","
																)}</td></td><td colspan="2"></td></tr>`
															);
													}
												}
												break;
											default:
												break;
										}
										break;
									case "schedule":
										if (result.error.code === 3) {
											jenisTrigger = jenis;
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
									case "proyek":
										switch (tbl) {
											case "edit":
											case "tambah_proyek":
												jenisTrigger = jenis;
												break;
											case "c":
												break;
											default:
												break;
										}
										break;
									case "harga_satuan":
										switch (tbl) {
											case "import":
											case "import_basic_price":
												jenisTrigger = jenis;
												break;
											default:
												break;
										}
										break;
									case "satuan":
										switch (tbl) {
											case "import_satuan":
											case "import":
												jenisTrigger = jenis;
												//$(`[data-tab="${jenisTrigger}"]`).trigger("click");
												break;
											case "edit":
												break;
											default:
												break;
										}
										break;
									case "analisa_alat_custom":
										jenisTrigger = "analisa_alat";
										break;
									case "analisa_ck":
									case "analisa_bm":
									case "analisa_sda":
									case "analisa_quarry":
									case "analisa_alat":
										jenisTrigger = jenis;
										//console.log(jenisTrigger);
										break;
									case "profil":
										switch (tbl) {
											case "edit":
												jenisTrigger = 'user';
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
								const warna = ['red', 'orange', 'yellow', 'olive', 'green', 'teal', 'blue', 'violet', 'purple', 'pink', 'brown', 'grey', 'black'];
								let warna_tbl = formData.get('warna_tbl');
								//ambil seluruh row thead dan row tfoot
								const elmRow = $('table tr');//$('table tfoot tr, table thead tr');
								console.log(elmRow)
								warna.forEach((value, key) => {
									elmRow.removeClass(value);
									$('table').removeClass('inverted');
								})
								if (warna_tbl !== 'non') {
									elmRow.addClass(warna_tbl);
									$('table').addClass('inverted');
								}
								break;
							default:
								break;
						}
						console.log(jenisTrigger);

						if (jenisTrigger.length > 0) {
							$(`a[data-tab="${jenisTrigger}"]`).trigger("click");
						}
						$("[rms]").mathbiila();
					} else {
						kelasToast = "warning"; //'success'
					}
					showToast(result.error.message, {
						class: kelasToast,
						icon: "check circle icon",
					});
				};
				runAjax(url, "POST", formData, dataType, false, false, "ajaxku");
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
	$('body').on('click', '.animasi.button', function () {
		$(this).closest('.ui.shape')
			//.shape('set next side', '.second.side')
			.shape('flip right');
	});
	//var mencegah fungsi dipanggil berulangkali
	var calledLokasi = false;
	//=====================
	//======DATA TAB=======
	//=====================
	$("body").on("click", "a[data-tab]", function (e) {
		e.preventDefault();
		tok($(this));
		var arrayDasboard = {
			home: ["home icon", "DASHBOARD", "AHSP Pekerjaan Konstruksi"],
			proyek: ["clipboard list icon", "Pekerjaan Konstruksi", "Tabulasi Dokumen"],
			rab: [
				"money icon",
				"Daftar Kuantitas dan Harga",
				"Bill Of Quantity, BoQ",
			],
			harga_satuan: [
				"clipboard list icon",
				"Harga Satuan",
				"Satuan yang digunakan",
			],
			satuan: ["layer group list icon", "Satuan", "Satuan yang digunakan"],
			profil: ["user icon", "Profil", "Pengaturan dan gambaran singkat User"],
			informasi_umum: [
				"sitemap icon",
				"Informasi Umum",
				"Pengaturan dan gambaran umum proyek",
			],
			analisa_alat: [
				"snowplow icon",
				"Analisa Peralatan",
				"pengaturan alat berat",
			],
			analisa_quarry: [
				"truck monster icon",
				"Analisa Quarry",
				"analisa harga dasar satuan bahan",
			],
			analisa_bm: [
				"road icon",
				"Bidang Binamarga",
				"analisa harga satuan pekerjaan (AHSP)",
			],
			analisa_ck: [
				"city icon",
				"Bidang Cipta Karya dan Perumahan",
				"analisa harga satuan pekerjaan (AHSP)",
			],
			schedule: ["bar chart icon", "Waktu Pelaksanaan", "time schedule"],
			reset: ["red table icon", "Reset Tabel", "menghapus seluruh data tabel"],
			peraturan: ["table icon", "Peraturan", "peraturan yang digunakan"],
			divisi: ["outdent icon", "Divisi AHSP", "pembagian divisi"],
			divisiBM: ["outdent icon", "Divisi AHSP", "Bina Marga"],
			divisiCK: ["outdent icon", "Divisi AHSP", "Cipta Karya"],
			divisiSDA: ["outdent icon", "Divisi AHSP", "Sumber Daya Air"],
			lokasi: ["map icon", "Lokasi", "Lokasi yang digunakan"],
			template: ["download icon", "Template", "Ungguh Contoh Template AHSP"],
			user: ["users icon", "Users AHSP", "Akun user ahsp"],
			analisa_sda: [
				"blue water icon",
				"Bidang Sumber Daya Air",
				"analisa harga satuan pekerjaan (AHSP)",
			],
			wallchat: ["comments outline icon", "AHSP chat", "we are chat"],
			wall: ["comments outline icon", "AHSP chat", "Ruang Chat Users"],
			inbox: ["comment outline icon", "AHSP chat", "inbox"],
			"monev[informasi]": ["info circle icon", "Informasi-Monev", "Informasi"],
			"monev[realisasi]": ["chartline icon", "Informasi-Monev", "Realisasi"],
			"monev[laporan]": ["chart pie icon", "Informasi-Monev", "Laporan"],
			outbox: ["comment dots outline icon", "AHSP chat", "outbox"],
		};
		var tabel = "";
		var dasboard = $(".message.dashboard");
		var ini = $(this);
		var tab = ini.attr("data-tab");
		var tbl = ini.attr("tbl");
		var url = "script/get_data";
		var jalankanAjax = false;
		let jenis = tab;
		$("#countRow").attr("name", jenis);
		var namaCariData = "";
		if (jenis in arrayDasboard) {
			var iconDashboard = arrayDasboard[jenis][0];
			var headerDashboard = arrayDasboard[jenis][1];
			var pDashboard = arrayDasboard[jenis][2];
		} else {
			iconDashboard = "home icon";
			headerDashboard = ini.text();
			pDashboard = "AHSP Pekerjaan Konstruksi";
		}
		var data = {
			cari: cari(jenis),
			rows: countRows(),
			jenis: jenis,
			tbl: tbl,
			halaman: halaman,
		};
		switch (jenis) {
			case 'wall':
				//=============================================
				//===========ruang chat visibility ==========
				//=============================================
				console.log('masuk tab visibility');
				$('.ui.comments[name="wall_status"]')
					.visibility({
						onTopVisible: function (calculations) {
							// top is on screen
							console.log('masuk tab visibility onTopVisible');

						},
						once: false,
						// update size when new content loads
						observeChanges: true,
						// load content on bottom edge visible
						onBottomVisible: function () {
							console.log('masuk tab visibility onBottomVisible');
							// loads a max of 5 times
							//window.loadFakeContent();
							//JALANKAN AJAX
							let url = "script/get_data";
							let data = {
								jenis: 'chat',
								tbl: jenis
							};
							let jalankanAjax = true;
							if (jalankanAjax) {
								suksesAjax["ajaxku"] = function (result) {
									if (result.success === true) {

									}

								};
								runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
							}
						}
					})
					;
				break;
			case "monev":
				let tabAktif = $('a[data-tab="monev[informasi]"]').closest('.vertical.pointing.menu').find('.red.item.active');
				if (tabAktif.length <= 0) {
					$('.pointing.menu a[data-tab="monev[informasi]"]').trigger('click');
				}
				break;
			case 'lap-harian':
				var tanggal = new Date($('[data-tab="lap-harian"] .calendar.laporan').calendar('get date'));
				console.log(tanggal);
				if (tanggal) {
					tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local stamp
					console.log(tanggal)
					data.tanggal = tanggal;
					jalankanAjax = true;
				}
				break;
			case "monev[informasi]":
			case "monev[laporan]":
			case "monev[realisasi]":
				data.tbl = "get_list";
				jalankanAjax = true;
				break;
			case "rekanan":
				tbl = 'get_list';
				data.tbl = "get_list";
				jalankanAjax = true;
				break;
			case "peraturan":
				data.tbl = "get_list";
				jalankanAjax = true;
				break;
			case "get":
				break;
			case "divisi":
				$(`[data-tab="divisiBM"]`).trigger("click");
				break;
			case "divisiBM":
			case "divisiCK":
			case "divisiSDA":
			case "schedule":
			case "rab": //pengaturan
				jalankanAjax = true;
				break;
			case "proyek": //pengaturan
				switch (tbl) {
					case "get_all_proyek":
						break;
					default:
						break;
				}
				jalankanAjax = true;
				break;
			case "peraturan":
			case "informasi_umum":
				jalankanAjax = true;
				break;
			case "tab-bahan":
				break;
			case "analisa_alat":
			case "harga_satuan":
				switch (tbl) {
					case "list":
						break;
					default:
						break;
				}
				jalankanAjax = true;
				break;
			case "satuan":
				jalankanAjax = true;
				break;
			case "analisa_quarry":
				switch (tbl) {
					case "list":
						break;
					default:
						break;
				}
				jalankanAjax = true;
				break;
			case "lokasi":
			case "lokasi-lokasi":
			case "lokasi-marker":
			case "lokasi-polyline":
			case "lokasi-polygon":
				jalankanAjax = true;
				break;
			case "analisa_ck":
			case "analisa_sda":
			case "analisa_bm":
				jalankanAjax = true;
				break;
			case "impor":
				jalankanAjax = false;
				break;
			case "user":
			case "profil":
				jalankanAjax = true;
				break;
			default:
				jalankanAjax = false;
				break;
		}
		dasboard.find($("i")).attr("class", "").addClass(iconDashboard);
		dasboard.find($("div.header")).text(headerDashboard);
		dasboard.find($("p")).text(pDashboard);
		if (tbl !== undefined && tbl.length > 0 && namaCariData.length > 0) {
			$("#cari_data").attr("tbl", namaCariData);
		}
		document.title = "AHSP | " + ini.text();
		if (jalankanAjax) {
			loaderShow();
			data.jenis = jenis;
			suksesAjax["ajaxku"] = function (result) {
				// console.log(result.success);
				// console.log(tab);
				if (result.success === true) {
					switch (tab) {
						case 'lap-harian':
							switch (tbl) {
								case 'get_list':
									$('table[name="lap_harian_manpower"]')
										.find("tbody")
										.html(result.data.tbody.tbodyUpah);
									$('table[name="lap_harian_bahan"]')
										.find("tbody")
										.html(result.data.tbody.tbodyBahan);
									$('table[name="lap_harian_alat"]')
										.find("tbody")
										.html(result.data.tbody.tbodyPeralatan);
									$('table[name="lap_harian_cuaca"]')
										.find("tbody")
										.html(result.data.tbody.tbodyCuaca);
									$('table[name="lap_harian_note"]')
										.find("tbody")
										.html(result.data.tbody.tbodyNote);
									break;

								default:
									break;
							}
							break;
						case "monev[realisasi]":
							$('table[name="tabel_realisasi"]')
								.find("tbody")
								.html(result.data.tbody);
							$('table[name="tabel_realisasi"]')
								.find("tfoot")
								.html(result.data.tfoot);
							var tglSpmk = result.data.dataProyek.tgl_spm;
							var tglKontrak = result.data.dataProyek.tgl_kontrak;
							console.log(tglSpmk, tglKontrak);
							if (tglSpmk === null) {
								$(
									'div[data-tab="monev[realisasi]"] .right.floated.basic .button'
								).addClass("disabled");
								showToast("lengkapi data kontrak terlebih dahulu", {
									class: "warning",
									icon: "info circle icon",
								});
							} else {
								$('div[data-tab="monev[realisasi]"] .disabled').removeClass(
									"disabled"
								);
							}

							break;
						case "monev[informasi]":
							addRulesForm($('form[name="form_range_date"]'));
							var sumRealisasiFisik = result.data.sumRealisasiFisik;
							var sumRealisasiKeu = result.data.sumRealisasiKeu;
							var sumRab = result.data.sumRab;
							var persenFisik = (sumRealisasiFisik / sumRab) * 100;
							//accounting.formatNumber((sumRealisasiFisik/sumRab)*100,4,".",",");
							var persenKeu = (sumRealisasiKeu / sumRab) * 100;
							var forMonev = $('form[name="monev[informasi]"]');
							if (forMonev.find('.ui.dropdown[name="owner"]')) {
								$('form[name="monev[informasi]"] .ui.dropdown[name="owner"]').dropdown({
									className: {
										item: "item vertical",
									},
									values: result.data.user_list,
									clearable: true,
								});
								$('form[name="monev[informasi]"] .ui.dropdown[name="id_pelaksana"],form[name="monev[informasi]"] .ui.dropdown[name="id_konsultan"]').dropdown({
									className: {
										item: "item vertical",
									},
									values: result.data.user_rekanan,
									clearable: true,
									onChange: function (value, text, $choice) {
										let dataChoice = $($choice).find('span.description').text();
										let npwp = dataChoice.split(";")[1];
										let dir = dataChoice.split(";")[0];
										$(this).closest('.two.fields').find('input[name="direktur"]').val(dir);
									}
								});
							}
							forMonev.form("set values", {
								no_kontrak: result.data.dataProyek.no_kontrak,
								tgl_kontrak: result.data.dataProyek.tgl_kontrak,
								nilai_kontrak: accounting.formatNumber(
									parseFloat(result.data.dataProyek.nilai_kontrak),
									2,
									".",
									","
								),
								rab: accounting.formatNumber(
									parseFloat(parseFloat(sumRab)),
									2,
									".",
									","
								),
								id_pelaksana: result.data.dataProyek.id_pelaksana,
								id_konsultan: result.data.dataProyek.id_konsultan,
								no_spm: result.data.dataProyek.no_spm,
								tgl_spm: result.data.dataProyek.tgl_spm,
								no_pho: result.data.dataProyek.no_pho,
								tgl_pho: result.data.dataProyek.tgl_pho,
								no_fho: result.data.dataProyek.no_fho,
								tgl_fho: result.data.dataProyek.tgl_fho,
								owner: JSON.parse(result.data.dataProyek.owner),
								keterangan: result.data.dataProyek.keterangan,
							});
							//dropdown owner
							//name="owner"


							hasKey = result.data.dataProyek.hasOwnProperty("addendum");
							if (hasKey) {
								var dataAddendum = JSON.parse(result.data.dataProyek.addendum);
								if (dataAddendum !== null) {
									var addendumMonev = $(
										'form[name="monev[informasi]"] div[name="data_addendum"]'
									);
									addendumMonev.html("");
									Object.keys(dataAddendum).forEach((key) => {
										var letChild = addendumMonev.children();
										var element = `<div class="field" direct="del"><label>Addendum [${parseInt(key) + 1
											}]</label><div class="two fields"><div class="twelve wide field"><div class="ui action input"><input placeholder="Nomor Addendum" type="text" name="nomor[${key}]" value="${dataAddendum[key]["nomor"]
											}"><button class="ui basic icon button" name="del_row" jns="direct" tbl="del_row" direct="1"><i class="trash alternate outline red icon"></i></button></div></div><div class="four wide field"><div class="ui calendar date"><div class="ui input left icon"><i class="calendar icon"></i><input type="text" placeholder="tanggal Addendum" name="tgl[${key}]" value="${dataAddendum[key]["tgl"]
											}"></div></div></div></div></div>`;
										if (letChild.length > 0) {
											letChild.last().after(element);
										} else {
											addendumMonev.html(element);
										}
									});
								}
								let calendarDate = new CalendarConstructor(".ui.calendar.date");
								// calendarDate.MaxDate(maxDate);
								// calendarDate.minDate(minDate);
								calendarDate.runCalendar()


								addRulesForm(forMonev);
								//apexchart
								$('div[name="chart1"]').html('');
								$('div[name="chart2"]').html('');
								var options = {
									series: [
										{
											data: [
												sumRab / 1000,
												sumRealisasiFisik / 1000,
												sumRealisasiKeu / 1000,
											],
										},
									],
									chart: {
										type: "bar",
										height: 300,
									},
									plotOptions: {
										bar: {
											barHeight: "100%",
											distributed: true,
											horizontal: true,
											dataLabels: {
												position: "bottom",
											},
										},
									},
									colors: ["#33b2df", "#546E7A", "#d4526e"],
									dataLabels: {
										enabled: true,
										textAnchor: "start",
										style: {
											colors: ["#fff"],
										},
										formatter: function (val, opt) {
											return (
												opt.w.globals.labels[opt.dataPointIndex] +
												":  " +
												accounting.formatNumber(val, 2, ".", ",")
											);
										},
										offsetX: 0,
										dropShadow: {
											enabled: true,
										},
									},
									stroke: {
										width: 1,
										colors: ["#fff"],
									},
									xaxis: {
										categories: ["BOQ", "Realisasi Fisik", "Realisasi Keuangan"],
									},
									yaxis: {
										labels: {
											show: false,
										},
									},
									title: {
										text: "Realisasi",
										align: "center",
										floating: true,
									},
									subtitle: {
										text: "Realisasi Fisik dan Keuangan (ribu)",
										align: "center",
									},
									tooltip: {
										theme: "dark",
										x: {
											show: false,
										},
										y: {
											title: {
												formatter: function () {
													return "";
												},
											},
										},
									},
								};
								var chart = new ApexCharts(
									document.querySelector('div[name="chart1"]'),
									options
								);
								chart.render();
								var options = {
									series: [persenFisik, persenKeu],
									chart: {
										height: 350,
										type: "radialBar",
										offsetY: -10,
									},
									plotOptions: {
										radialBar: {
											startAngle: -135,
											endAngle: 135,
											dataLabels: {
												total: {
													show: true,
													offsetY: 110,
													label: "SUM BOQ",
													formatter: function (w) {
														return accounting.formatNumber(
															parseFloat(sumRab),
															2,
															".",
															","
														);
													},
												},
												name: {
													fontSize: "12px",
													color: undefined,
													offsetY: 100,
												},
												value: {
													offsetY: 110,
													fontSize: "14px",
													color: undefined,
													formatter: function (val) {
														return (
															accounting.formatNumber(val, 2, ".", ",") + "%"
														);
													},
												},
											},
										},
									},
									fill: {
										type: "gradient",
										gradient: {
											shade: "dark",
											shadeIntensity: 0.15,
											inverseColors: false,
											opacityFrom: 1,
											opacityTo: 1,
											stops: [0, 50, 65, 91],
										},
									},
									stroke: {
										dashArray: 4,
									},
									labels: ["Fisik", "Keuangan"],
								};

								var chart = new ApexCharts(
									document.querySelector('div[name="chart2"]'),
									options
								);
								chart.render();
							}
							break;
						case "monev[laporan]":
							var waktuPelaksanaan = parseInt(result.data.dataProyek.MPP);
							var tglSpmk = result.data.dataProyek.tgl_spm;
							var tglKontrak = result.data.dataProyek.tgl_kontrak;
							var sumRealisasiFisik = roundUp(result.data.sumRealisasiFisik, 2);
							var sumRealisasiKeu = roundUp(result.data.sumRealisasiKeu, 2);
							var sumRab = roundUp(result.data.sumRab, 2);
							//range laporan periodik
							if (tglSpmk === null) {
								$('[data-tab="lap-harian"] .calendar.laporan,[data-tab="lap-harian"] .right.floated.basic .button').addClass("disabled");
								showToast("lengkapi data kontrak terlebih dahulu", {
									class: "warning",
									icon: "info circle icon",
								});
							} else {
								var tglSpmkConvert = tglSpmk.split("-");
								minDate = new Date(
									tglSpmkConvert[0],
									tglSpmkConvert[1] - 1,
									tglSpmkConvert[2].substr(0, 2)
								);
								maxDate = new Date(
									tglSpmkConvert[0],
									tglSpmkConvert[1] - 1,
									tglSpmkConvert[2].substr(0, 2)
								);
								maxDate.setDate(maxDate.getDate() + waktuPelaksanaan);
								$('[data-tab="lap-harian"] .disabled').removeClass("disabled");
								let calendarDateMonev = new CalendarConstructor('.ui.calendar.laporan[name="tgl_lap_harian"]');
								calendarDateMonev.minDate = minDate;
								calendarDateMonev.maxDate = maxDate;
								calendarDateMonev.typeOnChange = 'tab="lap-harian"';
								calendarDateMonev.runCalendar();
								const rangestart = new CalendarConstructor('#rangestart');
								rangestart.Type('date');
								rangestart.minDate = minDate;
								rangestart.maxDate = maxDate;
								rangestart.endCalendar = $('#rangeend');
								rangestart.runCalendar();
								const rangeend = new CalendarConstructor('#rangeend');
								rangeend.Type('date');
								rangeend.minDate = minDate;
								rangeend.maxDate = maxDate;
								rangeend.startCalendar = $('#rangestart');
								rangeend.runCalendar();

								var persenFisik = roundUp(
									parseFloat((sumRealisasiFisik / sumRab) * 100),
									2
								);
								var sisaFisik = roundUp(
									parseFloat(sumRab - sumRealisasiFisik),
									2
								);
								var sisaKeu = roundUp(parseFloat(sumRab - sumRealisasiKeu), 2);
								//accounting.formatNumber((sumRealisasiFisik/sumRab)*100,4,".",",");
								var persenKeu = roundUp((sumRealisasiKeu / sumRab) * 100, 2);
								$('p[name="total-anggaran"]').text(
									`Rp. ${accounting.formatNumber(sumRab, 2, ".", ",")}`
								);
								$('p[name="realisasi-fisik"]').text(
									`Rp. ${accounting.formatNumber(
										sumRealisasiFisik,
										2,
										".",
										","
									)} (${accounting.formatNumber(
										(sumRealisasiFisik / sumRab) * 100,
										2,
										".",
										","
									)} %)`
								);
								$('i[name="chart-realisasi-fisik-mini"]').text(
									`${accounting.formatNumber(
										(sumRealisasiFisik / sumRab) * 100,
										0,
										".",
										","
									)}%`
								);
								$('p[name="realisasi-keu"]').text(
									`Rp. ${accounting.formatNumber(
										sumRealisasiKeu,
										2,
										".",
										","
									)} (${accounting.formatNumber(
										(sumRealisasiKeu / sumRab) * 100,
										2,
										".",
										","
									)} %)`
								);
								$('i[name="chart-realisasi-keu-mini"]').text(`${accounting.formatNumber(sumRealisasiKeu / sumRab * 100, 0, ".", ",")}%`);

								$('p[name="sisa-fisik"]').text(`Rp. ${accounting.formatNumber(sisaFisik, 2, ".", ",")} (${accounting.formatNumber(sisaFisik / sumRab * 100, 2, ".", ",")} %)`), $('p[name="sisa-keu"]').text(`Rp. ${accounting.formatNumber(sisaKeu, 2, ".", ",")} (${accounting.formatNumber(sisaKeu / sumRab * 100, 2, ".", ",")} %)`);
							}





							break;
						case "peraturan":
							$('[name="list_peraturan"]').html(result.data.list);
							$('[name="list_peraturan_ket"]').html(result.data.foot);
							break;
						case "lokasi":
							/*==============================
							========== MAP  LEAFLET ========
							===============================*/
							var markerTemp;
							var hasKey = result.data.hasOwnProperty("users");
							let x_proyek = -1.1829846927219299;
							let y_proyek = 119.36318814754488;
							let keterangan = "",
								id_layer,
								selectLayer;
							if (hasKey) {
								hasKey = result.data.users.hasOwnProperty("sta_pengenal_X");
								if (hasKey) {
									x_proyek = result.data.users.sta_pengenal_X;
									y_proyek = result.data.users.sta_pengenal_Y;
								}
								keterangan = result.data.users.keterangan;
							}
							//mencegah berulang kali memanggil function
							if (!calledLokasi) {
								var container = L.DomUtil.get("map");
								if (container["_leaflet_id"] != null) {
									var afterMap = $(container).prev();
									container.remove();
									afterMap.after('<div id="map"></div>');
								}
								//map.invalidateSize();
								setTimeout(function () {
									//map cursor position
									L.CursorHandler = L.Handler.extend({
										addHooks: function () {
											//this._popup = new L.Popup();
											this._map.on("mouseover", this._open, this);
											this._map.on("mousemove", this._update, this);
											this._map.on("mouseout", this._close, this);
										},
										removeHooks: function () {
											this._map.off("mouseover", this._open, this);
											this._map.off("mousemove", this._update, this);
											this._map.off("mouseout", this._close, this);
										},
										_open: function (e) {
											this._update(e);
											//this._popup.openOn(this._map);
										},
										_close: function () {
											//this._map.closePopup(this._popup);
										},
										_update: function (e) {
											$("#lokasi_cursorY").text(
												accounting.formatNumber(e.latlng.lat, 6, ".", ",")
											);
											$("#lokasi_cursorX").text(
												accounting.formatNumber(e.latlng.lng, 6, ".", ",")
											);
										},
									});
									//L.control.scale({ position: 'bottomright', metric: true }).addTo(map);
									L.Map.addInitHook("addHandler", "cursor", L.CursorHandler);
									//mulai menggambar peta
									/*var map = L.map('map', {
										drawControl: true,
										cursor: true
									}).setView([y_proyek, x_proyek], 15);
									map.invalidateSize();
									L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
										attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">ahsp</a>',
										maxZoom: 19,
										minZoom: 8,
										drawControl: true,
										drawnItems: L.featureGroup().addTo(map),
										subdomains: ['a', 'b', 'c']
									}).addTo(map);//ini bagus renderingnya
									*/
									var osmUrl =
										"http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
										osmAttrib = "OSM @ ahsp",
										osm = L.tileLayer(osmUrl, {
											maxZoom: 18,
											minZoom: 8,
											attribution: osmAttrib,
										}),
										map = new L.Map("map", {
											center: new L.LatLng(y_proyek, x_proyek),
											cursor: true,
											zoom: 15,
										}),
										drawnItems = L.featureGroup().addTo(map);
									L.control
										.layers(
											{
												OSM: osm.addTo(map),
												TopoOSM: L.tileLayer(
													"https://a.tile.opentopomap.org/{z}/{x}/{y}.png",
													{
														attribution: "OSM @ ahsp",
														maxZoom: 18,
														minZoom: 8,
													}
												),
												GoStreet: L.tileLayer(
													"https://mt0.google.com/vt/lyrs=m&x={x}&y={y}&z={z}",
													{
														attribution: " Google @ ahsp",
														maxZoom: 18,
														minZoom: 8,
													}
												),
												GoSatelite: L.tileLayer(
													"http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}",
													{
														attribution: " Google @ ahsp",
														maxZoom: 18,
														minZoom: 8,
													}
												),
												GoTerrain: L.tileLayer(
													"https://mt1.google.com/vt/lyrs=p&x={x}&y={y}&z={z}",
													{
														attribution: "Google @ ahsp",
														maxZoom: 18,
														minZoom: 8,
													}
												),
											},
											{ drawlayer: drawnItems },
											{ position: "topright", collapsed: true }
										)
										.addTo(map);

									//edit

									//gambar skala
									L.control
										.scale({
											position: "topright",
											metric: true,
											imperial: false,
										})
										.addTo(map);
									/*var popup = L.popup()
										.setLatLng([-1.18327 + 0.00833, 119.36295])
										.setContent("pasangkayu")
										.openOn(map);*/
									// FeatureGroup is to store editable layers

									//

									/*var polygon = L.polygon([
										[-1.170, 119.350],
										[-1.171, 119.351],
										[-1.172, 119.352]
									]).addTo(map);
									polygon.bindPopup("trase");
									polygon.on("popupopen", onPopupOpen);
									*/

									/*
									map.on('click', function (e) {
										//alert("Lat, Lon : " + e.latlng.lat + ", " + e.latlng.lng);
										//markerTemp = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
										markerTemp = new L.Marker(e.latlng, { draggable: true });
										map.addLayer(markerTemp);
										markerTemp.bindPopup(`<b>Koordinat</b><br />${e.latlng.lat} ${e.latlng.lng}`).openPopup();
									});
									*/
									//map.removeLayer(markerTemp)
									//jenis
									function onMapClick(e) {
										var geojsonFeature = {
											type: "Feature",
											properties: {},
											geometry: {
												type: "Point",
												coordinates: [e.latlng.lat, e.latlng.lng],
											},
										};
										var marker;
										L.geoJson(geojsonFeature, {
											pointToLayer: function (feature, latlng) {
												marker = L.marker(e.latlng, {
													title: "Marking Location",
													alt: "Resource Location",
													riseOnHover: true,
													draggable: true,
												}).bindPopup(
													`<form class="ui mini form" name="lokasi"><div class="field"><div class="ui left icon input"><input type="text" value="${e.latlng.lat}" name="Y" readonly><i class="marker blue icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${e.latlng.lng}" readonly name="X"><i class="marker green icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" name="keterangan"><i class="tag icon"></i></div></div><div class="ui mini icon inverted basic buttons"><button class='ui button marker-delete-button'><i class="red erase icon"></i></button><button class='ui button tambah-marker'><i class="add green icon"></i></button></div></form>`
												);
												marker.on("popupopen", onPopupOpen);
												return marker;
											},
										}).addTo(map);
									}

									function onLayerClick(e) {
										var targetLayer = e.sourceTarget;
										console.log(targetLayer instanceof L.Rectangle);
										var targetLayer = e.sourceTarget;
										if (targetLayer instanceof L.Rectangle) {
											console.log("rectang");
										} else if (targetLayer instanceof L.Polygon) {
											console.log("Polygon");
										} else if (targetLayer instanceof L.Polyline) {
											console.log("Polyline");
										} else if (targetLayer instanceof L.Circle) {
											console.log("Circle");
										} else if (targetLayer instanceof L.Marker) {
											console.log("Marking");
										} else {
											console.log("tidak jelas shapenya");
										}
									}
									map.addControl(
										new L.Control.Draw({
											edit: {
												featureGroup: drawnItems,
												polyline: {
													allowIntersection: true,
												},
												polygon: {
													showLength: true,
													showArea: true,
													allowIntersection: true, // Otherwise, area would not be shown
												},
											},
											draw: {
												polygon: {
													allowIntersection: true,
													showArea: true,
												},
											},
										})
									);
									/*==============================
									============custom bar peta=====
									================================*/
									L.Control.Button = L.Control.extend({
										options: {
											position: "topleft",
										},
										onAdd: function (map) {
											var container = L.DomUtil.create(
												"div",
												"leaflet-bar leaflet-control"
											);
											var button = L.DomUtil.create(
												"a",
												"leaflet-control-button",
												container
											);
											L.DomEvent.disableClickPropagation(button);
											L.DomEvent.on(button, "click", function () {
												console.log("click");
											});
											var button2 = L.DomUtil.create(
												"a",
												"leaflet-control-button",
												container
											);
											L.DomEvent.disableClickPropagation(button2);
											L.DomEvent.on(button2, "click", function () {
												// munculkan lokasi
												console.log("click button 2");
												// Sets map data source and associates with map
												let marker, circle, zoomed;
												navigator.geolocation.watchPosition(success, error);
												function success(pos) {
													const lat = pos.coords.latitude;
													const lng = pos.coords.longitude;
													const accuracy = pos.coords.accuracy;
													if (marker) {
														map.removeLayer(marker);
														map.removeLayer(circle);
													}
													// Removes any existing marker and circule (new ones about to be set)
													marker = L.marker([lat, lng]).addTo(map);
													marker.bindPopup(``);
													marker.on("popupopen", onPopupOpen);
													circle = L.circle([lat, lng], {
														radius: accuracy,
													}).addTo(map);
													circle.bindPopup(``);
													circle.on("popupopen", onPopupOpen);
													button2.innerHTML =
														'<i class="location arrow blue icon" style="margin-right: 0px !important;"></i>';
													// Adds marker to the map and a circle for accuracy
													if (!zoomed) {
														zoomed = map.fitBounds(circle.getBounds());
													}
													// Set zoom to boundaries of accuracy circle
													map.setView([lat, lng]);
													// Set map focus to current user position
												}
												function error(err) {
													let kelasToast = "success";
													button2.innerHTML =
														'<i class="location arrow icon" style="margin-right: 0px !important;"></i>';
													pesan = "";
													if (err.code === 1) {
														pesan = "Please allow geolocation access";
													} else {
														pesan = "Cannot get current location";
													}
													showToast(pesan, {
														class: kelasToast,
														icon: "blue marker icon",
													});
												}
											});
											button.title = "cari lokasi";
											button2.title = "lokasi saya";
											//button.setAttribute("name", "flyout");
											//button.setAttribute("jns", "lokasi");
											//button.setAttribute("tbl", "lokasi");
											$(button)
												.attr("name", "flyout")
												.attr("jns", "lokasi")
												.attr("tbl", "go-to");
											button.innerHTML =
												'<i class="circular inverted teal search icon" style="margin-right: 0px !important;"></i>';
											button2.innerHTML =
												'<i class="location arrow icon" style="margin-right: 0px !important;"></i>';

											return container;
										},
										onRemove: function (map) { },
									});
									/*==============================
									============gambar dan edit=====
									================================*/
									var control = new L.Control.Button();
									control.addTo(map);
									var layerGroup = L.layerGroup().addTo(map);
									map.on(L.Draw.Event.CREATED, function (event) {
										var layer = event.layer,
											type = event.layerType;
										drawnItems.addLayer(layer);
										layer.addTo(layerGroup);
										layer.bindPopup(`halo`);
										layer.on("popupopen", onPopupOpen);
									});

									var lokasiProyek = L.marker([y_proyek, x_proyek], {
										title: "Lokasi Proyek",
										alt: "Resource Location",
										riseOnHover: true,
										draggable: true,
									}).addTo(map);
									lokasiProyek.bindPopup(``);
									lokasiProyek.on("popupopen", onPopupOpen);
									let id_lokasiProyek = L.stamp(lokasiProyek);
									var circleProyek = L.circle([y_proyek, x_proyek], {
										color: "red",
										fillColor: "#f03",
										fillOpacity: 0.5,
										radius: 50,
									}).addTo(map);
									circleProyek.bindPopup("areal kegiatan");
									// Function to handle delete as well as other events on marker popup open
									// getting all the markers at once
									$("body").on("click", '[name="get_layer"]', function (e) {
										e.preventDefault();
										let Layerku = getAllMarkers();
										//console.log(Layerku);
									});

									function getAllMarkers() {
										var allMarkersObjArray = []; // for marker objects
										var allMarkersGeoJsonArray = []; // for readable geoJson markers
										$.each(map._layers, function (ml) {
											//console.log(this);
											if (map._layers[ml].feature) {
												console.log(this);
												console.log(map._layers[ml].feature);
												allMarkersObjArray.push(this);
												allMarkersGeoJsonArray.push(
													JSON.stringify(this.toGeoJSON())
												);
											}
										});
										return allMarkersGeoJsonArray;
										//console.log(allMarkersObjArray);
									}
									/*
									$('#map').on('contextmenu', function (e) {
										e.stopPropagation();
										map.removeLayer(markerTemp)
										// Your code.
									});*/

									//mencegah fungsi dipanggil berulangkali

									$("body").on(
										"submit",
										'form[name="lokasi"]:visible',
										mySubmitFunction
									);
									function mySubmitFunction(e) {
										e.preventDefault();
										var jalanKanAjaxLokasi = false;
										let id_layerSelect = L.stamp(selectLayer);
										let form = $(this);
										let keterangan = form.form("get value", "keterangan");
										let kode = form.form("get value", "kode");
										let tblLokasi = form.attr("tbl");
										const json = selectLayer.toGeoJSON();
										if (selectLayer instanceof L.Circle) {
											json.properties.radius = selectLayer.getRadius();
										}
										json.properties.kode = kode;
										json.properties.keterangan = keterangan;
										console.log(tblLokasi);
										if (tblLokasi === "deleteLokasi") {
											drawnItems.removeLayer(id_layerSelect);
											jalanKanAjaxLokasi = false;
										}
										if (id_layerSelect !== id_lokasiProyek) {
											jalanKanAjaxLokasi = true;
											switch (tblLokasi) {
												case "add_row":
													break;
												case "update":
													break;

												default:
													break;
											}
										} else {
											json.properties.uraian = "lokasi proyek";
											jalanKanAjaxLokasi = true;
											tblLokasi = "update";
										}
										console.log("GeoJSON FORM : ");
										console.log(json);
										if (jalanKanAjaxLokasi) {
											let urlLokasi = "script/post_data";
											let dataLokasi = {
												jenis: "lokasi",
												tbl: tblLokasi,
												data: JSON.stringify(json),
											};
											suksesAjax["runAjaxLokasi"] = function (result) {
												let iconToast = "check circle icon";
												let classToast = "success";
												if (result.success === true) {
													if (result.error.code === 404) {
														classToast = "error";
														iconToast = "exclamation circle icon";
													} else {
														if (json.properties.uraian === "lokasi proyek") {
															var position = lokasiProyek.getLatLng();
															circleProyek.setLatLng(position);
															// marker.setLatLng(position,{id:uni,draggable:'true'}).bindPopup(position).update();
														}
														//
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
											runAjax(
												urlLokasi,
												"POST",
												dataLokasi,
												"Json",
												undefined,
												undefined,
												"runAjaxLokasi"
											);
										}
									}

									/*==============================
									============fungsi klik popup=====
									================================*/
									function onPopupOpen(e) {
										//
										let layer = e.popup._source; //ok
										selectLayer = layer;
										var tempMarker = this;
										const json = layer.toGeoJSON();
										if (layer instanceof L.Circle) {
											json.properties.radius = layer.getRadius();
										}
										id_layer = L.stamp(tempMarker);
										//bagaimana get ID dari layer di feature group on click
										/*drawnItems.eachLayer(function (layer) {
											id_layer = drawnItems.getLayerId(layer);
										});*/
										console.log("GeoJSON : ");
										console.log(json);
										let Area = 0,
											latidude = 0,
											longitude = 0;

										console.log(this);
										//console.log(layer)//ok
										if (id_layer !== id_lokasiProyek) {
											var elmBindPopup2 = `<div class="field"><div class="ui left icon input"><input type="text" value="${tempMarker._popup._latlng.lat}" name="Y" readonly><i class="marker blue icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${tempMarker._popup._latlng.lng}" readonly name="X"><i class="marker green icon"></i></div></div>`;
											let nameButton = "add_row";
											//menentukan jenis drawing//.getLatLng()
											//map.flyTo(myLayer.getBounds().getCenter());
											var targetLayer = e.sourceTarget;
											console.log(e.sourceTarget);

											//console.log(layer.getBounds().getCenter())//ok rectang, polygon,polyline, circle. not ok marker atau circlemarker
											let objectCenter;
											if (targetLayer instanceof L.Rectangle) {
												Area = L.GeometryUtil.geodesicArea(
													layer.getLatLngs()[0]
												);
												objectCenter = layer.getBounds().getCenter();
												latidude = objectCenter.lat;
												longitude = objectCenter.lng;

												//latidude = targetLayer._map._lastCenter.lat;
												//longitude = targetLayer._map._lastCenter.lng;
												console.log("rectang");
												elmBindPopup2 = `<div class="field"><div class="ui left icon input"><input type="text" value="${Area}" readonly><i class="vector square icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${latidude}" readonly name="lat"><i class="marker green icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${longitude}" readonly name="lng"><i class="marker green icon"></i></div></div>`;
											} else if (targetLayer instanceof L.Polygon) {
												Area = L.GeometryUtil.geodesicArea(
													layer.getLatLngs()[0]
												);
												objectCenter = layer.getBounds().getCenter();
												latidude = objectCenter.lat;
												longitude = objectCenter.lng;
												elmBindPopup2 = `<div class="field"><div class="ui left icon input"><input type="text" value="${Area}" readonly><i class="draw polygon icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${latidude}" readonly name="lat"><i class="marker green icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${longitude}" readonly name="lng"><i class="marker green icon"></i></div></div>`;
												//console.log('Polygon')
											} else if (targetLayer instanceof L.Polyline) {
												var coords = layer.getLatLngs();
												Area = 0; //length polyline
												for (var i = 0; i < coords.length - 1; i++) {
													Area += coords[i].distanceTo(coords[i + 1]);
												}
												//Area = L.GeometryUtil.geodesicArea(layer.getLatLngs());
												console.log("Polyline");
												//console.log(L.GeometryUtil.geodesicArea(layer.getLatLngs()));
												objectCenter = layer.getBounds().getCenter();
												latidude = objectCenter.lat;
												longitude = objectCenter.lng;
												elmBindPopup2 = `<div class="field"><div class="ui left icon input"><input type="text" value="${Area}" readonly><i class="ruler combined icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${latidude}" readonly name="lat"><i class="marker green icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${longitude}" readonly name="lng"><i class="marker green icon"></i></div></div>`;
											} else if (targetLayer instanceof L.Circle) {
												console.log("Circle");
												console.log(targetLayer.getRadius());
												let Radius = targetLayer.getRadius();
												Area = Math.PI * Radius ** 2;
												//Area = L.GeometryUtil.geodesicArea(layer.getLatLngs());error circle
												objectCenter = layer.getBounds().getCenter();
												latidude = objectCenter.lat;
												longitude = objectCenter.lng;
												elmBindPopup2 = `<div class="field"><div class="ui left icon input"><input type="text" value="${Area}" readonly><i class="circle outline icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${latidude}" readonly name="lat"><i class="marker green icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${longitude}" readonly name="lng"><i class="marker green icon"></i></div></div>`;
												//console.log(e.sourceTarget._radius)
											} else if (targetLayer instanceof L.Marker) {
												console.log("Marking");
											} else {
												console.log("tidak jelas shapenya");
												//form.form('set values', { Y: tempMarker._popup._latlng.lat, X: tempMarker._popup._latlng.lng });
											}
											//console.log(id_layer)
											layer.bindPopup(
												`<form class="ui mini form" name="lokasi"><div class="field"><div class="ui left icon input"><input type="text" name="kode" autofocus placeholder="kode"><i class="barcode icon"></i></div></div>${elmBindPopup2}<div class="field"><div class="ui left icon input"><input type="text" name="keterangan" placeholder="keterangan"><i class="tag icon"></i></div></div><div class="ui fluid mini buttons"><button class="ui green button lokasi-button" name="${nameButton}">Save</button><div class="or"></div><button class="ui red button marker-delete-button" name="deleteLokasi">Delete</button></div></form>`
											); //ok
										} else {
											layer.bindPopup(
												`<form class="ui mini form" name="lokasi"><div class="field"><div class="ui left icon input"><input type="text" value="${tempMarker._popup._latlng.lat}" name="Y" readonly><i class="marker blue icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" value="${tempMarker._popup._latlng.lng}" readonly name="X"><i class="marker green icon"></i></div></div><div class="field"><div class="ui left icon input"><input type="text" name="keterangan" value="${keterangan}"><i class="tag icon"></i></div></div><button class='ui mini fluid basic inverted primary button lokasi-button' name="update">update</button></form>`
											);
										}
										let form = $('form[name="lokasi"]:visible');
										form.attr("jns", jenis);
										//addRulesForm($('form[name="lokasi"]:visible'));
										$("body").on(
											"click",
											".marker-delete-button:visible",
											function (e) {
												e.preventDefault();
												//map.removeLayer(tempMarker);
												drawnItems.removeLayer(id_layer);
												form.attr("tbl", $(this).attr("name"));
												console.log($(this).attr("name"));
												form.form("submit");
											}
										);
										$("body").on(
											"click",
											".lokasi-button:visible",
											function (e) {
												e.preventDefault();
												form.attr("tbl", $(this).attr("name"));
												console.log($(this).attr("name"));
												form.form("submit");
											}
										);
									}
								}, 100);
								calledLokasi = true;
							}

							break;
						case "lokasi-lokasi":
						case "lokasi-marker":
						case "lokasi-polyline":
						case "lokasi-polygon":
							break;
						case "divisiBM":
						case "divisiCK":
						case "divisiSDA":
							switch (tbl) {
								case "get_list":
									$('table[name="tabel_divisi"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_divisi"]')
										.find("tfoot")
										.html(result.data.tfoot);
									break;
								default:
									break;
							}
							break;
						case "rekanan":
							console.log(tbl);
							switch (tbl) {
								case "get_list":
									console.log('masukji');
									$('table[name="tabel_rekanan"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_rekanan"]')
										.find("tfoot")
										.html(result.data.tfoot);
									$('[name="jumlah_rekanan"]').html(result.data.countRekanan);
									break;
								default:
									break;
							}
							break;
						case "rab":
							switch (tbl) {
								case "get_list":
									let tabel = $('table[name="tabel_rab"]');
									tabel.find("tbody").html(result.data.tbody);
									var hasKey = result.data.hasOwnProperty("sum");
									if (hasKey) {
										tabel
											.find("tfoot")
											.html(
												`<tr><td colspan="5"><td>${accounting.formatNumber(
													result.data.sum,
													2,
													".",
													","
												)}</td></td><td colspan="2"></td></tr>`
											);
									}
									$('[name="jumlah_proyek"]').text(result.data.sumProyek);
									$('[name="jumlah_boq"]').text(result.data.sumRab);
									hasKey = result.data.hasOwnProperty("tfoot");
									if (hasKey) {
										let adaTfoot = $(
											'table[name="tabel_rab"] tfoot'
										).children();
										console.log(`adaTfoot = ${adaTfoot}`);
										//console.log(adaTfoot);
										if (adaTfoot) {
											tabel.find("tfoot tr:last").after(result.data.tfoot);
										} else {
											tabel.find("tfoot").html(result.data.tfoot);
										}
									}
									break;
								default:
									break;
							}
							break;
						case "schedule":
							switch (tbl) {
								case "get_list":
									$('table[name="tabel_schedule"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_schedule"]')
										.find("tfoot")
										.html(result.data.tfoot);
									$('[name="jumlah_proyek"]').text(result.data.sumProyek);
									var MPP = result.data.MPP; //waktu pelaksanaan
									var durasi = 0;
									var awal = 0;

									$(".ui.range.slider.schedule2").slider({
										min: 0,
										start: 0,
										max: MPP,
										end: MPP,
										step: 1,
									});
									const dataTboy = $(result.data.tbody);
									//console.log(dataTboy);
									Object.keys(dataTboy).forEach((key) => {
										var element = dataTboy[key];
										var td = $(element).children();
										//console.log(td)
										let id_row = parseFloat($(element).attr("id_row"));
										let awal = parseFloat(
											$(element).find('[klm="mulai"]').children().text()
										);
										let durasi = parseFloat(
											$(element).find('[klm="durasi"]').children().text()
										);
										/*let bobot_selesai = parseFloat(
											$(element).find('[klm="bobot_selesai"]').children().text()
										);*/
										//let elmSlider = $(element).find(`.schedule[id_row="${id_row}"]`);
										if (id_row > 0) {
											//console.log(elmSlider);
											$(`.ui.range.slider.schedule.${id_row}`).slider({
												min: 1,
												max: MPP,
												start: awal,
												end: awal + durasi,
												step: 1,
												onMove: function () {
													let ini = $(this);
													let nilai = ini.slider("get value");
													let awal = ini.slider("get thumbValue", "first");
													let finish = ini.slider("get thumbValue", "second");
													let tr = ini.closest("tr");
													tr.find('td[klm="durasi"]').children().text(nilai);
													tr.find('td[klm="mulai"]').children().text(awal);
													let bobot_selesai = parseFloat(
														tr.find('td[klm="bobot_selesai"]').children().text()
													);
												},
											});
										}
										//console.log(elmSlider);
									});
									// }, 500);
									break;
								default:
									break;
							}
							break;
						case "proyek":
							$('[name="kd_proyek_user"]').text(
								result.data.dataProyek.kd_proyek
							);
							$('[name="nama_proyek_aktif"]').text(
								result.data.dataProyek.nama_proyek
							);
							switch (tbl) {
								case "get_all_proyek":
									$('table[name="tabel_proyek"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_proyek"]')
										.find("tfoot")
										.html(result.data.tfoot);
									$('[name="jumlah_proyek"]').text(result.data.sumProyek);
									break;
								default:
									break;
							}
							break;
						case "harga_satuan":
							switch (tbl) {
								case "list":
									$('table[name="tabel_harga_satuan"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_harga_satuan"]')
										.find("tfoot")
										.html(result.data.tfoot);
									$('[name="jumlah_proyek"]').text(result.data.sumProyek);
									$('[name="jumlah_harga_satuan"]').text(
										result.data.sum_harga_satuan
									);
									break;
								default:
									break;
							}
							break;
						case "satuan":
							switch (tbl) {
								case "list":
									$('table[name="tabel_satuan"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_satuan"]')
										.find("tfoot")
										.html(result.data.tfoot);
									$('[name="jumlah_proyek"]').text(result.data.sumproyek);
									$('[name="jumlah_satuan"]').text(
										result.data.sum_harga_satuan
									);
									break;
								default:
									break;
							}
							break;
						case "informasi_umum":
							$('table[name="tabel_informasi"]')
								.find("tbody")
								.html(result.data.tbody);
							$('table[name="tabel_informasi"]')
								.find("tfoot")
								.html(result.data.tfoot);
							break;
						case "analisa_alat":
							$('[name="list_peralatan"]').html(result.data.list);
							$('[name="list_peralatan_ket"]').html(result.data.foot);
							$('[name="jumlah_alat"]').html(result.data.sumAlat);
							break;
						case "analisa_quarry":
							$('[name="list_quarry"]').html(result.data.list);
							$('[name="list_quarry_ket"]').html(result.data.foot);
							$('[name="jumlah_quarry"]').html(result.data.sumquarry);
							break;
						case "analisa_bm":
							$('[name="list_analisa_bm"]').html(result.data.list);
							$('[name="list_analisa_bm_ket"]').html(result.data.foot);
							$('[name="jumlah_analisa_bm"]').html(result.data.sumanalisa);
							break;
						case "analisa_ck":
							$('[name="list_analisa_ck"]').html(result.data.list);
							$('[name="list_analisa_ck_ket"]').html(result.data.foot);
							$('[name="jumlah_analisa_ck"]').html(result.data.sumanalisa);
							break;
						case "analisa_sda":
							$('[name="list_analisa_sda"]').html(result.data.list);
							$('[name="list_analisa_sda_ket"]').html(result.data.foot);
							$('[name="jumlah_analisa_sda"]').html(result.data.sumanalisa);
							break;
						case "umum":
							break;
						case "user":
							switch (tbl) {
								case "list":
									$('table[name="tabel_user"]')
										.find("tbody")
										.html(result.data.tbody);
									$('table[name="tabel_user"]')
										.find("tfoot")
										.html(result.data.tfoot);
									//$('.ui.checkbox.user').checkbox();
									/*
									if ($('.user.toggle.checkbox').checkbox('is checked')) { // tentukan checked toogle
										hapus = 1;
									}*/
									$('table[name="tabel_user"] .ui.toggle.checkbox.user').checkbox({
										onChange: function () {
											var ini = $(this).closest('.checkbox'),
												row = $(this).closest('tr'),
												aktif = 0;
											if (ini.checkbox('is checked')) {
												aktif = 1;
											}
											var id_row = ini.attr('id_row');
											if (typeof id_row === "undefined") {
												id_row = row.attr("id_row");
												if (typeof id_row === "undefined") {
													id_row = ini.closest("div.item").attr("id_row");
													if (typeof id_row === "undefined") {
														id_row = ini.closest("form").attr("id_row");
													}
												}
											}
											var tbl = ini.attr('tbl');
											if (typeof tbl === "undefined") {
												tbl = row.attr("tbl");
												if (typeof tbl === "undefined") {
													tbl = ini.closest("div.item").attr("tbl");
													if (typeof tbl === "undefined") {
														tbl = ini.closest("form").attr("tbl");
													}
												}
											}
											if (id_row) {
												var data = {
													jenis: tbl,
													tbl: ini.attr('jns'),
													id: id_row,
													klm: ini.attr('klm'),
													txt: aktif,
													ubah: 'non',
												}
												suksesAjax['ajaxku'] = function (result) {
													var warna = 'green';
													if (result.success === false) {
														warna = 'warning';
													}
													loaderHide();
													showToast(result.error.message, {
														class: warna,
														icon: "check circle icon",
													});
												}
												runAjax("script/edit_cell", "POST", data, 'Json', undefined, undefined, "ajaxku");
											}
											console.log("onChange called");
										},
									});

									break;
								default:
									break;
							}
							break;
						case "profil":
							var user = result.data.users;
							var form = $('form[name="pengaturan"]');
							$('form[name="profil"]').form("set values", {
								nama: user.nama,
								username: user.username,
								email: user.email,
								dum_file: user.photo,
								tgl_login: user.tgl_login,
								kontak_person: user.kontak_person,
								nama_org: user.nama_org,
								warna_tbl: user.warna_tbl,
								thn_aktif_anggaran: user.thn_aktif_anggaran,
								kd_proyek_aktif: user.kd_proyek_aktif,
							});
							$(".ui.font.slider").slider("set value", user.font_size);
							addRulesForm($('form[name="profil"].ui.form'));
							//card
							var card = $('div[data-tab="profil"] .ui.card');
							var child_card = card.children();
							var content = child_card.eq(1);
							card.find("img").attr("src", user.photo);
							content.find(".header").text(user.nama);
							content.find(".form.description textarea").val(user.ket);
							content = child_card.eq(2);
							content.find("span.right").text("Joined " + user.tgl_daftar);
							//console.log(content);
							break;
						case "impor":
							break;
					}
					$("[rms]").mathbiila();
				} else {
					//console.log(tabel);
					if (tabel !== undefined && tabel.length > 0) {
						tabel.find("thead").empty();
						tabel.find("tbody").html(addHtml("null"));
						tabel.find("tfoot").empty();
					}
				}
				loaderHide();
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		}
	});

	var minDate, maxDate;
	//=====================================================
	//=========== flyout =================
	//=====================================================
	$(".context.example .ui.flyout").flyout({
		//non aktifkan toggle jika tekan dimmer
		selector: { pusher: '.flyout.pusher' },
		className: { pushable: '.bottom.pushable' },
		closable: false,
		onShow: function () {
			loaderHide();
			console.log('onShow flyout');
		},
		onHide: function (choice) {
			//console.log(choice);
			let form = $(".ui.flyout form");
			form.form('clear');
			removeRulesForm(form);
		},
		onApprove: function (elemen) {
			$(elemen).closest('div.flyout').find('form').form('submit');
			return false;
		},
		context: $('.context.example .bottom.segment.pushable'),
	});//.flyout('attach events', '[name="flyout"]');
	//=====================================================
	//===========button ambil data/get_data/ flyout ==========
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
		var id_row = ini.attr("id_row");
		if (typeof id_row === "undefined") {
			id_row = ini.closest("tr").attr("id_row");
			if (typeof id_row === "undefined") {
				id_row = ini.closest("div.item").attr("id_row");
			}
		}
		switch (jenis) {
			case 'rekanan':
			case "analisa_quarry":
			case "analisa_ck":
			case "analisa_alat_custom":
			case "analisa_sda":
			case "analisa_bm":
			case "analisa_alat":
				switch (tbl) {
					case 'cek_kode':
						data.kode = ini.closest(".input").find('input').val();//'input[name="kode"]'
						if (data.kode.length > 1) {
							jalankanAjax = true;
						} else {
							showToast("Input dahulu kode analisa sebelum cek data", {
								class: "warning",
								icon: "check circle icon",
							});
						}
						break;

					default:
						break;
				}
				break;
			case "copy":
				switch (tbl) {
					case 'copy_lap_harian':
						var tanggalLapHarian = $('.ui.calendar[name="tgl_lap_harian"]').calendar('get date');
						if (tanggalLapHarian === null || typeof tanggalLapHarian === 'undefined') {
							attrName = 'pilih tanggal laporan harian';
							showToast("pilih tanggal laporan harian", {
								class: "warning",
								icon: "exclamation circle icon",
							});
						}
						break;
					case 'proyek':
						break;

					default:
						break;
				}
				break;
			case "monev":
				switch (tbl) {
					case 'lap-harian_edit':
						var elementTD = ini.closest('tr').children();
						var dataC = {};
						var i = 0
						Object.keys(elementTD).forEach((key) => {
							var element = $(elementTD[key]);

							var attrKlm = element.attr('klm');

							var txt = element.text().trim();
							if (typeof attrKlm !== "undefined") {
								//console.log(`txt = ${txt}`);
								dataC[attrKlm] = txt;
							}
							i++;
						});
					case 'lap-harian':
						var tanggalLapHarian = $('.ui.calendar[name="tgl_lap_harian"]').calendar('get date');
						if (tanggalLapHarian === null || typeof tanggalLapHarian === 'undefined') {
							attrName = 'pilih tanggal laporan harian';
							showToast("pilih tanggal laporan harian", {
								class: "warning",
								icon: "exclamation circle icon",
							});
						} else {
							tanggalLapHarian = new Date(tanggalLapHarian);
						}
						break;
					default:
						break;
				}
				break;
			case "proyek":
				switch (tbl) {
					case "cek_kode":
						data.text = ini.closest(".input").find('input[name="kode"]').val();
						if (data.text.length > 1) {
							jalankanAjax = true;
						} else {
							showToast("Input dahulu kode proyek sebelum cek data", {
								class: "warning",
								icon: "check circle icon",
							});
						}
						break;
					case "edit":
						data.id_row = id_row;
						if (data.id_row >= 1) {
							jalankanAjax = true;
						} else {
							showToast("Data tidak ada", {
								class: "warning",
								icon: "check circle icon",
							});
						}
						break;
					default:
						break;
				}
				break;
			case "harga_satuan":
				switch (tbl) {
					case "edit":
						data.id_row = id_row;
						if (data.id_row >= 1) {
							jalankanAjax = true;
						} else {
							showToast("Data tidak ada", {
								class: "warning",
								icon: "check circle icon",
							});
						}
						break;
					default:
						break;
				}
				break;
			case "x":
				switch (tbl) {
					case "y":
						break;
					default:
						break;
				}
				break;
			default:
				break;
		}
		console.log(`jenis = ${jenis}; tbl = ${tbl}; id_row = ${id_row}; attrName = ${attrName}`);
		//MASUKKAN HTML DI FLY OUT
		if (attrName === "flyout") {
			formIni.attr("tbl", tbl).attr("jns", jenis);
			if (typeof id_row !== "undefined") {
				formIni.attr("id_row", id_row)
			}
			//tampilkan flyout
			//$(".ui.flyout").flyout("toggle");
			switch (jenis) {
				//$(button).attr("name", "flyout").attr("jns", "lokasi").attr("tbl", "go-to");
				case 'rekanan':
					switch (tbl) {
						case 'edit':
							data.id_row = id_row;
							dataHtmlku.icon = "edit icon";
							dataHtmlku.header = "Edit data";
							jalankanAjax = true;
						case 'input':
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
						default:
							break;
					}
					break;
				case "profil":
					switch (tbl) {
						case 'edit':
							dataHtmlku.konten = buatElemenHtml("fieldTextAction", {
								label: "username",
								atribut: 'name="username" placeholder="username..." readonly',
								atributLabel: `name="get_data" jns="${jenis}" tbl="cek_kode"`,
							}) +
								buatElemenHtml("fieldText", {
									label: "email",
									atribut: 'name="email" placeholder="email..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nama Lengkap",
									atribut: 'name="nama" placeholder="Nama Lengkap..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Kontak Person",
									atribut: 'name="kontak_person" placeholder="No.HP/Alamat..."',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Type User",
									atribut: 'name="type_user"',
									kelas: "lainnya selection",
									dataArray: [
										['admin', "admin"],
										['user', "user"]
									]
								}) +
								buatElemenHtml("fieldText", {
									label: "Organisasi",
									atribut: 'name="nama_org" placeholder="Organisasi..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="ket" rows="2"',
								});
							dataHtmlku.icon = "edit icon";
							dataHtmlku.header = "Edit user AHSP";
							let elementTD = ini.closest('tr').children();
							var dataC = {};
							let i = 0
							Object.keys(elementTD).forEach((key) => {
								let element = $(elementTD[key]);

								let attrKlm = element.attr('klm');

								let txt = element.text().trim();
								if (typeof attrKlm !== "undefined") {
									//console.log(`txt = ${txt}`);
									dataC[attrKlm] = txt;
								}
								i++;
							});
							//console.log(dataC);
							break;
						default:
							break;
					}
					break;
				case "copy":
					switch (tbl) {
						case "analisa_alat":
						case "analisa_quarry":
						case "analisa_bm":
						case "analisa_sda":
						case "analisa_ck":
						case "analisa_alat_custom":
							var content = ini.closest('div.item').find('div.content div.header').text().split(':');
							function returnVal(data) {
								if (data.trim().length > 0) {
									return ` value="${data.trim()}" `;
								} else {
									return '';
								}
							};
							let kd_analisa = returnVal(content[0]);
							let uraian = returnVal(content[1]);
							dataHtmlku.konten =
								buatElemenHtml("dividerHeader", { label: "Data" }) +
								buatElemenHtml("fieldText", {
									label: "Kode",
									atribut:
										`placeholder="Kode Analisa.." name="kode_copy"${kd_analisa}readonly`,
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut:
										`placeholder="Kode Analisa.." name="uraian_copy"${uraian}readonly`,
								}) +
								buatElemenHtml("dividerHeader", { label: "Input" }) +
								buatElemenHtml("fieldTextAction", {
									label: "Kode Analisa",
									atribut: `name="kode" placeholder="Kode Analisa..."`,
									txtLabel: "cek",
									atributLabel: `name="get_data" jns="${tbl}" tbl="cek_kode"`,
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut: 'name="uraian" placeholder="Uraian..."',
								});
							if (tbl === 'analisa_quarry') {
								dataHtmlku.konten += buatElemenHtml("fieldText", {
									label: "Lokasi",
									atribut: 'name="lokasi" placeholder="Lokasi..."',
								}) + buatElemenHtml("fieldText", {
									label: "Tujuan",
									atribut: 'name="tujuan" placeholder="Tujuan..."',
								});
							}
							dataHtmlku.icon = "copy icon";
							dataHtmlku.header = "Salin dan Tempel Analisa";
							break;
						case 'copy_lap_harian':
							tanggalLapHarian = new Date(tanggalLapHarian);
							dataHtmlku.konten =
								buatElemenHtml("dividerHeader", { label: "Data" }) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Referensi",
									kelas: "laporan disabled",
									atribut:
										'placeholder="Input Tanggal.." name="tanggal_copy" readonly',
								}) +
								buatElemenHtml("dividerHeader", { label: "Input" }) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal Paste",
									kelas: "laporan",
									atribut:
										'placeholder="Input Tanggal.." name="tanggal" readonly',
								});
							dataHtmlku.icon = "copy icon";
							dataHtmlku.header = "Salin dan Tempel Laporan Harian";
							break;
						case 'proyek':
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "Kode Dokumen",
									atribut: 'name="kd_proyek_copy" readonly',
								}) +
								buatElemenHtml("dividerHeader", { label: "Dokumen Baru" }) +
								buatElemenHtml("fieldTextAction", {
									label: "Kode Dokumen",
									atribut: 'name="kode" placeholder="Kode Proyek (M00x)..."',
									txtLabel: "cek",
									atributLabel: 'name="get_data"  jns="proyek" tbl="cek_kode"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian/Nama Proyek",
									atribut: 'name="uraian" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tahun",
									atribut:
										'placeholder="Input Tahun.." name="tahun" onkeypress="return angka(event);" maxlength="4" size="4" readonly',
									kelas: "year",
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									atribut: 'name="copy_realisasi_proyek" non_data',
									txtLabel: 'Salin Realisasi Proyek'
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="aktifkan_proyek" non_data',
									txtLabel: "Aktifkan sebagai proyek default",
								});
							dataHtmlku.icon = "copy icon";
							dataHtmlku.header = "Copy Proyek";
							var elementTD = ini.closest('tr').children();
							var dataC = [];
							var i = 0
							Object.keys(elementTD).forEach((key) => {
								var element = $(elementTD[key]);
								var txt = element.text().trim();
								dataC[i] = txt;
								i++;
							});

							break;

						default:
							break;
					}
					break;
				case "monev":
					switch (tbl) {
						case "import":
							//elemen = `<div class="ui icon message"><i class="${iconData}"></i><div class="content"><div class="header">${labelData} </div><a ${atributData}  target="_blank">${valueData}</a></div></div>`;
							dataHtmlku.konten =
								buatElemenHtml("messageLink", {
									label: "Ungguh File",
									atribut: ' href="temp/realisasi_import.xlsx"',
									value: "ungguh format impor file realisasi",
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File (*.xlsx)...",
									accept: ".xlsx",
								}) +
								buatElemenHtml("fieldDropdown", {
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
							dataHtmlku.icon = "excel file icon";
							dataHtmlku.header = "Import Data Realisasi";
							jalankanAjax = true;
							url = "script/writer_xlsx",
								data = {
									jenis: "monev",
									tbl: "import_realisasi",
								};
							break;
						case "edit":
							data.id_row = id_row;
						case "input":
							data.rows = "all";
							dataHtmlku.konten =
								buatElemenHtml("fieldDropdown", {
									label: "Uraian",
									atribut: 'name="uraian"',
									kelas: "uraian selection search",
								}) +
								buatElemenHtml("dividerHeader", { label: "Data" }) +
								buatElemenHtml("fieldTextLabelKanan", {
									label: "Volume",
									txtLabel: "sat",
									atributLabel: 'name="satuan"',
									atribut: 'name="volume" placeholder="Volume..." readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Jumlah Harga",
									atribut:
										'name="jumlah_harga" placeholder="Jumlah Harga" readonly non_data',
								}) +
								buatElemenHtml("fieldTextLabelKanan", {
									label: "realisasi fisik s/d sekarang",
									txtLabel: "sat",
									atributLabel: 'name="satuan"',
									atribut:
										'name="realisasi_fisik_now" placeholder="Realisasi..." readonly non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "realisasi keuangan s/d sekarang",
									atribut:
										'name="realisasi_keu_now" placeholder="Realisasi..." readonly non_data',
								}) +
								buatElemenHtml("dividerHeader", { label: "Input" }) +
								buatElemenHtml("fieldCalendar", {
									label: "Tanggal",
									kelas: "laporan",
									atribut:
										'placeholder="Input Tanggal.." name="tanggal" onkeypress="return angka(event);" maxlength="4" size="4" readonly',
								}) +
								buatElemenHtml("fieldTextLabelKanan", {
									label: "realisasi fisik",
									txtLabel: "sat",
									atributLabel: 'name="satuan"',
									atribut:
										'name="realisasi_fisik" placeholder="Realisasi..." rms onkeypress="return rumus(event);" oninput="checkMinMax(this);"',
								}) +
								buatElemenHtml("fieldText", {
									label: "realisasi keuangan",
									atribut:
										'name="realisasi_keu" placeholder="Realisasi..." non_data rms onkeypress="return rumus(event);checkMinMax(this);" oninput="checkMinMax(this);"',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="2" non_data',
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File...",
									accept: ".jpg,.jpeg,.png,.pdf,.xlsx,.docx,.mp4",
									atribut: "non_data",
								});
							dataHtmlku.icon = "file alternate icon";
							dataHtmlku.header = "Input Realisasi";
							jalankanAjax = true;
							if (tbl === "edit") {
								dataHtmlku.header = "Edit Realisasi";
								dataHtmlku.icon = "edit alternate icon";
								formIni.attr("id_row", id_row);
							}
							break;
						case 'lap-harian_edit':
						case 'lap-harian':
							dataHtmlku.konten = buatElemenHtml("dividerHeader", { label: "Data" }) +
								buatElemenHtml("fieldTextIcon", {
									label: "Tanggal",
									kelas: "laporan disabled",
									atribut:
										'placeholder="Input Tanggal.." name="tanggal" readonly',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Jenis Data",
									atribut: 'name="type"',
									kelas: "type_lap_harian selection",
									dataArray: [
										["upah", "Tenaga Kerja"],
										["bahan", "Bahan"],
										["peralatan", "Alat yang digunakan"],
										["cuaca", "Cuaca"],
										["note", "Catatan"],
									],
								}) + buatElemenHtml("dividerHeader", { label: "Input" }) + '<div name="segment"></div>';
							//buatkan fungsi dropdown untu
							break;

						default:
							break;
					}
					break;
				case "peraturan":
					switch (tbl) {
						case "input":
							dataHtmlku.icon = "file alternate icon";
							dataHtmlku.header = "Tambah Data/Peraturan";
						case "edit":
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut: 'name="uraian" placeholder="Uraian..."',
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
								buatElemenHtml("fieldDropdown", {
									label: "Status Data",
									atribut: 'name="status"',
									kelas: "lainnya selection",
									dataArray: [
										["umum", "Umum"],
										["rahasia", "Rahasia"],
										["proyek", "Dokumen kegiatan"]
									],
								})+
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
						default:
							break;
					}
					break;
				case "lokasi":
					switch (tbl) {
						case "go-to":
							dataHtmlku.konten = `<div class="field"><label>Latitude (lintang)</label><div class="three fields"><div class="field"><input type="text" name="latitude[derajat]" placeholder="derajat"></div><div class="field"><input type="text" name="latitude[menit]" placeholder="'"></div><div class="field"><input type="text" name="latitude[detik]" placeholder='"'></div></div></div><div class="field"><label>Longitude (bujur)</label><div class="three fields"><div class="field"><input type="text" name="longitude[derajat]" placeholder="derajat"></div><div class="field"><input type="text" name="longitude[menit]" placeholder="'"></div><div class="field"><input type="text" name="longitude[detik]" placeholder='"'></div></div></div>`;
							dataHtmlku.icon = "search icon";
							dataHtmlku.header = "Ke Lokasi";
							break;
						default:
							break;
					}
					break;
				case "rab":
					switch (tbl) {
						case "edit":
							dataHtmlku.konten =
								buatElemenHtml("fieldSearch", {
									label: "Cari Analisa",
									atribut: 'placeholder="Cari Analisa.."',
									kelas: "analisa",
								}) +
								buatElemenHtml("fieldText", {
									label: "Kode",
									atribut: 'name="kode" readonly',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian",
									atribut:
										' rows="2" name="uraian" placeholder="Uraian..." readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Volume",
									atribut:
										'name="volume" placeholder="Volume..." rms autofocus',
								}) +
								buatElemenHtml("fieldText", {
									label: "Satuan",
									atribut: 'name="satuan" placeholder="Satuan..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Harga Dasar HSP",
									atribut:
										'name="harga_dasar" placeholder="Harga Dasar..." readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Biaya Operasional & Overhead",
									atribut:
										'name="jumlah_op" placeholder="Biaya Operasional & Overhead..." readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Harga Satuan",
									atribut:
										'name="harga_satuan" placeholder="Harga Satuan..." readonly',
								});
							dataHtmlku.icon = "edit icon";
							dataHtmlku.header = "Edit Pekerjaan";
							//var id_row = ini.attr('id_row');
							data.id_row = id_row;
							jalankanAjax = true;
							formIni.attr("id_row", id_row);
							break;
						default:
							break;
					}
					break;
				case "schedule":
					switch (tbl) {
						case "edit":
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "KODE",
									atribut: 'name="kd_analisa" readonly',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Uraian",
									atribut:
										' rows="2" name="uraian" placeholder="Uraian..." readonly',
								}) +
								buatElemenHtml("fieldText", {
									label: "Durasi",
									atribut: 'name="durasi" placeholder="Durasi..." rms',
								}) +
								buatElemenHtml("fieldText", {
									label: "mulai",
									atribut: 'name="mulai" placeholder="Start..." rms',
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Dependent",
									atribut:
										'name="dependent" placeholder="pilih dependent..." non_data',
									kelas: "fluid multiple search selection",
									dataArray: [[1, "item pekerjaan"]],
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								});
							dataHtmlku.icon = "edit icon";
							dataHtmlku.header = "Edit Pekerjaan";
							//var id_row = ini.attr('id_row');
							data.id_row = id_row;
							jalankanAjax = true;
							formIni.attr("id_row", id_row);
							break;
						default:
							break;
					}
					break;
				case "sbu":
					dataHtmlku.konten =
						buatElemenHtml("fieldSearch", {
							label: "Cari Analisa",
							atribut: 'placeholder="Cari Analisa.."',
							kelas: "analisa",
						}) +
						buatElemenHtml("fieldText", {
							label: "Kode",
							atribut: 'name="kode" readonly',
						}) +
						buatElemenHtml("fieldTextarea", {
							label: "Uraian",
							atribut:
								' rows="2" name="uraian" placeholder="Uraian..." readonly',
						}) +
						buatElemenHtml("fieldText", {
							label: "Volume",
							atribut: 'name="volume" placeholder="Volume..." rms autofocus',
						}) +
						buatElemenHtml("fieldText", {
							label: "Satuan",
							atribut: 'name="satuan" placeholder="Satuan..."',
						}) +
						buatElemenHtml("fieldText", {
							label: "Harga Dasar HSP",
							atribut:
								'name="harga_dasar" placeholder="Harga Dasar..." readonly',
						}) +
						buatElemenHtml("fieldText", {
							label: "Biaya Operasional & Overhead",
							atribut:
								'name="jumlah_op" placeholder="Biaya Operasional & Overhead..." readonly',
						}) +
						buatElemenHtml("fieldText", {
							label: "Harga Satuan",
							atribut:
								'name="harga_satuan" placeholder="Harga Satuan..." readonly',
						});
					dataHtmlku.icon = "add icon";
					dataHtmlku.header = "Tambah Analisa";
					switch (tbl) {
						case "bm":
							break;
						default:
							break;
					}
					break;
				case "proyek":
					switch (tbl) {
						case "tambah_proyek":
							dataHtmlku.konten = buatElemenHtml("fieldTextAction", {
								label: "Kode Proyek",
								atribut: 'name="kode" placeholder="Kode Proyek (M00x)..."',
								txtLabel: "cek",
								atributLabel: 'name="get_data"  jns="proyek" tbl="cek_kode"',
							});
							dataHtmlku.konten +=
								buatElemenHtml("fieldText", {
									label: "Uraian/Nama Proyek",
									atribut: 'name="uraian" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tahun",
									atribut:
										'placeholder="Input Tahun.." name="tahun" readonly',
									kelas: "year",
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="aktifkan_proyek" non_data',
									txtLabel: "Aktifkan sebagai proyek default",
								});
							dataHtmlku.icon = "file alternate icon";
							dataHtmlku.header = "Tambah Pekerjaan Baru";
							break;
						case "edit":
							dataHtmlku.konten = buatElemenHtml("fieldText", {
								label: "Kode Proyek",
								atribut: 'name="kode" readonly',
							});
							dataHtmlku.konten +=
								buatElemenHtml("fieldText", {
									label: "Uraian/Nama Proyek",
									atribut: 'name="uraian" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldCalendar", {
									label: "Tahun",
									atribut:
										'placeholder="Input Tahun.." name="tahun" readonly',
									kelas: "year",
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Keterangan",
									atribut: 'name="keterangan" rows="4"',
								}) +
								buatElemenHtml("fielToggleCheckbox", {
									label: "",
									atribut: 'name="aktifkan_proyek" non_data',
									txtLabel: "Aktifkan sebagai proyek default",
								});
							dataHtmlku.icon = "edit icon";
							dataHtmlku.header = "Edit Pekerjaan";
							break;
						case "del_proyek":
							dataHtmlku.icon = "trash alternate outline icon";
							dataHtmlku.header = "Hapus Pekerjaan";
							break;
						case "g":
							break;
						default:
							break;
					}
					break;
				case "divisiBM":
				case "divisiCK":
				case "divisiSDA":
				case "divisi":
					switch (tbl) {
						case "import":
							dataHtmlku.konten =
								buatElemenHtml("fieldCalendar", {
									label: "Tahun",
									atribut:
										'placeholder="Input Tahun.." name="tahun" readonly',
									kelas: "year",
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Bidang",
									atribut: 'name="bidang"',
									kelas: "lainnya selection",
									dataArray: [
										["bm", "Binamarga"],
										["ck", "Cipta Karya"],
										["sda", "Sumber daya Air"],
									],
								}) +
								buatElemenHtml("fieldFileInput2", {
									label: "Pilih File Dokumen",
									placeholderData: "Pilih File (*.xlsx)...",
									accept: ".xlsx",
								}) +
								buatElemenHtml("fieldDropdown", {
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
							dataHtmlku.icon = "excel file icon";
							dataHtmlku.header = "Import Data Divisi";
							break;
						case "edit":
							dataHtmlku.icon = "edit icon";
							dataHtmlku.header = "Edit Divisi";
							data.id_row = ini.attr("id_row");
							if (data.id_row >= 1) {
								jalankanAjax = true;
							} else {
								showToast("Data tidak ada", {
									class: "warning",
									icon: "check circle icon",
								});
							}
						case "input":
							dataHtmlku.konten =
								buatElemenHtml("fieldCalendar", {
									label: "Tahun",
									atribut:
										'placeholder="Input Tahun.." name="tahun" readonly',
									kelas: "year",
								}) +
								buatElemenHtml("fieldDropdown", {
									label: "Bidang",
									atribut: 'name="bidang"',
									kelas: "lainnya selection",
									dataArray: [
										["bm", "Binamarga"],
										["ck", "Cipta Karya"],
										["sda", "Sumber daya Air"],
									],
								}) +
								buatElemenHtml("fieldText", {
									label: "Kode",
									atribut: 'name="kode" placeholder="Kode..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut: 'name="uraian" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..."',
								});
							if (tbl === "input") {
								dataHtmlku.icon = "add icon";
								dataHtmlku.header = "Tambah Divisi";
							}
							break;
						default:
							break;
					}
					break;
				case "harga_satuan":
					switch (tbl) {
						case "import":
						case "import_basic_price":
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
						case "edit":
							//var id_row = ini.attr('id_row');
							data.id_row = id_row;
							jalankanAjax = true;
							formIni.attr("id_row", id_row);
						case "input": //tambah data
							//dropdown
							if (tbl === "edit") {
								dataHtmlku.icon = "edit icon";
								dataHtmlku.header = "Edit Basic Price";
							} else {
								dataHtmlku.icon = "tasks icon";
								dataHtmlku.header = "Tambah Data Basic Price";
							}
							dataHtmlku.konten = buatElemenHtml("fieldDropdown", {
								label: "Jenis Basic Price",
								atribut: 'name="jenis_basic_price" value="bahan"',
								kelas: "lainnya selection",
								dataArray: [
									["upah", "Upah"],
									["bahan", "Bahan"],
									["peralatan", "Peralatan"],
									["royalty", "Bahan Royalty"],
								],
							});
							//text field
							dataHtmlku.konten +=
								buatElemenHtml("fieldText", {
									label: "Kode",
									atribut:
										'name="kode" placeholder="Kode basic price (contoh:MH01).." maxlength="15" size="15"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut: 'name="uraian" placeholder="Uraian..."',
								});
							//dropdown
							dataHtmlku.konten += buatElemenHtml("fieldDropdown", {
								label: "Satuan",
								atribut: 'name="satuan"',
								textDrpdown: "Pilih Satuan...",
								kelas: "fluid search satuan two column selection",
							});
							//text field
							dataHtmlku.konten +=
								buatElemenHtml("fieldText", {
									label: "Harga Satuan",
									atribut:
										'name="harga_satuan" placeholder="Harga Satuan..." onkeypress="return desimal(event);"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Sumber Data",
									atribut: 'name="sumber_data" placeholder="Sumber Data..."',
								}) +
								buatElemenHtml("fieldTextarea", {
									label: "Spesifikasi",
									atribut: 'rows="2" name="spesifikasi"',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..."',
								});
							break;
						default:
							break;
					}
					break;
				case "satuan":
					switch (tbl) {
						case "import":
						case "import_satuan":
							dataHtmlku.icon = "file excel icon green";
							dataHtmlku.header = "Import data dari file Excel";
							//file
							dataHtmlku.konten =
								buatElemenHtml("fieldFileInput2", {
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
						case "edit":
							//var id_row = ini.attr('id_row');
							data.id_row = id_row;
							jalankanAjax = true;
							formIni.attr("id_row", id_row);
						case "input": //tambah data
							//dropdown
							dataHtmlku.icon = "tasks icon";
							dataHtmlku.header = "Tambah Data";
							if (tbl === "edit") {
								dataHtmlku.icon = "edit icon";
								dataHtmlku.header = "Edit satuan";
							} else {
								dataHtmlku.icon = "tasks icon";
								dataHtmlku.header = "Tambah satuan";
							}
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "Value",
									atribut:
										'name="value" placeholder="Kode satuan (contoh:m3/m2).."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut: 'name="item" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut: 'name="keterangan" placeholder="Keterangan..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Nama/sebutan lain satuan",
									atribut: 'name="sebutan_lain" placeholder="Sebuatan Lain..."',
								});
							break;
						default:
							break;
					}
					break;
				case "analisa_ck":
				case "analisa_alat":
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//file
					dataHtmlku.konten = buatElemenHtml("fieldFileInput2", {
						label: "Pilih File Dokumen",
						placeholderData: "Pilih File (*.xlsx)...",
						accept: ".xlsx",
					});
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
				case "analisa_quarry":
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//text
					dataHtmlku.konten =
						buatElemenHtml("fieldDropdown", {
							label: "Jenis",
							atribut: 'name="kode_quarry" placeholder="Uraian..."',
							kelas: "quarry search selection",
						}) +
						buatElemenHtml("fieldText", {
							label: "Lokasi",
							atribut:
								'name="lokasi" placeholder="Lokasi...(Quarry)" value="Quarry"',
						}) +
						buatElemenHtml("fieldText", {
							label: "Tujuan",
							atribut:
								'name="tujuan" placeholder="Tujuan...(Base Camp)" value="Base Camp"',
						});
					//file
					dataHtmlku.konten += buatElemenHtml("fieldFileInput2", {
						label: "Pilih File Dokumen",
						placeholderData: "Pilih File (*.xlsx)...",
						accept: ".xlsx",
					});
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
				case "analisa_alat_custom":
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//text
					dataHtmlku.konten =
						buatElemenHtml("fieldText", {
							label: "Kode Peralatan",
							atribut: 'name="kd_analisa" placeholder="Kode Alat"',
						}) +
						buatElemenHtml("fieldText", {
							label: "Nama Peralatan",
							atribut: 'name="jenis_pek" placeholder="Nama Peralatan..."',
						});
					//file
					dataHtmlku.konten += buatElemenHtml("fieldFileInput2", {
						label: "Pilih File Dokumen",
						placeholderData: "Pilih File (*.xlsx)...",
						accept: ".xlsx",
					});
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
				case "analisa_sda":
				case "analisa_bm":
					dataHtmlku.icon = "file excel icon green";
					dataHtmlku.header = "Import data dari file Excel";
					//text
					dataHtmlku.konten =
						buatElemenHtml("fieldText", {
							label: "Item Pembayaran No.",
							atribut:
								'name="kd_analisa" placeholder="Item Pembayaran Nomor :"',
						}) +
						buatElemenHtml("fieldText", {
							label: "Jenis Pekerjaan",
							atribut: 'name="jenis_pek" placeholder="Jenis Pekerjaan : ..."',
						}) +
						buatElemenHtml("fieldText", {
							label: "Satuan Pembayaran",
							atribut:
								'name="satuan_pembayaran" placeholder="Satuan Pembayaran..."',
						});
					//file
					dataHtmlku.konten += buatElemenHtml("fieldFileInput2", {
						label: "Pilih File Dokumen",
						placeholderData: "Pilih File (*.xlsx)...",
						accept: ".xlsx",
					});
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
				case "informasi_umum":
					dataHtmlku.icon = "plus icon";
					dataHtmlku.header = "Tambahkan informasi";
					switch (tbl) {
						case 'input':
							dataHtmlku.konten =
								buatElemenHtml("fieldText", {
									label: "Nomor",
									atribut:
										'name="nomor_uraian" placeholder="Nomor" non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Uraian",
									atribut: 'name="uraian" placeholder="Uraian..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Kode",
									atribut:
										'name="kode" placeholder="Kode (jangan ganda)..."',
								}) +
								buatElemenHtml("fieldText", {
									label: "Koefisien",
									atribut:
										'name="nilai" placeholder="Koefisien (nilai)..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Satuan",
									atribut:
										'name="satuan" placeholder="Satuan (unit)..." non_data',
								}) +
								buatElemenHtml("fieldText", {
									label: "Keterangan",
									atribut:
										'name="keterangan" placeholder="Keterangan..." non_data',
								});
							break;
						default:
							break;
					}
					break;
				case "x":
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
			calendarYear.Type('year');
			calendarYear.runCalendar();
			$('div[name="jml_header"]').dropdown("set selected", 1);
			$('.ui.accordion').accordion();
			$("[rms]").mathbiila();
			switch (jenis) {
				case "peraturan":
					switch (tbl) {
						case "input":
						case "edit":
							formIni.find('.ui.dropdown.lainnya').dropdown();
							break;
						default:
							break;
					}
					break;
				case "profil":
					switch (tbl) {
						case 'edit':
							console.log(dataC);
							//dataC = {};
							Object.keys(dataC).forEach((key) => {
								var elm = formIni.find(`[name="${key}"]`);
								if (typeof elm !== 'undefined') {
									//accounting.formatNumber(jumlah_harga,jumlah_harga.countDecimals(),".",",")
									let value = dataC[key];
									$('form[name="form_flyout"]').form("set value", key, value)
								}
							});
							break;
						default:
							break;
					}
					break;
				case "monev":
					switch (tbl) {
						case 'import':
							$('div[name="jml_header"]').dropdown("set selected", 2);
							break;
						case 'lap-harian_edit':
							var nameTable = ini.closest('table').attr('name');
							var selectJenis = 'upah';
							switch (nameTable) {
								case 'lap_harian_manpower':
									selectJenis = 'upah';
									break;
								case 'lap_harian_bahan':
									selectJenis = 'bahan';
									break;
								case 'lap_harian_alat':
									selectJenis = 'peralatan';
									break;
								case 'lap_harian_cuaca':
									selectJenis = 'cuaca';
									break;
								case 'lap_harian_note':
									selectJenis = 'note';
									break;
								default:
									break;
							}
						case 'lap-harian':
							//buatkan fungsi dropdown
							//formIni.form("set value", 'tanggal', ini.closest('.container').find('.floated.calendar.laporan input').val());tanggal = `${tanggal.getFullYear()}-${tanggal.getMonth() + 1}-${tanggal.getDate()}`;//local time
							var tanggal = `${tanggalLapHarian.getFullYear()}-${tanggalLapHarian.getMonth() + 1
								}-${tanggalLapHarian.getDate()}`;//local time zone
							formIni.form("set value", 'tanggal', tanggal);
							formIni.find('.ui.dropdown.type_lap_harian').dropdown({
								onChange: function (value, text, $choice) {
									var segmentForm = formIni.find('div[name="segment"]');
									let elmSegmentForm = buatElemenHtml("fieldText", {
										label: "Uraian",
										atribut:
											'name="uraian" placeholder="Uraian..."',
									});
									let autofocus = '';
									if (value === 'cuaca') {
										elmSegmentForm = '';//uraian otomatis terisi cuaca dan value di mysql tdk usah diinput
										autofocus = ' autofocus';
									}
									switch (value) {
										case 'upah':
											elmSegmentForm +=
												buatElemenHtml("fieldText", {
													label: "Kode Analisa",
													atribut:
														'name="kd_analisa" placeholder="Kode analisa..." ',
												}) +
												buatElemenHtml("fieldText", {
													label: "Kode Tenaga Kerja",
													atribut:
														'name="kode" placeholder="Kode Tenaga Kerja..."',
												}) +
												buatElemenHtml("fieldText", {
													label: "Jumlah",
													atribut:
														'name="value-jumlah" placeholder="Jumlah..." rms onkeypress="return rumus(event);" non_data',
												}) +
												buatElemenHtml("fieldText", {
													label: "Satuan",
													atribut: 'name="value-satuan"',
												});
											break;
										case 'bahan':
										case 'quarry':
											elmSegmentForm +=
												buatElemenHtml("fieldText", {
													label: "Kode Analisa",
													atribut:
														'name="kd_analisa" placeholder="Kode analisa..." ',
												}) +
												buatElemenHtml("fieldText", {
													label: "Kode Tenaga Kerja",
													atribut:
														'name="kode" placeholder="Kode Tenaga Kerja..."',
												}) +
												buatElemenHtml("fieldText", {
													label: "Jumlah Diterima",
													atribut:
														'name="value-diterima" placeholder="Jumlah..." rms onkeypress="return rumus(event);" non_data',
												}) +
												buatElemenHtml("fieldText", {
													label: "Jumlah Ditolak",
													atribut:
														'name="value-ditolak" placeholder="Jumlah..." rms onkeypress="return rumus(event);" non_data',
												}) +
												buatElemenHtml("fieldText", {
													label: "Satuan",
													atribut: 'name="value-satuan"',
												});
											break;
										case 'peralatan':
											elmSegmentForm += buatElemenHtml("fieldText", {
												label: "Jumlah Diterima",
												atribut:
													'name="value-diterima" placeholder="Jumlah..." rms onkeypress="return rumus(event);" non_data',
											}) +
												buatElemenHtml("fieldText", {
													label: "Jumlah Ditolak",
													atribut:
														'name="value-ditolak" placeholder="Jumlah..." rms onkeypress="return rumus(event);" non_data',
												}) +
												buatElemenHtml("fieldText", {
													label: "Merk/Type",
													atribut:
														'name="value-merk_type" placeholder="Merk/Type..."',
												});
											break;
										case 'cuaca':
											elmSegmentForm += buatElemenHtml("fieldCalendar", {
												label: "Jam",
												kelas: "jam",
												atribut:
													'name="uraian-jam" placeholder="Jam..." readonly',
											}) +
												buatElemenHtml("fieldDropdown", {
													label: "Cuaca",
													atribut: 'name="value-cuaca"',
													kelas: "lainnya selection",
													dataArray: [
														["cerah", "Cerah", '<i class="sun icon"></i>'],
														["mendung", "Mendung", '<i class="cloud sun icon"></i>'],
														["angin_kencang", "Angin Kencang", '<i class="wind icon"></i>'],
														["gerimis", "Gerimis", '<i class="cloud sun rain icon"></i>'],
														["hujan_lebat", "Hujan Lebat", '<i class="cloud rain icon"></i>'],
														["lainnya", "Lainnya", '<i class="temperature high icon"></i>'],
													],
												})
											break;
										default:
											break;
									}
									elmSegmentForm += buatElemenHtml("fieldText", {
										label: "Keterangan",
										atribut:
											`name="keterangan" placeholder="Keterangan..."${autofocus} non_data`,
									});
									formIni.form('reset');
									removeRulesForm(formIni);
									segmentForm.html(elmSegmentForm);
									//satuanDropdown();
									let dropdown = new DropdownConstructor(".satuan.ui.dropdown");
									dropdown.satuan();
									addRulesForm(formIni);
									$("[rms]").mathbiila();
									switch (value) {
										case 'upah':
											break;
										case 'bahan':
										case 'quarry':
											break;
										case 'peralatan':
											break;
										case 'cuaca':
											formIni.find('div[name="segment"] .ui.dropdown').dropdown();
											let calendarDate = new CalendarConstructor(formIni.find('div[name="segment"] .ui.calendar.jam'));
											calendarDate.Type('time');
											calendarDate.runCalendar();

											break;
										default:
											break;
									}

								}
							});
							delay(function () {
								if (tbl === 'lap-harian_edit') {
									$('form[name="form_flyout"]').form("set values", {
										type: selectJenis
									})
									Object.keys(dataC).forEach((key) => {
										var elm = formIni.find(`[name="${key}"]`);
										if (typeof elm !== 'undefined') {
											//accounting.formatNumber(jumlah_harga,jumlah_harga.countDecimals(),".",",")
											let value = dataC[key];
											switch (key) {
												case 'value-diterima':
												case 'value-ditolak':
												case 'value-jumlah':
													value = parseFloat(dataC[key]);
													value = accounting.formatNumber(dataC[key], value.countDecimals(), ".", ",");
													break;
												default:
													break;
											}
											//elm = formIni.find(`[name="${key}"]`)
											$('form[name="form_flyout"]').form("set value", key, value)
										}
									});

								}
							}, 400);
							break;

						default:
							break;
					}
					break;
				case "copy":
					switch (tbl) {
						case 'copy_lap_harian':
							let calendarDateCopy = new CalendarConstructor(formIni.find('.ui.calendar.laporan'));
							calendarDateCopy.minDate = minDate;
							calendarDateCopy.maxDate = maxDate;
							calendarDateCopy.runCalendar();
							let tanggal = `${tanggalLapHarian.getFullYear()}-${tanggalLapHarian.getMonth() + 1
								}-${tanggalLapHarian.getDate()}`;
							formIni.form("set value", 'tanggal_copy', tanggal);
							break;
						case 'proyek':
							var angkaRandom = Math.floor(Math.random() * 10001);
							formIni.form("set values", {
								kd_proyek_copy: dataC[0],
								kode: `${dataC[0]}_copy${angkaRandom}`,
								uraian: `${dataC[1]}_copy`,
								tahun: `${dataC[2]}_copy`,
								keterangan: `${dataC[5]}_copy`,
							})
							break;

						default:
							break;
					}
					break;
				case "sbu":
					$(".ui.search.analisa").search({
						minCharacters: 3,
						maxResults: countRows(),
						searchDelay: 600,
						apiSettings: {
							method: "POST",
							url: "script/get_data",
							// passed via POST
							data: {
								jenis: jenis,
								tbl: tbl,
								cari: function () {
									//console.log($(this));
									return $(".ui.search.analisa").search("get value");
								},
								rows: countRows(),
								halaman: 1,
							},
						},
						fields: {
							results: [0].uraian,
							title: "uraian",
							description: "gabung",
							kode: "kode",
						},
						onSelect(result, response) {
							//console.log(result);
							let hasil = result[0];
							//console.log(response);
							//kode = result.kode;
							let harga_dasar = parseFloat(result.harga_satuan);
							let jumlah_op = (parseFloat(result.op) / 100) * harga_dasar;
							let harga_satuan = harga_dasar + jumlah_op;
							formIni.form("set values", {
								kode: result.kode,
								uraian: result.uraian,
								satuan: result.satuan,
								harga_dasar: harga_dasar,
								jumlah_op: jumlah_op,
								harga_satuan: harga_satuan,
							});
						},
					});
					break;
				case "analisa_quarry":
					switch (tbl) {
						case "import_quarry":
							$(".ui.dropdown.quarry").dropdown({
								saveRemoteData: false,//agar tidak menyimpan data di sessionStorage
								apiSettings: {
									// this url just returns a list of tags (with API response expected above)
									method: "POST",
									url: "script/get_data",
									data: {
										jenis: "analisa_quarry",
										tbl: "get",
										cari: "",
										rows: "all",
										halaman: 1,
									},
								},
								fields: {
									//results: results,
									value: "value",
									text: "name",
									description: "value",
								},
								filterRemoteData: true
							}); //.dropdown('set selected', 'jam');
							break;
						default:
							break;
					}
					break;
				case "harga_satuan":
					$(".ui.dropdown").dropdown("refresh");
					switch (tbl) {
						case "import_basic_price":
							break;
						case "edit":
							jalankanAjax = true;
						case "input":
							//satuanDropdown();
							let dropdownConstr = new DropdownConstructor(".satuan.ui.dropdown");
							dropdownConstr.satuan();
							break;
						default:
							break;
					}
					break;
				case "divisi":
					formIni.find('.ui.dropdown.lainnya').dropdown();
					switch (tbl) {
						case "y":
							break;
						case "w":
							break;
						default:
							break;
					}
					break;
				case "x":
					switch (tbl) {
						case "y":
							break;
						case "w":
							break;
						default:
							break;
					}
					break;
				default:
					break;
			}
		}
		//JALANKAN AJAX
		if (jalankanAjax) {
			loaderShow();
			suksesAjax["ajaxku"] = function (result) {
				if (result.success === true) {
					switch (jenis) {
						case "rekanan":
							switch (tbl) {
								case "edit":
									//
									formIni.form("set values", {
										nama_perusahaan: result.data.users.nama_perusahaan,
										alamat: result.data.users.alamat,
										npwp: result.data.users.npwp,
										direktur: result.data.users.direktur,
										no_ktp: result.data.users.no_ktp,
										alamat_dir: result.data.users.alamat_dir,
										no_akta_pendirian: result.data.users.no_akta_pendirian,
										tgl_akta_pendirian: result.data.users.tgl_akta_pendirian,
										lokasi_notaris_pendirian: result.data.users.lokasi_notaris_pendirian,
										nama_notaris_pendirian: result.data.users.nama_notaris_pendirian,
										dum_file: result.data.users.file,
										keterangan: result.data.users.keterangan,
									});
									// akta perubahan dan data_lain
									const notaris_perubahan = JSON.parse(result.data.users.notaris_perubahan);
									if (Object.keys(notaris_perubahan).length) {
										$.map(notaris_perubahan, function (val, i) {
											console.log(i)
											formIni.form("set value", i, val);
										})
									}
									const data_lain = JSON.parse(result.data.users.data_lain);
									if (Object.keys(data_lain).length) {
										$.map(data_lain, function (val, i) {
											formIni.form("set value", i, val);
										})
									}
									addRulesForm(formIni);
									break;
								default:
									break;
							}
							break;
						case "monev":
							switch (tbl) {
								case 'lap-harian_edit':
									break;
								case "import":
									break;
								case "edit":
								case "input":
									var waktuPelaksanaan = parseInt(result.data.dataProyek.MPP);
									var tglSpmk = result.data.dataProyek.tgl_spm;
									var tglSpmkConvert = tglSpmk.split("-");
									minDate = new Date(
										tglSpmkConvert[0],
										tglSpmkConvert[1] - 1,
										tglSpmkConvert[2].substr(0, 2)
									);
									maxDate = new Date(
										tglSpmkConvert[0],
										tglSpmkConvert[1] - 1,
										tglSpmkConvert[2].substr(0, 2)
									);
									maxDate.setDate(maxDate.getDate() + waktuPelaksanaan);
									let sumRealisasiFisik = 0;
									let sumRealisasiKeuangan = 0
									var maxValRealisasiFisik = 0;
									var maxValRealisasiKeuangan = 0;
									let calendarDateEditInput = new CalendarConstructor(".ui.calendar.laporan");
									calendarDateEditInput.maxDate = maxDate;
									calendarDateEditInput.minDate = minDate;
									calendarDateEditInput.runCalendar();

									$('form[name="form_flyout"] .ui.dropdown.uraian.selection').dropdown({
										className: {
											item: "item vertical",
										},
										values: result.results,
										clearable: true,
										placeholder: "Pilih Harga Satuan Pekerjaan (HSP)",
										onChange: function (value, text, $choice) {
											console.log($choice);
											let dataGetRealisasi = {
												jenis: "monev",
												tbl: "getRealisasi",
												kode: value,
											};
											suksesAjax["ajaxDropdown"] = function (result2) {
												if (result2.success === true) {
													let dataChoice = $($choice).find('span.description').text();
													console.log(dataChoice);
													let satuan = dataChoice.split(";")[2];
													let volume = dataChoice.split(";")[1];
													let jumlah_harga = dataChoice.split(";")[3];
													volume = volume;//accounting.formatNumber(volume, volume.countDecimals(), ".", ",");
													jumlah_harga = jumlah_harga;
													formIni.find('[name="satuan"]').text(satuan);
													formIni.form("set values", {
														volume: volume,
														jumlah_harga: jumlah_harga,
														realisasi_fisik_now: function () {
															volume = accounting.unformat(volume, ",");
															sumRealisasiFisik = result2.data.realisasiFisik;
															maxValRealisasiFisik = volume - sumRealisasiFisik;
															$('form[name="form_flyout"] input[name="realisasi_fisik"]').attr("max", maxValRealisasiFisik);
															if (sumRealisasiFisik === null) {
																return 0;
															} else {
																let realisasiFis = parseFloat(sumRealisasiFisik);
																console.log(realisasiFis);
																//console.log(realisasiFis.countDecimal());
																return accounting.formatNumber(realisasiFis, 2, ".", ",");
															}
														},
														realisasi_keu_now: function () {
															jumlah_harga = accounting.unformat(jumlah_harga, ",");
															sumRealisasiKeuangan = result2.data.realisasiKeuangan;
															maxValRealisasiKeuangan = jumlah_harga - sumRealisasiKeuangan;
															$('form[name="form_flyout"] input[name="realisasi_keu"]').attr("max", maxValRealisasiKeuangan);
															if (sumRealisasiKeuangan === null) {
																return 0;
															} else {
																let realisasiUang = parseFloat(sumRealisasiKeuangan);
																realisasiUang = accounting.formatNumber(realisasiUang, realisasiUang.countDecimals(), ".", ",");
																return realisasiUang;
															}
														},
													});

												}
											};
											runAjax(url, "POST", dataGetRealisasi, "Json", void 0, void 0, "ajaxDropdown");
										},
									});
									if (tbl === "edit") {
										let id_dropdown = result.results.rowMonev[0].id_rab;
										let realisasi_fisik = parseFloat(result.results.rowMonev[0].realisasi_fisik);
										let realisasi_keu = parseFloat(result.results.rowMonev[0].realisasi_keu);
										$('form[name="form_flyout"]').form("set values", {
											uraian: id_dropdown,
											tanggal: result.results.rowMonev[0].tanggal,
											realisasi_fisik: accounting.formatNumber(realisasi_fisik, realisasi_fisik.countDecimals(), ".", ","),
											realisasi_keu: accounting.formatNumber(realisasi_keu, realisasi_keu.countDecimals(), ".", ","),
											dum_file: result.results.rowMonev[0].file,
											keterangan: result.results.rowMonev[0].keterangan,
										});
										maxValRealisasiFisik = sumRealisasiFisik - realisasi_fisik;
										maxValRealisasiKeuangan = sumRealisasiKeuangan - realisasi_keu;
										//console.log(realisasiFisik)
										//console.log(realisasi_fisik)
										$('form[name="form_flyout"] input[name="realisasi_fisik"]').attr("max", maxValRealisasiFisik);
										$('form[name="form_flyout"] input[name="realisasi_keu"]').attr("max", maxValRealisasiKeuangan);
									}
									break;
								default:
									break;
							}
							break;
						case "peraturan":
							switch (tbl) {
								case "edit":
									formIni.form("set values", {
										uraian: result.data.users.uraian,
										keterangan: result.data.users.keterangan,
										tanggal: result.data.users.tanggal,
										type: result.data.users.type,
										status: result.data.users.status,
										dum_file: result.data.users.file,
									});
									addRulesForm(formIni);
									break;
								default:
									break;
							}
							break;
						case "schedule":
							switch (tbl) {
								case "edit":
									formIni.find('div.dropdown[name="dependent"]').dropdown({
										className: {
											item: "item vertical",
										},
										values: result.data.user_list,
										clearable: true,
									})
									formIni.form("set values", {
										kd_analisa: result.data.users.kd_analisa,
										uraian: result.data.users.uraian,
										durasi: result.data.users.durasi,
										mulai: result.data.users.mulai,
										// bobot: accounting.formatNumber(
										// 	result.data.users.bobot,
										// 	2,
										// 	".",
										// 	","
										// ),
										dependent: JSON.parse(result.data.users.dependent),
										keterangan: result.data.users.keterangan,
									});
									addRulesForm(formIni);
									break;
								default:
									break;
							}
							break;
						case "rab":
							switch (tbl) {
								case "edit":
									formIni.form("set values", { kode: result.data.users.kd_analisa, uraian: result.data.users.uraian, satuan: result.data.users.satuan.toLowerCase(), volume: accounting.formatNumber(result.data.users.volume, 4, ".", ","), harga_dasar: accounting.formatNumber(result.data.users.harga_dasar, 2, ".", ","), harga_satuan: accounting.formatNumber(result.data.users.harga_satuan, 2, ".", ","), jumlah_op: accounting.formatNumber(result.data.users.jumlah_op, 2, ".", ","), });
									addRulesForm(formIni);
									var bidang = result.data.bidang;
									if (bidang.length < 0) {
										bidang = "bm";
									}
									$(".ui.search.analisa").search({
										minCharacters: 3,
										maxResults: countRows(),
										searchDelay: 600,
										apiSettings: {
											method: "POST",
											url: "script/get_data",
											// passed via POST
											data: {
												jenis: 'sbu',//jenis: jenis,
												tbl: tbl,
												id_row: id_row,
												cari: function () {
													//console.log($(this));
													return $(".ui.search.analisa").search("get value");
												},
												rows: countRows(),
												halaman: 1,
											},
										},
										fields: {
											results: [0].uraian,
											title: "uraian",
											description: "gabung",
											kode: "kode",
										},
										onSelect(result, response) {
											//console.log(result);
											let hasil = result[0];
											//console.log(response);
											//kode = result.kode;
											let harga_dasar = parseFloat(result.harga_satuan);
											let jumlah_op =
												(parseFloat(result.op) / 100) * harga_dasar;
											let harga_satuan = harga_dasar + jumlah_op;
											formIni.form("set values", {
												kode: result.kode,
												uraian: result.uraian,
												satuan: result.satuan,
												harga_dasar: harga_dasar,
												jumlah_op: jumlah_op,
												harga_satuan: harga_satuan,
											});
										},
									});
									break;
								case "z":
									break;
								default:
									break;
							}
							break;
							break;
						case "proyek":
							switch (tbl) {
								case "cek_kode":
									break;
								case "tambah_proyek":
									break;
								case "edit":
									formIni.form("set values", {
										kode: result.data.kd_proyek,
										uraian: result.data.nama_proyek,
										tahun: result.data.tahun_anggaran,
										keterangan: result.data.keterangan,
									});
									addRulesForm(formIni);
									break;
								default:
									break;
							}
							break;
						case "satuan":
							switch (tbl) {
								case "edit":
									formIni.form("set values", {
										value: result.data.users.value,
										item: result.data.users.item,
										keterangan: result.data.users.keterangan,
										sebutan_lain: result.data.users.sebutan_lain,
									});
									addRulesForm(formIni);
									//dropdownConstr.setVal(result.data.users.value);
									break;
								default:
									break;
							}
							break;
						case "divisiBM":
						case "divisiCK":
						case "divisiSDA":
						case "divisi":
							switch (tbl) {
								case "edit":
									formIni.form("set values", {
										tahun: result.data.users.tahun,
										bidang: result.data.users.bidang,
										kode: result.data.users.kode,
										uraian: result.data.users.uraian,
										keterangan: result.data.users.keterangan,
									});
									addRulesForm(formIni);
									break;
								default:
									break;
							}
							break;
						case "analisa_quarry":
							switch (tbl) {
								case "import_quarry": //dropdown
									//formIni.find('[name="jenis"]').closest('.input').html(result.data.dropdown)
									addRulesForm(formIni);
									break;
								case "z":
									break;
								default:
									break;
							}
							break;
						case "harga_satuan":
							switch (tbl) {
								case "edit":
									formIni.form("set values", {
										jenis_basic_price: result.data.jenis,
										kode: result.data.kode,
										uraian: result.data.uraian,
										satuan: result.data.satuan.toLowerCase(),
										harga_satuan: accounting.formatNumber(
											result.data.harga_satuan,
											4,
											".",
											","
										),
										sumber_data: result.data.sumber_data,
										spesifikasi: result.data.spesifikasi,
										keterangan: result.data.keterangan,
									});
									addRulesForm(formIni);
									break;
								case "z":
									break;
								default:
									break;
							}
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
							break;
					}
					delay(function () {
						if (attrName === "flyout" && jalankanAjax === true) {
							$(".ui.flyout").flyout("toggle");
						}
					}, 200);
				}
				var hasKey = result.hasOwnProperty("error");
				if (hasKey) {
					loaderHide();
					hasKey = result.error.hasOwnProperty("message");
					if (hasKey) {
						showToast(result.error.message, {
							class: "success",
							icon: "check circle icon",
						});
					}
				} else {
					loaderHide();
				}
			};
			runAjax(url, "POST", data, "Json", undefined, undefined, "ajaxku");
		}
		delay(function () {
			if (attrName === "flyout" && jalankanAjax === false) {
				$(".ui.flyout").flyout("toggle");
			}
		}, 200);

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
		var ini = $(this);
		//Membaca File Excel dengan sheetJS
		let jenis = ini.attr("jns");
		var tbl = ini.attr("tbl");
		let formIni = $('form[name="form_modal"]');
		//console.log('jenis input file :' + jenis);
		if (tbl === "add_row_tabel") {
			var jsonData = [];
			var reader = new FileReader();
			reader.readAsArrayBuffer(e.target.files[0]);
			reader.onload = function (e) {
				var data = new Uint8Array(reader.result);
				var workbook = XLSX.read(data, { type: "array" });
				var first_sheet_name = workbook.SheetNames[0];
				jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[first_sheet_name], {
					header: 1,
				});
				//console.log(jsonData);
				//console.log(jsonData[0]);
				//console.log(JSON.stringify(jsonData));
				jsonData.forEach((value, key) => {
					//console.log(key + " " + value)
					//console.log(value.length)
					var jumlahData = value.length;
					var jumlahKolom = 8;
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
	//file type validation untuk file csv
	/*$('input[nama="file"]').change(function () {
	    
	});*/
	$(".ui.toggle.checkbox").checkbox({
		onChecked: function () {
			let ini = $(this);
			let name = ini.attr("name");
			//console.log(name);
			switch (name) {
				case "tampil_koordinat":
					let elm = ini
						.closest(".ui.grid.stackable")
						.find(".fluid.ten.wide.stretched.column .tab.active")
						.attr("data-tab");
					//console.log(elm);
					$(`a[data-tab="${elm}"]`).trigger("click");
					$(this).next().text("Sembunyikan Pop-up Koordinat");
					break;
				default:
					break;
			}
		},
		onUnchecked: function () {
			let ini = $(this);
			let name = ini.attr("name");
			//console.log(name);
			switch (name) {
				case "tampil_koordinat":
					let elm = ini
						.closest(".ui.grid.stackable")
						.find(".fluid.ten.wide.stretched.column .tab.active")
						.attr("data-tab");
					//console.log(elm);
					$(`a[data-tab="${elm}"]`).trigger("click");
					$(this).next().text("Tampilkan Pop-up Koordinat");
					break;
				default:
					break;
			}
		},
	});
	// check hapus tidak data lama
	$(".xlsx.toggle.checkbox").checkbox({
		onChecked: function () {
			$(this)
				.closest("form")
				.find(".ui.message li")
				.text("Mengimpor data file akan menghapus data lama");
		},
		onUnchecked: function () {
			$(this)
				.closest("form")
				.find(".ui.message li")
				.text("Mengimpor data file tanpa menghapus data lama");
		},
	});
	//=======================================
	//=======================PAGINATION======
	//=======================================
	$("body").on("click", 'a[name="page"]', function (e) {
		e.preventDefault();
		var ini = $(this);
		//<div class="ui active centered inline loader"></div>
		//ini.addClass('ui mini loading');
		var data_tab = ini.closest("[data-tab]").attr("data-tab");
		ini.append('<div class="ui active loader"></div>');
		halaman = ini.attr("hal");
		var ret = ini.attr("ret");
		var tbl = ini.attr("tbl");
		if (data_tab.length < 1) {
			data_tab = $("#cari_data").attr("name");
		}
		console.log(`data_tab : ${data_tab} ;tbl: ${tbl}`)
		switch (ret) {
			case "prev":
				halaman = halaman - 1;
				break;
			case "next":
				halaman = parseInt(halaman) + 1;
				break;
		}
		var run = 0;
		switch (tbl) {
			case "divisiBM":
			case "divisiCK":
			case "divisiSDA":
				console.log(tbl);
				$('a[data-tab="' + tbl + '"][tbl="get_list"]').first().trigger('click');
				break;
			default:
				$('a[data-tab="' + data_tab + '"][tbl="' + tbl + '"]').first().trigger('click');
		}
	});

	//untuk apa $(document).ready(function() { ... }); setara  $(function () {....}
	$(function () {
		$("div[name='dropdown_keg_dpa_p']").dropdown({
			fullTextSearch: true,
			clearable: true,
			onChange: function () {
				$('form[name="form_dpa_p"]').form("submit");
			},
		});
	});
	$("#countRow").dropdown({
		onChange: function (val, text, $selectedItem) {
			var ini = $(this);
			let jenis = ini.attr("name");
			if (jenis !== undefined && jenis.length > 0) {
				switch (jenis) {
					case "proyek":
					case "harga_satuan":
					case "informasi_umum":
					case "analisa_alat":
					case "analisa_quarry":
					case "analisa_bm":
					case "analisa_ck":
					case "analisa_sda":
					case "user":
					case "satuan":
					case "proyek":
						$('a[data-tab="' + jenis + '"]').click();
						break;
					default:
						$('a[data-tab="' + jenis + '"]').click();
						break;
				}
			}
		},
	});
	//============================
	//=========CARI DATA======
	//============================
	$(document).on("keyup", "#cari_data", function () {
		var ini = $(this);
		var elm_load = ini.closest("div.ui.input");
		let jenis = ini.attr("name");
		var txt = ini.val().trim();
		if (jenis !== undefined && jenis.length > 0) {
			elm_load.addClass("loading");
			delay(function () {
				switch (jenis) {
					case "proyek":
						$('a[data-tab="' + jenis + '"]').click();
						break;
					case "kd_akun":
						$('a[data-tab="get"][tbl="' + jenis + '"]').click();
						break;
					case "usulan_lain": //usulan
					case "renja_p":
						var val = $('form[name="form_renja"]').form("get value", "kd_keg");
						if (val === "all" || val === "rekap_prog_keg") {
							$('form[name="form_renja"]').form("submit");
						} else {
							var tbl_pakai = $('table[name="renja"]').find("tbody");
							var tr = tbl_pakai.children();
							tr.each(function () {
								var cari_td = $(this).find('td[klm="uraian"]');
								if (txt.length > 1) {
									if (
										cari_td.text().toLowerCase().includes(txt.toLowerCase())
									) {
										cari_td.css("background-color", "yellow");
									} else {
										cari_td.removeAttr("style");
									}
								} else {
									cari_td.removeAttr("style");
								}
								//console.log(cari_td.text());
							});
						}
						break;
					case 4:
						break;
					case 5:
						break;
					default:
						$('a[data-tab="' + jenis + '"]').click();
				}
				elm_load.removeClass("loading");
			}, 1000);
		}
	});
	//================================
	//======= EDIT CELL TABEL=========
	//================================
	var dataAwal_Cell = "";
	//data awal edit cell
	$(document).on("focus", "[klm]", function () {
		dataAwal_Cell = $(this).text().trim();
	});
	$(document).on("blur", "[klm]", function () {
		//console.log("masukmi blur");
		var ini = $(this);
		var dataAwal = dataAwal_Cell;
		var tbl = ini.attr("tbl");
		var klm = ini.attr("klm");
		var id = ini.attr("id_row");
		if (tbl === undefined) {
			tbl = ini.closest("tr").attr("tbl");
			if (tbl === undefined) {
				tbl = ini.closest("tbody").attr("tbl");
				if (tbl === undefined) {
					tbl = ini.closest("table").attr("tbl");
					if (tbl === undefined) {
						tbl = ini.closest("table").attr("name");
					}
				}
			}
		}
		if (id === undefined) {
			id = ini.closest("tr").attr("id_row");
			if (id === undefined) {
				id = ini.closest("table").attr("id_row");
			}
		}
		if (klm !== "" && parseInt(id) > 0 && tbl !== "") {
			setTimeout(function () {
				var text = ini.text().trim();
				var tbody_tbl = ini.closest("tbody");
				// tentukan jika ada atribut angka/rms maka ubah tabel dengan mengambil data
				if (
					ini.attr("angka") !== undefined ||
					ini.find("div").attr("rms") !== undefined
				) {
					text = accounting.unformat(text, ",");
					//console.log(`angka klm:${klm}, tbl:${tbl}, id:${id}`);
					if (
						Number.parseFloat(text) !==
						Number.parseFloat(accounting.unformat(dataAwal, ","))
					) {
						var tr = tbody_tbl.children();
						var data_row = new Object();
						//Verifikasi inputan
						switch (tbl) {
							case "analisa_alat_custom":
							case "analisa_quarry":
							case "analisa_sda":
							case "analisa_bm":
							case "analisa_ck":
								let sumRows = 0;
								tr.each(function (ii, elm) {
									var no_id = $(this).attr("id_row"); //sama dengan ii
									//console.log(ii);
									//console.log(elm);
									//console.log(no_id);
									var elementTD = elm.children;
									data_row[no_id] = {};
									Object.keys(elementTD).forEach((key) => {
										var element = $(elementTD[key]);
										var nama_kolom = element.attr("klm");
										if (typeof nama_kolom !== "undefined") {
											//console.log(nama_kolom);
											//console.log($(this).text().trim());
											var txt = element.text().trim();
											var tryNumbers = txt.replaceAll(",", "");
											tryNumbers = tryNumbers.replaceAll(".", "");
											if (
												containsOnlyNumbers(tryNumbers) ||
												nama_kolom === "harga_satuan" ||
												nama_kolom === "koefisien"
											) {
												text = accounting.unformat(text, ",");
												dataAwal = accounting.unformat(dataAwal, ",");
												data_row[no_id][nama_kolom] = accounting.unformat(
													element.text().trim(),
													","
												);
											} else {
												data_row[no_id][nama_kolom] = element.text().trim();
											}
										}
									});
									sumRows++;
									//tambhkan no_sortir
									data_row[no_id]["no_sortir"] = sumRows;
								});
								//console.log(data_row);
								break;
							case "analisa_alat":
								tr.children("td").each(function () {
									var nama_kolom = $(this).attr("klm");
									if (typeof nama_kolom !== "undefined") {
										//console.log(nama_kolom);
										//console.log($(this).text().trim());
										data_row[nama_kolom] = accounting.unformat(
											$(this).text().trim(),
											","
										);
									}
								});
								//console.log(data_row);
								break;
							case "y":
								break;
							case "z":
								break;
						}
						edit_data_return(tbl, id, text, klm, tbl, tbody_tbl, data_row);
					}
				} else {
					//console.log(`string klm:${klm}, tbl:${tbl}, id:${id}`);
					var tryNumbers = text.replaceAll(",", "");
					tryNumbers = tryNumbers.replaceAll(".", "");
					if (containsOnlyNumbers(tryNumbers)) {
						text = accounting.unformat(text, ",");
						dataAwal = accounting.unformat(dataAwal, ",");
					}
					if (text !== dataAwal) {
						edit_data(tbl, id, text, klm, "non");
					}
				}
			}, 350);
		}
	});
	// edit data angka
	function edit_data_return(
		tbl,
		id,
		text,
		klm,
		ubah,
		tbody_tbl,
		dataArray = {}
	) {
		var data = {
			jenis: "update_row",
			tbl: tbl,
			id: id,
			txt: text,
			klm: klm,
			dataArray: JSON.stringify(dataArray),
			ubah: ubah,
		};
		suksesAjax["ajaxku"] = function (result) {
			var dt_id = 0;
			var jumlah_total = 0;
			// masukkan data pada cell tabel renja rka
			var no_urut = 1;
			var responHasil = result.data; //JSON.parse(result.data) ;
			var hasKey = result.data.hasOwnProperty("dtupdate");
			if (hasKey) {
				responHasil = result.data.dtupdate;
			}
			console.log(responHasil);
			responHasil.forEach(function (element, index) {
				//console.log(element);
				var key;
				let nilaiEkementKey, row;
				hasKey = element.hasOwnProperty("id");
				//console.log(hasKey);
				if (hasKey) {
					let id = element.id;
					row = tbody_tbl.find('[id_row="' + id + '"]');
				}

				for (key in element) {
					//console.log(key + ' ' + element[key]);

					switch (tbl) {
						case "analisa_alat":
							//.countDecimals()
							nilaiEkementKey = parseFloat(element[key]);
							var jumlahDesimal = 2;
							if (nilaiEkementKey.countDecimals() > 2) {
								jumlahDesimal = nilaiEkementKey.countDecimals();
							}
							if (nilaiEkementKey > 0) {
								var elemenku = tbody_tbl
									.find('td[klm="' + key + '"]')
									.children();
								if (elemenku.length > 0) {
									elemenku.text(
										accounting.formatNumber(
											nilaiEkementKey,
											jumlahDesimal,
											".",
											","
										)
									);
								} else {
									tbody_tbl
										.find('td[klm="' + key + '"]')
										.text(
											accounting.formatNumber(
												nilaiEkementKey,
												jumlahDesimal,
												".",
												","
											)
										);
								}
								//var elm =tbody_tbl.find('td[klm="' + key + '"]').children().text(accounting.formatNumber(nilaiEkementKey, nilaiEkementKey.countDecimals(), '.', ','))
							}
							break;
						case "analisa_bm":
						case "analisa_quarry":
						case "analisa_alat_custom":
						case "analisa_sda":
						case "analisa_ck":
							//.countDecimals()
							//console.log(key + ' ' + element[key]);
							nilaiEkementKey = element[key];
							//console.log(nilaiEkementKey);
							if (isInt(nilaiEkementKey) || isFloat(nilaiEkementKey)) {
								//text = accounting.unformat(text, ",");
								nilaiEkementKey = parseFloat(element[key]);
								var jumlahDesimal = 2;
								if (nilaiEkementKey.countDecimals() > 2) {
									jumlahDesimal = nilaiEkementKey.countDecimals();
								}
								if (jumlahDesimal > 6) {
									jumlahDesimal = 6;
								}
								nilaiEkementKey = accounting.formatNumber(
									nilaiEkementKey,
									jumlahDesimal,
									".",
									","
								);
								//console.log(nilaiEkementKey);
							}
							var elemenku = row.find('td[klm="' + key + '"]').children();
							if (elemenku.length > 0) {
								elemenku.text(nilaiEkementKey);
							} else {
								row.find('td[klm="' + key + '"]').text(nilaiEkementKey);
							}
							break;
						default:
							break;
					}
				}
				//console.log(element[key]);
				//var elm = tbody_tbl.find('tr[id_row="' + id + '"] td[klm="' + key + '"]').text(accounting.formatNumber(element[key], 2, '.', ','))
				//console.log(elm);
			});
			var pesan = "<p>" + result.error.message + " </p> ";
			var kelasku = "success";
			if (result.success !== true) {
				kelasku = "warning";
			}
			showToast(pesan, {
				class: kelasku,
				icon: "check circle icon",
			});
		};
		runAjax(
			"script/edit_cell",
			"POST",
			data,
			"Json",
			undefined,
			undefined,
			"ajaxku"
		);
	}
	//==============================
	// fungsi edit data text di cell tabel
	function edit_data(tbl, id, text, klm, ubah) {
		var data = {
			jenis: "update_row",
			tbl: tbl,
			id: id,
			txt: text,
			klm: klm,
			ubah: ubah,
		};
		suksesAjax["ajaxku"] = function (result) {
			//console.log(typeof result);
			var pesan = "<p>" + result.error.message + " </p> ";
			var kelasku = "success";
			if (result.success === true) {
			} else {
				kelasku = "warning";
			}
			showToast(pesan, {
				class: kelasku,
				icon: "check circle icon",
			});
		};
		runAjax(
			"script/edit_cell",
			"POST",
			data,
			"Json",
			undefined,
			undefined,
			"ajaxku"
		);
	}
	//============================
	//================SLIDER======
	//============================
	$(".ui.font.slider").slider({
		min: 60,
		max: 160,
		start: 60,
		step: 5,
		onMove: function () {
			$(this).next().val($(this).slider("get value"));
		},
	});
});
//sortir value dalam object
//objs.sort( compare );
function compare(a, b) {//function compare(a, b,key) {
	if (a.no_sortir < b.no_sortir) {
		return -1;
	}
	if (a.no_sortir > b.no_sortir) {
		return 1;
	}
	return 0;
}


//menentukan bisa tidaknya angka
function containsOnlyNumbers(str) {
	return /^\d+$/.test(str);
}
//=====================
//==== FUNGSI =========
//=====================
//fungsi menu tanpa data-tab
function menuNonTab(elm) {
	elm.addClass("active").siblings().removeClass("active");
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
	callback
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
function addHtml(jenis, data = []) {
	//data bentuk object
	var htmlku = "";
	switch (jenis) {
		case "x":
			htmlku = "";
			break;
		case "x":
			htmlku = "";
			break;
		default:
			htmlku =
				'<tr class="ui warning center aligned"><td colspan="5" class="center aligned"><div class="ui icon header"><i class="red dont icon"></i> Data tidak ditemukan</div></td></tr>';
	}
	return htmlku;
}
//====MEMBUAT ELEMEN HTML================
//====contohdataElemen={atribut:'name="tambah" jns="harga_satuan" tbl="input"'}
//================================
function buatElemenHtml(namaElemen = "", dataElemen = {}) {
	var acceptData = "atribut" in dataElemen ? dataElemen.accept : ".pdf";
	var content = "content" in dataElemen ? dataElemen.content : "";
	var atributData = "atribut" in dataElemen ? dataElemen.atribut : "";
	var atributData2 = "atribut2" in dataElemen ? dataElemen.atribut2 : "";
	var atributLabel =
		"atributLabel" in dataElemen ? dataElemen.atributLabel : "";
	var kelasData = "kelas" in dataElemen ? dataElemen.kelas : "";
	var labelData = "label" in dataElemen ? dataElemen.label : "";
	var textDrpdown =
		"textDrpdown" in dataElemen ? dataElemen.textDrpdown : "Pilih...";
	var placeholderData =
		"placeholderData" in dataElemen ? dataElemen.placeholderData : "";
	var elemen1Data = "elemen1" in dataElemen ? dataElemen.elemen1 : "";
	var iconData = "icon" in dataElemen ? dataElemen.icon : "download icon";
	var icon = "icon" in dataElemen ? dataElemen.icon : "calendar";
	var posisi = "posisi" in dataElemen ? dataElemen.posisi : "left";
	var colorData = "color" in dataElemen ? dataElemen.color : "positive";
	var valueData = "value" in dataElemen ? dataElemen.value : "";
	var iconDataSeach = "icon" in dataElemen ? dataElemen.icon : "search icon";
	var txtLabelData = "txtLabel" in dataElemen ? dataElemen.txtLabel : "@";
	var dataArray = "dataArray" in dataElemen ? dataElemen.dataArray : []; //contoh untuk dropdown
	var dataArray2 = "dataArray2" in dataElemen ? dataElemen.dataArray2 : [[]]; //contoh buat dropdown yang ada deskripsi
	let jenisListDropdown =
		"jenisListDropdown" in dataElemen ? dataElemen.jenisListDropdown : "@"; //jenis dropdown[Selection,Search Selection,Clearable Selection,Multiple Selection,Multiple Search Selection,Description,Image,Actionable ,Columnar Menu]
	// var file
	var accept = "accept" in dataElemen ? dataElemen.accept : ".xlsx";
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
		case "fieldSearch":
			var elemen =
				'<div class="field"><label>' +
				labelData +
				'</label><div class="ui fluid category search ' +
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
				rowsData = dataArray[x];
				if (rowsData.length === 1) {
					dataValue = rowsData[0];
					txt = dataValue;
					elemen2 +=
						'<div class="item" data-value="' +
						dataValue +
						'">' +
						txt +
						"</div>";
				} else if (rowsData.length === 2) {
					dataValue = rowsData[0];
					txt = rowsData[1];
					elemen2 +=
						'<div class="item" data-value="' +
						dataValue +
						'">' +
						txt +
						"</div>";
				} else if (rowsData.length === 3) {
					//mempunyai deskripsi
					dataValue = rowsData[0];
					txt = rowsData[1];
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
//==============================================
//object jquery untuk generate input ke json
// var object = $('#testform').extractObject();
//==============================================
$.fn.extractObject = function (atribut = 'name') {
	var formValues = this.find('input, textarea, select');//$('input[type=text]');
	var obj = {};
	let i = 0;
	$.map(formValues, function (n, i) {
		const text = $(n).val();
		obj[$(n).attr(atribut)] = text;
		if (text.length <= 0) {
			return {};
		}
		i++;
	})
	return obj;
}


// $.fn.extractObject = function () {
// 	var accum = {};
// 	function add(accum, namev, value) {
// 		if (namev.length == 1)
// 			accum[namev[0]] = value;
// 		else {
// 			if (accum[namev[0]] == null)
// 				accum[namev[0]] = {};
// 			add(accum[namev[0]], namev.slice(1), value);
// 		}
// 	};
// 	this.find('input, textarea, select').each(function () {
// 		add(accum, $(this).attr('name').split('.'), $(this).val());
// 	});
// 	return accum;
// }
// function createJSONObject(){
//     var formValues = $('input[type=text]');
//     var obj = {};
//     $.map(formValues, function(n, i) {
//         obj[n.name] = $(n).val();
//     });
//     console.log(JSON.stringify(obj));
// }
// // ...
function cari(name = "") {
	$("#cari_data").attr("name", name);
	return $("#cari_data").val().trim();
}
function countRows() {
	return $("#countRow").dropdown("get value");
}
// fungsi mengganti text tanpa timpa child
//$(selector).textNodes().replaceWith('ok')
//$(selector).textNodes().first().replaceWith('ok')
jQuery.fn.textNodes = function () {
	return this.contents().filter(function () {
		return this.nodeType === Node.TEXT_NODE && this.nodeValue.trim() !== "";
	});
};
function is_KeyMath(evt) {
	return /[0-9]|\=|\+|\-|\/|\*|\%|\[|\]|\,/.test(
		String.fromCharCode(evt.which)
	);
}
function is_NumberKey(evt) {
	"use strict";
	var charCode = evt.which ? evt.which : event.keyCode;
	if (charCode !== 44 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		// 44=',' 46= '.'
		return false;
	} else {
		return true;
	}
}
//onkeypress="return rumus(event)
function desimal(evt) {
	//return /[0-9]/.test(String.fromCharCode(evt.which));
	var charCode = evt.which ? evt.which : event.keyCode;
	if (charCode !== 44 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		// 44=',' 46= '.'
		return false;
	} else {
		return true;
	}
}
//onkeypress="return rumus(event)
function angka(evt) {
	return /[0-9]/.test(String.fromCharCode(evt.which));
}
//onkeypress="return rumus(event)
function rumus(evt) {
	return /[0-9]|\=|\+|\-|\/|\*|\%|\[|\]|\,/.test(
		String.fromCharCode(evt.which)
	);
}
//==min max
//contoh html <input type="number" matInput min="1" max="99" #tempVar autocomplete="off" formControlName="noOfWeeks" (keydown)="handleKeydown($event)"/>
function handleKeyDown(e) {
	const typedValue = e.keyCode;
	if (typedValue < 48 && typedValue > 57) {
		// If the value is not a number, we skip the min/max comparison
		return;
	}
	const typedNumber = parseInt(e.key);
	const min = parseInt(e.target.min);
	const max = parseInt(e.target.max);
	const currentVal = parseInt(e.target.value) || "";
	const newVal = parseInt(typedNumber.toString() + currentVal.toString());
	if (newVal < min || newVal > max) {
		e.preventDefault();
		e.stopPropagation();
	}
}
//untuk menhitung dasar perkalian
function jumlah(evt, elm) {
	delay(function () {
		var tfoot = elm.closest("table").find("tfoot");
		var tbody = elm.closest("table").find("tbody");
		var tr = elm.closest("tr");
		var vol = accounting.unformat(
			tr.find('td[klm="volume"]').text().trim(),
			","
		);
		var harga = accounting.unformat(
			tr.find('td[klm="harga_satuan"]').text().trim(),
			","
		);
		var jumlah = parseFloat(vol) * parseFloat(harga);
		var elmJumlah = tr.find('td[klm="jumlah"]');
		elmJumlah.text(accounting.formatNumber(jumlah, 2, ".", ","));
		//jumlahkan semua jumlah
		var tr_array = tbody.find('td[klm="jumlah"]');
		var jumlahTotal = 0;
		tr_array.each(function () {
			var val = accounting.unformat($(this).text().trim(), ",");
			//console.log($(this).text());
			jumlahTotal += parseFloat(val);
		});
		//console.log(tfoot.find('th[klm="total"]'));
		tfoot
			.find('th[klm="total"]')
			.text(accounting.formatNumber(jumlahTotal, 2, ".", ","));
		return /[0-9]|\=|\+|\-|\/|\*|\%|\[|\]|\,/.test(
			String.fromCharCode(evt.which)
		);
	}, 300);
}
//============================
// insert di dropdown mutable
//inserDropdown($("div[name='periode_renstra']"), periode);
//============================
function inserDropdown(element, pilih) {
	if (element !== undefined) {
		var data = element.find(".menu").html();
		if (data.includes('<div class="item" data-value=')) {
			setTimeout(function () {
				element.dropdown("set selected", pilih);
				element.removeClass("loading");
			}, 100);
		} else {
			setTimeout(function () {
				inserDropdown(element, pilih);
			}, 100);
		}
	}
}
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
var delay = (function () {
	var timer = 0;
	return function (callback, ms) {
		clearTimeout(timer);
		timer = setTimeout(callback, ms);
	};
})();
/*
delay(function () {
}, 700);
*/
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
	settings.position = settings.position === undefined ? "top right" : settings.position;
	settings.class = settings.class === undefined ? "info" : settings.class; //warna
	settings.icon = settings.icon === undefined ? false : settings.icon;
	settings.showProgress = settings.showProgress === undefined ? "bottom" : settings.showProgress;
	settings.displayTime = settings.displayTime === undefined ? 4500 : settings.displayTime;//0 jika ingin tampil terus sebelum diklik
	settings.showMethod = settings.showMethod === undefined ? 'zoom' : settings.showMethod;
	settings.showDuration = settings.showDuration === undefined ? 1000 : settings.showDuration;
	settings.hideMethod = settings.hideMethod === undefined ? 'fade' : settings.hideMethod;
	settings.hideDuration = settings.hideDuration === undefined ? 1000 : settings.hideDuration;
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
			hideDuration: settings.hideDuration
		}
	});
}
function isInt(n) {
	return Number(n) === n && n % 1 === 0;
}

function isFloat(n) {
	return Number(n) === n && n % 1 !== 0;
}
// check angka input
function checkMinMax(sender) {
	let min = sender.min;
	console.log(min);
	let max = sender.max;
	let value = parseFloat(sender.value);
	if (value > max) {
		sender.value = accounting.formatNumber(max, 2, ".", ",");
	} else if (value < min) {
		sender.value = accounting.formatNumber(min, 2, ".", ",");
	}
}
function roundUp(num, precision) {
	precision = Math.pow(10, precision);
	return Math.ceil(num * precision) / precision;
}
