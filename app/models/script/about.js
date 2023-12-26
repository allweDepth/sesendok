$(document).ready(function () {
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
});