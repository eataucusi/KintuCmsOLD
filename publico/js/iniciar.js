$(function() {
    $('.ancla').click(function(e) {
        var enlace = $(this).attr('href');
        enlace = enlace.split('#');
        enlace = '#' + enlace[1];
        if ($(enlace).length > 0) {
            $('html, body').animate({scrollTop: $(enlace).offset().top - 10}, 600, 'swing');
            e.preventDefault();
        }
    });

    var $slider = $('#waylis_slides');
    if ($slider.length != 0) {
        $slider.Wslider();
    }

    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('#ir-arriba').fadeIn();
        } else {
            $('#ir-arriba').fadeOut();
        }
    });

    var $menu = $('#menu  .menu > ul');
    if ($menu.length != 0) {
        $menu.menu({up: 100});
    }


    $('form.form').submit(function() {
        $('#cargando').fadeIn(500);
        $('#marco').slideDown(500);
    });

    $('#mi-perfil').hover(function() {
        $('#mi-perfil .alert:not(:animated)').slideDown(400);
    }, function() {
        $('#mi-perfil .alert').slideUp(300);
    });

    //compartir en facebook
    $('.fb-comp').click(function(e) {
        e.preventDefault();
        window.open($(this).attr('href'), 'Compartir en facebook', 'width=626,height=436');
    });
    //prety
    var prettify = false;
    $("pre").each(function() {
        $(this).addClass('prettyprint linenums');
        prettify = true;
    });

    // si se encontro bloques de código se llama la función prettyPrint
    if (prettify) {
        $(document.createElement('link')).attr({
                href: "publico/css/prettify.css",
                media: 'screen',
                rel: 'stylesheet'
        }).appendTo('head');
        $.getScript("publico/js/prettify/prettify.js",function(){prettyPrint()});
    }
})