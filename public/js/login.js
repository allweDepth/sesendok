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

            const url = BASEURL + halamandok+"/masuk";
            console.log(url);
            console.log('disini js key=' + keyEncryption);
            var data = {
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
    
    //===================================
	//=========== class dropdown ========
	//===================================
	class DropdownConstructor {
		jenis = '';
		tbl = '';
		ajax = false;
		result_ajax = {};
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
					url: "script/register_akun",
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
		returnListOnChange(jenis = "list_dropdown", tbl = "satuan", minCharacters = 3) {
			let get = this.element.dropdown("get query");
			let elm = this.element;
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
					url: "script/register_akun",
					throttle: 600,
					//throttle: 1000,//delay perintah
					// passed via POST
					data: {
						jenis: jenis,
						tbl: tbl,
						cari: function (value) {
							return elm.dropdown("get query");
						},
						rows: '10', //"all",
						halaman: 1,
					}, fields: {
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
							cari: '',
							rows: 10,
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
		users(get) {
			get = this.element.dropdown("get query");
			this.element.dropdown({
				//$(".satuan.ui.dropdown").dropdown({
				apiSettings: {
					cache: false,
					// this url just returns a list of tags (with API response expected above)
					method: "POST",
					url: "script/register_akun",
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
});