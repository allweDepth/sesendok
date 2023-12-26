const halAwal = halamanDefault;
const enc = new Encryption();
$(document).ready(function () {
	"use strict";
	//remove session storage
	sessionStorage.clear()
	var halaman = 1;

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





	//sidebar toggle
	$(".ui.sidebar").sidebar({
		//context: $('.context.example')
		selector: { pusher: '.context.example.pusher' },
	}).sidebar('setting', 'transition', 'push').sidebar('attach events', "#toggle");

	$(".ui.accordion").accordion();
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
			on: 'hover',
			onShow: function (e) {
				(async () => {
					const datapost = {
						jenis: 'candaan',
						tbl: 'candaan'
					}
					const options = {
						method: "POST", // *GET, POST, PUT, DELETE, etc.
						// mode: "cors", // no-cors, *cors, same-origin
						// cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
						// credentials: "same-origin", // include, *same-origin, omit
						headers: {
							"Content-Type": 'application/json; charset=UTF-8',//'application/x-www-form-urlencoded; charset=UTF-8'//"application/json"
							// 'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: JSON.stringify(datapost)
					};
					try {
						const url = 'script/candaan';
						await fetch(url, options).then((response) => {
							return response.json();//return response.json();
						}).then((json) => {
							// console.log(json);
							// const obj = json;
							// const keys = Object.keys(obj)
							// const randIndex = Math.floor(Math.random() * keys.length)
							// const randKey = keys[randIndex]
							// const name = obj[randKey]
							$(this).find('.kata').html(`<p><span class="ui large text">${json.data}</span></p><br><div class="ui basic massive segment"><em data-emoji="angel" class="loading"></em><em data-emoji="blush" class="medium loading"></em><em data-emoji="grin" class="loading"></em></div>`);
						}).catch((error) => {
							console.log('Error: ', error)
						});
					} catch (error) {
						console.log('Error: ', error)
					}
				})();
				//$(this).dimmer({loaderText: 'Wait a second, please...'})
			}
		});
	//logout
	$("body").on("click", "[name='log_out']", function () {
		setTimeout(function () {
			window.location.href = "script/logout";
		}, 400);
	});

});
