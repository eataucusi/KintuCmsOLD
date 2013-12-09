$(function(){  
    $('.ancla').click(function(e){                    
        var enlace = $(this).attr('href');
        enlace = enlace.split('#');
        enlace = '#' + enlace[1];
        if ($(enlace).length > 0) {
            $('html, body').animate({scrollTop: $(enlace).offset().top - 10}, 600, 'swing');
            e.preventDefault();
        }
    });
    
    var $slider = $('#waylis_slides');
    if($slider.length != 0){
        $slider.Wslider();
    } 
    
    $(window).scroll(function(){
        if ($(this).scrollTop() > 200){
            $('#ir-arriba').fadeIn();
        } else {
            $('#ir-arriba').fadeOut();
        }
    });
    
   var $menu = $('#menu  .menu > ul');
   if($menu.length != 0){
        $menu.menu({up:100});
    }    
    
    
    $('form.form').submit(function(){
        $('#cargando').fadeIn(500);
        $('#marco').slideDown(500);        
    });
    
    $('#mi-perfil').hover(function(){
        $('#mi-perfil .alert:not(:animated)').slideDown(400);
    }, function (){
        $('#mi-perfil .alert').slideUp(300);
    }); 
})