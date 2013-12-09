$(function(){
    var prettify = false;
    $("pre").each(function() {
        $(this).addClass('prettyprint');
        prettify = true;
    });

    // si se encontro bloques de código se llama la función prettyPrint
    if ( prettify ) {
        $(document.createElement('link')).attr({
                href: "../../vistas/_plantillas/por_defecto/css/prettify.css",
                media: 'screen',
                rel: 'stylesheet'
        }).appendTo('head');
        $.getScript("../../vistas/_plantillas/por_defecto/js/prettify/prettify.js", function() {prettyPrint()});
    }
    //compartir en facebook
    $('.fb-comp').click(function(e){
        e.preventDefault();
        window.open($(this).attr('href'), 'Compartir en facebook', 'width=626,height=436');
    })
})