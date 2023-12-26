// JavaScript Document
$(document).ready(function () {
    'use strict';
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $.fn.api.settings.api = {
        'cek akun': 'script/register_akun.php',
        'create user': 'script/register_akun.php',
    };
    let encryption = new Encryption();
    $('input[name="username"],input[name="email"]').keyup(function () {
        var txt = ($(this).val()).trim();
        var jumlahKarakter = txt.length;
        let klm = $(this).attr('name');
        delay(function () {
            if (jumlahKarakter >= 5) {
                $.ajax({
                    type: 'POST',
                    data: {
                        jenis: encryption.encrypt('cari_username', keyEncryption),
                        klm: encryption.encrypt(klm, keyEncryption),
                        search: encryption.encrypt(txt, keyEncryption),
                        cry: true
                    },
                    url: 'script/register_akun',
                    dataType: 'JSon'
                }).done(function (result) {
                    $('.ui.error.message').html(result.data);
                    $('.ui.error.message').addClass('icon');
                    $('.ui.error.message').addClass('visible');
                });

            } else {
                $('.ui.error.message').html('data kurang');
            }
        }, 1000);
    });
    /* $('input[name="username"]').keyup(function () {
         var txt = ($(this).val()).trim();
         //var jumlahKarakter = txt.length;
         
         $('input[name="username"]').api({
             url: "script/register_akun.php",
             method: 'POST',
             //serializeForm: true,
             data: {
                 jenis: 'cari_username'
             },
             dataType: 'json',
             beforeSend: function (settings) {
                 // cancel request if no id
                 console.log(settings);
                 settings.data.search = txt;
                 return settings;
                 
             },
             onSuccess: function (result) {
                 $('.ui.error.message').html(result);
                 $('.ui.error.message').addClass('icon');
                 $('.ui.error.message').addClass('visible');
             },
             onFailure: function (jqXHR, textStatus, err) {
                 alert('<i class="info icon"></i>' + textStatus, err);
             }
         });
     });*/
    
    $('.ui.form').form({
        fields: {
            username: {
                identifier: 'username',
                rules: [{
                    type: 'empty',
                    prompt: 'Silahkan isi username'
                }, {
                    type: 'minLength[8]',
                    prompt: 'username minimal 8 karakter'
                }]
            },
            email: {
                identifier: 'email',
                rules: [{
                    type: 'empty',
                    prompt: 'Silahkan isi e-mail'
                }, {
                    type: 'email',
                    prompt: 'Please enter a valid e-mail'
                }]
            },
            nama: {
                identifier: 'nama',
                rules: [{
                    type: 'empty',
                    prompt: 'Silahkan isi nama lengkap anda'
                }, {
                    type: 'minLength[4]',
                    prompt: 'Nama minimal 4 karakter'
                }]
            },
            kontak_person: {
                identifier: 'kontak_person',
                rules: [{
                    type: 'empty',
                    prompt: 'silahkan isi kontak person anda'
                }]
            },
            password: {
                identifier: 'password',
                rules: [{
                    type: 'empty',
                    prompt: 'silahkan isi password'
                }, {
                    type: 'minLength[8]',
                    prompt: 'password harus minimal 8 karakter'
                }]
            },
            organisasi: {
                identifier: 'organisasi',
                rules: [{
                    type: 'empty',
                    prompt: 'silahkan isi organisasi'
                }]
            },
            setuju: {
                identifier: 'setuju',
                rules: [{
                    type: 'checked',
                    prompt: 'Setujui penggunaan aplikasi'
                }]
            }
        }, onSuccess: function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.set('register', 'register');
            formData.set('setuju', 'setuju');
            console.log(formData);
            formData.forEach((value, key) => {
                if (value.toString().length > 0) {
                    formData.set(key, encryption.encrypt(value.toString(), keyEncryption));
                }
            });
            formData.set('cry', true);
            $.post({
                type: "POST", // karna tanpa data yg dikirim 
                data: formData, // ambil semua data di form
                url: "script/register_akun",
                dataType: 'Json',
                contentType: false,    //Required
                cache: false,          //Required
                processData: false,    //Required
                success: function (result) {
                    //console.log( result );
                    if (parseInt(result.data) === 1) {
                        window.location.href = "login";
                    } else if (parseInt(result) === 2) {
                        //window.location.href="index.php";    
                    }
                }
            });
        }
    });
    /*.api({
        action: 'create user',
        method: 'POST',
        dataType: 'JSON',
        serializeForm: true,
        data: {
            register: 'register',
            setuju: 'setuju'
        },
        
        // arbitrary POST/GET same across all requests
        //data:new FormData(this),
        // modify data PER element in callback
        /*
        beforeSend: function (settings) {
            // form data is editable in before send
            console.log(settings);
            console.log(settings.data);
            console.log(settings.data.username);
            if (settings.data.register == undefined) {
                //settings.data.register = 'register';
                //settings.data.push({name: 'register', value: 'register'});
                //data[data.length] = { name: "username", value: "The Username" };
            }
            // open console to inspect object
            console.log(settings.data);
            return settings;
        },*/
    /*
    onSuccess: function (response, element) {
        if (parseInt(response) === 1) {
            window.location.href = "login.html";
        }
    },
    onFailure: function (jqXHR, textStatus, err) {
        console.log(jqXHR)
        console.log(textStatus)
        console.log(err)
        alert(textStatus, err);
    }
});*/
    $('.ui.dropdown').dropdown();
});