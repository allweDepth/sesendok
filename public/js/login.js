var dok = '';
$(document).ready(function () {
    "use strict";
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
    $(document).on('click', "div[name='login']", function (event) {
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

            const url = BASEURL + "Login/masuk";
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
                    console.log(jqXHR);
                    modal_notif('<i class="info icon"></i>' + status, jqXHR);
                }
            });
            (async () => {
            })();
        }
    });
});