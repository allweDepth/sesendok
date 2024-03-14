var dok = '';
$(document).ready(function () {
	"use strict";
	// fix menu when passed
	$('.masthead')
		.visibility({
			once: false,
			onBottomPassed: function () {
				$('.fixed.menu').transition('fade in');
			},
			onBottomPassedReverse: function () {
				$('.fixed.menu').transition('fade out');
			}
		})
		;
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
	// create sidebar and attach to menu open
	$('.ui.sidebar')
		.sidebar('attach events', '.toc.item')
		;
	$(".ui.accordion").accordion();
	$(".ui.accordion.menu_utama").accordion({
		exclusive: false
	});
	//Sticking to Own Context
	$('.ui.sticky')
		.sticky({
			context: '.pusher',
			pushing: true
		});

	var keyEncryption = halamanDefault;
	let encryption = new Encryption();

	function modal_notif(kop, conten) {

		$('#kop_notifikasi').html(kop);
		$('#conten_notifikasi').html('<p>' + conten + '</p>');
		$('.ui.basic.modal.info').modal('show');
	}
	$(document).on('click', "a[name='modal']", function (event) {
		event.preventDefault();
		$('.ui.modal.login')
			.modal('show')
			;
	})
	$(document).on('click', "a[name='modal-register']", function (event) {
		event.preventDefault();
		$('.ui.modal.register')
			.modal('show')
			;
	})
	$(document).on('click', "button[name='login']", function (event) {
		event.preventDefault();
		dok = $(this).attr('value');
		$('.ui.form').form('submit');
		return false;
	})
	$('.ui.form').form({
		fields: {
			username: {
				identifier: 'username',
				rules: [{
					type: 'empty',
					prompt: 'Please enter your username or e-mail'
				}]
			},
			password: {
				identifier: 'password',
				rules: [{
					type: 'empty',
					prompt: 'Please enter your password'
				}]
			}
		},
		onSuccess: function (event) {
			event.preventDefault();
			//$(this).serialize();

			var dataku = $(this).serializeArray();
			var username = dataku[0].value;
			var password = dataku[1].value;
			username = encryption.encrypt(username, keyEncryption);
			password = encryption.encrypt(password, keyEncryption);

			const url = BASEURL + halamandok + "/masuk";
			console.log(url);
			console.log('disini js key=' + keyEncryption);
			let data = {
				username: username,
				password: password,
				login: 'login',
				dok: 'dok',
				cry: true
			}
			$.ajax({
				type: "POST",
				data: data,
				url: url,
				dataType: 'JSon',
				success: function (result) {
					console.log("result = " + result);
					//$(this).reset(); alert result;
					if (parseInt(result) == 1) {
						window.location.href = BASEURL + "home"; //admin
					} else if (parseInt(result) == 2) {
						window.location.href = BASEURL + "home";
					} else if (parseInt(result) == 6) {
						modal_notif(
							'<i class="info icon"></i>Akun belum aktif',
							'Hubungi admin untuk mengaktifkan akun anda'
						);
					} else if (parseInt(result) == 7) {
						modal_notif(
							'<i class="info icon"></i>Gagal Login',
							'Kombinasi akun anda salah'
						);
					}
				},
				error: function (jqXHR, status, err) {
					//loaderHide();
					// console.log(jqXHR);
					// console.log(status);
					// modal_notif('<i class="info icon"></i>' + status, jqXHR);
				}
			});
			(async () => {
			})();
		}
	});
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
							
							break;
						// =================
						// UNTUK FORM FLYOUT
						// =================
						case "form_flyout":
							
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
							let kelasToast = "success";
							if (result.success === true) {
								
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
							MyForm.form('reset')
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
					return false;
				},
				onFailure: function (e) {
					loaderHide();
					return false;
				},
			});
		}
	}
	//===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		jenis = '';
		tbl = '';
		ajax = false;
		result_ajax = {};
		url = BASEURL + "/register/wilayah";
		constructor(element) {
			this.element = $(element); //element;
			// this.methodConstructor = new MethodConstructor();
		}
		returnList(jenis = "list_dropdown", tbl = "satuan", minCharacters = 3) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			this.element.dropdown({
				minCharacters: minCharacters,
				maxResults: countRows(),
				searchDelay: 600,
				throttle: 600,
				cache: false,
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					cache: false,
					method: "POST",
					url: "script/register/wilayah",
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: {
						jenis: jenis,
						tbl: tbl,
						cari: function (value) {
							return elm.dropdown("get query");
						},
						rows: countRows(), //"all",
						halaman: 1,
					}, fields: {
						results: "results",
					},
					// filterRemoteData: true,
				},

				// filterRemoteData: true,
				// saveRemoteData: false,
			});
		}
		returnListOnChange(jenis = "list_dropdown", tbl = "satuan", minCharacters = 3, allField = {}) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
			let dataSend = Object.assign({
				jenis: jenis,
				tbl: tbl,
				cari: function (value) {
					return elm.dropdown("get query");
				},
				rows: '10', //"all",
				halaman: 1
			}, allField);
			switch (jenis) {
				case 'list_dropdown':
					switch (tbl) {
						case 'wilayah':
							this.url = BASEURL + "/register/wilayah";
							break;
						case 'organisasi':
							this.url = BASEURL + "/register/organisasi";
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
			this.element.dropdown({
				minCharacters: minCharacters,
				maxResults: 10,
				searchDelay: 600,
				throttle: 600,
				cache: false,
				apiSettings: {
					// this url just returns a list of tags (with API response expected above)
					cache: false,
					method: "POST",
					url: this.url,
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: dataSend,
					fields: {
						results: "results",
					},
					// filterRemoteData: true,
				},
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					let ajaxSend = false;
					switch (jenis) {
						case 'list_dropdown':
							switch (tbl) {
								case 'wilayah'://tujuan sasaran renstra
									ajaxSend = true;
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
							jenis: jenis,
							tbl: tbl
						};
						let url = 'script/register_akun';
						let cryptos = false;
						switch (jenis) {
							case 'list_dropdown':
								switch (tbl) {
									case 'wilayah'://tujuan sasaran renstra
										let elementDrop = $(`.ui.organisasi.dropdown.ajx`);
										data.kd_wilayah = $('.ui.wilayah.dropdown.ajx').dropdown('get value');
										url = BASEURL + "/register/organisasi";
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
						suksesAjax["ajaxku"] = function (result) {
							let kelasToast = "success";
							if (result.success === true) {
								switch (jenis) {
									case 'list_dropdown':
										switch (tbl) {
											case 'wilayah'://tujuan sasaran renstra
												$(`.ui.organisasi.dropdown.ajx`).dropdown({values:result.results});
												
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
		onChange(jenis = "list_dropdown", tbl = "satuan", ajax = false) {
			let ajaxSend = ajax;
			this.element.dropdown({
				onChange: function (value, text, $choice) {
					let dataChoice = $($choice).find('span.description').text();
					switch (jenis) {
						case 'getJsonRows':
							switch (tbl) {
								case 'tujuan_renstra'://tujuan sasaran renstra

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
						let url = 'script/register_akun';
						let cryptos = false;

						suksesAjax["ajaxku"] = function (result) {
							var kelasToast = "success";
							if (result.success === true) {
								switch (jenis) {
									case 'getJsonRows':

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
		valuesDropdown(values) {
			this.element.dropdown({
				values: values
			})
		}
	}
	let dropdownWilayah = new DropdownConstructor('.ui.wilayah.dropdown.ajx');
	dropdownWilayah.returnListOnChange("list_dropdown", "wilayah", 3);


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
});