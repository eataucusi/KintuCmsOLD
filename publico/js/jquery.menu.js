(function($){
    $.fn.menu = function(opciones){
        var defecto = {down: 400, up: 200};
        defecto = $.extend({}, defecto, opciones);
        var $nivel1 = $(this).find('> li.expansible');
        var $nivel2 = $nivel1.find('> ul > li.expansible');
        $nivel1.hover(function(){
            $(this).find('> ul:not(:animated)').slideDown(defecto.down);
        }, function(){
            $(this).find('> ul').slideUp(defecto.up)
        });
        $nivel2.hover(function(){
            $(this).find('> ul:not(:animated)').slideDown(defecto.down)
        }, function(){
            $(this).find('> ul').slideUp(defecto.up)
        });
    }
})(jQuery);